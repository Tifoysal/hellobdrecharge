<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TransactionsController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 15;

        if (!empty($keyword)) {
            $tnx = Transaction::where('mobile', 'LIKE', "%$keyword%")->orWhere('success_datetime', 'LIKE',
                "%$keyword%")
                ->paginate($perPage);
        } else {
            $tnx = Transaction::paginate($perPage);
        }

        return view('admin.transaction.transactionDashboard', compact('tnx'));
    }

    public function show($id)
    {
        $td = Transaction::select('transactions.trx_id', 'transactions.mobile', 'transactions.amount',
            'transactions.type', 'transactions.success_datetime', 'transactions.status', 'inbox.body')
            ->leftjoin('inbox', 'transactions.id', '=', 'inbox.trxid')
            ->where('transactions.id', $id)
            ->first();

        return view('admin.transaction.transactionShow', compact('td'));
    }

    public function allTnxdata(DataTables $dataTables)
    {
        $userId = Auth::user()->id;
        $userType = Auth::user()->type;

        if ($userType == 'admin') {
            $query = Transaction::where('transactions.status', 'success')
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $query = Transaction::where('transactions.status', 'success')
                ->where('transactions.user_id', $userId)
                ->orderBy('id', 'desc')
                ->get();
        }


        return $dataTables->collection($query)
            ->addIndexColumn()
            ->addColumn('action', function ($query) {
                return $query->action_buttons;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.dashboard');
    }

    public function store(Request $_post)
    {
      $_post->validate([
        'req_mobile' => 'required|size:11',
        'req_amount' => 'required|numeric',
        'req_type' => 'required',
        'pin' => 'required',
      ]);

      $userId=Auth::user()->id;
      $userPin=DB::table('users')
      ->where('pin',$_post['pin'])
      ->where('id',$userId)
      ->first();

      $currentBal=DB::table('users_balance')
      ->select('current_balance')
      ->where('user_id',$userId)
      ->orderby('id','DESC')
      ->first();

      $cb=$currentBal->current_balance;
      $requested_amount=$_post['req_amount'];
      $numberLength=strlen($_post['req_mobile']);

      if($cb>$requested_amount)
      {
        if($numberLength==11)
        {
          if(null !== $userPin)
           {
            $tel=substr($_post['req_mobile'],0,3);
            if ($tel=='017')
            {
              $telco = '47001';
            }
            elseif($tel=='018')
            {
              $telco = '47002';
            }
            elseif($tel=='019')
            {
              $telco = '47003';
            }
            elseif($tel=='015')
            {
              $telco = '47004';
            }
            elseif($tel=='016')
            {
              $telco = '47007';
            }
            if($tel=='017' or $tel=='016' or $tel=='018' or $tel=='019' or $tel=='015')
            {
              DB::table('transactions')
              ->insert([
                'mobile'=>$_post['req_mobile'],
                'user_id'=>$userId,
                'amount'=>$requested_amount,
                'type'=>$_post['req_type'],
                'success_datetime'=>date("Y-m-d H:i:s"),
                'telco'=>$telco,
                'status'=>'pending',
                'sender'=>$_post['req_mobile']
              ]);

            // after sending request reduce balance from user account
            $newBalance=$cb-$requested_amount;

            DB::table('users_balance')->insert([
              'user_id'=>$userId,
              'transaction_head'=>'OUT',
              'amount'=>$requested_amount,
              'current_balance'=>$newBalance,
              'created_by'=>$userId
            ]);

            $op_id=DB::table('operators')
            ->select('op_id')
            ->where('telco',$telco)
            ->where('status','active')
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
              'amount'=>$requested_amount,
              'current_balance'=>$OpcurrentBal->current_balance+$requested_amount,
              'created_by'=>$userId
            ]);

            session()->flash('message', 'Request sent to server.');
              }
              else
              {
                session()->flash('error', 'Invalid mobile number.');
              }
            }
            else
            {
              session()->flash('error', 'Invalid PIN.');
            }
        }
        else
        {
          session()->flash('error', 'Number Should be 11 digits.');
        }
      }
      else
      {
        session()->flash('error', 'You have not Enough Balance');
      }

      return redirect('admin');
    }

    public function data(DataTables $dataTables)
    {
        $userId = Auth::user()->id;
        $userType = Auth::user()->type;
        // $query = RequestModel::all();
        if ($userType == 'admin') {
        // echo "string";exit();
            $query = Transaction::select('transactions.id','transactions.mobile','transactions.amount','transactions.type','transactions.status','transactions.success_datetime','users.username')
            ->leftjoin('users', 'transactions.user_id', '=', 'users.id')
            ->orderBy('transactions.id', 'desc')
            ->get();
        } else {
            $query = Transaction::where('user_id', $userId)
                ->orderBy('id', 'desc')
                ->get();
        }

        return $dataTables->collection($query)
            ->addIndexColumn()
            ->addColumn('action', function ($query) {
                return $query->action_buttons;
            })
            ->rawColumns(['action'])
            ->make(true);

    }


    public function edit($id)
    {
        return view('admin.modals.request');
    }


    public function Update(Request $_post)
    {

        $userId = Auth::user()->id;
        $_post->validate([
            'status' => 'required',
            'remarks' => 'required'
        ]);

        if ($_post)
        {
            $trxData = DB::table('transactions')->select('id', 'amount', 'user_id', 'telco', 'status')
            ->where('id', $_post['req_id'])
            ->first();


            $op_id=DB::table('operators')->select('op_id')
            ->where('telco',$trxData->telco)
            ->first();

            if (($_post['status'] == 'cancel') and ($trxData->status == 'pending' or $trxData->status == 'processing'))
            {
                DB::table('transactions')
                ->where('id', $_post['req_id'])
                ->update([
                    'trx_id' => $_post['remarks'],
                    'status' => $_post['status']

                ]);

                $currentBal = DB::select(DB::raw("SELECT `current_balance`
                    from `operators_balance`
                    where `op_id` =$op_id->op_id
                    order by `id` desc limit 1"));


                $rtn_amount = $trxData->amount;
                $new_op_bal = ($currentBal[0]->current_balance) - ($trxData->amount);

// deduct from operator
                DB::table('operators_balance')
                ->where('op_id',$op_id->op_id)
                ->insert([
                    'op_id' => $op_id->op_id,
                    'transaction_head' => 'OUT',
                    'amount' => $rtn_amount,
                    'current_balance' => $new_op_bal,
                    'created_by' => $userId
                ]);

// add to the user
                $UserCurrentBal = DB::table('users_balance')
                ->select('current_balance')
                ->where('user_id', $trxData->user_id)
                ->orderby('id', 'DESC')
                ->first();

                DB::table('users_balance')->insert([
                    'user_id' => $trxData->user_id,
                    'transaction_head' => 'IN',
                    'amount' => $trxData->amount,
                    'current_balance' => $UserCurrentBal->current_balance + $trxData->amount,
                    'created_by' => $userId
                ]);


                session()->flash('message', 'Request Canceled');
                return redirect('admin/reports');
            }
            elseif ((($_post['status'] == 'success') or ($_post['status'] == 'pending')) and  $trxData->status == 'processing')
            {
                if($_post['status'] == 'pending')
                {
                    DB::table('transactions')
                    ->where('id', $_post['req_id'])
                    ->update([
                        'trx_id' => $_post['remarks'],
                        'status' => $_post['status'],
                        'req_process' =>0
                    ]);
                }else
                {
                     DB::table('transactions')
                    ->where('id', $_post['req_id'])
                    ->update([
                        'trx_id' => $_post['remarks'],
                        'status' => $_post['status']
                    ]);
                }


                    session()->flash('message', 'Requst Success');
                    return redirect('admin/reports');
            }

            else
            {
                session()->flash('error', 'Request not updated. N.B:pending canbe cancel,processing can be success or cancel or Resend');
                return redirect('admin/reports');
            }
        }
    }
}
