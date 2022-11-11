@extends('admin.master')
@section('content')

    <div class="container-fluid content-panel">
        <div class="row dash-row">
            <div class="col-xl-4">
                <div class="stats stats-dark">
                    <h3 class="stats-title"> Total User </h3>
                    <div class="stats-content">
                        <div class="stats-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="stats-data">
                            <div class="stats-number">{{$user}}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="stats stats-success ">
                    <h3 class="stats-title"> Today's (Mobile Banking) </h3>
                    <div class="stats-content">
                        <div class="stats-icon">
                            <i class="fas fa-money-bill-alt"></i>
                        </div>
                        <div class="stats-data">
                            <div class="stats-number">{{$todays_mbanking}}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="stats stats-warning">
                    <h3 class="stats-title"> Today's (Recharge) </h3>
                    <div class="stats-content">
                        <div class="stats-icon">
                            <i class="fas fa-mobile"></i>
                        </div>
                        <div class="stats-data">
                            <div class="stats-number">{{$todays_recharge}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row dash-row">
            <div class="col-xl-4">
                <div class="stats stats-info">
                    <h3 class="stats-title"> Total Pending </h3>
                    <div class="stats-content">
                        <div class="stats-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="stats-data">
                            <div class="stats-number">{{$todays_pending}}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="stats stats-primary ">
                    <h3 class="stats-title">Total Success </h3>
                    <div class="stats-content">
                        <div class="stats-icon">
                            <i class="fas fa-money-bill-alt"></i>
                        </div>
                        <div class="stats-data">
                            <div class="stats-number">{{count($total_sale)}}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="stats stats-danger">
                    <h3 class="stats-title">Total</h3>
                    <div class="stats-content">
                        <div class="stats-icon">
                            <i class="fas fa-mobile"></i>
                        </div>
                        <div class="stats-data">
                            @if(auth()->user()->type=='admin')
                                <div class="stats-number">Sale:{{$total_sale->sum('user_charge')}}</div>
                                <div class="stats-number">Cost:{{$total_sale->sum('amount')}}</div>
                                <div class="stats-number">Profit:{{(float)$total_sale->sum('user_charge')-(float)$total_sale->sum('amount')}}</div>
                            @else
                                <div class="stats-number">Sale:{{$total_sale->sum('user_charge')}}</div>

                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
