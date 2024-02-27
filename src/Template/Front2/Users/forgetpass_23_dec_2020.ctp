<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/login2.css" />

<div class="container">

	<div class="login_box">

		<?php echo $this->Form->create('pass_find');?>

		<div class="welcome">
			<?=__('Find Password') ?>
		</div>

		<div class="com_logo2">
			<?=__('Find pass real') ?>
		</div>

        <div id="show_msg"></div>
        <div id="return_msg"></div>

		<div class="form-field">
			<input id="user" name="email" required value="" type="text" class="input short" placeholder="<?=__('Enter Email') ?>" /><input id="send_code" name="send_code" type="button" class="button black" value="<?=__('Send auth mail') ?>" onclick="sendAuthEmail()" />
		</div>

		<div class="form-field form-submit">
			<input id="pass" name="auth_key" required value="" type="text" class="input" placeholder="<?=__('Enter authentication number') ?>" />
		</div>

		<div class="form-submit">
			<input type="submit" class="button" value="<?=__('Authenticate') ?>" />
		</div>

		<div class="foot-lnk" style="visibility: hidden">
			<a href="<?php echo $this->Url->Build(['controller'=>'users','action'=>'forgetid']); ?>"><?=__('Forgot ID') ?></a>
		</div>

		</form>

	</div>

</div>

<script src="<?php echo $this->request->webroot ?>assets/js/jquery.min.js"></script>

<script>
function sendAuthEmail() {
	if (document.getElementById('_hidden_frame')==null) {
		$('<div id="_hidden_frame" style="position:fixed; left:0; top:0; width:100%; height:100%; background:#0c0c00; z-index:11; opacity:0.35"></div>').appendTo('body');
	}
	$("#_hidden_frame").show();

	if (document.getElementById('_outer_box')==null) {
        $('<div id="_outer_box" class="forget_popop" style="display:none;"></div>').appendTo('body');
		$('<div style="margin:70px 0 30px; color:#000000; font-size: 22px;">인증메일이 발송되었습니다.</div><div><input type="button" class="button" value="<?=__('Confirm') ?>" style="width:302px; height:65px; font-size:22px; background:#000000; color:#ffffff" onclick="hideMsgWindow()"></div>').appendTo('#_outer_box');
	}
	$("#_outer_box").fadeIn();
}

$(document).ready(function(){
    $("#send_code").click(function(){
    	var email = $("#user").val();
        $.ajax({
            beforeSend:function(){
                $("#show_msg").html('<img src="<?= $this->request->webroot; ?>ajax-loader.gif" />');
            },
            url : '<?php echo $this->Url->build(['controller'=>'users','action'=>'sendEmailCode']);  ?>',
            type : 'post',
	    data :{"email":email},
            dataType : 'json',
            success : function(resp){
                $("#show_msg").html('<div class="alert alert-success">Verification code sent to your email id.</div>');
                setTimeout(function(){ $("#show_msg").hide(); }, 10000);
            }
        })
    });
});

function hideMsgWindow() {
	if(document.getElementById('_outer_box')) $('#_outer_box').hide();
	$('#_hidden_frame').hide();
}

</script>
