
<header class="main-header"> 
  <!-- Logo --> 
  <a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>" class="logo"> 
  <!-- mini logo for sidebar mini 50x50 pixels --> 
  <span class="logo-mini"><img src="<?=$this->request->webroot ?>assets/html/images/livecrypto-logo2.png" alt="&nbsp;"></span>
  <!-- logo for regular state and mobile devices --> 
  <span class="logo-lg"><img src="<?=$this->request->webroot ?>assets/html/images/livecrypto-logo.png" alt="&nbsp;"></span> </a> 
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top"> 
    <!-- Sidebar toggle button--> 
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button"> <span class="sr-only">Toggle navigation</span> </a>
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <!-- Messages: style can be found in dropdown.less-->
        <li class="coin-text"> 
			<?php

			$bitRate =  $this->Conversion->getbitInGalaxy();
			
			//echo '<span>1 AGC = '.number_format((float)$bitRate,8).' BTC </span> ';
			//echo '<span>1 AGC = '.number_format((float)$bitRate,8).' BTC </span> ';
			
			?>
			
			<span id="inrss"></span>  </li>
        
        <!-- User Account: style can be found in dropdown.less -->
        <li class="dropdown user user-menu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
			
			<?php 
			 if($authUser['image'] =='') echo '<img width="16" src="'.$this->request->webroot.'user200.jpg" class="img-circle" >';
			 else echo '<img  width="16" src="'.$this->request->webroot.'uploads/user_thumb/'.$authUser['image'].'" class="img-circle" >';
			?>
			
		<span class="hidden-xs"><?=$authUser['name'];?></span> </a>
          <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header"> 
				<?php 
				 if($authUser['image'] =='') echo '<img src="'.$this->request->webroot.'user200.jpg" class="img-circle" >';
				 else echo '<img src="'.$this->request->webroot.'uploads/user_thumb/'.$authUser['image'].'" class="img-circle" >';
				?>
				
              <p> <?=$authUser['name'];?> <small><?=($authUser['user_type']=='A' ? "Admin":"Member" );?></small> </p>
            </li>
          
            <li class="user-footer">
              <div class="pull-left">
                  <?php if($authUser['user_type']=='A'){ ?>
                  <a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'change_password']);  ?>" class="btn btn-default btn-flat">Change Password</a>
                    <?php } elseif($authUser['user_type']=='U'){?>
                      <a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'profile']);  ?>" class="btn btn-default btn-flat">Change Password</a>
                  <?php } ?>
              </div>
              <div class="pull-right"> <a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'logout']); ?>" class="btn btn-default btn-flat">Sign out</a> </div>
            </li>
          </ul>
        </li>
        <!-- Control Sidebar Toggle Button -->
       
      </ul>
    </div>
  </nav>
</header>

