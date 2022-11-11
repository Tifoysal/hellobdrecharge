@extends('layouts.backend')

@section('dashboard-content')
    <style>
        .balance-transfer{
            margin-top: 100px;
            margin-bottom: 100px;
        }
    </style>
    <div class="container balance-transfer">
    <div class="row">
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
    <form role="form" action="{{route('balance.transfer_post')}}" method="POST">
        {{ csrf_field() }}
        <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Select User Number</label>
            <div class="col-sm-10" >
                <input type="email" required id="receiver_email" name="receiver_email" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label for="amount" class="col-sm-2 col-form-label">Amount</label>
            <div class="col-sm-10">
                <input name="amount" type="number" class="form-control" id="amount" placeholder="Amount" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="pin" class="col-sm-2 col-form-label">PIN</label>
            <div class="col-sm-10">
                <input name="pin" type="password" class="form-control" id="pin" placeholder="Pin" required>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-2"></div>
            <div class="col-sm-10">
                <button type="submit" onclick="clicked(event)" class="btn btn-primary form-control">Submit</button>
            </div>
        </div>
    </form>
    </div>
    </div>
    @endsection
@push('script')

    <script type="text/javascript">
        $('#example').select2({
            placeholder: 'Select a month'
        });

        function clicked(e)
        {
            var email=document.getElementById("receiver_email").value;
            var amount=document.getElementById("amount").value;
            // if(!confirm('Are you sure?'))e.preventDefault();
            if(!confirm('Confirm Request to Email: '+email+' ,and Amount: '+amount+ ' ?'))e.preventDefault();
        }
    </script>

@endpush
