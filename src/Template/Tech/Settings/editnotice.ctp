<style>
.input-style{border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><a href="<?php echo $this->Url->build(['controller'=>'settings','action'=>'editnotice']);  ?>"> <?= __('Edit Notice');?> </a></h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home');?></a></li>
            <li class="active"><a href="<?php echo $this->Url->build(['controller'=>'settings','action'=>'editnotice']);  ?>"> <?= __('Edit Notice');?> </a></li>
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
                                 <h3 class="w3_inner_tittle two"><?= __('Edit: ');?></h3>
                             </div>
                             <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
								<?php echo $this->Form->create($conversion,array('method'=>'post','enctype'=>'multipart/form-data',)); ?>
                                 <div class="col-md-12 col-md-offset-3">
                                     <?= $this->Flash->render() ?>
                                     <div class="col-md-6 form-group valid-form">
                                         <?= __('Select Language: ');?>

                                         <?php echo $this->Form->input('lang', ['type'=>'select','options'=> array(
											'en_US'=>"ENG",
											'ko_KR'=>"KOR"
											),'label'=>false,'class'=>"form-control input-style required",'value'=>$BoardNotice['lang']]); ?>
                                     </div>
                                     <div class="clearfix"></div>
                                     <div class="col-md-6 form-group valid-form">
                                         <?= __('Category: ');?>

                                         <?php echo $this->Form->input('category', ['type'=>'select','options'=> array(
											'notice'=>__('Boardcategory notice'),
											'general'=>__('Boardcategory general')
											),'label'=>false,'class'=>"form-control input-style required",'value'=>$BoardNotice['category']]); ?>
                                     </div>
                                     <div class="clearfix"></div>
                                     <div class="col-md-6 form-group valid-form">
                                         <?= __('Subject: ');?>

                                         <?php  echo $this->Form->input('subject',array('class' => 'form-control input-style required','label' =>false,"type"=>"text","id"=>"subject","required"=>true,'value'=>$BoardNotice['subject']));?>
                                     </div>
                                     <div class="clearfix"></div>
                                     <div class="col-md-6 form-group valid-form">
                                         <?= __('Contents: ');?>

                                         <?php echo $this->Form->input('contents', ['type'=>'textarea', 'label'=>false, 'required'=>true,'id'=>'contents','class' => 'form-control input-style required','value'=>$BoardNotice['contents']]); ?>                                    </div>
                                     <div class="clearfix"></div>
                                     <div class="col-md-6 form-group valid-form">
                                         <?= __('File Attachment: ');?>

                                         <?php  echo $this->Form->input('icon_img',array('class' => 'form-control input-style','label' =>false,"type"=>"file","id"=>"icon_img"));?>
                                         <?php if(!empty($BoardNotice['file'])){
                                             $issueFile = "<img src='".$this->request->webroot."uploads/board/".$BoardNotice['file']."' width=50 />";
                                             echo $issueFile;
                                         } ?>
                                     </div>
                                     <div class="clearfix"></div>
                                     <div class="form-group col-md-12">
                                         <?php  echo $this->Form->button(__('Submit'), ['type' => 'submit','class'=>'btn btn-primary']); ?>
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
