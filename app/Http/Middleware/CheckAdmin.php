<?php

namespace App\Http\Middleware;


use Brian2694\Toastr\Facades\Toastr;
use Closure;
use Illuminate\Http\Request;

class CheckAdmin
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
        if(auth()->user()->type==='admin')
        {
            return $next($request);
        }else
        {
            Toastr::error('Permission denied');
            return redirect()->back();
        }
    }
}
