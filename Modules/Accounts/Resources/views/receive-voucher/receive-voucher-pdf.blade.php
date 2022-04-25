<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Receive Voucher</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> --}}
    <style>
        .clearfix {
            overflow: auto;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        img {
            width: 100%;
        }

        .header {
            border-bottom: 1px solid #f1f1f1;
            padding: 10px 0;
        }

        .logo {
            width: 16%;
            float: left;
        }

        .headline {
            width: 40%;
            float: right;
            padding: 0 20px;
            text-align: right;
        }

        table,
        td,
        th {
            border: 1px solid black;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .top-left-table {
            width: 400px;
            float: left;
        }

        .top-right-table {
            width: 200px;
            float: right;
            overflow: hidden;
        }

        .text-center {
            text-align: center;
        }

        .signature {
            width: 18%;
            /* float: left; */
            margin: 0 5.4px;
            display: inline-block;
            height: 250px;
            height: auto;
            vertical-align: top;
        }
       


        .signature p {
            /* border-top: 1px solid #f1f1f1; */
            margin: 0;
            font-size: 12px;
        }
        .signotry_img{
            height: 60px;
            widows: 100%;
            text-align: center;
        }
        .signotry_img img{
            height: 100%;
            widows: 100%;
            overflow: hidden;
        }

        .footer {
            overflow: auto;
            margin-top: 100px;
        }

    </style>
</head>

<body>
    <div class="header clearfix">
        <div class="logo">
            <img src="{{ public_path('assets/users/images/' . $data['formData']['institute_logo']) }}" alt="">
            {{-- <img src="{{ asset('assets/users/images/'.$institute->logo) }}" alt=""> --}}
        </div>
        <div class="headline">
            <h5>{{ $data['formData']['institute_name'] }}</h5>
            <p>{{ $data['formData']['institute_address1'] }}</p>
            <p>{{ $data['formData']['institute_address2'] }}</p>
        </div>
    </div>
    <div class="content clearfix">

        <h3 class="text-center">Receive Voucher</h3>

        <div class="clearfix">
            <div class="top-left-table">
                <table>
                    <tbody>
                        <tr>
                            <td><b>Campus:</b></td>
                            <td>{{ $data['formData']['campus'] }}</td>
                        </tr>
                        <tr>
                            <td><b>Voucher No:</b></td>
                            <td>{{ $data['formData']['voucher_no'] }}</td>
                        </tr>
                        <tr>
                            <td><b>Reference No:</b></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="top-right-table">
                <table>
                    <tbody>
                        <tr>
                            <th>Date:</th>
                            <td>{{ $data['formData']['trans_date'] }}</td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
        </div>

        <table style="margin-top: 30px">
            <thead>
                <tr>
                    <th>Accounts Head</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['formData']['voucherDebitData'] as $d)
                    <tr>
                        <td>{{ $d['dr_accountCode'] }}</td>
                        <td style="text-align: right;">{{ $d['dr_amount'] }}</td>
                        <td style="text-align: right;">0</td>
                        <td>{{ $d['remarks'] }}</td>
                    </tr>
                @endforeach
                @foreach ($data['formData']['voucherCreditData'] as $d)
                    <tr>
                        <td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $d['cr_accountCode'] }}</td>
                        <td style="text-align: right;">0</td>
                        <td style="text-align: right;">{{ $d['cr_amount'] }}</td>
                        <td>{{ $d['remarks'] }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-right"><b>Total</b></td>
                    <td class="text-right" style="text-align: right;">{{ $data['formData']['totalDebit'] }}</td>
                    <td class="text-right" style="text-align: right;">{{ $data['formData']['totalCredit'] }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <table style="margin-top: 30px">
            <tbody>
                <tr>
                    <td>Amount In Word: </td>
                    <td><span class="in-word-amount">{{ $data['formData']['totalDebit'] }}</span></td>
                </tr>
                <tr>
                    <td>Narration: </td>
                    <td>{{ $data['formData']['remarks'] }}</td>
                </tr>
            </tbody>
        </table>

        <p style="text-align: right">For {{ $data['formData']['institute_name'] }} only.</p>
    </div>

    <div class="footer clearfix">
        <div class="signature-row">
            @php
                $numOfFullLineSignatories = ((int)($totalSignatory/5))*5;
                $numOfLastLineSignatories = $totalSignatory%5;
                $numOfBlankSignatories = 5 - $numOfLastLineSignatories;
                $typeOfLastSignatories = ($numOfLastLineSignatories == 1)?'one':(($numOfLastLineSignatories%2)?'odd':'even');
            @endphp
            
            {{-- Full line signatories --}}
            @for ($i=0; $i<$numOfFullLineSignatories; $i++)
                <div class="signature">
                    @if ($signatories[$i]->attatch)
                        <div class="signotry_img">
                            <img src="{{ public_path() . '/assets/signatory/'.$signatories[$i]->attatch }}"  alt="">
                        </div>
                    @else
                        <div class="signotry_img"></div>
                    @endif
                    <hr>
                    <p><b>{{$signatories[$i]->label}} by</b></p>
                    <p>{{$signatories[$i]->employeeInfo->singleUser->name}}</p>
                    <p>
                        @if ($signatories[$i]->employeeInfo->singleDesignation )
                            {{$signatories[$i]->employeeInfo->singleDesignation->name}} 
                        @endif
                        @if ($signatories[$i]->employeeInfo->singleDepartment)
                            , {{$signatories[$i]->employeeInfo->singleDepartment->name}}
                        @endif
                    </p>
                </div>
            @endfor

            {{-- Last line signatories --}}
            @if ($typeOfLastSignatories == 'one')
                @for ($i=0; $i<$numOfBlankSignatories; $i++)
                    <div class="signature"></div>
                @endfor
                <div class="signature">
                    @if ($signatories[$numOfFullLineSignatories]->attatch)
                        <div class="signotry_img">
                            <img src="{{ public_path() . '/assets/signatory/'.$signatories[$numOfFullLineSignatories]->attatch }}"  alt="">
                        </div>
                    @else
                        <div class="signotry_img"></div>
                    @endif
                    <hr>
                    <p><b>{{$signatories[$numOfFullLineSignatories]->label}} by</b></p>
                    <p>{{$signatories[$numOfFullLineSignatories]->employeeInfo->singleUser->name}}</p>
                    <p>
                        @if ($signatories[$numOfFullLineSignatories]->employeeInfo->singleDesignation )
                            {{$signatories[$numOfFullLineSignatories]->employeeInfo->singleDesignation->name}} 
                        @endif
                        @if ($signatories[$numOfFullLineSignatories]->employeeInfo->singleDepartment)
                            , {{$signatories[$numOfFullLineSignatories]->employeeInfo->singleDepartment->name}}
                        @endif
                    </p>
                </div>
            @elseif ($typeOfLastSignatories == 'even')
                @for ($i=$numOfFullLineSignatories; $i<$numOfFullLineSignatories+($numOfLastLineSignatories/2); $i++)
                    <div class="signature">
                        @if ($signatories[$i]->attatch)
                            <div class="signotry_img">
                                <img src="{{ public_path() . '/assets/signatory/'.$signatories[$i]->attatch }}"  alt="">
                            </div>
                        @else
                            <div class="signotry_img"></div>
                        @endif
                        <hr>
                        <p><b>{{$signatories[$i]->label}} by</b></p>
                        <p>{{$signatories[$i]->employeeInfo->singleUser->name}}</p>
                        <p>
                            @if ($signatories[$i]->employeeInfo->singleDesignation )
                                {{$signatories[$i]->employeeInfo->singleDesignation->name}} 
                            @endif
                            @if ($signatories[$i]->employeeInfo->singleDepartment)
                                , {{$signatories[$i]->employeeInfo->singleDepartment->name}}
                            @endif
                        </p>
                    </div>
                @endfor
                @for ($i=0; $i<$numOfBlankSignatories; $i++)
                    <div class="signature"></div>
                @endfor
                @for ($i=$numOfFullLineSignatories+($numOfLastLineSignatories/2); $i<$numOfFullLineSignatories+$numOfLastLineSignatories; $i++)
                    <div class="signature">
                        @if ($signatories[$i]->attatch)
                            <div class="signotry_img">
                                <img src="{{ public_path() . '/assets/signatory/'.$signatories[$i]->attatch }}"  alt="">
                            </div>
                        @else
                            <div class="signotry_img"></div>
                        @endif
                        <hr>
                        <p><b>{{$signatories[$i]->label}} by</b></p>
                        <p>{{$signatories[$i]->employeeInfo->singleUser->name}}</p>
                        <p>
                            @if ($signatories[$i]->employeeInfo->singleDesignation )
                                {{$signatories[$i]->employeeInfo->singleDesignation->name}} 
                            @endif
                            @if ($signatories[$i]->employeeInfo->singleDepartment)
                                , {{$signatories[$i]->employeeInfo->singleDepartment->name}}
                            @endif
                        </p>
                    </div>
                @endfor
            @elseif ($typeOfLastSignatories == 'odd')
                <div class="signature"></div>
                @for ($i=$numOfFullLineSignatories; $i<$numOfFullLineSignatories+$numOfLastLineSignatories; $i++)
                    <div class="signature">
                        @if ($signatories[$i]->attatch)
                            <div class="signotry_img">
                                <img src="{{ public_path() . '/assets/signatory/'.$signatories[$i]->attatch }}"  alt="">
                            </div>
                        @else
                            <div class="signotry_img"></div>
                        @endif
                        <hr>
                        <p><b>{{$signatories[$i]->label}} by</b></p>
                        <p>{{$signatories[$i]->employeeInfo->singleUser->name}}</p>
                        <p>
                            @if ($signatories[$i]->employeeInfo->singleDesignation )
                                {{$signatories[$i]->employeeInfo->singleDesignation->name}} 
                            @endif
                            @if ($signatories[$i]->employeeInfo->singleDepartment)
                                , {{$signatories[$i]->employeeInfo->singleDepartment->name}}
                            @endif
                        </p>
                    </div>
                @endfor
                <div class="signature"></div>
            @endif
        </div>
    </div>
</body>
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> --}}
{{-- <script>
    function inWords(num) {
        var a = ['', 'One ', 'Two ', 'Three ', 'Four ', 'Five ', 'Six ', 'Seven ', 'Eight ', 'Nine ', 'Ten ', 'Eleven ',
            'Twelve ', 'Thirteen ', 'Fourteen ', 'Fifteen ', 'Sixteen ', 'Seventeen ', 'Eighteen ', 'Nineteen '
        ];
        var b = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        if ((num = num.toString()).length > 9) return 'overflow';
        n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
        if (!n) return;
        var str = '';
        str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'Crore ' : '';
        str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'Lakh ' : '';
        str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'Thousand ' : '';
        str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'Hundred ' : '';
        str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) +
            'taka only. ' : '';
        return str;
    }

    var wordAmountHolder = document.querySelector('.in-word-amount');
    var amount = wordAmountHolder.innerHTML;
    wordAmountHolder.innerHTML = inWords(amount);
</script> --}}

</html>
