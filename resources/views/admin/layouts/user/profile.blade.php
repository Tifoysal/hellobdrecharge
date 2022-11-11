@extends('admin.master')
@section('content')
    @foreach ($errors->all() as $error)
        <p class="alert alert-danger">{{ $error }}</p>
    @endforeach
    @if (session('message'))
        <div class="alert alert-success alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">x</a>
            {{ session('message')}}
        </div>
    @endif
    <div class="col-md-12 content-panel" >
    <form enctype="multipart/form-data" action="#" method="POST" role="form">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6"> <h3>User Profile</h3></div>
            <div class="col-md-6" style="text-align: right"><h5><span class="badge badge-success">Balance: {{$user->balance}}  BDT</span></h5></div>
        </div>
        <hr>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleFormControlInput1">User Name</label>
                    <input readonly required type="text" class="form-control" id="exampleFormControlInput1" value="{{$user->username}}" name="user_name">
                </div>
                <div class="form-group">
                    <label for="email">User Email</label>
                    <input readonly type="email" class="form-control" id="email" value="{{$user->email}}" name="email">
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input readonly required name="phone_number" type="text" class="form-control" id="phone" value="{{$user->phone_number}}">
                </div>
            </div>

            <div class="col-md-6" style="">
                <div class="form-group" style="text-align: center">
                    <span><img style="width:150px;" src="{{asset('/uploads/user/'.$user->image)}}" alt="User Image"></span>
                </div>
            </div>
        </div>

{{--        <div class="row">--}}
{{--                <div class="col-md-4">--}}
{{--                    <div class="form-group">--}}
{{--                        <label for="balance">Doc 01 </label>--}}
{{--                        <input type="file" class="form-control" id="balance" name="doc1">--}}
{{--                    </div>--}}

{{--                </div><div class="col-md-4">--}}
{{--                    <div class="form-group">--}}
{{--                        <label for="balance">Doc 02 </label>--}}
{{--                        <input type="file" class="form-control" id="balance" name="doc2" >--}}
{{--                    </div>--}}

{{--                </div><div class="col-md-4">--}}
{{--                    <div class="form-group">--}}
{{--                        <label for="balance">Doc 03 </label>--}}
{{--                        <input type="file" class="form-control" id="balance" name="doc3">--}}
{{--                    </div>--}}

{{--                </div>--}}
{{--        </div>--}}

{{--        <div class="form-group">--}}
{{--            <label for="nid_passport">NID / Passport No *:</label>--}}
{{--            <input readonly type="text" placeholder="Enter NID or passport number." required class="form-control" name="nid_passport" id="nid_passport" value="{{$user->nid_passport}}">--}}
{{--        </div>--}}

{{--        <div class="form-group">--}}
{{--            <label for="address">Address *</label>--}}
{{--            <textarea readonly required class="form-control" name="address" id="address">{{$user->address}}</textarea>--}}

{{--        </div>--}}
{{--        <div class="form-group">--}}
{{--            <label for="pin">Enter PIN * </label>--}}
{{--            <input readonly required placeholder="Enter PIN" type="text" class="form-control" id="pin" name="pin">--}}
{{--        </div>--}}



{{--        <button type="submit" class="btn btn-success mr-2">Update</button>--}}
        <a href="{{route('users.data')}}" class="btn btn-primary">Back</a>
    </form>

        <hr>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <h3>Update password</h3>
                    <form action="{{route('profile.update.password',$user->id)}}" method="post" role="form">
                        @csrf
                        @method('put')

                            <div class="form-group">
                                <label for="password">Enter New Password * </label>
                                <input required placeholder="Enter Password" type="password" class="form-control" id="password" name="password">
                            </div>
                            <div class="form-group">
                                <label for="pin">Enter PIN * </label>
                                <input required placeholder="Enter PIN" type="pin" class="form-control" id="pin" name="pin">
                            </div>

                            <button class="btn btn-success" type="submit">Update</button>


                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <h3>Update PIN</h3>
                    <form action="{{route('profile.update.pin',$user->id)}}" method="post" role="form">
                        @csrf
                        @method('put')

                            <div class="form-group">
                                <label for="pin">Enter New PIN * </label>
                                <input required placeholder="Enter New PIN" type="text" class="form-control" id="pin" name="pin">
                            </div>
                            <div class="form-group">
                                <label for="old_pin">Enter Old PIN * </label>
                                <input required placeholder="Enter Old PIN" type="text" class="form-control" id="old_pin" name="old_pin">
                            </div>

                            <button class="btn btn-success" type="submit">Update</button>


                    </form>
                </div>
            </div>
        </div>


    </div>
@stop
