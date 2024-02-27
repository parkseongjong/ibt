<!DOCTYPE html>
<!-- saved from url=(0057)https://xbootstrap.com/demo/crypto/cryptoapp/index-6.html -->
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">
<head>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
<meta name="description" content="Coin IBT is a fast and secure platform that makes it easy to buy, sell, and store cryptocurrency like Bitcoin, Ethereum, and more.">
<meta name="keywords" content="crypto, Bootstrap, bitcoins, ethereum, dogecoin, iota, ripple, siacoin, exchange, trading platform, crypto trading">
<meta name="author" content="">
<link rel="icon" href="<?php echo $this->request->webroot?>assets/images/favicon2.ico" type="image/x-icon" />
<title>COIN IBT Exchange</title>
<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/font-awesome.css" />
<!-- <link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/animate+animo.css" /> -->
<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/csspinner.min.css" />
<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/app.css?id=<?php echo time(); ?>" />
<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/new.css" />
<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/new_customer.css" />
<script src="<?php echo $this->request->webroot ?>assets/html/js/jquery.js"></script>
<script src="<?php echo $this->request->webroot ?>assets/html/js/bootstrap.js"></script>
<style>
	.wrapper {
	  background-color: #fff;
	  height: initial;
	}
	.content-container {
	  width: 100%;
	  height: 100%;
	  color: #000;
	}
	.content-container .content-inner {
	  height: 100%;
	  padding: 180px 0;
	  text-align: center;
	}
	.content-container .content-inner .content-title {
	  font-size: 40px;
	  line-height: 1.5;
	  font-weight: 700;
	  margin-bottom: 40px;
	}
	.content-container .content-inner .content-subtitle {
	  font-size: 24px;
	  color: #535353;
	  line-height: 1.5;
	  margin-bottom: 120px;
	}
	.content-container .content-inner .shortcut-button {
	  width: 350px;
	  padding: 15px 0;
	  background-color: #7d7d7d;
	  color: #fff;
	  border: none;
	  text-align: center;
	  font-size: 22px;
	  cursor: pointer;
	}

	@media (max-width: 990px) {
	  .wrapper {
	  }
	  .content-container {
	  }
	  .content-container .content-inner {
	  }
	  .content-container .content-inner .content-title {
		font-size: 24px;
	  }
	  .content-container .content-inner .content-subtitle {
		font-size: 16px;
		padding: 0 5px;
	  }
	  .content-container .content-inner .shortcut-button {
		width: 200px;
		font-size: 16px;
	  }
	}
