<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'userdetails']);  ?>"><?=__("Users' Details");?></a></h1>
        <ol class="breadcrumb">
            <li><a href="/tech/dashboard"><i class="fa fa-dashboard"></i><?=__("Home");?></a></li>
            <li class="active"><a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'userdetails']);  ?>"><?=__("Users' Details");?></a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-tables">
                <div class="w3l-table-info agile_info_shadow">
                    <div class="clearfix"></div>
                    <form id="frm" method="get">
                        <div class="form-group">
                            <div id="selectuser" class="col-md-2 col-sm-2 col-xs-12">
								<?php  echo $this->Form->input('user_name',array('empty'=>__('Please select user'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','id'=>"user_name")); ?>
							</div>
							<div id="selectuseremail" class="col-md-2 col-sm-2 col-xs-12">
								<?php  echo $this->Form->input('user_email',array('empty'=>__('Please select email'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','id'=>"user_email")); ?>
							</div>
                            <div class="col-md-1 col-sm-1 col-xs-12">
                                <?php  echo $this->Form->input('user_level',array('placeholder'=>__('User Level'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'number','value'=>$this->request->query('user_level'))); ?>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('start_date',array('placeholder'=>__('Start Date'),'class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text','value'=>$this->request->query('start_date'))); ?>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('end_date',array('placeholder'=>__('End Date'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text','value'=>$this->request->query('end_date'))); ?>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('pagination',array('empty'=>__('No of records'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>array(10=>10,25=>25,50=>50,100=>100))); ?>
                                <input type="hidden" name="export" id="export" />
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12">
                                <button type="submit" class="btn btn-success"><?= __('Search'); ?></button>
                            </div>
                        </div>
                    </form>
		            <div class="clearfix"></div>
                    <div class="dropdown m-t-5 m-b-15">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><?=__("Export");?>
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
                            <th class="text-center"><?=__("ID");?></th>
                            <th class="text-center"><?=__("Name");?></th>
                            <th class="text-center"><?=__("Phone Number");?></th>
                            <th class="text-center"><?=__("Annual Membership");?></th>
                            <th class="text-center"><?=__("Category");?></th>
                            <th class="text-center"><?=__("User Level");?></th>
                            <th class="text-center"><?=__("Auth Level");?> 1</th>
                            <th class="text-center"><?=__("Auth Level");?> 2</th>
                            <th class="text-center"><?=__("Total Buy/Sell Amount");?></th>
                            <th class="text-center"><?=__("action");?></th>
                            <th class="text-center"><?=__("Auth Level");?> 3</th>
                            
                        </tr>
                        </thead>
                        <tbody>
                        <?php
						
						$statusArr = ['P'=>"Pending",'R'=>"Rejected",'A'=>"Approved",'N'=>"Not Uploaded"];
                        $count= $serial_num;
						$webroot = $this->request->webroot;
                        foreach($users->toArray() as $k=>$data){
							$this->add_system_log(200, $data['id'], 1, '고객 상세 리스트 조회 (이름, 전화번호, 이메일, 계좌)');
						$kyArr = ['N'=>'Not Uploaded','P'=>'Pending','C'=>'Completed','R'=>'Rejected'];
						
                        if($k%2==0) $class="odd";
                        else $class="even";
						
						$idDocumnetRejectedReason = ($data['id_document_status'] =="R" && !empty($data['id_document_reject_reason'])) ? " ( ".$data['id_document_reject_reason']." )" : "";
						$scanCopyRejectedReason = ($data['scan_copy_status'] =="R" && !empty($data['scan_copy_reject_reason'])) ? " ( ".$data['scan_copy_reject_reason']." )" : "";
                        ?>
                        <tr class="<?=$class?>" id="user_row_<?= $data['id']; ?>">
                                <td class="text-center" style="vertical-align: middle"><?= $data['id']; ?></td>
                                <td class="text-center" style="vertical-align: middle"><a href="javascript:void(0)" onclick="unmasking(this,'N',<?= $data['id']; ?>)" class="text-dark"><?= $this->masking('N',$data['name']); ?></a></td>
								<td class="text-center" style="vertical-align: middle"><a href="javascript:void(0)" onclick="unmasking(this,'P',<?= $data['id']; ?>)" class="text-dark"><?= $this->masking('P',$data['phone_number']); ?></a></td>
                                <td class="text-center" style="vertical-align: middle">
									<input type="checkbox" id="membership_<?=$data['id'];?>" name="membership" onchange="membership_change('<?=$data['id'];?>')" value="<?=$data['annual_membership'];?>" <?php if($data['annual_membership'] == 'Y'){echo "checked";}?>>
								</td>
                                <td class="text-center" style="vertical-align: middle"><?= __(ucfirst($data['category'])); ?></td>
                                <td class="text-center" style="vertical-align: middle"><?= $data['user_level']; ?></td>
                                <td class="text-center" style="vertical-align: middle"><?php if (!empty($data['phone_number'])){ echo "✔"; } else {echo "✗"; } ?></td>
                                <td class="text-center" style="vertical-align: middle">
                                    <?php 
										$otp="✗"; $bnk= "✗"; $bnk2= "✗";
                                        if(!empty($data['bank']) && !empty($data['account_number'])) {
                                            $bnk = __($data['bank']);
                                            $bnk2 = $this->masking('B',$this->Decrypt($data['account_number']));
                                        }
                                        if($data['g_verify'] == "Y") $otp = "✔"; 
                                    ?>
									<table class="table table-striped">
										<tbody>
											<tr>
												<td><b><?= __('Email: ');?></b>
													<a href="javascript:void(0)" onclick="unmasking(this,'E',<?= $data['id']; ?>)" class="text-dark"><?= $this->masking('E',$data['email']); ?></a>
												</td>
												<td><b><?=__('OTP: ');?></b><?=$otp;?></td>
											</tr>
											<tr>
												<td><b><?=__('Bank Name: ');?></b><?=$bnk;?></td>
												<td><b><?=__('Account Number: ');?></b>
													<a href="javascript:void(0)" onclick="unmasking(this,'B',<?= $data['id']; ?>)" class="text-dark"><?=$bnk2;?></a>
												</td>
											</tr>
										</tbody>
									</table>
                                </td>
                                <td class="text-center" style="vertical-align: middle">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <td><b><?= __('Total Buy Amount: ');?></b><?php
                                                    $totalBuyAmount = $this->CurrentPrice->getBuySellAmount($data['id']);
                                                    $totalBuy = number_format($totalBuyAmount['buyAmount'],2);
                                                    echo (' '.isset($totalBuy) ? $totalBuy : 0 .''); ?> KRW
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b><?= __('Total Sell Amount: ');?></b><?php
                                                    $totalSellAmount = $this->CurrentPrice->getBuySellAmount($data['id']);
                                                    $totalSell = number_format($totalSellAmount['sellAmount'],2);
                                                    echo (''.isset($totalSell) ? $totalSell : 0 .''); ?> KRW
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td class=" last text-center" style="vertical-align: middle">
                                    <div class="dropdown">
                                        <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown"><?= __('action');?><span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <li><a href="javascript:void(0)" onclick="removeEmail(<?php echo $data['id'] ?>)" class="btn btn-xs"><i class="fa fa-close"></i> <?= __('Remove Email Auth');?></a></li>
                                            <li><a href="javascript:void(0)" onclick="removeBank(<?php echo $data['id'] ?>)" class="btn btn-xs"><i class="fa fa-close"></i> <?= __('Remove Bank Auth');?></a></li>
                                            <li><a href="javascript:void(0)" onclick="removeOTP(<?php echo $data['id'] ?>)" class="btn btn-xs"><i class="fa fa-close"></i> <?= __('Remove OTP Auth');?></a></li>
                                        </ul>
                                    </div>
                                </td>
                                <td class="text-center" style="vertical-align: middle">
                                    <?php
										$front1 = '✗';
										$front2 = __('Not Uploaded');
										$back1 = '✗';
										$back2 = __('Not Uploaded');
										$backChange = '';
										$photo1 = '✗';
										$photo2 = __('Not Uploaded');
										$ScanCopyChange = '';

										if(!empty($data['id_document_front'])){
											$front1 = "<a target='_blank' href='".BASEURL."uploads/id_verification/{$data['id_document_front']}' ><img src='{$webroot}uploads/id_verification/{$data['id_document_front']}' width=50 /></a>";
											$front2 = $statusArr[$data['id_document_status']].$idDocumnetRejectedReason;
										}

										if(!empty($data['id_document_back'])){
											$back1 = "<a target='_blank' href='".BASEURL."uploads/id_verification/{$data['id_document_back']}' ><img src='{$webroot}uploads/id_verification/".$data['id_document_back']."' width=50 /></a>";
											$back2 = $statusArr[$data['id_document_status']].$idDocumnetRejectedReason;
										}
										
										if ($statusArr[$data['id_document_status']].$idDocumnetRejectedReason != "Not Uploaded"){
											$idDoc = "id_document";
											$backChange = "<a href='javascript:void(0);' onclick='change_status(".'"'.$data['id'].'","'.$idDoc.'"'.")'>".__('Change')."</a>";
										}

										if(!empty($data['scan_copy'])){
											$photo1 = "<a target='_blank' href='".BASEURL."uploads/id_verification/{$data['scan_copy']}' ><img src='{$webroot}uploads/id_verification/".$data['scan_copy']."' width=50 /></a>";
											$photo2 = $statusArr[$data['scan_copy_status']].$scanCopyRejectedReason;
										}

										if($statusArr[$data['scan_copy_status']].$scanCopyRejectedReason != "Not Uploaded"){
											$scanDoc = "scan_copy";
											$ScanCopyChange = "<a href='javascript:void(0);' onclick='change_status(".'"'.$data['id'].'","'.$scanDoc.'"'.")'>".__('Change')."</a>";
										}
									?>
										<table class="table table-striped">
                                            <tbody>
                                                <tr>
                                                    <td><b><?=__('ID Front: ');?></b><?=$front1;?></td>
                                                    <td><b><?=__('Reason: ');?></b><?=__($front2);?></td>
                                                    <td></td>
                                                </tr> 
                                                <tr>
                                                    <td><b><?=__('ID Back: ');?></b><?=$back1;?></td>
                                                    <td><b><?=__('Reason: ');?></b><?=__($back2);?></td>
                                                    <td><b><?=__('ID Document Status: ');?></b><?=__($backChange);?></td>
                                                </tr> 
                                                <tr>
                                                    <td><b><?=__('Scanned Photo: ');?></b><?=$photo1;?></td>
                                                    <td><b><?=__('Reason: ');?></b><?=__($photo2);?></td>
                                                    <td><b><?=__('Scanned Photo Status: ');?></b><?=__($ScanCopyChange);?></td>
                                                </tr>
                                            </tbody>
                                        </table>
							        </td>
                                </tr>
                            <?php $count++; } ?>
                        <?php  if(count($users->toArray()) < 1) {
                            echo "<tr class='even'><td colspan = '10'>No record found</td></tr>";
                        } ?>
                        </tbody>
                    </table>
                    <?php 
						$searchArr = [];
						foreach($this->request->query as $singleKey=>$singleVal){
							$searchArr[$singleKey] = $singleVal;
						}
						$this->Paginator->options(array('url' => array('controller' => 'Users', 'action' => 'userdetails')+$searchArr));
                        echo "<div class='pagination' style = 'float:right'>";

                        // the 'first' page button
                        $paginator = $this->Paginator;
                        echo $paginator->first(__("First"));

                        // 'prev' page button,
                        // we can check using the paginator hasPrev() method if there's a previous page
                        // save with the 'next' page button
                        if($paginator->hasPrev()){
                            //echo $paginator->prev(__("Prev"));
                        }

                        // the 'number' page buttons
                        echo $paginator->numbers(array('modulus' => 9));

                        // for the 'next' button
                        if($paginator->hasNext()){
                            //echo $paginator->next(__("Next"));
                        }

                        // the 'last' page button
                        echo $paginator->last(__("Last"));

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
<input type="hidden" id="user_name_search" name="" value="<?=$this->request->query('user_name');?>">
<input type="hidden" id="user_email_search" name="" value="<?=$this->request->query('user_email');?>">
<script>
	$(document).ready(function() {
		user_search_select2('user_name'); /* user name search */
		username_ajax_check('user_name'); // 검색 후 selected 처리
		user_email_select2('user_email'); /* user email search */
		email_ajax_check('user_email'); // 검색 후 selected 처리
		datepicker_set('start-date');
		datepicker_set('end-date');

		$("#update_status_op").change(function(){
			var getVal = $(this).val();
			if(getVal==="R"){
				$(".reason").show();
			} else {
				$(".reason").hide();
			}
		});
	});

	function checkConfrim(getdata){
		if(confirm("Are You Really want to impersonate ?")){
			url = '/front/users/impersonate/'+getdata;
			
			window.open(url, '_blank');
		}
		else {
			return false;
		}
		
	}
		
	function delete_section(id){
		bootbox.confirm("Are you sure?", function(result) {
			if(result == true){
				jQuery.ajax({ 
					//url: 'delete',
					url: '<?php echo $this->Url->build(['controller'=>'users','action'=>'deleteProgram']); ?>',
					beforeSend: function(xhr){
						xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
					},
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
					beforeSend: function(xhr){
						xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
					},
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
					beforeSend: function(xhr){
						xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
					},
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
					beforeSend: function(xhr){
						xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
					},
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
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
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
					beforeSend: function(xhr){
						xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
					},
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