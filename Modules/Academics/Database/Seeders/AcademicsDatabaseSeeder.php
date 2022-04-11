<?php

namespace Modules\Academics\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Academics\Entities\ExamMarkParameter;

class AcademicsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        // DB::table('academics_division')->insert(array(
        //     array('name' => 'Science', 'short_name' => 'Sc'),
        //     array('name' => 'Arts', 'short_name' => 'Art'),
        //     array('name' => 'Commerce', 'short_name' => 'Com'),

        // ));

        // $this->call("OthersTableSeeder");

        ExamMarkParameter::insert([
            ['name' => 'Subjective', 'created_by' => 1],
            ['name' => 'Objective', 'created_by' => 1],
            ['name' => 'Practical', 'created_by' => 1],
            ['name' => 'Viva', 'created_by' => 1],
            ['name' => 'Attendance', 'created_by' => 1],
            ['name' => 'Assignment', 'created_by' => 1],
            ['name' => 'Behaviour', 'created_by' => 1],
            ['name' => 'Culture', 'created_by' => 1]
        ]);
    }
}
