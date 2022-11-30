<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Mail\NotifyMail;
use App\Models\Operator;
use App\Models\Package;
use App\Models\Sender;
use App\Models\Service;
use App\Models\Statement;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use http\Exception\RuntimeException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Mockery\Exception;
use NunoMaduro\Collision\Adapters\Phpunit\State;
use phpDocumentor\Reflection\Exception\PcreException;
use function PHPUnit\Framework\throwException;

class RequestController extends Controller
{
    public function getRate($id)
    {
        $data=Service::find($id);
        return response()->json([
            'data'=>$data,
            'message'=>"success"
        ]);
    }
    public function index()
    {
        $from = isset($_GET['dateFrom']) ? urldecode($_GET['dateFrom']) : Carbon::now()->format('Y-m-d');
        $to = isset($_GET['dateTo']) ? urldecode($_GET['dateTo']) : Carbon::now()->format('Y-m-d');
        if (auth()->user()->type == 'admin') {
            $requests = Transaction::whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)
                ->where('trx_type', '!=', 'MB')->orderBy('id', 'desc')->get();

        } else {
            $requests = Transaction::where('user_id', auth()->user()->id)
                ->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)
                ->where('trx_type', '!=', 'MB')->orderBy('id', 'desc')->get();
        }

        return view('admin.layouts.request.list', compact('requests', 'from', 'to'));
    }

    public function create()
    {
        if(isset($_GET['service_id']))
        {
            $service = Service::find($_GET['service_id']);
            return view('admin.layouts.request.form', compact('service'));
        }
        $services = Service::all();
        return view('admin.layouts.request.select-service', compact('services'));
    }

    public function dataIndex()
    {
        $requests = Transaction::where('trx_type', 'DR')->get();
//        dd($requests);
        return view('admin.layouts.request.data_recharge_list', compact('requests'));
    }

    //data recharge start here
    public function dataCreate()
    {

//        $operators = Operator::with('hasPackage')->where('status', 'active')->get();
        $operators = Operator::where('status', 'active')->get();

        return view('admin.layouts.request.internet.operator-select', compact('operators'));
    }

    public function selectType($operator)
    {
        try {
            $packages=[];
            if(in_array($operator,Operator::OPERATOR))
            {
                $obj=new Helper();
                $data['packages']=json_decode($obj->getPackages($operator))->RechargeOffer;
                $data['operator']=$operator;
                if(Cache::has('packages'))
                {
                    Cache::forget('packages');
                }
                Cache::put('packages',$data);
            }
//        $packages = Package::where('operator', $operator)->where('status', 'active')->get();
        $service = Service::where('name', 'internet')->first();
            $types=[
                1=>'combo',
                2=>'talktime',
                3=>'internet'
            ];

//            return view('admin.layouts.request.internet.data_recharge_form', ['packages' => $packages, 'operator_id' => $operator, 'service' => $service]);

            return view('admin.layouts.request.internet.type-select', ['packages' => Cache::get('packages'),'types'=>$types,'service' => $service]);

        }catch (\Throwable $exception)
        {
            return redirect()->back()->withErrors('No Packages Found.');
        }
      }

    public function getPackages($type)
    {

//        $packages = Package::where('operator', $operator)->where('type', $type)->where('status', 'active')->get();
        $service = Service::where('name', 'internet')->first();
        $packages=Cache::get('packages');
        return view('admin.layouts.request.internet.data_recharge_form', ['packages' => $packages, 'service' => $service,'type'=>$type]);
    }

    //mobile banking start here
    public function mBankingCreate()
    {
        $service = Service::where('name', 'internet')->first();
        return view('admin.layouts.request.mbanking_request_form', compact('service'));
    }


    public function mBankingIndex()
    {
        $from = isset($_GET['dateFrom']) ? urldecode($_GET['dateFrom']) : Carbon::now()->format('Y-m-d');
        $to = isset($_GET['dateTo']) ? urldecode($_GET['dateTo']) : Carbon::now()->format('Y-m-d');
        if (auth()->user()->type == 'admin') {
            $requests = Transaction::with('sentFrom')
                ->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)->where('trx_type', 'MB')
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $requests = Transaction::with('sentFrom')
                ->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)
                ->where('user_id', auth()->user()->id)
                ->where('trx_type', 'MB')->orderBy('id', 'desc')->get();
        }
