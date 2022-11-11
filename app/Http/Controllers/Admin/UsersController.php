<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Statement;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserBalance;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function login()
    {
        return view('admin.layouts.user.login');
    }

    public function doLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile'   => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        //get user by mobile number
        $user = User::where('phone_number', $request->mobile)->first();
        $credentials = [
            'phone_number' => $request->input('mobile'),
            'password'     => $request->input('password'),
        ];

        if (!empty($user)) {
            try {
                if (auth()->attempt($credentials)) {
                    if (auth()->user()->status === 'active') {
                        //set count 0
                        $user->update(['attempt_count' => 0]);
                        Toastr::success('Login Successful', 'success', ["positionClass" => "toast-top-right"]);
                        return redirect()->route('dashboard');

                    } elseif (auth()->user()->status === 'inactive') {

                        Auth::logout();
//                    Toastr::warning('Your account is deactivated. Please contact with admin.', 'warning', ["positionClass" => "toast-top-right"]);
                        return redirect()->back()->withErrors('Your Account is Inactive. Please contact Customer Service through WhatsApp +8801677247247. Thank You!');
                    } else {
                        $user->increment('attempt_count');
                        Auth::logout();
//                    Toastr::warning('Your account is deactivated. Please contact with admin.', 'warning', ["positionClass" => "toast-top-right"]);
                        return redirect()->back()->withErrors('Your Account has been permanently Closed. Thank You!');
                    }

                } else {
                    if ((int)$user->attempt_count > 1) {
                        //inactive user user
                        $user->update(['status' => 'inactive']);
                        //           dd($user->attempt_count);
                        return redirect()->back()->withErrors('Too many attempts.Your Account is Inactive. Please contact Customer Service through WhatsApp +8801677247247. Thank You!');
                    }

                    $user->increment('attempt_count');
                    //  Toastr::error("Invalid Credentials !", 'error', ["positionClass" => "toast-top-right"]);
                    return redirect()->back()->withErrors('Invalid credentials.');
                }
            } catch (\Exception $e) {

                return redirect()->back()->withErrors($e->getMessage());
            }

        } else {

            return redirect()->back()->withErrors('Invalid Credentials');
        }
    }

    public function logout()
    {
        auth()->logout();
        Toastr::success('Logged out successfully.', 'success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('home');
    }


    public function data()
    {
        $users = User::paginate(10);

        return view('admin.layouts.user.list', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        $user = '';
        return view('admin.layouts.user.create', compact('user'));
    }

    public function addBalance(Request $request, int $id)
    {
        if ($_POST) {
            try {
                $request->validate([
                    'type'   => 'required',
                    'amount' => 'required',
                    'pin'    => 'required',
                ]);
                $userId = Auth::user()->id;

                $currentBal = User::select('balance','phone_number')
                    ->find($id);

                if ((int)Auth::user()->pin==(int)request()->input('pin')) {
                    if ($userId != $id) {
                        $ab = auth()->user()->balance;

                        $reqBal = (float)request()->input('amount');
                        $cb = (float)$currentBal->balance;

                        // print_r($balance.'-'.$reqBal);exit();
                        $tnxhead = request()->input('type');
                        $helper = new Helper();
                        $lastId = Statement::latest()->first();
                        if($lastId)
                        {
                            $lastId=$lastId->id;
                        }else
                        {
                            $lastId=0;
                        }

                        $temp_id = 'FR' . $lastId = $lastId + 1;

                        if ($tnxhead == 'sub') {
                            if ($cb >= $reqBal) {//check user has enough balance

                                DB::beginTransaction();
                                $details='Balance Deducted from-'.$currentBal->phone_number.'. Reason: '.$request->reason;

                                $user=User::find($id);
                                $user->decrement('balance', $reqBal);

                                User::find($userId)->increment('balance', $reqBal);
                                $helper->statement($id,$temp_id,$details,$reqBal,0,$user->balance,$reqBal);
                                DB::commit();
                                session()->flash('message', 'Balance Debited from User!');
                                return redirect()->back();
                            } else {
                                session()->flash('error', 'Insufficient User Balance!');
                                return redirect()->back();
                            }
                        } else {
                            if ($ab >= $reqBal) {
                                $details='Balance Credited to-'.$currentBal->phone_number.'. Reason: '.$request->reason;
                                DB::beginTransaction();
                                $user=User::find($id);
                                $user->increment('balance', $reqBal);
//                                dd($user);
                                User::find($userId)->decrement('balance', $reqBal);
                                $helper->statement($id,$temp_id,$details,0,$reqBal,$user->balance,$reqBal);
                                DB::commit();
                                session()->flash('message', 'Balance Credited to User!');
                                return redirect()->back();
                            } else {
                                session()->flash('error', 'Insufficient Admin Balance Balance!');
                                return redirect()->back();
                            }
                        }

                    } else {
                        session()->flash('error', 'Admin Cannot Update Balance By itself!');
                        return redirect()->back();
                    }
                } else {
                    session()->flash('error', 'Invalid PIN !');
                    return redirect()->back();
                }
            } catch (Exception $e) {
                DB::rollback();
                session()->flash('error', $e->getMessage());
                return redirect()->back();
            }


            // print_r($currentBal->current_balance);exit();
        }
        $user = User::find($id);
        return view('admin.layouts.user.balance', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'username'     => 'required',
            'password'     => 'required',
            'address'      => 'required',
            'phone_number' => 'required|unique:users',
            'nid_passport' => 'required|unique:users',
        ]);
        $file_name = '';

        if ($request->hasFile('file')) {
            $avatar = $request->file('file');

            if ($avatar->isValid()) {
                $file_name = uniqid("user_", true) . mt_rand(10,
                        10) . '.' . $avatar->getClientOriginalExtension();
                $avatar->storeAs('user', $file_name);
            }
        }

        $docs = array();
        if ($request->hasFile('docs')) {
            foreach ($request->docs as $key => $doc) {
                if ($doc->isValid()) {
                    $doc_name = uniqid("doc_", true) . mt_rand(10,
                            10) . '.' . $doc->getClientOriginalExtension();
                    $doc->storeAs('docs', $doc_name);
                    $docs[$key] = $doc_name;
                }
            }
        }
        $docs = json_encode($docs);

        $data = $request->except(['password', 'file']);// this will return all form data except
        $data['password'] = bcrypt($request->password);
        $data['docs'] = $docs;
        $data['image'] = $file_name;
//        dd($data);
        User::create($data);

        return redirect()->back()->with('message', 'User Created Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return void
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return void
     */


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return void
     */
    public function destroy($id)
    {
        // User::destroy($id);

        return redirect('admin/users')->with('flash_message', 'Permission Protected!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.layouts.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nid_passport' => 'required|unique:users,nid_passport,' . $id,
            'phone_number' => 'required|unique:users,phone_number,' . $id,
            'user_name'    => 'required',
        ]);

        try {
            $user = User::find($id);
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

                $docs = json_decode($user->docs, true);

                if ($request->hasFile('docs')) {
                    foreach ($request->docs as $key => $doc) {
                        if ($doc->isValid()) {
                            $doc_name = uniqid("doc_", true) . mt_rand(10,
                                    10) . '.' . $doc->getClientOriginalExtension();
                            $doc->storeAs('docs', $doc_name);
                            //unlink file
                            if(array_key_exists($key,$docs)){
                                $image_path = public_path('uploads/docs/' . $docs[$key]);
                                @unlink($image_path);
                            }

                            $docs[$key] = $doc_name;
                        }
                    }
                }

                $docs = json_encode($docs);

                $user->update([
                    'status'       => $request->status,
                    'type'         => $request->type,
                    'phone_number' => $request->phone_number,
                    'username'     => $request->user_name,
                    'address'      => $request->address,
                    'image'        => $file_name,
                    'nid_passport' => $request->nid_passport,
                    'email'        => $request->email,
                    'docs'         => $docs
                ]);

                $message = 'User updated!';
            } else {
                $message = 'Something went wrong maybe invalid PIN.';
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }


        return redirect()->back()->with('message', $message);
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
//        dd($request->all());
        $this->validate($request,
            [
                'pin'       => 'required',
                'admin_pin' => 'required'
            ]);

        try {
            if ((int)$request->admin_pin === (int)auth()->user()->pin) {
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

    private function pr($data)
    {
        echo "<pre>";
        print_r($data);
        exit;
    }
}
