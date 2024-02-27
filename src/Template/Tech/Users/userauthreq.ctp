<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'userauthreq']);  ?>"><?= __('Users Auth Requested List');?> </a> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home');?></a></li>
            <li class="active"> <a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'userauthreq']);  ?>"><?= __('Users Auth Requested List');?></a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-tables">
                <div class="w3l-table-info agile_info_shadow">
                    <div class="clearfix"></div>
                    <form method="get" id="frm" class="form-horizontal form-label-left input_mask">
                        <div class="form-group">
                            <div id="selectrec" class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('user_id',array('empty'=>__('Please select record number'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','id'=>"list_no")); ?>
                            </div>
                            <div id="selectuser" class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('user_name',array('empty'=>__('Please select user'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','id'=>"user_name")); ?>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <?php  echo $this->Form->input('start_date',array('placeholder'=>__('Start Date'),'class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text','value'=>(!empty($_GET['start_date']) ? $_GET['start_date'] : ""))); ?>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <?php  echo $this->Form->input('end_date',array('placeholder'=>__('End Date'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text','value'=>(!empty($_GET['end_date']) ? $_GET['end_date'] : ""))); ?>
                                <input type="hidden" name="export" id="export" />
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12">
                                <button type="submit" class="btn btn-success"><?= __('Search');?></button>
                            </div>
                        </div>
                    </form>
                    <div class="dropdown m-b-20">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><?= __('Export');?>
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:void(0)" onclick="export_f('c')">CSV</a></li>
                        </ul>
                    </div>
	                <?= $this->Flash->render() ?>
                    <div id="transferHistory" class="mt10 table-responsive">
                        <table class="two-axis table" id="historyData">
                            <thead style="background: #d3ccea;    font-size: 16px;">
								<tr >
									<th style="color:#fff"><?= __('#')?></th>
									<th style="color:#fff"><?= __('User ID')?></th>
									<th style="color:#fff"><?= __('Name')?></th>
									<th style="color:#fff"><?= __('Phone Number')?></th>
									<th style="color:#fff"><?= __('Email')?></th>
									<th style="color:#fff"><?= __('Bank Name')?></th>
									<th style="color:#fff"><?= __('Bank Account Number')?></th>
									<th style="color:#fff"><?= __('Requests')?></th>
									<th style="color:#fff"><?= __('Action')?></th>
									<th style="color:#fff"><?= __('Created')?></th>
									<th style="color:#fff"><?= __('Updated')?></th>
								</tr>
                            <thead>
                            <tbody id="transferHistoryList">
                            <?php

                            foreach($listing->toArray() as $k=>$data){
								$this->add_system_log(200, $data['user_id'], 1, '고객 수정 요청 목록 조회 (이름, 전화번호, 이메일, 계좌)');
                                if($k%2==0) $class="odd";
                                else $class="even";

                                ?>
                                <tr class="<?=$class?>" id="user_row_<?= $data['user_id']; ?>">
                                    <td><?= $data['id']; ?></td>
                                    <td><?= $data['user_id']; ?></td>
									<td><a href="javascript:void(0)" onclick="unmasking(this,'NN',<?= $data['id']; ?>)" class="text-dark"><?= $this->masking('N',$data['user_name']); ?></a></td>
									<td><a href="javascript:void(0)" onclick="unmasking(this,'NP',<?= $data['id']; ?>)" class="text-dark"><?= $this->masking('P',$data['user_phone_number']); ?></a></td>
									<td><a href="javascript:void(0)" onclick="unmasking(this,'NE',<?= $data['id']; ?>)" class="text-dark"><?php if(!empty($data['user_email'])){ echo $this->masking('E',$data['user_email']);} ?></a></td>
                                    <td><?= $data['user_bank_name'] != 'NULL' ? __($data['user_bank_name']) : ''; ?></td>
									<td><a href="javascript:void(0)" onclick="unmasking(this,'NB',<?= $data['id']; ?>)" class="text-dark"><?= $this->masking('B',$this->Decrypt($data['user_account_number'])); ?></a></td>
                                    <td><?php 
										$requestType = '';
										if ($data['request'] == "emailAuth_change"){
											$requestType = 'email';
											echo __("Email Change Request");
                                        }
                                        if ($data['request'] == "bankAuth_change"){
											$requestType = 'bank';
                                            echo __("Bank Information Change Request");
                                        }
                                        if ($data['request'] == "otpAuth_change"){
											$requestType = 'otp';
                                            echo __("OTP Change Request");
                                        }
                                        ?>
									</td>
                                    <td class=" ">
                                        <?php
											if($data['status'] == "Pending"){
										?>
											<a href="javascript:void(0)" onclick="confirm_change_auth(this,<?=$data['user_id'];?>,<?=$data['id'];?>,'<?=$requestType;?>')" class="btn btn-xs"><?=__("Approval");?></a>
										<?php 
											} else { 
												echo __('Change Request Completed');
											}
										?>
                                    </td>
                                    <td><?= $data['created'] != null ? $data['created']->format('Y-m-d H:i:s') : '';?> </td>
									<td><?= $data['updated'] != null ? $data['updated']->format('Y-m-d H:i:s') : '';?> </td>
                                </tr>
                                <?php } ?>
                            <?php  if(count($listing->toArray()) < 1) {
                                echo "<tr class='even'><td colspan = '10'>".__('No record found')."</td></tr>";
                            } ?>
                            </tbody>
                        </table>
                        <?php
							$searchArr = [];
							foreach($this->request->query as $singleKey=>$singleVal){
								$searchArr[$singleKey] = $singleVal;
							}
							$this->Paginator->options(array('url' => array('controller' => 'users', 'action' => 'userauthreq')+$searchArr));
							echo "<div class='pagination' style = 'float:right'>";
							$paginator = $this->Paginator;
							echo $paginator->first(__("First"));
							if($paginator->hasPrev()){
								//echo $paginator->prev(__("Prev"));
							}
							echo $paginator->numbers(array('modulus' =>9));
							if($paginator->hasNext()){
								//echo $paginator->next(__("Next"));
							}
							echo $paginator->last(__("Last"));
							echo "</div>";
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<input type="hidden" id="user_name_search" name="" value="<?=$this->request->query('user_name');?>">
<script>
    $(document).ready(function(){
        datepicker_set('start-date');
		datepicker_set('end-date');
        list_select2('list_no','userauthreq');

        user_search_select2('user_name'); /* user name search */
		username_ajax_check('user_name'); // 검색 후 selected 처리

    });
	//confirmChangeAuth
	function confirm_change_auth(getThis, userId, id, type){
		bootbox.confirm("<?= __('Are you sure to applove?');?>", function(result) {
            if(result === true){
                jQuery.ajax({
                    //url: 'delete',
                    url: "<?php echo $this->Url->build(['controller'=>'users','action'=>'confirmChangeAuth']); ?>",
					beforeSend: function(xhr){
						xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
					},
                    data: {
						"userId" : userId,
						"id" : id,
						"type" : type
					},
                    type: 'POST',
					dataType : 'json',
                    success: function(data) {
						let msg_type = 'error';
						let msg_title = 'fail';
                        if(data.success == 'true'){
                            jQuery(getThis).parent().html("Change Request Completed");
							msg_type = 'success';
							msg_title = "<?= __('Success!');?>";
                        }
						new PNotify({
							title: msg_title,
							text: data.message,
							type: msg_type,
							styling: 'bootstrap3',
							delay:1200
						});
                    },
                    error: function (request) {
                        new PNotify({
                            title: '<?= __('Error');?>',
                            text: '<?= __('This record is being referenced at other place. So, you cannot modify it.');?>',
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