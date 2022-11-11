<style>
    .name-balance {
        padding: 10px;
        background: whitesmoke;
        font-weight: bold;
    }

    @media (max-width: 768px) {
        .profile {
            display: none;
        }
    }
</style>
<header class="dash-toolbar">
    <a href="#!" class="menu-toggle">
        <i class="fas fa-bars"></i>
    </a>

    <div class="tools">
        <div style="margin-right: 20px;">
            <img src="{{asset('/img/logo.png')}}" alt="logo" width="130px">
        </div>
        <div class="profile" style="line-height: 6px;">
            <p>{{auth()->user()->username}}</p>
            <p>ID: {{auth()->user()->phone_number}}</p>
            <p>Points: {{auth()->user()->balance}}</p>
        </div>
        {{--        <a href="https://github.com/HackerThemes/spur-template" target="_blank" class="tools-item">--}}
        {{--            <i class="fab fa-github"></i>--}}
        {{--        </a>--}}
        {{--        <a href="#!" class="tools-item">--}}
        {{--            <i class="fas fa-bell"></i>--}}
        {{--            <i class="tools-item-count"></i>--}}
        {{--        </a>--}}


    </div>
</header>
