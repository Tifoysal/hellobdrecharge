@extends('admin.master')
@section('content')
    <div class="container container-tab">
        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif
        @if(session()->has('message'))
            <p class="alert alert-success"> {{session()->get('message')}}</p>
        @endif
        <div class="row justify-content-left align-items-left">


            <div id="accordion" class="form-group col-12">
                <h2 class="mb-3 mb-sm-4 text-center">Purchase</h2>

                <div class="form-wrapper rounded">
                    <!--Wallet-->
                    <form action="{{route(auth()->user()->type.'.recharge.post','mobile_wallet')}}" role="form" method="post">
                        @csrf()

                        <div class="card rounded-0">
                            <div class="btn" data-toggle="collapse" data-target="#wallet" aria-expanded="false"
                                 aria-controls="collapseTwo">
                                <div class="card-header"
                                     style="text-align: left;background: purple;color: white;font-weight: bold;">
                                    Mobile Wallet
                                </div>
                            </div>
                            <div id="wallet" class="collapse" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="row text-left">
                                        <div class="form-group col-12">
                                            @foreach($account->where('type','Mobile') as $maccount )
                                            <label class="custom-control custom-radio">
                                                <input value="{{$maccount->name}}" id="{{$maccount->name}}" name="details[type]" type="radio"
                                                       class="">
                                                <span class="custom-control-indicator"></span>
                                                <span
                                                    class="custom-control-description">{{$maccount->name}}</span>
                                            </label>
                                            @endforeach

                                        </div>

                                        <div class="form-group col-12">
                                            <label class="mb-2">Amount:</label>
                                            <input name="details[amount]" required placeholder="Enter Amount"
                                                   id="amount" type="text" class="form-control rounded-0 mb-2">
                                        </div>

{{--                                        <div class="form-group col-12">--}}
{{--                                            <label class="mb-2">Sent From:</label>--}}
{{--                                            <input name="details[sent_from]" placeholder="Enter Payment from" id="from"--}}
{{--                                                   type="text" class="form-control rounded-0 mb-2">--}}
{{--                                        </div>--}}
{{--                                        <div class="form-group col-12">--}}
{{--                                            <label class="mb-2">Transaction ID:</label>--}}
{{--                                            <input name="details[trx_number]" placeholder="Enter Transaction ID"--}}
{{--                                                   id="transactionId" type="text" class="form-control rounded-0 mb-2">--}}
{{--                                        </div>--}}
                                        <div class="form-group col-12 text-center">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--Bank Payment-->
                    <form action="{{route(auth()->user()->type.'.recharge.post','bank')}}" role="form" method="post"
                          enctype="multipart/form-data">
                        @csrf()

                        <div class="card rounded-0">
                            <div class="btn" data-toggle="collapse" data-target="#bank" aria-expanded="false"
                                 aria-controls="collapseTwo">
                                <div class="card-header"
                                     style="text-align: left;background: purple;color: white;font-weight: bold;">
                                    Bank Payment
                                </div>
                            </div>
                            <div id="bank" class="collapse" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="row text-left">
                                        <div class="form-group col-12">
                                            @foreach($account->where('type','Bank') as $baccount )
                                            <label class="custom-control custom-radio">
                                                <input value="{{$baccount->name}}" id="{{$baccount->name}}" name="details[type]"
                                                       type="radio" class="">
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">
                                                   <p> {{$baccount->name}}</p>

                                                </span>
                                            </label>
                                            @endforeach
                                        </div>
                                        <div class="form-group col-12">
                                            <label class="mb-2">Amount:</label>
                                            <input name="details[amount]" required placeholder="Enter Amount"
                                                   id="amount" type="text" class="form-control rounded-0 mb-2">
                                        </div>
{{--                                        <div class="form-group col-12">--}}
{{--                                            <label class="mb-2">Account From:</label>--}}
{{--                                            <input name="details[sent_from]" placeholder="Enter your Account"--}}
{{--                                                   id="account" type="text" class="form-control rounded-0 mb-2">--}}
{{--                                        </div>--}}
{{--                                        <div class="form-group col-12">--}}
{{--                                            <label class="mb-2">Transaction ID:</label>--}}
{{--                                            <input name="details[trx_number]" placeholder="Enter Transaction ID"--}}
{{--                                                   id="transactionId2" type="text" class="form-control rounded-0 mb-2">--}}
{{--                                        </div>--}}
                                        <div class="form-group col-12 custom-file">
                                            <input name="details[receipt]" type="file" class="custom-file-input"
                                                   id="vehicleRegistration">
                                            <label class="custom-file-label" for="customFile">Attach Receipt (jpg,jpeg,png only)</label>
                                        </div>
                                        <div class="form-group col-12 text-center">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!--Agent Banking-->
                    <form action="{{route(auth()->user()->type.'.recharge.post','agent')}}" role="form" method="post"
                          enctype="multipart/form-data">
                        @csrf()

                        <div class="card rounded-0">
                            <div class="btn" data-toggle="collapse" data-target="#agent" aria-expanded="false"
                                 aria-controls="collapseTwo">
                                <div class="card-header"
                                     style="text-align: left;background: purple;color: white;font-weight: bold;">
                                    Agent Banking
                                </div>
                            </div>
                            <div id="agent" class="collapse" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="row text-left">
                                        <div class="form-group col-12">
                                            @foreach($account->where('type','Agent') as $Aaccount )
                                            <label class="custom-control custom-radio">
                                                <input value="{{$Aaccount->name}}" id="{{$Aaccount->name}}" name="details[type]"
                                                       type="radio" class="">
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">
                                                   <p> {{$Aaccount->name}}</p>
                                                </span>
                                            </label>
                                            @endforeach
                                        </div>
                                        <div class="form-group col-12">
                                            <label class="mb-2">Amount:</label>
                                            <input name="details[amount]" required placeholder="Enter Amount"
                                                   id="amount" type="text" class="form-control rounded-0 mb-2">
                                        </div>
{{--                                        <div class="form-group col-12">--}}
{{--                                            <label class="mb-2">Account From:</label>--}}
{{--                                            <input name="details[sent_from]" placeholder="Enter your Account"--}}
{{--                                                   id="account" type="text" class="form-control rounded-0 mb-2">--}}
{{--                                        </div>--}}
{{--                                        <div class="form-group col-12">--}}
{{--                                            <label class="mb-2">Transaction ID:</label>--}}
{{--                                            <input name="details[trx_number]" placeholder="Enter Transaction ID"--}}
{{--                                                   id="transactionId2" type="text" class="form-control rounded-0 mb-2">--}}
{{--                                        </div>--}}
                                        <div class="form-group col-12 custom-file">
                                            <input type="file" name="details[receipt]" class="custom-file-input"
                                                   id="vehicleRegistration">
                                            <label class="custom-file-label" for="customFile">Attach Receipt (jpg,jpeg,png only)</label>
                                        </div>
                                        <div class="form-group col-12 text-center">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>


        </div>
    </div>

@stop
