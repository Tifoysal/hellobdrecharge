@extends('admin.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3>New Purchase Account</h3>
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

                    <form action="{{route('purchase.account.create')}}" method="post" role="form" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Account Name and Number<span class="required" style="color: red">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                   placeholder="Ex. DBBL (199.00.223.224) Uttara, Dhaka" required>
                        </div>
                        <div class="form-group">
                            <label for="type">Account Type<span class="required" style="color: red">*</span></label>
                            <select required class="form-control" name="type" id="type">
                                <option value="Mobile">Mobile</option>
                                <option value="Bank">Bank</option>
                                <option value="Agent">Agent</option>
                            </select>
                    </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control"
                                      placeholder="Description">{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-success">Create</button>
                            <a href="{{route('purchase.account.list')}}" class="btn btn-danger">Back</a>
                        </div>
                    </form>

            </div>
        </div>
    </div>
@stop


