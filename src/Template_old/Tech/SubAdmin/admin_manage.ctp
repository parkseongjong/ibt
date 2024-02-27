<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Manage <small>sub admin</small> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Sub admin</li>
        </ol>
    </section>
      <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-validation agile_info_shadow">


            <div class="row">

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div id="divLoading"> </div><!--Loading class -->
                  <div class="x_content">
<?= $this->Flash->render() ?>
                    <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action two-axis table">
                        <thead>
                          <tr class="headings">
                             <th class="column-title">S.No. </th>
							 <th class="column-title">Username </th>
                            <th class="column-title">Name </th>
                            <th class="column-title">Email </th>
                            <th class="column-title">Phone Number</th> 
                           
							<th class="column-title">Status </th>
                            <th class="column-title no-link last"><span class="nobr">Action</span>
                            </th>
                          
                          </tr>
                        </thead>

                        <tbody>
							<?php 
							$count = 1;
							foreach($Users->toArray() as $data){ ?>
							<tr id ="user_row_<?= $data['id']; ?>">
							
								<td><?= $count?>.</td>
								<td><?= $data['username']?></td>
								<td class=" "><?php  echo ucwords($data['name']) ?></td>
								<td class=" "><?= $data['email']; ?> </td>
								<td class=" "><?= $data['phone_number']; ?></td>
								
								<td class=" ">
									<input type="hidden" id="user_status_<?= $data['id'] ?>" value ="<?= $data['enabled']; ?>" />
									<a href="javascript:void(0)" id="status_id_<?= $data['id']; ?>" onclick="change_user_status(<?php echo $data['id'] ?>)">
									<?php  if($data['enabled'] == 'Y'){
										echo '<button type="button" class="btn btn-success btn-xs">Active</button>'; 
									}else{
										echo '<button type="button" class="btn btn-danger btn-xs">Deactive</button>';
									} ?></a>
								</td>
								<td class=" last">
									<a href="<?php echo $this->Url->build(['controller'=>'sub_admin' ,'action' =>'admin_edit' ,$data['id']]);  ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
									<a href="#" onclick="delete_user(<?= $data['id'] ?>)" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>
								</td>
							</tr>
							
							<?php  $count++;
							} ?> 
							
							<?php  if(count($Users->toArray()) < 1) {
										echo "<tr><th colspan = '6'>No record found</th></tr>";
								   } ?>	

                         </tbody>
                      </table>
                      <?php $this->Paginator->options(array('url' => array('controller' => 'sub_admin', 'action' => 'adminsearch')));
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

					echo "</div>";
							
					?>
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

function delete_user(id){
	bootbox.confirm("Are you sure?", function(result) {
		if(result == true){
			jQuery.ajax({ 
				//url: 'delete',
				url: '<?php echo $this->Url->build(['controller'=>'sub_admin','action'=>'delete']); ?>',
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
function change_user_status(id){
	var status = $("#user_status_"+id).val();
	if(status == 'Y'){
		var ques= "Do you want change the status to DEACTIVE";	
		var status = "N";
		var change = '<button type="button" class="btn btn-danger btn-xs">Deactive</button>'
	}else{
		var ques= "Do you want change the status to ACTIVE";
		var status = "Y";
		var change = '<button type="button" class="btn btn-success btn-xs">Active</button>';
	}
	
	
	bootbox.confirm(ques, function(result) {
		if(result == true){
			jQuery.ajax({ 
				url: '<?php echo $this->Url->build(['controller'=>'sub_admin','action'=>'status']); ?>',
				data: {'id':id,'status':status},
				type: 'POST',
				success: function(data) {
					if(data == 1){
						jQuery("#status_id_"+id).html(change);
						jQuery("#user_status_"+id).val(status);
						new PNotify({
							  title: 'Success',
							  text: 'Status changed successfully!',
							  type: 'success',
							  styling: 'bootstrap3',
							  delay:1200
						  });
						
					}
					if(data == 'forbidden'){
						
						new PNotify({
							  title: '403 Error',
							  text: 'You donot have permission to access this action.',
							  type: 'error',
							  styling: 'bootstrap3',
							  delay:1200
						  });
						
					}
				}
			});
		}
	});

	
}

function search_result(){
			
			var key = jQuery("#search").val();
			
			jQuery.ajax({ 
						url: '<?php echo $this->Url->build(['controller'=>'sub_admin' , 'action'=>'adminsearch']);  ?>',
						data: {'key':key},
						type: 'POST',
						success: function(data) {
							if(data){
								
								jQuery('.table-responsive').html(data);
								
							}
						}
			});
			
		}
		
		jQuery('.table-responsive').on('click','.pagination li a',function(event){
			event.preventDefault() ;
			var keyy = jQuery('#search').val();
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
