<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::allAsArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'theme_mode'           => 'required|in:light,dark',
            'primary_color'        => 'required|string|max:20',
            'secondary_color'      => 'required|string|max:20',
            'alert_followup_hours' => 'required|integer|min:1|max:8760',
            'alert_warning_hours'  => 'required|integer|min:1|max:8760',
        ]);

        SystemSetting::set('theme_mode',            $request->theme_mode);
        SystemSetting::set('primary_color',         $request->primary_color);
        SystemSetting::set('secondary_color',       $request->secondary_color);
        SystemSetting::set('alert_followup_hours',  $request->alert_followup_hours);
        SystemSetting::set('alert_warning_hours',   $request->alert_warning_hours);

        return back()->with('success', 'تم حفظ الإعدادات بنجاح.');
    }
}