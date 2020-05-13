<div data-scroll-to-active="true" class="main-menu menu-fixed menu-accordion menu-shadow menu-dark">
  <div class="main-menu-content">
    <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
      <li class="nav-item"><a href="{{route('supervisior::dashboard')}}"><span data-i18n="" class="menu-title">Dashboard</span></a></li>
      <li class="nav-item"><a href="{{route('supervisior::provider_create')}}"><span data-i18n="" class="menu-title">Add New Provider</span></a></li>
      <li class="nav-item"><a href="{{route('supervisior::employees')}}"><span data-i18n="" class="menu-title">Provider</span></a></li>
      <li class="nav-item"><a href="{{route('supervisior::approve_pictures')}}"><span data-i18n="" class="menu-title">Approve Provider Pic</span></a></li>
      <li class="nav-item"><a href="{{route('supervisior::approve_bio')}}"><span data-i18n="" class="menu-title">Approve Provider Bio</span></a></li>
      <li class="nav-item"><a href="{{route('supervisior::declined_pic')}}"><span data-i18n="" class="menu-title">Declined Provider Pic</span></a></li>
      <li class="nav-item"><a href="{{route('supervisior::ban_ipaddress')}}"><span data-i18n="" class="menu-title">Ban Ipaddress</span></a></li>
      <li class="nav-item"><a href="{{route('supervisior::alter_provider_info')}}"><span data-i18n="" class="menu-title">Alter Provider Info</span></a></li>
      <li class="nav-item"><a href="{{route('supervisior::block_list')}}"><span data-i18n="" class="menu-title">Suspend Customer/Provider</span></a></li>
      <li class="nav-item"><a href=""><span data-i18n="" class="menu-title">Instant Booking</span></a>
        <ul class="menu-content">  
          <li><a href="{{route('supervisior::viewAllCurrentRunningJobs')}}" class="menu-item"><i class="fa fa-calendar-plus-o"></i><span data-i18n="" class="menu-title">Current Running Jobs</span></a></li>
          <li><a href="{{route('supervisior::viewAllCompletedJobs')}}" class="menu-item"><i class="fa fa-calendar-check-o"></i><span data-i18n="" class="menu-title">Completed Jobs</span></a></li>
          <li><a href="{{route('supervisior::viewAllProviderCanceledJobs')}}" class="menu-item"><i class="fa fa-calendar-times-o"></i><span data-i18n="" class="menu-title">Provider Canceled Jobs</span></a></li>
          <li><a href="{{route('supervisior::viewAllCustomerCanceledJobs')}}" class="menu-item"><i class="fa fa-calendar-times-o"></i><span data-i18n="" class="menu-title">Customer Canceled Jobs</span></a></li>
          <li><a href="{{route('supervisior::ForgotCheckout')}}" class="menu-item"><i class="icon-speech"></i><span data-i18n="" class="menu-title">Forgot Provider Checkout</span></a></li>

          <li><a href="{{route('supervisior::unfilled_jobs')}}" class="menu-item"><i class="icon-speech"></i><span data-i18n="" class="menu-title">Unfilled Jobs</span></a></li>
        </ul>
      </li>

      <li class="nav-item"><a href=""><span data-i18n="" class="menu-title">Scheduled Booking</span></a>
        <ul class="menu-content">         
          <li><a href="{{route('supervisior::workingdays')}}" class="menu-item"><i class="ft-check-square"></i><span data-i18n="" class="menu-title">Working Days</span></a></li>

          <li><a href="{{route('supervisior::DailyScheduledJobs')}}" class="menu-item"><i class="ft-watch"></i><span data-i18n="" class="menu-title">Daily Scheduled jobs</span></a></li>

          <li><a href="{{route('supervisior::WeeklyScheduledJobs')}}" class="menu-item"><i class="ft-pie-chart"></i><span data-i18n="" class="menu-title">Weekly Scheduled jobs</span></a></li>
          
          <li><a href="{{route('supervisior::MonthlyScheduledJobs')}}" class="menu-item"><i class="fa fa-calendar"></i><span data-i18n="" class="menu-title">Monthly Scheduled jobs</span></a></li>
        </ul>
      </li>
    </ul>
  </div>
</div>
