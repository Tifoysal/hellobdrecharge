@extends('admin.master')
@section('content')

    @foreach ($errors->all() as $error)
        <p class="alert alert-danger">{{ $error }}</p>
    @endforeach
    @if (session('error'))
        <div class="alert alert-danger alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">x</a>
            {{ session('error')}}
        </div>
    @endif
    @if (session('message'))
        <div class="alert alert-success alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">x</a>
            {{ session('message')}}
        </div>
    @endif
    <form action="{{route('users.add_balance', $user->id)}}" method="POST" role="form">
        @csrf
        <div class="row">
            <div class="col-md-6 content-panel">

                <div class="form-group">
                    <h5><b>User Current Balance: {{$user->balance}} BDT</b></h5>
                </div>

                <div class="form-group">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Select Type</legend>
                    <input required type="radio" name="type" class="" value="add" id="add">
                    <label for="add">Add Credit</label>

                    <input required type="radio" name="type" class="" value="sub" id="sub">
                    <label for="sub">Deduct Credit</label>
                    </fieldset>
                </div>
                <div class="form-group">
                    <label for="amount">Enter Amount to Add/Deduct</label>
                    <input required placeholder="Enter amount to add or deduct" type="number" step="0.01" name="amount" class="form-control" id="amount" >
                </div>

                <div class="form-group">
                    <label for="pin">Enter Your PIN</label>
                    <input required placeholder="Enter Your PIN" type="password" name="pin" class="form-control" id="pin" >
                </div>

                <div class="form-group">
                    <label for="pin">Enter Reason</label>
                    <textarea class="form-control" name="reason" id="" cols="" rows="" placeholder="Reason.."></textarea>
                </div>
                <button onclick="confirm('Are you sure?')" type="submit" class="btn btn-success mr-2">Submit</button>
                <a href="{{route('users.data')}}" class="btn btn-danger">Back</a>
            </div>

        </div>


    </form>
@stop
