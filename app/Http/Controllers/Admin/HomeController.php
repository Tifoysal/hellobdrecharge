<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user=User::where('status','active')->count();
        if(auth()->user()->type!='admin')
        {
            $todays_recharge=Transaction::where('user_id',auth()->user()->id)->where('trx_type','RE')->where('status','success')->whereDate('s_datetime',carbon::today())->sum('amount');
            $todays_mbanking=Transaction::where('user_id',auth()->user()->id)->where('trx_type','DR')->where('status','success')->whereDate('s_datetime',carbon::today())->sum('amount');
            $todays_pending=Transaction::where('user_id',auth()->user()->id)->where('status','pending')->count();
            $total_sale=Transaction::where('user_id',auth()->user()->id)->where('status','success')->whereDate('s_datetime',carbon::today())->get();
        }else
        {
            $todays_recharge=Transaction::where('trx_type','RE')->where('status','success')->whereDate('s_datetime',carbon::today())->sum('amount');
            $todays_mbanking=Transaction::where('trx_type','MB')->where('status','success')->whereDate('s_datetime',carbon::today())->sum('amount');
            $total_sale=Transaction::where('status','success')->get();
            $todays_pending=Transaction::where('status','pending')->count();
        }

        return view('admin.layouts.dashboard',compact('user','todays_recharge','todays_mbanking','total_sale','todays_pending'));
    }

    public function registerForm()
    {
        dd("test");
        return view('auth.register');
    }

    protected function createUser(Request $data)
    {
//        dd($request->all());
        $user = User::create([
            'username' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone'],
            'password' => bcrypt($data['password']),
            'balance' => 0
        ]);
        DB::TABLE('users_balance')->insert(
            [
                'user_id' => $user->id,
                'amount' => 0,
                'transaction_head' => 'OB', //OB for opening Balance
                'created_by' => $user->id
            ]);

//        $user->assignRole('user');

        return redirect()->back();
    }
}
