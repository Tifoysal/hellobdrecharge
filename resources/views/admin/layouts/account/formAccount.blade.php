<?php
    $userType = Auth::user()->type;
?>

<div class="row">
    <div class="col-md-12 col-sm-12">
        @if($userType=='admin')

        <div class="col-md-3 col-sm-3">
            <select class="form-control acu" name="userId" id="acu">
                <option value="">-- Select User --</option>
                <option value="allusers">All Users</option>
                @foreach($userList as $ul)
                    <option value="{{$ul->id}}">{{$ul->username}}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="col-md-3 col-sm-3">
            <div class='input-group date' id='datetimepicker1'>
                <input type='text' class="form-control" name="dateFrom" value="{{$from}}" />
                <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
            </div>

        </div>
        <div class="col-md-2 col-sm-2">
            <div class='input-group date' id='datetimepicker2'>
                <input type='text' class="form-control" name="dateTo" value="{{$to}}" />
                <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
            </div>
        </div>
        <div class="col-md-1 col-sm-1">
            <button type="submit" style="width: 80px; margin-top: 0px;" class="btn btn-info btn-mar form-control">
                <i class="glyphicon glyphicon-search"></i>
            </button>
        </div>
        @if($userType=='user')
            <div class="col-md-1 col-sm-1">
                <a href="{{route('balance.transfer')}}" style="width: 170px; margin-top: 0px;" class="btn btn-success btn-mar form-control">
                   Balance Transfer <i class="glyphicon glyphicon-plus"></i>
                </a>
            </div>
        @endif
    </div>
</div>
