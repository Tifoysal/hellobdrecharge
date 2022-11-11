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

                    <form action="{{route('purchase.account.update',$account->id)}}" method="post" role="form" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label for="name">Account Name and Number<span class="required" style="color: red">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ $account->name }}"
                                   placeholder="Ex. DBBL (199.00.223.224) Uttara, Dhaka" required>
                        </div>
                        <div class="form-group">
                            <label for="type">Account Type<span class="required" style="color: red">*</span></label>
                            <select required class="form-control" name="type" id="type">
                                <option @if($account->type=='Mobile') selected @endif  value="Mobile">Mobile</option>
                                <option @if($account->type=='Bank') selected @endif  value="Bank">Bank</option>
                                <option  @if($account->type=='Agent') selected @endif value="Agent">Agent</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="status">Account Status<span class="required" style="color: red">*</span></label>
                            <select required class="form-control" name="status" id="status">
                                <option @if($account->status=='active') selected @endif  value="active">Active</option>
                                <option @if($account->status=='inactive') selected @endif  value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control"
                                      placeholder="Description">{{ $account->description }}</textarea>
                        </div>

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-success">Update</button>
                            <a href="{{route('purchase.account.list')}}" type="button" class="btn btn-danger">Back</a>
                        </div>
                    </form>

            </div>
        </div>
    </div>
@stop


