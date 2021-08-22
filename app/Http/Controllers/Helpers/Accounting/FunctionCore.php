<?php

namespace App\Http\Controllers\Helpers\Accounting;

use Modules\Finance\Entities\Entries;

class  FunctionCore {

    /**
     * This function returns the ledger or group name with code if present
     */
    function toCodeWithName($code, $name) {
        if (empty($code)) {
            return $name;
        } else {
            return '[' . $code . '] ' . $name;
        }
    }

    /**
     * Perform a calculate with Debit and Credit Values
     *
     * @param1 float number 1
     * @param2 char number 1 debit or credit
     * @param3 float number 2
     * @param4 float number 2 debit or credit
     * @return array() result of the operation
     */
    function calculate_withdc($param1, $param1_dc, $param2, $param2_dc) {
        $result = 0;
        $result_dc = 'D';

        if ($param1_dc == 'D' && $param2_dc == 'D') {
            $result = $this->calculate($param1, $param2, '+');
            $result_dc = 'D';
        } else if ($param1_dc == 'C' && $param2_dc == 'C') {
            $result = $this->calculate($param1, $param2, '+');
            $result_dc = 'C';
        } else {
            if ($this->calculate($param1, $param2, '>')) {
                $result = $this->calculate($param1, $param2, '-');
                $result_dc = $param1_dc;
            } else {
                $result = $this->calculate($param2, $param1, '-');
                $result_dc = $param2_dc;
            }
        }

        return array('amount' => $result, 'dc' => $result_dc);
    }



    function calculate($param1 = 0, $param2 = 0, $op = '') {

        $decimal_places = 2;

        if (extension_loaded('bcmath')) {
            switch ($op)
            {
                case '+':
                    return bcadd($param1, $param2, $decimal_places);
                    break;
                case '-':
                    return bcsub($param1, $param2, $decimal_places);
                    break;
                case '==':
                    if (bccomp($param1, $param2, $decimal_places) == 0) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                    break;
                case '!=':
                    if (bccomp($param1, $param2, $decimal_places) == 0) {
                        return FALSE;
                    } else {
                        return TRUE;
                    }
                    break;
                case '<':
                    if (bccomp($param1, $param2, $decimal_places) == -1) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                    break;
                case '>':
                    if (bccomp($param1, $param2, $decimal_places) == 1) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                    break;
                case '>=':
                    $temp = bccomp($param1, $param2, $decimal_places);
                    if ($temp == 1 || $temp == 0) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                    break;
                case 'n':
                    return bcmul($param1, -1, $decimal_places);
                    break;
                default:
                    die();
                    break;
            }
        } else {
            $result = 0;

            if ($decimal_places == 2) {
                $param1 = $param1 * 100;
                $param2 = $param2 * 100;
            } else if ($decimal_places == 3) {
                $param1 = $param1 * 1000;
                $param2 = $param2 * 1000;
            }

            $param1 = (int)round($param1, 0);
            $param2 = (int)round($param2, 0);
            switch ($op)
            {
                case '+':
                    $result = $param1 + $param2;
                    break;
                case '-':
                    $result = $param1 - $param2;
                    break;
                case '==':
                    if ($param1 == $param2) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                    break;
                case '!=':
                    if ($param1 != $param2) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                    break;
                case '<':
                    if ($param1 < $param2) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                    break;
                case '>':
                    if ($param1 > $param2) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                    break;
                case '>=':
                    if ($param1 >= $param2) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                    break;
                case 'n':
                    $result = -$param1;
                    break;
                default:
                    die();
                    break;
            }

            if ($decimal_places == 2) {
                $result = $result/100;
            } else if ($decimal_places == 3) {
                $result = $result/100;
            }

            return $result;
        }
    }


