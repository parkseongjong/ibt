<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<!-- <script type="text/javascript" src="<?php echo $this->request->webroot ?>js/front2/users/idverification.js"></script> -->
<script type="text/javascript" src="https://api.basisid.com/assets/js/widget.multi.js"></script>

<div class="container">
    <div class="profile_box">
        <?php echo $this->element('Front2/profile_menu'); ?>
        <div id="lvl" class="current_level">
            <?=__('Have level '.$user->user_level) ?>
        </div>
        <div class="depth on">
            <ul class="depth_head" level="1">
                <li>
                    <div class="round_check"></div>
                </li>
                <li>
                    <div class="title"><?=__('Mobile completed') ?></div>
                    <div class="desc"><?=$user->phone_number;?> / <?=$user->name;?></div>
                </li>
                <li>
                    <button type="button"><?=__('Verification completed') ?></button>
                </li>
            </ul>
            <div class="detail">
                <div class="notice">
                    <?=__('Notice2') ?>
                </div>
                <p>
                    - <?=__('Level notice 1-1') ?><br />
                    - <?=__('Level notice 1-2') ?>
					<br />&nbsp;&nbsp;&nbsp;<?=__('Level notice 2-2') ?>
                </p>
            </div>
        </div>
		<?php
			$level_div_class = 'round_number';
			$div_completed = '';
			$completed_text = __('Authentication required');
			$div_level = '2';
			$pending_text = '';
			if($user->email_auth == 'Y' && $user->bank_verify == 'Y' && $user->g_verify == 'Y'){
				$level_div_class = 'round_check';
				$div_completed = 'on';
				$completed_text = __('Verification completed');
				$div_level = '';
			}
			if($user->email_auth == 'P' || $user->bank_verify == 'P' || $user->g_verify == 'P'){
				// $pending_text = '(대기중인 변경 요청이 있습니다)';
				$pending_text = '(You have a pending change request)';
			}
		?>
        <div id="lvl2d" class="depth <?=$div_completed;?>">
            <ul class="depth_head" level="2">
                <li>
                    <div id="lvl2" class="<?=$level_div_class;?>"><?=$div_level;?></div>
                </li>
                <li>
                    <div id="tit" class="title">
						<?=__('Id verify') ?>
					</div>
                    <div class="desc"><?=__('Email Otp Bank') ?></div>
                </li>
                <li>
                    <button id="btnAut" type="button"><?=$completed_text;?></button>
                </li>
            </ul>
            <div class="detail">
                <div class="notice"><?=__('Notice2') ?></div>
                <p>- <?=__('Level notice 2-1') ?></p>
                <h1><?=__('Two-level phase') ?> <?=$pending_text;?></h1>
                <?php echo $this->Form->create($user,array('url'=>['controller'=>'users','action'=>'idVerification'],'id'=>'verification_form','method'=>'post'));?>
				<?php echo $this->Form->end();?>
				<!-- 이메일 인증 완료 영역-->
				<div id="email_change_area" style="display:none; height:150px;">
					<div style="float : left;">
						<ul class="h2">
							<li><?=__('Email authentication').' '.__('Complete'); ?></li>
						</ul>
					</div>
					<div>
						<div class="change_request">
							<button id="change_auth_btn_email" type="button" onclick="chage_auth('email');"><?=__('Request for changing email') ?></button>
						</div>
					</div>
					<div id="show_msg_email" style="width:70%;"></div>
				</div>
				<!-- 이메일 인증 영역-->
				<div id="email_area" style="display:none;">
					<ul class="h2">
						<li><?=__('Email authentication') ?></li>
						<li class="left">
							<label id="lbl"><input type="checkbox" id="checkbox_email" class="check"> <?=__('Confirm collection') ?> </label>
						<li>
					</ul>
					<div>
						<div class="level_2_input">
							<ul>
								<li class="gray">
									<input id="email" name="email" type="text" class="text" value="<?php if(!empty($user->email)) echo $user->email; ?>" onkeyup="check_email(this.value)" placeholder="<?=__('Enter your email') ?>" />
								</li>
								<li>
									<button id="send_code" type="button" onclick="send_email()"><?=__('Send auth code') ?></button>
									<img id="ajax_loader_email" src="/webroot/ajax-loader.gif" class="send-ajax-img"  />
								</li>
							</ul>
							<p id="error_email" class="text-pink error-message" style=""></p>
						</div>
						<div id="show_msg" style="width:50%;"></div>
						<div id="return_msg"></div>
						<div class="level_2_input">
							<ul>
								<li class="gray">
									<input type="number" id="email_code" maxlength="6"  class="text" value="" placeholder="<?=__('Enter auth code') ?>" onkeydown="only_number(this)" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>
								</li>
								<li>
									<button type="button" id="confirm_code" class="button" onclick="confirm_email_code()">  <?=__('Authenticate') ?> </button>
									<img id="ajax_loader_email_code" src="/webroot/ajax-loader.gif" class="send-ajax-img"  />
								</li>
							</ul>
							<!-- <p id="error_email_code" class="text-pink error-message">인증 번호를 6자리로 입력해주세요</p> -->
							<p id="error_email_code" class="text-pink error-message">Please enter the 6-digit verification code</p>
						</div>
						<div id="email_resp" style="width:50%;"></div>
					</div>
				</div>
				<!-- 계좌 인증 완료 영역-->
				<br>
				<div id="bank_change_area" style="display:none; height:150px;">
					<div style="float : left;">
						<ul class="h2">
							<li><?=__('Account authentication').' '.__('Complete'); ?></li>
						</ul>
					</div>
					<div >
						<div class="change_request">
							<button id="change_auth_btn_bank" type="button" onclick="chage_auth('bank');"><?=__('Request for changing bank account information') ?></button>
						</div>
					</div>
					<div id="show_msg_bank"></div>
				</div>
				<!-- 계좌 인증 영역 -->
                <div id="bank_area" style="display:none;">
                    <ul class="h2">
                        <li><?=__('Account authentication') ?></li>
                        <li>
                            <label><input id="check_bank" type="checkbox" class="check" > <?=__('Confirm collection') ?> </label>
                        <li>
                    </ul>
                    <div id="show_bank_msg"></div>
                    <div id="return_bank_msg"></div>
                    <div class="level_2_input" id="bank">
                        <ul>
                            <li class="gray">
                                <select name="bankList" id="bankList">
									<option value=""><?=__('Select a Bank') ?> </option>
                                    <?php 
										foreach($banklist as $k=>$v) { ?>
											<option value="<?=$v?>" <?php if($v == $user->bank){echo "selected";};?>><?=__($v)?></option>
									<?php } ?>
                                </select>
                            </li>
                            <li class="gray">
                                <input name="accountNum" id="accountNum" type="number" class="text" style="width:320px;" value="<?php if(!empty($user->account_number)) echo $this->Decrypt($user->account_number); ?>" placeholder="<?=__('Enter Account Number') ?>" onkeydown="only_number(this)" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>
                            </li>
                            <li>
                                <button class="button" type="button" id="bank_auth_btn" onclick="bank_auth();"><?=__('Authenticate') ?></button>
								<img id="ajax_loader_bank" src="/webroot/ajax-loader.gif" class="send-ajax-img"  />
                            </li>
                        </ul>
						<p id="error_bank" class="text-pink error-message"></p>
                        <div id="bank_id_resp" style="display:none;"></div>
                    </div>
                </div>
				<!-- OTP 인증 완료 영역-->
				<br>
				<div id="otp_change_area" style="display:none;height:150px;">
					<div style="float : left;">
						<ul class="h2">
							<li><?=__('OTP authentication').' '.__('Complete'); ?></li>
						</ul>
					</div>
					<div >
						<div class="change_request">
							<button id="change_auth_btn_otp" type="button" onclick="chage_auth('otp');"><?=__('Request for changing OTP authentication') ?></button>
						</div>
					</div>
					<div id="show_msg_otp"></div>
				</div>
				<!-- OTP 인증 영역-->
                <div id="otp_area" style="display:none;" >
					<ul class="h2">
                        <li><?=__('OTP authentication') ?></li>
                    </ul>
                    <?php 
						if($user->g_verify != 'Y'){
					?>
					<h3 style="color:#ff1a1a; font-size:24px; margin-top:50px;">
                        <?=__('How to Get OTP Certified') ?>
                    </h3>

                    <h3 style="margin-top:20px;">
                        1. <?=__('How OTP 1') ?>
                    </h3>

                    <table class="app_link_table">
                        <tr>
                            <td class="app_play_icon" >
                                <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target = "_blank"><img src="/wb/imgs/google_play_button.png" /></a>
                                <div style="margin-top:16px; font-size: 18px;">
                                    <?=__('Download android') ?>
                                </div>
                            </td><!-- Reactivate after iOS approval -->
                            <td class="app_play_icon" >
                                <a href="https://apps.apple.com/us/app/google-authenticator/id388497605" target = "_blank"><img src="/wb/imgs/app_store_button.png" /></a>
                                <div style="margin-top:16px; font-size: 18px;">
                                    <?=__('Download iphone') ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <h3>2. <?=__('How OTP 2') ?></h3>
                    <table class="app_link_table">
                        <tr>
                            <td class="scan_bar">
                                <img src="<?= $googleAuthUrl; ?>" style="border:2px solid #2c33dc; padding:12px;" />
                            </td>
                            <td class="scan_bar_right" style=" vertical-align:middle">
                                <div class="level_2_input">
                                    <ul>
                                        <li class="gray" style="margin:0">
                                            <input id="key" type="text" class="text" value="<?= $user->g_secret; ?>" class="bar_copy_input" readonly />
                                            <button type="button" class="copy-key" onclick="copyTxt()"><?=__('Copy secret key') ?></button>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </table>
					<?php } ?>
					<ul class="h2">
                        <li>
                            <label><input type="checkbox" id="chkbox_otp" class="check"><?=__('Confirm collection') ?></label>
                        <li>
                    </ul>
                    <h3>
                        <?=__('Please enter 6') ?>
                    </h3>
                    <div id="otpA" name="otpA" class="level_2_input">
                        <span id="new_error"></span>
                        <ul>
                            <li class="gray">
                                <input id="authcode" name="authcode" type="number" class="text" value="" maxlength="6" required="required"
                                       placeholder="<?=__('Enter OTP Number') ?>" onkeypress="only_number(this)"
                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>
                            </li>
                            <li>
                                <button id="otp_auth_btn" type="button" onclick="otp_auth()"><?=__('Authenticate') ?></button>
								<img id="ajax_loader_otp" src="/webroot/ajax-loader.gif" class="send-ajax-img"  />
                            </li>
                        </ul>
						<p id="error_otp" class="text-pink error-message"></p>
                    </div>
                </div>
            </div>
        </div>
		<?php
			$level_div_class = 'round_number';
			$div_completed = '';
			$completed_text = __('Authentication required');
			$div_level = '3';
			if($user->user_level >= 3){
				$level_div_class = 'round_check';
				$div_completed = 'on';
				$completed_text = __('Verification completed');
				$div_level = '';
			}
		?>
        <div id="lvl3d" class="depth <?=$div_completed;?>">
            <ul class="depth_head" level="3">
                <li>
                    <div id="lvl3" class="<?=$level_div_class;?>"><?=$div_level;?></div>
                </li>
                <li>
                    <div id="titl" class="title"><?=__('Pledge auth') ?></div>
                    <div class="desc"><?=__('KRW 300') ?></div>
                </li>
                <li>
                    <button id="btnAuth" type="button"><?=$completed_text;?></button>
                </li>
            </ul>

            <div id="level3Detail" class="detail">
                <div class="notice">
                    <?=__('Notice2') ?>
                </div>
                <p>
                    - <?=__('Level notice 3-1') ?>
                </p>

                <h1><?=__('Level three phase') ?></h1>
                <h2 id="kycStatus" style="color: #1b0552"></h2>

                <div id="bas-widget-container" style="min-height: 350px;">
                    <?= __('KYC Verification') ?>
                </div>
            </div>
        </div>
        <div class="depth">
            <ul class="depth_head" level="4">
                <li>
                    <div class="round_number">4</div>
                </li>
                <li>
                    <div class="title"><?=__('Self exam') ?></div>
                    <div class="desc"><?=__('KRW 1000') ?></div>
                </li>
                <li>
                    <button type="button"><?=__('Authentication required') ?></button>
                </li>
            </ul>

            <div class="detail">
                <div class="notice">
                    <?=__('Notice2') ?>
                </div>
                <p>
                    - <?=__('Level notice 4-1') ?><br />
                    - <?=__('Level notice 4-2') ?>
                </p>

                <div class="warning">
                    <img src="/wb/imgs/warning.png" /> <?=__('Must Level 3') ?>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="new_ipinfo_ip_chk" value="<?=$new_ipinfo_ip_chk;?>">
