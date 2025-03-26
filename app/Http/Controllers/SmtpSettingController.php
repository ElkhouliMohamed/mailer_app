<?php

namespace App\Http\Controllers;

use App\Models\SmtpSetting;
use Illuminate\Http\Request;

class SmtpSettingController extends Controller
{
    public function index()
    {
        $smtpSettings = SmtpSetting::all();
        return view('smtp_settings.index', compact('smtpSettings'));
    }

    public function create()
    {
        return view('smtp_settings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'host' => 'required|string|max:255',
            'port' => 'required|integer',
            'encryption' => 'nullable|in:tls,ssl',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'sender_name' => 'required|string|max:255',
            'sender_email' => 'required|email|unique:smtp_settings,sender_email',
        ]);

        SmtpSetting::create([
            'user_id' => auth()->id(),
            'host' => $request->host,
            'port' => $request->port,
            'encryption' => $request->encryption,
            'username' => $request->username,
            'password' => $request->password,
            'sender_name' => $request->sender_name,
            'sender_email' => $request->sender_email,
        ]);
        return redirect()->route('smtp_settings.index')->with('success', 'SMTP setting added successfully.');
    }

    public function edit(SmtpSetting $smtpSetting)
    {
        return view('smtp_settings.edit', compact('smtpSetting'));
    }

    public function update(Request $request, SmtpSetting $smtpSetting)
    {
        $request->validate([
            'host' => 'required|string|max:255',
            'port' => 'required|integer',
            'encryption' => 'nullable|in:tls,ssl',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'sender_name' => 'required|string|max:255',
            'sender_email' => 'required|email|unique:smtp_settings,sender_email,' . $smtpSetting->id,
        ]);

        $smtpSetting->update($request->all());

        return redirect()->route('smtp_settings.index')->with('success', 'SMTP setting updated successfully!');
    }

    public function destroy(SmtpSetting $smtpSetting)
    {
        $smtpSetting->delete();
        return redirect()->route('smtp_settings.index')->with('success', 'SMTP setting deleted successfully.');
    }
}
