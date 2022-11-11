@extends('admin.master')
@section('content')
    <div class="row p-4 ">
        <div class="col-md-2"></div>
        <div class="col-md-8 content-panel">


            <h3 class="text-center">User Registration</h3>
            <hr>
            @foreach ($errors->all() as $error)
                <p class="alert alert-danger">{{ $error }}</p>
            @endforeach
            @if (session('message'))
                <div class="alert alert-success alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">x</a>
                    {{ session('message')}}
                </div>
            @endif
            <form action="{{route('users.store')}}" method="post" role="form" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Full Name<span class="required" style="color: red">*</span></label>
                    <input type="text" name="username" class="form-control" value="{{ old('username') }}"
                           placeholder="Full Name" required>
                </div>

                <div class="form-group">
                    <label for="name">Email (optional)</label>
                    <input type="text" name="email" class="form-control" value="{{ old('email') }}" placeholder="Email">
                </div>
                <div class="form-group">
                    <label for="name">Password<span class="required" style="color: red">*</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
                </div>

{{--                <div class="form-group">--}}
{{--                    <label for="pin">PIN<span class="required" style="color: red">*</span></label>--}}
{{--                    <input maxlength="5" id="pin" type="password" name="pin" class="form-control" placeholder="Enter PIN" required>--}}
{{--                </div>--}}
                <div class="form-group">

                    <label for="name">Mobile Number<span class="required" style="color: red">*</span></label>
                    <input type="number" name="phone_number" class="form-control" value="{{ old('phone_number') }}"
                           placeholder="017xxxxxxxx" required>
                </div>

                <div class="form-group">
                    <label for="nid_passport">NID / Passport *: <span class="required" style="color: red">*</span></label>
                    <input type="text" name="nid_passport" class="form-control" value="{{ old('nid_passport') }}"
                           placeholder="Enter NID or Passport" required>
                </div>

                <div class="form-group">
                    <label for="address">Address<span class="required" style="color: red">*</span></label>
                    <textarea type="text" name="address" value="{{ old('address') }}" class="form-control"
                              placeholder="Address" required></textarea>
                </div>
                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="doc1">Doc 01 </label>
                                            <input type="file" class="form-control" id="doc1" name="docs['doc1']">
                                        </div>

                                    </div><div class="col-md-4">
                                        <div class="form-group">
                                            <label for="doc2">Doc 02 </label>
                                            <input type="file" class="form-control" id="doc2" name="docs['doc2']" >
                                        </div>

                                    </div><div class="col-md-4">
                                        <div class="form-group">
                                            <label for="doc3">Doc 03 </label>
                                            <input type="file" class="form-control" id="doc3" name="docs['doc3']">
                                        </div>

                                    </div>
                            </div>

                <div class="form-group">
                    <label for="image">Image :</label>
                    <input name="file" type="file" class="form-control" id="image">
                </div>

                <div class="form-group text-right">
                    <button type="submit" class="btn btn-success">Create</button>
                    <button type="button" class="btn btn-danger">Back</button>
                </div>
            </form>
        </div>
    </div>

@stop
