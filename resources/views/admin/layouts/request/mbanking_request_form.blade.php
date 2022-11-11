@extends('admin.master')
@section('content')
    <div class="row">
        @if($service->status=='active')
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
                    <div class="col-md-12" style="text-align: center;color: green;">
                    <h4>Mobile Banking</h4>
                        <hr>
                    </div>
                <form action="{{route(auth()->user()->type.'.mBanking.store','mbanking')}}" method="post" role="form">
                    @csrf
                    <label>Enter Account NO * :</label>
                    <input value="{{old('req_mobile')}}" class="form-control" type="number" name="req_mobile" id="req_mobile" required
                           placeholder="017XXXXXXXX">


                    <label>Enter Amount * :</label>
                    <input value="50"  class="form-control" type="number" name="req_amount" id="req_amount" required
                           placeholder="Amount" min="50">


                    <label>Select Banking Type * :</label>
                    <select class="form-control" name="req_type" required>
                        <option value="bkash">Bkash</option>
                        <option value="rocket">Rocket</option>
                        <option value="dbbl">DBBL</option>
                        <option value="ucash">Ucash</option>
                        <option value="mcash">Mcash</option>
                        <option value="surecash">SureCash</option>
                        <option value="nagad">Nagad</option>
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



        @else
            <div class="unavailable">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/dd/Achtung.svg/1200px-Achtung.svg.png" alt="danger" style="width: 100px;">
                <p><h2>{{$service->notice}}</h2></p>
            </div>
        @endif
    </div>

    @if(Session::has('review'))
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background: green;color:white;">
                        <h5 class="modal-title" id="exampleModalLabel" >Order Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Transaction Number</th>
                                <th scope="col">{{Session::get('review')->tmp_trxid}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th scope="row">Date and Time</th>
                                <td>{{Session::get('review')->created_at}}</td>

                            </tr>
                            <tr>
                                <th scope="row">Transaction Type</th>
                                <td>{{Session::get('review')->trx_type}}</td>

                            </tr>
                            <tr>
                                <th scope="row">Mobile Number</th>
                                <td>{{Session::get('review')->mobile}}</td>

                            </tr>
{{--                            <tr>--}}
{{--                                <th scope="row">Amount (BDT)</th>--}}
{{--                                <td>{{Session::get('review')->amount}}</td>--}}
{{--                            </tr>--}}

                            <tr>
                                <th scope="row">User Charge (BDT)</th>
                                <td>{{Session::get('review')->user_charge}}</td>
                            </tr>

                            <tr>
                                <th scope="row">Before Deduction</th>
                                <td>{{(int)auth()->user()->balance + (int)Session::get('review')->amount}}</td>
                            </tr>

                            <tr>
                                <th scope="row">Balance</th>
                                <td>{{auth()->user()->balance}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Payment Status</th>
                                <td>{{Session::get('review')->status}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Transaction reference number</th>
                                <td>{{Session::get('review')->trx_id}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        {{--                    <button type="button" class="btn btn-primary">Save changes</button>--}}
                    </div>
                </div>
            </div>
        </div>
    @endif

@stop

@push('javascript')
    @if(Session::get('review'))
        <script>
            $(document).ready(function(){
                $("#exampleModal").modal('show');
            });
        </script>
    @endif
    <script>
        function clicked(e) {
            var number = document.getElementById("req_mobile").value;
            var amount = document.getElementById("req_amount").value;

            // if(!confirm('Are you sure?'))e.preventDefault();
            if (!confirm('Confirm Request to Number: ' + number + ' ,and Amount: ' + amount + ' ?')) e.preventDefault();
        }
    </script>
@endpush
