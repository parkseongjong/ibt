<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <a href="<?php echo $this->Url->build(['controller'=>'reports','action'=>'kyclist']);  ?>"><?= __('Users'); ?> <small><?= __(' Listing'); ?></small></a> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home'); ?></a></li>
            <li class="active"><a href="<?php echo $this->Url->build(['controller'=>'reports','action'=>'kyclist']);  ?>"><?= __('Users'); ?></a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-tables">
                <div class="w3l-table-info agile_info_shadow">
                    <div class="clearfix"></div>
                    <form method="get" id="frm">
                        <div class="form-group">
                            <div id="selectuser" class="col-md-2 col-sm-2 col-xs-12">
								<?php  echo $this->Form->input('user_name',array('empty'=>__('Please select user'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','id'=>"user_name")); ?>
							</div>
							<div id="selectuseremail" class="col-md-2 col-sm-2 col-xs-12">
								<?php  echo $this->Form->input('user_email',array('empty'=>__('Please select email'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','id'=>"user_email")); ?>
							</div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('start_date',array('placeholder'=> __('Start Date'),'class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text')); ?>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('end_date',array('placeholder'=> __('End Date'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
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
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle m-t-15 m-b-15" type="button" data-toggle="dropdown"><?= __('Export'); ?>
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
								<th><?= __('Username'); ?></th>
								<th><?= __('Name'); ?></th>
								<th><?= __('Email'); ?></th>
								<th><?= __('Annual Membership'); ?></th>
								<th><?= __('User Level'); ?></th>
								<th><?= __('Category'); ?></th>
								<th><?= __('ID Type'); ?></th>
								<th><?= __('ID Number'); ?></th>
								<th><?= __('ID Front'); ?></th>
								<th><?= __('ID Back'); ?></th>
								<th><?= __('ID Document Status'); ?></th>
								<th><?= __('Scanned Documents'); ?></th>
								<th><?= __('Scanned Documents Status'); ?></th>
							</tr>
                        </thead>
                        <tbody>
                        <?php
						
						$statusArr = ['P'=>__("Pending"),'R'=>__("Requested"), 'D'=>__("Declined"),'A'=>__("Approved"),'N'=>__("Non Verified")];
                        $count= $serial_num;
						$webroot = $this->request->webroot;
                        foreach($users->toArray() as $k=>$data){
							$this->add_system_log(200, $data['id'], 1, 'KYC 목록 조회 (이름, 전화번호, 신분증 등)');
							$kyArr = ['P'=>__("Pending"),'R'=>__("Requested"), 'D'=>__("Declined"),'A'=>__("Approved"),'N'=>__("Non Verified")];//['N'=>__('Not Uploaded'),'P'=>__('Pending'),'C'=>__('Completed'),'R'=>__('Rejected')];
						
							if($k%2==0) $class="odd";
							else $class="even";
							
							$idDocumnetRejectedReason = ($data['id_document_status'] =="R" || $data['id_document_status'] =="D" && !empty($data['id_document_reject_reason'])) ? " ( ".$data['id_document_reject_reason']." )" : "";
							$scanCopyRejectedReason = ($data['scan_copy_status'] =="R" || $data['scan_copy_status'] =="D" && !empty($data['scan_copy_reject_reason'])) ? " ( ".$data['scan_copy_reject_reason']." )" : "";
                        ?>
                        <tr class="<?=$class?>" id="user_row_<?= $data['id']; ?>">
                            <td><?=$count?></td>
							<td><a href="javascript:void(0)" onclick="unmasking(this,'P',<?= $data['id']; ?>)" class="text-dark"><?= $this->masking('P',$data['username']); ?></a></td>
							<td><a href="javascript:void(0)" onclick="unmasking(this,'N',<?= $data['id']; ?>)" class="text-dark"><?= $this->masking('N',$data['name']); ?></a></td>
							<td><a href="javascript:void(0)" onclick="unmasking(this,'E',<?= $data['id']; ?>)" class="text-dark"><?= $this->masking('E',$data['email']); ?></a></td>
                            <td><?php if ($data['annual_membership'] == 'Y'){
                                    echo $this->Form->checkbox('membership',['id'=>'membership_'.$data['id'].'','value'=>'Y','checked'=>true,'onChange'=>'membership_change('.$data['id'].');']);
                                } else {
                                    echo $this->Form->checkbox('membership',['id'=>'membership_'.$data['id'].'','value'=>'N','checked'=>false,'onChange'=>'membership_change('.$data['id'].');']);
                                }
                                ?></td>
                            <td><?= $data['user_level']; ?></td>
                            <td><?= __(ucfirst($data['category'])); ?></td>
                            <td><?= $data['id_type']; ?></td>
                            <td><?php
									if(!empty($data['id_number'])){
										echo $this->Decrypt($data['id_number']);
									}
							?></td>
                            <td>
							<?php 
								if(!empty($data['id_document_front'])){
									$userHash = $data['user_hash'];
									$access_token = $data['access_token'];
									$idFront = $data['id_document_front'];
									echo "<a target='_blank' href='https://api.basisid.com/mobcontent/{$userHash}/{$access_token}/{$idFront}' ><img src='https://api.basisid.com/mobcontent/{$userHash}/{$access_token}/{$idFront}' width=50 /></a>";
								} 
							?>
							</td>
							<td>
							<?php 
								if(!empty($data['id_document_back'])){
                                    $userHash = $data['user_hash'];
                                    $access_token = $data['access_token'];
									$idBack = $data['id_document_back'];
                                    echo "<a target='_blank' href='https://api.basisid.com/mobcontent/{$userHash}/{$access_token}/{$idBack}' ><img src='https://api.basisid.com/mobcontent/{$userHash}/{$access_token}/{$idBack}' width=50 /></a>";
								} 
							?>
							</td>
							 <td>
								<?php echo $statusArr[$data['id_document_status']].$idDocumnetRejectedReason; ?><br/>

							 </td>
							 <td>
							<?php 
								if(!empty($data['scan_copy'])){
                                    $userHash = $data['user_hash'];
                                    $access_token = $data['access_token'];
									$scanCopy = $data['scan_copy'];
                                    echo "<a target='_blank' href='https://api.basisid.com/mobcontent/{$userHash}/{$access_token}/{$scanCopy}' ><video width='50' controls='controls' preload='metadata'> <source src='https://api.basisid.com/mobcontent/{$userHash}/{$access_token}/{$scanCopy}' type='video/mp4'></video></a>";
								} 
							?>
							<td>
								<?php echo $statusArr[$data['scan_copy_status']].$scanCopyRejectedReason; ?><br/>

							</td>
                        </tr>
                            <?php $count++; } ?>
                        <?php  if(count($users->toArray()) < 1) {
                            echo "<tr class='even'><td colspan = '14'>".__('No record found')."</td></tr>";
                        } ?>
                        </tbody>
                    </table>
                    <?php 
						$searchArr = [];
						foreach($this->request->query as $singleKey=>$singleVal){
							$searchArr[$singleKey] = $singleVal;
						}
						$this->Paginator->options(array('url' => array('controller' => 'Reports', 'action' => 'kyclist')+$searchArr));
                        echo "<div class='pagination' style = 'float:right'>";
                        // the 'first' page button
                        $paginator = $this->Paginator;
                        echo $paginator->first(__('First'));

                        if($paginator->hasPrev()){
                            //echo $paginator->prev(__('Prev'));
                        }
                        // the 'number' page buttons
                        echo $paginator->numbers(array('modulus' => 9));
                        // for the 'next' button
                        if($paginator->hasNext()){
                           // echo $paginator->next(__('Next'));
                        }

                        // the 'last' page button
                        echo $paginator->last(__('Last'));

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
	 <?php echo $this->Form->create('',['url'=>['controller'=>'Reports','action'=>'kycStatusUpdate']]);?>
	 <?php echo $this->Form->input("status_type",['id'=>'status_type','type'=>'hidden']);?>
	 <?php echo $this->Form->input("status_id",['id'=>'status_id','type'=>'hidden']);?>
	
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <?php echo $this->Form->input("status",['id'=>'update_status_op','class'=>'form-control','type'=>'select','options'=>['P'=>__('Pending'),'A'=>__('Approve'),'R'=>__('Reject')]]);?>
		<label class="reason" style="display:none;"><?= __('Reason: '); ?> </label>
		<?php echo $this->Form->input("reject_reason",['class'=>'form-control reason','label'=>false,'type'=>'textarea','id'=>'reject_reason','style'=>'display:none;']);?>
      </div>
      <div class="modal-footer">
       
		<?php echo $this->Form->submit(__("Submit"),['class'=>'bnt btn-info']);?>
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
    });

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
			url: '<?= $this->Url->build(['controller' => 'reports', 'action' => 'updateMembership']);  ?>',
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
</script>