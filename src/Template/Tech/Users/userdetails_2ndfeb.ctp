<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Users' <small>Details</small> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Users' Details</li>
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
       
                    <h3 class="w3_inner_tittle two">Users' Details</h3>
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
                            <th class="text-center">#</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Username</th>
                            <th class="text-center">Membership</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">User Level</th>
                            <th class="text-center">Auth Level 1</th>
                            <th class="text-center">Auth Level 2</th>
                            <th class="text-center">Action</th>
                            <th class="text-center">Auth Level 3</th>
                            
                        </tr>
                        </thead>
                        <tbody>
                        <?php
						
						$statusArr = ['P'=>"Pending",'R'=>"Rejected",'A'=>"Approved",'N'=>"Not Uploaded"];
                        $count= 1; 
						$webroot = $this->request->webroot;
                        foreach($users->toArray() as $k=>$data){
						$kyArr = ['N'=>'Not Uploaded','P'=>'Pending','C'=>'Completed','R'=>'Rejected'];
						
                        if($k%2==0) $class="odd";
                        else $class="even";
						
						$idDocumnetRejectedReason = ($data['id_document_status'] =="R" && !empty($data['id_document_reject_reason'])) ? " ( ".$data['id_document_reject_reason']." )" : "";
						$scanCopyRejectedReason = ($data['scan_copy_status'] =="R" && !empty($data['scan_copy_reject_reason'])) ? " ( ".$data['scan_copy_reject_reason']." )" : "";
                        ?>
                        <tr class="<?=$class?>" id="user_row_<?= $data['id']; ?>">
                            <td class="text-center" style="vertical-align: middle"><?=$count?></td>
                            <td class="text-center" style="vertical-align: middle"><?php echo $data['name']; ?></td>
                            <td class="text-center" style="vertical-align: middle"><?php echo $data['username']; ?></td>
                            <td class="text-center" style="vertical-align: middle"><?php if ($data['annual_membership'] == "Y"){
                                    echo "Annual Member";
                                } else {
                                    echo "General Member";
                                }
                                ?></td>
                            <td class="text-center" style="vertical-align: middle"><?php echo $data['category']; ?></td>
                            <td class="text-center" style="vertical-align: middle"><?php echo $data['user_level']; ?></td>
                            <td class="text-center" style="vertical-align: middle"><?php if (!empty($data['phone_number'])){
                                    echo "✔";
                                } else {
                                    echo "✗";
                                }
                                ?></td>
                            <td class="text-center" style="vertical-align: middle">
                                <?php
                                    $otp="";$bnk="";
                                    if(!empty($data['bank']) && !empty($data['account_number']))
                                    {
                                        //$bnk = " Bank Name: ".$data['bank'].", Account Number: ".$data['account_number'];
                                        $bnk = $data['bank'];
                                        $bnk2 = $data['account_number'];
                                    }
                                    else{
                                        //$bnk="Bank Name: ✗, Account Number: ✗";
                                        $bnk= "✗";
                                        $bnk2= "✗";
                                    }
                                    if($data['g_verify'] == "Y"){
                                        $otp = "✔";
                                    }
                                    else{
                                        $otp="✗";
                                    }
                                    //echo "Email: ".$data['email'].", ".$bnk.", OTP: ".$otp;
                                    echo('
                                        <table class="table table-striped">
                                            <tbody>
                                                <tr>
                                                    <td><b>Email:</b> '.$data['email'].'</td>
                                                    <td><b>OTP:</b> '.$otp.'</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Bank Name:</b> '.$bnk.'</td>
                                                    <td><b>Account Number:</b>. '.$bnk2.'</td>
                                                </tr>
                                               
                                            </tbody>
                                        </table>
                                    ');
                                ?>
                            </td>
                            <td class=" last text-center" style="vertical-align: middle">
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Show Action
                                        <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li><a href="javascript:void(0)" onclick="removeEmail(<?php echo $data['id'] ?>)" class="btn btn-xs"><i class="fa fa-close"></i> Remove Email Auth</a></li>
                                        <li><a href="javascript:void(0)" onclick="removeBank(<?php echo $data['id'] ?>)" class="btn btn-xs"><i class="fa fa-close"></i> Remove Bank Auth</a></li>
                                        <li><a href="javascript:void(0)" onclick="removeOTP(<?php echo $data['id'] ?>)" class="btn btn-xs"><i class="fa fa-close"></i> Remove OTP Auth</a></li>

                                    </ul>
                                </div>
                            </td>
                            <td class="text-center" style="vertical-align: middle">

                            <?php

								if(!empty($data['id_document_front'])){
									$front1 = "<a target='_blank' href='".BASEURL."uploads/id_verification/{$idFront}' >
                                                <img src='{$webroot}uploads/id_verification/$idFront' width=50 />
                                            </a>";
									$front2 = $statusArr[$data['id_document_status']].$idDocumnetRejectedReason;
								} else {
                                    $front1 = '✗';
                                    $front2 = 'Not Uploaded';
                                }

								if(!empty($data['id_document_back'])){
									$back1 = "<a target='_blank' href='".BASEURL."uploads/id_verification/{$idBack}' >
                                                <img src='{$webroot}uploads/id_verification/".$data['id_document_back']."' width=50 />
                                            </a>";
									$back2 = $statusArr[$data['id_document_status']].$idDocumnetRejectedReason;
								}
								else {
                                    $back1 = '✗';
                                    $back2 = 'Not Uploaded';
                                }
                                if ($statusArr[$data['id_document_status']].$idDocumnetRejectedReason != "Not Uploaded"){
                                   $backChange = '<a href="javascript:void(0);" onclick="change_status('.$data['id'].',"id_document");">Change</a>';
                                }
                                else{
                                    $backChange = '';
                                }

								if(!empty($data['scan_copy'])){
									$photo1 = "<a target='_blank' href='".BASEURL."uploads/id_verification/{$scanCopy}' >
                                            <img src='{$webroot}uploads/id_verification/".$data['scan_copy']."' width=50 />
                                        </a>";
                                    $photo2 = $statusArr[$data['scan_copy_status']].$scanCopyRejectedReason;
								}
								else {
                                    $photo1 = '✗';
                                    $photo2 = 'Not Uploaded';
                                }

								if($statusArr[$data['scan_copy_status']].$scanCopyRejectedReason != "Not Uploaded"){
                                    $ScanCopyChange = '<a href="javascript:void(0);" onclick="change_status('.$data['id'].',"scan_copy");">Change</a>';
                                }
								else{
                                    $ScanCopyChange = '';
                                }
                                echo('
                                    <table class="table table-striped">
                                        <tbody>
                                        <tr>
                                            <td><b>ID Front Side:</b> '.$front1.'</td>
                                            <td><b>Reason:</b> '.$front2.'</td>
                                            <td></td>
                                        </tr> 
                                        <tr>
                                            <td><b>ID Back Side:</b> '.$back1.'</td>
                                            <td><b>Reason:</b> '.$back2.'</td>
                                            <td><b>ID Document Status:</b> '.$backChange.'</td>
                                        </tr> 
                                        <tr>
                                            <td><b>Scanned Photo:</b> '.$photo1.'</td>
                                            <td><b>Reason:</b> '.$photo2.'</td>
                                            <td><b>Scan Copy Status:</b> '.$ScanCopyChange.'</td>
                                        </tr>
    
                                        </tbody>
                                    </table>
                                ');
                            ?>
							</td>
                            
							
							
                            
                        </tr>
                        <?php $count++;} ?>
                        </tbody>
                    </table>
                    <?php $this->Paginator->options(array('url' => array('controller' => 'Users', 'action' => 'userdetailsearch')));
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


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
	 <?php echo $this->Form->create('',['url'=>['controller'=>'Users','action'=>'usersStatusUpdate']]);?>
	 <?php echo $this->Form->input("status_type",['id'=>'status_type','type'=>'hidden']);?>
	 <?php echo $this->Form->input("status_id",['id'=>'status_id','type'=>'hidden']);?>
	
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <?php echo $this->Form->input("status",['id'=>'update_status_op','class'=>'form-control','type'=>'select','options'=>['P'=>'Pending','A'=>'Approved','R'=>'Reject']]);?>
		<label class="reason" style="display:none;">Reason </label>
		<?php echo $this->Form->input("reject_reason",['class'=>'form-control reason','label'=>false,'type'=>'textarea','id'=>'reject_reason','style'=>'display:none;']);?>
      </div>
      <div class="modal-footer">
       
		<?php echo $this->Form->submit("submit",['class'=>'bnt btn-info']);?>
      </div>
    </div>
 <?php echo $this->Form->end();?>
  </div>
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

function removeEmail(id){
    bootbox.confirm("Are you sure?", function(result) {
        if(result == true){
            jQuery.ajax({
                //url: 'delete',
                url: '<?php echo $this->Url->build(['controller'=>'users','action'=>'removeEmailAuth']); ?>',
                data: {'id':id},
                type: 'POST',
                success: function(data) {
                    if(data == 1){
                        jQuery("#user_row_"+id).update();
                        new PNotify({
                            title: 'Success',
                            text: 'Record Updated Successfully!',
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

function removeBank(id){
    bootbox.confirm("Are you sure?", function(result) {
        if(result == true){
            jQuery.ajax({
                //url: 'delete',
                url: '<?php echo $this->Url->build(['controller'=>'users','action'=>'removeBankAuth']); ?>',
                data: {'id':id},
                type: 'POST',
                success: function(data) {
                    if(data == 1){
                        jQuery("#user_row_"+id).update();
                        new PNotify({
                            title: 'Success',
                            text: 'Record Updated Successfully!',
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

function removeOTP(id){
    bootbox.confirm("Are you sure?", function(result) {
        if(result == true){
            jQuery.ajax({
                //url: 'delete',
                url: '<?php echo $this->Url->build(['controller'=>'users','action'=>'removeOTPAuth']); ?>',
                data: {'id':id},
                type: 'POST',
                success: function(data) {
                    if(data == 1){
                        jQuery("#user_row_"+id).update();
                        new PNotify({
                            title: 'Success',
                            text: 'Record Updated Successfully!',
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

	function change_status(rowId,type){
		$("#status_id").val(rowId);
		$("#status_type").val(type);
		$("#myModal").modal('show');  
	}

function membership_change(id) {
    if ($("#membership_" + id).prop('checked') === true) {
        var value = "Y";
    } else if ($("#membership_" + id).prop('checked') === false) {
        var value = "N";
    }
    $.ajax({
        type: 'post',
        url: '<?= $this->Url->build(['controller' => 'users', 'action' => 'updateMembership']);  ?>',
        data: {'id':id,'annual_membership': value},
        success: function (data) {

        }
    });
}

	$("#update_status_op").change(function(){
		var getVal = $(this).val();
	
		if(getVal=="R"){
			$(".reason").show();
		}
		else {
			$(".reason").hide();
		}
	});
		
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


