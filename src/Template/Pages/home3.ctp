<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
       <title><?php echo __("COIN IBT Exchange"); ?></title>
      <meta name="description" content="Coin IBT is a fast and secure platform that makes it easy to buy, sell, and store cryptocurrency like Bitcoin, Ethereum, and more." />
      <meta name="keywords" content="crypto, Bootstrap, bitcoins, ethereum, dogecoin, iota, ripple, siacoin, exchange, trading platform, crypto trading" />
      <!-- Bootstrap -->
	  <link href="<?php echo $this->request->webroot ?>wb/img/favicon2.ico" rel="icon" />


      <link href="<?php echo $this->request->webroot ?>wb/css/style.css" rel="stylesheet"/>
      <link href="<?php echo $this->request->webroot ?>wb/css/font-awesome.min.css" rel="stylesheet" />

       <script src="<?php echo $this->request->webroot ?>assets/html/js/jquery.js"></script>
       <script src="<?php echo $this->request->webroot ?>assets/html/js/bootstrap.js"></script>
       <script src="<?php echo $this->request->webroot ?>assets/html/js/modernizr.js"></script>
       <script src="<?php echo $this->request->webroot ?>assets/html/js/fastclick.js"></script>
       <!-- Bootstrap -->

       <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100;300;400;500;700&display=swap" rel="stylesheet">
       <link href="<?php echo $this->request->webroot ?>wb/css/index.css" rel="stylesheet"/>
       <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

   </head>
   <body class="animate-border divide">
   <section class="wrapper">

       <?php echo $this->element('Front2/nav'); ?>


   </section>
   <script type="text/javascript">
       window._mfq = window._mfq || [];
       (function() {
           var mf = document.createElement("script");
           mf.type = "text/javascript"; mf.async = true;
           mf.src = "//cdn.mouseflow.com/projects/560dedc2-b6f7-4508-ab97-71b7d3b16a00.js";
           document.getElementsByTagName("head")[0].appendChild(mf);
       })();
   </script>

   <link rel="stylesheet" href="https://swiperjs.com/package/swiper-bundle.min.css">

   <div id="main_banner">
       <div class="container" style="position: relative;">

           <div class="mm_100">
               <div class="main_title_black">
                   <?php echo __("SAFE") ?> <br /><?php echo __("COIN") ?> <br /><?php echo __("INCUBATOR") ?>
               </div>
               <div class="main_title desc">
                   <?php echo __("Safe Blockchain Exchange"); ?>
               </div>
               <div class="sub_title desc">
                   <?php echo __("Can be used anywhere in the world") ?><br /><?php echo __("Blockchain Exchange Service"); ?>
               </div>
           </div>

           <div class="pppppp" style="position:absolute; width:664px; top:80px; right:0px;">
               <div class="swiper-container">
                   <div class="swiper-wrapper">
                       <div class="swiper-slide">
                           <div>
                               <ul style="list-style-type:none; overflow:hidden;">
                                   <li style="float:left; padding-left:20px; width:200px; height: 239px; background:#fff">
                                       <h2 style="font-size: 20px; font-weight: 900; margin-top: 34px;"><?php echo __("COIN") ?> IBT</h2>
                                       <p style="font-size: 20px; font-weight: bold; line-height: 1.5"><?php echo __("Easy, Fast and Secure") ?><br /><?php echo __("Global Exchange Platform") ?></p>
                                   </li>
                                   <li style="float:right"><img src="/wb/imgs/main_banner1.jpg" style="width:444px; height: 239px;" /></li>
                               </ul>
                           </div>
                           <div class="swiper-button-next" style="width:100px; height:100px; background:#fff; color:black; top:95px; right:32px; opacity:0.6"></div>
                       </div>
                       <div class="swiper-slide">
                           <div>
                               <ul style="list-style-type:none; overflow:hidden;">
                                   <li style="float:left; padding-left:20px; width:200px; height: 239px; background:#fff">
                                       <h2 style="font-size: 20px; font-weight: 900; margin-top: 34px;"><?php echo __("COIN") ?> IBT</h2>
                                       <p style="font-size: 20px; font-weight: bold; line-height: 1.5"><?php echo __("Cryptocurrency") ?><br /><?php echo __("safety") ?><br /><?php echo __("Trade") ?></p>
                                   </li>
                                   <li style="float:right"><img src="/wb/imgs/main_banner2.jpg" style="width:444px; height: 239px;" /></li>
                               </ul>
                           </div>
                           <div class="swiper-button-prev" style="width:100px; height:100px; background:#fff; color:black; top:95px; left:530px; opacity:0.6"></div>
                       </div>
                   </div>
               </div>
           </div>

       </div>
   </div>

   <script src="https://swiperjs.com/package/swiper-bundle.min.js"></script>
   <script>
       var swiper = new Swiper('.swiper-container', {
           navigation: {
               nextEl: '.swiper-button-next',
               prevEl: '.swiper-button-prev',
           },
       });
   </script>

   <div id="notice_block" style="display: none">
       <div class="container">
           <ul>
               <li class="title">공지사항</li>
               <li class="symbol">▲<br />▼</li>
               <li class="data">CTC 거래소가 2020년 7월 20일 가오픈하였습니다.</li>
               <li class="data_new">New</li>
               <li class="title" style="margin-left:100px">보도자료</li>
               <li class="symbol">▲<br />▼</li>
               <li class="data">CTC거래소는 블록체인 거래소의 신흥강자로써 회원수 60만명...</li>
           </ul>
       </div>
   </div>
