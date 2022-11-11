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
                            <i class="fas fa-table"></i> Operator List
                        </div>
                        <div class="spur-card-title float-right">
                            <a href="{{route('operator.create')}}" type="button" class="btn btn-success">New Operator
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
                        <h4 class="card-title">Operator List</h4>
                        <!-- Button trigger modal -->


                        <div class="table-responsive pt-3">
                            <table id="user_dt" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>serial</th>
                                    <th>Logo</th>
                                    <th>Name</th>
                                    <th>Operator Code</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($operators as $key=>$operator)

                                    <tr>
                                        <td class="font-weight-medium">{{$key+1}}</td>
                                        <td><img style="width: 50px;height: 50px" src="{{asset('/uploads/operator/'.$operator->logo)}}" alt="{{$operator->name}}"></td>
                                        <td>{{$operator->name}}</td>
                                        <td>{{$operator->opcode}}</td>
                                        <td>
                                            <span class="badge @if($operator->status=='active') badge-success @else badge-danger @endif ">
                                                {{ucfirst($operator->status)}}
                                            </span>
                                        </td>
                                        <td>
{{--                                            <a href="" class="btn btn-info"><i class="fa fa-eye"></i></a>--}}
                                            <a href="{{route('operator.edit',$operator->id)}}" class="btn btn-primary"><i class=""></i>Edit</a>
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
        @push('javascript')
            <script type="text/javascript">
                $(document).ready(function () {
                    $('#user_dt').DataTable();
                });
            </script>
    @endpush
