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
			<!--<div id="show_msg" style="display: none;">����� �������ּ���.</div>-->
			    <div class="form-verify">
				
			        <form method="post" name="form_auth" id="auth_form"><!--  onsubmit="return false;" -->
				    <input type="hidden" name="ordr_idxx" id="auth_ordr_idxx" class="frminput "value="" readonly="readonly" maxlength="40"/>
				    <div id="show_pay_btn">
					    <input type="<?php echo (trim($getUserCountryCode)=="KR") ? "submit" : "hidden" ?>" class="button" name="id_auth_btn" id="id_auth_btn" value="<?=__('Identity verification') ?>" onclick="return auth_type_check(this);" />
				    </div>

				    <input type="hidden" name="req_tx" value="cert" /><!-- 요청종류 -->
				    <input type="hidden" name="cert_method" value="01" /><!-- 요청구분 -->
				    <input type="hidden" name="web_siteid"   value="<?= $g_conf_web_siteid ?>" /><!-- 웹사이트아이디 : ../cfg/cert_conf.php 파일에서 설정해주세요 -->
				    <!-- <input type="hidden" name="fix_commid" value="KTF"/>--><!-- 노출 통신사 default 처리시 아래의 주석을 해제하고 사용하십시요 - SKT : SKT , KT : KTF , LGU+ : LGT-->
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
			<?php echo $this->Form->create('signup',array('id'=>'signup', 'name'=>'signup'));?>
			<div style="margin: 35px 0;">
				<?= $this->Flash->render() ?>
				<ul class="label" style="margin-top:40px">
					<li><?=__('Phone') ?></li>
					<li id="msg_phone_check"></li>
				</ul>

                <div class="form-field">
                    <div class="<?= (trim($getUserCountryCode)=="KR") ? "" : "non-kr" ?>">
					    <input id="phone" name="phone" required value="<?php if ( !empty($phone_no) ) echo $phone_no; ?>" type="tel" maxlength="<?= (trim($getUserCountryCode)=="KR") ? "11" : "10" ?>" class="input" placeholder="<?= __('Auth first')?>"  style="vertical-align: top; display:inline-block;"/>
                        <div class="alert" style="display:none;" id="send_sms_resp"></div>
                        <input id="sendCodeBtn" name="sendCodeBtn" type="<?= (trim($getUserCountryCode)=="KR") ? "hidden" : "button" ?>" class="button" value="<?=__('Send code') ?>" onclick="sendCode();" style="margin-top: 10px;"/>
                        <img src="<?= $this->request->webroot ?>ajax-loader.gif" style="display:none;" id="send_sms_loader">
                    </div>
                </div>

                <ul class="<?= (trim($getUserCountryCode)=="KR") ? "hidden" : "label" ?>" >
                    <li><?= (trim($getUserCountryCode)=="KR") ? "" : __('Auth code') ?></li>
                </ul>
                <div class="form-field" >
                    <div class="<?= (trim($getUserCountryCode)=="KR") ? "" : "non-kr" ?>">
                        <input id="verifyCodeTxt" name="verifyCodeTxt" required type="<?= (trim($getUserCountryCode)=="KR") ? "hidden" : "text" ?>" class="input" maxlength="6" placeholder="<?= __('Enter auth code')?>" />
                        <div class="alert" style="display:none;" id="send_sms_check_resp"> </div>
                        <input id="verifyCodeBtn" name="verifyCodeBtn" type="<?= (trim($getUserCountryCode)=="KR") ? "hidden" : "button" ?>" class="button" value="<?=__('Authenticate') ?>" onclick="sendCodeCheck();" style="margin-top: 10px"/>
                        <img src="<?= $this->request->webroot ?>ajax-loader.gif" style="display:none;" id="send_sms_check_loader">
                    </div>
                </div>

				<input id="form_type" name="form_type"  value="<?= (trim($getUserCountryCode)=="KR") ? "korean" : "non_korean"; ?>" type="hidden" class="input"   />

                <ul id= "userns" class="label" style="margin-top:10px">
					<li><?=__('Name') ?></li>
					<li id="msg_id_check"></li>
				</ul>
				<div id= "userntxts" class="form-field">
					<input id="user_name" name="user_name" required value="<?php if ( !empty($user_name) ) echo $user_name; ?>" type="text" class="input"  placeholder="<?= (trim($getUserCountryCode)=="KR") ? __('Auth first') : __("Please enter your name") ?>" onkeyup="<?= (trim($getUserCountryCode)=="KR") ? "" : "checkUserName()" ?>" />
				</div>

                <ul id= "usere" class="label" style="margin-top:10px">
                    <li><?=__('Email') ?></li>
                    <li id="msg_email_check"></li>
                </ul>
                <div id= "useremail" class="form-field">
                    <input id="email" name="email" required value="<?php if ( !empty($email) ) echo $email; ?>" type="text" class="input"  placeholder="<?= __("Enter Email") ?>" />
                </div>

				<ul class="label" >
					<li><?=__('Password') ?></li>
					<li id="msg_pass_check"></li>
				</ul>
				<div class="form-field">
					<input id="pass" name="password" required value="" type="password" class="input" data-type="password" placeholder="<?=__('Please enter a password') ?>" onKeyUp="check_password()" />
				</div>

				<ul class="label">
					<li><?=__('Password confirm') ?></li>
					<li id="msg_pass_confirm"></li>
				</ul>

				<div class="form-field">
					<input id="pass2" name="password2" required value="" type="password" class="input" data-type="password" placeholder="<?=__('Password again') ?>" onKeyUp="check_password_confirm()" />
				</div>

				<div class="form-check chk-left">
					<label id="lbl1"><input id="check1" name="all_agree" type="checkbox" class="check" onclick="checkAll(this)"> <span class="bold"><?=__('All agree') ?></span></label> <br />
					<label id="lbl2"><input id="check2" name="term" type="checkbox" class="check"> <?=__('Agree terms') ?> <?=__('Necessary2') ?></label> <br />
					<label id="lbl3"><input id="check3" name="policy" type="checkbox" class="check"> <?=__('Confirm privacy') ?> <?=__('Necessary2') ?></label> <br />
					<label><input id="check4" type="checkbox" class="check"> <?=__('Agree personal') ?> <?=__('Optional2') ?></label> <br />
					<label><input id="check5" type="checkbox" class="check"> <?=__('Agree Receive') ?> <?=__('Optional2') ?></label> <br />
				</div>

			</div>

			<div class="form-submit">
				<input type="submit" id="submit_btn" class="button" value="<?=__('Member registration complete') ?>" onclick="check_password()"/>
			</div>

			<div class="foot-lnk">
				<a href="<?php echo $this->Url->Build(['controller'=>'users','action'=>'login']); ?>"><?=__('Already Member?') ?></a>
			</div>
			
			<?php echo $this->Form->end();?>
        </div>
    </div>
