<?php

namespace Modules\Academics\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Academics\Database\Seeders\DB;

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
        DB::table('academics_division')->insert(array(
            array('name' => 'Science', 'short_name' => 'Sc'),
            array('name' => 'Arts', 'short_name' => 'Art'),
            array('name' => 'Commerce', 'short_name' => 'Com'),

        ));

        // $this->call("OthersTableSeeder");
    }
}
