@extends('admin.master')
@section('content')
    <style type="text/css">
        .box {
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
    <div class="container">
        <h2>Select Type</h2>


        <div class="row">
            @if($types)
                @foreach($types as $key=>$type)

                    <div class="col-md-2 box zoom">
                        <a href="{{route(auth()->user()->type.'.request.data.getpackages',$key)}}">
                            <div class="thumbnail" style="text-align: center">
                                <span
                                    style="font-size: large;color: black;font-weight: bold;"> {{ucwords($type)}}</span>
                            </div>
                        </a>
                    </div>
                @endforeach

            @else
                <div>
                    <span class="alert alert-danger">No Type found!</span>
                </div>
            @endif

        </div>
    </div>
@endsection