</div>
<iframe id="kcp_cert" name="kcp_cert" width="100%" sandbox="allow-same-origin allow-scripts allow-popups allow-forms" height="700" frameborder="0" scrolling="yes" style="display: none;"></iframe>
<script>

function submitButtonDisableOrEnable(){
	var term =$('input[name="term"]').is(':checked');
	var policy =$('input[name="policy"]').is(':checked');
	if(term && policy){
        $("#submit_btn").css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
	}
	else {
        $("#submit_btn").attr('disabled','disabled');
	}
}

function changeStatus(status){
    if(status === true) {
        $("#check1").prop("disabled", status).attr('disabled', status);
        $("#check2").prop("disabled", status).attr('disabled', status);
        $("#check3").prop("disabled", status).attr('disabled', status);
        $("#lbl1").prop("disabled", status).attr('disabled', status).css({
            'pointer-events': 'none',
            'cursor': 'default'
        });
        $("#lbl2").prop("disabled", status).attr('disabled', status).css({
            'pointer-events': 'none',
            'cursor': 'default'
        });
        $("#lbl3").prop("disabled", status).attr('disabled', status).css({
            'pointer-events': 'none',
            'cursor': 'default'
        });
    } else {
        // alert('false');
        // $("#check1").removeAttr('disabled');
        // $("#check2").removeAttr('disabled');
        // $("#check3").removeAttr('disabled');
        $("#lbl1").prop("disabled", status).css({'pointer-events': 'auto', 'cursor': 'pointer'});
        $("#check1").prop('disabled', status);
        $("#check2").prop('disabled', status);
        $("#check3").prop('disabled', status);
        
        $("#lbl2").prop("disabled", status).attr('disabled', status).css({
            'pointer-events': 'auto',
            'cursor': 'pointer'
        });
        $("#lbl3").prop("disabled", status).attr('disabled', status).css({
            'pointer-events': 'auto',
            'cursor': 'pointer'
        });
    }
    
}

