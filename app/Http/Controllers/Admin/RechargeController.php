<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Mail\NotifyMail;
use App\Models\PurchaseAccount;
use App\Models\Recharge;
use App\Models\Statement;
use App\Models\User;
use App\Models\UserBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Mockery\Exception;

class RechargeController extends Controller
{
    public function list()
    {
        $data = [
            'title' => 'Recharge',
            'menu'  => 'list',
        ];
        if(auth()->user()->type=='admin')
        {
            $recharges = Recharge::with(['user', 'updatedBy'])
                ->orderBy('status', 'desc')
                ->orderBy('id', 'desc')
                ->get();
        }else{
            $recharges = Recharge::with(['user', 'updatedBy'])
                ->where('user_id',auth()->user()->id)
                ->orderBy('status', 'desc')
                ->orderBy('id', 'desc')
                ->get();
        }

//       dd($recharges);
        return view('admin.layouts.recharge.list', compact('recharges', 'data'));
    }

    //recharge request approval method
    public function edit(Request $request)
    {
        $request->validate([
            'pin'             => 'required',
            'received_amount' => 'required',
        ]);
        $id = $request->recharge_id;
        if ((int)$request->pin === (int)auth()->user()->pin) {
            $recharge_data = Recharge::find($id);
//            dd($recharge_data);
            $user = User::find($recharge_data->user_id);
            $userId = $user->id;
            $admin_balance = (float)auth()->user()->balance;
            $recharge_amount = (float)$request->received_amount;

            if ($admin_balance > $recharge_amount) {
                $th = 'CREDIT';
                $cb = (float)$user->balances + $recharge_amount;
                $adminb = $admin_balance - $recharge_amount;
                $adminTnxhead = 'DEBIT';
                DB::beginTransaction();
                //user balance create
                UserBalance::create([
                    'user_id'          => $userId,
                    'transaction_head' => $th,
                    'amount'           => $recharge_amount,
                    'current_balance'  => $cb,
                    'created_by'       => auth()->user()->id
                ]);
                //admin balance create
                UserBalance::create([
                    'user_id'          => auth()->user()->id,
                    'transaction_head' => $adminTnxhead,
                    'amount'           => $recharge_amount,
                    'current_balance'  => $adminb,
                    'created_by'       => auth()->user()->id
                ]);
                //update user balance
                User::find($userId)->increment('balance', $recharge_amount);
                //update admin balance
                User::find(auth()->user()->id)->decrement('balance', $recharge_amount);
                $recharge_data->update([
                    'status'=>'approved',
                    'updated_by'=>auth()->user()->id
                ]);
                $helper=new Helper();
                $helper->statement($userId,$recharge_data->system_trx,'Allocation',0,(int)$recharge_amount,User::find($userId)->balance);
                DB::commit();
                session()->flash('message', 'Balance Credited to User!');
                return redirect()->back();
            } else {
                return redirect()->back()->with('message', 'Admin Balance Insufficient');
            }

        } else {
            return redirect()->back()->with('message', 'Invalid User PIN');

        }
    }

    public function show($id)
    {
        $data = [
            'title' => 'Recharge',
            'menu'  => $id . ' /show',
        ];

            $recharge_data = Recharge::findOrFail($id);


        return view('admin.layouts.recharge.show', compact('data', 'recharge_data'));
    }

    public function cancel($id)
    {
        $recharge_data = Recharge::find($id);

        if($recharge_data){
            $recharge_data->update([
                'status' => 'cancel',
                'updated_by'=>auth()->user()->id
            ]);
//            dd($recharge_data);
            $message='Request Cancelled.';
        }else
        {
            $message='No Data found';
        }
        return redirect()->back()->with('message',$message);
    }

    public function recharge()
    {
        $account=PurchaseAccount::where('status','active')->get();
        return view('admin.layouts.recharge.recharge',compact('account'));
    }

    public function rechargePost(Request $request, $type)
    {
//        dd(auth()->user());
        $request->validate([
            'details.*'    => 'required',
            'details.type' => 'required'
        ]);
        $file_name = '';
        try {
            if ($request->file('details.receipt') != null) {
                $photo = $request->file('details.receipt');
                $file_name = uniqid('photo_', true) . mt_rand(10,10) . '.' . $photo->getClientOriginalName();
                $photo->storeAs('receipts', $file_name);
            }
            $lastId = Recharge::all()->count();
            $temp_id = 'PS' . $lastId = $lastId + 1;
            $data = [
                'user_id'         => auth()->user()->id,
                'system_trx'         =>$temp_id,
//                'transaction_id'  => $request->input('details.trx_number'),
                'type'            => $type,
                'deposit_account' => $request->input('details.type'),
                'amount'          => $request->input('details.amount'),
//                'sent_from'       => $request->input('details.sent_from'),
                'receipt'         => $file_name
            ];
            DB::beginTransaction();
            Recharge::create($data);
//            $body=[
//                'order_type'=>'Purchase',
//                'username'=>auth()->user()->username,
//                'amount'=>$request->input('details.amount'),
//                'number'=>auth()->user()->phone_number,
//                'transaction_id'=>$data['system_trx']
//            ];
//dd($body);
            $user=User::find(1);
//            Mail::to($user)->send(new NotifyMail($body));
            DB::commit();
        }catch (\Exception $e)
        {

            DB::rollBack();
            return redirect()->back()->withErrors('Something went wrong.');
        }

        return redirect()->back()->with('message',
            'Your request is submitted successfully. Please wait for Admin approval.');
    }
}
