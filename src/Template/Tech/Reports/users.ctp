<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><a href="<?php echo $this->Url->build(['controller'=>'reports','action'=>'users']);  ?>"><?=__("Users");?> &nbsp;<small> <?=__("Listing");?></small></a> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i><?=__("Home");?></a></li>
            <li class="active"><a href="<?php echo $this->Url->build(['controller'=>'reports','action'=>'users']);  ?>"><?=__("Users");?></a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-tables">
            <div class="w3l-table-info agile_info_shadow">
				<div class="clearfix"></div>
				<form id="frm" method="get" >
                    <input type="hidden" id="log_block_users" name="log_block_users" value="<?=$this->request->query('log_block_users');?>">
                    <input type="hidden" id="annual_members" name="annual_members" value="<?=$this->request->query('annual_members');?>">
                    <input type="hidden" id="deposit_disable_users" name="deposit_disable_users" value="<?=$this->request->query('deposit_disable_users');?>">
					<input type="hidden" id="export" name="export"  />
					<div class="form-group">
                        <div id="selectuser" class="col-md-2 col-sm-2 col-xs-12">
                            <?php  echo $this->Form->input('user_name',array('empty'=>__('Please select user'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','id'=>"user_name")); ?>
                        </div>
                        <div id="selectuseremail" class="col-md-2 col-sm-2 col-xs-12">
                            <?php  echo $this->Form->input('user_email',array('empty'=>__('Please select email'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','id'=>"user_email")); ?>
                        </div>
						<div class="col-md-2 col-sm-2 col-xs-12">
							<?php  echo $this->Form->input('start_date',array('placeholder'=>__('Start Date'),'class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text','value'=>$this->request->query('start_date'))); ?>
						</div>
						<div class="col-md-2 col-sm-2 col-xs-12">
							<?php  echo $this->Form->input('end_date',array('placeholder'=>__('End Date'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text','value'=>$this->request->query('end_date'))); ?>
						</div>
						<div class="col-md-2 col-sm-2 col-xs-12">
							<?php  //echo $this->Form->input('pagination',array('empty'=>'No of record','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>array(10=>10,25=>25,50=>50,100=>100))); ?>
							<select id="pagination" name="pagination" class="form-control col-md-7 col-xs-12">
								<option value=""><?= __('No of records');?></option>
								<option value="10" <?php if($this->request->query('pagination')==10){echo "selected";}?>>10</option>
								<option value="25" <?php if($this->request->query('pagination')==25){echo "selected";}?>>25</option>
								<option value="50" <?php if($this->request->query('pagination')==50){echo "selected";}?>>50</option>
								<option value="100" <?php if($this->request->query('pagination')==100){echo "selected";}?>>100</option>
							</select>
							
						</div>
						<div class="col-md-1 col-sm-1 col-xs-12">
							<button type="submit" class="btn btn-success"><?= __('Search');?></button>
						</div>
                        <div class="col-md-2 col-sm-2 col-xs-12 token-button-list-container">
                            <button type="button" onclick="sort('log_block_users','B')"  class="btn btn-tokenss <?php if($this->request->query('log_block_users') == 'B') {echo 'selected';}?>"><?= __('Login Blocked Users');?></button>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12 token-button-list-container">
                            <button type="button" onclick="sort('annual_members','Y')" class="btn btn-tokenss <?php if($this->request->query('annual_members') == 'Y') {echo 'selected';}?>"><?= __('Annual Members');?></button>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12 token-button-list-container">
                            <button type="button" onclick="sort('deposit_disable_users','Y')" class="btn btn-tokenss <?php if($this->request->query('deposit_disable_users') == 'Y') {echo 'selected';}?>"><?= __('Deposit Disabled Users');?></button>
                        </div>
					</div>
				</form>
				<div class="clearfix"></div>
                    <h3 class="w3_inner_tittle two"></h3>
                   <div class="dropdown m-b-15">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><?=__("Export");?><span class="caret"></span></button>
                        <ul class="dropdown-menu">
                          <!--  <li><a href="javascript:void(0)" onclick="export_f('e')">Excel</a></li>-->
                            <li><a href="javascript:void(0)" onclick="export_f('c')">CSV</a></li>
                        </ul>
                    </div>
                    <div class="table-responsive">
                    <table id="table-two-axis" class="two-axis table" >
                        <thead>
							<tr style="text-align: center;vertical-align: middle">
								<th  style="text-align: center;vertical-align: middle">#</th>
								<th  style="text-align: center;vertical-align: middle"><?= __('Name'); ?></th>
								<th  style="text-align: center;vertical-align: middle"><?= __('Email'); ?></th>
								<th  style="text-align: center;vertical-align: middle"><?= __('Annual Membership'); ?></th>
								<th  style="text-align: center;vertical-align: middle"><?= __('Annual Membership Date'); ?></th>
								<th  style="text-align: center;vertical-align: middle"><?= __('Membership Expiry Date'); ?></th>
								<th  style="text-align: center;vertical-align: middle"><?= __('User Level'); ?></th>
								<th  style="text-align: center;vertical-align: middle"><?= __('Disable Deposit'); ?></th>
								<th  style="text-align: center;vertical-align: middle"><?= __('Phone Number'); ?></th>
								<th  style="text-align: center;vertical-align: middle"><?= __('Date of Registration'); ?> </th>
								<th class="column-title"  style="text-align: center;vertical-align: middle"><?= __('Status'); ?> </th>
								<th class="column-title"  style="text-align: center;vertical-align: middle"><?= __('Login Block'); ?> </th>
								<th class="column-title no-link last"  style="text-align: center;vertical-align: middle"><span class="nobr"><?= __('action'); ?></span>
							</tr>
                        </thead>
                        <tbody>
                        <?php
                        $count= $serial_num;
                        foreach($users->toArray() as $k=>$data){
							$this->add_system_log(200, $data['id'], 1, '고객 목록 조회');
							$kyArr = [''=>'Not Uploaded','P'=>'Pending','Y'=>'Completed','N'=>'Rejected'];
						
							if($k%2==0) $class="odd";
							else $class="even";
                        ?>
                        <tr class="<?=$class?>" id="user_row_<?= $data['id']; ?>" style="text-align: center;vertical-align: middle">
                            <td><?=$count?></td>
                            <td><a href="javascript:void(0)" onclick="unmasking(this,'N',<?= $data['id']; ?>)" class="text-dark"><?= $this->masking('N',$data['name']); ?></a></td>
                            <td><a href="javascript:void(0)" onclick="unmasking(this,'E',<?= $data['id']; ?>)" class="text-dark"><?= $this->masking('E',$data['email']); ?></a></td>
                            <td><?php if ($data['annual_membership'] == 'Y'){
                                echo $this->Form->checkbox('membership',['id'=>'membership_'.$data['id'].'','value'=>'Y','checked'=>true,'onChange'=>'membership_change('.$data['id'].');']);
                                } else {
                                    echo $this->Form->checkbox('membership',['id'=>'membership_'.$data['id'].'','value'=>'N','checked'=>false,'onChange'=>'membership_change('.$data['id'].');']);
                                }
                                    ?></td>
                            <td id="ann_mem_date_<?= $data['id'] ?>"><?php if(!empty($data['annual_membership_date'])) {echo $data['annual_membership_date']->format('Y-m-d H:i:s');} else {echo "";} ?></td>
                            <td id="mem_expire_<?= $data['id'] ?>"><?php if(!empty($data['membership_expires_at'])) {echo $data['membership_expires_at']->format('Y-m-d H:i:s');} else {echo "";}  ?></td>
                            <td><?php echo $data['user_level']; ?></td>
                            <td><?php if ($data['deposit'] == 'Y'){
                                    echo $this->Form->checkbox('deposit',['id'=>'deposit_'.$data['id'].'','value'=>'Y','checked'=>true,'onChange'=>'deposit_change('.$data['id'].');']);
                                } else {
                                    echo $this->Form->checkbox('deposit',['id'=>'deposit_'.$data['id'].'','value'=>'N','checked'=>false,'onChange'=>'deposit_change('.$data['id'].');']);
                                }
                                ?></td>
                            <td><a href="javascript:void(0)" onclick="unmasking(this,'P',<?= $data['id']; ?>)" class="text-dark"><?php echo $this->masking('P',$data['phone_number']); ?></a></td>
                            <td><?php echo $data['created']->format('Y-m-d H:i:s'); ?></td>
							<td class=" ">
								<input type="hidden" id="user_status_<?= $data['id'] ?>" value ="<?= $data['enabled']; ?>" />
								<a href="javascript:void(0)" id="status_id_<?= $data['id']; ?>" onclick="change_user_status(<?php echo $data['id'] ?>)">
								<?php  if($data['enabled'] == 'Y'){
									echo '<button type="button" class="btn btn-success btn-xs">'.__("Active").'</button>'; 
								}else{
									echo '<button type="button" class="btn btn-danger btn-xs">'.__("Deactive").'</button>';
								} ?></a>
							</td>
							<td class="">
								<?php  if($data['user_status'] == 'B'){
									echo '<button type="button" class="btn btn-danger btn-xs" style="cursor: default">'.__("Block").'</button>'; 
								}else{
									echo '';
								} ?></a>
							</td>
							<td class=" last">
								<div class="dropdown">
								  <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown"><?=__("Show Action");?>
								  <span class="caret"></span></button>
								  <ul class="dropdown-menu width100" style="min-width: 100px;">
									<!--<li><a href="<?php //echo $this->Url->build(['controller'=>'Transactions','action'=>'translist',$data['id']]); ?>"  class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> All ETH Transactions </a></li>
									<li><a href="<?php //echo $this->Url->build(['controller'=>'Transactions','action'=>'alltranslist',$data['id'],2]); ?>"  class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> ETH Transactions </a></li>
									<li><a href="<?php //echo $this->Url->build(['controller'=>'Transactions','action'=>'alltranslist',$data['id'],3]); ?>"  class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> RAM Transactions </a></li>
									<li><a href="<?php //echo $this->Url->build(['controller'=>'Transactions','action'=>'alltranslist',$data['id'],4]); ?>"  class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> ADMC Transactions </a></li>-->
									<li class="width100"><a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'profile',$data['id']]); ?>"  class="btn btn-info btn-xs text-left text-white p-l-5" ><i class="fa fa-pencil"></i> <?=__('Edit')?> </a></li>
									<li class="width100"><a onclick="transfer(<?php echo $data['id'] ?>)"  class="btn btn-info btn-xs text-left text-white p-l-5" ><i class="fa fa-arrow-right"></i> <?=__('Transfer to CTC Wallet')?> </a></li>
									<li class="width100"><a onclick="checkConfrim('<?php echo md5($data['username']) ?>');" href="javascript:void(0);"  class="btn btn-info btn-xs text-left text-white p-l-5" ><i class="fa fa-pencil"></i> <?=__('Impersonate')?> </a></li>
									<?php
										if($data['user_status'] == 'A'){ $btn_action_name = __('Login Block'); } else if($data['user_status'] == 'B'){ $btn_action_name = __('Login Unblock'); 										}
									?>
									<li class="width100"><a href="javascript:void(0)" onclick="login_block(<?php echo $data['id'] ?>,'<?=$data['user_status'];?>')" class="btn btn-danger btn-xs text-left text-white p-l-5" ><i class="fa fa-close"></i> <?= __($btn_action_name);?> </a></li>
									<li class="width100"><a href="javascript:void(0)" onclick="delete_section(<?php echo $data['id'] ?>)" class="btn btn-danger btn-xs text-left text-white p-l-5" ><i class="fa fa-close"></i> <?=__('Delete')?> </a></li>
								  </ul>
								</div>
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
						$this->Paginator->options(array('url' => array('controller' => 'Reports', 'action' => 'users')+$searchArr));
                        echo "<div class='pagination' style = 'float:right'>";
                        $paginator = $this->Paginator;
                        echo $paginator->first(__("First"));
                        if($paginator->hasPrev()){
                            //echo $paginator->prev(__("Prev"));
                        }
                        echo $paginator->numbers(array('modulus' => 9));
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
	/* 계정 접속 */
	function checkConfrim(getdata){
		if(confirm("Are You Really want to impersonate ?")){
			url = '/front2/users/impersonate/'+getdata;
			
			window.open(url, '_blank');
		}
		return;		
	}
	/* 로그인 차단 */
	function login_block(id,type){
		var msg;
		if(type == 'A'){
			msg = "<?=__('Are you sure you want to block it?')?>";
		} else if(type == 'B'){
			msg = "<?=__('Are you sure you want to unblock it?')?>";
		}
		if(confirm(msg)){
			$.ajax({
				type: 'post',
				url: "<?= $this->Url->build(['controller' => 'reports', 'action' => 'loginblock']);  ?>",
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
				data: {'id':id,'type':type},
				success: function (resp) {
					if (resp !== "false") {
						location.reload();
					}
				}
			});
		}
	}
	/* 회원 삭제 */
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

	/* 연간회원 변경 */
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
			success: function (resp) {
				if (resp.success === "false") {
				} else {
					var result = JSON.parse(resp)
					if(result.data.timeNow !== null || result.data.timeNow !== undefined || result.data.timeNow !== '' || result.data.expiry !== null
						|| result.data.expiry !== undefined || result.data.expiry){
						$("#ann_mem_date_" + id).html(result.data.timeNow);
						$("#mem_expire_" + id).html(result.data.expiry);
					} else {
						//alert("Null");
					}

				}
			}
		});
	}
	/* 입금차단 변경 */
	function deposit_change(id) {
		if ($("#deposit_" + id).prop('checked') === true) {
			var value = "Y";
		} else if ($("#deposit_" + id).prop('checked') === false) {
			var value = "N";
		}
		$.ajax({
			type: 'post',
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
			url: '<?= $this->Url->build(['controller' => 'reports', 'action' => 'updatedeposit']);  ?>',
			data: {'id':id,'deposit': value},
			success: function (data) {

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
	/* 검색 조건 */
	function sort(sort_type,value){
		let sort_type_id = $('#'+sort_type);
		if(sort_type_id.val() == ''){
			sort_type_id.val(value);
		} else {
			sort_type_id.val('');
		}
		$('#frm').submit();
	}
	//Transfer user main account balance to CTC Wallet. 20210826 Hassam
    function transfer(id){
        let msg = "<?=__('Are you sure you want to transfer all the main account balance to CTC Wallet?')?>";
        if(confirm(msg)){
            $.ajax({
                type: 'post',
                url: "<?= $this->Url->build(['controller' => 'reports', 'action' => 'transfer']);  ?>",
                beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                },
                data: {'id':id},
                success: function (resp) {
                    let resps = JSON.parse(resp);
                    if (resps.success === "false") {
                        alert(resps.message);
                    } else {
                        alert(resps.message);
                        location.reload();
                    }
                }
            });
        }
    }
</script>