$(document).ready(function(){

    $("#submit_btn").css({'pointer-events': 'none'}).attr('disabled','disabled');
    
    changeStatus(true);

    var loc = "<?= trim($getUserCountryCode); ?>";

    // $(".loginform").submit(function(){
    //     $("#loading-o").removeClass('none');
    //     var countryData = $("#phone").intlTelInput("getSelectedCountryData");
    //     var getPhoneVal = $("#phone").val();
    //     var getFirstChar = getPhoneVal.charAt(0);
    //     if(getPhoneVal!='') {
    //         if(countryData.dialCode==82 && getFirstChar==0){
    //             getPhoneVal = getPhoneVal.substr(1);
    //         }
    //         $("#phone").val("+"+countryData.dialCode+getPhoneVal);
    //     }
    // });

    $("#email").blur(function () {
        if (!validateEmail($("#email").val())) {
            //unCheckAll();
            $("#email").addClass('input_error');

        } else {
            $("#email").removeClass('input_error');
            $("#email").addClass('input_complete');
        }
    });

    $("#email").on('keypress', function (event) {
        var regex = new RegExp("[A-Z0-9a-z@\._]");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
            event.preventDefault();
            return false;
        }
    });

    $("#phone").intlTelInput({
        initialCountry: "auto",
        preferredCountries : ['ca','jp','us','cn'],
        geoIpLookup: function(callback) {
            $.get('https://ipinfo.io/json?token=d5b65ce795f734', function() {}, "jsonp").always(function(resp) { // 6ad007f53defcc
                var countryCode = (resp && resp.country) ? resp.country : "";
                callback(countryCode);
            });
        },
        utilsScript: "<?php echo $this->request->webroot ?>assets/flag/build/js/utils.js" // just for formatting/placeholders etc
    });

    if(loc === "KR") {

        $('#user_name').attr('readonly', true);
        $('#phone').attr('readonly', true);
        if($("#user_name").val() !== '' && $("#phone").val() !== ''){
            $("#id_auth_btn").attr('hidden',true);
        }


    } else {
        $("#verifyCodeBtn").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
        $("#sendCodeBtn").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});

        $("#user_name").on('keypress', function (event) {
            var regex = new RegExp("^[a-zA-Z ]+$");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!regex.test(key)) {
                event.preventDefault();
                return false;
            }
        });

        $("#phone").on('keypress', function (event) {
            var regex = new RegExp("^[0-9]$");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!regex.test(key)) {
                event.preventDefault();
                return false;
            }
        });

        $("#phone").on('keyup', function(){
            if($("#phone").val().length === 10){
                $("#sendCodeBtn").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
                $('#phone').removeClass('input_error');
                $('#phone').addClass('input_complete');
            } else {
                $("#phone").addClass('input_error');
                $("#sendCodeBtn").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
            }
        });

        $("#phone").on('change', function(){
            if($("#phone").val().length === 10){
                $("#sendCodeBtn").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
                $('#phone').removeClass('input_error');
                $('#phone').addClass('input_complete');
            } else {
                $("#phone").addClass('input_error');
                $("#sendCodeBtn").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
                $("#verifyCodeBtn").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
            }
        });

        $("#verifyCodeTxt").on('keypress', function (event) {
            var regex = new RegExp("^[0-9]$");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!regex.test(key)) {
                event.preventDefault();
                return false;
            }
        });

        $("#verifyCodeTxt").on('keyup', function(){
            if($("#verifyCodeTxt").val().length !== 6){
                $("#verifyCodeTxt").addClass('input_error');
                $("#verifyCodeBtn").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
            } else {
                $("#verifyCodeBtn").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
                $('#verifyCodeTxt').removeClass('input_error');
                $('#verifyCodeTxt').addClass('input_complete');
            }
        });
    }

	$('input[name="all_agree"]').click(function(){
		submitButtonDisableOrEnable();
	});

	$('input[name="term"]').click(function(){
		submitButtonDisableOrEnable();
	});
	
	$('input[name="policy"]').click(function(){
		submitButtonDisableOrEnable();
	});
	//submitButtonDisableOrEnable();
    init_orderid();
    $('#usern').hide();
    $('#userntxt').hide();

    $("#pass").change(function (){
        if($('#pass2').val() === ''){
            $('#msg_pass_check').html("");
            $('#msg_pass_confirm').html("");
            $('#msg_pass_confirm').removeClass("input_error");
        }
        check_password();
        if(loc === "KR"){
           validationKR();
        }else {
           validationGL();
        }
    });
    $("#pass").blur(function (){
        if($('#pass2').val() === ''){
            $('#msg_pass_check').html("");
            $('#msg_pass_confirm').html("");
            $('#msg_pass_confirm').removeClass("input_error");
        }
        check_password();
        if(loc === "KR"){
            validationKR();
        }else {
            validationGL();
        }
    });

    $("#pass").keyup(function(){
        if($('#pass2').val() === ''){
            $('#msg_pass_check').html("");
            $('#msg_pass_confirm').html("");
            $('#msg_pass_confirm').removeClass("input_error");
        }
        check_password();
        if(loc === "KR"){
            validationKR();
        }else {
            validationGL();
        }
    });

    $("#pass2").change(function (){
        check_password_confirm();
        if(loc === "KR"){
            validationKR();
        }else {
            validationGL();
        }
    });
    $("#pass2").blur(function (){
        check_password_confirm();
        if(loc === "KR"){
            validationKR();
        }else {
            validationGL();
        }
    });

    $("#pass2").keyup(function(){
        check_password_confirm();
        if(loc === "KR"){
            validationKR();
        }else {
            validationGL();
        }
    });

});

