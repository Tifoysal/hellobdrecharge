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
                <h3>Request Details</h3>
                <div class="col-md-12 content-panel">
                <form action="{{route('admin.mBanking.update',$request->id)}}" method="post" role="form">
                    @csrf
                    @method('put')
                    <p>
                    <label>Request By :</label> {{$request->user->username}}
                    </p><p>
                    <label>Mobile Number :</label> {{$request->mobile}}
                    </p>
                    <p>
                    <label>Amount :</label> {{$request->amount}}
                    </p>
                    <p>
                    <label>Status :</label> {{ucfirst($request->status)}}
                    </p>
                    <p>
                    <label>Note :</label>
                        <textarea placeholder="Note..." class="form-control" name="trx_id" id="">{{$request->trx_id}}</textarea>

                    </p>

                    @if(auth()->user()->type=='admin')
                    <div class="form-group">
                        <label for="sender">Select Sent From :</label>
                        <select name="sender" id="sender" class="form-control" required>
                            @foreach ($senders as $sender)
                                <option value="{{$sender->id}}">{{$sender->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select required class="form-control" name="status" id="">
                            <option @if($request->status=='pending') selected @endif value="pending">Pending</option>
{{--                            <option value="processing">Processing</option>--}}
                            <option @if($request->status=='cancel') selected @endif value="cancel">Cancel-Refund</option>
{{--                            <option value="failed">Failed</option>--}}
                            <option @if($request->status=='success') selected @endif value="success">Success</option>
                        </select>
                    </div>
                    @if($request->status!='cancel')
                    <button type="submit" class="btn btn-primary">Update</button>
                    @endif
                    @endif

                    <a href="{{route(auth()->user()->type.'.mBanking.index')}}"
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
