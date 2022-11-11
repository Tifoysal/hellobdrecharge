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
                <div class="content-panel">
                <form action="{{route('request.store')}}" method="post" role="form">
                    @csrf
                    <label>Enter Mobile NO * :</label>
                    <input class="form-control" type="number" name="req_mobile" id="req_mobile" required
                           placeholder="01XXXXXXXXX">


                    <label>Enter Amount * :</label>
                    <input class="form-control" type="number" name="req_amount" id="req_amount" required
                           placeholder="Amount" min="5">


                    <label>Select Type * :</label>
                    <select class="form-control" name="req_type" required>
                        <option selected="selected" value="prepaid">Prepaid</option>
                        <option value="postpaid">PostPaid</option>
                    </select>


                    <label>Select Operator:</label>
                    <select class="form-control" name="req_operator" required="required">
                        <option selected="" value="">--Select Operator--</option>
                        <option selected="" value="47001">Grameenphone</option>
                        <option selected="" value="47002">Robi</option>
                        <option selected="" value="47003">Banglalink</option>
                        <option selected="" value="47004">Teletalk</option>
                        <option selected="" value="47007">Airtel</option>
                    </select>


                    <label>Enter PIN * :</label>
                    <input class="form-control" type="password" name="pin" id="pin" required placeholder="pin">


                    <button type="submit" style="width: 100px; margin-top: 25px;"
                            class="btn btn-info btn-mar form-control"
                            onclick="clicked(event)">Send
                        <i class="glyphicon glyphicon-send"></i>
                    </button>
                    <!-- <input type="submit" onclick="clicked(event)" /> -->
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
