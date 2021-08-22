<?php

namespace App\Providers;

use App\Observers\AttendanceDeviceObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Modules\Academics\Entities\AttendanceUploadAbsent;
use Modules\Academics\Observers\AbsentObserver;
use Modules\Academics\Entities\AttendanceUploadHistory;
use Modules\Academics\Observers\AttendanceUploadObserver;
use Modules\Academics\Observers\DevicePresentObserver;
use Modules\Admission\Entities\ApplicantEnrollment;
use Modules\API\Observers\OnlineApplicationObserver;
use Modules\API\Observers\HscApplicationObserver;
use Modules\Admission\Entities\HscApplicant;
use Modules\Academics\Entities\AttendanceUpload;
use Modules\Employee\Entities\AttendanceDevice;
use Modules\Fee\Entities\Transaction;
use Modules\Admin\Entities\BillingInfo;
use Modules\Fee\Observers\TransactionObserver;
use Modules\Admin\Observers\SubscriptionManagementObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(255);
        // AttendanceUpload::observe(DevicePresentObserver::class);
        AttendanceUploadAbsent::observe(AbsentObserver::class);
        AttendanceUploadHistory::observe(AttendanceUploadObserver::class);
        // attendance device job observer
        AttendanceDevice::observe(AttendanceDeviceObserver::class);
        // applicant user profile observer
        ApplicantEnrollment::observe(OnlineApplicationObserver::class);
        HscApplicant::observe(HscApplicationObserver::class);

        // transaction table to entries and entriese item
        Transaction::observe(TransactionObserver::class);

        // BillingInfo table to entries and entriese item
        BillingInfo::observe(SubscriptionManagementObserver::class);

        // Validator::extend('phone', function($attribute, $value, $parameters, $validator) {
        //     return preg_match('%^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})(?:[\-\.\ \\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$%i', $value) && strlen($value) >= 10;
        // });

        // Validator::replacer('phone', function($message, $attribute, $rule, $parameters) {
        //     return str_replace(':attribute',$attribute, ':attribute is invalid phone number');
        // });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
        //
    }
}
