
<nav class="navbar navbar-default navbar-top navbar-fixed-top">
<!--<div  class="alert alert-danger">Exchange is under maintenance.
</div>-->
    <div class="navbar-header"> <a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']) ?>" class="navbar-brand">
      <div class="brand-logo"><img src="/assets/html/images/livecrypto-logo.png"  alt="Avatar" class="img-responsive"></div>
      <div class="brand-logo-collapsed"><img src="/assets/html/images/livecrypto-logo2.png" alt="Avatar" class="img-responsive"></div>
      </a> </div>
    <div class="nav-wrapper">
     <ul class="nav navbar-nav mt0">
      <li> <a href="javascript:void(0);"  data-toggle="aside" > <strong><i class="fa fa-bars" aria-hidden="true"></i></strong> </a> </li>
        <li> <a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']) ?>" > <strong><em class="fa fa-shopping-cart"></em> Buy/Sell</strong> </a> </li>
		<li></li>
      </ul>
      <ul class="nav navbar-nav navbar-right mt0">
	  <!---Second verification slider start--->
	  <!--<li>
		<ul class="list-inline customcss customcss2">
			<?php //if(!empty($_SESSION['Auth']['User'])) { ?>
			<li><strong>2FA</strong></li>
			
				<li><span class="text-danger">Off</span></li>
				<li><label class="switch" >
				  <input type="checkbox" id="verification_checkbox" value="Y" <?php //if($secondVerification == "Y") { echo "checked"; } ?>>
				  <span class="slider round"></span>
				</label></li>
				<li><span class="text-success">On</span></li>
				<li><div id="return_msg"></div></li>
			<?php //} ?>
		</ul>
	 </li>-->
	 
	 
	<!---Second verification slider end--->
	<?php if(!empty($_SESSION['Auth']['User'])) { ?>
        <li class="dropdown dropdown-list"> <a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'transactionlist']) ?>" data-toggle="dropdown" data-play="bounceIn" class="dropdown-toggle"> <strong> History</strong> </a> </li>
		<?php } ?>
     <!--   <li class="dropdown dropdown-list"> <a href="javascript:void(0);" data-toggle="dropdown" data-play="bounceIn" class="dropdown-toggle"> <em class="fa fa-bell"></em>
          <div class="label label-danger">3</div>
          </a>
          <ul class="dropdown-menu col-md-4 col-sm-6 col-xs-12">
            <li>
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>Buy/Sell</th>
                      <th>Progress</th>
                      <th>Date</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Buy order SC</td>
                      <td><div class="progress progress-striped progress-xs">
                          <div role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" class="w-100-p progress-bar progress-bar-success"> <span class="sr-only">100% Complete</span> </div>
                        </div></td>
                      <td><em class="fa fa-calendar fa-fw text-muted"></em>02/19/2018 </td>
                      <td class="text-center"> Complete </td>
                    </tr>
                    <tr>
                      <td>Sell order SC</td>
                      <td><div class="progress progress-striped progress-xs">
                          <div role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-danger w-50-p"> <span class="sr-only">50% Complete</span> </div>
                        </div></td>
                      <td><em class="fa fa-calendar fa-fw text-muted"></em>02/18/2018 </td>
                      <td class="text-center"> 50% Filled </td>
                    </tr>
                    <tr>
                      <td>Buy order IOTA</td>
                      <td><div class="progress progress-striped progress-xs">
                          <div role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-success w-50-p"> <span class="sr-only">50% Complete</span> </div>
                        </div></td>
                      <td><em class="fa fa-calendar fa-fw text-muted"></em>02/17/2018 </td>
                      <td class="text-center"> 50% Filled </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </li>
          </ul>
        </li>-->
		<?php if(!empty($_SESSION['Auth']['User'])) { ?>
        <li class="dropdown"> <a href="javascript:void(0);" data-toggle="dropdown" data-play="bounceIn" class="dropdown-toggle refof"> <em class="fa fa-user"></em> </a>
		
          <ul class="dropdown-menu">
            <li><a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'profile']) ?>">Profile</a> </li>
            <li><a target="_blank" href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'mywallet']); ?>">My Wallet</a> </li>
			<li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'flatExchange']); ?>">Flat Exchange</a> </li>
           <!-- <li><a href="<?php echo $this->Url->build(['controller'=>'Users','action'=>'ramtransfer']); ?>">Ram Transfer</a> </li>
			<li><a href="javascript:void(0);">Settings</a> </li>-->
            <li class="divider"></li>
            <li><a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'logout']); ?>">Logout</a> </li>
          </ul>
        </li><?php } ?>
      </ul>
    </div>
  </nav>
  
<script>

	$("document").ready(function(){
		$("#verification_checkbox").change(function(){
			if($(this).is(":checked")){
				var boxVal = "Y";	
			}
			else {
				var boxVal = "N";
			}
			
			jQuery.ajax({ 
				url: '<?php echo $this->Url->build(['controller'=>'users' , 'action'=>'change_second_verification']);  ?>',
				type : 'post',
				dataType: "json",
				data : {"verification_status":boxVal},
				success: function(data) {
					 $("#return_msg").removeClass("alert alert-success alert-danger");
                    if (data.success == 1) {
                        $("#authQrCode").attr("src", data.qr);
                        $('#myModal').modal({
                            backdrop: "static",
                            show: true
                        });
                    }

                    //if (boxVal == "Y") {
                    //   $("#return_msg").addClass("alert alert-success").html("2-Step verification enabled.<br>Check your email for 2fa verification code");
                  // } 
                   if (boxVal == "N") {
                        $("#return_msg").addClass("alert alert-danger").html("2-Step verification disabled");
                    }
                    setTimeout('$("#return_msg").fadeOut();', 5000);
				}
			});
		});
	});
</script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-122322473-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-122322473-1');
</script>

