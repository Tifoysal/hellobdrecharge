<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\InboxModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class InboxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index(Request $request)
    {
        $inputs = $request->all();
        $from = isset($inputs['from']) ? urldecode($inputs['from']) : Carbon::today()->format('Y-m-d');
        $to = isset($inputs['to']) ? urldecode($inputs['to']) : Carbon::today()->format('Y-m-d');


    return view('admin.inbox.inboxDashboard',compact('from', 'to'));
    }

    public function search(Request $request)
    {
        $keyword = $request->get('search');


         $inbox = InboxModel::select('inbox.id', 'inbox.sender', 'inbox.sms_date', 'body', 'operators.opname','trx_id','trxid')
            ->leftjoin('transactions', 'inbox.trxid', '=', 'transactions.id')
            ->leftjoin('operators', 'inbox.operator', '=', 'operators.telco')
            ->orderby('id', 'DESC')
            ->where('body', 'LIKE', "%$keyword%")
            ->paginate(10);
            $inbox->appends(['inbox' => 'transactions.id']);
            $inbox->count();

    return view('admin.inbox.inboxDashboard',compact('inbox'));
    }

    public function data(DataTables $dataTables,Request $request)
    {
        $inputs = $request->all();

        $from = isset($inputs['from']) ? Carbon::createFromFormat('Y-m-d', urldecode($inputs['from']))->hour(0)->minute(0)->toDateTimeString() : Carbon::today();
        $to = isset($inputs['to']) ? Carbon::createFromFormat('Y-m-d', urldecode($inputs['to']))->hour(23)->minute(59)->toDateTimeString() : Carbon::now();



        $query = InboxModel::select('inbox.id', 'inbox.sender', 'inbox.created_at', 'body', 'operators.opname','trx_id','trxid')
            ->leftjoin('transactions', 'inbox.trxid', '=', 'transactions.id')
            ->leftjoin('operators', 'inbox.operator', '=', 'operators.telco')
            ->where('inbox.created_at', '>=', $from)
            ->where('inbox.created_at', '<=', $to)
            ->orderby('id', 'DESC')
            ->get();

        return $dataTables->collection($query)
            ->addIndexColumn()
            ->addColumn('action', function ($query) {
                return $query->action_buttons;
            })
            ->rawColumns(['action'])
            ->make(true);


    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request,
            ['name' => 'required', 'email' => 'required', 'password' => 'required', 'roles' => 'required']);

        $data = $request->except('password');
        $data['password'] = bcrypt($request->password);
        $user = User::create($data);

        foreach ($request->roles as $role) {
            $user->assignRole($role);
        }

        return redirect('admin/users')->with('flash_message', 'User added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
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
     * @param  int $id
     *
     * @return void
     */
    public function edit($id)
    {

        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    public function assign_sms(Request $request)
    {
        if($request)
        {
            $trxid=$request['trxId'];
            $userId = Auth::user()->id;
            $trxData=DB::select(DB::raw("SELECT amount,user_id,status
                from transactions where id=$trxid"));

            if(!empty($trxData))
            {
            if($trxData[0]->status=='pending' or $trxData[0]->status=='processing')
                {

            // ======================update trxid in inbox
                    DB::table('inbox')
                    ->where('id',$request['sms_id'])
                    ->update([
                        'trxid'=>$request['trxId']
                    ]);
            // ======================update transactions status
                    DB::table('transactions')
                    ->where('id',$request['trxId'])
                    ->update([
                        'status'=>$request['assign_sms_sts']
                    ]);

                    if($request['assign_sms_sts']=='cancel')
                    {
                        $currentBal = DB::select(DB::raw("SELECT current_balance,op_id
                            from operators_balance
                            where op_id =(select op_id from operators
                            where telco=(select telco from transactions
                            where id=$trxid))
                            order by id desc limit 1"));


                        $rtn_amount = $trxData[0]->amount;
                        $new_op_bal = ($currentBal[0]->current_balance) - ($trxData[0]->amount);
    // print_r($new_op_bal);exit();
    // deduct from operator
                        DB::table('operators_balance')
                        ->where('op_id',$currentBal[0]->op_id)
                        ->insert([
                            'op_id' => $currentBal[0]->op_id,
                            'transaction_head' => 'OUT',
                            'amount' => $rtn_amount,
                            'current_balance' => $new_op_bal,
                            'created_by' => $userId
                        ]);

    // add to the user
                        $UserCurrentBal = DB::table('users_balance')
                        ->select('current_balance')
                        ->where('user_id', $trxData[0]->user_id)
                        ->orderby('id', 'DESC')
                        ->first();

                        DB::table('users_balance')->insert([
                            'user_id' => $trxData[0]->user_id,
                            'transaction_head' => 'IN',
                            'amount' => $trxData[0]->amount,
                            'current_balance' => $UserCurrentBal->current_balance + $trxData[0]->amount,
                            'created_by' => $userId
                        ]);


                        session()->flash('message', 'Request Canceled');
                        return redirect('admin/inbox');
                    }

                    session()->flash('message', 'Requst Success');
                    return redirect('admin/inbox');

                }

                else
                {
                    session()->flash('error', 'Transaction already cancel or success');
                    return redirect('admin/inbox');

                }
            }
            else
            {
                 session()->flash('error', 'Invalid Transaction ID');
                    return redirect('admin/inbox');
            }

        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     *
     * @return void
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, ['name' => 'required', 'email' => 'required', 'roles' => 'required']);

        $data = $request->except('password');
        if ($request->has('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user = User::findOrFail($id);
        $user->update($data);

        $user->roles()->detach();
        foreach ($request->roles as $role) {
            $user->assignRole($role);
        }

        return redirect('admin/users')->with('flash_message', 'User updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return void
     */
    public function destroy($id)
    {
        User::destroy($id);

        return redirect('admin/users')->with('flash_message', 'User deleted!');
    }

    public function profile($id)
    {
        $user = User::findOrFail($id);
        if ($user->id == Auth::user()->id) {
            return view('admin.profile', compact('user'));
        }

        return redirect()->route('admin');
    }

    public function profileUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->email . ',email',
            'password' => 'min:6|confirmed',
        ]);


        $user->name = $request['name'];
        $user->phone = $request['phone'];
        $user->email = $request['email'];
        if (isset($request['password']) && !is_null($request['password'])) {
            $user->password = bcrypt($request['password']);
        }
        $user->save();
        return redirect(route('admin'));
    }
}
