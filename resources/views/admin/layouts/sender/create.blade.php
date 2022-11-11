@extends('admin.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3>Sender Setup</h3>
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

                    <form action="{{route('sender.store')}}" method="post" role="form" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Sender Name<span class="required" style="color: red">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                   placeholder="Operator Name" required>
                        </div>

                        <div class="form-group">
                            <label for="number">Number <span class="required" style="color: red">*</span></label>
                            <input id="number" type="text" name="number" class="form-control" value="{{ old('number') }}" placeholder="Number">
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control"
                                      placeholder="Description">{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-success">Create</button>
                            <button type="button" class="btn btn-danger">Back</button>
                        </div>
                    </form>

            </div>
        </div>
    </div>
@stop


