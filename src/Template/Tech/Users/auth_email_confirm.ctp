<style>
	.login-page{background: #312f2c;}
</style>
<div class="login-box" style="width: 450px;">
    <div class="login-logo">
        <a href="/tech/dashboard"><b style="color:#f4f4f4;"><?php echo $coinNameStatic; ?></b></a>
    </div>
    <div class="login-box-body">
        <p class="login-box-msg">지속적인 로그인 실패로 인해 해당 계정은 차단 되었습니다. <br>해당 계정의 메일 인증 후에 계정 차단 해제가 가능합니다.</p>
        <?php echo $this->Form->create('',['method'=>'post']);?>
            <div class="form-group has-feedback">
                <input type="text" name="username" class="form-control" placeholder="Username/Email" required>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="auth_code" maxlength="6" class="form-control" placeholder="Auth Code" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
		        <?php echo $this->Flash->render(); ?>
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Confirm</button>
                </div>
            </div>
        </form>
    </div>
</div>
