<div class="login-box">
    <div class="login-logo">
        <a href="/tech/dashboard"><b><?php echo $coinNameStatic; ?></b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <?php echo $this->Form->create('login');?>
        <?php echo $this->Flash->render(); ?>
            <div class="form-group has-feedback">
                <input type="text" name="username" class="form-control" placeholder="Username/Email">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="password" class="form-control" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
            </div>
        </form>
    </div>
</div>