function init_orderid()
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

function auth_type_check(obj) {
    //$("#show_msg").css('display', 'none');
    var auth_form = $("#auth_form");

    if ($('#auth_ordr_idxx').val() === '') {
        //alert( "��û��ȣ�� �ʼ� �Դϴ�." );
        return false;
    } else {
        if ((navigator.userAgent.indexOf("Android") > -1 || navigator.userAgent.indexOf("iPhone") > -1) === false) // 스마트폰이 아닌경우
        {
            var return_gubun;
            var width = 450;
            var height = 500;

            var leftpos = screen.width / 2 - (width / 2);
            var toppos = screen.height / 2 - (height / 2);

            var winopts = "width=" + width + ", height=" + height + ", toolbar=no,status=no,statusbar=no,menubar=no,scrollbars=no,resizable=no";
            var position = ",left=" + leftpos + ", top=" + toppos;
            var AUTH_POP = window.open('', 'auth_popup', winopts + position);
        }

        auth_form.attr('method', 'post');
        auth_form.attr('target', 'auth_popup');
        //auth_form.attr('action', "<?php echo $this->Url->build(['controller' => 'Kcp', 'action' => 'kcpcertProcReq', 'prefix' => false]); ?>");
        auth_form.attr('action', 'http://coinibt.io/webroot/KcpcertCoinibt/WEB_ENC/kcpcert_proc_req.php');
        //identity_verify(this);
        return true;

    }
}
</script>

