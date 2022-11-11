<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" />
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600|Open+Sans:400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet"/>
     <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="{{asset('/css/spur.css')}}"/>
    <link rel="stylesheet" href="{{asset('/css/styles.css')}}"/>


<link rel="stylesheet" href="{{asset('/frontend/toaster/toastr.min.css')}}"/>

    <title>{{config('app.name')}}</title>

    <style>
        .dataTables_length {
            float: left;
        }

        .dataTables_filter {
            float: right;
        }

        .dataTables_filter input {
            /*background-color: green;*/
            border-radius: 4px;
            border: 1px solid #dddddd;
        }

        .paginate_button {
            background-color: #6764d4;
            color: white !important;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px;
        }

        .dataTables_paginate a:hover {
            color: black;
        !important;
            text-decoration: none;
        }

        .spur-card-icon {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .transaction-ch {
            display: flex;
            justify-content: space-between;
        }
       fieldset.scheduler-border {
             border: 1px groove #ddd !important;
             padding: 0 1.4em 1.4em 1.4em !important;
             margin: 0 0 1.5em 0 !important;
             -webkit-box-shadow:  0px 0px 0px 0px #000;
             box-shadow:  0px 0px 0px 0px #000;
         }

        legend.scheduler-border {
            font-size: 1.2em !important;
            font-weight: bold !important;
            text-align: left !important;
            width:auto;
            padding:0 10px;
            border-bottom:none;
        }
    </style>
</head>

<body>
<div class="dash">
    <div class="dash-nav dash-nav-dark">
        <header>
            <a href="#!" class="menu-toggle">
                <i class="fas fa-bars"></i>
            </a>
            <a href="{{route('dashboard')}}" class="spur-logo"><i class="fas fa-mobile"></i> <span>{{config('app.name')}}</span></a>
        </header>

        @include('admin.partials.nav')

    </div>

    <div class="dash-app">

        @include('admin.partials.header')

        <main class="dash-content">
            @yield('content')
        </main>

    </div>
</div>






<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="{{asset('/js/spur.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<script>
$(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });
    </script>

<script src="{{asset('/js/chart-js-config.js')}}"></script>
<script src="{{asset('/frontend/toaster/toastr.min.js')}}"></script>
@stack('javascript')
{!! Toastr::message() !!}
</body>

</html>
