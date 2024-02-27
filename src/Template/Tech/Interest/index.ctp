<style>
.input-style{border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Interest </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Interest</li>
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
                                <h3 class="w3_inner_tittle two">Add/Edit :</h3>
                            </div>
                            <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
								<?php echo $this->Form->create($conversion,array('method'=>'post'));
				  ?>
				  <input type="hidden" id="landing_program_id" value="" name="id"/>
                               
                                    <?= $this->Flash->render() ?>
                                    <div class="col-md-3 form-group valid-form">
                                        Date :
										 <?php  echo $this->Form->input('add_date',array('class' => 'form-control input-style datepicker','label' =>false,"type"=>"text","id"=>"from_date","required"=>true));?>
                                        
                                    </div>
                                    <div class="col-md-3 form-group valid-form">
                                        Percent:
                                        <?php  echo $this->Form->input('percent',array('class' => 'form-control input-style','label' =>false,"type"=>"text","id"=>"to_date","required"=>true));?>
                                    </div>


                                    <div class="clearfix"></div>
									  <div class="form-group col-md-12">
										<?php  echo $this->Form->button('Submit', ['type' => 'submit','class'=>'btn btn-primary']); ?>
									  </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
				  <div class="w3agile-validation w3ls-validation mt20">
                    <div class="agile-validation agile_info_shadow">
                        <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                            <div class="input-info">
                                <h3 class="w3_inner_tittle two">List of Interest:</h3>
                            </div>
							<div id="main_wallet_transaction_div" class="mt10 table-responsive">
                            <table id="table-two-axis " class="two-axis table dataTable">
							<thead>
							<tr>
								<th>S No.</th>
								<th>date</th>
								<th>Percent</th>
								<th class="column-title no-link last"><span class="nobr">Action</span>
							</tr>
							</thead>
							<tbody>
							<?php
							$count= 1;
								
							 foreach($listing->toArray() as $k=>$data){
								if($k%2==0) $class="odd";
								else $class="even";
							?>
							<tr  style="text-align:center" class="<?=$class?>" id="user_row_<?= $data['id']; ?>">
								<td> <?=$count?></td>
								<td class="from_date_<?= $data['id']; ?>" data-hiddendate='<?php echo date('Y-m-d',strtotime($data['add_date'])); ?>'><?php echo date('d M Y',strtotime($data['add_date']))?> </td>
								<td class="to_date_<?= $data['id']; ?>" data-hiddendate="<?php echo $data['percent']; ?>"><?php echo $data['percent']; ?> </td>
								<td class=" last">
									<a href="javascript:void(0)" onclick="edit_section(<?php echo $data['id'] ?>)" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
									
									<a href="javascript:void(0)" onclick="delete_section(<?php echo $data['id'] ?>)" class="btn btn-danger btn-xs"><i class="fa fa-close"></i> Delete </a>
								
								</td>				
								
							</tr>
							<?php $count++; } ?>
							<?php  if(count($listing->toArray()) < 1) {
								echo "<tr class='even'><td colspan = '6'>No record found</td></tr>";
						   } ?>	
							</tbody>
						</table>
						   <?php /* $this->Paginator->options(array('url' => array('controller' => 'Interest', 'action' => 'search')));
							echo "<div class='pagination' style = 'float:right'>";
		 
							// the 'first' page button
							$paginator = $this->Paginator;
							echo $paginator->first("First");

							// 'prev' page button, 
							// we can check using the paginator hasPrev() method if there's a previous page
							// save with the 'next' page button
							if($paginator->hasPrev()){
							echo $paginator->prev("Prev");
							}

							// the 'number' page buttons
							echo $paginator->numbers(array('modulus' => 2));

							// for the 'next' button
							if($paginator->hasNext()){
							echo $paginator->next("Next");
							}

							// the 'last' page button
							echo $paginator->last("Last");

							echo "</div>"; */
									
							?>
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
		var from_date_old_value  =  $(".from_date_"+id).attr('data-hiddendate');
		from_date_old_value = from_date_old_value.trim();
		$("#from_date").val(from_date_old_value);
		
		var to_date_old_value  =  $(".to_date_"+id).attr('data-hiddendate');
		to_date_old_value = to_date_old_value.trim();
		$("#to_date").val(to_date_old_value);
		
		var bonus_token_percent_old_value  =  $(".bonus_token_percent_"+id).text();
		bonus_token_percent_old_value = bonus_token_percent_old_value.trim();
		$("#bonus_token_percent").val(bonus_token_percent_old_value);
		$("#bonus_token_percent").focus();
		
	}
	
	
	
	function delete_section(id){
		bootbox.confirm("Are you sure?", function(result) {
			if(result == true){
				jQuery.ajax({ 
					//url: 'delete',
					url: '<?php echo $this->Url->build(['controller'=>'Interest','action'=>'deleteProgram']); ?>',
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
	
	jQuery('.table-responsive').on('click','.pagination li a',function(event){
		event.preventDefault() ;
		var keyy = $('form').serialize();
		var urli = jQuery(this).attr('href');
		jQuery.ajax({ 
				url: urli,
				data: {key:keyy},
				type: 'POST',
				success: function(data) {
					if(data){
						
						jQuery('.table-responsive').html(data);
						
					}
				}
		});
		
	});

    </script>
