<?php echo $this->Form->create('',array('method'=>'post','id'=>'leaving_form'));?>
<div class="content-container">
	<div class="content-inner">
		<div class="caution-container">
			<div class="caution-title"> 탈퇴하기 전 아래 유의사항을 확인해 주세요. </div>
			<div class="caution-list-wrap">
				<ul class="caution-list">
					<li>사용자가 서비스 회원을 탈퇴할 경우 회사는 부정이용을 방지하기 위하여 1년간 개인정보를 보관합니다.</li>
					<li>이 경우 개인정보는 식별할 수 없는 상태로 보관되며 동일한 ID로 재가입이 불가합니다.</li>
					<li>결제 처리에 대한 서비스는 전자상거래 등에서의 소비자보호에 관한 법률 등 관계법령의 규정에 의거하여, 자산이 남아 있을 경우 자산포기각서 및 수기 서명 작성 후 탈퇴처리가 완료됩니다.</li>
				</ul>
			</div>
			<div class="caution-check-wrap">
				<input type="checkbox" id="agree" class="caution-checkbox" onchange="check_agree()">
				<label for="agree" class="caution-checkbox-label"> 유의 사항을 모두 확인했으며, 이에 동의합니다. </label>
				<p id="error_msg1" style="display:none;">탈퇴 유의 사항을 동의해주세요</p>
			</div>
		</div>
		<div class="form-container">
			<div class="form-top-area">
				<div class="user-id-form">
					<div class="user-label user-id-label">아이디</div>
					<div id="user-id" class="user-id-value"><?=$username;?></div>
				</div>
				<div class="user-password-form">
					<div class="user-label user-password-label">비밀번호 확인</div>
					<input type="password" id="user-password" name="password" class="user-password-input" onkeyup="check_password(this.value)" placeholder="비밀번호 입력" /> </div>
				<div class="warn-area" id="error_msg2" style="display:none;"> 비밀번호를 입력해 주세요. </div>
			</div>
			<?= $this->Flash->render(); ?>
			<button type="button" class="form-button" onClick="onClickDeleteAccountButton()"> 탈퇴하기 </button>
		</div>
	</div>
</div>
<?php echo $this->Form->end();?>
<script>
	let agree_chk = false;
	let pass_chk = false;
	function onClickDeleteAccountButton() {
		if(!error_msg('agree_chk')) return;
		if(!error_msg('pass_chk')) return;
		$('#leaving_form').submit();
	}
	/* 비밀번호 확인 */
	function check_password(value){
		if(value.length < 6){
			pass_chk = false;
		} else {
			pass_chk = true;
		}
		return error_msg('pass_chk');
	}
	function check_agree(){
		const checked = document.querySelector('#agree').checked;
		agree_chk = checked;
		return error_msg('agree_chk');
	}
	/* error msg */
	function error_msg(type){
		if(type == 'pass_chk'){
			if(!pass_chk){
				$('#error_msg2').show();
				return false;
			} else {
				$('#error_msg2').hide();
				return true;
			}
		}
		if(type == 'agree_chk'){
			if(!agree_chk){
				$('#error_msg1').show();
				return false;
			} else {
				$('#error_msg1').hide();
				return true;
			}
		}
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
  padding: 100px 20px;
}
.content-container .content-inner .caution-container {
  background-color: #f6f6f6;
  padding: 50px 80px 38px 80px;
  max-width: 700px;
  margin: 0 auto 65px;
}
.content-container .content-inner .caution-container .caution-title {
  text-align: center;
  font-size: 24px;
  margin-bottom: 48px;
  font-weight: 700;
}
.content-container .content-inner .caution-container .caution-list-wrap {
  margin-bottom: 50px;
}
.content-container .content-inner .caution-container .caution-list-wrap .caution-list {
  font-size: 18px;
  color: #333;
  line-height: 1.5;
}
.content-container .content-inner .caution-container .caution-list-wrap .caution-list > li {
  list-style: disc;
}
.content-container .content-inner .caution-container .caution-check-wrap {
  text-align: center;
}
.content-container .content-inner .caution-container .caution-check-wrap .caution-checkbox {
  width: 24px;
  height: 24px;
  vertical-align: middle;
}
.content-container .content-inner .caution-container .caution-check-wrap .caution-checkbox-label {
  font-size: 18px;
  color: #2a2a2a;
  vertical-align: middle;
}
.content-container .content-inner .caution-container .caution-check-wrap p {
  color:#ec0505;
}
.content-container .content-inner .form-container {
  display: flex;
  flex-direction: column;
  align-items: center;
}
.content-container .content-inner .form-container .form-top-area {
  margin-bottom: 90px;
}
.content-container .content-inner .form-container .form-top-area .user-id-form > .user-label,
.content-container .content-inner .form-container .form-top-area .user-password-form > .user-label {
  font-size: 22px;
  font-weight: 700;
  flex-basis: 200px;
}
.content-container .content-inner .form-container .form-top-area .user-id-form {
  display: flex;
  margin-bottom: 30px;
  align-items: center;
}
.content-container .content-inner .form-container .form-top-area .user-id-form .user-id-label {
}
.content-container .content-inner .form-container .form-top-area .user-id-form .user-id-value {
  font-size: 22px;
  color: #6738ff;
  font-weight: 700;
}
.content-container .content-inner .form-container .form-top-area .user-password-form {
  display: flex;
  align-items: center;
}
.content-container .content-inner .form-container .form-top-area .user-password-form .user-password-label {
}
.content-container .content-inner .form-container .form-top-area .user-password-form .user-password-input {
  font-size: 22px;
  padding: 10px 20px;
  outline: none;
  border: 2px solid #e0e0e0;
  width: 500px;
  flex: 1;
}
.content-container .content-inner .form-container .form-top-area .warn-area {
  color: #fd0000;
  margin-left: 205px;
}
.content-container .content-inner .form-container .form-button {
  background-color: #6738ff;
  padding: 15px 0;
  width: 250px;
  font-size: 18px;
  color: #fff;
  border: 2px solid #6738ff;
  cursor: pointer;
}

