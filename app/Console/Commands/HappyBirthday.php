<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Modules\Student\Entities\StudentInformation;
use App\Http\Controllers\SmsSender;
use Modules\Setting\Entities\AutoSmsModule;

class HappyBirthday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:birthday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a Happy birthday message to users via SMS';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    private  $smsSender;
    private  $autoSmsModule;
    public function __construct(SmsSender $smsSender, AutoSmsModule $autoSmsModule)
    {

        parent::__construct();
        $this->smsSender=$smsSender;
        $this->autoSmsModule=$autoSmsModule;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $instituteList=$this->autoSmsModule->where('status_code',"BIRTHDAY")->get();

        foreach ($instituteList as $institute){

            $students= StudentInformation::whereMonth('dob', '=', date('m'))->whereDay('dob', '=', date('d'))->where('institute',$institute->ins_id)->where('campus',$institute->campus_id)->get();
            $studentIdList=array();
            foreach( $students as $student ) {
                $studentIdList[] = $student->id;
            }

            $this->smsSender->birthday_sms_job($studentIdList, $institute->ins_id,$institute->campus_id);
        }

        $this->info('The happy birthday messages were sent successfully!');

    }


}
