@extends('admin.master')
@section('content')
    <div class="row">

        <div class="col-md-12">
            <div class="col-md-3">
            </div>
            <div class="col-md-6">
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
                <h3>Request Update</h3>
                <div class="col-md-12 content-panel">
                    <form action="{{route('request.update',$request->id)}}" method="post" role="form">
                        @csrf
                        @method('put')
                        <p>
                            <label>Request By :</label> {{$request->user->username}}
                        </p>
                        <p>
                            <label>Mobile Number :</label> {{$request->mobile}}
                        </p>
                        <p>
                            <label>Amount :</label> {{$request->amount}}
                        </p>

                        <p>
                            <label>Status :</label> <span class="badge badge-success">{{ucfirst($request->status)}}</span>
                        </p>
                        <p>
                            <label>Transaction Message (from API) :</label>
                            <textarea class="form-control" name="" id="">  {{$request->message}}</textarea>

                        </p>
                        <div class="form-group">
                            <label>Select Status:</label>
                            <select class="form-control" name="status" id="">
                                <option value="">Select Status</option>
{{--                                <option @if($request->status=='pending') selected @endif value="pending">Pending</option>--}}
{{--                                <option @if($request->status=='processing') selected @endif value="processing">Processing</option>--}}
                                <option @if($request->status=='success') selected @endif value="success">Success</option>
                                <option @if($request->status=='cancel') selected @endif value="cancel">Cancel</option>
{{--                                <option @if($request->status=='failed') selected @endif value="failed">Failed</option>--}}
                            </select>
                             </div>

                        <div class="form-group">
                            <label>Enter PIN * :</label>
                            <input class="form-control" type="password" name="pin" id="pin" required placeholder="pin">

                        </div>
                        @if($request->status!='cancel')
                            <button type="submit" class="btn btn-primary">Update</button>
                        @endif
                        <a href="{{route(auth()->user()->type.'.request.index')}}"
                           class="btn btn-info">Back
                            <i class="glyphicon glyphicon-send"></i>
                        </a>

                    </form>
                </div>
            </div>
            <div class="col-md-3">
            </div>
        </div>

    </div>
@stop

@push('script')



    <script>
        function clicked(e) {
            var number = document.getElementById("req_mobile").value;
            var amount = document.getElementById("req_amount").value;
            // if(!confirm('Are you sure?'))e.preventDefault();
            if (!confirm('Confirm Request to Number: ' + number + ' ,and Amount: ' + amount + ' ?')) e.preventDefault();
        }
    </script>
@endpush
