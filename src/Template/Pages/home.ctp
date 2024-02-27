<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">
   <head>
      <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
       <!-- <title><?php echo __("COIN IBT Exchange"); ?></title> -->
       <title><?php echo __("SMBIT Exchange"); ?></title>
      <meta name="description" content="SMBIT is a fast and secure platform that makes it easy to buy, sell, and store cryptocurrency like Bitcoin, Ethereum, and more." />
      <meta name="keywords" content="crypto, Bootstrap, bitcoins, ethereum, dogecoin, iota, ripple, siacoin, exchange, trading platform, crypto trading" />
      <!-- Bootstrap -->
	  <!-- <link href="<?php echo $this->request->webroot ?>wb/img/favicon2.ico" rel="icon" /> -->
	  <link href="<?php echo $this->request->webroot ?>images/favicon.ico" rel="icon" />


      <link href="<?php echo $this->request->webroot ?>wb/css/style.css" rel="stylesheet"/>
      <link href="<?php echo $this->request->webroot ?>wb/css/font-awesome.min.css" rel="stylesheet" />

       <script src="<?php echo $this->request->webroot ?>assets/html/js/jquery.js"></script>
       <script src="<?php echo $this->request->webroot ?>assets/html/js/bootstrap.js"></script>
       <script src="<?php echo $this->request->webroot ?>assets/html/js/modernizr.js"></script>
       <script src="<?php echo $this->request->webroot ?>assets/html/js/fastclick.js"></script>
       <!-- Bootstrap -->
       <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100;300;400;500;700&display=swap" rel="stylesheet">
       <link href="<?php echo $this->request->webroot ?>wb/css/index.css?ver=004" rel="stylesheet"/>
       <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	   <script src="<?php echo $this->request->webroot ?>js/front2/utilities/utility.js?id=<?php echo time(); ?>"></script>
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

   <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">

   <div id="main_banner">
       <div class="container" style="position: relative;">

           <div class="mm_100">
               <div class="main_title_black">
                   <!-- <?php echo __("SAFE") ?> <br /><?php echo __("COIN") ?> <br /><?php echo __("INCUBATOR") ?> -->
                   SMBIT
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
                                       <!-- <h2 style="font-size: 20px; font-weight: 900; margin-top: 34px;"><?php echo __("COIN") ?> IBT</h2> -->
                                       <h2 style="font-size: 20px; font-weight: 900; margin-top: 34px;">SMBIT</h2>
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
                                       <!-- <h2 style="font-size: 20px; font-weight: 900; margin-top: 34px;"><?php echo __("COIN") ?> IBT</h2> -->
                                       <h2 style="font-size: 20px; font-weight: 900; margin-top: 34px;">SMBIT</h2>
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

  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
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

<script>
	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}	
	function getPairCurrentPrice(){
		$.ajax({
			url : "<?php echo $this->Url->Build(['prefix'=>'front2','controller'=>'exchange','action'=>'getPairCurrentPrice',20]); ?>",
			type : 'GET',
			dataType : 'json',
			success : function(resp){
				$.each(resp,function(getKey,getValData){
					var getVal = getValData.price;
					var getPricePercent = getValData.price_percent;
					getPricePercent = parseFloat(getPricePercent).toFixed(2);
					getVal =  numberWithCommas(getVal);
					$("#"+getKey).html(getVal);
					var setPriceClass = (getPricePercent<0) ? "red" : "blue"; 
					var setPriceSign = (getPricePercent<0) ? "-" : "+"; 
					$("#"+getKey+"_percent").html(setPriceSign+""+Math.abs(getPricePercent)+"%").removeClass("red").removeClass("blue").addClass(setPriceClass);
				})
			}
		});
	} 
	getPairCurrentPrice();

