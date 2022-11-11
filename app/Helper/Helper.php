<?php

namespace App\Helper;

use App\Models\Statement;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use phpDocumentor\Reflection\Types\Boolean;

class Helper
{

    public static function generateToken($requestData = null)
    {
//        dd($requestData);
        $APIUserName = "wowpayhasbi";
        $APIPassword = "kr+uDAW9=<_^HA4_";
        $token = Http::withHeaders(['Authorization' => 'Basic ' . base64_encode($APIUserName . ':' . $APIPassword)])
            ->post('https://agentapi.paywellonline.com/Authentication/PaywellAuth/getToken');
        $tokenData = json_decode($token, true);

//get token and use it into security_token
//        $Request_data = json_encode(array("username"=>"wowpayhasbi",
//                                          "password"=>"3197"));
//        $Request_data = json_encode(array("username"=>"wowpayhasbi",
//                                          "password"=>"3197",
//                                          "ref_id"=>"A11111111111111",
//                                          "msisdn"=>"01671099723",
//                                          "amount"=>"10",
//                                          "con_type"=>"postpaid",
//                                          "operator"=>"AT"));
        $Request_data = json_encode($requestData);
//        dd($Request_data);
        $api_key = "cb8bdb22f11e8d3695badae28f77b70fe0b1277a8c1a77538d936e0e087356a3";
        $encryption_key = "3093f675c18438fc4dce132ac7f359b4887a858ce49676930c8e2006776279b2ffef505e92630b114f677ee46478180895ced42fef2a72c8bf32166c32d33a47";
        $security_token = $tokenData['token']['security_token'];
        $hashed_data = hash_hmac('sha256', $Request_data, $encryption_key);
        $bearer_token = base64_encode($security_token . ":" . $api_key . ":" . $hashed_data);

        return $bearer_token;
    }

    public function getBalance()//get paywell balance
    {
        $body = [
            'username' => 'wowpayhasbi',
            'password' => '3197'
        ];

        $url = 'https://agentapi.paywellonline.com/Retailer/RetailerService/retailerBalance';
        $response = $this->PostApi($url, $body);
        return $response->body();
    }

    public function getPackages($operator)//get paywell balance
    {
        $body = [
            'username' => 'wowpayhasbi',
            'operator' => strtoupper($operator)
        ];

        $url = 'https://agentapi.paywellonline.com/Recharge/mobileRecharge/topUpOffer';
        $response = $this->PostApi($url, $body);
        return $response->body();
    }

    public function rechargeApi($body)//recharge api call
    {
        $url = 'https://agentapi.paywellonline.com/Recharge/mobileRecharge/singleTopup';
        $response = $this->PostApi($url, $body);
        return $response;
    }

    private function GetApi($url)
    {
        $request = Http::get($url);
        return $request->body();
    }

    private function PostApi($url, $body)
    {
        $bearer = self::generateToken($body);
        $request = Http::withHeaders(['Authorization' => 'Bearer ' . $bearer, 'Content-type' => 'application/json'])
            ->post($url, $body);
        return $request;
    }

// insert data into statement
    public function statement($user_id, $trxno, $details, $debit, $credit, $balance,$requested_amount)
    {
        Statement::create([
            'user_id' => $user_id,
            'trxno' => $trxno,
            'details' => $details,
            'debit' => $debit,
            'credit' => $credit,
            'balance' => $balance,
            'requested_amount' => $requested_amount,
        ]);
    }

//@param $id


    public function refund(int $id, float $request_amount): bool
    {
        $customer = User::find($id);
        $admin = User::find(1);

        $lastId = Statement::all()->count();
        $temp_id = 'FR' . $lastId = $lastId + 1;

        if ((int)$admin->balance >= $request_amount) {
            $details = 'Balance Refund to-' . $customer->phone_number;
            DB::beginTransaction();
            $customer->increment('balance', $request_amount);
            $admin->decrement('balance', $request_amount);
            $this->statement($id, $temp_id, $details, 0, $request_amount, $customer->balance,$request_amount);
            DB::commit();
            return true;
        } else {
            return false;
        }
    }


    function userBalanceDecrement(int $user_id, float $amount)
    {
        User::find($user_id)->decrement('balance', $amount);
    }


    function userBalanceIncrement(int $user_id, float $amount)
    {
        User::find($user_id)->increment('balance', $amount);
    }



}

