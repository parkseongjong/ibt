<style>
.input-style{border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><?= __('Generate Passwords'); ?></h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home'); ?></a></li>
            <li class="active"><?= __('Generate Passwords'); ?></li>
        </ol>
    </section>
    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="clearfix"></div>
             <div class="w3agile-validation w3ls-validation ">
				<div class="w3agile-validation w3ls-validation mt20">
                    <div class="agile-validation agile_info_shadow">
                        <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                            <div class="input-info">
                                <h3 class="w3_inner_tittle two"><?= __('Generate Passwords'); ?></h3>
                            </div>
                            <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
								<form action="" method="POST" id="frm" enctype="multipart/form-data">
								<div class="col-md-12 col-md-offset-3">
									<?= $this->Flash->render(); ?>
									<div class="clearfix"></div>
                                    <div class="col-md-6 form-group valid-form">
                                        <? __('Upload File: '); ?>
										<input type="file" id="csv_file" name="csv_file" value="" class="form-control input-style " required>
                                    </div>
<!--                                    <div class="col-md-4 col-sm-3 col-xs-12">-->
<!--                                        Private Key:-->
<!--                                        --><?php // echo $this->Form->input('pkey',array('id'=>'pkey','placeholder'=>'Please enter a private key','class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text',"required"=>true)); ?>
<!--                                    </div>-->
									
									<div class="clearfix"></div>
									<div class="form-group col-md-12" style="margin-top:15px;">
                                        <?php  echo $this->Form->button('Generate', ['type' => 'submit','class'=>'btn btn-primary']); ?>
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

	// function submit_check(){
	// 	if($('#csv_file').val() == ''){
	// 		$('#csv_file').focus();
	// 		return;
	// 	}
	// 	$('#add_btn').hide();
	// 	$('#frm').submit();
	// }

</script>
