<?php

namespace Modules\Student\Database\Seeders;

use Illuminate\Database\Seeder;

class StudentDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Model::unguard();
        $this->call("Modules\Student\Database\Seeders\StudnetInfoTableSeeder");
    }
}
