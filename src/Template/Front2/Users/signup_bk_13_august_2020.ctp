<?php
       //  session_start();
include "/var/www/html/config/cert_conf.php";      // ȯ�漳�� ���� include
$g_conf_Ret_URL      = "http://coinibt.com/front2/KcpcertCoinibt/WEB_ENC/kcpcert_proc_res.php"; // ��������
?>
<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/login2.css" />

<div class="container">

	<div class="login_box signup_box">

		<?php echo $this->Form->create('signup');?>

		<div style="width: 480px; margin: 0 auto">

			<div class="welcome">
				<?=__('SignUp Phone') ?>
			</div>
                                      <div id="show_msg" style="display: none;">����� �������ּ���.</div>
			<div class="form-verify">

                    <form method="post" name="form_auth" id="auth_form">
                        <input type="hidden" name="ordr_idxx" id="auth_ordr_idxx" class="frminput "value="" readonly="readonly" maxlength="40"/>
                        <div id="show_pay_btn">
				            <input type="submit" class="button" id="id_auth_btn" value="<?=__('Identity verification') ?>" onclick="return auth_type_check(this);" />
                        </div>

				        <input type="hidden" name="req_tx" value="cert" /><!-- ��û���� -->
				        <input type="hidden" name="cert_method" value="01" /><!-- ��û���� -->
				        <input type="hidden" name="web_siteid"   value="" /><!-- ������Ʈ���̵� : ../cfg/cert_conf.php ���Ͽ��� �������ּ��� -->
				        <!-- <input type="hidden" name="fix_commid" value="KTF"/>--><!-- ���� ��Ż� default ó���� �Ʒ��� �ּ��� �����ϰ� ����Ͻʽÿ� - SKT : SKT , KT : KTF , LGU+ : LGT-->
				        <input type="hidden" name="site_cd" value="" /><!-- ����Ʈ�ڵ� : ../cfg/cert_conf.php ���Ͽ��� �������ּ��� -->
				        <input type="hidden" name="Ret_URL" value="<?= $g_conf_Ret_URL ?>" /><!-- Ret_URL : ../cfg/cert_conf.php ���Ͽ��� �������ּ��� -->
				        <input type="hidden" name="cert_otp_use" value="Y" /><!-- cert_otp_use �ʼ� ( �޴��� ����) - Y : �Ǹ� Ȯ�� + OTP ���� Ȯ�� , N : �Ǹ� Ȯ�� only -->
				        <input type="hidden" name="cert_enc_use" value="Y" /><!-- cert_enc_use �ʼ� (������ : �޴��� ����) -->
				        <input type="hidden" name="cert_enc_use_ext" value="Y" />      <!-- ���� ��ȣȭ ��ȭ -->
				        <input type="hidden" name="res_cd" value="" />
				        <input type="hidden" name="res_msg" value="" />
				        <input type="hidden" name="veri_up_hash" value="" /><!-- up_hash ���� �� ���� �ʵ� -->
				        <input type="hidden" name="cert_able_yn" value="Y" /><!-- ����Ȯ�� input ��Ȱ��ȭ -->
				        <input type="hidden" name="web_siteid_hashYN" value="Y" /><!-- web_siteid �� ���� �ʵ� -->
				        <input type="hidden" name="param_opt_1"  value="register" /> <!-- ������ ��� �ʵ� (�����Ϸ�� ����)-->
				        <input type="hidden" name="param_opt_2"  value="" />
				        <input type="hidden" name="param_opt_3"  value="" />

			        </form>
			    </div>

			<div style="margin: 35px 0;">

				<ul class="label" style="margin-top:40px">
					<li><?=__('User ID') ?></li>
					<li id="msg_phone_check"></li>
				</ul>
                                                                                <div class="form-field">
					<input id="phone" name="phone" required value="" type="tel" class="input" placeholder="<?=__('Auth first') ?>" pattern="[0-9]{3}-[0-9]{4}-[0-9]{4}" disabled />
				</div>
                                                                                <ul id= "usern" class="label" style="margin-top:10px">
					<li><?=__('Name') ?></li>
					<li id="msg_id_check"></li>
				</ul>
				<div id= "userntxt" class="form-field">
					<input id="user" name="username" required value="" type="text" class="input" placeholder="<?=__('Auth first') ?>" disabled/>
				</div>

				<ul class="label" style="margin-top:30px">
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
					<label><input id="check1" type="checkbox" class="check" onclick="checkAll(this)"> <span class="bold"><?=__('All agree') ?></span></label> <br />
					<label><input id="check2" type="checkbox" class="check"> <?=__('Agree terms') ?> <?=__('Necessary2') ?></label> <br />
					<label><input id="check3" type="checkbox" class="check"> <?=__('Confirm privacy') ?> <?=__('Necessary2') ?></label> <br />
					<label><input id="check4" type="checkbox" class="check"> <?=__('Agree personal') ?> <?=__('Optional2') ?></label> <br />
					<label><input id="check5" type="checkbox" class="check"> <?=__('Agree Receive') ?> <?=__('Optional2') ?></label> <br />
				</div>

			</div>

			<div class="form-submit">
				<input type="submit" class="button" value="<?=__('Member registration complete') ?>" />
			</div>

			<div class="foot-lnk">
				<a href="<?php echo $this->Url->Build(['controller'=>'users','action'=>'login']); ?>"><?=__('Already Member?') ?></a>
			</div>
                </div>
		</div>

		</form>
	</div>

