@extends('admin.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3>Voice - Combo</h3>
            <div class="col-md-3">
            </div>
            <div class="col-md-6 content-panel">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @elseif(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @elseif(session()->has('error'))
                    <div class="alert alert-warning">
                        {{ session('error') }}
                    </div>
                @endif

                    <form action="{{route('package.create')}}" method="post" role="form" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Package Name<span class="required" style="color: red">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                   placeholder="Package Name" required>
                        </div>

                        <div class="form-group">
                            <label for="operator">Select Operator</label>
                            <select class="form-control" name="operator_id" id="operator">
                                <option value="">--Select Operator--</option>
                            @foreach($operators as $operator)
                                <option value="{{$operator->id}}">{{$operator->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="type">Select Type</label>
                            <select class="form-control" name="type" id="type">
                                <option value="">--Select Type--</option>
                                <option value="talktime">Talk Time</option>
                                <option value="internet">Internet</option>
                                <option value="combo">Combo</option>
                                <option value="malaysian_topup">Malaysian Top-up</option>

                            </select>
                        </div>

                        <div class="form-group">
                            <label for="name">User Charge <span class="required" style="color: red">*</span></label>
                            <input type="number" name="user_charge" class="form-control" value="{{ old('user_charge') }}" placeholder="User Charge">
                        </div>

                        <div class="form-group">
                            <label for="vendor_charge">Vendor Charge <span class="required" style="color: red">*</span></label>
                            <input id="vendor_charge" type="number" name="vendor_charge" class="form-control" value="{{ old('vendor_charge') }}" placeholder="Vendor Charge">
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control"
                                      placeholder="Description">{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-success">Create</button>
                            <a href="{{route('package.list')}}" class="btn btn-danger">Back</a>
                        </div>
                    </form>

            </div>
        </div>
    </div>
@stop


