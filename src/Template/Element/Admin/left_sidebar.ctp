<aside class="main-sidebar"> 
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar"> 
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image"> <img src="<?php echo $this->request->webroot;?>css/Admin/bower_components/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image"> </div>
      <div class="pull-left info">
        <p><?=$authUser['name'];?></p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a> </div>
    </div>
    <!-- search form -->
	<?php 
			$level = $authUser['level_id'];
			$user_id = $authUser['id'];

		?>
				<ul class="sidebar-menu" data-widget="tree">
					<li class="header">MAIN NAVIGATION</li>
		<?php 
						foreach($left_bars as $l){
							if($l->treeview == 'N'){
								if($level <= $l->level_id){
		?>
									<li><a href="<?php echo $l->url;?>"><i class="fa <?php echo $l->icon_class;?>"></i><span><?php echo __($l->menu_name);?></span></a></li>

		<?php 
								}

							} else if($l->treeview == 'Y') {
								if($l->treeview_sort == 0){
		?>
										<li class="treeview">
											<a href="#"><i class="fa <?php echo $l->treeview_icon_class1;?>" aria-hidden="true"></i>
												<span><?php echo __($l->treeview_name);?></span><span class="pull-right-container"><i class="fa <?php echo $l->treeview_icon_class2;?>"></i></span>
											</a>
											<ul class="treeview-menu">
		<?php
									}
								if($level <= $l->level_id){
		?>
										<li><a href="<?php echo $l->url;?>"><i class="fa <?php echo $l->icon_class;?>"></i><span><?php echo __($l->menu_name);?></span></a></li>
		<?php
								}
								if(($l->treeview_sort+1) == $l->treeview_cnt){
		?>	
										</ul>
									</li>
		<?php 
								}
							
							}
						} 
		?>
                    <li><a href="/tech/deposit-application/depositapplicationlist2"><i class="fa fa-exchange"></i><span>투자신청목록(개발)</span></a></li>
					<li><a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'logout']);  ?>"><i class="fa fa-sign-out" aria-hidden="true"></i><span>Logout</span></a></li>
				</ul>
    <!-- /.search form --> 
    <!-- sidebar menu: : style can be found in sidebar.less 
    <ul class="sidebar-menu" data-widget="tree">
		<li class="header">MAIN NAVIGATION</li>
		
		<li> <a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"> <i class="fa fa-dashboard"></i> <span>Dashboard</span> </a> </li>
		
		<!--<li> <a href="<?php //echo $this->Url->build(['controller'=>'Transactions','action'=>'transaction']);  ?>"> <i class="fa fa-exchange"></i> <span>Transactions</span> </a> </li>
		
		<li class="treeview">
			<a href="#"><i class="fa fa-exchange" aria-hidden="true"></i>
			<span>Withdrawals</span><span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span>
			</a>
			<ul class="treeview-menu">
			<li> <a href="<?php echo $this->Url->build(['controller'=>'Reports','action'=>'withdrawal']);  ?>"> <i class="fa fa-upload"></i> <span>Withdrawal</span> </a> </li>
				<!--<li> <a href="<?php //echo $this->Url->build(['controller'=>'Reports','action'=>'ramWithdrawal']);  ?>"> <i class="fa fa-upload"></i> <span>Ram Withdrawal</span> </a> </li>
				
				<li> <a href="<?php //echo $this->Url->build(['controller'=>'Reports','action'=>'admcWithdrawal']);  ?>"> <i class="fa fa-upload"></i> <span>Admc Withdrawal</span> </a> </li>
				<li> <a href="<?php //echo $this->Url->build(['controller'=>'Reports','action'=>'usdWithdrawal']);  ?>"> <i class="fa fa-upload"></i> <span>Usd Withdrawal</span> </a> </li>
			</ul>
		</li>
		
		<li class="treeview">
			<a href="#"><i class="fa fa-exchange" aria-hidden="true"></i>
			<span>Deposits</span><span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span>
			</a>
			<ul class="treeview-menu">
			<li> <a href="<?php echo $this->Url->build(['controller'=>'Reports','action'=>'deposit']);  ?>"> <i class="fa fa-download"></i> <span>Deposit</span> </a> </li>
				<!--<li> <a href="<?php //echo $this->Url->build(['controller'=>'Reports','action'=>'ramDeposit']);  ?>"> <i class="fa fa-download"></i> <span>Ram Deposit</span> </a> </li>
				
				<li> <a href="<?php //echo $this->Url->build(['controller'=>'Reports','action'=>'admcDeposit']);  ?>"> <i class="fa fa-download"></i> <span>Admc Deposit</span> </a> </li>
				<li> <a href="<?php //echo $this->Url->build(['controller'=>'Reports','action'=>'usdDeposit']);  ?>"> <i class="fa fa-download"></i> <span>Usd Deposit</span> </a> </li>
				
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
		
		<!--<li> <a href="<?php //echo $this->Url->build(['controller'=>'Reports','action'=>'usertxn']);  ?>"> <i class="fa fa-exchange"></i> <span>Users Coin Report</span> </a> </li>
		
		<!--<li> <a href="<?php //echo $this->Url->build(['controller'=>'Reports','action'=>'ethReport']);  ?>"> <i class="fa fa-exchange"></i> <span>Eth Report</span> </a> </li>

        <li> <a href="<?php echo $this->Url->build(['controller'=>'Coin','action'=>'index']);  ?>"> <i class="fa fa-exchange"></i> <span>Coins</span> </a> </li>
        <li> <a href="<?php echo $this->Url->build(['controller'=>'Coin','action'=>'coinpairIndex']);  ?>"> <i class="fa fa-exchange"></i> <span>Coin Pairs</span> </a> </li>
        <li> <a href="<?php echo $this->Url->build(['controller'=>'Coin','action'=>'sendreward']);  ?>"> <i class="fa fa-exchange"></i> <span>Send Reward</span> </a> </li>
        <li> <a href="<?php echo $this->Url->build(['controller'=>'Coin','action'=>'rewardlist']);  ?>"> <i class="fa fa-exchange"></i> <span>Send Reward List</span> </a> </li>

        <li> <a href="<?php echo $this->Url->build(['controller'=>'Coin','action'=>'withdrawalreward']);  ?>"> <i class="fa fa-exchange"></i> <span>Withdrawal Reward</span> </a> </li>
        <li> <a href="<?php echo $this->Url->build(['controller'=>'Coin','action'=>'withdrawalrewardlist']);  ?>"> <i class="fa fa-exchange"></i> <span>Withdrawal Reward List</span> </a> </li>


        <li><a href="<?php echo $this->Url->build(['controller'=>'Settings','action'=>'fee']);  ?>"> <i class="fa fa-gear" aria-hidden="true"></i> <span>Fee Setting</span></a></li>
        <li> <a href="<?php echo $this->Url->build(['controller'=>'Users','action'=>'mywallet']);  ?>"> <i class="fa fa-exchange"></i> <span>Transfer Amount</span> </a> </li>
        <li> <a href="<?php echo $this->Url->build(['controller'=>'Transactions','action'=>'depositlist']);  ?>"> <i class="fa fa-money"></i> <span>Users Deposit Amount</span> </a> </li>
        <li> <a href="<?php echo $this->Url->build(['controller'=>'Transactions','action'=>'withdrawallist']);  ?>"> <i class="fa fa-money"></i> <span>Users Withdrawal Amount</span> </a> </li>
        <li> <a href="<?php echo $this->Url->build(['controller'=>'Transactions','action'=>'coinswithdrawallist']);  ?>"> <i class="fa fa-money"></i> <span>Users Coins Withdrawal Amount</span> </a> </li>
