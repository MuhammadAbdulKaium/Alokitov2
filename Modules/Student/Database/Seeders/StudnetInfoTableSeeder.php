<?php

namespace Modules\Student\Database\Seeders;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentEnrollment;

class StudnetInfoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Model::unguard();

        $userProfile = new User();
        // store user profile
        $userProfile->name     = 'amirul islam';
        $userProfile->email    = 'amirulaskldf@gmail.com';
        $userProfile->password = bcrypt(123456);
        // save user profile
        $userCreated = $userProfile->save();

        // Validate, then create if valid
        $studentProfile = new StudentInformation();
        // storing student profile
        $studentProfile->user_id     = $userProfile->id;
        $studentProfile->type        = 1;
        $studentProfile->title       = 'Mr.';
        $studentProfile->first_name  = 'AKM';
        $studentProfile->middle_name = 'Amirul Islam';
        $studentProfile->last_name   = 'Islam';
        $studentProfile->gender      = 'Male';
        $studentProfile->dob         = '1991-12-31';
        $studentProfile->blood_group = 'O+';
        $studentProfile->religion    = 1;
        $studentProfile->birth_place = 'Dhaka';
        $studentProfile->email       = 'alksdjf@gma.cim';
        $studentProfile->phone       = '1234123412341234';
        $studentProfile->residency   = 1;
        $studentProfile->passport_no = 'i4928345u23';
        $studentProfile->nationality = 1;
        $studentProfile->campus      = 1;
        // // save student profile
        $studentProfileCreated = $studentProfile->save();

        // Validate, then create if valid
        $enrollProfile = new StudentEnrollment();
        // storing student profile
        $enrollProfile->std_id         = $studentProfile->id;
        $enrollProfile->gr_no          = 'asdjkf';
        $enrollProfile->academic_level = 1;
        $enrollProfile->batch          = 1;
        $enrollProfile->section        = 1;
        $enrollProfile->academic_year  = 1;
        $enrollProfile->admission_year = 1;
        $enrollProfile->enrolled_at    = '1991-12-31';
        $enrollProfile->save();

        
        ///////////////////// another //////////////////

        $userProfile = new User();
        // store user profile
        $userProfile->name     = 'amirul islam';
        $userProfile->email    = 'amirulaskldf@gmail.com';
        $userProfile->password = bcrypt(123456);
        // save user profile
        $userCreated = $userProfile->save();

        // Validate, then create if valid
        $studentProfile = new StudentInformation();
        // storing student profile
        $studentProfile->user_id     = $userProfile->id;
        $studentProfile->type        = 1;
        $studentProfile->title       = 'Mr.';
        $studentProfile->first_name  = 'AKM';
        $studentProfile->middle_name = 'Amirul Islam';
        $studentProfile->last_name   = 'Islam';
        $studentProfile->gender      = 'Male';
        $studentProfile->dob         = '1991-12-31';
        $studentProfile->blood_group = 'O+';
        $studentProfile->religion    = 1;
        $studentProfile->birth_place = 'Dhaka';
        $studentProfile->email       = 'alksdjf@gma.cim';
        $studentProfile->phone       = '1234123412341234';
        $studentProfile->residency   = 1;
        $studentProfile->passport_no = 'i4928345u23';
        $studentProfile->nationality = 1;
        $studentProfile->campus      = 1;
        // // save student profile
        $studentProfileCreated = $studentProfile->save();

        // Validate, then create if valid
        $enrollProfile = new StudentEnrollment();
        // storing student profile
        $enrollProfile->std_id         = $studentProfile->id;
        $enrollProfile->gr_no          = 'asdjkf';
        $enrollProfile->academic_level = 1;
        $enrollProfile->batch          = 1;
        $enrollProfile->section        = 1;
        $enrollProfile->academic_year  = 1;
        $enrollProfile->admission_year = 1;
        $enrollProfile->enrolled_at    = '1991-12-31';
        $enrollProfile->save();

        ///////////////////// another //////////////////

        $userProfile = new User();
        // store user profile
        $userProfile->name     = 'amirul islam';
        $userProfile->email    = 'amirulaskldf@gmail.com';
        $userProfile->password = bcrypt(123456);
        // save user profile
        $userCreated = $userProfile->save();

        // Validate, then create if valid
        $studentProfile = new StudentInformation();
        // storing student profile
        $studentProfile->user_id     = $userProfile->id;
        $studentProfile->type        = 1;
        $studentProfile->title       = 'Mr.';
        $studentProfile->first_name  = 'AKM';
        $studentProfile->middle_name = 'Amirul Islam';
        $studentProfile->last_name   = 'Islam';
        $studentProfile->gender      = 'Male';
        $studentProfile->dob         = '1991-12-31';
        $studentProfile->blood_group = 'O+';
        $studentProfile->religion    = 1;
        $studentProfile->birth_place = 'Dhaka';
        $studentProfile->email       = 'alksdjf@gma.cim';
        $studentProfile->phone       = '1234123412341234';
        $studentProfile->residency   = 1;
        $studentProfile->passport_no = 'i4928345u23';
        $studentProfile->nationality = 1;
        $studentProfile->campus      = 1;
        // // save student profile
        $studentProfileCreated = $studentProfile->save();

        // Validate, then create if valid
        $enrollProfile = new StudentEnrollment();
        // storing student profile
        $enrollProfile->std_id         = $studentProfile->id;
        $enrollProfile->gr_no          = 'asdjkf';
        $enrollProfile->academic_level = 1;
        $enrollProfile->batch          = 1;
        $enrollProfile->section        = 1;
        $enrollProfile->academic_year  = 1;
        $enrollProfile->admission_year = 1;
        $enrollProfile->enrolled_at    = '1991-12-31';
        $enrollProfile->save();


        ///////////////////// another //////////////////

        $userProfile = new User();
        // store user profile
        $userProfile->name     = 'amirul islam';
        $userProfile->email    = 'amirulaskldf@gmail.com';
        $userProfile->password = bcrypt(123456);
        // save user profile
        $userCreated = $userProfile->save();

        // Validate, then create if valid
        $studentProfile = new StudentInformation();
        // storing student profile
        $studentProfile->user_id     = $userProfile->id;
        $studentProfile->type        = 1;
        $studentProfile->title       = 'Mr.';
        $studentProfile->first_name  = 'AKM';
        $studentProfile->middle_name = 'Amirul Islam';
        $studentProfile->last_name   = 'Islam';
        $studentProfile->gender      = 'Male';
        $studentProfile->dob         = '1991-12-31';
        $studentProfile->blood_group = 'O+';
        $studentProfile->religion    = 1;
        $studentProfile->birth_place = 'Dhaka';
        $studentProfile->email       = 'alksdjf@gma.cim';
        $studentProfile->phone       = '1234123412341234';
        $studentProfile->residency   = 1;
        $studentProfile->passport_no = 'i4928345u23';
        $studentProfile->nationality = 1;
        $studentProfile->campus      = 1;
        // // save student profile
        $studentProfileCreated = $studentProfile->save();

        // Validate, then create if valid
        $enrollProfile = new StudentEnrollment();
        // storing student profile
        $enrollProfile->std_id         = $studentProfile->id;
        $enrollProfile->gr_no          = 'asdjkf';
        $enrollProfile->academic_level = 1;
        $enrollProfile->batch          = 1;
        $enrollProfile->section        = 1;
        $enrollProfile->academic_year  = 1;
        $enrollProfile->admission_year = 1;
        $enrollProfile->enrolled_at    = '1991-12-31';
        $enrollProfile->save();


        ///////////////////// another //////////////////

        $userProfile = new User();
        // store user profile
        $userProfile->name     = 'amirul islam';
        $userProfile->email    = 'amirulaskldf@gmail.com';
        $userProfile->password = bcrypt(123456);
        // save user profile
        $userCreated = $userProfile->save();

        // Validate, then create if valid
        $studentProfile = new StudentInformation();
        // storing student profile
        $studentProfile->user_id     = $userProfile->id;
        $studentProfile->type        = 1;
        $studentProfile->title       = 'Mr.';
        $studentProfile->first_name  = 'AKM';
        $studentProfile->middle_name = 'Amirul Islam';
        $studentProfile->last_name   = 'Islam';
        $studentProfile->gender      = 'Male';
        $studentProfile->dob         = '1991-12-31';
        $studentProfile->blood_group = 'O+';
        $studentProfile->religion    = 1;
        $studentProfile->birth_place = 'Dhaka';
        $studentProfile->email       = 'alksdjf@gma.cim';
        $studentProfile->phone       = '1234123412341234';
        $studentProfile->residency   = 1;
        $studentProfile->passport_no = 'i4928345u23';
        $studentProfile->nationality = 1;
        $studentProfile->campus      = 1;
        // // save student profile
        $studentProfileCreated = $studentProfile->save();

        // Validate, then create if valid
        $enrollProfile = new StudentEnrollment();
        // storing student profile
        $enrollProfile->std_id         = $studentProfile->id;
        $enrollProfile->gr_no          = 'asdjkf';
        $enrollProfile->academic_level = 1;
        $enrollProfile->batch          = 1;
        $enrollProfile->section        = 1;
        $enrollProfile->academic_year  = 1;
        $enrollProfile->admission_year = 1;
        $enrollProfile->enrolled_at    = '1991-12-31';
        $enrollProfile->save();



    }
}
