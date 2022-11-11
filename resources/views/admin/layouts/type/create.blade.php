@extends('admin.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3>Create Type</h3>
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

                    <form action="{{route('service.store')}}" method="post" role="form" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Type Name<span class="required" style="color: red">*</span></label>
                            <input type="text" name="name" class="form-control" value=""
                                   placeholder="Service Name" required>
                        </div>

                        <div class="form-group">
                            <label for="name">Type Code<span class="required" style="color: red">*</span></label>
                            <input type="text" name="code" class="form-control" value=""
                                   placeholder="Ex. 47001" required>
                        </div>


                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" name="status" id="status">
                                <option value="active">Active</option>
                                <option  value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-success">Create</button>
                            <a href="{{route('type.index')}}" class="btn btn-danger">Back</a>
                        </div>
                    </form>

            </div>
        </div>
    </div>
@stop


