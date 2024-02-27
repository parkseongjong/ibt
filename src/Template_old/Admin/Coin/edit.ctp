<style>
.input-style{border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Coin Management </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Coin Management</li>
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
                                <h3 class="w3_inner_tittle two">Add Token :</h3>
                            </div>
                            <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
								<?php echo $this->Form->create('Coin',array('method'=>'post','enctype'=>'multipart/form-data'));
								echo $this->Form->input('id',['type'=>'hidden']);
				  ?>
								<div class="col-md-12 col-md-offset-3">
                               
                                    <?= $this->Flash->render() ?>
                                    <div class="col-md-6 form-group valid-form">
                                        Coin Name :
										 <?php  echo $this->Form->input('name',array('class' => 'form-control input-style required','label' =>false,"type"=>"text","id"=>"name","required"=>true));?>
                                        
                                    </div>
									
									
									<div class="clearfix"></div>
                                    
									
									<div class="col-md-6 form-group valid-form">
                                        Coin Short Name:
                                        <?php  echo $this->Form->input('short_name',array('class' => 'form-control input-style required','label' =>false,"type"=>"text","id"=>"short_name","required"=>true));?>
                                    </div>
									
									
									<div class="clearfix"></div>
									
									
									<div class="col-md-6 form-group valid-form">
                                        Icon:
                                        <?php  echo $this->Form->input('icon_img',array('class' => 'form-control input-style required','label' =>false,"type"=>"file","id"=>"icon_img"));?>
                                    </div>
									
									<div class="clearfix"></div>
									
									<div class="col-md-6 form-group valid-form">
                                        Usd Price:
                                        <?php  echo $this->Form->input('usd_price',array('class' => 'form-control input-style required','label' =>false,"type"=>"text","id"=>"usd_price","required"=>true));?>
                                    </div>
									
									<div class="clearfix"></div>
									<div class="col-md-6 form-group valid-form">
                                        Total Coin:
                                        <?php  echo $this->Form->input('total_coin',array('class' => 'form-control input-style required','label' =>false,"type"=>"text","id"=>"total_coin","required"=>true));?>
                                    </div>
                                    
									
									<div class="clearfix"></div>
									  <div class="form-group col-md-12">
										<?php  echo $this->Form->button('Submit', ['type' => 'submit','class'=>'btn btn-primary']); ?>
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
		
	function edit_section(id){
    
		$("#landing_program_id").val(id);
		var total_token_old_value  =  $(".total_token_"+id).text();
		total_token_old_value = total_token_old_value.trim();
		$("#total_token").val(total_token_old_value);
		
		var token_sold_old_value  =  $(".sold_token_"+id).text();
		token_sold_old_value = token_sold_old_value.trim();
		$("#sold_token").val(token_sold_old_value);
		
		var total_btc_old_value  =  $(".total_btc_"+id).text();
		total_btc_old_value = total_btc_old_value.trim();
		$("#total_btc").val(total_btc_old_value);
		
		var btc_value_old_value  =  $(".btc_value_"+id).text();
		btc_value_old_value = btc_value_old_value.trim();
		$("#btc_value").val(btc_value_old_value);
		
		var token_value_old_value  =  $(".token_value_"+id).text();
		token_value_old_value = token_value_old_value.trim();
		$("#token_value").val(token_value_old_value);
		$("#token_value").focus();
		
	}
	
	
	
	function delete_section(id){
		bootbox.confirm("Are you sure?", function(result) {
			if(result == true){
				jQuery.ajax({ 
					//url: 'delete',
					url: '<?php echo $this->Url->build(['controller'=>'Coin','action'=>'deleteProgram']); ?>',
					data: {'id':id},
					type: 'POST',
					success: function(data) {
						if(data == 1){
							jQuery("#user_row_"+id).remove();
							new PNotify({
								  title: 'Success',
								  text: 'Record Delete successfully!',
								  type: 'success',
								  styling: 'bootstrap3',
								  delay:1200
							  });
							
						}if(data == 'forbidden'){
							
							new PNotify({
								  title: '403 Error',
								  text: 'You donot have permission to access this action.',
								  type: 'error',
								  styling: 'bootstrap3',
								  delay:1200
							  });
							
						}
					},
					error: function (request) {
						new PNotify({
								  title: 'Error',
								  text: 'This record is being referenced in other place. You cannot delete it.',
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
