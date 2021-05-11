<header class="main-header">

    <!-- Logo -->
    <a href="index.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>S</b>moke</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Smoke</b>Shop<?php //echo $_SESSION['company_name']; ?></span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
         <ul class="nav navbar-nav navbar-right">  
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="fa fa-gears"></span>&nbsp;<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
			<li><a href="<?php echo SITE_ROOT; ?>?route=modules/system/user_profile"><span class='glyphicon glyphicon-user'></span> User Profile</a></li> 
			<li class="divider"></li>  

					<?php if($_SESSION['role_id']==1){ ?>
					<li><a href="<?php echo SITE_ROOT; ?>?route=modules/system/user_management"><span class='glyphicon glyphicon-user'></span>&nbsp; Manage Users</a></li> 
					<li class="divider"></li> 
					<li><a href="<?php echo SITE_ROOT; ?>?route=modules/system/login_log"><span class='fa fa-book'></span>&nbsp; Logins Log</a></li> 
					<li class="divider"></li> 					
			<li><a href="<?php echo SITE_ROOT; ?>?route=modules/gl/setup/company/company_info"><span class='glyphicon glyphicon-cog'></span>&nbsp; Company Setup</a></li> 
					<li class="divider"></li> 
					<?php } ?>
				<!--<li><a href="<?php echo SITE_ROOT; ?>?route=modules/system/app_management"><span class='glyphicon glyphicon-cog'></span>&nbsp;Application Setup</a></li> 
					<li class="divider"></li> -->
            <li><a href="<?php echo SITE_ROOT; ?>?logout=1" ><span class="glyphicon glyphicon-off"></span> Logout</a></li>
          </ul>
        </li>
      </ul>
      </div>

    </nav>
  </header>
 