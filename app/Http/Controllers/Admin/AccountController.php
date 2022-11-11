<?php

namespace App\Http\Controllers\Admin;

use App\Exports\StatementExport;
use App\Http\Controllers\Controller;
use App\Models\Statement;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserBalance;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Input;
use Maatwebsite\Excel\Facades\Excel;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function balanceTransfer()
    {
        $users = User::select('id', 'phone_number')->where('type', 'user')->where('status', 1)->get();
        return view('admin.account.user_balance_transfer', compact('users'));
    }

    public function balanceTransferPost(Request $request)
    {
        $userId = Auth()->user()->id;
        $request->validate([
            'receiver_email' => 'required',
            'amount' => 'required|numeric',
            'pin' => 'required|numeric',
        ]);

        $receiver_id = User::select('id')->where('email', $request->input('receiver_email'))->first();
//dd($receiver_id);
        if ($receiver_id) {
            $receiver_id = $receiver_id->id;

            if ($receiver_id != $userId) {

                $req_pin = $request->input('pin');

                $checkPin = User::select('pin')
                    ->where('pin', $req_pin)
                    ->where('id', $userId)
                    ->first();

                // print_r($balance.'-'.$reqBal);exit();


                if ($checkPin) {

                    //sender current balance
                    $currentBal = DB::table('users_balance')
                        ->select('current_balance')
                        ->where('user_id', $userId)
                        ->orderby('id', 'DESC')
                        ->first();

                    $balance = (int)$currentBal->current_balance;
                    $reqBal = (int)$request->input('amount');
                    $tnxhead = 'S';
                    //check sender has enough balance
                    if ($balance > $reqBal) {
                        $thisNewBalance = $balance - $reqBal;
                        $uLastId = DB::table('users_balance')
                            ->select('trx_id')
                            ->orderby('trx_id', 'DESC')
                            ->first();
                        $this_user_th = 'S';
                        DB::table('users_balance')->insert([
                            'trx_id' => $uLastId->trx_id + 1,
                            'user_id' => $userId,
                            'transaction_head' => $this_user_th,
                            'amount' => $reqBal,
                            'current_balance' => $thisNewBalance,
                            'created_by' => $userId,
                        ]);

                        $receiverCurrentBal = DB::table('users_balance')
                            ->select('current_balance')
                            ->where('user_id', $receiver_id)
                            ->orderby('id', 'DESC')
                            ->first();
                        $receiver_balance = (int)$receiverCurrentBal->current_balance;
                        $receiverNewBalance = $receiver_balance + $reqBal;
                        $receiver_uLastId = DB::table('users_balance')
                            ->select('trx_id')
                            ->orderby('trx_id', 'DESC')
                            ->first();
                        $receiver_th = 'A';
                        DB::table('users_balance')->insert([
                            'trx_id' => $receiver_uLastId->trx_id + 1,
                            'user_id' => $receiver_id,
                            'transaction_head' => $receiver_th,
                            'amount' => $reqBal,
                            'current_balance' => $receiverNewBalance,
                            'created_by' => $userId,
                        ]);
                        session()->flash('message', 'Balance Added to User!');
                        return redirect()->back();
                    } else {
                        session()->flash('error', 'Insufficient Balance!');
                        return redirect()->back();
                    }
                } else {
                    session()->flash('error', 'Invalid User PIN.');
                    return redirect()->back();
                }
            } else {
                session()->flash('error', 'You cannot transfer balance to your own account. ');
                return redirect()->back();
            }
        } else {
            session()->flash('error', 'Unknown Receiver.');
            return redirect()->back();
        }
    }

    public function index()
    {
        $tnx = "";

        $from = isset($_GET['dateFrom']) ? $_GET['dateFrom'] : Carbon::today()->format('Y-m-d');
        $to = isset($_GET['dateTo']) ? $_GET['dateTo'] : Carbon::today()->format('Y-m-d');
        $user = '';

        if ($_GET) {
            if (auth()->user()->type == 'admin') {
                if ($_GET['userId'] == 'allusers') {
                    $user = 'all';
                } else{
                    $user = $_GET['userId'];
                }
            } else {
                $user = auth()->user()->id;
            }


            if ($user!='all') {
                $tnx = Statement::select('trxno','created_at','details','debit','credit','balance')->with('user')
                    ->where('user_id', $user)
                    ->whereDate('created_at', '>=', $from)
                    ->whereDate('created_at', '<=', $to)
                    ->orderBy('id','desc');
//                    ->paginate(10);
//                dd($tnx);
            }
            else {
                $tnx = Statement::select('trxno','created_at','details','debit','credit','balance')->with('user')
                    ->whereDate('created_at', '>=', $from)
                    ->whereDate('created_at', '<=', $to)
                    ->orderBy('id','desc');
//                    ->paginate(10);
            }
            if(isset($_GET['type']))
            {
                return Excel::download(new StatementExport($tnx->get()), 'statement('.$from.' to '.$to.').xlsx');
            }
        }
        $tnx=$tnx?$tnx->paginate(10):'';

        $userList = User::select('id', 'username')
            ->get();

        return view('admin.layouts.account.accountDashboard',
            compact('tnx', 'userList', 'from', 'to', 'user'));
    }

    public function search($type = 'list')
    {
        dd($type);
        $userType = Auth::user()->type;
        $userId = Auth::user()->id;

        $from = isset($inputs['from']) ? urldecode($inputs['from']) : Carbon::today()->format('Y-m-d');
        $to = isset($inputs['to']) ? urldecode($inputs['to']) : Carbon::today()
            ->format('Y-m-d');

        $dateFrom = isset($_POST['dateFrom']) ? Carbon::createFromFormat('Y-m-d',
            urldecode($_POST['dateFrom']))->hour(0)->minute(0)->second(0)->toDateTimeString() : Carbon::today();
        $dateTo = isset($_POST['dateTo']) ? Carbon::createFromFormat('Y-m-d',
            urldecode($_POST['dateTo']))->hour(23)->minute(59)->second(59)->toDateTimeString() : Carbon::now();


        if ($userType == 'admin') {
            $opid = $_POST['accountOP'];
            $postUserId = $_POST['userId'];

            if (!empty($_POST['userId'])) {
                if ($_POST['userId'] == 'allusers') {
                    $filter = "all";

                    $tnx = DB::select(DB::raw("SELECT ub.trx_id,users.id,users.username,ub.transaction_head,ub.amount,ub.current_balance,ub.created_dt
            from users_balance as ub
            LEFT JOIN users
            on users.id=ub.user_id where users.type<>'admin' and ub.transaction_head <>'IN' and ub.transaction_head <>'OUT'
            and ub.created_dt>='$dateFrom' AND  ub.created_dt<= '$dateTo'
            order by ub.created_dt asc"));
                } else {
                    $filter = "ind";
                    $currentBal = DB::table('users_balance')
                        ->select('current_balance')
                        ->where('user_id', $postUserId)
                        ->orderby('id', 'DESC')
                        ->first();


                    $tnx = DB::select(DB::raw("SELECT ub.trx_id,users.id,users.username,ub.transaction_head,ub.amount,ub.current_balance,ub.created_dt
            from users_balance as ub
            LEFT JOIN users
            on users.id=ub.user_id where users.id=$postUserId
            and ub.transaction_head <>'IN' and ub.transaction_head <>'OUT'
            and ub.created_dt>='$dateFrom' AND  ub.created_dt<= '$dateTo'
            order by ub.created_dt asc"));
                }


            } else {
                if ($opid == '') {
                    $opid = 1;
                }

                if ($_POST['accountOP'] == 'allop') {
                    $filter = "allop";
                    $tnx = DB::select(DB::raw("SELECT ob.trx_id,operators.op_id,operators.opname,ob.transaction_head,ob.amount,ob.current_balance,ob.created_dt
                from operators_balance as ob
                LEFT JOIN operators
                on operators.op_id=ob.op_id
                where ob.transaction_head <>'IN' and ob.transaction_head <>'OUT'
                and ob.created_dt>='$dateFrom' AND  ob.created_dt<= '$dateTo'
                order by ob.created_dt asc "));
                } else {
                    $filter = "ind";
                    $currentBal = DB::table('operators_balance')
                        ->select('current_balance')
                        ->where('op_id', $opid)
                        ->orderby('id', 'DESC')
                        ->first();

                    $tnx = DB::select(DB::raw("SELECT ob.trx_id,operators.op_id,operators.opname,ob.transaction_head,ob.amount,ob.current_balance,ob.created_dt
                from operators_balance as ob
                LEFT JOIN operators
                on operators.op_id=ob.op_id
                where operators.op_id=$opid
                and ob.transaction_head <>'IN' and ob.transaction_head <>'OUT'
                and ob.created_dt>='$dateFrom' AND  ob.created_dt<= '$dateTo'
                order by ob.created_dt asc "));
                }

            }
        } else {
            $postUserId = $userId;
            $filter = "ind";
            $currentBal = DB::table('users_balance')
                ->select('current_balance')
                ->where('user_id', $postUserId)
                ->orderby('id', 'DESC')
                ->first();


            $tnx = DB::select(DB::raw("SELECT ub.trx_id,users.id,users.username,ub.transaction_head,ub.amount,ub.current_balance,ub.created_dt
            from users_balance as ub
            LEFT JOIN users
            on users.id=ub.user_id where users.id=$postUserId
            and ub.transaction_head <>'IN' and ub.transaction_head <>'OUT'
            and ub.created_dt>='$dateFrom' AND  ub.created_dt<= '$dateTo'
            order by ub.created_dt asc"));
        }

        if ($userType == 'admin') {
            $userList = DB::table('users')
                ->select('id', 'username')
                ->get();
            $opList = DB::table('operators')
                ->select('op_id', 'opname')
                ->get();
        } else {
            $userList = DB::table('users')
                ->select('id', 'username')
                ->where('id', $userId)
                ->get();
        }

        return view('admin.account.accountDashboard',
            compact('tnx', 'currentBal', 'userList', 'opList', 'from', 'to', 'filter'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
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
    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
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
     * @param int $id
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

    public function export()
    {
        $data=Statement::all();
        return Excel::download(new StatementExport($data), 'statement.xlsx');
    }
}
