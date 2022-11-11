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
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Transaction Number</th>
                            <th scope="col">First</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope="row">Date and Time</th>
                            <td>Mark</td>

                        </tr>
                        <tr>
                            <th scope="row">Transaction Type</th>
                            <td>Jacob</td>

                        </tr>
                        <tr>
                            <th scope="row">Mobile Number</th>
                            <td>Larry</td>

                        </tr>
                        <tr>
                            <th scope="row">Amount (BDT)</th>
                            <td>Larry</td>
                        </tr>

                        <tr>
                            <th scope="row">Before Deduction</th>
                            <td>Larry</td>
                        </tr>

                        <tr>
                            <th scope="row">Balance</th>
                            <td>Larry</td>
                        </tr>
                        <tr>
                            <th scope="row">Payment Status</th>
                            <td>Larry</td>
                        </tr>
                        <tr>
                            <th scope="row">Transaction reference number</th>
                            <td>Larry</td>
                        </tr>
                        </tbody>
                    </table>

            </div>
            <div class="col-md-3">
            </div>
        </div>

    </div>
@stop

