<?php

namespace Modules\Employee\Entities\Imports;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EmployeeImport implements ToModel, WithHeadingRow
{
    use Importable;

    public function model(array $row)
    {
        if(!empty($row[0])){
            return ([
                'fm_ba_employee_no'                     => $row[0],
                'category_teaching_non_teaching'        => $row[1],
                'first_name'                            => $row[2],
                'last_name'                             => $row[3],
                'name_alias'                            => $row[4],
                'gender'                                => $row[5],
                'date_of_birth_yyyy_mm_dd'              => $row[6],
                'date_of_joining_yyyy_mm_dd'            => $row[7],
                'department'                            => $row[8],
                'designation'                           => $row[9],
                'position_serial'                       => $row[10],
                'date_of_retirement_yyyy_mm_dd '        => $row[11],
                'current_address '                      => $row[12],
                'permanent_address '                    => $row[13],
                'last_academic_qualification'           => $row[14],
                'special_qualification'                 => $row[15],
                'nid_no'                                => $row[16],
                'passport_no'                           => $row[17],
                'birth_certificate_no'                  => $row[18],
                'driving_license_no'                    => $row[19],
                'tin_no'                                => $row[20],
                'email_login_id'                        => $row[21],
                'mobile'                                => $row[22],
                'alternative_mobile'                    => $row[23],
                'fathers_name'                          => $row[24],
                'mothers_name'                          => $row[25],
                'marital_status'                        => $row[26],
                'spouse_name'                           => $row[27],
                'spouse_occupation'                     => $row[28],
                'spouse_mobile'                         => $row[29],
                'spouse_nid'                            => $row[30],
                'spouse_date_of_birth_yyyy_mm_dd'       => $row[31],
                'child_1_name'                          => $row[32],
                'child_1_date_of_birth_yyyy_mm_dd'      => $row[33],
                'child_1_gender'                        => $row[34],
                'child_2_name'                          => $row[35],
                'child_2_date_of_birth_yyyy_mm_dd'      => $row[36],
                'child_2_gender'                        => $row[37],
                'child_3_name'                          => $row[38],
                'child_3_date_of_birth_yyyy_mm_dd'      => $row[39],
                'child_3_gender'                        => $row[40],
                'child_4_name'                          => $row[41],
                'child_4_date_of_birth_yyyy_mm_dd'      => $row[42],
                'child_4_gender'                        => $row[43],

            ]);
        }
    }
}
