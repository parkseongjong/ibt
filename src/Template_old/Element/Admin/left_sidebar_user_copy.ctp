<aside class="main-sidebar"> 
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar"> 
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image"> 
		  <?php 
		  if($authUser['image'] !=''){
		  echo ' <img src="'.$this->request->webroot.'uploads/user_thumb/'.$authUser['image'].'" class="img-circle" alt="User Image">';
		  }else{
			   echo ' <img src="'.$this->request->webroot.'user200.jpg" class="img-circle" alt="User Image">';
			 }
		  ?>
		  </div>
      <div class="pull-left info">
        <p>User</p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a> </div>
    </div>

    <!-- /.search form --> 
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MAIN NAVIGATION</li>
      <li> <a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"> <i class="fa fa-dashboard"></i> <span>Dashboard</span> </a> </li>

      
      
      <!--<li class="treeview">
		 <a href="#"><i class="fa fa-google" aria-hidden="true"></i>
		  <span>Galaxy Coin Wallet</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> 
		 </a>
        <ul class="treeview-menu">
          
          <li><a href="<?php //echo $this->Url->build(['controller'=>'Transactions','action'=>'Galaxy']);  ?>"><i class="fa fa-plus" aria-hidden="true"></i> Buy</a></li>
          
          <li><a href="<?php //echo $this->Url->build(['controller'=>'Transactions','action'=>'transaction','Galaxy']);  ?>"> <i class="fa fa-exchange" aria-hidden="true"></i> Transaction</a></li>
        </ul>
      </li>-->
     
	  
	<li> <a href="<?php echo $this->Url->build(['controller'=>'Transactions','action'=>'transaction','purchase']);  ?>"> <i class="fa fa-dashboard"></i> <span><?php echo $coinNameStatic ?> Wallet</span> </a> </li>  
	
	<li> <a href="<?php echo $this->Url->build(['controller'=>'Transactions','action'=>'transaction','referral']);  ?>"> <i class="fa fa-dashboard"></i> <span>Referral</span> </a> </li>  
     
	<li> <a href="<?php echo $this->Url->build(['controller'=>'Transactions','action'=>'transaction','bonus']);  ?>"> <i class="fa fa-dashboard"></i> <span>Bonus</span> </a> </li>  
	
	<li><a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'referral']);  ?>"> <i class="fa fa-users" aria-hidden="true"></i> <span>Referral Team</span></a></li>
        <li><a href="<?php echo $this->Url->build(['controller'=>'LoginLogs','action'=>'index']);  ?>"> <i class="fa fa-users" aria-hidden="true"></i> <span>Login Logs</span></a></li>
      <li class="treeview"> <a href="#"> <i class="fa fa-wrench" aria-hidden="true"></i> <span>TOOLS</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
        <ul class="treeview-menu">
          <li><a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'profile']);  ?>"><i class="fa fa-user" aria-hidden="true"></i> Profile</a></li>
          
        </ul>
      </li>
	<li> <a href="<?php echo $this->Url->build(['controller'=>'Transactions','action'=>'withdrawal']);  ?>"> <i class="fa fa-wrench"></i> <span>Withdrawal</span> </a> </li>
     <li><a href="<?php echo $this->Url->build(['controller'=>'tickets','action'=>'index']);  ?>"> <i class="fa fa-life-ring" aria-hidden="true"></i> <span>SUPPORT</span></a></li>
    <!--   <li><a href="<?php echo $this->Url->build(['controller'=>'invite_friends','action'=>'index']);  ?>"> <i class="fa fa-share" aria-hidden="true"></i> <span>INVITE FRIENDS</span></a></li>-->

   <li><a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'logout']);  ?>"> <i class="fa fa-sign-out" aria-hidden="true"></i> <span>Logout</span></a></li>
      
    </ul>
  </section>
  <!-- /.sidebar --> 
</aside>