</script>
<style>
span.blue{
	color :#0c45d5;
}
span.red{
	color :#d80000;
}
</style>
   <div id="dashboard">
       <div class="container">
           <ul>
               <li>
                  <!-- <div class="percent"><span class="updown">▲</span> 1.40 %</div>-->
                   <div>
                       <span class="token">CTC</span><span class="base">/KRW</span>
                   </div>
                   <div>
                       <span class="amount2" id="current_price_CTC_KRW">0</span><span class="unit">KRW</span>
                   </div>
				    <div>
                       <span class="blue" id="current_price_CTC_KRW_percent">0%</span>
                   </div>
               </li>
               <li>
                   <!--<div class="percent"><span class="updown">▲</span> 0.57 %</div>-->
                   <div>
                       <span class="token">TP3</span><span class="base">/KRW</span>
                   </div>
                   <div>
                       <span class="amount2" id="current_price_TP3_KRW">0</span><span class="unit">KRW</span>
                   </div>
				    <div>
                       <span class="blue" id="current_price_TP3_KRW_percent">0%</span>
                   </div>
               </li>
               <li>
                 <!-- <div class="percent"><span class="updown">▲</span> 0.44 %</div>-->
                   <div>
                       <span class="token">MC</span><span class="base">/KRW</span>
                   </div>
                   <div>
                       <span class="amount2" id="current_price_MC_KRW">0</span><span class="unit">KRW</span>
                   </div>
				   <div>
                       <span class="blue" id="current_price_MC_KRW_percent">0%</span>
                   </div>
				    
               </li>
               <li>
                  <!--<div class="percent2"><span class="updown">▼</span> 0.44 %</div>-->
                   <div>
                       <span class="token">ETH</span><span class="base">/KRW</span>
                   </div>
                   <div>
                       <span class="amount2" id="current_price_ETH_KRW">0</span><span class="unit">KRW</span>
                   </div>
				    <div>
                       <span class="blue" id="current_price_ETH_KRW_percent">0%</span>
                   </div>
               </li>
           </ul>
       </div>
   </div>


      
  <div  id="safety_join" class="incubator-wrap">
  <div class="incubator-inner">
    <div class="txt-01">
      ABOUT
      <em>
        SMBIT
      <!-- COIN -->
      </em>
      <!-- INCUBATOR -->
    </div>
    <div class="txt-02">
      <!-- <div class="title_1"><?=__("What is a Coin Incubator")?>?</div> -->
      <div class="title_1"><?=__("What is SMBIT")?>?</div>
      <p>
       <?=__("Coin_incubator_text")?>
      </p>
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
           <div class="title_2"> <?php echo __("Secure Incubator") ?><br /><a href="mailto:ieo@coinibt.com?subject=Inquiring for listing"><span style="font-size:25px; color:#5797f7">[  <?php echo __("Inquiring for listing") ?> ]</span></a></div>

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
   <!-- Reactivate after iOS approval -->
<!--
   <div id="ctc_wallet">
       <div class="container">
           <div class="row">
           <div class="ctc_wallet_left">
               <div class="title_1">
                   CTC  <?php echo __("wallet") ?><span> APP</span>
               </div>
               <div class="desc_1">
                   <?php echo __("You can easily exchange coins") ?><br />
                   <?php echo __("Download Secure Wallet CTC Now") ?>.
               </div>
               <div class="imgs">
                   <a id="gApps" target='_blank' href="https://play.google.com/store/apps/details?id=com.cybertronchain.wallet2"><img src="/wb/imgs/playstore.png" /></a>
                   <a target='_blank' href="https://apps.apple.com/us/app/ctc-wallet/id1527694686"><img src="/wb/imgs/appstore.png" /></a>
               </div>
           </div>
		   <div class="ctc_wallet_left2">
             
                 <img src="/wb/imgs/phone_bg22.png" />
             
           </div>
           </div>
       </div>
   </div>-->
    <div class="other-wrap">
      <div class="other-inner">
        <!-- <div class="kakao-talk-wrap"> -->
          <!-- <small><?= __('Customer Service') ?> (<?= __('Weekdays') ?> 10:00 ~ 18:00)</small> -->
          <!--<button type="button"></button>--><!-- Reactivate after iOS approval -->
        <!-- </div> -->
        <a href="#!" class="banner-coin">
          <strong><?= __('To get coins,') ?></strong>
          <!-- <em><?= __('Join COIN IBT!') ?></em> -->
          <em><?= __('Join SMBIT!') ?></em>
        </a>
      </div>
    </div>
   <div id="customer_center">
       <div class="container">
           <ul>
