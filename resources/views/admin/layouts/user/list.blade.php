@extends('admin.master')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card spur-card">
                <div class="card-header d-flex justify-content-between">
                    <div class="spur-card-icon float-left">
                        <i class="fas fa-table"></i> User/all
                    </div>
                    <div class="spur-card-title float-right">
                    <a type="button" class="btn btn-success" href="{{route('users.create')}}" >Registrations
                        <i class="mdi mdi-plus"></i>
                    </a>
                    </div>
                </div>
                <div class="card-body ">

                    <h4 class="card-title">User Lists</h4>


                    <table id="user_dt" class="table table-hover table-in-card">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Image</th>
                            <th scope="col">Full Name</th>
                            <th scope="col">Type</th>
                            <th scope="col">Mobile</th>
                            <th scope="col">Cash Balance</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $key=>$user)
                            <tr>
                                <th scope="row">{{$key+1}}</th>
                                <td class="d-none d-sm-table-cell">
                                    <img src="{{asset('/uploads/user/'.$user->image)}}" alt="" width="50px">
                                </td>
                                <td>{{$user->username}}</td>
                                <td> <span class="badge badge-pill badge-success">{{$user->type}}</span></td>
                                <td>{{$user->phone_number}}</td>
                                <td>{{$user->balance}}</td>

                                <td>
                                        <span class="badge
                                        @if($user->status=='active')
                                        badge-success
                                        @elseif($user->status=='inactive')
                                            badge-warning
                                            @else
                                            badge-danger
                                            @endif
                                        ">{{$user->status}}</span>
                                </td>
                                <td>
                                    <a href="{{route('users.edit',$user->id)}}" class="btn btn-success btn-sm"> <i class="fas fa-user-edit"></i> </a>

                                    <a href="{{route('users.add_balance',$user->id)}}" class="btn btn-primary btn-sm"><i class="fas fa-money-bill-alt"></i></a>
                                </td>
                            </tr>
                         @endforeach

                    </table>

                    <table class="table ">
                        <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><b>Total:</b></td>
                            <td><b>{{$users->sum('balance')}}</b></td>
                            <td></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                {{--                {{ $users->links()}}--}}
            </div>
        </div>
    </div>

@stop
@section('javascript')
    <script>
        $(document).ready(function() {
            $('#user_dt').DataTable();
        });
    </script>
@stop
