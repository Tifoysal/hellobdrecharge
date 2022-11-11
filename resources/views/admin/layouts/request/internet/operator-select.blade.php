@extends('admin.master')
@section('content')
<style type="text/css">
    .box{
        background: white;
        border-radius: 10px;
        padding: 10px;
        margin: 10px;
        box-shadow: 4px 9px #939aa5;
    }
    .zoom {
        /*padding: 50px;*/
        transition: transform .2s; /* Animation */
        /*margin: 0 auto;*/
    }

    .zoom:hover {
        transform: scale(1.1); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
    }
</style>

@if($errors->any())
    @foreach($errors->all() as $error)
        <p class="alert alert-danger">{{$error}}</p>
    @endforeach
@endif
    <div class="container">
        <h2>Select Operator</h2>

        <div class="row">
            @if($operators)
                @foreach($operators as $operator)
            <div class="col-md-2 box zoom">
                <a href="{{route(auth()->user()->type.'.request.data.selectType',$operator->opcode)}}">
                <div class="thumbnail" style="text-align: center">
                    <img src="{{asset('/uploads/operator/'.$operator->logo)}}" alt="{{$operator->name}}" style="width:80px;height:80px;object-fit: contain">
                 </div>
                </a>
            </div>
                @endforeach

            @else
                <div>
                    <span class="alert alert-danger">No operators found!</span>
                </div>
            @endif

        </div>
    </div>
@endsection
