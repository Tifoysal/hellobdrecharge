<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>New Order Notification</title>
</head>
<body>

<div></div>
<h3>New Order Notification</h3>
<p>
<label>Order Type: </label>
    {{$body->type}}
</p>
<p>
<label>Order Transaction ID#: </label>
    {{$body->tmp_trxid}}
</p>
<p>
<label>Order By: </label>
    {{$body->user->username}}({{$body->user->phone_number}})
</p>
<p>
<label>Order Amount: </label>
    {{$body->amount}} BDT
</p>
<p>
<label>Number:</label>
    {{$body->mobile}}
</p>

</body>
</html>
