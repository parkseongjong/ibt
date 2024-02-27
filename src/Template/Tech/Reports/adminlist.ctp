<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>  <a href="<?php echo $this->Url->build(['controller'=>'reports','action'=>'adminlist']);  ?>"> <?= __('Administrators'); ?> &nbsp;<small> <?= __('List');?> </small> </a></h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home');?></a></li>
            <li class="active"> <a href="<?php echo $this->Url->build(['controller'=>'reports','action'=>'adminlist']);  ?>"><?= __('Administrators');?></a></li>
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
                                <?php  echo $this->Form->input('user_name',array('empty'=>__('Please select user'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>$usersFindList,'id'=>"search_username",'value'=>(!empty($_GET['user_name']) ? $_GET['user_name'] : ""))); ?>
                            </div>
                            <!--<div id="selectuseremail" class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('user_email',array('empty'=>__('Please select email'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>$usersEmailList,'id'=>"search_email",'value'=>(!empty($_GET['user_email']) ? $_GET['user_email'] : ""))); ?>
                            </div>-->
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('start_date',array('placeholder'=>__('Start Date'),'class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text')); ?>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('end_date',array('placeholder'=>__('End Date'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('pagination',array('empty'=>__('No of records'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>array(10=>10,25=>25,50=>50,100=>100))); ?>
                                <input type="hidden" name="export" id="export" />
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12">
                                <button type="submit" class="btn btn-success"><?= __('Search');?></button>
                            </div>
					    </div>
                    </form>
                    <div class="clearfix"></div>
                    <div class="dropdown  m-b-15">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><?= __('Export');?>
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                          <!--  <li><a href="javascript:void(0)" onclick="export_f('e')">Excel</a></li>-->
                            <li><a href="javascript:void(0)" onclick="export_f('c')">CSV</a></li>
                        </ul>
						<a class="btn btn-info" href="<?php echo $this->Url->build(['controller'=>'Users','action'=>'addadmin']) ?>"><?= __('Add new administrator');?></a>
                    </div>
                    <div class="table-responsive">
                    <table id="table-two-axis" class="two-axis table">
                        <thead>
							<tr> 
								<th><?= __('Admin Number');?></th>
								<th><?= __('ID');?></th>
								<th><?= __('Level');?></th>
								<th><?= __('Admin Name');?></th>
								<th><?= __('Email');?></th>
								<th><?= __('Phone Number');?></th>
								<th><?= __('Date of Registration');?></th>
								<th class="column-title"><?= __('Status');?> </th>
								<th class="column-title no-link last"><span class="nobr"><?= __('Action');?></span>
							</tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach($users->toArray() as $k=>$data){
							$this->add_system_log(200, $data['id'], 1, '관리자 목록 조회');
							$kyArr = [''=>'Not Uploaded','P'=>'Pending','Y'=>'Completed','N'=>'Rejected'];
						
                        if($k%2==0) $class="odd";
                        else $class="even";
                        ?>
                        <tr class="<?=$class?>" id="user_row_<?= $data['id']; ?>">
                            <td><?= $data['id'];?></td>
                            <td><?= $data['username']; ?></td>
                            <td><?= $data['username'] == 'admin' ? 'master' : $data['level']["level_name"]; ?></td>
                            <td><a href="javascript:void(0)" onclick="unmasking(this,'N',<?= $data['id']; ?>)" class="text-dark"><?= $this->masking('N',$data['name']); ?></a></td>
                            <td><a href="javascript:void(0)" onclick="unmasking(this,'E',<?= $data['id']; ?>)" class="text-dark"><?= $this->masking('E',$data['email']); ?></a></td>
                            <td><a href="javascript:void(0)" onclick="unmasking(this,'P',<?= $data['id']; ?>)" class="text-dark"><?= $this->masking('P',$data['phone_number']); ?></a></td>                            
                                                       
                            <td><?= $data['created']->format('Y-m-d H:i:s');?></td>
							<td class=" ">
								<input type="hidden" id="user_status_<?= $data['id'] ?>" value ="<?= $data['enabled']; ?>" />
								<a href="javascript:void(0)" id="status_id_<?= $data['id']; ?>" onclick="change_user_status(<?= $data['id'] ?>)">
								<?php  if($data['enabled'] == 'Y'){
									echo '<button type="button" class="btn btn-success btn-xs">'.__('ACTIVE').'</button>';
								}else{
									echo '<button type="button" class="btn btn-danger btn-xs">'.__('INACTIVE').'</button>';
								} ?></a>
							</td>
							<td class=" last">
							  <?php
								if($this->request->session()->read('Auth.User.id') == $data['id'] || $this->request->session()->read('Auth.User.id') == 1){
							  ?>
								<div class="dropdown">
								  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" id="adpass"><?= __('action');?>
								  <span class="caret"></span></button>
								  <ul class="dropdown-menu">
									<!--<li><a href="<?php //echo $this->Url->build(['controller'=>'Transactions','action'=>'translist',$data['id']]); ?>"  class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> All ETH Transactions </a></li>
									<li><a href="<?php //echo $this->Url->build(['controller'=>'Transactions','action'=>'alltranslist',$data['id'],2]); ?>"  class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> ETH Transactions </a></li>
									<li><a href="<?php //echo $this->Url->build(['controller'=>'Transactions','action'=>'alltranslist',$data['id'],3]); ?>"  class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> RAM Transactions </a></li>
									<li><a href="<?php //echo $this->Url->build(['controller'=>'Transactions','action'=>'alltranslist',$data['id'],4]); ?>"  class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> ADMC Transactions </a></li>-->
									<li><a href="<?= $this->Url->build(['controller'=>'users','action'=>'editadmin',$data['id']]); ?>"  class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> <?= __('Edit');?> </a></li>
									<!--<li><a onclick="checkConfrim('<?php //echo md5($data['username']) ?>');" href="javascript:void(0);"  class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Impersonate </a></li>-->
									<li><a href="javascript:void(0)" onclick="delete_section(<?= $data['id'] ?>)" class="btn btn-danger btn-xs"><i class="fa fa-close"></i> <?= __('Delete');?> </a></li>
								  </ul>
								</div>
							<?php }?>
							</td>
                        </tr>
                            <?php } ?>
                        <?php  if(count($users->toArray()) < 1) {
                            echo "<tr class='even'><td colspan = '10'>".__('No record found')."</td></tr>";
                        } ?>
                        </tbody>
                    </table>
                        <?php
							$searchArr = [];
							foreach($this->request->query as $singleKey=>$singleVal){
								$searchArr[$singleKey] = $singleVal;
							}
							$this->Paginator->options(array('url' => array('controller' => 'Reports', 'action' => 'adminlist')+$searchArr));
							echo "<div class='pagination' style = 'float:right'>";
							$paginator = $this->Paginator;
							echo $paginator->first(__('First'));
							if($paginator->hasPrev()){
								//echo $paginator->prev(__('Prev'));
							}
							echo $paginator->numbers(array('modulus' => 9));
							if($paginator->hasNext()){
								//echo $paginator->next(__('Next'));
							}
							echo $paginator->last(__('Last'));
							echo "</div>";
						?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
	$(document).ready(function() {
		$("#search_username").select2();
		$("#search_email").select2();
		datepicker_set('start-date');
		datepicker_set('end-date');
    });

	function delete_section(id){
		bootbox.confirm("<?= __('Are you sure?'); ?>", function(result) {
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
								  title: '<?= __('Success!'); ?>',
								  text: '<?= __('Record deleted successfully!');?>',
								  type: 'success',
								  styling: 'bootstrap3',
								  delay:1200
							  });
							
						}if(data == 'forbidden'){
							
							new PNotify({
								  title: '<?= __('403 Error'); ?>',
								  text: '<?= __('You do not have permission to perform this action');?>',
								  type: 'error',
								  styling: 'bootstrap3',
								  delay:1200
							  });
							
						}
					},
					error: function (request) {
						new PNotify({
								  title: '<?= __('Error'); ?>',
								  text: '<?= __('This record is being referenced at other place. So, you cannot delete it.'); ?>',
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
			var ques= "<?= __('Do you really want to change the status to DEACTIVE'); ?>";
			var status = "N";
			var change = '<button type="button" class="btn btn-danger btn-xs">Deactive</button>'
		}else{
			var ques= "<?= __('Do you really want to change the status to ACTIVE');?>";
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
								  title: '<?= __('Success!'); ?>',
								  text: '<?= __('Status changed successfully!'); ?>',
								  type: 'success',
								  styling: 'bootstrap3',
								  delay:1200
							  });
							
						}
						if(data == 'forbidden'){
							new PNotify({
								  title: '<?= __('403 Error'); ?>',
								  text: '<?= __('You do not have permission to perform this action'); ?>',
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