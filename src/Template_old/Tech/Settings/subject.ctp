<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Subjects </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Support subject</li>
        </ol>
    </section>
				
	<section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="clearfix"></div>
             <div class="w3agile-validation w3ls-validation ">
				 
				 
				<div class="w3agile-validation w3ls-validation mt20">
                    <div class="agile-validation agile_info_shadow">
						<h3 class="w3_inner_tittle two">Add/Edit</h3>			
							<?= $this->Flash->render() ?>
				  <?php echo $this->Form->create($Subjects,array('class'=>'form-horizontal form-label-left','novalidate','method'=>'post','enctype'=>'multipart/form-data'));?>
				
				
				<div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
							<input type="hidden" id="subject_id" value="" name="id"/>
                           <?php  echo $this->Form->input('subject',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"text")); ?>
                        </div>
                      </div>
					  
                   
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
							<?php  echo $this->Form->button('Reset', ['type' => 'reset','class'=>'btn btn-primary']); ?>
							<?php  echo $this->Form->button('Submit', ['type' => 'submit','class'=>'btn btn-success']); ?>
                        </div>
                      </div>
                    </form>
                    
                    </div>
				</div>
                    
				
				<div class="w3agile-validation w3ls-validation mt20">
                    <div class="agile-validation agile_info_shadow">	
				
<h3 class="w3_inner_tittle two">Listing</h3>	
          
            <div class="row">
			
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div id="divLoading"> </div><!--Loading class -->
                  <div class="x_content">

                    <div class="table-responsive">
						
                      <table class="table table-striped jambo_table bulk_action two-axis table">
                        <thead>
                          <tr class="headings">
							<th class="column-title">S.No. </th>
							<th class="column-title">Section </th>  
							<th class="column-title no-link last"><span class="nobr">Action</span>
							</th>
                          
                          </tr>
                        </thead>

                        <tbody>
							<?php 
							$count = 1;
							foreach($listing as $data){
							 ?>
							<tr id ="user_row_<?= $data['id']; ?>">
								<td><?= $count?>.</td>
								<td class="input_<?= $data['id']; ?>"><?= $data['subject']?></td>
								
								<td class=" last">
									<a href="javascript:void(0)" onclick="edit_section(<?php echo $data['id'] ?>)" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
									
									<a href="javascript:void(0)" onclick="delete_section(<?php echo $data['id'] ?>)" class="btn btn-danger btn-xs"><i class="fa fa-close"></i> Delete </a>
								
								</td>
							</tr>
							
							<?php  $count++;
							} ?> 
							
						

                         </tbody>
                      </table>
                    </div>
                  </div>
                </div>
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
     $("#subject_id").val(id);
	 var old_value  =  $(".input_"+id).text();
	 $("#subject").val(old_value);
	 $("#subject").focus();
	
}

function delete_section(id){
	bootbox.confirm("Are you sure?", function(result) {
		if(result == true){
			jQuery.ajax({ 
				//url: 'delete',
				url: '<?php echo $this->Url->build(['controller'=>'Settings','action'=>'deleteSubject']); ?>',
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
