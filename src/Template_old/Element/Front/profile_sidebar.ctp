<aside class="aside">
    <nav class="sidebar">
      <ul class="nav">
        <li>
		  <div class="item user-block has-submenu">
			<div class="user-block-picture">
			<?php if(!empty($user->image)) { ?>
			<img src="<?php echo $this->request->webroot.'uploads/user_thumb/'.$user->image; ?>" alt="Avatar" width="60" height="60" class="img-thumbnail img-circle account-img-mb"> </div>
			<?php } else { ?>
				<img src="<?php echo $this->request->webroot ?>assets/html/images/02.jpg" alt="Avatar" width="60" height="60" class="img-thumbnail img-circle account-img-mb"> </div>
			<?php } ?>
			<div class="user-block-info"> <span class="user-block-name item-text"><?php echo $user->username; ?></span> <span class="user-block-role"><i class="fa fa-check text-green"></i> Verified</span>
			  <div class="label label-primary"><a style="color:#fff;" href="<?php echo $this->Url->build(['controller'=>'users','action'=>'logout']); ?>"><i class="fa fa-lock"></i> Logout</a></div>
			</div>
		  </div>
		</li>
        <li  id="profile"> <a href="<?php echo $this->url->build(['controller'=>'users','action'=>'profile']); ?>" title="user" class=""> <em class="fa fa-user"></em> <span class="item-text">Profile</span> </a> </li>
		 <li  id="profile"> <a href="<?php echo $this->url->build(['controller'=>'users','action'=>'idVerification']); ?>" title="user" class=""> <em class="fa fa-user"></em> <span class="item-text">KYC</span> </a> </li>
		 <li  id="referral"> <a href="<?php echo $this->url->build(['controller'=>'users','action'=>'referral']); ?>" title="user" class=""> <em class="fa fa-user"></em> <span class="item-text">Referral</span> </a> </li>
        <li class="" id="security"> <a href="<?php echo $this->url->build(['controller'=>'users','action'=>'security']); ?>" title="security" class=""> <em class="fa fa-lock"></em> <span class="item-text">Security</span> </a> </li>
        <li class="" id="support" > <a href="<?php echo $this->url->build(['controller'=>'users','action'=>'support']); ?>" title="support" class=""> <em class="fa fa-life-ring"></em> <span class="item-text">Support</span> </a> </li>
        <li class="" id="tickets"> <a href="<?php echo $this->url->build(['controller'=>'users','action'=>'tickets']); ?>" title="questions" class=""> <em class="fa fa-question-circle"></em> <span class="item-text">My Tickets</span> </a> </li>
      </ul>
    </nav>
  </aside>
  <?php
$gerUrl = $_SERVER['REQUEST_URI'];
$exp = explode('/',$gerUrl);
$actionName = end($exp);

  ?>
  <script>
  $(document).ready(function(){
	  $("ul.nav li").removeClass('active');
	  $("#<?php echo $actionName ?>").addClass('active');
  });
  </script>