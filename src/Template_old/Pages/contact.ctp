<!DOCTYPE html>
<html lang="en">
<head>
<!--Script to show timer-->
<!--End Script to show timer-->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="HandheldFriendly" content="True">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="description" content="Hedge Connect is a decentralized self regulated financial payment network created for users that want to be independent from third parties like banks or the government.
No middlemen or other institution are needed for processing transactions.">
<meta name="keywords" content="Hedge Connect, HedgeConnect, bitcoin, blockchain, mining, profit ">
<title>Hedge Connect</title>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800%7CRoboto:300,400,500,700,900" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="<?=$this->request->webroot ?>assets/hedge/scss/bootstrap.css">
<link rel="stylesheet" href="<?=$this->request->webroot ?>assets/hedge/css/plugins.css">
<link rel="stylesheet" href="<?=$this->request->webroot ?>assets/hedge/scss/icofont.css">
<link rel="stylesheet" href="<?=$this->request->webroot ?>assets/hedge/scss/style.css">
<link rel="stylesheet" href="<?=$this->request->webroot ?>assets/hedge/scss/colors.css">
<link rel="stylesheet" href="<?=$this->request->webroot ?>assets/hedge/scss/responsive.css">
<link rel="stylesheet" href="<?=$this->request->webroot ?>assets/hedge/css/custom.css">
<style class="color_css"></style>
<link rel="stylesheet" href="<?=$this->request->webroot ?>assets/hedge/css/counter.css">
<link rel="shortcut icon" href="<?=$this->request->webroot ?>assets/hedge/favicon.png" type="image/x-icon">

	<style>
		body{margin:0px;padding:0px}
		.clearfix{clear:both;}
		.t-header{background:#0d6070;height:70px;}
		
		.pull-left{float:left;}
		.pull-right{float:right;}
		.full-width{width:100%}
		.logo{width: 52px;float: left;margin: 8px 10px 0 0;}
		.logo img{max-width:100%}
		#footer{    padding: 30px 0px;overflow: hidden;font-size: 12px;line-height: 14px;font-weight: 600;color: #2196f3;background-color: #212121;}
		.socail-network{font-size: 16px;line-height: 18px;color: #0d6070;overflow: hidden;margin: 0;}
		.socail-network li{display: inline-block;vertical-align: middle;margin: 0 0 0 26px;}
		.socail-network a{display: block;color: #bbbbbb;transition: all 0.25s linear;}
		.thanks-sec{padding:80px 0px;background:#f5f5f5;}
		.thanks-msg{ padding:30px;   width: 60%;    margin: 0 auto;    background: #fff;box-shadow: 0px 2px 4px #ccc;}
		.m-top-30{margin-top:30px}
		.th-text{font-size:16px;letter-spacing:.6px;}
		
	</style>

<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '1512266082175831');
  fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=1512266082175831&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
</head>
<script>
  fbq('track', 'CompleteRegistration');
</script>
<body>
	
	<header id="header">
    <div class="logo"> <a href="index.html"><img src="<?=$this->request->webroot ?>assets/hedge/images/logo.png" alt="HedgeConnect" class="img-responsive"></a> </div>
    <a href="#" class="nav-opener"><i class="fa fa-bars"></i> <i class="fa fa-times"></i></a>
    <nav id="nav">
      <ul class="list-unstyled">
        <li class="drop"> <a href="#" class="smooth" data-scroll-nav="0">Home <i class=""></i></a> </li>
        <li><a href="#" class="smooth" data-scroll-nav="1">About Us</a></li>
        <li><a href="#" class="smooth" data-scroll-nav="3">Whitepaper</a></li>
        <li><a href="#" class="smooth" data-scroll-nav="2">ICO</a></li>
        <li><a href="#" class="smooth" data-scroll-nav="4">Lending Program</a></li>
        <li><a href="#" class="smooth" data-scroll-nav="5">Affiliate</a></li>
        <li><a href="#" class="smooth" data-scroll-nav="6">Roadmap</a></li>
        <li><a href="#" class="smooth" data-scroll-nav="7">Contact Us</a></li>
        <?php if (!isset($authUser)) echo '<li><a href="javascript:void();" id="login_btn"> Login </a></li>';
                                    else echo '<li class="login-btn"><a href="'.$this->Url->build(["controller"=>"front",'action'=>'dashboard']).'">Dashboard </a></li>';?>
        <?php if (!isset($authUser)) { echo '<li><a href="'.$this->Url->build(["controller"=>"front",'action'=>'register']).'" > Register </a></li>'; }
                                   ?>
      </ul>
    </nav>
    <span class="side-over"></span>
    <div class="sidenav bg-white text-center"> <a href="#" class="side-close"><i class="fa fa-times"></i></a>
      <div class="logo-img"> <a href="home.html"><img src="<?=$this->request->webroot ?>assets/hedge/images/b-logo.png" alt="Hedge Connect" class="img-responsive"></a> </div>
      <strong>Welcome to Hedge Connect</strong>
      <form action="#" target="_blank" class="side-form bg-light">
        <fieldset>
          <button type="submit" class="btn text-center">Join us</button>
        </fieldset>
      </form>
      <div class="sideprice">
        <script type="text/javascript" src="https://files.coinmarketcap.com/static/widget/currency.js"></script>
        <div class="coinmarketcap-currency-widget" data-currency="bitcoin" data-base="USD" data-secondary="" data-ticker="true" data-rank="true" data-marketcap="true" data-volume="true" data-stats="USD" data-statsticker="false"></div>
      </div>
      <ul class="list-unstyled text-right socail-network">
        <li><a href="https://twitter.com/Hedge Connect" target="_blank"><i class="fa fa-twitter"></i></a></li>
        <li><a href="https://www.instagram.com/Hedge Connect/" target="_blank"><i class="fa fa-instagram"></i></a></li>
        <li><a href="https://t.me/Hedge Connect" target="_blank"><i class="fa fa-telegram"></i></a></li>
      </ul>
    </div>
  </header>
  
  
	<section class="thanks-sec">
		<div class="thanks-msg text-center" style="color:#000;">
			<h1 style="color:#000;"><b>THANK YOU</b></h1>
			<div class="m-top-30">
				<img src="<?=$this->request->webroot ?>assets/hedge/images/checked.png" alt="image">
			</div>
			<div class="m-top-30 text-center">
				<span class="th-text">
						Thanks you for reaching out. One of our team members will contact you shortly to schedule your first consultation.
				</span>
			</div>
		</div>
	</section>
</body>	
<span id="back-top" class="text-center md-round fa fa-caret-up"></span>
<footer id="footer" class="dark-bg pad-top-xs pad-bottom-xs">
  <div class="container">
    <div class="row">
      <div class="col-xs-12 col-sm-6">
        <p>2017 Â© Hedge Connect. All rights reserved.</p>
      </div>
      <div class="col-xs-12 col-sm-6">
        <ul class="list-unstyled text-right socail-network">
          <li><a href="https://www.facebook.com/pg/Hedge-Connect-141513009882491/" target="_blank"><i class="fa fa-facebook-official" aria-hidden="true"></i></a> </li>
          <li><a href="https://twitter.com/hedgeconnect1" target="_blank"><i class="fa fa-twitter"></i></a></li>
          <li><a href="https://www.instagram.com/hedgeconnect/" target="_blank"><i class="fa fa-instagram"></i></a></li>
          <li><a href="https://plus.google.com/b/105236377288592422884/105236377288592422884" target="_blank"><i class="fa fa-google-plus" aria-hidden="true"></i></a> </li>
          <h5><a href="<?=$this->request->webroot ?>assets/hedge/downloads/Privacy_Policy.pdf" target="_blank">Privacy Policy</a> <br>
            <a href="<?=$this->request->webroot ?>assets/hedge/downloads/Terms_and_Conditions.pdf" target="_blank">Terms and Conditions</a> </h5>
        </ul>
      </div>
    </div>
  </div>
</footer>
</html>
