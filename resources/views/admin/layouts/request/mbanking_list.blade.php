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
                            <i class="fas fa-table"></i> Mobile Banking Request List
                        </div>
                        @if(auth()->user()->type=='seller')
                            <div class="spur-card-title float-right">
                                <a href="{{route(auth()->user()->type.'.mBanking.create')}}" type="button"
                                   class="btn btn-success">New Request
                                    <i class="mdi mdi-plus"></i>
                                </a>
                            </div>
                        @endif
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
                        <h4 class="card-title">Request Lists</h4>
                        <!-- Button trigger modal -->

                        <form action="" method="get" role="form">
                            <div class="row">
                                <div class="col-md-3 col-sm-3">
                                    <div class='input-group date' id='datetimepicker1'>
                                        <input type='date' class="form-control" name="dateFrom" value="{{$from}}"/>
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </div>

                                </div>
                                <div class="col-md-2 col-sm-2">
                                    <div class='input-group date' id='datetimepicker2'>
                                        <input type='date' class="form-control" name="dateTo" value="{{$to}}"/>
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </div>
                                </div>
                                <div class="col-md-1 col-sm-1">
                                    <button type="submit" style="width: 80px; margin-top: 0px;"
                                            class="btn btn-info btn-mar form-control">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>

                            </div>

                        </form>

                        <div class="table-responsive pt-3">
                            <table id="user_dt" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>serial</th>
                                    <th>Temp. Transaction ID</th>
                                    @if(auth()->user()->type=='admin')
                                        <th>Sender</th>
                                        <th>Trx Type</th>
                                    @endif
                                    <th>DateTime</th>
                                    <th>Mobile</th>
                                    <th>User Charge</th>
                                    <th>Type</th>
                                    {{--                                    <th>Transaction ID</th>--}}
                                    <th>Sent From</th>
                                    <th>Status</th>
                                    @if(auth()->user()->type!='user')
                                        <th>Action</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($requests as $key=>$data)
                                    <tr>
                                        <td class="font-weight-medium">{{$key+1}}</td>
                                        <td>{{$data->tmp_trxid}}</td>
                                        @if(auth()->user()->type=='admin')
                                            <td>{{$data->user->username}}({{$data->user->phone_number}})</td>
                                            <td>{{$data->trx_type}}</td>
                                        @endif
                                        <td>{{date('Y-m-d h:m:i A',strtotime($data->created_at))}}</td>
                                        <td>{{$data->mobile}}</td>
                                        <td>{{$data->user_charge}}</td>
                                        <td>{{$data->type}}</td>
                                        {{--                                        <td>{{$data->trx_id}}</td>--}}
                                        <td>{{$data->sentFrom?$data->sentFrom->name.'('.$data->sentFrom->number.')':''}}</td>
                                        <td>
                                            <span class="badge
                                                @if($data->status=='pending')
                                                'badge-info'
                                                @elseif ($data->status=='success')
                                                'badge-success'
                                                @elseif ($data->status=='failed')
                                                'badge-success'
@endif
                                                ">{{$data->status}}</span>
                                        </td>
                                        @if(auth()->user()->type!='user')
                                            <td>
                                                <a href="{{route(auth()->user()->type.'.mBanking.edit',$data->id)}}" class="btn"><i
                                                        class="fa fa-pencil-alt"></i></a>
                                            </td>
                                        @endif


                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
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
