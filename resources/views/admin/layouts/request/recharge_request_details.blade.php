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

                    <p>
                    <label>Request By :</label> {{$request->user->username}} ({{$request->user->phone_number}})
                    </p>
                    <p>
                    <label>Mobile Number :</label> {{$request->mobile}}
                    </p>
                    <p>
                    <label>Amount :</label> {{$request->amount}}
                    </p><p>
                    <label>Total Deduction :</label> {{$request->total_deduction}}
                    </p>
                    <p>
                    <label>Transaction Message (from API) :</label>
                        <textarea class="form-control" name="" id="">  {{$request->message}}</textarea>

                    </p>
{{--                    <label>Enter PIN * :</label>--}}
{{--                    <input class="form-control" type="password" name="pin" id="pin" required placeholder="pin">--}}

                    <a href="{{route(auth()->user()->type.'.request.index')}}" style="width: 100px; margin-top: 25px;"
                            class="btn btn-info btn-mar form-control">Back
                        <i class="glyphicon glyphicon-send"></i>
                    </a>
                    <!-- <input type="submit" onclick="clicked(event)" /> -->
{{--                </form>--}}
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
