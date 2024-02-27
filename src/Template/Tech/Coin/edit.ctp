<style>
.input-style{border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <?= __('Token Management'); ?> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home'); ?></a></li>
            <li class="active"><?= __('Token Management'); ?></li>
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
                                <h3 class="w3_inner_tittle two"><?= __('Edit Token'); ?></h3>
                            </div>
                            <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
								<?php echo $this->Form->create('Coin',array('method'=>'post','enctype'=>'multipart/form-data'));
								echo $this->Form->input('id',['type'=>'hidden']);
				  ?>
								<div class="col-md-12 col-md-offset-3">
                                    <?= $this->Flash->render() ?>
									 <div class="col-md-6 form-group valid-form">
                                         <?= __('Serial No.: '); ?>
										 <?php  echo $this->Form->input('serial_no',array('class' => 'form-control input-style required','label' =>false,"type"=>"number","id"=>"name","required"=>true));?>
                                        
                                    </div>
									<div class="clearfix"></div>
                                    <div class="col-md-6 form-group valid-form">
                                        <?= __('Coin Name: '); ?>
										 <?php  echo $this->Form->input('name',array('class' => 'form-control input-style required','label' =>false,"type"=>"text","id"=>"name","required"=>true));?>
                                    </div>
									<div class="clearfix"></div>
									<div class="col-md-6 form-group valid-form">
                                        <?= __('Coin Short Name: '); ?>
                                        <?php  echo $this->Form->input('short_name',array('class' => 'form-control input-style required','label' =>false,"type"=>"text","id"=>"short_name","required"=>true));?>
                                    </div>
									<div class="clearfix"></div>
									<div class="col-md-6 form-group valid-form">
                                        <?= __('Icon: '); ?>
                                        <?php  echo $this->Form->input('icon_img',array('class' => 'form-control input-style required','label' =>false,"type"=>"file","id"=>"icon_img"));?>
                                    </div>
									<div class="clearfix"></div>
									<div class="col-md-6 form-group valid-form">
                                        <?= __('Price in USD: '); ?>
                                        <?php  echo $this->Form->input('usd_price',array('class' => 'form-control input-style required','label' =>false,"type"=>"text","id"=>"usd_price","required"=>true));?>
                                    </div>
									<div class="clearfix"></div>
									<div class="col-md-6 form-group valid-form">
                                        <?= __('Total Coins: '); ?>
                                        <?php  echo $this->Form->input('total_coin',array('class' => 'form-control input-style required','label' =>false,"type"=>"text","id"=>"total_coin","required"=>true));?>
                                    </div>
                                    <div class="clearfix"></div>
									<div class="col-md-6 form-group valid-form">
                                        <?= __('Main Wallet To Trading Wallet Transfer Limit: '); ?>
                                        <?php  echo $this->Form->input('mainwallet_to_tradingwallet_transfer_limit',array('class' => 'form-control input-style required','label' =>false,"type"=>"text","id"=>"total_coin","required"=>true));?>
                                    </div>
									<div class="clearfix"></div>
									  <div class="form-group col-md-12">
										<?php  echo $this->Form->button('Submit', ['type' => 'submit','class'=>'btn btn-primary','value'=>__('Submit')]); ?>
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
	function delete_section(id){
		bootbox.confirm("<?= __('Are you sure?'); ?>", function(result) {
			if(result == true){
				jQuery.ajax({ 
					//url: 'delete',
					url: '<?php echo $this->Url->build(['controller'=>'Coin','action'=>'deleteProgram']); ?>',
					beforeSend: function(xhr){
						xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
					},
					data: {'id':id},
					type: 'POST',
					success: function(data) {
						if(data == 1){
							jQuery("#user_row_"+id).remove();
							new PNotify({
								  title: '<?= __('Success!'); ?>',
								  text: '<?= __('Record deleted successfully!'); ?>',
								  type: 'success',
								  styling: 'bootstrap3',
								  delay:1200
							  });
							
						}if(data == 'forbidden'){
							
							new PNotify({
								  title: '<?= __('403 Error'); ?>',
								  text: '<?= __('You do not have permission to perform this action'); ?>',
								  type: 'error',
								  styling: 'bootstrap3',
								  delay:1200
							  });
							
						}
					},
					error: function (request) {
						new PNotify({
								  title: '<?= __('Error'); ?>',
								  text: '<?= __('This record is being referenced at other place. So, you cannot delete it'); ?>',
								  type: 'error',
								  styling: 'bootstrap3',
								  delay:1200
							  });
					},
				});
			}
		});
	}
</script>
