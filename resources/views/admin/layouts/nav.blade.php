<nav class="header-navbar navbar navbar-with-menu navbar-fixed-top navbar-semi-light bg-gradient-x-grey-blue navcustomcolor">
  <div class="navbar-wrapper">
    <div class="navbar-header">
      <ul class="nav navbar-nav">
        <li class="nav-item mobile-menu hidden-md-up float-xs-left"><a href="#" class="nav-link nav-menu-main menu-toggle hidden-xs"><i class="ft-menu font-large-1"></i></a></li>
        <li class="nav-item"><a href="{{url()->current()}}" class="navbar-brand">
            <img alt="stack admin logo" src="{{asset('admin/app-assets/images/logo/Cleanerup.png')}}"class="brand-logo" style="width: 172px;padding-bottom: 2px;">
           </a>
        </li>
        <li class="nav-item hidden-md-up float-xs-right">
          <a data-toggle="collapse" data-target="#navbar-mobile" class="nav-link open-navbar-container"><i class="fa fa-ellipsis-v"></i></a>
        </li>
      </ul>
    </div>
    <div class="navbar-container content container-fluid">
      <div id="navbar-mobile" class="collapse navbar-toggleable-sm">
        <ul class="nav navbar-nav">
          <li class="nav-item hidden-sm-down">
            <a href="#" class="nav-link nav-menu-main menu-toggle hidden-xs is-active"><i class="ft-menu"></i></a>
          </li>
        </ul>
        <ul class="nav navbar-nav float-xs-right">
          <li class="dropdown dropdown-user nav-item">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-user-link"><span class="showactive ">Show Active User</span></a>
          </li>
          <li class="dropdown dropdown-user nav-item">
            <a href="#" data-toggle="dropdown" class="dropdown-toggle nav-link dropdown-user-link">
              <span class="avatar avatar-online">
              <img src="{{asset('admin/app-assets/images/portrait/small/profile.jpg')}}" alt="avatar"><i></i></span>
              <span class="user-name">{{auth::user()->first_name}}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">             
              <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
            </div>
          </li>
        </ul>
      </div>
      <div class="panel panel-default mamberpanel">
        <div class="panel-heading c-list"><span class="title">Active User <i class="fa fa-times closeicon" aria-hidden="true"></i></span></div>
        <ul class="list-group contact-list-box">
          <li class="list-group-item">
            <div class="col-xs-12 col-sm-3">
              <div class="user-img-lft">
                <img src="http://api.randomuser.me/portraits/men/49.jpg" alt="Scott Stevens" class="img-responsive img-circle">
                <div class="dot-ac color-online"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-9"><h4 class="username">Scott Stevens</h4></div>
          </li>
          <li class="list-group-item">
            <div class="col-xs-12 col-sm-3">
              <div class="user-img-lft">
                <img src="http://api.randomuser.me/portraits/men/49.jpg" alt="Scott Stevens" class="img-responsive img-circle">
                <div class="dot-ac color-offline"></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-9"><h4 class="username">Scott Stevens</h4></div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>