<!--        <li> <a href="<?php //echo $this->Url->build(['controller'=>'Transactions','action'=>'withdrawallistold']);  ?><!--"> <i class="fa fa-money"></i> <span>Users Deposit Amount Old</span> </a> </li>
        <li><a href="<?php echo $this->Url->build(['controller'=>'DepositApplication','action'=>'depositapplicationlist']);?>"><i class="fa fa-exchange"></i><span>Deposit Application List</span></a></li>
        <li><a href="<?php echo $this->Url->build(['controller'=>'Transactions','action'=>'couponslist']);  ?>"><i class="fa fa-money"></i><span>Users Coupons List</span></a></li>
        <li><a href="<?php echo $this->Url->build(['controller'=>'Transactions','action'=>'admincouponslist']);  ?>"><i class="fa fa-money"></i><span>Admin Coupons List</span></a></li>

        <li class="treeview">
            <a href="#"><i class="fa fa-users" aria-hidden="true"></i>
                <span>Users</span><span class="pull-right-container"> <i class="fa fa-vcard-o"></i> </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?php echo $this->Url->build(['controller'=>'Reports','action'=>'users']);  ?>"> <i class="fa fa-users" aria-hidden="true"></i> <span>Users</span></a></li>
                <li><a href="<?php echo $this->Url->build(['controller'=>'Reports','action'=>'kyclist']);  ?>"> <i class="fa fa-user-circle" aria-hidden="true"></i> <span>Kyc List</span></a></li>
                <li><a href="<?php echo $this->Url->build(['controller'=>'Reports','action'=>'userbalnace']);  ?>"> <i class="fa fa-balance-scale" aria-hidden="true"></i> <span>User Balance</span></a></li>
                <li><a href="<?php echo $this->Url->build(['controller'=>'Users','action'=>'userdetails']);  ?>"> <i class="fa fa-id-card-o" aria-hidden="true"></i> <span>Users' Details</span></a></li>
                <li><a href="<?php echo $this->Url->build(['controller'=>'Users','action'=>'userauthreq']);  ?>"><i class="fa fa-address-book-o" aria-hidden="true"></i><span>Users' Auth Change Requests</span></a></li>
                <li><a href="<?php echo $this->Url->build(['controller'=>'Users','action'=>'principalwalletdetails']);  ?>"> <i class="fa fa-vcard" aria-hidden="true"></i> <span>Users' Principal Wallet Details</span></a></li>
                <li><a href="<?php echo $this->Url->build(['controller'=>'Users','action'=>'transactionsdetails']);  ?>"><i class="fa fa-vcard-o" aria-hidden="true"></i><span>Users' Transactions Details</span></a></li>
            </ul>
        </li>

        <li class="treeview">
            <a href="#"><i class="fa fa-gears" aria-hidden="true"></i>
                <span>Settings</span><span class="pull-right-container"> <i class="fa fa-shield"></i> </span>
            </a>
            <ul class="treeview-menu">
                <li> <a href="<?php echo $this->Url->build(['controller'=>'settings','action'=>'settings']);  ?>"> <i class="fa fa-wrench"></i> <span>Settings</span> </a> </li>
                <li> <a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'level']);  ?>"> <i class="fa fa-users"></i> <span>Assign Levels</span> </a> </li>
                <li> <a href="<?php echo $this->Url->build(['controller'=>'reports','action'=>'adminlist']);  ?>"> <i class="fa fa-shield"></i> <span>Admins</span> </a> </li>
                <li><a href="<?php echo $this->Url->build(['controller'=>'TmpCoinAddress','action'=>'add']); ?>"><i class="fa fa-shield"></i><span>Add Coin Address</span></a></li>
            </ul>
        </li>

        <li class="treeview">
            <a href="#"><i class="fa fa-gears" aria-hidden="true"></i>
                <span>E-Pay</span><span class="pull-right-container"> <i class="fa fa-shield"></i> </span>
            </a>
            <ul class="treeview-menu">
                <li> <a href="<?php echo $this->Url->build(['controller'=>'epay','action'=>'settings']);  ?>"> <i class="fa fa-wrench"></i> <span>Settings</span> </a> </li>
                <li> <a href="<?php echo $this->Url->build(['controller'=>'epay','action'=>'lists']);  ?>"> <i class="fa fa-users"></i> <span>User Lists</span> </a> </li>
                <li> <a href="<?php echo $this->Url->build(['controller'=>'epay','action'=>'logs']);  ?>"> <i class="fa fa-users"></i> <span>Logs</span> </a> </li>
            </ul>
        </li>

        <li><a href="<?php echo $this->Url->build(['controller'=>'Settings','action'=>'notice']);  ?>"> <i class="fa fa-newspaper-o" aria-hidden="true"></i> <span>Notice</span></a></li>
        <li><a href="<?php echo $this->Url->build(['controller'=>'Settings','action'=>'faqs']);  ?>"> <i class="fa fa-comments" aria-hidden="true"></i> <span>Faqs</span></a></li>
        <li><a href="<?php echo $this->Url->build(['controller'=>'contact_us','action'=>'manage']);  ?>"> <i class="fa fa-envelope" aria-hidden="true"></i> <span>Contact Us</span></a></li>
        <li><a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'logout']);  ?>"> <i class="fa fa-sign-out" aria-hidden="true"></i> <span>Logout</span></a></li>


    </ul>-->
  </section>
  <!-- /.sidebar --> 
</aside>
