@extends('admin.master')
@section('content')
    <style type="text/css">
        .center-text {
            margin: 15%;
        }
    </style>
    <div class="container-fluid content-panel">
        <div class="row dash-row" style="text-align: center">

            @if(auth()->user()->type!='admin')

                <div class="col-xl-4">
                    <a href="{{route(auth()->user()->type.'.request.create')}}">
                        <div class="stats stats-dark">
                            <h5 class="center-text"> Mobile Topup </h5>
                        </div>
                    </a>
                </div>



                <div class="col-xl-4">
                    <a href="{{route(auth()->user()->type.'.request.data.create')}}">
                        <div class="stats stats-success ">
                            <h5 class="center-text"> Internet and combo </h5>
                        </div>
                    </a>
                </div>



                <div class="col-xl-4">
                    @if(auth()->user()->type=='seller')
                        <a href="{{route(auth()->user()->type.'.mBanking.create')}}">
                            <div class="stats stats-warning">
                                <h5 class="center-text">Wallet Topup </h5>
                            </div>
                        </a>
                    @else
                        <div class="stats stats-primary ">
                            <h5 class="center-text" style="color: darkgrey">Wallet Topup</h5>
                        </div>
                    @endif
                </div>

            @endif
        </div>

        <div class="row dash-row">
            <div class="col-xl-4">
                @if(auth()->user()->type!='user')
                    <a href="{{route(auth()->user()->type.'.mBanking.index')}}">
                        <div class="stats stats-primary ">
                            <h5 class="center-text">Wallet Topup Report</h5>
                        </div>
                    </a>
                @else
                    <div class="stats stats-primary ">
                        <h5 class="center-text" style="color: darkgrey">Wallet Topup Report</h5>
                    </div>
                @endif
            </div>


            <div class="col-xl-4">
                <a href="{{route(auth()->user()->type.'.request.index')}}">
                    <div class="stats stats-info">
                        <h5 class="center-text"> Mobile Topup Report </h5>
                    </div>
                </a>
            </div>

            <div class="col-xl-4">
                @if(auth()->user()->type=='admin')
                    <a href="{{route('account')}}">
                        @else
                            <a href="{{route(auth()->user()->type.'.account')}}">
                                @endif
                                <div class="stats stats-danger " style="text-align: center">
                                    <h5 class="center-text"> Statement</h5>
                                </div>
                            </a>
            </div>

        </div>
    </div>
@stop
