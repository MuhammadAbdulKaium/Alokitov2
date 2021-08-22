<?php

use Illuminate\Database\Migrations\Migration;

class CreateAcademicsModuleViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // CREATE VIEW student_attendance_view_order AS SELECT
        //     a.id as att_id, a.student_id as student_id, a.attendacnce_type as attendacnce_type, b.class_id as class_id, b.section_id as section_id, b.session_id as session_id, b.subject_id as subject_id, c.sorting_order as sorting_order,a.attendance_date as attendance_date, a.deleted_at as deleted_at
        //   FROM
        //     academices_student_attendances a
        //   JOIN
        //     academices_student_attendances_details b
        //   ON
        //     b.student_attendace_id=a.id
        //   JOIN
        //     class_subjects c
        //   ON
        //     c.id = b.subject_id
        //   AND
        //     c.class_id = b.class_id
        //   AND
        //     c.section_id=b.section_id




        // CREATE VIEW student_attendance_view_one AS SELECT
        //     a.id as att_id, a.student_id as student_id, a.attendacnce_type as attendacnce_type, b.class_id as class_id, b.section_id as section_id, b.session_id as session_id, a.attendance_date as attendance_date, b.subject_id as subject_id, a.deleted_at as deleted_at
        //   FROM
        //     academices_student_attendances a
        //   JOIN
        //     academices_student_attendances_details b
        //   ON
        //     b.student_attendace_id=a.id
        //   wHERE
        //     b.subject_id = 0
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // statements
    }
}
