<?php

use Illuminate\Database\Migrations\Migration;

class CreateStudentModuleViews extends Migration
{

    public function up()
    {

        /* CREATE VIEW student_manage_view AS SELECT
         a.id as std_id, a.user_id as user_id, a.first_name as first_name, a.middle_name as middle_name, a.last_name as last_name, a.email as email, b.id as enroll_id, b.gr_no as gr_no, b.academic_year as academic_year, b.academic_level as academic_level, b.batch as batch, b.section as section
          FROM
            student_informations a
          JOIN
            student_enrollments b
          ON
            b.std_id=a.id*/

    }

    public function down()
    {
        //
    }
}
