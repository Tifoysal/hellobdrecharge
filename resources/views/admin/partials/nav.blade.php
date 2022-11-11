<nav class="dash-nav-list">

{{--    <div class="dropdown tools-item" style="width: 100% !important;">--}}
{{--        <a href="#" class="" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
{{--            <img style="width: 50px; border-radius: 25px;" src="{{asset('/uploads/user/'.auth()->user()->image)}}"--}}
{{--                 alt="user">--}}
{{--            {{auth()->user()->username}}--}}
{{--        </a>--}}
{{--        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">--}}
{{--            <p class="name-balance">Mobile ({{auth()->user()->phone_number}})</p>--}}
{{--            <p class="name-balance"> Balance:({{auth()->user()->balance}} .tk)</p>--}}
{{--            <a style="color:black !important;" class="dropdown-item" href="{{route('profile')}}">Profile</a>--}}
{{--            --}}{{--                <p class="dropdown-item" href="#!">Current Balance: {{auth()->user()->balance}}</p>--}}
{{--            <a style="color: black;" class="dropdown-item" href="{{route('logout')}}">Logout</a>--}}
{{--        </div>--}}
{{--    </div>--}}

    <a href="{{route('dashboard')}}" class="dash-nav-item">
        <i class="fas fa-home"></i> Dashboard
    </a>

    <div class="dash-nav-dropdown {{request()->is('profile/*')?' show':''}}">
        <a href="#!" class="dash-nav-item dash-nav-dropdown-toggle">
            <i class="fas fa-user"></i> {{ucfirst(auth()->user()->username)}} </a>
        <div class="dash-nav-dropdown-menu">
            <a class="dash-nav-dropdown-item">Points: {{auth()->user()->balance}}</a>
            <a class="dash-nav-dropdown-item"> {{auth()->user()->phone_number}}</a>
        </div>
        <div class="dash-nav-dropdown-menu">
            <a href="{{route('profile')}}" class="dash-nav-dropdown-item">Profile</a>
            {{--                <a href="{{route('request.index')}}" class="dash-nav-dropdown-item">List</a>--}}
        </div>
        <div class="dash-nav-dropdown-menu">
            <a href="{{route('logout')}}" class="dash-nav-dropdown-item">Logout</a>
            {{--                <a href="{{route('request.index')}}" class="dash-nav-dropdown-item">List</a>--}}
        </div>

    </div>


    @if(auth()->user()->type=='user')
        {{--    user area start --}}
        <div class="dash-nav-dropdown {{request()->is(auth()->user()->type.'/request/*')?' show':''}}">
            <a href="#!" class="dash-nav-item dash-nav-dropdown-toggle">
                <i class="fas fa-paper-plane"></i> Request </a>
            <div class="dash-nav-dropdown-menu">
                <a href="{{route(auth()->user()->type.'.request.create')}}" class="dash-nav-dropdown-item"> Mobile Topup</a>
                {{--                <a href="{{route('request.index')}}" class="dash-nav-dropdown-item">List</a>--}}
            </div>

            <div class="dash-nav-dropdown-menu">
                <a href="{{route(auth()->user()->type.'.request.data.create')}}" class="dash-nav-dropdown-item"> Internet and Combo</a>
            </div>

            <div class="dash-nav-dropdown-menu show">
                <a href="{{route(auth()->user()->type.'.request.index')}}" class="dash-nav-dropdown-item">Transactional Report</a>
            </div>
        </div>

        <div class="dash-nav-dropdown  {{request()->is(auth()->user()->type.'/account/*')?' show':''}}">
            <a href="#!" class="dash-nav-item dash-nav-dropdown-toggle">
                <i class="fas fa-file-invoice-dollar"></i> Account </a>
            <div class="dash-nav-dropdown-menu">
                <a href="{{route(auth()->user()->type.'.account')}}" class="dash-nav-dropdown-item">Statement</a>
            </div>
        </div>

        <div class="dash-nav-dropdown {{request()->is('recharge/*')?' show':''}}">
            <a href="#!" class="dash-nav-item dash-nav-dropdown-toggle">
                <i class="fas fa-plug"></i> Purchase </a>
            <div class="dash-nav-dropdown-menu">
                <a href="{{route('recharge.list')}}" class="dash-nav-dropdown-item">List</a>
            </div>
            <div class="dash-nav-dropdown-menu">
                <a href="{{route(auth()->user()->type.'.recharge.form')}}" class="dash-nav-dropdown-item">Create</a>
            </div>
        </div>


        {{--    user area end--}}
    @endif
    @if(auth()->user()->type==='seller')
        {{--    seller area start --}}
        <div class="dash-nav-dropdown {{request()->is(auth()->user()->type.'/request/*')?' show':''}}">
            <a href="#!" class="dash-nav-item dash-nav-dropdown-toggle">
                <i class="fas fa-paper-plane"></i> Request </a>
            <div class="dash-nav-dropdown-menu">
                <a href="{{route(auth()->user()->type.'.request.create')}}" class="dash-nav-dropdown-item"> Mobile Topup</a>
                {{--                <a href="{{route('request.index')}}" class="dash-nav-dropdown-item">List</a>--}}
            </div>


            <div class="dash-nav-dropdown-menu">
                <a href="{{route(auth()->user()->type.'.request.data.create')}}" class="dash-nav-dropdown-item"> Internet and Combo</a>
            </div>
            <div class="dash-nav-dropdown-menu show">
                <a href="{{route(auth()->user()->type.'.request.index')}}" class="dash-nav-dropdown-item">Transactional Report</a>
            </div>

            <div class="dash-nav-dropdown-menu">
                <a href="{{route(auth()->user()->type.'.mBanking.create')}}" class="dash-nav-dropdown-item">Wallet Topup</a>
            </div>
            <div class="dash-nav-dropdown-menu show">
                <a href="{{route(auth()->user()->type.'.mBanking.index')}}" class="dash-nav-dropdown-item">Wallet Topup List</a>
            </div>

        </div>
        <div class="dash-nav-dropdown {{request()->is('recharge/*')?' show':''}}">
            <a href="#!" class="dash-nav-item dash-nav-dropdown-toggle">
                <i class="fas fa-plug"></i> Purchase </a>
            <div class="dash-nav-dropdown-menu">
                <a href="{{route('recharge.list')}}" class="dash-nav-dropdown-item">List</a>
            </div>
            <div class="dash-nav-dropdown-menu">
                <a href="{{route(auth()->user()->type.'.recharge.form')}}" class="dash-nav-dropdown-item">Create</a>
            </div>
        </div>
        <div class="dash-nav-dropdown  {{request()->is(auth()->user()->type.'/account/*')?' show':''}}">
            <a href="#!" class="dash-nav-item dash-nav-dropdown-toggle">
                <i class="fas fa-file-invoice-dollar"></i> Account </a>
            <div class="dash-nav-dropdown-menu">
                <a href="{{route(auth()->user()->type.'.account')}}" class="dash-nav-dropdown-item">Statement</a>
            </div>
        </div>

        {{--    seller area end--}}
    @endif

    @if(auth()->user()->type==='admin')
        {{--    admin area--}}

        <div class="dash-nav-dropdown {{request()->is(auth()->user()->type.'/request/*')?' show':''}}">
            <a href="#!" class="dash-nav-item dash-nav-dropdown-toggle">
                <i class="fas fa-paper-plane"></i> Request </a>

            <div class="dash-nav-dropdown-menu show">
                {{--                <a href="{{route('request.create')}}" class="dash-nav-dropdown-item">New</a>--}}
                <a href="{{route(auth()->user()->type.'.request.index')}}" class="dash-nav-dropdown-item">Mobile Topup
                    List</a>
            </div>
            {{--            <div class="dash-nav-dropdown-menu">--}}
            {{--                <a href="{{route('mBanking.create')}}" class="dash-nav-dropdown-item">Mobile Banking</a>--}}
            {{--             </div>--}}
            <div class="dash-nav-dropdown-menu show">
                <a href="{{route('admin.mBanking.index')}}" class="dash-nav-dropdown-item">Wallet Topup List</a>
            </div>

        </div>

        <div class="dash-nav-dropdown {{request()->is('admin/user*')?' show':''}}">
            <a href="#!" class="dash-nav-item dash-nav-dropdown-toggle">
                <i class="fas fa-users"></i> Users </a>
            <div class="dash-nav-dropdown-menu">
                <a href="{{route('users.data')}}" class="dash-nav-dropdown-item">List</a>
            </div>
        </div>

        <div class="dash-nav-dropdown  {{request()->is('admin/account/*')?' show':''}}">
            <a href="#!" class="dash-nav-item dash-nav-dropdown-toggle">
                <i class="fas fa-file-invoice-dollar"></i> Account </a>
            <div class="dash-nav-dropdown-menu">
                <a href="{{route('account')}}" class="dash-nav-dropdown-item">Statement</a>
            </div>
        </div>


        <div class="dash-nav-dropdown {{request()->is('admin/package/*')?' show':''}}">
            <a href="#!" class="dash-nav-item dash-nav-dropdown-toggle">
                <i class="fas fa-gift"></i> Package </a>
            <div class="dash-nav-dropdown-menu">
                <a href="{{route('package.list')}}" class="dash-nav-dropdown-item">List</a>
            </div>
            <div class="dash-nav-dropdown-menu">
                <a href="{{route('package.create')}}" class="dash-nav-dropdown-item">Create</a>
            </div>
        </div>

        <div class="dash-nav-dropdown {{request()->is('admin/operator/*')?' show':''}}">
            <a href="#!" class="dash-nav-item dash-nav-dropdown-toggle">
                <i class="fas fa-mobile"></i> Operator </a>
            <div class="dash-nav-dropdown-menu">
                <a href="{{route('operator.list')}}" class="dash-nav-dropdown-item">List</a>
            </div>
            <div class="dash-nav-dropdown-menu">
                <a href="{{route('operator.create')}}" class="dash-nav-dropdown-item">Create</a>
            </div>
        </div>

        <div class="dash-nav-dropdown {{request()->is('admin/recharge/*')?' show':''}}">
            <a href="#!" class="dash-nav-item dash-nav-dropdown-toggle">
                <i class="fas fa-plug"></i> Purchase </a>
            <div class="dash-nav-dropdown-menu">
                <a href="{{route('recharge.list')}}" class="dash-nav-dropdown-item">List</a>
            </div>

        </div>




        {{--    <div class="dash-nav-dropdown {{request()->is('admin/payment/*')?' show':''}}">--}}
        {{--        <a href="#!" class="dash-nav-item dash-nav-dropdown-toggle">--}}
        {{--            <i class="fas fa-money-check-alt"></i> Balance  </a>--}}
        {{--        <div class="dash-nav-dropdown-menu">--}}
        {{--            <a href="" class="dash-nav-dropdown-item">Add</a>--}}
        {{--        </div>--}}
        {{--    </div>--}}



        <div class="dash-nav-dropdown {{request()->is('admin/settings/*')?' show':''}}">
            <a href="#!" class="dash-nav-item dash-nav-dropdown-toggle">
                <i class="fas fa-sliders-h"></i> Setting </a>
            <div class="dash-nav-dropdown-menu">
                <a href="{{route('settings')}}" class="dash-nav-dropdown-item">Business</a>
            </div>
            <div class="dash-nav-dropdown-menu">
                <a href="{{route('sender.index')}}" class="dash-nav-dropdown-item">Executor</a>
            </div>

                <div class="dash-nav-dropdown-menu">
                    <a href="{{route('purchase.account.list')}}" class="dash-nav-dropdown-item">Purchase account</a>
                </div>
            <div class="dash-nav-dropdown-menu">
                <a href="{{route('service.index')}}" class="dash-nav-dropdown-item">Services</a>
            </div>

        </div>

    @endif

    {{--    admin area end--}}


</nav>
