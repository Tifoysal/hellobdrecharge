@extends('admin.master')
@section('content')

    <style>
        p{
            background: purple;
            padding: 10px;
            border-radius: 10px;
            color:white;
            font-weight: bold;
        }
    </style>

    <p>{{$data['title']}} / {{$data['menu']}}</p>
    <div class="content-panel">
        <div class="row">
            <div class="col-md-6">
                <h3>Show Recharge Request # {{$recharge_data->system_trx}}</h3>
            </div>
            <div class="col-md-6" style="text-align: right;">
                <a href="{{route('recharge.list')}}" class="btn btn-primary"><i class="fa fa-arrow-circle-left"></i></a>
                <a href="{{route('dashboard')}}" class="btn btn-success"><i class="fa fa-home"></i></a>
            </div>
        </div>

        <hr>
        <div class="form-group">
            <div class="row">
            <div class="col-md-6">
                <label for="sender">Sender</label>
                <p>{{$recharge_data->user->username}} ({{$recharge_data->user->phone_number}})</p>
{{--                <label for="trx">Transaction ID</label>--}}
{{--                <p>{{$recharge_data->transaction_id}}</p>--}}
                <label for="status">Status: </label>
                <p class="badge badge-success">{{$recharge_data->status}}</p>
            </div>
            <div class="col-md-6">
{{--                <label for="sf">Sent From</label>--}}
{{--                <p>{{$recharge_data->sent_from}}</p>--}}
                <label for="ra">Requested Amount</label>
                <p>{{$recharge_data->amount}}</p>
            </div>
            </div>


            <label for="">Receipt</label>
            <img class="img-responsive img-thumbnail" src="{{asset('/uploads/receipts/'.$recharge_data->receipt)}}" alt="">
        </div>
        @if($recharge_data->status=='pending' && auth()->user()->type=='admin')
            <button id="edit" data-toggle="modal" data-target="#exampleModal" onclick="function hi(id){$('#recharge_id').val(id);};hi({{$recharge_data->id}})"class="btn btn-sm btn-primary js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Edit">
               Accept
            </button>

            <a data-confirm="Are you sure ?" href="{{route('recharge.cancel',$recharge_data->id)}}" class="btn btn-sm btn-danger js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Delete">
                Reject
            </a>

        @endif
    </div>



    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Verify</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('recharge.edit')}}" method="POST" role="form">
                    @csrf
                    <input type="hidden" value="" name="recharge_id" class="recharge_id" id="recharge_id">
                    <div class="modal-body">
                        <label for="pin">Please Enter PIN:</label>
                        <input id="pin" required type="password" class="form-control" name="pin" placeholder="Please Enter PIN">
                        <label for="received_amount">Enter Received Amount:</label>
                        <input id="received_amount" required type="number" class="form-control" name="received_amount" placeholder="Please Received Amount">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
