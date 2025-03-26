<?php

namespace App\Providers;

use App\Models\SmtpSetting;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */


    public function boot()
    {
        if (SmtpSetting::count() == 0) {
            SmtpSetting::create([
                'host' => 'smtp.hostinger.com',
                'port' => 465,
                'encryption' => 'tls',
                'username' => 'contact@adlabfactory.ma',
                'password' => '#aDc,2[#-ZxQ',
                'sender_name' => 'Adlab Factory',
                'sender_email' => 'contact@adlabfactory.ma',
            ]);
        }
    }

}