<script>
function login_agree_check(num){
	if ($("#agree_more"+num+"_contents").attr('class') === 'none')
	{
		$("#agree_more"+num+"_contents").removeClass('none');
		$("#agree_more"+num+"_more").addClass('none');
		$("#agree_more"+num+"_close").removeClass('none');
	} else {
		$("#agree_more"+num+"_contents").addClass('none');
		$("#agree_more"+num+"_more").removeClass('none');
		$("#agree_more"+num+"_close").addClass('none');
	}
}

function validationKR(){

    if($("#phone").val() === '' || $("#user_name").val() === ''){
        
        changeStatus(true);
        return false;
    }

    if($("#email").val() === '' || validateEmail($("#email").val()) !== true){
        
        changeStatus(true);
        return false;
    }

    if($("#pass").val() === '' || $("#pass2").val() === ''){
        
        changeStatus(true);
        return false;
    }

    if($("#pass").val().length < 8 || $("#pass2").val().length < 8 ){
        
        changeStatus(true);
        return false;
    }

    if($("#pass").val().length > 30 || $("#pass2").val().length > 30 ){
        
        changeStatus(true);
        return false;
    }

    if(!checkPass($("#pass").val()) || !checkPass($("#pass2").val())){
        
        changeStatus(true);
        return false;
    }

    
    changeStatus(false)
    $('#pass').removeClass('input_error');
    $('#pass').addClass('input_complete');
    $("#check1").attr('disabled', status);
    $("#check2").attr('disabled', status);
    $("#check3").attr('disabled', status);
    $("#lbl1").prop("disabled", status).css({
        'pointer-events': 'auto',
        'cursor': 'pointer'
    });
    $("#lbl2").prop("disabled", status).attr('disabled', status).css({
        'pointer-events': 'auto',
        'cursor': 'pointer'
    });
    $("#lbl3").prop("disabled", status).attr('disabled', status).css({
        'pointer-events': 'auto',
        'cursor': 'pointer'
    });

    return true;

}

function validationGL() {
    if($("#user_name").val() === '' || checkUserName($("#user_name").val()) !== true){
        
        changeStatus(true);
        return false;
    }

    if($("#email").val() === '' || validateEmail($("#email").val()) !== true){
        
        changeStatus(true);
        return false;
    }

    if($("#pass").val() === '' || $("#pass2").val() === ''){
        
        changeStatus(true);
        return false;
    }

    if($("#pass").val().length < 8 || $("#pass2").val().length < 8 ){
        
        changeStatus(true);
        return false;
    }

    if($("#pass").val().length > 30 || $("#pass2").val().length > 30 ){
        
        changeStatus(true);
        return false;
    }

    if(!checkPass($("#pass").val()) || !checkPass($("#pass2").val())){
       
        changeStatus(true);
        return false;
    }

    if($("#verifyCodeTxt").val().length !== 6 || $("#phone").val().length !== 10){
        
        changeStatus(true);
        return false;
    }

    if($("#pass").val() !== $("#pass2").val()){
        
        changeStatus(true);
        return false;
    }

    
    changeStatus(false);
    // $('#pass').removeClass('input_error');
    // $('#pass').addClass('input_complete');
    // $("#check1").attr('disabled', status);
    // $("#check2").attr('disabled', status);
    // $("#check3").attr('disabled', status);
    // $("#lbl1").prop("disabled", status).css({
    //     'pointer-events': 'auto',
    //     'cursor': 'pointer'
    // });
    // $("#lbl2").prop("disabled", status).attr('disabled', status).css({
    //     'pointer-events': 'auto',
    //     'cursor': 'pointer'
    // });
    // $("#lbl3").prop("disabled", status).attr('disabled', status).css({
    //     'pointer-events': 'auto',
    //     'cursor': 'pointer'
    // });
    return true;
}

