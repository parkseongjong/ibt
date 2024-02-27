<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'addadmin']);  ?>"> <?= __('Add Administrator');?></a> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home');?></a></li>
            <li class="active"><a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'addadmin']);  ?>"> <?= __('Add Administrator');?></a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="clearfix"></div>
            <div class="w3agile-validation w3ls-validation ">
                <div class="agile-validation agile_info_shadow">
                    <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                        <div class="  form-body form-body-info">
							<?php echo $this->Form->create('',array('class'=>'form-horizontal form-label-left','method'=>'post','id'=>'form'));?>
                                <?= $this->Flash->render() ?>
									<p class="text-danger m-t-20 m-b-20" style="text-align: left; margin-left: 284px;" >* <?=__('Password Info')?></p>
									<div class="item form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" for="name"><?= __('Level: ');?>  <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										<?php  echo $this->Form->input('level_id',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"select","options"=>[""=>__("Please select level")]+$levelList,"required"=>true)); ?>
										</div>
									  </div>
									  <br/>
                                    <div class="item form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" for="name"><?= __('아이디 ');?> <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										 <?php  echo $this->Form->input('username',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"text","required"=>true)); ?>
										</div>
									  </div>
                                     <div class="item form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" for="name"><?= __('Name: ');?> <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										 <?php  echo $this->Form->input('name',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"text","required"=>true)); ?>
										</div>
									  </div>
									   <div class="item form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" for="name"><?= __('Email: ');?> <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										 <?php  echo $this->Form->input('email',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"email","required"=>true)); ?>
										</div>
									  </div>
									  <div class="item form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" for="name"><?=__('Password: ');?>  <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										 <?php  echo $this->Form->input('password',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"password","required"=>true,'onkeyup'=>'check_password(1,this.value)')); ?>
										 <p id="pw_error_text1" class="text-danger" style="display:none;"></p>
										</div>
									  </div>
									  <div class="item form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" for="name"><?=__('Confirm Password: ');?>  <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										 <?php  echo $this->Form->input('confirm_password',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"password","required"=>true,'onkeyup'=>'check_password(2,this.value)')); ?>
										 <p id="pw_error_text2" class="text-danger" style="display:none;"></p>
										</div>
									  </div>
									   <div class="item form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" for="name"><?= __('Phone Number: ');?>  <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										 <?php  echo $this->Form->input('phone_number',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"text","required"=>true)); ?>
										</div>
									  </div>
                                    <div class="ln_solid"></div>
								  <div class="form-group">
									<div class="col-md-6 col-md-offset-2">
										<?php  echo $this->Form->button(__('Submit'), ['type' => 'button','class'=>'btn btn-success','onclick'=>'validate()']); ?>
									</div>
								  </div>
							</form>
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
	const regex = new RegExp("^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,20}$");
	const pattern1 = /[0-9]/;
    const pattern2 = /[a-zA-Z]/;
    const pattern3 = /[~!@\#$%<>^&*]/;     // 원하는 특수문자 추가 제거
	/* 비밀번호 확인 */
	function check_password(check_type,value){
		if(check_type == 1){
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
		if(check_type == 2){
			pass_same_chk = !(value != $('#password').val());
			return pass_error('pass_same_chk');
		}
	}
	/* 비밀번호 에러 메세지 */
	function pass_error(check_type){
		if(check_type == 'pass_chk'){
			if(!pass_chk){ // 특수 문자 관련 메세지
				$('#pw_error_text1').html('비밀번호 유의사항을 확인해주세요').show();
			} else {
				$('#pw_error_text1').html('').hide();
			}			 
		}
		if(check_type == 'pass_same_chk'){ // 패스워드 불일치
			if(!pass_same_chk){
				$('#pw_error_text2').html('비밀번호가 일치하지 않습니다').show();
			} else {
				$('#pw_error_text2').html('').hide();
			}
		}
	}
	function validate(){
		if(!pass_chk){
			pass_error('pass_chk');
			return;
		}
		if(!pass_same_chk){
			pass_error('pass_same_chk');
			return;
		}
		$('#form').submit();
	}
	// 1. 아이디 5글자 이상
	// 2. 이름
	// 3. 전화번호 숫자만
</script>