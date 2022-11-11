
@extends('admin.master')
@section('content')


    <style type="text/css">
        .pac_img img {
            height: 60px;
            width: 60px;
            border-radius: 50px;
            display: block;
            margin: auto;
        }
    </style>

    <div class="row">
        <div class="col-lg-12 grid-margin">
            <div class="card">
                <div class="card spur-card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="spur-card-icon float-left">
                            <i class="fas fa-table"></i> Purchase List
                        </div>
                        <div class="spur-card-title float-right">
                            @if(auth()->user()->type!=='admin')
                            <a href="{{route(auth()->user()->type.'.recharge.form')}}" type="button" class="btn btn-success">New Purchase
                                <i class="mdi mdi-plus"></i>
                            </a>
                                @endif
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach ($errors->all() as $error)
                            <p class="alert alert-danger">{{ $error }}</p>
                        @endforeach
                        @if (session('message'))
                            <div class="alert alert-success alert-dismissable">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">x</a>
                                {{ session('message')}}
                            </div>
                        @endif
                        <h4 class="card-title">Purchase Lists</h4>
                        <!-- Button trigger modal -->


                        <div class="table-responsive pt-3">
                            <table id="user_dt" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-center">
                                        Sl
                                    </th>
                                    <th>Sender</th>
                                    <th >Uniq Trx ID</th>
                                    <th >Type</th>
                                    <th >Deposited Account</th>
                                    <th >Request Amount</th>
                                    <th >Received Amount</th>
                                    <th >Approved / Reject By</th>
                                    <th >Created Time</th>
                                    <th >Updated Time</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($recharges as $key=>$recharge)
                                    <tr>
                                        <td class="font-w600">
                                            {{$key+1}}
                                        </td>
                                        <td class="">
                                            {{$recharge->user->username}} ({{$recharge->user->phone_number}})
                                        </td>
                                        <td>{{$recharge->system_trx}}</td>
                                        <td>
                                            {{$recharge->type}}
                                        </td>
                                        <td>
                                            {{$recharge->deposit_account}}
                                        </td>
                                        <td>
                                            {{$recharge->amount}}
                                        </td>
                                        <td>
                                            {{$recharge->received_amount}}
                                        </td>

                                        <td>
                                            {{$recharge->updatedBy ? $recharge->updatedBy->username:''}}
                                        </td>
                                        <td>
                                            {{date('Y-m-d h:m:s A',strtotime($recharge->created_at))}}
                                        </td>
                                        <td>

                                            @if(strtotime($recharge->updated_at)-strtotime($recharge->created_at)!=0) {{date('Y-m-d h:m:s A',strtotime($recharge->updated_at))}}
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">{{$recharge->status}}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{route('recharge.show',$recharge->id)}}" class="btn btn-sm btn-info js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Delete">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                {{--                                <button data-toggle="modal" data-target="#exampleModal" onclick="function hi(id){$('#recharge_id').val(id);};hi({{$recharge->id}})">click</button>--}}
                                                @if($recharge->status=='pending' && auth()->user()->type=='admin')
                                                    <button id="edit" data-toggle="modal" data-target="#exampleModal" onclick="function hi(id){$('#recharge_id').val(id);};hi({{$recharge->id}})"class="btn btn-sm btn-primary js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Edit">
                                                        <i class="fa fa-check"></i>
                                                    </button>

                                                    <a onclick="return confirm('Are you sure you want to delete this item')" href="{{route('recharge.cancel',$recharge->id)}}" class="btn btn-sm btn-danger js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Delete">
                                                        <i class="fa fa-times"></i>
                                                    </a>

                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
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
        @push('javascript')
            <script type="text/javascript">
                $(document).ready(function () {
                    $('#user_dt').DataTable();
                });
            </script>
    @endpush