function checkUserName(){

    var len = $('#user_name').val().length;
    var txt = $('#user_name').val();
    
    if(len<4 || len>40 || len === 0) {
        unCheckAll();
        $('#user_name').addClass('input_error');

        // $("#submit_btn").attr('disabled',true);
        $('#msg_id_check').html('Please enter valid name');
        // $("#submit_btn").prop('disabled', true).css({'pointer-events': 'none', 'background-color':'#d3ccea','cursor':'default','color':'#ffff'});
    } else {
        $('#user_name').removeClass('input_error');
        $('#msg_id_check').html('');
        $('#user_name').addClass('input_complete');
        return true;
    }
    if(len>3 && len<40){
        if(!checkName(txt)){
           // unCheckAll();
            $('#user_name').addClass('input_error');
            // $("#submit_btn").attr('disabled',true);
            $('#msg_id_check').html('Please enter valid name');
            // $("#submit_btn").prop('disabled', true).css({'pointer-events': 'none', 'background-color':'#d3ccea','cursor':'default','color':'#ffff'});
        } else {
            $('#user_name').removeClass('input_error');
            $('#msg_id_check').html('');
            $('#user_name').addClass('input_complete');
            return true;
        }
    } else {
       // unCheckAll();
        $('#user_name').addClass('input_error');
        // $("#submit_btn").attr('disabled',true);
        $('#msg_id_check').html('Please enter valid name');
        // $("#submit_btn").prop('disabled', true).css({'pointer-events': 'none', 'background-color':'#d3ccea','cursor':'default','color':'#ffff'});
    }
}

function check_password() {
    // console.log("check_password");
    var len = $('#pass').val().length;
    var pass = $('#pass').val();
    var confirm = $('#pass2').val();

    if (confirm.length === 0) {
        $('#pass2').removeClass('input_complete');
        $('#pass2').removeClass('input_error');
        $('#msg_pass_confirm').html("");
        $('#msg_pass_check').html("");
    }

    if (len === 0) {
        $('#pass').removeClass('input_complete');
        $('#pass').removeClass('input_error');
        $('#msg_pass_check').html("");
        $('#msg_pass_confirm').html("");
    }

	if (len>=8 || len<30) {
        if(!checkPass(pass)){
            unCheckAll();
		    $('#pass').addClass('input_error');
            // $("#submit_btn").attr('disabled',true);
            $('#msg_pass_check').html("<?=__('Password rule') ?>");
            // $("#submit_btn").prop('disabled', true).css({'pointer-events': 'none', 'background-color':'#d3ccea','cursor':'default','color':'#ffff'});
        } else {
            $('#pass').removeClass('input_error');
            $('#pass').addClass('input_complete');
        }
	} else {
	    //unCheckAll();
        $('#pass').addClass('input_error');
        // $("#submit_btn").attr('disabled',true);
        $('#msg_pass_check').html("<?=__('Password rule') ?>");
        // $("#submit_btn").prop('disabled', true).css({'pointer-events': 'none', 'background-color':'#d3ccea','cursor':'default','color':'#ffff'});
	}

	if(confirm === ''){
        $('#pass').removeClass('input_error');
        $('#msg_pass_check').html("");
    }

    if (pass !== confirm) {
        unCheckAll();
        $('#pass').removeClass('input_complete');
        $('#pass').addClass('input_error');
        if(confirm === ''){
            $('#msg_pass_check').html("");
            $('#msg_pass_confirm').html("");
            $('#pass2').removeClass('input_error');
        } else {
            $('#msg_pass_check').html("<?=__('Pass different') ?>");
        }
        // $("#submit_btn").attr('disabled',true);
        // $("#submit_btn").prop('disabled', true).css({'pointer-events': 'none', 'background-color':'#d3ccea','cursor':'default','color':'#ffff'});

    } else {
        if (checkPass(pass) && checkPass(confirm)) {
            $('#pass').removeClass('input_error');
            $('#pass').addClass('input_complete');
            $('#msg_pass_check').html("");
        } else {
            $('#msg_pass_check').html("<?=__('Password rule') ?>");
            $('#pass').removeClass('input_complete');
            $('#pass').addClass('input_error');
        }
    }
	check_password_confirm();
}

