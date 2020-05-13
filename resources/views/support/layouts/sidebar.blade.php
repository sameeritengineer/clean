<div data-scroll-to-active="true" class="main-menu menu-fixed menu-accordion menu-shadow menu-dark">
  <div class="main-menu-content">
    <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
      <li class="nav-item"><a href="{{route('support::dashboard')}}"><i class="fa fa-dashboard"></i><span data-i18n="" class="menu-title">Dashboard</span></a></li>

      <li class="nav-item"><a href="{{route('support::ViewblockProvider')}}"><i class="fa fa-flag"></i><span data-i18n="" class="menu-title">Block</span></a>
    	  <ul class="menu-content">
            <li><a href="{{route('support::ViewblockProvider')}}" class="menu-item"><i class="fa fa-flag"></i><span data-i18n="" class="menu-title">Block Provider</span></a></li>
    		    <li><a href="{{route('support::ViewblockCustomer')}}" class="menu-item"><i class="fa fa-flag"></i><span data-i18n="" class="menu-title">Block Customer</span></a></li>
        </ul>
      </li>

      <li class="nav-item"><a href="{{route('support::worker_notification')}}"><i class="fa fa-dashboard"></i><span data-i18n="" class="menu-title">Send Worker Notification</span></a></li>
      
      <li class="nav-item"><a href="{{route('support::chats')}}"><i class="fa fa-dashboard"></i><span data-i18n="" class="menu-title">Chat</span></a></li>

      <li class="nav-item"><a href="{{route('support::provider_check')}}"><i class="fa fa-dashboard"></i><span data-i18n="" class="menu-title">Provider Check In/Out</span></a></li>

      <li class="nav-item"><a href="{{route('support::users')}}"><i class="fa fa-flag"></i><span data-i18n="" class="menu-title">Add New Profile</span></a>
        <ul class="menu-content">
            <li><a href="{{route('support::users')}}" class="menu-item"><i class="fa fa-flag"></i><span data-i18n="" class="menu-title">Profile</span></a></li>
            <li><a href="{{route('support::users.create')}}" class="menu-item"><i class="fa fa-flag"></i><span data-i18n="" class="menu-title">Add New Profile</span></a></li>
        </ul>
      </li>

      <li class="nav-item"><a href="{{route('support::assign_a_job')}}"><i class="fa fa-dashboard"></i><span data-i18n="" class="menu-title">Assign a Job</span></a></li>
    </ul>
  </div>
</div>