<!--               <li class="phone">-->
<!--                   <div class="title">--><?php //echo __("Service center") ?><!-- (--><?php //echo __("weekday") ?><!-- 10:00 ~ 18:00)</div>-->
<!--                   <div class="phone_no">1588-1644</div>-->
<!--               </li>-->
               <li class="traninfo">
                   <div class="title_2"><?php echo __("Guidance on additional measures to prevent financial accidents") ?></div>
                   <div class="desc_2"><?php echo __("Guidance on additional measures to prevent financial accidents") ?></div>
               </li>
           </ul>
       </div>
   </div>
   <?php echo $this->element('Front2/footer'); ?>


   <!-- POPUP -->
   <!-- 2021-09-17 bwpark -->
   <div class="popup-background"></div>
    <div class="popup-container">
        <div class="popup-container-inner">
            <div class="popup-container-inner-2">
                <div class="popup-wrap">
                    <div class="popup-header">
                        <div class="popup-close" id="popup-close-button">X</div>
                    </div>

                    <div class="popup-body">
                        <div class="popup-title">원화마켓(KRW) 일시 중단 안내</div>
                        <div class="divider"></div>
                        <div class="popup-body-content">
                            <div class="content-1">
                                <div>개정된 「특정 금융거래정보의 보고 및 이용 등에 관한 법률」에 따라 코인아이비티를 포함한 모든 가상자산</div>
                                <div>사업자는 오는 9월24일까지 금융위원회에 일정한 법률 요건을 갖추어 신고 접수를 마쳐야 합니다.</div><br />
                                <div>이에 맞춰 저희 코인아이비티는 ‘정보보호 및 개인정보보호 관리체계 인증(ISMS-P)’ 심사접수 후 심사대기</div>
                                <div>중에 있으며, 심사기관 사정 상 일정이 지연됨에 따라 9월24일까지의 ISMS-P 인증이 늦어지게 되어,</div>
                                <div>부득이하게 현재 서비스 중인 원화마켓을 인증심사완료일까지 일시 중단합니다.</div><br />
                                <br />
                                * 2021년 9월 24일까지 입금 가능, 2021년 9월 25일부터 현금 입금 불가<br />
                                * 2021년 9월 25일 이후에도 현금 출금 가능 <br />
                                * 2021년 9월 16일부터 BTC, BNB 거래마켓은 거래지원 중단합니다.<br />
                                &nbsp;&nbsp;(BTC, BNB 거래마켓 거래소에서 삭제처리 완료)<br />
                                * 거래마켓에서 타거래소로 (USDT 이전 출금가능. 단, 출금조건은 동일)<br />
                                <br />
                                <div>(★ 코인아이비티는 아직 안정되지 않은 신규코인의 안전한 거래를 위한 인큐베이터이며 대부분 고객</div>
                                <div>보유분의 코인은 불법 다단계 피해자분들에게 무료 지급한 코인으로 폭락과 폭등의 방지를 하며 안전한</div>
                                <div>거래속에서 거래가 이루어질 수 있게 관리하고 안전한 거래를 권장합니다.)</div><br />
                                <br />
                                저희 코인아이비티는 고객님들의 자산을 언제나 안전하고, 온전하게 보관/관리하고 있으며,
                                원화마켓 일시 중단에 따른 고객님들의 원화 출금 요청에 불편함 없이 지원할 예정이오니 안심하시기 바랍니다. <br />
                                <div>또한 USDT 마켓거래는 국제거래소 상장가 기준을 준수하며</div>
                                <div>타거래소 실명등록 주소 계정으로 USDT 입·출금은 가능함을 안내합니다.</div> <br />
                                <br />
                                또한 최대한 빠른 시일 내에 ISMS-P 인증을 완료하여 가상자산사업자 신고를 통해 원화마켓 서비스를 재개할 수 있도록 최선을 다하겠습니다. <br />
                                <div>코인아이비티는 앞으로도 정부 및 국제자금세탁방지기구의 정책을 적극 반영하여,</div>
                                <div>고객 자산 보호를 위해 지속적인 노력을 기울일 것입니다.</div><br />
                                <br />
                                감사합니다.<br />
                            </div>
                            <div class="writer">
                                CTO 한백희 배상
                            </div>
                        </div>
                    </div>

                    <div class="popup-footer">
                        <!-- 다시보지 않기 -->
                        <input type="checkbox" id="popup-never-today" class="never-today-checkbox">
                        <label for="popup-never-today" class="never-today-label">오늘 하루 보지 않기</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        /**
         * 처음엔 display none
         * localStorage 확인하고 never-today 값 없으면 띄우기
         * Close 클릭 이벤트에서 오늘 하루 보지 않기 체크박스 확인하기
         */
        document.addEventListener('DOMContentLoaded', function() {
            const popupNeverToday = localStorage.getItem('popup-never-today');
            if (popupNeverToday === undefined) return;
            const nowDate = Date.now();
            const diff = Number(nowDate) - Number(popupNeverToday);
            // millisecond 때문에 1000 곱하기
            const oneDay = 24 * 60 * 60 * 1000;
            // 하루가 안지났음.
            if (diff < oneDay) return;

            // document.querySelector('.popup-container').classList.add('show');
        })
        document.querySelector('.popup-container #popup-close-button').addEventListener('click', function() {
            const checkboxPopupNeverToday = document.querySelector('.popup-container #popup-never-today');
            const isCheckPopupNeverToday = checkboxPopupNeverToday.checked;
            if (isCheckPopupNeverToday) {
                localStorage.setItem('popup-never-today', Date.now());
            }

            document.querySelector('.popup-container').classList.remove('show');
        });
    </script>
    <style>
        html {
            position: relative;
        }
        .popup-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 100;
            height: 100%;
            display: none;
        }
        .popup-container.show {
            display: block;
        }
        .popup-container .popup-container-inner {
            position: relative;
            width: 100%;
            height: 100%;
        }
        .popup-container .popup-container-inner .popup-container-inner-2 {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            box-shadow: 1px 1px 14px rgb(0 0 0 / 50%);
            max-width: 700px;
        }
        .popup-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .popup-container .popup-wrap {}
        .popup-container .popup-wrap .popup-header {
            padding: 15px 25px;
            font-size: 28px;
            font-weight: 700;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 5px;
        }
        .popup-container .popup-wrap .popup-header .popup-close {
            cursor: pointer;
        }
        .popup-container .popup-wrap .popup-body .popup-title {
            font-size: 18px;
            font-weight: 700;
            color: #240978;
            text-align: center;
        }
        .popup-container .popup-wrap .popup-body .divider {
            margin: 30px 139px 37px;
            height: 2px;
            width: 410px;
            background-color: #000;
        }
        .popup-container .popup-wrap .popup-body .popup-body-content {
            padding: 0 25px 52px;
        }
        .popup-container .popup-wrap .popup-body .popup-body-content .content-1 {
            font-size: 14px;
        }
				
        .popup-container .popup-wrap .popup-body .popup-body-content .writer {
            font-size: 17px;
            font-weight: 700;
            text-align: right;
            padding-right: 70px;
        }
        .popup-container .popup-wrap .popup-footer {
            padding: 15px 40px;
            background-color: #240978;
            color: #fff;
            display: flex;
            align-items: center;
        }
        .popup-container .popup-wrap .popup-footer .never-today-checkbox {
            margin-right: 10px;
        }
        .popup-container .popup-wrap .popup-footer .never-today-label {
            cursor: pointer;
        }

        @media screen and (max-width: 800px) {
            .popup-container {
            }
            .popup-container.show {
						}
            .popup-container .popup-container-inner {
							height: 100%;
							display: flex;
							justify-content: center;
							align-items: center;
							height: 100vh;
            }
            .popup-container .popup-container-inner .popup-container-inner-2 {
								position: relative;
								max-width: 360px;
								left: 0;
								top: 0;
								transform: none;
            }
            .popup-container .popup-wrap {
                width: 100%;
            }
            .popup-container .popup-wrap .popup-header {}
            .popup-container .popup-wrap .popup-header .popup-close {}
            .popup-container .popup-wrap .popup-body .popup-title {}
            .popup-container .popup-wrap .popup-body .divider {
                width: 50%;
                margin: 30px auto;
            }
            .popup-container .popup-wrap .popup-body .popup-body-content {
                padding: 0 10px 30px;
                max-height: 300px;
                overflow-y: auto;
            }
            .popup-container .popup-wrap .popup-body .popup-body-content .content-1 {
                font-size: 12px;
                line-height: 1.9;
            }
            .popup-body .popup-body-content .content-1 div {
                display: inline;
            }
            .popup-container .popup-wrap .popup-body .popup-body-content .writer {}
            .popup-container .popup-wrap .popup-footer {}
            .popup-container .popup-wrap .popup-footer .never-today-checkbox {}
            .popup-container .popup-wrap .popup-footer .never-today-label {}
        }
    </style>
   <!-- /POPUP -->
   </body>
</html>

<script>
    function detectMob() {
        const toMatch = [
            /Android/i,
            /webOS/i,
            /iPhone/i,
            /iPad/i,
            /iPod/i,
            /BlackBerry/i,
            /Windows Phone/i
        ];

        return toMatch.some((toMatchItem) => {
            return navigator.userAgent.match(toMatchItem);
        });
    }

    $(document).ready(function(){

		getPairCurrentPrice();
		setInterval(function(){ getPairCurrentPrice(); },60000);
       // setInterval(function(){ getPairCurrentPrice1(); },2000);
        if(detectMob()){
            $("#gApps").prop("href", "market://details?id=com.cybertronchain.wallet2");
        } else {
            $("#gApps").prop("href", "https://play.google.com/store/apps/details?id=com.cybertronchain.wallet2");
        }
    });


</script>