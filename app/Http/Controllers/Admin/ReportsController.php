<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\TransactionsModel;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ReportsController extends Controller
{
    public function index(Request $request)
    {


        $inputs = $request->all();
        $from = isset($inputs['from']) ? urldecode($inputs['from']) : Carbon::today()->format('Y-m-d');
        $to = isset($inputs['to']) ? urldecode($inputs['to']) : Carbon::today()->format('Y-m-d');
        $status = $request->get('status') ?: 'all';
        $report_type = 1;



       $total=DB::table('transactions')
       ->SELECT(DB::raw('sum(amount) total'))
       ->whereDate('created_at', '>=', $from)
       ->whereDate('created_at', '<=', $to)
       ->first();

        $total = $total->total;


    return view('admin.reports.reportsDashboard', compact('status', 'report_type', 'from', 'to','total'));
    }


    public function data(DataTables $dataTables, Request $request)
    {

        $userId = Auth::user()->id;
        $userType = Auth::user()->type;
        $inputs = $request->all();

        $from = isset($inputs['from']) ? Carbon::createFromFormat('Y-m-d', urldecode($inputs['from']))->hour(0)->minute(0)->toDateTimeString() : Carbon::today();
        $to = isset($inputs['to']) ? Carbon::createFromFormat('Y-m-d', urldecode($inputs['to']))->hour(23)->minute(59)->toDateTimeString() : Carbon::now();

        if ('all' !== $request->get('status')) {
            if ($userType === 'admin' or $userType === 'support') {
                $query = TransactionsModel::select('transactions.id', 'transactions.mobile', 'transactions.amount', 'transactions.type', 'transactions.status', 'transactions.success_datetime', 'transactions.trx_id', 'transactions.sim_amt', 'users.username','operators.opname')
                    ->where('transactions.status', $request->get('status'))
                    ->where('transactions.created_at', '>=', $from)
                    ->where('transactions.created_at', '<=', $to)
                    ->leftjoin('users', 'transactions.user_id', '=', 'users.id')
                     ->leftjoin('operators', 'transactions.telco', '=', 'operators.telco')
                    ->orderBy('transactions.id', 'desc')
                    ->get();
            } else {
                $query = TransactionsModel::select('transactions.id', 'transactions.mobile', 'transactions.amount', 'transactions.type', 'transactions.status', 'transactions.success_datetime', 'transactions.trx_id', 'transactions.sim_amt', 'users.username','operators.opname')
                    ->where('transactions.status', $request->get('status'))
                    ->where('users.id', $userId)
                    ->where('transactions.created_at', '>=', $from)
                    ->where('transactions.created_at', '<=', $to)
                    ->leftjoin('users', 'transactions.user_id', '=', 'users.id')
                    ->leftjoin('operators', 'transactions.telco', '=', 'operators.telco')
                    ->orderBy('transactions.id', 'desc')
                    ->get();
            }
        } else {
            if ($userType === 'admin' or $userType === 'support') {
                $query = TransactionsModel::select('transactions.id', 'transactions.mobile', 'transactions.amount', 'transactions.type', 'transactions.status', 'transactions.success_datetime', 'transactions.trx_id', 'transactions.sim_amt', 'users.username','operators.opname')
                    ->leftjoin('users', 'transactions.user_id', '=', 'users.id')
                    ->leftjoin('operators', 'transactions.telco', '=', 'operators.telco')
                    ->where('transactions.created_at', '>=', $from)
                    ->where('transactions.created_at', '<=', $to)
                    ->orderBy('transactions.id', 'desc')
                    ->get();
            } else {
                $query = TransactionsModel::select('transactions.id', 'transactions.mobile', 'transactions.amount', 'transactions.type', 'transactions.status', 'transactions.success_datetime', 'transactions.trx_id', 'transactions.sim_amt', 'users.username','operators.opname')
                    ->where('users.id', $userId)
                    ->where('transactions.created_at', '>=', $from)
                    ->where('transactions.created_at', '<=', $to)
                    ->leftjoin('users', 'transactions.user_id', '=', 'users.id')
                    ->leftjoin('operators', 'transactions.telco', '=', 'operators.telco')
                    ->orderBy('transactions.id', 'desc')
                    ->get();
            }
        }

        return $dataTables->collection($query)
            ->addIndexColumn()
            ->addColumn('action', function ($query) {
                return $query->action_buttons;
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function balanceMgmt()
    {
        $report_type = 2;
        $admin = DB::SELECT(DB::raw("SELECT sum((select current_balance from users_balance where user_id=users.id order by id
        desc limit 1)) as admin_balance from users where type='admin'"));
        $adminBal = $admin[0]->admin_balance;


        $users = DB::SELECT(DB::raw("SELECT sum((select current_balance from users_balance where user_id=users.id order by id
        desc limit 1)) as total_users_balance from users where type!='admin'"));

        $usersBal = $users[0]->total_users_balance;

        $operators = DB::SELECT(DB::raw("SELECT sum((select current_balance from operators_balance where op_id=operators.op_id order by id desc limit 1)) as total_operators_balance from operators"));

        $operatorsBal = $operators[0]->total_operators_balance;


        return view('admin.reports.reportsDashboard', compact('report_type', 'adminBal', 'usersBal', 'operatorsBal'));
    }
}
