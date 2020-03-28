<div data-scroll-to-active="true" class="main-menu menu-fixed menu-accordion menu-shadow menu-dark">
  <div class="main-menu-content">
    <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
      <li class="nav-item"><a href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i><span data-i18n="" class="menu-title">Dashboard</span></a></li>
      <li class="nav-item"><a href="{{route('OpenOpportunity')}}" class="menu-item"><i class="fa fa-calendar-plus-o"></i><span data-i18n="" class="menu-title">Open Opportunities</span><span class="tag tag tag-danger tag-pill float-xs-right mr-2" style="right: -18px;">New</span></a></li>
      <li class="nav-item"><a href="{{route('country')}}"><i class="fa fa-flag"></i><span data-i18n="" class="menu-title">Country</span></a>
    	  <ul class="menu-content">
            <li><a href="{{route('country')}}" class="menu-item"><i class="fa fa-flag"></i><span data-i18n="" class="menu-title">Country</span></a></li>
    		    <li><a href="{{route('state')}}" class="menu-item"><i class="fa fa-flag"></i><span data-i18n="" class="menu-title">States</span></a></li>
            <li><a href="{{route('city')}}" class="menu-item"><i class="fa fa-flag"></i><span data-i18n="" class="menu-title">City</span></a></li>
            <li><a href="{{route('zipcode')}}" class="menu-item"><i class="fa fa-flag"></i><span data-i18n="" class="menu-title">Zipcode</span></a></li>
        </ul>
      </li>
      <li class="nav-item"><a href=""><i class="icon-grid"></i><span data-i18n="" class="menu-title">Service</span></a>
        <ul class="menu-content">
            <li><a href="{{route('service')}}" class="menu-item"><i class="icon-grid"></i><span data-i18n="" class="menu-title">Service</span></a></li>
            <li><a href="{{route('serviceType')}}" class="menu-item"><i class="icon-list"></i><span data-i18n="" class="menu-title">Service Types</span></a></li>            
        </ul>
      </li>

      <li class="nav-item"><a href=""><i class="icon-user-following"></i><span data-i18n="" class="menu-title">Service Provider</span></a>
        <ul class="menu-content">
          <li><a href="{{route('addServiceProvider')}}" class="menu-item"><i class="ft-plus"></i><span data-i18n="" class="menu-title">Add Service Provider</span></a></li> 
          <li><a href="{{route('viewAllServiceProvider')}}" class="menu-item"><i class="icon-users"></i><span data-i18n="" class="menu-title">All Service Provider</span></a></li>
          <li><a href="{{route('unApprovedServiceProvider')}}" class="menu-item"><i class="icon-user-unfollow"></i><span data-i18n="" class="menu-title">Pending Registraion</span></a></li>
          <li><a href="{{route('unApprovedProfileProvider')}}" class="menu-item"><i class="icon-picture"></i><span data-i18n="" class="menu-title">Pending Profile Picture </span></a></li>
          <li><a href="{{route('unApprovedBioProvider')}}" class="menu-item"><i class="icon-speech"></i><span data-i18n="" class="menu-title">Pending Bio</span></a></li>
          <li><a href="{{route('provider_location')}}" class="menu-item"><i class="icon-speech"></i><span data-i18n="" class="menu-title">Provider Location</span></a></li>
        </ul>
      </li>  
      <li class="nav-item"><a href=""><i class="icon-user-unfollow"></i><span data-i18n="" class="menu-title">Declined Provider</span></a>
        <ul class="menu-content">         
          <li><a href="{{route('viewAllDeclineProvider')}}" class="menu-item"><i class="icon-user-unfollow"></i><span data-i18n="" class="menu-title">All Declined Provider List</span></a></li>
        </ul>
      </li> 
      <li class="nav-item"><a href=""><i class="fa fa-calendar-check-o"></i><span data-i18n="" class="menu-title">Instant Booking</span></a>
        <ul class="menu-content">  
          <!-- <li><a href="{{route('OpenOpportunity')}}" class="menu-item"><i class="fa fa-street-view"></i><span data-i18n="" class="menu-title">Open Opportunity</span></a></li> -->
          <li><a href="{{route('viewAllCurrentRunningJobs')}}" class="menu-item"><i class="fa fa-calendar-plus-o"></i><span data-i18n="" class="menu-title">Current Running Jobs</span></a></li>
          <li><a href="{{route('viewAllCompletedJobs')}}" class="menu-item"><i class="fa fa-calendar-check-o"></i><span data-i18n="" class="menu-title">Completed Jobs</span></a></li>
          <li><a href="{{route('viewAllProviderCanceledJobs')}}" class="menu-item"><i class="fa fa-calendar-times-o"></i><span data-i18n="" class="menu-title">Provider Canceled Jobs</span></a></li>
          <li><a href="{{route('viewAllCustomerCanceledJobs')}}" class="menu-item"><i class="fa fa-calendar-times-o"></i><span data-i18n="" class="menu-title">Customer Canceled Jobs</span></a></li>
          <li><a href="{{route('ForgotCheckout')}}" class="menu-item"><i class="icon-speech"></i><span data-i18n="" class="menu-title">Forgot Provider Checkout</span></a></li>
          <li><a href="{{route('get_completed_jobs_by_city')}}" class="menu-item"><i class="icon-speech"></i><span data-i18n="" class="menu-title">Job Completed By City</span></a></li>
        </ul>
      </li> 
      <li class="nav-item"><a href=""><i class="fa fa-braille"></i><span data-i18n="" class="menu-title">Scheduled Booking</span></a>
        <ul class="menu-content">         
          <li><a href="{{route('workingdays')}}" class="menu-item"><i class="ft-check-square"></i><span data-i18n="" class="menu-title">Working Days</span></a></li>
          <li><a href="{{route('DailyScheduledJobs')}}" class="menu-item"><i class="ft-watch"></i><span data-i18n="" class="menu-title">Daily Scheduled jobs</span></a></li>
          <li><a href="{{route('WeeklyScheduledJobs')}}" class="menu-item"><i class="ft-pie-chart"></i><span data-i18n="" class="menu-title">Weekly Scheduled jobs</span></a></li>
          <li><a href="{{route('MonthlyScheduledJobs')}}" class="menu-item"><i class="fa fa-calendar"></i><span data-i18n="" class="menu-title">Monthly Scheduled jobs</span></a></li>
        </ul>
      </li>
      <li class="nav-item"><a href=""><i class="fa fa-address-card-o"></i><span data-i18n="" class="menu-title">Rebook Cleaner</span></a>
        <ul class="menu-content">  
          <li><a href="{{route('Rebookedjobs')}}" class="menu-item"><i class="fa fa-tasks"></i><span data-i18n="" class="menu-title">Open Rebooked Opportunity</span></a></li>
          <li><a href="{{route('Rebookedjobs')}}" class="menu-item"><i class="fa fa-tasks"></i><span data-i18n="" class="menu-title">Accepted Rebooked Jobs</span></a></li>
        </ul>
      </li>  
      <li class="nav-item"><a href=""><i class="fa fa-users"></i><span data-i18n="" class="menu-title">Users</span></a>
        <ul class="menu-content">
            <li><a href="{{route('allUser')}}" class="menu-item"><i class="fa fa-users"></i><span data-i18n="" class="menu-title">All User</span></a></li>
        </ul>
      </li>
      <li class="nav-item"><a href=""><i class="fa fa-ban"></i><span data-i18n="" class="menu-title">Block</span></a>
        <ul class="menu-content">
            <li><a href="{{route('ViewblockProvider')}}" class="menu-item"><i class="ft-bell-off"></i><span data-i18n="" class="menu-title">Customer -> Provider</span></a></li>
            <li><a href="{{route('ViewblockCustomer')}}" class="menu-item"><i class="ft-bell-off"></i><span data-i18n="" class="menu-title">Provider -> Customer</span></a></li>
            <li><a href="{{route('block_list')}}" class="menu-item"><i class="ft-bell-off"></i><span data-i18n="" class="menu-title">Suspend customer/provider</span></a></li>
            <li><a href="{{route('prevent_notification')}}" class="menu-item"><i class="ft-bell-off"></i><span data-i18n="" class="menu-title">Prevent Notification</span></a></li>
        </ul>
      </li>
      <li class="nav-item"><a href="{{route('role')}}"><i class="fa fa-list-alt"></i><span data-i18n="" class="menu-title">Role</span></a>
        <ul class="menu-content">
            <li><a href="{{route('role')}}" class="menu-item"><i class="fa fa-list-alt"></i><span data-i18n="" class="menu-title">Role</span></a></li>  
        </ul>
      </li>
      <li class="nav-item"><a href=""><i class="fa fa-percent"></i><span data-i18n="" class="menu-title">Discount Codes</span></a>
        <ul class="menu-content">
          <li><a href="{{route('discount')}}" class="menu-item"><i class="fa fa-percent"></i><span data-i18n="" class="menu-title">Discount Codes</span></a></li>
        </ul>
      </li>
      <li class="nav-item"><a href=""><i class="icon-grid"></i><span data-i18n="" class="menu-title">Promo</span></a>
        <ul class="menu-content">
            <li><a href="{{route('promo.index')}}" class="menu-item"><i class="icon-grid"></i><span data-i18n="" class="menu-title">Promo</span></a></li>
        </ul>
      </li>
      <li class="nav-item"><a href=""><i class="fa fa-bell"></i><span data-i18n="" class="menu-title">Notification</span></a>
        <ul class="menu-content">
          <li><a href="{{route('notification')}}" class="menu-item"><i class="fa fa-users"></i><span data-i18n="" class="menu-title">Send Notification</span></a></li>
          <li title="Send Notification to Worker"><a href="{{route('worker_notification')}}" class="menu-item"><i class="fa fa-users"></i><span data-i18n="" class="menu-title">Send Notification to Worker</span></a></li>
        </ul>
      </li>
      <li class="nav-item"><a href="{{route('role')}}"><i class="fa fa-question-circle"></i><span data-i18n="" class="menu-title">FAQ</span></a>
        <ul class="menu-content">
            <li><a href="{{route('provider_faq')}}" class="menu-item"><i class="fa fa-question-circle"></i><span data-i18n="" class="menu-title">Provider FAQ</span></a></li>
            <li><a href="{{route('customer_faq')}}" class="menu-item"><i class="fa fa-question-circle"></i><span data-i18n="" class="menu-title">Customer FAQ</span></a></li>   
        </ul>
      </li>
    </ul>
  </div>
</div>
