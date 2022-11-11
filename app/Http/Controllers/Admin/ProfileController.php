<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = User::findOrFail(auth()->user()->id);
        return view('admin.layouts.user.profile', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return void
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
//            'status'       => 'required',
//            'type'         => 'required',
            'nid_passport' => 'required|unique:users,nid_passport,'.auth()->user()->id,
            'phone_number' => 'required|unique:users,phone_number,'.auth()->user()->id,
            'user_name'    => 'required',
            'pin'          => 'required'
        ]);

        try {
            $user = User::where('id', auth()->user()->id)->where('pin', $request->pin)->first();
            if ($user) {
                $file_name = '';

                if ($request->hasFile('file')) {
                    $avatar = $request->file('file');

                    if ($avatar->isValid()) {
                        $file_name = uniqid("user_", true) . mt_rand(10,
                                10) . '.' . $avatar->getClientOriginalExtension();
                        $avatar->storeAs('user', $file_name);
                    }
                } else {
                    $file_name = $user->image;
                }
                $user->update([
                    'phone_number' => $request->phone_number,
                    'username'     => $request->user_name,
                    'address'      => $request->address,
                    'nid_passport' => $request->nid_passport,
                    'image'        => $file_name,
                ]);

                $message = 'User updated!';
                $type = "success";
            } else {
                $message = 'Something went wrong';
                $type = "error";
            }
        } catch (Exception $e) {
            $message = "Something went wrong";
            $type = "error";
        }

        Toastr::$type($message, $type, ["positionClass" => "toast-top-right"]);
        return redirect()->back();
    }

    public function updatePassword(Request $request, $id)
    {

        try {
            $this->validate($request,
                [
                    'password' => 'required',
                    'pin'      => 'required'
                ]);

            if ((int)$request->pin === (int)auth()->user()->pin) {
                User::find($id)->update(['password' => Hash::make($request->password)]);
                Toastr::success('Password Updated.', 'success', ["positionClass" => "toast-top-right"]);
            } else {
                Toastr::error('Invalid PIN', 'error');
            }

            return redirect()->back();

        } catch (Exception $e) {
            Toastr::error($e->getMessage(), 'error', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
    }

    public function updatePin(Request $request, $id)
    {
        $this->validate($request,
            [
                'pin'   => 'required',
                'old_pin' => 'required'
            ]);
        try {
            if ((int)$request->old_pin === (int)auth()->user()->pin) {
                User::find($id)->update(['pin' => $request->pin]);
                Toastr::success('PIN Updated.', 'success', ["positionClass" => "toast-top-right"]);
            } else {
                Toastr::error('Invalid PIN', 'error');
            }

            return redirect()->back();

        } catch (Exception $e) {
            Toastr::error($e->getMessage(), 'error', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
    }
}
