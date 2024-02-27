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
</style>
<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/login2.css" />
<link rel="stylesheet" type="text/css" href="flag/build/css/intlTelInput.css">

<div class="container">
	<div class="login_box signup_box">

		<div style="max-width: 480px; margin: 0 auto">

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
					<li><?=__('User ID') ?></li>
					<li id="msg_phone_check"></li>
				</ul>

                <div class="form-field">
					<input id="<?= (trim($getUserCountryCode)=="KR") ? "phone" : "email"; ?>" name="<?= (trim($getUserCountryCode)=="KR") ? "phone" : "email"; ?>" required value="<?php if ( !empty($phone_no) ) echo $phone_no; ?>" type="<?= (trim($getUserCountryCode)=="KR") ? "tel" : "email"; ?>" class="input" placeholder="<?= (trim($getUserCountryCode)=="KR") ? "000-0000-0000" : __('Email') ?>" />
                </div>
				
				<input id="form_type" name="form_type"  value="<?php echo (trim($getUserCountryCode)=="KR") ? "korean" : "non_korean"; ?>" type="hidden" class="input"   />

				
                <ul id= "userns" class="label" style="margin-top:10px">
					<li><?=__('Name') ?></li>
					<li id="msg_id_check"></li>
				</ul>
				<div id= "userntxts" class="form-field">
					<input id="user_name" name="user_name" required value="<?php if ( !empty($user_name) ) echo $user_name; ?>" type="text" class="input"  placeholder="<?= (trim($getUserCountryCode)=="KR") ? __('Auth first') : __("Please enter your name") ?>" onkeyup="<?= (trim($getUserCountryCode)=="KR") ? "" : "checkUserName()" ?>" />
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
					<label><input id="check1" name="all_agree" type="checkbox" class="check" onclick="checkAll(this)"> <span class="bold"><?=__('All agree') ?></span></label> <br />
					<label><input id="check2" name="term" type="checkbox" class="check"> <?=__('Agree terms') ?> <?=__('Necessary2') ?></label> <br />
					<label><input id="check3" name="policy" type="checkbox" class="check"> <?=__('Confirm privacy') ?> <?=__('Necessary2') ?></label> <br />
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
		$("#submit_btn").attr('disabled',false);
		$("#submit_btn").css('background-color','#6738ff').css('cursor','pointer');
	}
	else {
		$("#submit_btn").attr('disabled',true);
		$("#submit_btn").css('background-color','#d3ccea').css('cursor','default');
	}
}

