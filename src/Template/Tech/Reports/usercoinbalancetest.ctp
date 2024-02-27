
    <link rel="stylesheet" href="<?php echo $this->request->webroot ?>datatable/jquery.dataTables.min.css">
	<script src="<?php echo $this->request->webroot ?>datatable/jquery.dataTables.min.js"></script>
   
    
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Users <small>Balance</small> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Users Balance</li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="content" class="table-layout">

        <div class="inner_content_w3_agile_info">
                <div class="agile-tables">
                <div class="w3l-table-info agile_info_shadow">
					 <div class="clearfix"></div>
            <form style="padding:10px" method="post" class="form-horizontal form-label-left input_mask">

					 
                     <div class="form-group">
						  <div class="col-md-3 col-sm-3 col-xs-12">
                            <?php  echo $this->Form->input('username',array('placeholder'=>'Username','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
                            
                        </div>
						  <div class="col-md-3 col-sm-3 col-xs-12">
                            <?php  echo $this->Form->input('name',array('placeholder'=>'Name','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
                            
                        </div>
                         <div class="col-md-3 col-sm-3 col-xs-12">
                            <?php  echo $this->Form->input('email',array('placeholder'=>'Email','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
                        </div>
                          <div class="col-md-3 col-sm-3 col-xs-12">
                            <?php  echo $this->Form->input('phone_number',array('placeholder'=>'Contact Number','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
                        </div> 
						 
                     </div>
                     <div class="form-group">
						
						  <div class="col-md-3 col-sm-3 col-xs-12">
                        <?php  echo $this->Form->input('start_date',array('placeholder'=>'Start date  ','class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text')); ?>
                       
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-12">
                         <?php  echo $this->Form->input('end_date',array('placeholder'=>'End Date','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
                       
                      </div>
                       <div class="col-md-2 col-sm-2 col-xs-12">
                            <?php  echo $this->Form->input('pagination',array('empty'=>'No of record','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>array(10=>10,25=>25,50=>50,100=>100))); ?>
                           <input type="hidden" name="export" id="export" />
                        </div> 
                         <div class="col-md-1 col-sm-1 col-xs-12">
                          <button type="submit" class="btn btn-success">Filter</button>
                        </div>
					 </div>
                  
				</form>
		<div class="clearfix"></div>
       
                    <h3 class="w3_inner_tittle two">Users Balance</h3>
                   <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Export
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                          <!--  <li><a href="javascript:void(0)" onclick="export_f('e')">Excel</a></li>-->
                            <li><a href="javascript:void(0)" onclick="export_f('c')">CSV</a></li>
                        </ul>
                    </div>
                    <div class="table-responsive">
                    <table id="table-two-axis" class="two-axis table">
                        <thead>
                        <tr> 
                            <th>#</th>
                           
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone number</th>
							<th>Total Balance</th>
							<th>Main Balance</th>
							<th>Trading Balance</th>
							

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $count= 1; 
                        foreach($users->toArray() as $k=>$data){
						$kyArr = [''=>'Not Uploaded','P'=>'Pending','Y'=>'Completed','N'=>'Rejected'];
						
                        if($k%2==0) $class="odd";
                        else $class="even";
						
						
						
						$getUserTransactions = $this->Custom->getBalanceForAdmin($coinShortName,$data['id']);
						$mainBalance  = $getUserTransactions['principalBalance'];
						$tradingBalance  = $getUserTransactions['withdrawBalance'];
						
						$totalBalance = $mainBalance+$tradingBalance;
						
                        ?>
                        <tr class="<?=$class?>" id="user_row_<?= $data['id']; ?>">
                            <td><?=$count?></td>
                            
                            <td><?php echo $data['name']; ?></td>
                            <td><?php echo $data['email']; ?></td>
                            <td><?php echo $data['phone_number']; ?></td>                            
                            <td><?php echo $totalBalance; ?></td>                            
							<td> <?php echo $getUserTransactions['principalBalance']; ?></td>         
							<td> <?php  echo $getUserTransactions['withdrawBalance']; ?></td>       
							

                        </tr>
                        <?php $count++;} ?>
                        </tbody>
                    </table>
                    <?php $this->Paginator->options(array('url' => array('controller' => 'Reports', 'action' => 'userbalnacesearch')));
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
    </section>
</div>

<script>

function checkConfrim(getdata){
	if(confirm("Are You Really want to impersonate ?")){
		url = '/front/users/impersonate/'+getdata;
		
		window.open(url, '_blank');
	}
	else {
		return false;
	}
	
}
$(document).ready(function() {
			$("#table-two-axis").DataTable({"order": [[ 4, "desc" ]]});
			$('#start-date').datepicker({
				
				format: 'yyyy-mm-dd',
				maxDate: '0'

			});
			$('#end-date').datepicker({
				
				format: 'yyyy-mm-dd',
				maxDate: '0'

			});


      });

		
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
        
		function export_f(v) {
            $('#export').val(v);
            $("form").submit();
            $('#export').val('');
        }
		
		
	function delete_section(id){
		bootbox.confirm("Are you sure?", function(result) {
			if(result == true){
				jQuery.ajax({ 
					//url: 'delete',
					url: '<?php echo $this->Url->build(['controller'=>'users','action'=>'deleteProgram']); ?>',
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
					url: '<?php echo $this->Url->build(['controller'=>'users','action'=>'status']); ?>',
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
		
		
	</script>


