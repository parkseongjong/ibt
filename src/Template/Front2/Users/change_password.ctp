<div id="_hidden_frame" style="position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgb(0 0 0 / 0.7);
    z-index: 11;">
    <link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/login2.css" />
    <div class="container">
        <div id="_outer_box" style=" display:none;" class="login_box login_box_popop">
            <div class="form-field">
                <button id="x" class="button" onclick="hideMsgWindow()" style="position:absolute; top:2%; right:2%; cursor: pointer; display: inline-block;">X</button>
            </div>

            <div class="welcome2">
                <?=__('Password Settings') ?>
            </div>
            <div class="com_logo5">
                <?=__('Please set a new password') ?>
            </div>

            <?php echo $this->Form->create($users,array('id'=>'password_form','enctype'=>'multipart/form-data','class'=>'form-horizontal form-label-left','method'=>'post'));?>
            <?= $this->Flash->render() ?>
            <ul class="label" style="margin-top:30px">
                <li id="msg_pass_check"></li>
            </ul>
            <div class="change-pw">
            <div class="form-field">
                <input id="old_password" name="old_password"  required value="" type="password" class="input" data-type="password" onKeyUp="check_password()" placeholder="<?=__('Enter old password') ?>" pattern="[0-9]*" inputmode="numeric" onkeypress="return isNumberKey(this,event);" />
            </div>
            <div class="form-field">
                <input id="new_password" name="new_password" onblur="new_pass_fun();" required value="" maxlength="6" type="password" class="input" data-type="password" onKeyUp="check_password()" pattern="[0-9]*" inputmode="numeric" onkeypress="return isNumberKey(this,event);" placeholder="<?=__('Enter a new password') ?> " />
            </div>
            <div class="form-field">
                <input id="confirm_password" name="confirm_password" onblur="confirm_pass_fun();" required value="" maxlength="6" type="password" class="input" data-type="password" pattern="[0-9]*" inputmode="numeric" onKeyUp="check_password()" onkeypress="return isNumberKey(this,event);" placeholder="<?=__('Confirm password') ?>" />
            </div>
            <div class="form-submit">
                <button type="submit" id="submitpass" name="submitpass" class="button" onclick="validate()"><?=__('Change password') ?> </button>
            </div>
            </div>
        </form>
            </div>
        </div>
    </div>


<script>
    function isNumberKey(txt, evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode === 46) {
            //Check if the text already contains the . character
            return txt.value.indexOf('.') === -1;
        } else {
            if (charCode > 31 &&
                (charCode < 48 || charCode > 57))
                return false;
        }
        return true;
    }

    $(document).ready(function (){
        $("#_hidden_frame").show();
        $("#_outer_box").fadeIn();

        $('#confirm_password').on('keyup',function(){
           if($('#confirm_password').val().length <6){
               $('#confirm_password').addClass('input_error');
           } else {
               $('#confirm_password').removeClass('input_error').addClass('input_complete');
           }

           if($('#confirm_password').val() === ''){
               $('#confirm_password').removeClass('input_error').removeClass('input_complete');
           }
        });

        $('#new_password').on('keyup',function(){
            if($('#new_password').val().length <6){
                $('#new_password').addClass('input_error');
            } else {
                $('#new_password').removeClass('input_error').addClass('input_complete');
            }

            if($('#new_password').val() === ''){
                $('#new_password').removeClass('input_error').removeClass('input_complete');
            }
        });

    });

    function hideMsgWindow() {
        if(document.getElementById('_outer_box')) $('#_outer_box').hide();
        $('#_hidden_frame').hide();
        document.location.href = "/front2/Users/security";
    }

    function check_passwordOP() {
        var lenOP = $('#old_password').val().length;
        var passOP = $('#old_password').val();

        if (lenOP<6 || lenOP>30) {
            $('#old_password').addClass('input_error');
        } else {
            // if(!checkPass(passOP)) {
            //     $('#old_password').addClass('input_error');
            // } else{
                $('#old_password').removeClass('input_error').addClass('input_complete');
            // }
        }
    }

    function checkPasswordNP(){
        var lenNP = $('#new_password').val().length;
        var passNP = $('#new_password').val();
        if (lenNP<6) {
            $('#new_password').addClass('input_error');
        } else {
            if(!checkPass(passNP)) {
                $('#new_password').addClass('input_error');
            } else{
                $('#new_password').removeClass('input_error').addClass('input_complete');
            }
        }
    }

    function checkPasswordCP(){
        var lenCP = $('#confirm_password').val().length;
        var passCP = $('#confirm_password').val();
        if (lenCP<6) {
            $('#confirm_password').addClass('input_error');
        } else {
            if(!checkPass(passCP)) {
                $('#confirm_password').addClass('input_error');
            } else{
                $('#confirm_password').removeClass('input_error').addClass('input_complete');
            }
        }
    }

    function validate(){
        var passNP = $('#new_password').val();
        var passCP = $('#confirm_password').val();
        var passOP = $('#old_password').val();

        if(!checkPass(passNP) || $('#new_password').val().length <6){
            check_password();
            $('#new_password').addClass('input_error');
            //return false;
        }else {
            $('#new_password').removeClass('input_error').addClass('input_complete');
           // return true;
        }

        if(!checkPass(passCP) || $('#confirm_password').val().length <6){
            check_password();
            $('#confirm_password').addClass('input_error');
           // return false;
        }
        else {
            $('#confirm_password').removeClass('input_error').addClass('input_complete');
           // return true;
        }
    }

    // function checkPass(str){
    //     var re = /^(?=.*?[a-zA-Z])(?=.*?[0-9]).{8,}$/;
    //     return re.test(str);
    // }

    function new_pass_fun() {
        if(!checkPass($('#new_password').val()) || $('#new_password').val().length <6){
            check_password();
            $('#msg_pass_check').html("<?=__('Password rule') ?>");
            $('#new_password').addClass('input_error');

        } else{
            $('#msg_pass_check').html("");
            $('#new_password').removeClass('input_error').addClass('input_complete');
        }
    }

    function confirm_pass_fun() {
        if($('#confirm_password').val()!==$('#new_password').val()){
            $('#confirm_password').addClass('input_error');
        }
        if(!checkPass($('#confirm_password').val()) || $('#confirm_password').val().length <6){
            check_password();
            $('#msg_pass_check').html("<?=__('Password rule') ?>");
            $('#confirm_password').addClass('input_error');
        } else{
            $('#msg_pass_check').html("");
            $('#confirm_password').removeClass('input_error').addClass('input_complete');
        }
    }


   // function old_pass_fun() {
        //if(!checkPass($('#old_password').val())){
        //    check_password();
        //    $('#msg_pass_check').html("<?//=__('Password rule') ?>//");
        //    $('#old_password').addClass('input_error');
        //
        //} else{
        //    $('#msg_pass_check').html("");
        //    $('#old_password').removeClass('input_error');
        //}
   // }

    function checkPass(str){
        return true;
        var re = /^[0-9]{6,}$/;///^(?=.*?[a-zA-Z])(?=.*?[0-9]).{6,}$/;
        return re.test(str);
    }
</script>