//        dd($requests);
        return view('admin.layouts.request.mbanking_list', compact('requests', 'from', 'to'));
    }

    public function mBankingEdit($id)
    {
        $request = Transaction::find($id);
        $senders = Sender::where('status', 'active')->get();
        return view('admin.layouts.request.mBanking_details', compact('request', 'senders'));
    }

    public function mBankingUpdate(Request $request, $id)
    {
//        dd($request->all());
        $request->validate([
            'status' => 'required'
        ]);

        $data = Transaction::find($id);
//        dd($data);
        if ($request->status != 'pending') {
            if (($request->status == 'cancel' || $request->status == 'success') && $data->status != 'cancel') {
                if ($request->status == 'cancel') {
                    //refund here
                    $helper = new Helper();
                    $refund = $helper->refund((int)$data->user_id, (int)$data->amount);
                    if ($refund == true) {
                        $data->update([
                            'trx_id' => $request->trx_id,
                            'status' => $request->status,
                            'sender' => $request->sender,
                        ]);
                        return redirect()->back()->with('message', 'Refund Success!');
                    } else {
                        return redirect()->back()->with('message', 'Nothing updated. May be insufficient admin balance.');
                    }
                } else {
                    $data->update([
                        'trx_id' => $request->trx_id,
                        'status' => $request->status,
                        'sender' => $request->sender,
                    ]);
                }
            } else {
                return redirect()->back()->with('message', 'Nothing to update.');
            }
        }
        return redirect()->back()->with('message', 'Request Updated Successfully.');
    }

    //request start here
    public function store(Request $_post, $type)
    {
        if ($type == 'recharge') {
            $_post->validate([
                'req_mobile' => 'required|size:11',
                'req_amount' => 'required|numeric',
                'total_deduction' => 'required|numeric|min:1',
                'req_type' => 'required',
                'pin' => 'required',
            ]);
        } elseif ($type == 'data') {
            $_post->validate([
                'req_mobile' => 'required|size:11',
//                'req_type' => 'required',
                'pin' => 'required',
                'package_id' => 'required',
            ]);
        } else {
            $_post->validate([
                'req_mobile' => 'required|size:11',
                'req_type' => 'required',
                'pin' => 'required',
            ]);
        }

        try {

            $id = $this->recharge($_post, $type);
            $review = Transaction::find($id);
            return redirect()->back()->with('review', $review)->withInput();

        } catch (Exception $e) {
            return redirect()->back()->with('message', $e->getMessage());
        }
    }

    //mobile recharge
    public function recharge($_post, $type)
    {
        if (auth()->user()->status === 'active') {
            $requested_amount = (int) $_post['req_amount'];

            if ($type == 'recharge') {
                $user_charge = (float) $_post['total_deduction'];
                $trx_type = 'RE';
                $service=Service::find($_post->service_id);

            } elseif ($type == 'data') {
                $package_id = $_post->package_id;
                $package =Cache::get('packages')['packages'][$package_id];
                $requested_amount = (int) $package->amount; //vendor charge or cost
                $user_charge =  round($package->amount+($package->amount * .25));
                $trx_type = 'DR';
            } else {
                $user_charge= (int)$_post['req_amount'];
                $trx_type = 'MB';
            }


            $userId = auth()->user()->id;
            $cb = (double)auth()->user()->balance;
            $numberLength = strlen($_post['req_mobile']);
            if ($cb >= $user_charge) {
                if ($numberLength === 11) {
                    if (auth()->user()->pin === $_post['pin']) {
                        $telco = $_post['req_operator'] ? $_post['req_operator'] : '';
                        try {
                            DB::beginTransaction();
                            $userIp=request()->ip();
                            $lastId = Statement::latest()->first();
                            $temp_id = 'FR' . $temp_id = (int)$lastId->id + 1;
                            if ($type == 'recharge') {
                                $recharge = Transaction::create([
                                    'mobile' => $_post['req_mobile'],
                                    'user_id' => $userId,
                                    'amount' => $requested_amount,
                                    'user_charge' => $user_charge,
                                    'type' => $_post['req_type'],
                                    'telco' => $telco,
                                    'status' => 'pending',
                                    'trx_type' => $trx_type,
                                    'rate' => $service->rate,
                                    'fees' => $service->fees,
                                    'service_id' => $service->id,
                                    'discount_commission' => $service->commission_discount,
                                    'total_deduction' => $_post['total_deduction'],
                                    'tmp_trxid' => $temp_id,
                                    'user_ip' => $userIp,
                                ]);
                            }
                            else{
                                $recharge = Transaction::create([
                                    'mobile' => $_post['req_mobile'],
                                    'user_id' => $userId,
                                    'amount' => $requested_amount,
                                    'user_charge' => $user_charge,
                                    'type' => $_post['req_type'],
                                    'telco' => $telco,
                                    'status' => 'pending',
                                    'trx_type' => $trx_type,
                                    'tmp_trxid' => $temp_id,
                                    'user_ip' => $userIp,
                                ]);
                            }
// send request to API and waiting for API response
                            //send api request
                            //MB=mobile banking
                            //update user balance
                            $helper = new Helper();


                            if ($trx_type != 'MB') {//only mobile recharge and data pack
                                $body = array(
                                    'username' => 'wowpayhasbi',
                                    'password' => '3197',
                                    'ref_id' => $temp_id,
                                    'msisdn' => $_post['req_mobile'],
                                    'amount' => $requested_amount,
                                    'con_type' => $_post['req_type'],
                                    'operator' => $telco,
                                );

                                $response = $helper->rechargeApi($body);

                                if ($response->offsetGet('data')['status'] == '200') {
                                    $recharge->update([
                                        'status' => 'success',
                                        'trx_id' => $response->offsetGet('data')['trans_id'],
                                        'message' => $response->offsetGet('data')['message']
                                    ]);

                                    //update admin balance
                                    $helper->userBalanceDecrement(auth()->user()->id,$user_charge);
                                    $helper->userBalanceIncrement(1,$user_charge);

                                    $details = 'Mobile topup-' . $_post['req_mobile'];
                                    $helper->statement(auth()->user()->id, $temp_id, $details, $user_charge, 0,auth()->user()->balance,$requested_amount );
                                } else {
                                    $recharge->update([
                                        'status' => 'failed',
                                        'trx_id' => $response->offsetGet('data')['status'],
                                        'message' => $response->offsetGet('data')['message']
                                    ]);
                                    $details = 'Top-up Failed :' . $response->offsetGet('data')['message'];
                                }
                            } else {
                                $helper->userBalanceDecrement(auth()->user()->id,$user_charge);
                                //update admin balance
                                $helper->userBalanceIncrement(1,$user_charge);
                                $details = 'Wallet topup-' . $_post['req_mobile'];
                                $helper->statement(auth()->user()->id, $temp_id, $details, $user_charge, 0,auth()->user()->balance,$requested_amount );
//                               dd($recharge);
                                Mail::to('hasbi.frhq@gmail.com')
                                    ->send(new NotifyMail($recharge)) ;
                            }
//
                            DB::commit();
                            return $recharge->id;

                        } catch (Exception $e) {
                            DB::rollBack();
//                            session()->flash('error', $e->getMessage());
                            throw new \ErrorException($e->getMessage());
                        }

//                    } else {
//                        session()->flash('error', 'Invalid Operator.');
//                    }
                    } else {
                        throw new Exception('Invalid PIN.');
                    }
                } else {
                    throw new Exception('Number Should be 11 digits.');
                }
            } else {
                throw new Exception('You have not Enough Balance');
            }
        } else {
            throw new Exception('Account Blocked. Please contact with Admin.');
        }
    }


    public function resend($id)
    {
        // call recharge method
        $data = Transaction::find($id);
        $requested_amount = (int)$data->amount;
        $user_charge = (int)$data->user_charge;

        $userId = $data->user_id;
        $user = User::find($userId);
        $cb = (double)$user->balance;
        $numberLength = strlen($data->mobile);

        if ($cb >= $user_charge) {
            if ($numberLength === 11) {
//                if (auth()->user()->pin === $_post['pin']) {
//                    $telco = $data->telco;
//                    if ($telco == '47001' or $telco == '47002' or $telco == '47003' or $telco == '47004' or $telco == '47007') {
                try {
                    DB::beginTransaction();

// send request to API and waiting for API response
                    //send api request
                    $body = array(
                        'username' => 'wowpayhasbi',
                        'password' => '3197',
                        'ref_id' => $data->tmp_trxid,
                        'msisdn' => $data->mobile,
                        'amount' => $requested_amount,
                        'con_type' => $data->type,
                        'operator' => $data->telco,
                    );
                    $helper = new Helper();
                    $response = $helper->rechargeApi($body);

                    if ($response->offsetGet('data')['status'] == '200') {
                        $data->update([
                            'status' => 'success',
                            'trx_id' => $response->offsetGet('data')['trans_id'],
                            'message' => $response->offsetGet('data')['message']
                        ]);

                        $user->decrement('balance',$user_charge);
                        //update admin balance
                        $helper->userBalanceIncrement(1,$user_charge);

                        $details = 'Resend Mobile topup-' . $data->mobile;
                        $helper->statement($user->id, $data->tmp_trxid, $details, $user_charge, 0,$user->balance,$requested_amount );
                        session()->flash('message', 'Recharge Success.');

                    } else {
                        $data->update([
                            'status' => 'failed',
                            'trx_id' => $response->offsetGet('data')['status'],
                            'message' => $response->offsetGet('data')['message']
                        ]);
                        session()->flash('message', $response->offsetGet('data')['message']);
                    }
                    DB::commit();

                } catch (Exception $e) {
                    DB::rollBack();
                    session()->flash('error', $e->getMessage());
                }

            } else {
                session()->flash('error', 'Number Should be 11 digits.');
            }
        } else {
            session()->flash('error', 'You have not Enough Balance');
        }
        return redirect()->back();
    }

    public function requestUpdate(Request $request, $id)
    {
        $request->validate([
            'status' => 'required'
        ]);

        $data = Transaction::find($id);
//        dd($data);
        if ($request->status != 'pending') {
            if (($request->status == 'cancel' || $request->status == 'success') && $data->status != 'cancel') {
                if ($request->status == 'cancel') {
                    //refund here
                    $helper = new Helper();
                    $refund = $helper->refund((int)$data->user_id, (float)$data->user_charge);
                    if ($refund == true) {
                        $data->update([
                            'trx_id' => $request->trx_id,
                            'status' => $request->status,
                            'sender' => $request->sender,
                        ]);
                        return redirect()->back()->with('message', 'Refund Success!');
                    } else {
                        return redirect()->back()->with('message', 'Nothing updated. May be insufficient admin balance.');
                    }
                } else {
                    $data->update([
                        'trx_id' => $request->trx_id,
                        'status' => $request->status,
                        'sender' => $request->sender,
                    ]);
                }
            } else {
                return redirect()->back()->with('message', 'Nothing to update.');
            }

        }


        return redirect()->back()->with('message', 'Request Updated Successfully.');
    }

    public function requestDetails($id)
    {
        $request = Transaction::with('user')->find($id);
        return view('admin.layouts.request.recharge_request_details', compact('request'));
    }

    public function requestUpdateForm($id)
    {
        $request = Transaction::with('user')->find($id);
        return view('admin.layouts.request.recharge_request_update', compact('request'));
    }


}