function check_password_confirm() {
    // console.log("check_password_confirm")
	var confirm = $('#pass2').val();
	var passwd = $('#pass').val();
	if (confirm.length === 0) {
		$('#pass2').removeClass('input_complete');
		$('#pass2').removeClass('input_error');
		$('#msg_pass_confirm').html("");
	}

    if (passwd.length === 0) {
        $('#pass').removeClass('input_complete');
        $('#pass').removeClass('input_error');
        $('#msg_pass_check').html("");
    }

	if(passwd === confirm && checkPass(passwd) && checkPass(confirm)){
        $('#pass').removeClass('input_error');
        $('#pass').addClass('input_complete');
        $('#pass2').addClass('input_complete');
        $('#msg_pass_check').html("");
        // console.log("742");
        // changeStatus(false);
    }



	if (confirm !== passwd) {
	    unCheckAll();

        if(passwd === ''){
            if(checkPass(confirm)){
                $('#msg_pass_confirm').html("");
                $('#pass2').removeClass('input_error');
               // $('#pass2').addClass('input_complete');
            } else {
                $('#pass2').addClass('input_error');
                $('#msg_pass_confirm').html("<?=__('Password rule') ?>");
            }

        } else {
            $('#msg_pass_confirm').html("<?=__('Pass different') ?>");
            $('#pass2').removeClass('input_complete');
            $('#pass2').addClass('input_error');
        }
        // $("#submit_btn").attr('disabled',true);
        // $("#submit_btn").prop('disabled', true).css({'pointer-events': 'none', 'background-color':'#d3ccea','cursor':'default','color':'#ffff'});

	} else {
        if (checkPass(passwd) && checkPass(confirm)) {
            $('#pass2').removeClass('input_error');
            $('#pass2').addClass('input_complete');
            $('#msg_pass_confirm').html("");
            // console.log("774");
            // changeStatus(false);
        } else {
            $('#msg_pass_confirm').html("<?=__('Password rule') ?>");
            $('#pass2').removeClass('input_complete');
            $('#pass2').addClass('input_error');
        }
	}
    var loc = "<?= trim($getUserCountryCode); ?>";
    if(loc === "KR"){
        if($("#user_name").val() === '' || $("#phone").val() === '' || $("#email").val() === ''){
            $('#phone').addClass('input_error');
            $('#email').addClass('input_error');
            $('#msg_id_check').html('Auth first');
            $('#msg_phone_check').html('Auth first');
            $('#user_name').addClass('input_error');
        }
    } else {
        if($("#user_name").val() === '' || $("#email").val() === '' || $("#phone").val() === ''){
            checkUserName();
            // $('#email').addClass('input_error');
            $('#phone').addClass('input_error');
            $('#msg_id_check').html('Please enter at least 4 alphabets');
            $('#msg_phone_check').html('Please enter valid email address');
            $('#user_name').addClass('input_error');
        }
    }
}
 
function checkAll(obj) {
    if ($(obj).is(":checked") === true) {
        $('input[type=checkbox]').prop('checked',true);
        $("#submit_btn").prop('enabled', true).css({'pointer-events': 'auto','cursor':'pointer'}).removeAttr('disabled');
        // $("#submit_btn").css({'pointer-events': 'auto', 'background-color':'#ffffff', 'cursor':'pointer', 'color':'#6738ff'}).removeAttr('disabled');
    } else {
        $('input[type=checkbox]').prop('checked',false);
        // $("#check1").attr('disabled', true);
        // $("#check2").attr('disabled', true);
        // $("#check3").attr('disabled', true);
        $("#submit_btn").prop('disabled', true).css({'pointer-events': 'none'}).attr('disabled','disabled');
    }
}

