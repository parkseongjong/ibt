
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
  vertical-align: middle;
}

.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
.customcss{
	color: #fff;
padding-top: 20px;
padding-left: 20px;
}
.main-header{ z-index:1200; }
</style>
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
<!---Second verification slider start--->
	<ul class="list-inline customcss">
<li><strong>2FA</strong></li>
    <li><span class="text-danger">Off</span></li>
    <li><label class="switch">
	  <input type="checkbox" id="verification_checkbox" value="Y" <?php if($secondVerification == "Y") { echo "checked"; } ?>>
	  <span class="slider round"></span>
	</label></li>
    <li><span class="text-success">On</span></li>
	<li><div id="return_msg"></div></li>
</ul>
	<!---Second verification slider end--->
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
	<li class="treeview"> <a href="#"> <i class="fa fa-wrench" aria-hidden="true"></i> <span>BTC Wallet</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
		<ul class="treeview-menu">
		  <li><a  href="<?php echo $this->Url->build(['controller'=>'Transactions','action'=>'btcDeposit']);  ?>"><i class="fa fa-user" aria-hidden="true"></i> Deposit </a></li>
		  <li><a href="<?php echo $this->Url->build(['controller'=>'Transactions','action'=>'btcWithdrawl']);  ?>"><i class="fa fa-user" aria-hidden="true"></i> Withdrawl</a></li>
		  
		</ul>
	</li>	
	
	<li> <a  href="<?php echo $this->Url->build(['controller'=>'Transactions','action'=>'ico']);  ?>"> <i class="fa fa-dashboard"></i> <span>ICO </span> </a> </li>
	
	<li> <a  href="<?php echo $this->Url->build(['controller'=>'Transactions','action'=>'lending']);  ?>"> <i class="fa fa-dashboard"></i> <span>Staking packages </span> </a> </li>
	
	<li class="treeview"> <a href="#"> <i class="fa fa-exchange" aria-hidden="true"></i> <span>Exchange</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
		<ul class="treeview-menu">
		  <li><a  href="<?php echo $this->Url->build(['controller'=>'Transactions','action'=>'newexchange']);  ?>"><i class="fa fa-exchange" aria-hidden="true"></i> Exchange </a></li>
		  
		  <?php
			$curerntDate = time();	
			$LchTime = strtotime("2018-04-15 13:30:00"); 

			$getDiff = $LchTime - $curerntDate;
			if($getDiff<0){
		?>
		  <li><a href="<?php echo $this->Url->build(['controller'=>'Transactions','action'=>'mysellexchangeorder']);  ?>"><i class="fa fa-exchange" aria-hidden="true"></i> My Sell Order</a></li>
		  <li><a href="<?php echo $this->Url->build(['controller'=>'Transactions','action'=>'mybuyexchangeorder']);  ?>"><i class="fa fa-exchange" aria-hidden="true"></i> My Buy Order</a></li>
		<?php } ?>
		</ul>
	</li>
	
	<!-- <li> <a href="<?php echo $this->Url->build(['controller'=>'Transactions','action'=>'transaction','referral']);  ?>"> <i class="fa fa-dashboard"></i> <span>Referral</span> </a> </li> 

	<li><a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'referral']);  ?>"> <i class="fa fa-users" aria-hidden="true"></i> <span>Referral Team</span></a></li> -->
	
    <li><a href="<?php echo $this->Url->build(['controller'=>'LoginLogs','action'=>'index']);  ?>"> <i class="fa fa-users" aria-hidden="true"></i> <span>Login Logs</span></a></li>
	
    <li class="treeview"> <a href="#"> <i class="fa fa-wrench" aria-hidden="true"></i> <span>TOOLS</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
        <ul class="treeview-menu">
			<li><a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'profile']);  ?>"><i class="fa fa-user" aria-hidden="true"></i> Profile</a></li>
        </ul>
    </li>
	
	<!--<li> <a href="<?php //echo $this->Url->build(['controller'=>'Transactions','action'=>'withdrawal']);  ?>"> <i class="fa fa-wrench"></i> <span>Withdrawal</span> </a> </li>-->
    
	<li><a href="<?php echo $this->Url->build(['controller'=>'tickets','action'=>'index']);  ?>"> <i class="fa fa-life-ring" aria-hidden="true"></i> <span>SUPPORT</span></a></li>
    <!--   <li><a href="<?php //echo $this->Url->build(['controller'=>'invite_friends','action'=>'index']);  ?>"> <i class="fa fa-share" aria-hidden="true"></i> <span>INVITE FRIENDS</span></a></li>-->

   <li><a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'logout']);  ?>"> <i class="fa fa-sign-out" aria-hidden="true"></i> <span>Logout</span></a></li>
      
    </ul>
  </section>
  <!-- /.sidebar --> 
</aside>

<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">2-step verification enabled </h4>
            </div>
            <div class="modal-body">
                Please download "Google Authenticator" application on your mobile and scan the code.
                Please use QRcode Or email verification code sent on your email.
                <div style="text-align: center;">
                    <img src="#" id="authQrCode">
                </div>
            </div>
            <!--            <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        </div>-->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