@media (max-width: 990px) {
  .wrapper {
  }
  .content-container {
  }
  .content-container .content-inner {
    padding: 100px 10px;
  }
  .content-container .content-inner .caution-container {
    padding: 50px 20px 50px 30px;
  }
  .content-container .content-inner .caution-container .caution-title {
    font-size: 18px;
  }
  .content-container .content-inner .caution-container .caution-list-wrap {
  }
  .content-container .content-inner .caution-container .caution-list-wrap .caution-list {
    font-size: 13px;
  }
  .content-container .content-inner .caution-container .caution-list-wrap .caution-list > li {
    margin-bottom: 10px;
  }
  .content-container .content-inner .caution-container .caution-check-wrap {
  }
  .content-container .content-inner .caution-container .caution-check-wrap .caution-checkbox {
    width: 15px;
    height: 15px;
  }
  .content-container .content-inner .caution-container .caution-check-wrap .caution-checkbox-label {
    font-size: 13px;
  }
  .content-container .content-inner .form-container {
  }
  .content-container .content-inner .form-container .form-top-area {
  }
  .content-container .content-inner .form-container .form-top-area .user-id-form > .user-label,
  .content-container .content-inner .form-container .form-top-area .user-password-form > .user-label {
    flex-basis: 0;
    margin-bottom: 10px;
    font-size: 16px;
  }
  .content-container .content-inner .form-container .form-top-area .user-id-form {
    flex-direction: column;
  }
  .content-container .content-inner .form-container .form-top-area .user-id-form .user-id-label {
  }
  .content-container .content-inner .form-container .form-top-area .user-id-form .user-id-value {
    font-size: 16px;
  }
  .content-container .content-inner .form-container .form-top-area .user-password-form {
    flex-direction: column;
    
  }
  .content-container .content-inner .form-container .form-top-area .user-password-form .user-password-label {
  }
  .content-container .content-inner .form-container .form-top-area .user-password-form .user-password-input {
    width: 200px;
    font-size: 16px;
  }
  .content-container .content-inner .form-container .form-top-area .warn-area {
    margin-left: 5px;
  }
  .content-container .content-inner .form-container .form-button {
    width: 200px;
    font-size: 16px;
  }
}
</style>