<input type="hidden" id="id_document_status" value="<?= $user->id_document_status; ?>">
<input type="hidden" id="email_auth" value="<?= $user->email_auth; ?>">
<input type="hidden" id="bank_verify" value="<?= $user->bank_verify; ?>">
<input type="hidden" id="g_verify" value="<?= $user->g_verify; ?>">
<script type="text/javascript">
	let pass_chk_email = false; //이메일 유효성 체크
	let pass_same_email = false; // 이메일 중복 체크
    let docStatus = $('#id_document_status').val();
	let currentLevel = 0;
	let currentDetail = null;
    if (docStatus === "P" || docStatus === "A") {
        $('#bas-widget-container').hide();
        if(docStatus === "P"){
            // $('#kycStatus').html('인증 요청이 완료 되었습니다. 잠시후 반영이 완료될 예정입니다.');
            $('#kycStatus').html('Your authentication request has been completed. The reflection will be completed after a while.');
        } else {
            // $('#kycStatus').html('비대면 생체인식인증이 완료 되었습니다.');
            $('#kycStatus').html('Non-face-to-face biometric authentication has been completed.');
        }
    } else {
        $('#bas-widget-container').show();
/*        window.BAS.AS.initFrame({
            key: "prod-PQyyrYZBZYJFPeerTTmhgVPewGblwgBg", bas_gw: "https://api.basisid.com/", container_id: "bas-widget-container",
            ui: {
                width: "100%",
                height: "705px",
                style: "",
                mobile_height: 'auto'
            },
            options: {
				"language": "en"
            },
            events:{
                onLoad: function(){
                    console.log("BAS AS loaded");
                },
                onManualCheck: function(result) {
                    if (result.status === "ok"){
                        updateStatus(result.user_hash, result.status);
                    } else {
						check_all_area();
                        alert('Error');
                    }
                },
            }
        });*/
    }
	
	/*이메일 유효성 검사*/
	function check_email(value){	
		var pattern = new RegExp("^[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*.[a-zA-Z]{2,3}$");
		if(!pattern.test(value)){
			pass_chk_email = false;
			show_error('chk_email');
			return;
		} else {
			pass_chk_email = true;
		}
		show_error('chk_email');
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
					show_error('same_email');
				}
			});
		}
	}
	/* 인증 메일 발송 */
	function send_email(){
		const email_data = $("#email").val();
		if (!show_error('chkbox_email')) return;
		if(!show_error('chk_email')) return;
		if(!show_error('same_email')) return;
		// if(confirm('인증코드를 발송하시겠습니까?')){
		if(confirm('Would you like to send a verification code?')){
			$.ajax({
				url : '/front2/users/sendEmailCode',
				data : {"email":email_data},
				type : 'post',
				dataType : 'json',
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
					$('#send_code').hide();
					$('#ajax_loader_email').show();
				},
				success : function(resp){
					$('#ajax_loader_email').hide();
					$('#send_code').text('재발송').show();
					if(resp.success == 'false'){
						$('#error_email').html(resp.message).show();
						$('#error_email').prev().css('margin-bottom','0');
					} else {
						$("#show_msg").html('<div class="alert alert-success">'+resp.message+'</div>');
						setTimeout(function(){$("#show_msg").hide();}, 5000);
						$('#email_code').focus();
					}
					return;
				}
			});
		}
	}
	/* 이메일 코드 인증 */
	function confirm_email_code(){
		const email_data = $("#email").val();
		const email_code = $("#email_code").val();
		if(!show_error('chkbox_email')) return;
		if(!show_error('chk_email')) return; 
		if(!show_error('same_email')) return;
		if(!show_error('email_code')) return;
		$.ajax({
			url : '/front2/users/confirmEmailCode',
			data : {"email":email_data,"authcode" : email_code},
			type : 'post',
			dataType : 'json',
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				$('#confirm_code').hide();
				$('#ajax_loader_email_code').show();
			},
			success : function(resp){
				$('#ajax_loader_email_code').hide();
				$('#confirm_code').show();
				if(resp.success == 'false'){
					$('#error_email_code').html(resp.message).show();
					$('#error_email_code').prev().css('margin-bottom','0');
				} else {
					$('#show_msg_email').html('<div class="alert alert-success">' + resp.message + '</div>').show();
					$('#email_auth').val('Y');
					check_email_area();
					setTimeout(function () {
						$("#show_msg_email").css('display','none');
					}, 5000);
				}
				return;
			}
		});
	}
	/* 은행 계좌 인증 */
	function bank_auth(){
		if(!show_error('chkbox_bank')) return;
		if(!show_error('chk_bank')) return;
		if(!show_error('chk_account')) return;
		const bankName = $("#bankList option:selected").val();
		const accountNumber = $("#accountNum").val();
		// if(confirm('이 계좌로 인증하시겠습니까?')){
		if(confirm('Would you like to verify with this account?')){
			$.ajax({
				type: 'post',
				url: '/front2/users/bankauth',
				data: {"bank":bankName,"account_number":accountNumber},
				dataType:'JSON',
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
					$('#bank_auth_btn').hide();
					$('#ajax_loader_bank').show();
				},
				success:function(resp){
					$('#ajax_loader_bank').hide();
					$('#bank_auth_btn').show();
					if(resp.success == 'false'){
						$('#error_bank').html(resp.message).show();
						$('#error_bank').prev().css('margin-bottom','0');
					} else {
						$('#show_msg_bank').html('<div class="alert alert-success">' + resp.message + '</div>').show();
						$('#bank_verify').val('Y');
						check_bank_area();
						setTimeout(function () {
							$("#show_msg_bank").css('display','none');
						}, 5000);
					}
					return;
				}
			});
		}
	}
	/* otp 인증 */
	function otp_auth(){
		if(!show_error('chkbox_otp')) return;
		if(!show_error('chk_otp_code')) return;
		const authcode = $("#authcode").val();
		$.ajax({
			type: 'post',
			url: '/front2/users/otpAuth',
			data: {"authcode":authcode},
			dataType:'JSON',
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				$('#otp_auth_btn').hide();
				$('#ajax_loader_otp').show();
			},
			success:function(resp){
				$('#ajax_loader_otp').hide();
				$('#otp_auth_btn').show();
				if(resp.success == 'false'){
					$('#error_otp').html(resp.message).show();
				} else {
					$('#show_msg_otp').html('<div class="alert alert-success">' + resp.message + '</div>').show();
					$('#g_verify').val('Y');
					check_otp_area();
					setTimeout(function () {
						$("#show_msg_otp").css('display','none');
					}, 5000);
				}
				return;
			}
		});
	}
	/* 이메일 영역 */
	function check_email_area(){
		const email_auth = $('#email_auth').val();
		if(email_auth != 'N'){
			$('#email_change_area').show();
			$('#email_area').hide();
		} else if(email_auth == 'N'){
			$('#email_change_area').hide();
			$('#email_area').show();
		}
		check_all_area();
	}
	/* 계좌 영역 */
	function check_bank_area(){
		const bank_verify = $('#bank_verify').val();
		if(bank_verify != 'N'){
			$('#bank_change_area').show();
			$('#bank_area').hide();
		} else if(bank_verify == 'N'){
			$('#bank_change_area').hide();
			$('#bank_area').show();
		}
		check_all_area();
	}
	/* OTP 인증 영역 */
	function check_otp_area(){
		const g_verify = $('#g_verify').val();
		if(g_verify != 'N'){
			$('#otp_change_area').show();
			$('#otp_area').hide();
		} else if(g_verify == 'N'){
			$('#otp_change_area').hide();
			$('#otp_area').show();
		}
		check_all_area();
	}
	function check_all_area(){
		const email_auth = $('#email_auth').val();
		const bank_verify = $('#bank_verify').val();
		const g_verify = $('#g_verify').val();
		let level = 1;
		if(email_auth == 'Y' && bank_verify == 'Y' && g_verify == 'Y'){
			$('#lvl2d').addClass('on');
			$('#lvl2').removeClass('round_number').addClass('round_check').html('');
            // $('#btnAut').text('인증완료');
            $('#btnAut').text('Authentication required');
			$('.detail').hide();
			currentLevel = 0;
			currentDetail = null;
			level = 2;
		} else {
			$('#lvl2d').removeClass('on');
			$('#lvl2').addClass('round_number').removeClass('round_check').html('2');
            // $('#btnAut').text('인증필요');
            $('#btnAut').text('Authentication required');
		}
		if(email_auth == 'Y' && bank_verify == 'Y' && g_verify == 'Y' && docStatus === "A"){
			level = 3;
		}
		// $('#lvl').html('고객님은 <span class="bold">레벨 '+level+'</span>까지 인증 완료했습니다.');
		$('#lvl').html('You have been certified up to <span class="bold">Level '+level+'</span>');
		return;
	}
	$(document).ready(function(){
		check_email_area();
		check_bank_area();
		check_otp_area();
		$('ul.depth_head').click(function(){
			let level = $(this).attr('level');
			let par = $(this).parent();
			if (currentLevel !== level) {
				$(currentDetail).slideUp();
				$(currentDetail).parent().removeClass('active');
				let detail = $(par).find('.detail');
				$(detail).slideDown();
				$(par).addClass('active');
				currentLevel = level;
				currentDetail = detail;
			} else {
				if(!$(par).hasClass('active')){
					let detail = $(par).find('.detail');
					$(detail).slideDown();
					$(par).addClass('active');
					currentLevel = level;
					currentDetail = detail;
				} else {
					$(par).find('.detail').slideUp();
					$(par).removeClass('active');
				}
			}
		});

		$("#bankList").change(function(){
			let bankName = $("#bankList option:selected").val();
			let accountNumber = $("#accountNum");
			if(bankName === "K Bank"){
				accountNumber.attr('maxLength',12);
			}
			if(bankName === "Kakao Payco"){
				accountNumber.attr('maxLength',13);
			}
			if(bankName === "Shinhan Bank"){
				accountNumber.attr('maxLength',12);
			}
			if(bankName === "Industrial Bank of Korea"){
				accountNumber.attr('maxLength',14);
			}
			if(bankName === "Nonghyup Bank"){
				accountNumber.attr('maxLength',16);
			}
		});
	});
	/* 인증 변경 신청 */
	function chage_auth(type){
		// if (confirm('변경 신청을 하시겠습니까? 변경신청후 승인까지는 다소 시간이 걸립니다.')) {
		if (confirm('Would you like to request a change? It takes some time for approval after request for change.')) {
			$.ajax({
				url : '/front2/users/newChangeAuth',
				type : 'post',
				data : {"type" : type},
				dataType : 'json',
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
					$('#change_auth_btn_'+type).css('display','none');
				},
				success : function(resp){
					$('#change_auth_btn_'+type).css('display','');
					let alert_class = 'alert-success';
					if(resp.success==="false"){
						alert_class = 'alert-danger';
					} 
					$("#show_msg_"+type).html('<div class="alert '+alert_class+'">' + resp.message + '</div>').show();
					setTimeout(function () {
						$("#show_msg_"+type).css('display','none');
					}, 5000);
					check_all_area();
				}
			});
		}
	}
	
	function copyTxt() {
		let copyText = document.getElementById("key");
		copyText.select();
		copyText.setSelectionRange(0, 99999)
		document.execCommand("copy");
	}

	function updateStatus(user_hash,status){
		$.ajax({
			url: '/front2/users/updatestatus',
			type: 'POST',
			data: {
				user_hash: user_hash,
				status: status
			},
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
			success: function (resp) {
				console.log(resp);
				if (resp.success === "false") {
					alert('Error');
				}
			}
		});
	}
	/* 에러 메세지 */
	function show_error(type){
		if(type == 'chkbox_email'){
			if ($('input:checkbox[id="checkbox_email"]').is(":checked") == false) {
				// $('#error_email').html('개인정보 수집 및 이용 동의를 체크해주세요').show();
				$('#error_email').html('Please check the consent to collection and use of personal information').show();
				$('#error_email').prev().css('margin-bottom','0');
				return false;
			} else {
				$('#error_email').html('').hide();
				$('#error_email').prev().css('margin-bottom','20px');
				return true;
			}		 
		}
		if(type == 'chk_email'){
			if(!pass_chk_email){
				$('#error_email').html('<?=__("Please enter a valid email address");?>').show();
				$('#error_email').prev().css('margin-bottom','0');
				$('#email').focus();
				return false;
			} else {
				$('#error_email').html('').hide();
				$('#error_email').prev().css('margin-bottom','20px');
				return true;
			}			 
		}
		if(type == 'same_email'){
			if(!pass_same_email){
				$('#error_email').html('<?=__("This email is already registered.");?>').show();
				$('#error_email').prev().css('margin-bottom','0');
				$('#email').focus();
				return false;
			} else {
				$('#error_email').html('').hide();
				$('#error_email').prev().css('margin-bottom','20px');
				return true;
			}			 
		}
		if(type == 'email_code'){
			if($('#email_code').val().length < 6){
				$('#error_email_code').show();
				$('#error_email_code').prev().css('margin-bottom','0');
				$('#email_code').focus();
				return false;
			} else {
				$('#error_email_code').hide();
				$('#error_email_code').prev().css('margin-bottom','20px');
				return true;
			}	
		}
		if(type == 'chkbox_bank'){
			if ($('input:checkbox[id="check_bank"]').is(":checked") == false) {
				// $('#error_bank').html('개인정보 수집 및 이용 동의를 체크해주세요').show();
				$('#error_bank').html('Please check the consent to collection and use of personal information').show();
				return false;
			} else {
				$('#error_bank').html('').hide();
				return true;
			}	
		}
		if(type == 'chk_bank'){
			if($('#bankList option:selected').val() == ''){
				// $('#error_bank').html('인증 은행을 선택해주세요').show();
				$('#error_bank').html('Please select a certified bank').show();
				return false;
			} else {
				$('#error_bank').html('').hide();
				return true;
			}
		}
		if(type == 'chk_account'){
			if($('#accountNum').val().length < 8 ){
				// $('#error_bank').html('계좌번호를 확인해주세요').show();
				$('#error_bank').html('Please check your account number').show();
				$('#accountNum').focus();
				return false;
			} else {
				$('#error_bank').html('').hide();
				return true;
			}
		}
		if(type == 'chkbox_otp'){ 
			if ($('input:checkbox[id="chkbox_otp"]').is(":checked") == false) {
				// $('#error_otp').html('개인정보 수집 및 이용 동의를 체크해주세요').show();
				$('#error_otp').html('Please check the consent to collection and use of personal information').show();
				return false;
			} else {
				$('#error_otp').html('').hide();
				return true;
			}	
		}
		if(type == 'chk_otp_code'){
			if($('#authcode').val().length < 6 ){
				// $('#error_otp').html('OTP 번호를 6자리로 입력해주세요').show();
				$('#error_otp').html('Please enter your OTP number with 6 digits').show();
				$('#authcode').focus();
				return false;
			} else {
				$('#error_otp').html('').hide();
				return true;
			}
		}
	}
</script>