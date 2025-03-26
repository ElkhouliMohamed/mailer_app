<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SmtpSetting;

class SmtpSettingSeeder extends Seeder
{
    public function run()
    {
        SmtpSetting::firstOrCreate([
            'sender_email' => 'contact@adlabfactory.ma'
        ], [
            'host' => 'smtp.hostinger.com',
            'port' => 465,
            'encryption' => 'tls',
            'username' => 'contact@adlabfactory.ma',
            'password' => '#aDc,2[#-ZxQ',
            'sender_name' => 'Adlab Factory',
        ]);
    }
}
