<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-left">
                <li><a href=" {{ route('user.dashboard') }}"><h5>Home</h5></a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="{{ route('user.account') }}"><h5>Account</h5></a></li>
                <li><a href="{{ route ('user.logout')}}"><h5>Logout</h5></a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>