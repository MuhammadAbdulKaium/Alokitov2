<?php

namespace Modules\Student\Entities\Imports;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Modules\Student\Entities\StudentProfileView;
use Modules\Student\Entities\StudentInformation;
use Illuminate\Support\Facades\DB;

class StudentImport implements ToModel, WithHeadingRow
{
    use Importable;

    public function model(array $row)
    {
        if(!empty($row[0])){
            return ([
                'username'                  => $row[0],
                'first_name'                => $row[1],
                'last_name'                 => $row[2],
                'bn_fullname'               => $row[3],
                'gender'                    => $row[4],
                'present_address'           => $row[5],
                'permanent_address'         => $row[6],
                'nationality'               => $row[7],
                'birth_place'               => $row[8],
                'student_type '             => $row[9],
                'identification_mark'       => $row[10],
                'religion'                  => $row[11],
                'language'                  => $row[12],
                'blood_group '              => $row[13],
                'dob'                       => $row[14],
                'admission_year_id'         => $row[15],
                'academic_year_id'          => $row[16],
                'tution_fees'               => $row[17],
                'academic_level'            => $row[18],
                'batch_id'                  => $row[19],
                'division_id'               => $row[20],
                'section_id'                => $row[21],
                'batch_no'                  => $row[22],
                'father_name'               => $row[23],
                'father_occupation'         => $row[24],
                'father_mobile'             => $row[25],
                'mother_name'               => $row[26],
                'mother_occupation'         => $row[27],
                'mother_mobile'             => $row[28],
                'guardian_name'             => $row[29],
                'guardian_relation'         => $row[30],
                'guardian_mobile'           => $row[31],
                'hobby'                     => $row[32],
                'aim'                       => $row[33],
                'dream'                     => $row[34],
                'idol'                      => $row[35],
            ]);
        }
    }
}
