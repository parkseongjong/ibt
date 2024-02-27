<style>
.input-style{border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <?= __('Coin Pair Management'); ?> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home'); ?></a></li>
            <li class="active"><?= __('Coin Pair Management'); ?></li>
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
                                <h3 class="w3_inner_tittle two"><?= __('Edit Coin Pair'); ?></h3>
                            </div>
							<?= $this->Flash->render() ?>
                            <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
								<?php echo $this->Form->create('Coinpair',array('method'=>'post','enctype'=>'multipart/form-data'));
								echo $this->Form->input('id',['type'=>'hidden']);
				  ?>
								<div class="col-md-12 col-md-offset-3">
                                    <div class="col-md-6 form-group valid-form">
                                        <?= __('First Coin: '); ?>
										 <?php  echo $this->Form->input('coin_first_id',array('class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>$coinList,"required"=>true));?>
                                    </div>
									<div class="clearfix"></div>
									<div class="col-md-6 form-group valid-form">
                                        <?= __('Second Coin: '); ?>
                                        <?php  echo $this->Form->input('coin_second_id',array('class' => 'form-control input-style required','label' =>false,"type"=>"select","options"=>$coinList,"required"=>true));?>
                                    </div>
										<div class="clearfix"></div>
									<div class="col-md-6 form-group valid-form">
                                        <?= __('Max Min Price %: '); ?>
                                        <?php  echo $this->Form->input('max_min_current_price_percent',array('class' => 'form-control input-style required','label' =>false,"type"=>"text","required"=>true));?>
                                    </div>
									<div class="clearfix"></div>
									<div class="col-md-6 form-group valid-form">
                                        <?= __('Order No.: '); ?>
                                        <?php  echo $this->Form->input('order_no',array('class' => 'form-control input-style required','label' =>false,"type"=>"number","required"=>true));?>
                                    </div>
									<br/>
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
								  text: '<?= __('This record is being referenced at other place. So, you cannot delete it.'); ?>',
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
