<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckScheduled extends Command
{
    protected $name = 'payment:check';

    public function fire()
    {
       Log::info('My Romesh Shil ');
    }
}
