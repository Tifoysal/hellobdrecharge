<?php

namespace App\Http\Controllers;

use App\TransactionsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function create(Request $request)
    {
       
        $this->validate($request, [
            'mobile' => 'required|size:11',
            'amount' => 'required|numeric',
            'type' => 'required',
            'pin' => 'required',
            'email' => 'required',
            'password' => 'required',
            'req_operator' => 'required',
        ]);

        $credentials = [
            'email' => trim($request->input('email')),
            'password' => trim($request->input('password')),
            'status' => 1
        ];
 
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User is not authenticated.'
            ]);
        }


        if ((string)$user->pin === $request->input('pin')) {
            $data = $request->all();

            $currentBal = DB::table('users_balance')
            ->select('current_balance')
            ->where('user_id', $user->id)
            ->orderby('id', 'DESC')
            ->first();
            
            $cb = (double)$currentBal->current_balance;

            if ($cb > (double)$data['amount']) {

                $newBalance = $cb - (double)$data['amount'];
                $telco= $request->req_operator;

                if($data['type'] == '0') {
                    $data['type'] = 'prepaid';
                }

                if($data['type'] == '1') {
                    $data['type'] = 'postpaid';
                }

                // if ($tel === '017' || $tel === '016' || $tel === '018' || $tel === '019' || $tel === '015') {
                 if ($telco == '47001' or $telco == '47002' or $telco == '47003' or $telco == '47004' or $telco == '47007') 
                 {

                    $transaction = TransactionsModel::create([
                        'mobile' => $data['mobile'],
                        'user_id' => $user->id,
                        'amount' => (double)$data['amount'],
                        'type' => $data['type'],
                        'success_datetime' => date('Y-m-d H:i:s'),
                        'telco' => $telco,
                        'status' => 'pending',
                        'sender' => $user->phone_number
                    ]);


                    DB::table('users_balance')->insert([
                        'user_id' => $user->id,
                        'transaction_head' => 'OUT',
                        'amount' => (double)$data['amount'],
                        'current_balance' => $newBalance,
                        'created_by' => $user->id
                    ]);

                    $op_id = DB::table('operators')
                    ->select('op_id')
                    ->where('telco', $telco)
                    ->where('status', 'active')
                    ->first();

                    $OpcurrentBal=DB::table('operators_balance')
                    ->select('current_balance')
                    ->where('op_id',$op_id->op_id)
                    ->orderby('id','DESC')
                    ->first();

                    DB::table('operators_balance')
                    ->insert([
                      'op_id'=>$op_id->op_id,
                      'transaction_head'=>'IN',
                      'amount'=>(double)$data['amount'],
                      'current_balance'=>(double)$OpcurrentBal->current_balance+(double)$data['amount'],
                      'created_by'=>$user->id
                  ]); 

                    return response()->json([
                        'success' => true,
                        'id' => (int)$transaction->id,
                        'message' => 'Request sent to server.'
                    ]);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Operator.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Not enough Balance.'
            ]);

        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid PIN provided.'
        ]);
    }

    public function status($id)
    {
        $transaction = TransactionsModel::find($id);

        if (null !== $transaction) {
            return response()->json([
                'success' => true,
                'id' => (int)$transaction->id,
                'transaction_id' => (string)$transaction->transaction_id,
                'status' => $transaction->status
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid id provided.'
        ]);
    }
}
