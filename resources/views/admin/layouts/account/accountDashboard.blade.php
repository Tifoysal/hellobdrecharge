
@extends('admin.master')

@section('content')
    <div class="pull-left">
        <h3>Account</h3>
        <?php
        $userType = Auth::user()->type;
        ?>
    </div>
    <form action="" method="get" role="form">
        <div class="row">

                @if($userType=='admin')

                    <div class="col-md-3 col-sm-3">
                        <select class="form-control acu" name="userId" id="acu">
                            <option value="allusers">All Users</option>
                            @foreach($userList as $ul)
                                <option @if($user==$ul->id) selected @endif value="{{$ul->id}}">{{$ul->username}}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-md-3 col-sm-3">
                    <div class='input-group date' id='datetimepicker1'>
                        <input type='date' class="form-control" name="dateFrom" value="{{$from}}" />
                        <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                    </div>

                </div>
                <div class="col-md-2 col-sm-2">
                    <div class='input-group date' id='datetimepicker2'>
                        <input type='date' class="form-control" name="dateTo" value="{{$to}}" />
                        <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                    </div>
                </div>
                <div class="col-md-1 col-sm-1">
                    <button type="submit" style="width: 80px; margin-top: 0px;" class="btn btn-info btn-mar form-control">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
                    <div class="col-md-1 col-sm-1">

                        <input style="width: 80px; margin-top: 0px;" type="submit" class="btn btn-success" name="type" value="Export" >

                    </div>

        </div>

    </form>



        <div class="row m-3 content-panel" >
            @if($tnx)
        <table class="table table-hover">

            <thead>
            <tr>
                <th class="">Serial Number</th>
                <th>Transaction Number</th>
                <th>Date & Time</th>
                <th>Details</th>
{{--                <th>Transaction Type</th>--}}
{{--                <th>Mobile Number</th>--}}
                <th>Order Amount (Dr)</th>
                <th>Order Amount (Cr)</th>
                <th>Available User Balance</th>
{{--                <th>Service Name</th>--}}
{{--                <th>Operator Name</th>--}}
{{--                <th>Service Country</th>--}}
{{--                <th>Status</th>--}}
{{--                <th>Action</th>--}}
            </tr>
            </thead>
            <tbody>

            @foreach($tnx as $key=>$tnxData)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$tnxData->trxno}}</td>
                    <td>{{date('Y-m-d',strtotime($tnxData->created_at))}}</td>
                    <td> {{$tnxData->details}}</td>
                    <td>{{$tnxData->debit}}</td>
                    <td>{{$tnxData->credit}}</td>
                    <td>{{$tnxData->balance}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

                {{$tnx->appends($_GET)->links()}}
@endif
        </div>
        @endsection
        @push('script')
            <script type="text/javascript">
                $(function () {
                    $('#datetimepicker1').datepicker();
                });
                $(function () {
                    $('#datetimepicker2').datepicker();
                });

                $('#acop').change(function(){
                    $('#acu option:first').prop('selected', 'selected');
                });


                $('#acu').change(function(){
                    $('#acop option:first').prop('selected', 'selected');
                });

            </script>
    @endpush

