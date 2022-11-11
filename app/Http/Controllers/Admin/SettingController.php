<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $edit = BusinessSetting::find(1);

        return view('admin.setting.edit', compact('edit'));
    }


    public function update(Request $request)
    {
        $this->validate($request, [
            'logo'                    => 'file|max:10240',
            'company_name'            => 'required|regex:/^[a-zA-Z]+(([\',. -][a-zA-Z ])?[a-zA-Z]*)*$/',
            'email'                   => 'email',
            'phone_number'            => 'integer'
        ]);
$id=1;
        try {
            $settings = BusinessSetting::find($id);
            $_file['logo'] = $settings->logo;
            $_file['favicon'] = $settings->favicon;

            if ($request->hasFile('logo')) {
                if ($request->file('logo')) {
                    $file = $request->file('logo');
                    $filename = uniqid('photo', true) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('/uploads/business'), $filename);
                    $_file['logo'] = $filename;
                }
            }
            if ($request->hasFile('favicon')) {
                if ($request->file('favicon')) {
                    $file = $request->file('favicon');
                    $filename = uniqid('photo', true) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('/uploads/business'), $filename);
                    $_file['favicon'] =  $filename;
                }
            }
            $data = [
                'company_name'                  => $request->company_name,
                'logo'                          => $_file['logo'],
                'favicon'                       => $_file['favicon'],
                'address'                       => $request->address,
                'google_location'               => $request->google_location,
                'email'                         => $request->email,
                'phone_number'                  => $request->phone_number,
                'web_address'                   => $request->web_address,
                'hot_line'                      => $request->hot_line,
                'facebook'                      => $request->facebook,
                'twitter'                       => $request->twitter,
                'instagram'                     => $request->instagram,
                'pinterest'                     => $request->pinterest,
                'youtube'                       => $request->youtube,
                'about'                         => $request->about,
                'tag_line'                      => $request->tag_line,

            ];
            $settings->update($data);
            Toastr::success('Setting Updated successfully', 'Setting');
            return redirect()->back();
        } catch (\Exception $e) {
            dd($e->getMessage());
            Toastr::error('Error occurred while updating setting', 'Setting');
            return redirect()->back();
        }
    }
}
