@extends('admin.master')
@section('content')
    <div id="app" class="row">
        @if($service)
            @if($service->status=='active')
                <div class="col-md-12">
                    <h3>Send Request </h3>

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

                            <form action="{{route(auth()->user()->type.'.request.store','recharge')}}" method="post"
                                  role="form">

                                @csrf
                                <input type="hidden" name="service_id" class="service_id" value="{{$service->id}}">
                                <label>Enter Mobile Number * :</label>
                                <input class="form-control" type="number" name="req_mobile" id="req_mobile" required
                                       value="{{old('req_mobile')}}" placeholder="017XXXXXXXX">

                                <label>Enter Amount * :</label>
                                <input class="amount form-control" type="number"
                                       name="req_amount" id="req_amount" required
                                       placeholder="Amount" min="10">


                                {{--                        <label>Select Type * :</label>--}}
                                {{--                        <select class="form-control" name="req_type" required>--}}
                                {{--                            <option selected="selected" value="prepaid">Prepaid</option>--}}
                                {{--                            <option value="postpaid">PostPaid</option>--}}
                                {{--                        </select>--}}
                                <input type="hidden" name="req_type" value="prepaid">

                                <label>Select Operator:</label>
                                <select class="form-control" name="req_operator" required="required">
                                    <option value="">--Select Operator--</option>
                                    <option value="GP">Grameenphone</option>
                                    <option value="RB">Robi</option>
                                    <option value="BL">Banglalink</option>
                                    <option value="TT">Teletalk</option>
                                    <option value="AT">Airtel</option>
                                    <option value="GP ST">Skitto</option>
                                </select>


                                <label>Rate :</label>
                                <input class="form-control rate" type="text" id="rate" readonly>

                                <label>User Charge :</label>
                                <input class="form-control user_charge" type="text" id="user_charge" readonly>

                                <label>Fees :</label>
                                <input class="form-control fees" type="text" id="fees" readonly>

                                <label>Commission/Discount (%) :</label>
                                <input class="form-control commission" type="text" id="commission" readonly>

                                <label>Total Deduction :</label>
                                <input name="total_deduction" required class="form-control total_deduction" type="text" id="total_deduction" readonly>

                                <label>Enter PIN * :</label>
                                <input class="form-control" type="password" name="pin" id="pin" required
                                       placeholder="Your PIN">


                                <button type="submit" style="width: 100px; margin-top: 25px;"
                                        class="btn btn-success btn-mar form-control"
                                        onclick="clicked(event)">Send
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                                <a href="" style=" margin-left:10px;width: 100px; margin-top: 25px;"
                                   class="btn btn-danger"> <i class="fa fa-home" aria-hidden="true"></i> Back</a>

                            </form>
                        </div>
                    </div>
                    <div class="col-md-3">
                    </div>
                </div>
            @else
                <div class="unavailable">
                    <img
                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/dd/Achtung.svg/1200px-Achtung.svg.png"
                        alt="danger" style="width: 100px;">
{{--                    <p>--}}
{{--                    <h2>{{$service->notice}}</h2></p>--}}
                </div>
            @endif
        @else
            <div class="unavailable">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/dd/Achtung.svg/1200px-Achtung.svg.png"
                     alt="danger" style="width: 100px;">
                <p>
                <h2>No service found. Please contact with administrator</h2></p>
            </div>
        @endif

    </div>
    @if(Session::has('review'))
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background: green;color:white;">
                        <h5 class="modal-title" id="exampleModalLabel">Order Details</h5>
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
                            {{--                        <tr>--}}
                            {{--                            <th scope="row">Amount (BDT)</th>--}}
                            {{--                            <td>{{Session::get('review')->amount}}</td>--}}
                            {{--                        </tr>--}}

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

    <script type="text/javascript">
        document.querySelector(".amount").addEventListener("keyup", function (e) {
            if (e.target.value) {
                var service_id=document.querySelector(".service_id").value
                fetch("http://hellobdrecharge.test/api/get-rate/"+service_id)
                {{--fetch(<?php isset($_SERVER["HTTPS"]) ? 'https' : 'http'; ?>"//hellobd.biz/api/get-rate/"+service_id)--}}
                    .then((resp) => resp.json()).then((data) => {
                    document.querySelector(".rate").value = data.data.rate
                    var fees=parseFloat(document.querySelector(".fees").value = data.data.fees)
                    var commission=parseFloat(document.querySelector(".commission").value = data.data.commission_discount)
                    var user_charge=parseFloat(document.querySelector(".user_charge").value = (e.target.value/data.data.rate).toFixed(2))
                    document.querySelector(".total_deduction").value = (user_charge+fees+commission).toFixed(2)
                })
            } else {
                document.querySelector(".rate").value =0
                document.querySelector(".fees").value = 0
                document.querySelector(".commission").value = 0
                document.querySelector(".user_charge").value = 0
                document.querySelector(".total_deduction").value = 0
                document.querySelector(".amount").value =''
                console.log("empty");
            }


        })
    </script>

    @if(Session::get('review'))
        <script>
            $(document).ready(function () {
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
