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
                            <i class="fas fa-table"></i> Request List
                        </div>
                        <div class="spur-card-title float-right">
                            <a href="{{route('request.create')}}" type="button" class="btn btn-success">New Request
                                <i class="mdi mdi-plus"></i>
                            </a>
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
                        <h4 class="card-title">Request Lists</h4>
                        <!-- Button trigger modal -->


                        <div class="table-responsive pt-3">
                            <table id="user_dt" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>serial</th>
                                    <th>Temp. Transaction ID</th>
                                    <th>Sender</th>
                                    <th>DateTime</th>
                                    <th>Mobile</th>
                                    <th>User Charge</th>
                                    <th>Operator</th>
                                    <th>Transaction ID</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($requests as $key=>$data)

                                    <tr>
                                        <td class="font-weight-medium">{{$key+1}}</td>
                                        <td>{{$data->tmp_trxid}}</td>
                                        <td>{{$data->user->phone_number}}</td>
                                        <td>{{date('Y-m-d h:s:i A',strtotime($data->created_at))}}</td>
                                        <td>{{$data->mobile}}</td>
                                        <td>{{$data->user_charge}}</td>
                                        <td>{{$data->telco}}</td>
                                        <td>{{$data->trx_id}}</td>
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
                                        <td>
                                            <a href="{{route('request.show',$data->id)}}" class="btn"><i class="fa fa-eye"></i></a>
                                            <a href="{{route('request.update',$data->id)}}" class="btn"><i class="fa fa-pencil-alt"></i></a>
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
        @stop
        @section('javascript')
            <script type="text/javascript">
                $(document).ready(function () {
                    $('#user_dt').DataTable();
                });
            </script>
@endsection
