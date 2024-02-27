<?php //echo $this->element('Front/profile_sidebar'); ?>
 <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
<style>
.row {
	margin-right: -15px;
	margin-left: -15px;
	margin: 10px;
}
</style>
<!--A Design by W3layouts 
Author: W3layout
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<html>
<head>
<title>:: Livecrypto Exchange ::</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="stylish Sign in and Sign up Form A Flat Responsive widget, Login form web template,Flat Pricing tables,Flat Drop downs  Sign up Web Templates, Flat Web Templates, Login signup Responsive web template, Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!--online_fonts-->
	<link href="//fonts.googleapis.com/css?family=Sansita:400,400i,700,700i,800,800i,900,900i&amp;subset=latin-ext" rel="stylesheet">
	<link href="//fonts.googleapis.com/css?family=Poiret+One" rel="stylesheet">
<!--//online_fonts-->
	<link href="<?php echo $this->request->webroot ?>html_css/style.css" rel='stylesheet' type='text/css' media="all" />
    <link href="<?php echo $this->request->webroot ?>html_css/bootstrap.css" rel='stylesheet' type='text/css' media="all" />


    <!--stylesheet-->
    
 <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<div id="particles-js"></div>

<style>
#particles-js {
    position: absolute;
    background: url(../img/header_bg.jpg) no-repeat;
        background-repeat: no-repeat;
        background-size: auto auto;
    background-repeat: no-repeat;
	height:100%;
	width:100%;
}

</style>
   
    
</head>
<body>






<div id="top_wrapper">
 <div class="container2">
  <div class="row">
   <div class="col-md-3 col-xs-8">
    <div class="logo">
     <h2>Livecrypto Exchange</h2>
    </div>
   </div>
   <div class="col-md-9">
    <div class="logo">
     <div class="menu">
   
   <nav class="navbar navbar-default">

    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav navbar-right">
        <li class="active"><a href="/">Home <span class="sr-only">(current)</span></a></li>
      <!--  <li><a href="#">About Us  </a></li>
        <li><a href="#">Currencies </a></li>
        <li><a href="#">Markets </a></li>
        <li><a href="#">Blog</a></li>
        <li><a href="#">CONTACT</a></li>-->
		<li><a href="/front">Login</a></li>
        
      </ul>
    </div><!-- /.navbar-collapse -->

</nav>
   
   </div>
    </div>
   </div>
  </div>
 </div>
</div>

<section>
    <section class="main-content"> 
      <!-- Live Coin Price -->
      <div class="row">
        <div class="col-lg-12">
          <div class="panel panel-default">
            <div class="panel-collapse">
              <div class="panel-body"> 
                <!-- Table Starts Here -->
                <section class="main-content">
                  <h3>Support </h3>
				   <div class="container">
                  <div class="row">
                    <div class="col-md-10 col-md-offset-2">
					<?= $this->Flash->render() ?>
                      <div class="feedback-form in_form">
					    <?php echo $this->Form->create($user,array('enctype'=>'multipart/form-data','class'=>'form-horizontal form-label-left','method'=>'post'));?>
                          
                        <ul style="list-style-type:none;">
						
						<li class="item row">
                          <div  class="col-md-3">
                           <span>Email</span></span><span class="need">&nbsp;*</span>
                           </div>
                           <div  class="col-md-6">
                             <input type="email" name="email" id="email" class="form-control">
                            </div>
                            
                        </li>
						
                          <li class="item row">
                          <div  class="col-md-3">
                           <span>Issue Type</span></span><span class="need">&nbsp;*</span>
                           </div>
                           <div  class="col-md-6">
                            <select name="issue_type" id="issue_type" required class="form-control">
                             <option value="">Choose here</option>
                             <option value="login_issue">Login Issue</option>
                             <option value="ram_deposit_issue">Ram Deposit Issue</option>
                             <option value="admc_deposit_issue">ADMC Deposit Issue</option>
                             <option value="Admc_withdrawal_issue">ADMC Withdrawal Issue</option>
                             <option value="eth_deposit">ETH Deposit</option>
                             <option value="eth_withdrawal">ETH Withdrawal</option>
                             <option value="other">Other</option>
                            </select>
                            </div>
                            
                          </li>
						    <li class="item row" id="tx_id_li" style="display:none;">
                          <div  class="col-md-3">
                           <span>Transaction Id</span></span><span class="need">&nbsp;</span>
                           </div>
                           <div  class="col-md-6">
                            <input type="text" name="tx_id"  class="form-control" />
                            </div>
                            
                          </li>
						   <li class="item row">
                          <div  class="col-md-3">
                           <span>Issue</span></span><span class="need">&nbsp;*</span>
                           </div>
                           <div  class="col-md-6">
                            <textarea name="issue" required class="form-control"></textarea>
                            </div>
                            
                          </li>
						  
						  
                          <li class="item row">
                          <div  class="col-md-3">
                           <span>Upload</span>
                           </div>
                            <div  class="col-md-9">
							  <div class="up_dashed">
							  <i class="fa fa-plus iconfont" aria-hidden="true"></i>
							  <input type="file" name="issue_file" id="fileInput" class="img-upload-input" accept="image/jpeg,image/jpg,image/png">
							  
                             
                             </div> <span>and each file size should not exceed 5MB. Supported formats: jpg / jpeg / png </span>
                             </div></li>
							 
							 <li class="item row">
								<div  class="col-md-3">
							   &nbsp;
							   </div>
								<div  class="col-md-9">
								  <div class="g-recaptcha" data-sitekey="6Ld4AFoUAAAAAIK9geac7EVbDf8bUb7RGMKiXdaj"></div>
								 </div>
							 
							 
								
							</li>	 
                            
                          <li class="item row">
                          <div  class="col-md-3">
                          
                           </div>
                           <div  class="col-md-6">
                            <button class="confirm-btn btn " >Submit</button>
                            </div>
                            
                          </li>
                          
                        </ul>
						</form>
                      </div>
                    </div>
                  </div>
				   
				   </div>
				  
                </section>
                <!-- Table Ends Here --> 
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </section>
  <script>
  $('document').ready(function(){
	  
	  $("#issue_type").change(function(){
		  var getVal = $(this).val();
		
		  if(getVal == 'ram_deposit_issue' ||  getVal == 'admc_deposit_issue' ||  getVal == 'Admc_withdrawal_issue' ||  getVal == 'eth_deposit' ||  getVal == 'eth_withdrawal'){
			 $("#tx_id_li").show();
		  }
		  else {
			  $("#tx_id_li").hide();
		  }
	  });
  });
  </script>
<div id="copy">
 <div class="container">
  <div class="row">
   <div class="col-md-12" style="text-align:center; color:#fff; font-size:14px; padding:20px 0; opacity:0.7">2018 © Livecrypto Exchange. All Rights Reserved</div>
  </div>
 </div>
</div>
  <script src='https://www.google.com/recaptcha/api.js'></script>
  
  