<script>
    //alert('특정금융거래정보법에 의거하여 잠시 서비스를 중지합니다.');
    //history.go(-1);
</script>
<style>
.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}
.alert {
    position: relative;
    padding: .75rem 1.25rem;
    margin-bottom: 1rem;
    border: 1px solid transparent;
    border-radius: .25rem;
}
.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}
.iti { width: 100%; }
</style>
<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/login2.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->request->webroot ?>assets/flag/build/css/intlTelInput.css">

<div class="container">
	<div class="login_box signup_box">
		<div style="max-width: 680px; margin: 0 auto">
			<div class="welcome">
				<?=__('SignUp Phone') ?> 
			</div>
            <div class="form-verify">
                <form method="post" name="form_auth" id="auth_form"><!--  onsubmit="return false;" -->
				    <input type="hidden" name="ordr_idxx" id="auth_ordr_idxx" class="frminput" value="" readonly="readonly" maxlength="40"/>
				    <div id="show_pay_btn">
 					    <!--<input type="<?php /*echo (trim($getUserCountryCode)=="KR") ? "submit" : "hidden" */?>" class="button" name="id_auth_btn" id="id_auth_btn" value="<?/*=__('Identity verification') */?>" onclick="auth_type_check(this)"/>-->
				    </div>
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
            </div>
			<?php //$getUserCountryCode = 'test';?>
            <?php echo $this->Form->create('signup',array('id'=>'signup', 'name'=>'signup','method'=>'post'));?>
            <input type="hidden" name="getUserCountryCode" id="getUserCountryCode" value="<?= trim($getUserCountryCode); ?>"/>
                <div style="margin: 35px 0;">
                    <?= $this->Flash->render() ?>
                    <ul class="label" style="margin-top:40px">
                        <li><?=__('Phone') ?></li>
                        <li id="msg_phone_check"></li>
                    </ul>
                    <div class="form-field">
                        <!--<div class="<?/*= (trim($getUserCountryCode)=="KR") ? "" : "non-kr" */?>">-->
                        <div>
                            <!--<input id="phone" name="phone" required value="<?php /*if ( !empty($phone_no) ) echo $phone_no; */?>" type="tel" class="input"  style="vertical-align: top; display:inline-block;"   placeholder="본인인증을 완료해주세요" onclick="<?/*= (trim($getUserCountryCode)=="KR") ? 'phonechk()' : '' */?>" />-->
                            <input id="phone" name="phone" required value="<?php if ( !empty($phone_no) ) echo $phone_no; ?>" type="tel" class="input"  style="width: 94%;vertical-align: top; display:inline-block;"   placeholder="Phone Number">

                            <!--<input id="sendCodeBtn" name="sendCodeBtn" type="<?/*= (trim($getUserCountryCode)=="KR") ? "hidden" : "button" */?>" class="button" value="<?/*=__('Send code') */?>"  />-->
                            <!-- style="margin-top: 10px;"-->
							<!--<p id="error_certification" style="display:none; color:red;"></p>
                            <span id="error_phone"></span>
                            <div class="alert" style="display:none;" id="send_sms_resp"></div>
                            <img src="<?/*= $this->request->webroot */?>ajax-loader.gif" style="display:none;" id="send_sms_loader">-->
                        </div>
                    </div>
                    <ul class="<?= (trim($getUserCountryCode)=="KR") ? "hidden" : "label" ?>" >
                        <li><?= (trim($getUserCountryCode)=="KR") ? "" : __('Auth code') ?></li>
                    </ul>
                    <div class="form-field"  style="display: none">
                        <!--<div class="<?/*= (trim($getUserCountryCode)=="KR") ? "" : "non-kr" */?>">-->
                        <div class="non-kr">
                            <p>12121</p>
                            <div class="verifyCodeBox"><input id="verifyCodeTxt" name="verifyCodeTxt" required type="<?= (trim($getUserCountryCode)=="KR") ? "number" : "number" ?>" class="input" maxlength="6" placeholder="<?= __('Enter authentication code')?>" onkeydown="only_number(this)" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /></div>
                            <input id="verifyCodeBtn" name="verifyCodeBtn" type="<?= (trim($getUserCountryCode)=="KR") ? "button" : "button" ?>" class="button" value="<?=__('Authenticate') ?>" onclick="sendCodeCheck();" /><!-- style="margin-top: 10px"-->
                            <div class="alert" style="display:none;" id="send_sms_check_resp"> </div>
                            <img src="<?= $this->request->webroot ?>ajax-loader.gif" style="display:none;" id="send_sms_check_loader">
                        </div>
                    </div>
                    <input id="form_type" name="form_type"  value="<?= (trim($getUserCountryCode)=="KR") ? "korean" : "non_korean"; ?>" type="hidden" class="input"   />
                    <ul id= "userns" class="label" style="margin-top:10px">
                        <li><?=__('Name') ?></li>
                        <li id="msg_id_check"></li>
                    </ul>
                    <div id= "userntxts" class="form-field">
                        <!--<input id="user_name" name="user_name" style="width:94%;" required value="<?php /*if ( !empty($user_name) ) echo $user_name; */?>" type="text" class="input"  placeholder="본인인증을 완료해주세요" readonly onclick="<?/*= (trim($getUserCountryCode)=="KR") ? 'phonechk()' : '' */?>" />-->
                        <input id="user_name" name="user_name" style="width:94%;" required value="<?php if ( !empty($user_name) ) echo $user_name; ?>" type="text" class="input"  placeholder="Name" />
						<p id="error_user_name" style="display:none; color:red;"></p>
                    </div>
                    <ul id= "usere" class="label" style="margin-top:10px">
                        <li><?=__('Email') ?></li>
                        <li id="msg_email_check"></li>
                    </ul>
                    <div id= "useremail" class="form-field">
                        <input id="email" name="email" style="width:94%;" required value="<?php if ( !empty($email) ) echo $email; ?>" type="text" class="input"  placeholder="<?= __('Enter your email') ?>" onKeyUp="check_email(this.value)"  />
                        <div class="alert" style="display:none; color:red;" id="error_email"></div>
                    </div>
                    <ul class="label" >
                        <li><?=__('Password') ?></li>
                        <li id="msg_pass_check"></li>
                    </ul>
                    <div class="form-field">
                        <input id="pass" name="password" required value="" type="password" class="input"  maxlength="30" placeholder="비밀번호를 입력해주세요" onKeyUp="check_password(1, this.value)"  />
						<p id="pw_error_text1" style="display:none; color:red;"></p>
                    </div>
                    <ul class="label">
                        <li><?=__('Password confirm') ?></li>
                        <li id="msg_pass_confirm"></li>
                    </ul>
                    <div class="form-field">
                        <input id="pass2" name="password2" required value="" type="password" class="input"  maxlength="30" placeholder="<?=__('Password confirm') ?>" onKeyUp="check_password(2, this.value)"  />
						<p id="pw_error_text2" style="display:none; color:red;"></p>
                    </div>
                    <div class="form-check chk-left">
                        <label id="lbl1">
                            <input id="check1" name="all_agree" type="checkbox" class="check " onchange="chk()">
                            <span class="bold"><?=__('All agree') ?></span>
                        </label> <br />
                        <label id="lbl2">
                            <input id="check2" name="term" type="checkbox" class="check checkbox" onchange="chk_term()"> <?=__('Agree terms') ?> <?=__('Necessary2') ?>
                        </label> 
						<p id="error_agree" style="display:none; color:red; margin : 0;font-size: 13px"></p>	
						<br />
                        <label id="lbl3">
                            <input id="check3" name="policy" type="checkbox" class="check checkbox" onchange="chk_policy()"> <?=__('Confirm privacy') ?> <?=__('Necessary2') ?>
                        </label> 
						<p id="error_policy" style="display:none; color:red; margin : 0;font-size: 13px"></p>
						<br />
                        <label>
                            <input id="check4" name="check4" type="checkbox" class="check checkbox"> <?=__('Agree personal') ?> <?=__('Optional2') ?>
                        </label> <br />
                        <label>
                            <input id="check5" name="check5" type="checkbox" class="check checkbox"> <?=__('Agree Receive') ?> <?=__('Optional2') ?>
                        </label> <br />
                    </div>
                </div>
                <div class="form-submit">
                    <?php 
						$onclick_function = 'submit_chk()';
						if($ServerCheckValue == 'Y') {
							$onclick_function = "openWin('modal_alert')";
						}
					?>
					<button id="submit_btn" type="button" class="button" onclick="<?=$onclick_function;?>"><?=__('Member registration complete') ?></button>
                </div>
                <div class="foot-lnk">
                    <a href="<?php echo $this->Url->Build(['controller'=>'users','action'=>'login']); ?>"><?=__('Already Member?') ?></a>
                </div>
            <?php echo $this->Form->end();?>
        </div>
    </div>