</div>


<iframe id="kcp_cert" name="kcp_cert" width="100%" height="700" frameborder="0" scrolling="yes" style="display: none;"></iframe>


<script>
$(document).ready(function(){
                     init_orderid();
          $('#usern').hide();   
           $('#userntxt').hide();  
          $("#pass").blur(function(){             
          if(!checkPass($("#pass").val())){
              check_password();
              $('#msg_pass_check').html("<?=__('Password rule') ?>");
              $('#pass').addClass('input_error');
          } else{
              $('#msg_pass_check').html("");
              $('#pass').removeClass('input_error');
          }
        });
});
</script>

<script type="text/javascript">

function identity_verify(obj) {
          $(obj).addClass('pass');
          $(obj).val("<?=__('Self-certification completed') ?>");
          $('#msg_pass_check').html("<?=__('Password rule') ?>");
          $('#phone').addClass('input_complete');
          $('#user').addClass('input_complete');
          $('#phone').val('010');
          $('#usern').show();
          $('#userntxt').show();
}

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

          identity_verify(obj);
          $("#show_msg").css('display', 'none');


	//var auth_form = document.getElementById("auth_form");
    var auth_form = $("#auth_form");


    if ($('#auth_ordr_idxx').val() == '')
	{
		alert( "��û��ȣ�� �ʼ� �Դϴ�." );
		return false;
	}

	else
	{
		if( navigator.userAgent.indexOf("Android") > - 1 || navigator.userAgent.indexOf("iPhone") > - 1 || navigator.userAgent.indexOf("android-web-view") > - 1 )
		{
			auth_form.target = "kcp_cert";
            document.getElementById( "cert_info" ).style.display = "none";
			document.getElementById( "kcp_cert"  ).style.display = "";
		}
		else
		{
			var return_gubun;
			var width  = 600;
			var height = 600;

			var leftpos = screen.width  / 2 - ( width  / 2 );
			var toppos  = screen.height / 2 - ( height / 2 );

			var winopts  = "width=" + width   + ", height=" + height + ", toolbar=no,status=no,statusbar=no,menubar=no,scrollbars=no,resizable=no";
			var position = ",left=" + leftpos + ", top="    + toppos;
			var AUTH_POP = window.open('http://coinibt.com/front2/KcpcertCoinibt/WEB_ENC/kcpcert_start.php','Authentication', winopts + position);

			auth_form.target = 'Authentication';
		}
		auth_form.action = "http://coinibt.com/front2/KcpcertCoinibt/WEB_ENC/kcpcert_start.php"; // ����â ȣ�� �� ����� ���� ������ �ּ�
		return true;
    }

}
</script>



<script>
function login_agree_check(num){
	if ($("#agree_more"+num+"_contents").attr('class') == 'none')
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

function check_password() {
          var len = $('#pass').val().length;
          var pass = $('#pass').val();
	if (len<8 || len>30) {
                              if(!checkPass(pass)){
		$('#pass').addClass('input_error');
                              }
	} else {
		$('#pass').removeClass('input_error');
	}
	check_password_confirm();
}

function check_password_confirm() {
	var confirm = $('#pass2').val();
	var passwd = $('#pass').val();
	if (confirm.length==0) {
		$('#pass2').removeClass('input_complete');
		$('#pass2').removeClass('input_error');
		$('#msg_pass_confirm').html("");
		return;
	}
	if (passwd != confirm) {
		$('#pass2').removeClass('input_complete');
		$('#pass2').addClass('input_error');
		$('#msg_pass_confirm').html("<?=__('Pass different') ?>");
	} else {
		$('#pass2').removeClass('input_error');
		$('#pass2').addClass('input_complete');
		$('#msg_pass_confirm').html("");
	}
}
 
function checkAll(obj) {
    if ($(obj).is(":checked") == true) {
        $('input[type=checkbox]').prop('checked',true);
    } else {
        $('input[type=checkbox]').prop('checked',false);
    }
}

function checkPass(str){
          var re = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/;
          return re.test(str);
}
</script>
