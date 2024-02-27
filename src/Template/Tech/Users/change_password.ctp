<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Change Password </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Change Password</li>
        </ol>
    </section>
    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="clearfix"></div>
                <div class="w3agile-validation w3ls-validation mt20">
                    <div class="agile-validation agile_info_shadow">
                        <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                            <div class="input-info">
                                <h3 class="w3_inner_tittle two">Change Password:</h3>
								<p class="text-danger" style="text-align: left;margin-left: 284px;" >* <?=__('Password Info')?></p>
                            </div>
                            <div class="  form-body form-body-info">
                                <?php echo $this->Form->create('',array('id'=>'password_form','class'=>'form-horizontal form-label-left','novalidate','method'=>'post'));?>	
                                <?php echo $this->Flash->render(); ?>
                                <div class="item form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="name">Old Password <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <?php  echo $this->Form->input('old_password',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"password","onkeyup"=>"check_password(1,this.value)")); ?>
										<p id="pw_error_text1" class="text-danger" style="display:none;"></p>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="email">New Password <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <?php  echo $this->Form->input('new_password',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"password","onkeyup"=>"check_password(2,this.value)")); ?>
										<p id="pw_error_text2" class="text-danger" style="display:none;"></p>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="email">Confirm Password <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <?php  echo $this->Form->input('confirm_password',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"password","onkeyup"=>"check_password(3,this.value)")); ?>
										<p id="pw_error_text3" class="text-danger" style="display:none;"></p>
									</div>
                                </div>

                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-2">
                                        <?php echo $this->Form->button('Update password', ['type' => 'button','class'=>'btn btn-success','onclick' =>'validate()']); ?>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
	let pass_same_chk = false; // 패스워드 - 패스워드 확인 같은지 체크
	let pass_chk = false; // 특수문자, 영문자, 숫자 포함 되어 있는지 체크
	let origin_pass_chk = false; // 현재 비밀번호 확인
	const regex = new RegExp("^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,20}$");
	const pattern1 = /[0-9]/;
    const pattern2 = /[a-zA-Z]/;
    const pattern3 = /[~!@\#$%<>^&*]/;     // 원하는 특수문자 추가 제거
	/* 비밀번호 확인 */
	function check_password(check_type,value){
		if(check_type == 1){
			if(value.length < 6){
				origin_pass_chk = false;
			} else {
				origin_pass_chk = true;
			}
			return pass_error('origin_pass_chk');
		}
		if(check_type == 2){
			pass_same_chk = false;
			if(value.length < 8){
				pass_chk = false;
			} else if (value.length >= 8 && value.length < 10){
				if (!regex.test(value)) {
					pass_chk = false;
				} else {
					pass_chk = true;
				} 
			} else if(value.length >= 10){
				 if( (!pattern1.test(value) && !pattern2.test(value) ) || (!pattern1.test(value) && !pattern3.test(value)) || (!pattern2.test(value) && !pattern3.test(value))) {
					pass_chk = false;
				 } else {
					pass_chk = true;
				 }
			}
			return pass_error('pass_chk');
		}
		if(check_type == 3){
			pass_same_chk = !(value != $('#new-password').val());
			return pass_error('pass_same_chk');
		}
	}
	/* 비밀번호 에러 메세지 */
	function pass_error(check_type){
		if(check_type == 'origin_pass_chk'){ // 현재 비밀번호
			if(!origin_pass_chk){
				$('#pw_error_text1').html('현재 비밀번호를 6자리 이상 입력해주세요').show();
			} else {
				$('#pw_error_text1').html('').hide();
			}			 
		}
		if(check_type == 'pass_chk'){
			if(!pass_chk){ // 특수 문자 관련 메세지
				$('#pw_error_text2').html('비밀번호 유의사항을 확인해주세요').show();
			} else {
				$('#pw_error_text2').html('').hide();
			}			 
		}
		if(check_type == 'pass_same_chk'){ // 패스워드 불일치
			if(!pass_same_chk){
				$('#pw_error_text3').html('비밀번호가 일치하지 않습니다').show();
			} else {
				$('#pw_error_text3').html('').hide();
			}
		}
	}
	function validate(){
		if(!origin_pass_chk){
			pass_error('origin_pass_chk');
			return;
		}
		if(!pass_chk){
			pass_error('pass_chk');
			return;
		}
		if(!pass_same_chk){
			pass_error('pass_same_chk');
			return;
		}
		$('#password_form').submit();
	}
</script>