</div>
<iframe id="kcp_cert" name="kcp_cert" width="100%" sandbox="allow-same-origin allow-scripts allow-popups allow-forms" height="900" frameborder="0" scrolling="yes" style="display: none;"></iframe>
<input type="hidden" id="ipinfo_token" value="<?=$ipinfo_token;?>">

<script>
	const loc = $('#getUserCountryCode').val();
	let pass_length_chk = false; // 패스워드 2가지 조건 길이 체크
	let pass_same_chk = false; // 패스워드 - 패스워드 확인 같은지 체크
	let pass_chk = false; // 특수문자, 소문자, 대문자, 숫자 포함 되어 있는지 체크
	let pass_chk_email = false; //이메일 유효성 체크
	let pass_same_email = false; // 이메일 중복 체크
	let termStatus = false; // 이용약관 체크 필수 
	let policyStatus = false; // 개인정보처리방침 필수
	let phone_chk = false;
	let username_chk = false;
	/* ipinfo_token 
		해당 토큰 수정은 UsersTable getIpinfoToken() 에서 수정해주세요
	*/
	const ipinfo_token = $('#ipinfo_token').val(); // 2021-07-22 이충현 2f11973e5039d5 => 4f91a06d11ac51 으로 수정 
	$(document).ready(function(){
        //국가번호 삭제



		if(loc != 'KR'){
			$('#user_name').attr('readonly',false);
			$('#phone').attr('readonly',false);
		}
		init_orderid();
        
        //국가별 나라코드치환
		/*$("#phone").intlTelInput({
			initialCountry: "kr",
			preferredCountries : ['ca','jp','us','cn'],
			geoIpLookup: function(callback) {
				$.get('https://ipinfo.io/json?token='+ipinfo_token, function() {}, "jsonp").always(function(resp) { 
					var countryCode = (resp && resp.country) ? resp.country : "";
					callback(countryCode);
				});
			},
			utilsScript: "/webroot/assets/flag/build/js/utils.js" // just for formatting/placeholders etc
		});
        */
		var telInput = $("#phone"),
			errorMsg = $("#error_phone");
/*		telInput.intlTelInput({
			initialCountry: "auto",
			preferredCountries : ['ca','jp','us','cn'],
			geoIpLookup: function(callback) {
				$.get('https://ipinfo.io/json?token='+ipinfo_token, function() {}, "jsonp").always(function(resp) { 
					var countryCode = (resp && resp.country) ? resp.country : "";
					callback(countryCode);
				});
			},
			utilsScript: "/webroot/assets/flag/build/js/utils.js" // just for formatting/placeholders etc
		});*/
		$('.checkbox').click(function(){
			$('input[type=checkbox]:checked+span').css('background-color','#fff');
			$('.bold').css('background-color','#fff');
			if($(this).prop('checked')==false){
				$('#check1').prop('checked',false);
			} else {
				let cnt1 = $('input[type="checkbox"]:checked').length  ;
				let cnt2 = $('input[type="checkbox"]').length -1;
				if(cnt1 == cnt2){
					$('#check1').prop('checked',true);
				}
			}
		});
		$("#sendCodeBtn").click(function() {
            var phone = $("#phone").val();
            if($("#phone").val().length > 6 && $("#phone").val().length < 16){
                if ($.trim(telInput.val())) {
                    if (telInput.intlTelInput("isValidNumber")) {
                        $.ajax({
                            url:'/front2/users/checkphoneunique',
							beforeSend: function(xhr){
								xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
							},
                            type:'POST',
                            data :{phone:phone},
                            dataType:'JSON',
                            success:function(resp){
                                if(resp!==undefined && resp!==null && resp!==''){
                                    if(resp.status==="false"){
                                        $('#error_certification').html(resp.message).show();
                                        return ;
                                    }else{
										$('#error_certification').html(resp.message).hide();
                                        sendCode();
                                    }
                                }else{
                                    errorMsg.removeClass("input_complete");
                                }
                            }
                        });
                    }else{
                        //$("#sendCodeBtn").attr("disabled",true);
                        $("#phone").addClass('input_error');
                        $("#error_phone").html("Please enter correct number");
                       // alert('bad format');
                    }
                }
            } else{
                //$("#sendCodeBtn").attr("disabled",true);
                $("#phone").addClass('input_error');
                $("#error_phone").html("The number you entered is invalid");
              //  alert('bad format - short/long number');

            }
        });
	});
	function auth_type_check(obj) {
		var auth_form = $("#auth_form");
		if ($('#auth_ordr_idxx').val() === '') {
			return false;
		} else {
			if( navigator.userAgent.indexOf("Android") > - 1 || navigator.userAgent.indexOf("iPhone") > - 1 || navigator.userAgent.indexOf("iPad") > - 1 ) {
				auth_form.attr('target', 'kcp_cert');
				$("#kcp_cert").css('display', '');
				$(".container").css('display', 'none');
				$(".header2").css('display', 'none');
			} else {
				var return_gubun;
				var width = 450;
				var height = 500;
				var leftpos = screen.width / 2 - (width / 2);
				var toppos = screen.height / 2 - (height / 2);
				var winopts = "width=" + width + ", height=" + height + ", toolbar=no,status=no,statusbar=no,menubar=no,scrollbars=no,resizable=no";
				var position = ",left=" + leftpos + ", top=" + toppos;
				var AUTH_POP = window.open('', 'auth_popup', winopts + position);
				auth_form.attr('target', 'auth_popup');
			}
			auth_form.attr('method', 'post');
			auth_form.attr('action', '/webroot/KcpcertCoinibt/SMART_ENC/smartcert_proc_req.php');
			return true;
		}
	}
	function init_orderid() // 본인인증 초기셋팅
	{
		var today = new Date();
		var year  = today.getFullYear();
		var month = today.getMonth()+ 1;
		var date  = today.getDate();
		var time  = today.getTime();

		if (parseInt(month) < 10)
		{
			month = "0" + month;
		}
		var vOrderID = year + "" + month + "" + date + "" + time;
		$('#auth_ordr_idxx').val(vOrderID);
	}
	/* 비밀번호 확인 */
	const regex = new RegExp("^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,20}$");
	const pattern1 = /[0-9]/;
    const pattern2 = /[a-zA-Z]/;
    const pattern3 = /[~!@\#$%<>^&*]/;     // 원하는 특수문자 추가 제거
	/* 비밀번호 확인 */
	function check_password(check_type,value){
		if(check_type == 1){	
			if(value.length < 8){
				pass_length_chk = false;
			} else if (value.length >= 8 && value.length < 10){
				if (!regex.test(value)) {
					pass_length_chk = false;
				} else {
					pass_length_chk = true;
				}
			} else if(value.length >= 10){
				 if( (!pattern1.test(value) && !pattern2.test(value) ) || (!pattern1.test(value) && !pattern3.test(value)) || (!pattern2.test(value) && !pattern3.test(value))) {
					pass_length_chk = false;
				 } else {
					pass_length_chk = true;
				 }
			}
			return pass_error('length');
		}
		if(check_type == 2){
			pass_same_chk = !(value != $('#pass').val());
			return pass_error('same');
		}
	}
	/* 비밀번호 에러 메세지 */
	function pass_error(type){
	/*	if(type == 'phone_chk'){
			//본인인증 관련 메시지
			if(!phone_chk){
				$('#error_certification').html('본인인증을 완료해주세요').show();
				return false;
			} else {
				$('#error_certification').html('').hide();
				return true;
			}			 
		}*/
		if(type == 'length'){
			if(!pass_length_chk){// 길이가 짧다는 에러 메세지 표출
				$('#pw_error_text1').html('If you mix both letters, numbers, and special characters, please set them to at least 8 digits, and if you mix only two, set them to at least 10 digits.').show();
				return false;
			} else {
				$('#pw_error_text1').html('').hide();
				return true;
			}
		}
		if(type == 'same'){
			// 패스워드 상이하다는 에러 메세지 
			if(!pass_same_chk){
				$('#pw_error_text2').html('The password doesnt match.').show();
				return false;
			} else {
				$('#pw_error_text2').html('').hide();
				return true;
			}
		}
		if(type == 'chk_email'){
			// 이메일 문자 관련 메세지
			if(!pass_chk_email){
				$('#error_email').html('Please enter the correct email address.').show();
				return false;
			} else {
				$('#error_email').html('').hide();
				return true;
			}			 
		}
		if(type == 'same_email'){
			// 이메일 중복 관련 메세지
			if(!pass_same_email){
				$('#error_email').html('duplicate email address.').show();
				return false;
			} else {
				$('#error_email').html('').hide();
				return true;
			}			 
		}
		if(type == 'term_error'){
			// 이용약관 관련 메세지
			if(!termStatus){
				$('#error_agree').html('You must check the terms and conditions.').show();
				$('#error_agree').next().hide();
				$('input[id="check1"]').prop('checked',false);
				return false;
			} else {
				$('#error_agree').html('').hide();
				$('#error_agree').next().show();
				return true;
			}			 
		}
		if(type == 'policy_error'){
			// 개인정보처리방침 관련 메세지
			if(!policyStatus){
				$('#error_policy').html('You must check your personal information processing policy.').show();
				$('#error_policy').next().hide();
				$('input[id="check1"]').prop('checked',false);
				return false;
			} else {
				$('#error_policy').html('').hide();
				$('#error_policy').next().show();
				return true;
			}			 
		}
		if(type == 'username'){
			if(!username_chk){// 길이가 짧다는 에러 메세지 표출
				$('#error_user_name').html('Please type your name.').show();
				return false;
			} else {
				$('#error_user_name').html('').hide();
				return true;
			}
		}
	}
	/*이메일 유효성 검사*/
	function check_email(value){	
		var pattern = new RegExp("^[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*.[a-zA-Z]{2,3}$");
		if(!pattern.test(value)){
			pass_chk_email = false;
			pass_error('chk_email');
			return;
		} else {
			pass_chk_email = true;
		}
		pass_error('chk_email');
		if(pass_chk_email){
			$.ajax({
				url:'/front2/users/checkEmailUnique',
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
				type:'POST',
				data :{email:value},
				dataType:'JSON',
				success:function(resp){
					pass_same_email = false;
					if(resp.message == 'success') {
						pass_same_email = true;
					}
					pass_error('same_email');
				}
			});
		}
	}	
	function chk(){//전체동의
		if ($(('input[id="check1"]')).is(":checked")) {
			$('input[type="checkbox"]').prop('checked',true);
			termStatus = true;
			policyStatus = true;
			$('input[type=checkbox]:checked+span').css('background-color','#fff');
		}  else {
			$('input[type="checkbox"]').prop('checked',false);
			termStatus = false;
			policyStatus = false;
		} 
		pass_error('term_error');
		pass_error('policy_error');
	}
	function chk_term(){ //이용약관 
		if (!$(('input[id="check2"]')).is(":checked")) {
			termStatus = false;
		}  else {
			termStatus = true;
		}
		return pass_error('term_error');
	} 
	function chk_policy(){ //개인정보처리방침
		if (!$(('input[id="check3"]')).is(":checked")) {
			policyStatus = false;
		}  else {
			policyStatus = true;
		} 
		return pass_error('policy_error');
	}

	/*회원가입 완료 버튼*/
	function submit_chk(){
		//modal_alert('알림','2021-07-14 10:30 부터 2021-07-14 11:00 까지 <br>서버 점검으로 인해 접속이 불가합니다.');
		//return;
		check_auth();
		/*if(!pass_error('phone_chk')){*/
        if($("#phone").val() == ''){
			$('#phone').focus();
            alert("Check your phone number. ")
			return;
		}
		if(loc != 'KR'){
			if(!pass_error('username')){
				return;
			}
		}
		if(!pass_error('chk_email')){
			$('#email').focus();
			return;
		}
		if(!pass_error('same_email')){
			$('#email').focus();
			return;
		}
		if(!pass_error('length')){
			$('#pass').focus();
			return;
		}
		if(!pass_error('same')){
			$('#pass').focus();
			return;
		}
		if(!pass_error('term_error')){
			return;
		}
		if(!pass_error('policy_error')){
			return;
		}
		$('#signup').submit();
	}
	function check_auth(){
		const user_name = $('#user_name').val();
		if(user_name.trim() != '' && user_name.length > 1){
			username_chk = true;
		}
		return ;
	}

	/*대표전화 본인인증창 띄우기*/
	function phonechk(){
	   if($('#phone').val() == ''){
			$('#id_auth_btn').click();
	   } 
	   return;
	}
	function certification(){
		const phone_number = $('#phone').val();
		$.ajax({
			url:'/front2/users/checkphoneunique',
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
			type:'POST',
			data :{phone:phone_number},
			dataType:'JSON',
			success:function(resp){
				if(resp!==undefined && resp!==null && resp!==''){
					if(resp.status==="false"){
						modal_alert('경고','이미 가입된 번호입니다.');
						$('#error_certification').html('이미 가입된 번호입니다.').show();
						$('#phone').val('');
						$('#user_name').val('');
						return ;
					}else{
						phone_chk = true;
						$('#error_certification').hide();
					}
				}else{
					//$("#sendCodeBtn").attr("disabled",true);
				  //  alert("error happened");
					errorMsg.removeClass("input_complete");
				}
			}
		});
	}
    //해외 SMS 인증코드 발송 관련 제거
	function sendCodeCheck(){

		var authCode = $("#verifyCodeTxt").val();
		$.ajax({
			url:'<?php echo $this->Url->build(["controller"=>"Users","action"=>"smsCodeCheck"]) ?>',
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
			type:'POST',
			data :{authcode:authCode,"phone" : $('#phone').val()},
			dataType:'JSON',
			success:function(resp){
				if(resp.success==="true"){
					$("#verifyCodeBtn").val("<?=__('Authenticated') ?>");
					$("#verifyCodeBtn").attr('disabled',true);
					$("#sendCodeBtn").attr('disabled',true);
					phone_chk = true;
					pass_error('phone_chk');
				} else {
					$("#send_sms_check_resp").removeClass("alert-success").addClass("alert-danger").html(resp.message).show();
					$("#verifyCodeTxt").addClass('input_error');
					$("#sendCodeBtn").removeAttr('disabled').prop('enabled', true);
				}
				setTimeout(function(){
					$("#send_sms_check_resp").hide();
				},5000);
				$("#send_sms_check_loader").hide();
			} ,
			error:function(resp){
				$("#sendCodeBtn").removeAttr('disabled').prop('enabled', true);
				$("#verifyCodeTxt").addClass('input_error');
				$("#send_sms_check_loader").hide();
			} 
		})
	}
	function sendCode(){
		$("#sendCodeBtn").removeAttr('disabled').prop('enabled', true);
		let countryData = $("#phone").intlTelInput("getSelectedCountryData");
		let getPhoneVal = $("#phone").val();
		let countryCode = countryData.dialCode;;
		 $("#send_sms_loader").show();
		$.ajax({
			url:'<?php echo $this->Url->build(["controller"=>"Users","action"=>"sendsmscode"]) ?>',
			type:'POST',
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
			data :{"phone":getPhoneVal,"country":countryCode,"type":'signup'},
			dataType:'JSON',
			success:function(resp){
				$("#send_sms_resp").removeClass("alert-success alert-danger").addClass("alert-success").html(resp.message).show();
				setTimeout(function(){
					$("#send_sms_resp").hide();
				},5000);
				setTimeout(function (){
					$("#sendCodeBtn").removeAttr('disabled').prop('enabled', true);
					$("#send_sms_resp").html("Please wait! It may take a minute to receive the verification code!");
				},100000);
				$("#send_sms_loader").hide();
				$("#sendCodeBtn").attr('disabled','disabled');
			} ,
			error:function(resp){
				$("#verifyCodeBtn").removeAttr('disabled').prop('enabled', true);
				$("#sendCodeBtn").removeAttr('disabled').prop('enabled', true);
				$("#phone").addClass('input_error');
				$("#send_sms_loader").hide();
			} 
		})
	}
</script>
<script src="<?php echo $this->request->webroot ?>assets/flag/build/js/utils.js"></script>
<script src="<?php echo $this->request->webroot ?>assets/flag/build/js/intlTelInput.js"></script>

