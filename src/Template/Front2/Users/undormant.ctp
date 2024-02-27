<?php echo $this->Form->create('',array('id'=>'undormant','method'=>'post','name'=>'signup'));?>
	<input type="hidden" id="user_id" name="user_id" value="<?=$user_id;?>">
	<input type="hidden" id="phone" name="phone" value="" >
	<input type="hidden" id="user_name" name="user_name" value="">
<?php echo $this->Form->end();?>
<div class="content-container container">
	<div class="content-inner">
		<div class="content-top-area">
			<div class="content-top-title"><?=__("The account is dormant");?></div>
			<div class="content-top-subtitle"><?=__("If you unlock an account that has been dormant for a year because you haven't logged in, You can use it again")?></div>
		</div>
		<div class="content-bottom-area">
			<div class="content-bottom-title"> <?=__("Release dormant account");?> </div>
			<div class="content-bottom-subtitle"> <?=__("Release the dormant state");?> </div>
			<div class="content-bottom-table-wrap">
				<div class="content-bottom-table">
					<div class="bottom-table-left"> <?=__("Dormant Release Guide");?> </div>
					<div class="bottom-table-right"> <?=__("If you release the dormant state, you can use it normally again, and you need to log again in after releasing it")?> </div>
				</div>
			</div>
			<?= $this->Flash->render(); ?>
			<div class="content-bottom-buttons">
				<button type="button" class="release-button" onClick="onClickGoAuth()"><?=__("Release dormant account");?></button>
				<button type="button" class="cancel-button" onClick="onClickCancelButton()"><?=__("Cancel");?></button>
			</div>
		</div>
	</div>
</div>
<div class="header2" style="display:none;"></div>
<iframe id="kcp_cert" name="kcp_cert" width="100%" sandbox="allow-same-origin allow-scripts allow-popups allow-forms" height="900" frameborder="0" scrolling="yes" style="display: none;"></iframe>
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
<script>
	$(document).ready(function(){
		init_orderid();
	});
	function onClickCancelButton() {
		if(confirm('휴면 상태 해제를 취소하시겠습니까?')){
			location.href = "/front2/users/login";
		}
		return;
	}
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
		if($('#user_id').val() == ''){
			if(confirm('세션이 만료되었습니다. 처음부터 다시 시작해주세요')){
			}
			location.href = "/front2/users/login";
		}
		$('#undormant').submit();
	}
</script>

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
  padding: 100px 0;
}
.content-container .content-inner .content-top-area {
  text-align: center;
  padding-bottom: 50px;
}
.content-container .content-inner .content-top-area .content-top-title {
  font-size: 30px;
  font-weight: 700;
  margin-bottom: 40px;
}
.content-container .content-inner .content-top-area .content-top-subtitle {
  font-size: 22px;
  color: #333;
}
.content-container .content-inner .content-bottom-area {
  width: 900px;
  border-top: 1px solid #e5e5e5;
  padding-top: 70px;
  margin: 0 auto;
}
.content-container .content-inner .content-bottom-area .content-bottom-title {
  padding-left: 12px;
  font-size: 22px;
  font-weight: 700;
  margin-bottom: 13px;
}
.content-container .content-inner .content-bottom-area .content-bottom-subtitle {
  padding-left: 12px;
  font-size: 18px;
  margin-bottom: 50px;
}
.content-container .content-inner .content-bottom-area .content-bottom-table {
  width: 100%;
  display: flex;
  border-top: 2px solid #9a9a9a;
  border-bottom: 2px solid #9a9a9a;
  margin-bottom: 120px;
}
.content-container .content-inner .content-bottom-area .content-bottom-table .bottom-table-left {
  width: 20%;
  padding: 30px 0 30px 18px;
  font-size: 20px;
  color: #4d4d4d;
  background-color: #ededed;
}
.content-container .content-inner .content-bottom-area .content-bottom-table .bottom-table-right {
  flex: 1;
  padding-left: 23px;
  font-size: 18px;
  line-height: 1.5;
  display: flex;
  align-items: center;
}
.content-container .content-inner .content-bottom-area .content-bottom-buttons {
  display: flex;
  justify-content: center;
}
.content-container .content-inner .content-bottom-area .content-bottom-buttons .release-button {
  margin-right: 22px;
  width: 250px;
  padding: 15px 0;
  background-color: #6738ff;
  color: #fff;
  border: 2px solid #6738ff;
  font-size: 18px;
  cursor: pointer;
}
.content-container .content-inner .content-bottom-area .content-bottom-buttons .cancel-button {
  border: 2px solid #6738ff;
  padding: 15px 0;
  width: 250px;
  color: #6738ff;
  font-size: 18px;
  cursor: pointer;
  background: #fff;
}

@media (max-width: 990px) {
  .content-container .content-inner .content-bottom-area {
    width: auto;
  }
  .content-container .content-inner .content-top-area .content-top-title {
    font-size: 24px;
    margin-bottom: 18px;
  }
  .content-container .content-inner .content-top-area .content-top-subtitle {
    font-size: 12px;
  }
  .content-container .content-inner .content-bottom-area .content-bottom-title {
    font-size: 20px;
  }
  .content-container .content-inner .content-bottom-area .content-bottom-subtitle {
    font-size: 16px;
  }
  .content-container .content-inner .content-bottom-area .content-bottom-table-wrap {
    padding: 0 30px;
  }
  .content-container .content-inner .content-bottom-area .content-bottom-table {
    flex-direction: column;
  }
  .content-container .content-inner .content-bottom-area .content-bottom-table .bottom-table-left {
    width: auto;
    font-size: 18px;
    text-align: center;
    padding-left: 0;
  }
  .content-container .content-inner .content-bottom-area .content-bottom-table .bottom-table-right {
    padding: 30px 0;
    font-size: 14px;
  }
  .content-container .content-inner .content-bottom-area .content-bottom-buttons {
    flex-direction: column;
    align-items: center;
  }
  .content-container .content-inner .content-bottom-area .content-bottom-buttons .release-button {
    margin-right: 0;
    margin-bottom: 22px;
    width: 200px;
    font-size: 17px;
  }
  .content-container .content-inner .content-bottom-area .content-bottom-buttons .cancel-button {
    width: 200px;
    font-size: 17px;
  }
}
</style>