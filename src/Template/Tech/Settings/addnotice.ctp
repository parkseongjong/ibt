<style>
.input-style{border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Notice </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Notice</li>
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
                                <h3 class="w3_inner_tittle two">Add Notice :</h3>
                            </div>
                            <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
								<?php echo $this->Form->create($conversion,array('method'=>'post','enctype'=>'multipart/form-data')); ?>
								<div class="col-md-12 col-md-offset-3">
                                    <?= $this->Flash->render() ?>
									<div class="col-md-6 form-group valid-form">
										Select Language :
										 <?php  
										 echo $this->Form->input('lang', ['type'=>'select','options'=> array(
											'en_US'=>"ENG",
											'ko_KR'=>"KOR"
											),'label'=>false,'class'=>"form-control input-style required"]);
										 ?>
                                    </div>
									<div class="clearfix"></div>
                                    <div class="col-md-6 form-group valid-form">
										Category :
										 <?php  
										 echo $this->Form->input('category', ['type'=>'select','options'=> array(
											'notice'=>__('Boardcategory notice'),
											'general'=>__('Boardcategory general')
											),'label'=>false,'class'=>"form-control input-style required"]);
										 ?>
                                    </div>
									<div class="clearfix"></div>
									<div class="col-md-6 form-group valid-form">
                                        Subject:
                                        <?php  echo $this->Form->input('subject',array('class' => 'form-control input-style required','label' =>false,"type"=>"text","id"=>"subject","required"=>true));?>
                                    </div>
									<div class="clearfix"></div>
									<div class="col-md-6 form-group valid-form">
									Contents:
										<?php echo $this->Form->input('contents', ['type'=>'textarea', 'label'=>false, 'required'=>true,'id'=>'contents','class' => 'form-control input-style required']); ?>                                    </div>
									<div class="clearfix"></div>
									<div class="col-md-6 form-group valid-form">
									File Attachment	:
                                        <?php  echo $this->Form->input('icon_img',array('class' => 'form-control input-style required','label' =>false,"type"=>"file","id"=>"icon_img"));?>
                                    </div>
									<div class="clearfix"></div>
									  <div class="form-group col-md-12">
										<?php  echo $this->Form->button('Submit', ['type' => 'submit','class'=>'btn btn-primary']); ?>
									  </div>
									</div>  
                                <?php  echo $this->Form->end(); ?>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
		</div>
    </section>
</div>