$(document).ready(function(){
    $("#submit_btn").attr('disabled',true);
    $("#submit_btn").css('background-color','#d3ccea').css('cursor','default');
    $("#check1").attr('disabled', true);
    $("#check2").attr('disabled', true);
    $("#check3").attr('disabled', true);
    var loc = "<?= trim($getUserCountryCode); ?>";

    if(loc === "KR") {
        $('#user_name').attr('readonly', true);
        $('#phone').attr('readonly', true);
        if($("#user_name").val() !== '' && $("#phone").val() !== ''){
            $("#id_auth_btn").attr('hidden',true);
        }

    } else {

        $("#email").blur(function () {
            if (!validateEmail($("#email").val())) {

                $("#submit_btn").attr('disabled', true);
                $("#submit_btn").css('background-color', '#d3ccea').css('cursor', 'default');
                $("#email").addClass('input_error');

            } else {
                $("#email").removeClass('input_error');
                $("#email").addClass('input_complete');
            }
        });

        $("#user_name").on('keypress', function (event) {
            var regex = new RegExp("^[a-zA-Z ]+$");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!regex.test(key)) {
                event.preventDefault();
                return false;
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
        check_password();
        if(loc === "KOR"){
           validationKR();
        }else {
           validationGL();
        }
    });
    $("#pass").blur(function (){
        check_password();
        if(loc === "KOR"){
            validationKR();
        }else {
            validationGL();
        }
    });

    $("#pass").keyup(function(){
        check_password();
        if(loc === "KOR"){
            validationKR();
        }else {
            validationGL();
        }
    });

    $("#pass2").change(function (){
        check_password_confirm();
        if(loc === "KOR"){
            validationKR();
        }else {
            validationGL();
        }
    });
    $("#pass2").blur(function (){
        check_password_confirm();
        if(loc === "KOR"){
            validationKR();
        }else {
            validationGL();
        }
    });

    $("#pass2").keyup(function(){
        check_password_confirm();
        if(loc === "KOR"){
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

    if ($('#auth_ordr_idxx').val() === '')
	{
		//alert( "��û��ȣ�� �ʼ� �Դϴ�." );
		return false;
	}
	else
	{
	    if( ( navigator.userAgent.indexOf("Android") > - 1 || navigator.userAgent.indexOf("iPhone") > - 1 ) === false ) // 스마트폰이 아닌경우
        {
             var return_gubun;
             var width  = 450;
             var height = 500;
	
             var leftpos = screen.width  / 2 - ( width  / 2 );
             var toppos  = screen.height / 2 - ( height / 2 );
	
             var winopts  = "width=" + width   + ", height=" + height + ", toolbar=no,status=no,statusbar=no,menubar=no,scrollbars=no,resizable=no";
             var position = ",left=" + leftpos + ", top="    + toppos;
             var AUTH_POP = window.open('','auth_popup', winopts + position);
        }
        
        auth_form.attr('method', 'post');
        auth_form.attr('target', 'auth_popup');
		//auth_form.attr('action', "<?php echo $this->Url->build(['controller'=>'Kcp','action'=>'kcpcertProcReq','prefix'=>false]); ?>");
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

    if ($("#user_name").val() !== '' && $("#phone").val() !== '' && $("#pass").val() !== '' && $("#pass2").val() !== '' && $("#pass").val().length >= 8 && $("#pass2").val().length >= 8
        && checkPass($("#pass").val()) && checkPass($("#pass2").val()) && $("#pass").val() === $("#pass2").val()) {
        $("#check1").attr('disabled', false);
        $("#check2").attr('disabled', false);
        $("#check3").attr('disabled', false);
        $('#pass').removeClass('input_error');
        $('#pass').addClass('input_complete');
    } else{
        $("#check1").attr('disabled', true);
        $("#check2").attr('disabled', true);
        $("#check3").attr('disabled', true);
    }
}

function validationGL(){
    if ($("#user_name").val() !== '' && $("#email").val() !== '' && $("#pass").val() !== '' && $("#pass2").val() !== '' && $("#pass").val().length >= 8 && $("#pass2").val().length >= 8
        && checkPass($("#pass").val()) && checkPass($("#pass2").val()) && $("#pass").val() === $("#pass2").val()) {
        $("#check1").attr('disabled', false);
        $("#check2").attr('disabled', false);
        $("#check3").attr('disabled', false);
        $('#pass').removeClass('input_error');
        $('#pass').addClass('input_complete');
    } else {
        $("#check1").attr('disabled', true);
        $("#check2").attr('disabled', true);
        $("#check3").attr('disabled', true);
    }
}

function checkUserName(){

    var len = $('#user_name').val().length;
    var txt = $('#user_name').val();
    if(len<4 || len>40 || len === 0) {
        unCheckAll();
        $('#user_name').addClass('input_error');
        $("#submit_btn").attr('disabled',true);
        $('#msg_id_check').html('Please enter valid name');
        $("#submit_btn").css('background-color','#d3ccea').css('cursor','default');
    } else {
        $('#user_name').removeClass('input_error');
        $('#user_name').addClass('input_complete');
    }
    if(len>3 && len<40){
        if(!checkName(txt)){
            $('#user_name').addClass('input_error');
            $("#submit_btn").attr('disabled',true);
            $('#msg_id_check').html('Please enter valid name');
            $("#submit_btn").css('background-color','#d3ccea').css('cursor','default');
        } else {
            $('#user_name').removeClass('input_error');
            $('#user_name').addClass('input_complete');
        }
    } else {
        $('#user_name').addClass('input_error');
        $("#submit_btn").attr('disabled',true);
        $('#msg_id_check').html('Please enter valid name');
        $("#submit_btn").css('background-color','#d3ccea').css('cursor','default');
    }
}

function check_password() {
    var len = $('#pass').val().length;
    var pass = $('#pass').val();
    var confirm = $('#pass2').val();
	if (len>=8 || len<30) {
        if(!checkPass(pass)){
            unCheckAll();
		    $('#pass').addClass('input_error');
            $("#submit_btn").attr('disabled',true);
            $('#msg_pass_check').html("<?=__('Password rule') ?>");
            $("#submit_btn").css('background-color','#d3ccea').css('cursor','default');
        } else {
            $('#pass').removeClass('input_error');
            $('#pass').addClass('input_complete');
        }
	} else {
        $('#pass').addClass('input_error');
        $("#submit_btn").attr('disabled',true);
        $('#msg_pass_check').html("<?=__('Password rule') ?>");
        $("#submit_btn").css('background-color','#d3ccea').css('cursor','default');
	}

	if(confirm === ''){
        $('#pass').removeClass('input_error');
        $('#msg_pass_check').html("");
    }

    if (pass !== confirm) {
        unCheckAll();
        $('#pass').removeClass('input_complete');
        $('#pass').addClass('input_error');
        $('#msg_pass_check').html("<?=__('Pass different') ?>");
        $("#submit_btn").attr('disabled',true);
        $("#submit_btn").css('background-color','#d3ccea').css('cursor','default');

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
	var confirm = $('#pass2').val();
	var passwd = $('#pass').val();
	if (confirm.length === 0) {
		$('#pass2').removeClass('input_complete');
		$('#pass2').removeClass('input_error');
		$('#msg_pass_confirm').html("");
		return;
	}

	if(passwd === confirm && checkPass(passwd) && checkPass(confirm)){
        $('#pass').removeClass('input_error');
        $('#pass').addClass('input_complete');
        $('#pass2').addClass('input_complete');
        $('#msg_pass_check').html("");
    }

	if (confirm !== passwd) {
	    unCheckAll();
		$('#pass2').removeClass('input_complete');
		$('#pass2').addClass('input_error');
		$('#msg_pass_confirm').html("<?=__('Pass different') ?>");
        $("#submit_btn").attr('disabled',true);
        $("#submit_btn").css('background-color','#d3ccea').css('cursor','default');

	} else {
        if (checkPass(passwd) && checkPass(confirm)) {
            $('#pass2').removeClass('input_error');
            $('#pass2').addClass('input_complete');
            $('#msg_pass_confirm').html("");
        } else {
            $('#msg_pass_confirm').html("<?=__('Password rule') ?>");
            $('#pass2').removeClass('input_complete');
            $('#pass2').addClass('input_error');
        }
	}
    var loc = "<?= trim($getUserCountryCode); ?>";
    if(loc === "KR"){
        if($("#user_name").val() === '' || $("#phone").val() === ''){
            $('#phone').addClass('input_error');
            $('#msg_id_check').html('Please enter at least 4 Alphabets');
            $('#msg_phone_check').html('Please authenticate your phone');
            $('#user_name').addClass('input_error');
        }
    } else {
        if($("#user_name").val() === '' || $("#email").val() === ''){
            checkUserName();
            $('#email').addClass('input_error');
            $('#msg_id_check').html('Please enter at least 4 alphabets');
            $('#msg_phone_check').html('Please enter valid email address');
            $('#user_name').addClass('input_error');
        }
    }
}
 
function checkAll(obj) {
    if ($(obj).is(":checked") === true) {
        $('input[type=checkbox]').prop('checked',true);
    } else {
        $('input[type=checkbox]').prop('checked',false);
    }
}

function unCheckAll() {
    if ($('#check1').is(":checked") === true) {
        $('input[type=checkbox]').prop('checked',false);
        $('#submit_btn').attr('disabled',true);
        $('#submit_btn').css('background-color','#d3ccea').css('cursor','default');
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

</script>
<script src="flag/build/js/utils.js"></script>
<script src="flag/build/js/intlTelInput.js"></script>