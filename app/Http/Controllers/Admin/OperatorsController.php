<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\OperatorModel;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class OperatorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 15;

        if (!empty($keyword))
        {
            $operators = OperatorModel::where('opname', 'LIKE', "%$keyword%")->orWhere('telco', 'LIKE', "%$keyword%")
                ->paginate($perPage);
        } else {
            $users = OperatorModel::paginate($perPage);
        }

        return view('admin.operators.operatorsDashboard', compact('operators'));
    }

    public function data(DataTables $dataTables)
    {
       $query=OperatorModel::select(DB::raw('* ,(select current_balance from operators_balance where op_id=operators.op_id order by id desc limit 1) currentBalance'))
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
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        // $roles = Role::select('id', 'name', 'label')->get();
        // $roles = $roles->pluck('label', 'name');

        return view('admin.operators.create');
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
        $userId = Auth::user()->id;
        $this->validate($request, ['opname' => 'required', 'telco' => 'required', 'amount' => 'required']);

        $data = $request->except('amount');
        $data['created_by'] = $userId;

        // print_r($data);exit();
        $lastId = OperatorModel::create($data)->op_id;

        DB::TABLE('operators_balance')->insert(
            [
                'op_id' => $lastId,
                'amount' => $request->input('amount'),
                'transaction_head' => 'OB', //OB for opening Balance
                'created_by' => $userId
            ]);
        return redirect('admin/operators')->with('flash_message', 'Operator added!');
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
        $operator = OperatorModel::findOrFail($id);

        return view('admin.operators.show', compact('operator'));
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
        // $operator = OperatorModel::findOrFail($id);
        $operator = OperatorModel::select('operators.op_id', 'amount', 'telco', 'opname')
            ->leftjoin('operators_balance', 'operators.op_id', '=', 'operators_balance.op_id')
            ->where('operators.op_id', '=', $id)
            ->first();


        return view('admin.operators.edit', compact('operator'));
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

        $this->validate($request, ['opname' => 'required', 'amount' => 'required', 'telco' => 'required']);

        $data = $request->except('amount');
        $amount = $request->only('amount');
        // print_r($data);exit();
        $operator = OperatorModel::findOrFail($id);
        $operator->update($data);
        DB::table('operators_balance')
            ->where('id', $id)
            ->update($amount);

        return redirect('admin/operators')->with('flash_message', 'Operator updated!');
    }

    public function addBalance($id)
    {
        $userId=Auth::user()->id;
        $userType=Auth::user()->type;

        $currentBal = DB::table('operators_balance')
                    ->select('current_balance')
                    ->where('op_id', $id)
                    ->orderby('id', 'DESC')
                    ->first();
        $req_pin=request()->input('pin');

        $balance=$currentBal->current_balance;


        if($_POST)
        {
            $req_pin=request()->input('pin');
            $checkPin = DB::table('users')
                    ->select('pin')
                    ->where('pin', $req_pin)
                    ->where('id',$userId)
                    ->first();

            if($checkPin)
            {
              if($userType=='admin')
                {
                $adminBal=DB::table('users_balance')
                ->select('current_balance')
                ->where('user_id',$userId)
                ->orderby('id','DESC')
                ->first();

                $reqBal=request()->input('balance');
                $cb=$currentBal->current_balance;
                $ab=$adminBal->current_balance;

                $tnxhead=request()->input('tnxhead');
                if($tnxhead=='add')
                {
                   if($reqBal<$ab)
                   {
                    $newBal=$cb+$reqBal;
                    $adminb=$ab-$reqBal;
                    $adminTnxhead='S';
                    $th='A';
                   }else
                   {
                    session()->flash('error', 'Admin has not enough balance to add !');
                    return redirect('admin/operators');
                   }
                }else
                {
                   if($reqBal<$cb)
                   {
                      $th='S';
                      $newBal=$cb-$reqBal;
                      $adminb=$ab+$reqBal;
                      $adminTnxhead='A';
                    }else
                    {
                    session()->flash('error', 'Operator has not enough balance to Sub !');
                    return redirect('admin/operators');
                    }
                }

                $opLastId=DB::table('operators_balance')
                ->select('trx_id')
                ->orderby('trx_id','DESC')
                ->first();

                $uLastId=DB::table('users_balance')
                ->select('trx_id')
                ->orderby('trx_id','DESC')
                ->first();

                DB::table('operators_balance')
                ->insert([
                    'trx_id'=>$opLastId->trx_id+1,
                    'op_id'=>$id,
                    'transaction_head'=>$th,
                    'amount'=>$reqBal,
                    'current_balance'=>$newBal,
                    'created_by'=>$userId
                ]);
                 DB::table('users_balance')
                 ->insert([
                  'trx_id'=>$uLastId->trx_id+1,
                  'user_id'=>$userId,
                  'transaction_head'=>$adminTnxhead,
                  'amount'=>$reqBal,
                  'current_balance'=>$adminb,
                  'created_by'=>$userId
                ]);


            // print_r($currentBal->current_balance);exit();
                }
                session()->flash('message', 'Balance Updated Successfully !');
                return redirect('admin/operators');
            }
            else
            {
            session()->flash('error', 'Invalid PIN !');
            return redirect('admin/operators');
            }

        }
        else
        {

        return view('admin.operators.add_balance',compact('id','balance'));
        }
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
        // OperatorModel::destroy($id);

        return redirect('admin/operators')->with('flash_message', 'Permission Protected!');
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
