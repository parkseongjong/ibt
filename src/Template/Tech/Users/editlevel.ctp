<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>  Edit Level </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"> editlevel</li>
        </ol>
    </section>
    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="clearfix"></div>
            <div class="w3agile-validation w3ls-validation ">
                <div class="agile-validation agile_info_shadow">
                    <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                        <div class="input-info">
                            <h3 class="w3_inner_tittle two"> Edit <?php echo $levelObj->level_name ?></h3>
                        </div>
                        <div class="  form-body form-body-info">
							<?php echo $this->Form->create($levelObj,array('enctype'=>'multipart/form-data','class'=>'form-horizontal form-label-left','method'=>'post','id'=>'frm'));?>
								<?= $this->Flash->render() ?>
								<div class="item form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12" for="name">Level Name <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<?php  echo $this->Form->input('level_name',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"text","required"=>true,"readonly"=>true)); ?>
									</div>
								</div>
								<div class="item form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12" for="name">Pages  <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<?php foreach($list as $l){ ?>
											<input id="level_page_<?=$l->id;?>" type="checkbox" name="pages[]" class="" value="<?=$l->id;?>"
											<?php if($l->level_id == $levelObj->id){?>
												checked
											<?php } ?>
											/>
											<label for="level_page_<?=$l->id;?>" style="font-weight: normal;"><?=$l->menu_name;?> (level : <?=$l->level_id;?>)</label><br/>
										<?php } ?>
									</div>
								</div>
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-md-6 col-md-offset-2">
										<?php  echo $this->Form->button('Submit', ['type' => 'button','class'=>'btn btn-success','onclick'=>'submitCheck()']); ?>
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
	function submitCheck(){
		if(confirm('Do you want to change level on checked pages?')){
			$('#frm').submit();
		}
	}
</script>