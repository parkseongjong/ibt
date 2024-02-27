<style>
	.login-page{background: #312f2c;}
</style>
<div class="login-box" style="width: 450px;">
    <div class="login-logo">
        <a href="/tech/dashboard"><b style="color:#f4f4f4;"><?php echo $coinNameStatic; ?></b></a>
    </div>
    <div class="login-box-body">
        <p class="login-box-msg">2차 인증 페이지</p>
        <?php echo $this->Form->create('',['method'=>'post']);?>
			<input type="hidden" id="loginTokenUserId" name="loginTokenUserId" value="<?=$this->Encrypt($this->request->session()->read('loginTokenUserId'));?>">
            <div class="form-group has-feedback">
                <input type="text" name="otp_number" maxlength="6" class="form-control" placeholder="Please Enter OTP Number" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
		        <?php echo $this->Flash->render(); ?>
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Submit</button>
                </div>
				<div class="col-xs-4" style="float: right;">
                    <a href="/tech/users/otpinfo" >OTP 등록</a>
                </div>
            </div>
        </form>
    </div>
</div>
