<aside class="main-sidebar"> 
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar"> 
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image"> <img src="<?php echo $this->request->webroot;?>css/Admin/bower_components/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image"> </div>
      <div class="pull-left info">
        <p>Admin</p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a> </div>
    </div>
    <!-- search form -->

    <!-- /.search form --> 
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
		<li class="header">MAIN NAVIGATION</li>
		
		<li> <a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"> <i class="fa fa-dashboard"></i> <span>Dashboard</span> </a> </li>
		
		<li> <a href="<?php echo $this->Url->build(['controller'=>'Transactions','action'=>'transaction']);  ?>"> <i class="fa fa-exchange"></i> <span>BTC/ETH Transactions</span> </a> </li>
		
		<li class="treeview">
			<a href="#"><i class="fa fa-exchange" aria-hidden="true"></i>
			<span>Withdrawals</span><span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span>
			</a>
			<ul class="treeview-menu">
			<li> <a href="<?php echo $this->Url->build(['controller'=>'Reports','action'=>'ethWithdrawal']);  ?>"> <i class="fa fa-upload"></i> <span>Withdrawal</span> </a> </li>
				<!--<li> <a href="<?php //echo $this->Url->build(['controller'=>'Reports','action'=>'ramWithdrawal']);  ?>"> <i class="fa fa-upload"></i> <span>Ram Withdrawal</span> </a> </li>
				
				<li> <a href="<?php //echo $this->Url->build(['controller'=>'Reports','action'=>'admcWithdrawal']);  ?>"> <i class="fa fa-upload"></i> <span>Admc Withdrawal</span> </a> </li>
				<li> <a href="<?php //echo $this->Url->build(['controller'=>'Reports','action'=>'usdWithdrawal']);  ?>"> <i class="fa fa-upload"></i> <span>Usd Withdrawal</span> </a> </li>-->
			</ul>
		</li>
		
		<li class="treeview">
			<a href="#"><i class="fa fa-exchange" aria-hidden="true"></i>
			<span>Deposits</span><span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span>
			</a>
			<ul class="treeview-menu">
			<li> <a href="<?php echo $this->Url->build(['controller'=>'Reports','action'=>'ethDeposit']);  ?>"> <i class="fa fa-download"></i> <span>Deposit</span> </a> </li>
				<!--<li> <a href="<?php //echo $this->Url->build(['controller'=>'Reports','action'=>'ramDeposit']);  ?>"> <i class="fa fa-download"></i> <span>Ram Deposit</span> </a> </li>
				
				<li> <a href="<?php //echo $this->Url->build(['controller'=>'Reports','action'=>'admcDeposit']);  ?>"> <i class="fa fa-download"></i> <span>Admc Deposit</span> </a> </li>
				<li> <a href="<?php //echo $this->Url->build(['controller'=>'Reports','action'=>'usdDeposit']);  ?>"> <i class="fa fa-download"></i> <span>Usd Deposit</span> </a> </li>-->
				
			</ul>
		</li>
		
		<li class="treeview">
			<a href="#"><i class="fa fa-exchange" aria-hidden="true"></i>
			<span>Exchange</span><span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span>
			</a> 
			<ul class="treeview-menu">
				<li> <a href="<?php echo $this->Url->build(['controller'=>'Exchange','action'=>'buyList']);  ?>"> <i class="fa fa-upload"></i> <span>Buy List</span> </a> </li>
				<li> <a href="<?php echo $this->Url->build(['controller'=>'Exchange','action'=>'sellList']);  ?>"> <i class="fa fa-upload"></i> <span>Sell List</span> </a> </li>
				<li> <a href="<?php echo $this->Url->build(['controller'=>'Exchange','action'=>'transaction']);  ?>"> <i class="fa fa-exchange"></i> <span>Transactions</span> </a> </li>
			</ul> 
		</li>
		
		<!--<li class="treeview">
			<a href="#"><i class="fa fa-exchange" aria-hidden="true"></i>
			<span>Volume Report</span><span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span>
			</a> 
			<ul class="treeview-menu">
				<li> <a href="<?php //echo $this->Url->build(['controller'=>'Exchange','action'=>'volume',3]);  ?>"> <i class="fa fa-upload"></i> <span>RAM</span> </a> </li>
				<li> <a href="<?php //echo $this->Url->build(['controller'=>'Exchange','action'=>'volume',4]);  ?>"> <i class="fa fa-upload"></i> <span>ADMC</span> </a> </li>
			
			</ul> 
		</li>-->
		
		<!--<li> <a href="<?php //echo $this->Url->build(['controller'=>'Reports','action'=>'usertxn']);  ?>"> <i class="fa fa-exchange"></i> <span>Users Coin Report</span> </a> </li>-->
		
		<li> <a href="<?php echo $this->Url->build(['controller'=>'Reports','action'=>'ethReport']);  ?>"> <i class="fa fa-exchange"></i> <span>Eth Report</span> </a> </li>
		
		<li> <a href="<?php echo $this->Url->build(['controller'=>'Coin','action'=>'index']);  ?>"> <i class="fa fa-exchange"></i> <span>Coins</span> </a> </li>
		
		<li> <a href="<?php echo $this->Url->build(['controller'=>'Coin','action'=>'coinpairIndex']);  ?>"> <i class="fa fa-exchange"></i> <span>Coin Pairs</span> </a> </li>
		
		<li> <a href="<?php echo $this->Url->build(['controller'=>'Coin','action'=>'sendreward']);  ?>"> <i class="fa fa-exchange"></i> <span>Send Reward</span> </a> </li>
		
		<li> <a href="<?php echo $this->Url->build(['controller'=>'Coin','action'=>'rewardlist']);  ?>"> <i class="fa fa-exchange"></i> <span>Send Reward List</span> </a> </li>		
		
		
		<li><a href="<?php echo $this->Url->build(['controller'=>'Reports','action'=>'users']);  ?>"> <i class="fa fa-users" aria-hidden="true"></i> <span>Users</span></a></li>
		
		<li><a href="<?php echo $this->Url->build(['controller'=>'contact_us','action'=>'manage']);  ?>"> <i class="fa fa-envelope" aria-hidden="true"></i> <span>Contact Us</span></a></li>
		
		<li><a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'logout']);  ?>"> <i class="fa fa-sign-out" aria-hidden="true"></i> <span>Logout</span></a></li>
		
		
    </ul>
  </section>
  <!-- /.sidebar --> 
</aside>