    function toCurrency($dc, $amount) {

        $decimal_places = 2;

        if ($this->calculate($amount, 0, '==')) {
            return $this->curreny_format(number_format(0, $decimal_places, '.', ''));
        }

        if ($dc == 'D') {
            if ($this->calculate($amount, 0, '>')) {
                return 'Dr ' . $this->curreny_format(number_format($amount, $decimal_places, '.', ''));
            } else {
                return 'Cr ' . $this->curreny_format(number_format($this->calculate($amount, 0, 'n'), $decimal_places, '.', ''));
            }
        } else if ($dc == 'C') {
            if ($this->calculate($amount, 0, '>')) {
                return 'Cr ' . $this->curreny_format(number_format($amount, $decimal_places, '.', ''));
            } else {
                return 'Dr ' . $this->curreny_format(number_format($this->calculate($amount, 0, 'n'), $decimal_places, '.', ''));
            }
        } else if ($dc == 'X') {
            /* Dr for positive and Cr for negative value */
            if ($this->calculate($amount, 0, '>')) {
                return 'Dr ' . $this->curreny_format(number_format($amount, $decimal_places, '.', ''));
            } else {
                return 'Cr ' . $this->curreny_format(number_format($this->calculate($amount, 0, 'n'), $decimal_places, '.', ''));
            }
        } else {
            return $this->curreny_format(number_format($amount, $decimal_places, '.', ''));
        }
        return lang('search_views_amounts_td_error');
    }


    // currency format

    function curreny_format($input) {
        switch ('##,###.##') {
            case 'none':
                return $input;
            case '##,###.##':
                return $this->_currency_2_3_style($input);
                break;
            case '##,##.##':
                return $this->_currency_2_2_style($input);
                break;
            case "###,###.##":
                return $this->_currency_3_3_style($input);
                break;
            default:
                die("Invalid curreny format selected.");
        }
    }


    /*********************** ##,###.## FORMAT ***********************/
    function _currency_2_3_style($num)
    {
        $decimal_places = 2;


        $pos = strpos((string)$num, ".");
        if ($pos === false) {
            if ($decimal_places == 2) {
                $decimalpart = "00";
            } else {
                $decimalpart = "000";
            }
        } else {
            $decimalpart = substr($num, $pos + 1, $decimal_places);
            $num = substr($num, 0, $pos);
        }

        if (strlen($num) > 3) {
            $last3digits = substr($num, -3);
            $numexceptlastdigits = substr($num, 0, -3 );
            $formatted = $this->_currency_2_3_style_makecomma($numexceptlastdigits);
            $stringtoreturn = $formatted . "," . $last3digits . "." . $decimalpart ;
        } elseif (strlen($num) <= 3) {
            $stringtoreturn = $num . "." . $decimalpart;
        }

        if (substr($stringtoreturn, 0, 2) == "-,") {
            $stringtoreturn = "-" . substr($stringtoreturn, 2);
        }
        return $stringtoreturn;
    }

    function _currency_2_3_style_makecomma($input)
    {
        if (strlen($input) <= 2) {
            return $input;
        }
        $length = substr($input, 0, strlen($input) - 2);
        $formatted_input = $this->_currency_2_3_style_makecomma($length) . "," . substr($input, -2);
        return $formatted_input;
    }

    /*********************** ##,##.## FORMAT ***********************/
    function _currency_2_2_style($num)
    {
        $decimal_places = 2;


        $pos = strpos((string)$num, ".");
        if ($pos === false) {
            if ($decimal_places == 2) {
                $decimalpart = "00";
            } else {
                $decimalpart = "000";
            }
        } else {
            $decimalpart = substr($num, $pos + 1, $decimal_places);
            $num = substr($num, 0, $pos);
        }

        if (strlen($num) > 2) {
            $last2digits = substr($num, -2);
            $numexceptlastdigits = substr($num, 0, -2);
            $formatted = _currency_2_2_style_makecomma($numexceptlastdigits);
            $stringtoreturn = $formatted . "," . $last2digits . "." . $decimalpart;
        } elseif (strlen($num) <= 2) {
            $stringtoreturn = $num . "." . $decimalpart ;
        }

        if (substr($stringtoreturn, 0, 2) == "-,") {
            $stringtoreturn = "-" . substr($stringtoreturn, 2);
        }
        return $stringtoreturn;
    }

    function _currency_2_2_style_makecomma($input)
    {
        if (strlen($input) <= 2) {
            return $input;
        }
        $length = substr($input, 0, strlen($input) - 2);
        $formatted_input = _currency_2_2_style_makecomma($length) . "," . substr($input, -2);
        return $formatted_input;
    }

    /*********************** ###,###.## FORMAT ***********************/
    function _currency_3_3_style($num)
    {
        $decimal_places = $this->_ci->mAccountSettings->decimal_places;
        return number_format($num,$decimal_places,'.',',');
    }


    function entryLedgers($id) {
        /* Load the Entry model */
        $Entry = new Entries;
        return $Entry->entryLedgers($id);
    }




}