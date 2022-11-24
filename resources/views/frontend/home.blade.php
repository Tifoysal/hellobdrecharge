<!DOCTYPE html>
<html>
<head>
    <title>HelloBD Recharge</title>

    <link href="{{asset('css/bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset('css/login.css')}}" rel="stylesheet" >
    <link href="{{asset('css/website/style.css')}}" rel="stylesheet" type="text/css"
          media="all"/>
    <!-- for-mobile-apps -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="keywords" content="telecommunication service, recharge, top up"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- //for-mobile-apps -->
    <!--fonts-->
    <link href='//fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'>
    <link
        href='//fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic'
        rel='stylesheet' type='text/css'>
    <!--//fonts-->
    <!-- js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
</head>
<body>


<div class="banner" style="background-color:#c5e373;display: grid !important; " >
    <div class="">

{{--        <div class="banner-info">--}}
{{--            <h3 style="">Recharge Your Phone Easily Anytime from Anywhere.</h3>--}}
{{--        </div>--}}


        {{--                <div class="card-header bg-primary text-white"> Please sign in</div>--}}
        {{--                <div class="card-body">--}}
        {{--                    <form action="{{route('do.login')}}" method="POST" role="form">--}}
        {{--                        @csrf--}}
        {{--                        <div class="form-group">--}}
        {{--                            <input name="mobile" type="number" class="form-control" id="mobile"--}}
        {{--                                   placeholder="Enter Mobile Number">--}}
        {{--                        </div>--}}
        {{--                        <div class="form-group">--}}
        {{--                            <input name="password" type="password" class="form-control" id="exampleInputPassword1"--}}
        {{--                                   placeholder="Password">--}}
        {{--                        </div>--}}
        {{--                        <div class="account-dialog-actions" style="text-align: center">--}}
        {{--                            <button type="submit" class="btn btn-primary">Sign in</button>--}}
        {{--                        </div>--}}
        {{--                    </form>--}}
        {{--                </div>--}}

        <div class="login_container">

                <div id="header">
                <div class="hgjhghjjhjjh">
                    <div class="row forPaddingtop">
                        <div class="col-sm-12 text-center">
                            <h1 class="hederCntent">Topping up Happiness!</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 2ndPart -->
            <div id="secondPart">
                <div class="hgjhghjjhjjh">
                    <div class="row">
                        <div class="col-12 text-center">
                            <img class="img-fluid text-center forPaddingBottom"
                                 src="{{asset('uploads/business/'.$setting->logo)}}" alt="">
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-8">
                            <form action="{{route('do.login')}}" method="post">
                                @csrf
                                @foreach ($errors->all() as $error)
                                    <p class="alert alert-danger">{{ $error }}</p>
                                @endforeach
                                @if (session('message'))
                                    <div class="alert alert-success alert-dismissable">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">x</a>
                                        {{ session('message')}}
                                    </div>
                                @endif
                                <div class="form-floating mb-3 input-group">
                                    <div class="input-group mb-3">
                                        <input name="mobile" required type="text" class="form-control" id="floatingPassword"
                                               placeholder="Phone Number" aria-label="Username"
                                               aria-describedby="basic-addon1">
                                    </div>
                                </div>
                                <div class="form-floating mb-3 input-group">
                                    <input name="password" required type="password" class="form-control" placeholder="Password"
                                           id="inputPassword">
                                </div>
                                <a style="color: green;" href="https://api.WhatsApp.com/send?phone=+8801777422558" class="psw pt-2 pb-4">
                                    <i class="fa fa-whatsapp"></i>
                                    +8801777422558</a>
                                <br>
                                <div class="d-grid gap-1 col-12 mx-auto mt-3" style="display: contents">
                                    <input style="width:100%" class="btn btn-secondary logIn" type="submit" name="submit"
                                           value="LOGIN">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center pt-5">
                <div class="col-8">
                    <div class="form-check" style="display: grid">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
                        <label class="form-check-label" for="flexCheckChecked">
                            By login, you agree to our <a class='last' href="#"> Terms of Use </a>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{--</div>--}}
<!---728x90--->
<div class="footer">
    <div class="container">
        <h2><a href="index.html">HelloBD</a></h2>
        <p>© {{date('Y')}} HelloBD. All Rights Reserved | Design, Developed & Maintenance by <a href="https://www.kodeeo.com/" target="_blank">Kodeeo ltd.</a>
        </p>
        <ul>
            <li><a class="face1" href="#"></a></li>
            <li><a class="face2" href="#"></a></li>
            <li><a class="face3" href="#"></a></li>
            <li><a class="face4" href="#"></a></li>
        </ul>
    </div>
</div>

<!-- login -->
<div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-info">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body modal-spa">
                <div class="login-grids">
                    <div class="login">
                        <div class="login-left">
                            <ul>
                                <li><a class="fb" href="#"><i></i>Sign in with Facebook</a></li>
                                <li><a class="goog" href="#"><i></i>Sign in with Google</a></li>
                                <li><a class="linkin" href="#"><i></i>Sign in with Linkedin</a></li>
                            </ul>
                        </div>
                        <div class="login-right">
                            <form>
                                <h3>Signin with your account </h3>
                                <input type="text" value="Enter your mobile number or Email" onfocus="this.value = '';"
                                       onblur="if (this.value == '') {this.value = 'Enter your mobile number or Email';}"
                                       required="">
                                <input type="password" value="Password" onfocus="this.value = '';"
                                       onblur="if (this.value == '') {this.value = 'Password';}" required="">
                                <h4><a href="#">Forgot password</a> / <a href="#">Create new password</a></h4>
                                <div class="single-bottom">
                                    <input type="checkbox" id="brand" value="">
                                    <label for="brand"><span></span>Remember Me.</label>
                                </div>
                                <input type="submit" value="SIGNIN">
                            </form>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <p>By logging in you agree to our <span>Terms and Conditions</span> and <span>Privacy Policy</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- //login -->

<script type="text/javascript" src="https://p.w3layouts.com/demos/easy_recharge/web/js/jquery.min.js"></script>
<!-- js -->


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//m.servedby-buysellads.com/monetization.js" type="text/javascript"></script>

<script src="https://codefund.io/properties/441/funder.js" async="async"></script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src='https://www.googletagmanager.com/gtag/js?id=UA-149859901-1'></script>

</body>
</html>
