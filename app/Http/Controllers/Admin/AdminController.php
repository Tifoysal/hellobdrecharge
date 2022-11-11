<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
         return view('admin.layouts.dashboard');
//         if(auth()->user()->type === 'admin' || auth()->user()->type === 'user' )
//         {
//            return view('admin.request.requestDashboard');
//         }else
//         {
//           return redirect()->action('Reports\ReportsController@index');
//         }

    }

}