</style>
</head>
<body class="animate-border divide">
	<div class="content-container container">
		<div class="content-inner">
			<div class="content-title"><?=__("Locked due to consecutive input failures");?></div>
			<div class="content-subtitle">
				<?=__("Certification Info");?>
			</div>
			<button class="shortcut-button" onClick="onClickGoAuth()"><?=__("Identity verification");?></button>
		</div>
	</div>
	<div class="header2" style="display:none;"></div>
	<?php echo $this->Form->create('',array('id'=>'signup', 'name'=>'signup','method'=>'post','url'=>'/front2/Users/selfCertification'));?>
		<input type="hidden" id="phone" name="phone" value="" >
		<input type="hidden" id="user_name" name="user_name" value="">
		<input type="hidden" id="is_auth_ok" name="is_auth_ok" value="N">
		<input type="hidden" id="is_auth" name="is_auth" value="">
	<?php echo $this->Form->end();?>
	<form method="post" name="form_auth" id="auth_form"><!--  onsubmit="return false;" -->
		<input type="hidden" name="ordr_idxx" id="auth_ordr_idxx" class="frminput" value="" />
		<input type="hidden" name="req_tx" value="cert" /><!-- 요청종류 -->
		<input type="hidden" name="cert_method" value="01" /><!-- 요청구분 -->
		<input type="hidden" name="web_siteid"   value="<?= $g_conf_web_siteid ?>" /><!-- 웹사이트아이디 : ../cfg/cert_conf.php 파일에서 설정해주세요 -->
		<input type="hidden" name="site_cd" value="<?= $g_conf_site_cd ?>" /><!-- 사이트코드 : ../cfg/cert_conf.php 파일에서 설정해주세요 -->
		<input type="hidden" name="Ret_URL" value="<?= $g_conf_Ret_URL ?>" /><!-- Ret_URL : ../cfg/cert_conf.php 파일에서 설정해주세요 -->
		<input type="hidden" name="cert_otp_use" value="Y" /><!-- cert_otp_use 필수 ( 메뉴얼 참고) - Y : 실명 확인 + OTP 점유 확인 , N : 실명 확인 only -->
		<input type="hidden" name="cert_enc_use" value="Y" /><!-- cert_enc_use 필수 (고정값 : 메뉴얼 참고) -->
		<input type="hidden" name="cert_enc_use_ext" value="Y" />      <!-- 리턴 암호화 고도화 -->
		<input type="hidden" name="res_cd" value="" />
		<input type="hidden" name="res_msg" value="" />
		<input type="hidden" name="veri_up_hash" value="" id="veri_up_hash" /><!-- up_hash 검증 을 위한 필드 -->
		<input type="hidden" name="cert_able_yn" value="Y" /><!-- 본인확인 input 비활성화 -->
		<input type="hidden" name="web_siteid_hashYN" value="Y" /><!-- web_siteid 을 위한 필드 -->
		<input type="hidden" name="param_opt_1"  value="" /> <!-- 가맹점 사용 필드 (인증완료시 리턴)-->
		<input type="hidden" name="param_opt_2"  value="" />
		<input type="hidden" name="param_opt_3"  value="" />
	</form>
	<iframe id="kcp_cert" name="kcp_cert" width="100%" sandbox="allow-same-origin allow-scripts allow-popups allow-forms" height="900" frameborder="0" scrolling="yes" style="display: none;"></iframe>
	<script>
		$(document).ready(function(){
			init_orderid();
		});
		function onClickGoAuth() {
			const auth_form = $("#auth_form");
			if ($('#auth_ordr_idxx').val() === '') {
				return false;
			} else {
				if( navigator.userAgent.indexOf("Android") > - 1 || navigator.userAgent.indexOf("iPhone") > - 1 || navigator.userAgent.indexOf("iPad") > - 1 ) {
					auth_form.attr('target', 'kcp_cert');
					$("#kcp_cert").css('display', 'block');
					$(".container").css('display', 'none');
				} else {
					let width = 450;
					let height = 500;
					let leftpos = screen.width / 2 - (width / 2);
					let toppos = screen.height / 2 - (height / 2);
					let winopts = "width=" + width + ", height=" + height + ", toolbar=no,status=no,statusbar=no,menubar=no,scrollbars=no,resizable=no";
					let position = ",left=" + leftpos + ", top=" + toppos;
					let AUTH_POP = window.open('', 'auth_popup', winopts + position);
					auth_form.attr('target', 'auth_popup');
				}
				auth_form.attr('method', 'post');
				auth_form.attr('action', '/webroot/KcpcertCoinibt/SMART_ENC/smartcert_proc_req.php');
				auth_form.submit();
				return true;
			}
		}
		function auth_type_check() {
		}
		function init_orderid() // 본인인증 초기셋팅
		{
			const today = new Date();
			const year  = today.getFullYear();
			let month = today.getMonth()+ 1;
			const date  = today.getDate();
			const time  = today.getTime();

			if (parseInt(month) < 10)
			{
				month = "0" + month;
			}
			const vOrderID = year + "" + month + "" + date + "" + time;
			$('#auth_ordr_idxx').val(vOrderID);
		}
		function certification(){
			$('#signup').submit();
		}
	</script>
</body>
</html>