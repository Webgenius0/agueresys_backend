<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;

class SystemSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = SystemSetting::latest('id')->first();
        return view('backend.layouts.settings.index', compact('settings'));
    }
    /**
     * Display a listing of the resource.
     */
    public function mailSettingGet()
    {
        $settings = [
            'mail_mailer' => env('MAIL_MAILER', ''),
            'mail_host' => env('MAIL_HOST', ''),
            'mail_port' => env('MAIL_PORT', ''),
            'mail_username' => env('MAIL_USERNAME', ''),
            'mail_password' => env('MAIL_PASSWORD', ''),
            'mail_encryption' => env('MAIL_ENCRYPTION', ''),
            'mail_from_address' => env('MAIL_FROM_ADDRESS', ''),
        ];

        return view('backend.layouts.settings.mail_settings', compact('settings'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // dd($request->all());
        $validateData = $request->validate([
            'title' => 'required|string|max:100',
            'system_name' => 'required|string|max:50',
            'email' => 'nullable|string|email|max:255',
            'contact_number' => 'nullable|string|max:20',
            // 'company_open_hour' => 'required|string|max:255',
            'copyright_text' => 'nullable|string|max:255',
            'logo' => 'nullable|mimes:jpeg,jpg,png,ico,svg',
            'favicon' => 'nullable|mimes:jpeg,jpg,png,ico,svg',
            // 'address' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);
        // dd($validateDta);
        $setting = SystemSetting::first();
        // $setting->title = $request->title;
        // $setting->system_name = $request->system_name;
        // $setting->email = $request->email;
        // $setting->contact_number = $request->contact_number;
        // // $setting->company_open_hour = $request->company_open_hour;
        // $setting->copyright_text = $request->copyright_text;
        // // $setting->address = $request->address;
        // $setting->description = $request->description;

        if ($request->hasFile('logo')) {
            if ($setting->logo) {
                Helper::fileDelete(public_path($setting->logo));
            }
            $setting['logo'] = Helper::fileUpload($request->file('logo'), 'logos', time() . '_' . getFileName($request->file('logo')));
        }
        if ($request->hasFile('favicon')) {
            if ($setting->favicon) {
                Helper::fileDelete(public_path($setting->favicon));
            }
            $setting['favicon'] = Helper::fileUpload($request->file('favicon'), 'favicons', time() . '_' . getFileName($request->file('favicon')));
        }
        // $setting->save();

        $setting->update($validateData);

        flash()->success("System Setting Updated Successfully");
        return back();
    }

    /**
     * Update mail settings.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function mailSettingUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'mail_mailer' => 'nullable|string',
            'mail_host' => 'nullable|string',
            'mail_port' => 'nullable|string',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            // 'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'nullable|string',
        ]);

        try {
            $envContent = File::get(base_path('.env'));
            $lineBreak = "\n";
            $envContent = preg_replace([
                '/MAIL_MAILER=(.*)\s*/',
                '/MAIL_HOST=(.*)\s*/',
                '/MAIL_PORT=(.*)\s*/',
                '/MAIL_USERNAME=(.*)\s*/',
                '/MAIL_PASSWORD=(.*)\s*/',
                '/MAIL_ENCRYPTION=(.*)\s*/',
                '/MAIL_FROM_ADDRESS=(.*)\s*/',
            ], [
                'MAIL_MAILER=' . $request->mail_mailer . $lineBreak,
                'MAIL_HOST=' . $request->mail_host . $lineBreak,
                'MAIL_PORT=' . $request->mail_port . $lineBreak,
                'MAIL_USERNAME=' . $request->mail_username . $lineBreak,
                'MAIL_PASSWORD=' . $request->mail_password . $lineBreak,
                'MAIL_ENCRYPTION=' . $request->mail_encryption . $lineBreak,
                'MAIL_FROM_ADDRESS=' . '"' . $request->mail_from_address . '"' . $lineBreak,
            ], $envContent);

            File::put(base_path('.env'), $envContent);
            flash()->success("Mail Setting Updated Successfully");
            return back();
        } catch (Exception) {
            flash()->success("Failed Updated ");
            return back();
        }
    }

}
