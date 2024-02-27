<style>
.input-style{border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px}
.open > .dropdown-menu {
    display: block;
    height: 400px;
    overflow-y: scroll;
}
</style>
<div class="content-wrapper" style="overflow: auto;">
    <section class="content-header">
        <h1> <?php echo __($title); ?> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home'); ?></a></li>
            <li class="active"><?php echo __($title); ?></li>
        </ol>
    </section>
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="clearfix"></div>
            <div class="w3agile-validation w3ls-validation ">
                <div class="w3agile-validation w3ls-validation mt20">
                    <div class="agile-validation agile_info_shadow">
                        <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                            <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
								<?php echo $this->Form->create($add,array('method'=>'post','enctype'=>'multipart/form-data')); ?>
									<div class="col-md-12 col-md-offset-3">
										<?= $this->Flash->render() ?>
										<div class="col-md-6 form-group valid-form">
											<?= __('Coin Name: '); ?>
											<?php  echo $this->Form->input('epay_id',array('class' => 'form-control input-style required','label' =>false,"type"=>"radio","options"=>$coinList,"required"=>true));?>
										</div>
										<div class="clearfix"></div>
										<div class="col-md-6 form-group valid-form">
											<?= __('Username: '); ?>
											<?php  echo $this->Form->input('user_ids',array('id'=>'user_name','class' => 'form-control input-style required search_users','label' =>false,"type"=>"select","required"=>true, "multiple"=>true, "placeholder"=>__('Please select user'),'style' => 'width:100%; display: inline-block;'));?>
										</div>
										<div class="clearfix"></div>
										<div class="col-md-6 form-group valid-form">
											<?= __('Coin Amount: '); ?>
											<?php  echo $this->Form->input('amount',array('class' => 'form-control input-style required','label' =>false,"type"=>"number","id"=>"amount","required"=>true));?>
										</div>
										<div class="clearfix"></div>
										<div class="col-md-6 form-group valid-form">
											<?= __('Type: '); ?>
											<?php  echo $this->Form->input('add_type',array('class' => 'form-control input-style required','label' =>false,"type"=>"radio","options"=>$addtypeList,"required"=>true));?>
										</div>
										<div class="clearfix"></div>
										<div class="col-md-6 form-group valid-form">
											<?= __('Category: '); ?>
											<?php  echo $this->Form->input('e_type',array('class' => 'form-control input-style required','label' =>false,"type"=>"radio","options"=>$category,"required"=>true));?>
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
</div>
<script>
    $(document).ready(function() {
		user_search_select2('user_name'); /* user name search */
    });
</script>