<?php

$ctcKrwPrice = $this->CurrentPrice->getCurrentPrice(21,20);
//$ctcKrwPrice  = number_format($ctcKrwPrice,4); 

$tp3KrwPrice = $this->CurrentPrice->getCurrentPrice(17,20);
//$tp3KrwPrice  = number_format($tp3KrwPrice,4); 

$btcKrwPrice = $this->CurrentPrice->getCurrentPrice(1,20);
//$btcKrwPrice  = number_format($btcKrwPrice,4);

$ethKrwPrice = $this->CurrentPrice->getCurrentPrice(18,20);
//$ethKrwPrice  = number_format($ethKrwPrice,4); 

$xrpKrwPrice = $this->CurrentPrice->getCurrentPrice(23,20);
//$xrpKrwPrice  = number_format($xrpKrwPrice,4); 


 ?>


   <div id="dashboard">
       <div class="container">
           <ul>
               <li>
                  <!-- <div class="percent"><span class="updown">▲</span> 1.40 %</div>-->
                   <div>
                       <span class="token">CTC</span><span class="base">/KRW</span>
                   </div>
                   <div>
                       <span class="amount2"><?php echo $ctcKrwPrice; ?> </span><span class="unit">KRW</span>
                   </div>
               </li>
               <li>
                   <!--<div class="percent"><span class="updown">▲</span> 0.57 %</div>-->
                   <div>
                       <span class="token">TP3</span><span class="base">/KRW</span>
                   </div>
                   <div>
                       <span class="amount2"><?php echo $tp3KrwPrice; ?></span><span class="unit">KRW</span>
                   </div>
               </li>
               <li>
                  <!-- <div class="percent"><span class="updown">▲</span> 0.44 %</div>-->
                   <div>
                       <span class="token">BTC</span><span class="base">/KRW</span>
                   </div>
                   <div>
                       <span class="amount2"><?php echo $btcKrwPrice; ?></span><span class="unit">KRW</span>
                   </div>
               </li>
               <li>
                  <!-- <div class="percent2"><span class="updown">▼</span> 0.44 %</div>-->
                   <div>
                       <span class="token">ETH</span><span class="base">/KRW</span>
                   </div>
                   <div>
                       <span class="amount2"><?php echo $ethKrwPrice; ?></span><span class="unit">KRW</span>
                   </div>
               </li>
               <li>
                  <!-- <div class="percent2"><span class="updown">▼</span> 0.03 %</div>-->
                   <div>
                       <span class="token">XRP</span><span class="base">/USDT</span>
                   </div>
                   <div>
                       <span class="amount2"><?php echo $xrpKrwPrice; ?></span><span class="unit">USDT</span>
                   </div>
               </li>
           </ul>
       </div>
   </div>



   <div id="safety_join2">
       <div class="container">
           <div class="row">
               <div class="col phone_bg">
               </div>
               <div id="safety_join2_text">
                
                               <table width="100%" cellpadding="0" cellspacing="0" >
                               	<tr>
                               		<td align="left" class="atitle_1" height="25"><?php echo __("coin incubator") ?>?</td>
                             
                               	</tr>
                               	<tr>
                               		<td align="left" class="atitle_2" height="35">ABOUT Coin Incubator</td>                               		
                               	</tr>
                               	<tr>
                               		<td height="20"></td>
                               	</tr>
                             	<tr >
                               		<td align="left" class="atitle_3"><?=__('coin_incubator_text') ?></td>                               		
                               	</tr>                               	
                               </table>
          
                  
             </div>
           </div>
       </div>
   </div>
       
       
       

   <div id="safety_join">
       <div class="container">
           <div class="row">
               <div class="col phone_bg">
               </div>
               <div class="col">
                   <div class="title_1"><?php echo __("Be with our safe service") ?>!</div>
                   <div class="title_2"><?php echo __("Simple membership registration") ?></div>
                   <ul>
                       <li>1</li>
                       <li><?php echo __("Fill out the online application") ?>.</li>
                   </ul>
                   <!--<ul>
                       <li>2</li>
                       <li><?php echo __("Please wait until your account is approved") ?>.</li>
                   </ul>-->
                   <ul>
                       <li>2</li>
                       <li><?php echo __("Digital assets Deposit") ?>.</li>
                   </ul>
                   <ul>
                       <li>3</li>
                       <li><?php echo __("Start trading now") ?>!</li>
                   </ul>
               </div>
           </div>
       </div>
   </div>

   <div id="specific">
       <div class="container">
           <div class="title_1"><?php echo __("We pursue secure transactions with the world's highest level of strong security") ?></div>
           <div class="title_2"> <?php echo __("Secure Incubator") ?><br /><span style="font-size:25px; color:#5797f7">[ <?php echo __("Inquiring for listing") ?> ]</span></div>

           <ul>
               <li>
                   <div class="imgs">
                       <img src="/wb/imgs/rebait.png" />
                   </div>
                   <div class="sub_title_1">
                      <?php echo __("Transaction fee rebate") ?>
                   </div>
                   <div class="sub_title_2">
                       <?php echo __("Maker fee") ?> 0.25 %<br />
                      <!-- <?php echo __("I will rebate") ?>.-->
                   </div>
               </li>
               <li>
                   <div class="imgs" style="width:70px;">
                       <img src="/wb/imgs/guard.jpg" />
                   </div>
                   <div class="sub_title_1">
                       <?php echo __("Cold storage security") ?>
                   </div>
                   <div class="sub_title_2">
                       <?php echo __("Security of digital assets") ?><br />
                       <?php echo __("Peace of mind is guaranteed") ?>.
                   </div>
               </li>
               <li>
                   <div class="imgs" style="width:70px;">
                       <img src="/wb/imgs/service.png" />
                   </div>
                   <div class="sub_title_1">
                       <?php echo __("Real-time support") ?>
                   </div>
                   <div class="sub_title_2">
                       <?php echo __("To support inquiries and requirements") ?><br />
                       <?php echo __("A dedicated counselor is on standby") ?>.
                   </div>
               </li>
               <li>
                   <div class="imgs" style="width:90px;">
                       <img src="/wb/imgs/business.jpg" />
                   </div>
                   <div class="sub_title_1">
                       <?php echo __("Business account") ?>
                   </div>
                   <div class="sub_title_2">
                       <?php echo __("For digital asset trading") ?><br />
                       <?php echo __("Provides excellent support") ?>.
                   </div>
               </li>
           </ul>
       </div>
   </div>

   <div id="ctc_wallet">
       <div class="container">
           <div class="row">
           <div class="ctc_wallet_left">
               <div class="title_1">
                   CTC  <?php echo __("wallet") ?><span> APP DOWN</span>
               </div>
               <div class="desc_1">
                   <?php echo __("You can easily exchange coins") ?><br />
                   <?php echo __("Download Secure Wallet CTC Now") ?>.
               </div>
               <div class="imgs">
                   <a target='_blank' href="https://play.google.com/store/apps/details?id=com.cybertronchain.wallet2"><img src="/wb/imgs/playstore.png" /></a>
                   <a target='_blank' href="https://apps.apple.com/us/app/ctc-wallet/id1527694686"><img src="/wb/imgs/appstore.png" /></a>
               </div>
           </div>
		   <div class="ctc_wallet_left2">
             
                 <img src="/wb/imgs/phone_bg22.png" />
             
           </div>
           </div>
       </div>
   </div>

   <div id="customer_center">
       <div class="container">
           <ul>
               <li class="phone">
                   <div class="title"><?php echo __("Service center") ?> (<?php echo __("weekday") ?> 10:00 ~ 18:00)</div>
                   <div class="phone_no">1588-1644</div>
               </li>
               <li class="traninfo">
                   <div class="title_2"><?php echo __("Guidance on additional measures to prevent financial accidents") ?></div>
                   <div class="desc_2"><?php echo __("Guidance on additional measures to prevent financial accidents") ?></div>
               </li>
           </ul>
       </div>
   </div>
   <?php echo $this->element('Front2/footer'); ?>
   </body>
</html>