function unCheckAll() {
    if ($('#check1').is(":checked") === true) {
        $('input[type=checkbox]').prop('checked',false);
        $("#submit_btn").prop('disabled', true).attr('disabled','disabled').css({'pointer-events': 'none'});
        // $("#check1").attr('disabled', true);
        // $("#check2").attr('disabled', true);
        // $("#check3").attr('disabled', true);
        // $('#submit_btn').attr('disabled',true);
        // $("#submit_btn").prop('disabled', true).css({'pointer-events': 'none', 'background-color':'#d3ccea','cursor':'default','color':'#ffff'});
    } else {
        // $("#check1").attr('disabled', true);
        // $("#check2").attr('disabled', true);
        // $("#check3").attr('disabled', true);
    }
}

function checkPass(str){
    var re = /^(?=.*?[a-zA-Z])(?=.*?[0-9]).{8,}$/;
    return re.test(str);
}

function checkName(str){
    var reg = /^[a-zA-Z ]*$/;
    return reg.test(str);
}

function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function checkPhone(str){
    var reg = /^\d{10}$/;
    return reg.test(str);
}

function sendCode(){
	var countryData = $("#phone").intlTelInput("getSelectedCountryData");
    var getPhoneVal = $("#phone").val();
	var phoneNumber = "+"+countryData.dialCode+getPhoneVal;
	// alert(countryData.dialCode);
	$("#send_sms_loader").show();
	$.ajax({
		url:'<?php echo $this->Url->build(["controller"=>"Users","action"=>"sendsmscode"]) ?>',
		type:'POST',
		data :{phone:phoneNumber},
		dataType:'JSON',
		success:function(resp){
			$("#send_sms_resp").removeClass("alert-success alert-danger").addClass("alert-success").html(resp.message).show();
			setTimeout(function(){
				$("#send_sms_resp").hide();
			},5000);
            $("#sendCodeBtn").css({'pointer-events': 'none'}).attr('disabled','disabled');
			$("#send_sms_loader").hide();
		} ,
		error:function(resp){
            $("#verifyCodeBtn").removeAttr('disabled').css({'pointer-events': 'auto'}).prop('enabled', true);
            $("#sendCodeBtn").removeAttr('disabled').css({'pointer-events': 'auto'}).prop('enabled', true);
            $("#phone").addClass('input_error');
			$("#send_sms_loader").hide();
		} 
	})
}


function sendCodeCheck(){
	
    var authCode = $("#verifyCodeTxt").val();
	
	 $("#send_sms_check_loader").show();
	$.ajax({
		url:'<?php echo $this->Url->build(["controller"=>"Users","action"=>"sendsmscodecheck"]) ?>',
		type:'POST',
		data :{authcode:authCode},
		dataType:'JSON',
		success:function(resp){
			if(resp.success==="true"){
				$("#send_sms_check_resp").removeClass("alert-success alert-danger").addClass("alert-success").html(resp.message).show();
                $("#verifyCodeBtn").css({'pointer-events': 'none'}).attr('disabled','disabled');
                $("#verifyCodeBtn").html('<?= __('Authenticated')?>');
			}
			else {
				$("#send_sms_check_resp").removeClass("alert-success alert-danger").addClass("alert-danger").html(resp.message).show();
                $("#verifyCodeTxt").addClass('input_error');
                $("#verifyCodeBtn").removeAttr('disabled').css({'pointer-events': 'auto'}).prop('enabled', true);
                $("#sendCodeBtn").removeAttr('disabled').css({'pointer-events': 'auto'}).prop('enabled', true);
			}
			setTimeout(function(){
				$("#send_sms_check_resp").hide();
			},5000);
            $("#verifyCodeBtn").css({'pointer-events': 'none'}).attr('disabled','disabled');
			$("#send_sms_check_loader").hide();
		} ,
		error:function(resp){
            $("#sendCodeBtn").removeAttr('disabled').css({'pointer-events': 'auto'}).prop('enabled', true);
            $("#verifyCodeTxt").addClass('input_error');
			$("#send_sms_check_loader").hide();
		} 
	})
}

</script>
<script src="<?php echo $this->request->webroot ?>assets/flag/build/js/utils.js"></script>
<script src="<?php echo $this->request->webroot ?>assets/flag/build/js/intlTelInput.js"></script>