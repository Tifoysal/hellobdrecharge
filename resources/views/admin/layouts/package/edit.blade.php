@extends('admin.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3>Package Update</h3>
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

                    <form action="{{route('package.update',$package->id)}}" method="post" role="form" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label for="name">Package Name<span class="required" style="color: red">*</span></label>
                            <input valu type="text" name="name" class="form-control" value="{{ $package->name }}"
                                   placeholder="Package Name" required>
                        </div>

                        <div class="form-group">
                            <label for="operator">Select Operator</label>
                            <select class="form-control" name="operator_id" id="operator">
                                <option value="">--Select Operator--</option>
                            @foreach($operators as $operator)
                                <option @if($package->operator==$operator->id) selected @endif value="{{$operator->id}}">{{$operator->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="type">Select Type</label>
                            <select class="form-control" name="type" id="type">
                                <option value="">--Select Type--</option>
                              <option @if($package->type==='talktime') selected @endif value="talktime">TalkTime</option>
                                <option @if($package->type==='internet') selected @endif value="internet">Internet</option>
                                <option @if($package->type==='combo') selected @endif value="combo">Combo</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="name">User Charge <span class="required" style="color: red">*</span></label>
                            <input type="number" name="user_charge" class="form-control" value="{{ $package->user_charge }}" placeholder="User Charge">
                        </div>

                        <div class="form-group">
                            <label for="vendor_charge">Vendor Charge <span class="required" style="color: red">*</span></label>
                            <input id="vendor_charge" type="number" name="vendor_charge" class="form-control" value="{{ $package->vendor_charge }}" placeholder="Vendor Charge">
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control"
                                      placeholder="Description">{{ $package->description }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <select class="form-control" name="status" id="">
                                <option @if($package->status=='active') selected @endif value="active">Active</option>
                                <option @if($package->status=='inactive') selected @endif value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-success">Update</button>
                            <button type="button" class="btn btn-danger">Back</button>
                        </div>
                    </form>

            </div>
        </div>
    </div>
@stop


