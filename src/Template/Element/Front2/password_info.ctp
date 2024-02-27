<?php echo $this->Form->create('',['method'=>'POST']);?>
<?php echo $this->Form->end();?>
<div class="password-reset-modal-container" id="password_reset_modal" style="display:none;">
	<div class="password-reset-modal">
		<div class="password-reset-modal-top">
		    <div class="password-reset-modal-title">
                Guidance on resetting the password
			</div>
			<div class="password-reset-modal-desc">
                If you haven't changed your password for more than 6 months to protect your personal information and prevent damage caused by personal information theft, please change it periodically.
		    </div>
		</div>
		<div class="password-reset-modal-bottom">
			<button type="button" class="check-90d-button" onClick="changeNextTime()">Change it 90 days later.</button>
			<button type="button" class="confirm-button" onClick="onClickConfirmButton()">Check</button>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		checkPasswordDate();
	});
	function onClickCheck90DayButton() {
		closePasswordModal();
	}
	function onClickConfirmButton() {
		window.location.href='/front2/users/new-change-password';
	}
	function closePasswordModal(){
		$('#password_reset_modal').hide();
	}
	function openPasswordModal(){
		$('#password_reset_modal').show();
	}
	function changeNextTime(){
		$.ajax({
			type: 'post',
			url: "/front2/exchange/change-next-time",
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
			data: {},
			success: function (resp) {
				if(resp.status == '200'){
					closePasswordModal();
				} else {
					console.log(resp.message);
					closePasswordModal();
				}
			}
		});
	}
	function checkPasswordDate(){
		$.ajax({
			type: 'post',
			url: "/front2/exchange/get-password-date",
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
			data: {},
			dataType:"json",
			success: function (resp) {
				if(resp.status == '5000'){
					onClickConfirmButton();
				}
				if(resp.status == '5001'){
					openPasswordModal();
				}
				if(resp.status == '200'){
					closePasswordModal();
				}
			}
		});
	}
</script>

<style>
.password-reset-modal-container {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.3);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 999;
}
.password-reset-modal-container .password-reset-modal {
  background-color: #fff;
  color: #000;
  display: flex;
  flex-direction: column;
  width: 100%;
  max-width: 600px;
  margin: 0 10px;
}
.password-reset-modal-container .password-reset-modal .password-reset-modal-top {
  flex: 1;
  padding-top: 100px;
  padding: 100px 50px;
}
.password-reset-modal-container .password-reset-modal .password-reset-modal-top .password-reset-modal-title {
  font-size: 34px;
  margin-bottom: 50px;
  font-weight: 700;
  text-align: center;
}
.password-reset-modal-container .password-reset-modal .password-reset-modal-top .password-reset-modal-desc {
  font-size: 20px;
  line-height: 1.5;
  text-align: center;
  word-break: keep-all;
}
.password-reset-modal-container .password-reset-modal .password-reset-modal-bottom {
  height: 65px;
  display: flex;
}
.password-reset-modal-container .password-reset-modal .password-reset-modal-bottom .check-90d-button {
  flex: 1;
  border: none;
  cursor: pointer;
  background-color: #eeeeee;
  color: #000;
  font-size: 20px;
}
.password-reset-modal-container .password-reset-modal .password-reset-modal-bottom .confirm-button {
  flex: 1;
  border: none;
  cursor: pointer;
  background-color: #000;
  color: #fff;
  font-size: 20px;
}

@media (max-width: 990px) {
  .password-reset-modal-container {}
  .password-reset-modal-container .password-reset-modal {}
  .password-reset-modal-container .password-reset-modal .password-reset-modal-top {
    padding: 60px 10px;
  }
  .password-reset-modal-container .password-reset-modal .password-reset-modal-top .password-reset-modal-title {
    font-size: 24px;
  }
  .password-reset-modal-container .password-reset-modal .password-reset-modal-top .password-reset-modal-desc {
    font-size: 14px;
  }
  .password-reset-modal-container .password-reset-modal .password-reset-modal-bottom {}
  .password-reset-modal-container .password-reset-modal .password-reset-modal-bottom .check-90d-button {
    font-size: 14px;
  }
  .password-reset-modal-container .password-reset-modal .password-reset-modal-bottom .confirm-button {
    font-size: 14px;
  }
}
</style>