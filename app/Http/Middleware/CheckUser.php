<?php

namespace App\Http\Middleware;

use Brian2694\Toastr\Facades\Toastr;
use Closure;
use Illuminate\Http\Request;

class CheckUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
//        dd(auth()->user()->type);
        if(auth()->user()->type=='user')
        {
            return $next($request);
        }else
        {
            Toastr::warning('Permission denied', 'success', ["positionClass" => "toast-top-right"]);

            return redirect()->back();
        }
    }
}
