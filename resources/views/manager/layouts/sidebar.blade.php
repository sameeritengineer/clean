<div data-scroll-to-active="true" class="main-menu menu-fixed menu-accordion menu-shadow menu-dark">
  <div class="main-menu-content">
    <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
      <li class="nav-item"><a href="{{route('manager::dashboard')}}"><i class="fa fa-dashboard"></i><span data-i18n="" class="menu-title">Dashboard</span></a></li>
      <li class="nav-item"><a href="{{route('manager::providers')}}"><i class="fa fa-dashboard"></i><span data-i18n="" class="menu-title">Provider</span></a></li>
      <li class="nav-item"><a href="{{route('manager::block_list')}}"><i class="fa fa-dashboard"></i><span data-i18n="" class="menu-title">Suspend Customer/Provider</span></a></li>

      <li class="nav-item"><a href=""><i class="fa fa-calendar-check-o"></i><span data-i18n="" class="menu-title">Instant Booking</span></a>
        <ul class="menu-content">  
          <li><a href="{{route('manager::viewAllCurrentRunningJobs')}}" class="menu-item"><i class="fa fa-calendar-plus-o"></i><span data-i18n="" class="menu-title">Current Running Jobs</span></a></li>
          <li><a href="{{route('manager::viewAllCompletedJobs')}}" class="menu-item"><i class="fa fa-calendar-check-o"></i><span data-i18n="" class="menu-title">Completed Jobs</span></a></li>
          <li><a href="{{route('manager::viewAllProviderCanceledJobs')}}" class="menu-item"><i class="fa fa-calendar-times-o"></i><span data-i18n="" class="menu-title">Provider Canceled Jobs</span></a></li>
          <li><a href="{{route('manager::viewAllCustomerCanceledJobs')}}" class="menu-item"><i class="fa fa-calendar-times-o"></i><span data-i18n="" class="menu-title">Customer Canceled Jobs</span></a></li>
          <li><a href="{{route('manager::ForgotCheckout')}}" class="menu-item"><i class="icon-speech"></i><span data-i18n="" class="menu-title">Forgot Provider Checkout</span></a></li>

          <li><a href="{{route('manager::unfilled_jobs')}}" class="menu-item"><i class="icon-speech"></i><span data-i18n="" class="menu-title">Unfilled Jobs</span></a></li>
        </ul>
      </li>

      <li class="nav-item"><a href=""><i class="fa fa-braille"></i><span data-i18n="" class="menu-title">Scheduled Booking</span></a>
        <ul class="menu-content">         
          <li><a href="{{route('manager::workingdays')}}" class="menu-item"><i class="ft-check-square"></i><span data-i18n="" class="menu-title">Working Days</span></a></li>

          <li><a href="{{route('manager::DailyScheduledJobs')}}" class="menu-item"><i class="ft-watch"></i><span data-i18n="" class="menu-title">Daily Scheduled jobs</span></a></li>

          <li><a href="{{route('manager::WeeklyScheduledJobs')}}" class="menu-item"><i class="ft-pie-chart"></i><span data-i18n="" class="menu-title">Weekly Scheduled jobs</span></a></li>
          
          <li><a href="{{route('manager::MonthlyScheduledJobs')}}" class="menu-item"><i class="fa fa-calendar"></i><span data-i18n="" class="menu-title">Monthly Scheduled jobs</span></a></li>
        </ul>
      </li>

      <li class="nav-item"><a href="{{route('manager::assign_a_job')}}"><i class="fa fa-dashboard"></i><span data-i18n="" class="menu-title">Assign a Job</span></a></li>

      <li class="nav-item"><a href="{{route('manager::ViewblockProvider')}}"><i class="fa fa-flag"></i><span data-i18n="" class="menu-title">Block</span></a>
        <ul class="menu-content">
            <li><a href="{{route('manager::ViewblockProvider')}}" class="menu-item"><i class="fa fa-flag"></i><span data-i18n="" class="menu-title">Block Provider</span></a></li>
            <li><a href="{{route('manager::ViewblockCustomer')}}" class="menu-item"><i class="fa fa-flag"></i><span data-i18n="" class="menu-title">Block Customer</span></a></li>
        </ul>
      </li>

      <li class="nav-item"><a href="{{route('manager::worker_notification')}}"><i class="fa fa-dashboard"></i><span data-i18n="" class="menu-title">Send Worker Notification</span></a></li>

      <li class="nav-item"><a href="{{route('manager::chats')}}"><i class="fa fa-dashboard"></i><span data-i18n="" class="menu-title">Chat</span></a></li>

      <li class="nav-item"><a href="{{route('manager::provider_check')}}"><i class="fa fa-dashboard"></i><span data-i18n="" class="menu-title">Provider Check In/Out</span></a></li>


    </ul>
  </div>
</div>
