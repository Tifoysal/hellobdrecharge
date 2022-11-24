@extends('admin.master')
@section('content')
    <div class="row">
        @if($service->status=='active')
            <div class="col-md-12">
                <h3>Send Request </h3>
                <div class="col-md-12">
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
                    @if(count($packages)>0)

                        <div class="content-panel">
                                <div class="">

                                    <div class="tab-content" id="myTabContent" style="padding: 10px;">
                                        @if(isset($packages['packages']))
                                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                                 aria-labelledby="home-tab">
                                                    <h1>All {{$type==1?'Combo':($type==2?'Talk Time':'Internet')}} Offers</h1>

                                                    <table class="table table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col">Name</th>
                                                            <th scope="col">Amount</th>
                                                            <th scope="col">Number</th>
                                                            <th scope="col">PIN</th>
                                                            <th scope="col">Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($packages['packages'] as $key=>$package)

                                                            @if($package->type==$type)
                                                          <form action="{{route(auth()->user()->type.'.request.store','data')}}" method="post"
                                                                      role="form">
                                                              @csrf
                                                              <input type="hidden" name="package_id" value="{{$key}}">
                                                              <input type="hidden" name="req_type" value="prepaid">
                                                              <input type="hidden" name="req_operator" value="{{$packages['operator']}}">
                                                        <tr>
                                                            <th scope="row">{{$key+1}}</th>
                                                            <td>{{$package->remarks}}</td>
                                                            <td>{{$package->amount+($package->amount*.25)}} BDT</td>
                                                            <td>
                                                                <input class="form-control" type="text" name="req_mobile" id="req_mobile" required
                                                                       value="" placeholder="017XXXXXXXX">
                                                                  </td>

                                                            <td>
                                                                <input class="form-control" type="password" name="pin" id="pin" required
                                                                       placeholder="pin">
                                                            </td>

                                                            <td>
                                                                <button type="submit" style="width: 100px;"
                                                                        class="btn btn-success btn-mar form-control"
                                                                        onclick="clicked(event)">Send
                                                                    <i class="fas fa-paper-plane"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                          </form>
                                                        @endif
                                                        @endforeach

                                                        </tbody>
                                                    </table>

                                            </div>

                                        @endif
                                    </div>
                                </div>

                        </div>
                        @else
                            <div class="unavailable">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/dd/Achtung.svg/1200px-Achtung.svg.png"
                                     alt="danger" style="width: 100px;">
                                <p>
                                <h2>No package found.</h2></p>
                            </div>
                    @endif
                </div>
                <div class="col-md-3">
                </div>
            </div>

        @else
            <div class="unavailable">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/dd/Achtung.svg/1200px-Achtung.svg.png"
                     alt="danger" style="width: 100px;">
                <p>
                <h2>{{$service->notice}}</h2></p>
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
                            {{--                            <tr>--}}
                            {{--                                <th scope="row">Amount (BDT)</th>--}}
                            {{--                                <td>{{Session::get('review')->amount}}</td>--}}
                            {{--                            </tr>--}}

                            <tr>
                                <th scope="row">Before Deduction</th>
                                <td>{{Session::get('review')->status=='success'?(int)auth()->user()->balance + (int)Session::get('review')->amount:auth()->user()->balance}}</td>
                            </tr>

                            <tr>
                                <th scope="row">Balance</th>
                                <td>{{auth()->user()->balance}}</td>
                            </tr>

                            <tr>
                                <th scope="row">User Charge (BDT)</th>
                                <td>{{Session::get('review')->user_charge}}</td>
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
            $(document).ready(function () {
                $("#exampleModal").modal('show');
            });
        </script>
    @endif
    <script>
        function clicked(e) {
            var number = document.getElementById("req_mobile").value;
            var amount = $("input[type='radio'][name='package_id']:checked").val();
            var amount = amount.split("_");

            // if(!confirm('Are you sure?'))e.preventDefault();
            if (!confirm('Confirm Request to Number: ' + number + ' and Package Price : ' + amount[1])) e.preventDefault();
        }
    </script>
@endpush
