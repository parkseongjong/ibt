<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><a href="<?php echo $this->Url->build(['controller'=>'settings','action'=>'adminIpList']);  ?>"> <?= __('Admin Ip List');?> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home');?></a></li>
            <li class="active"><a href="<?php echo $this->Url->build(['controller'=>'settings','action'=>'adminIpList']); ?>"> <?= __('Admin Ip List');?></a></li>
        </ol>
    </section>
	<br/>
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-tables">
                <div class="w3l-table-info agile_info_shadow">
                    <div class="clearfix"></div>
                    <?php echo $this->Form->create('',array('method'=>'post',"id"=>"frm"));?>
                        <div class="form-group">
                            <div class="col-md-2 col-sm-2 col-xs-12">
								<input type="text" id="access_ip" name="access_ip" value="" class="form-control col-md-7 col-xs-12" placeholder="IP를 추가해주세요">
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12">
                                <button type="button" class="btn btn-success" onclick="add_ip()"><?= __('Add') ?></button>
                            </div>
                        </div>
                    </form>
					<div class="clearfix"></div>
					<?= $this->Flash->render(); ?>
                    <div class="mt10 table-responsive">
                        <table id="table-two-axis" class="two-axis table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th><?= __('Access Ip'); ?></th>
                                    <th><?= __('Status'); ?></th>
                                    <th><?= __('Created'); ?></th>
                                    <th><?= __('Updated'); ?></th>
                                    <th><?= __('Last Admin Id'); ?></th>
									<th><?= __('삭제'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                foreach($list as $l){ 
									$status = '미사용';
									$btn_class = 'btn-danger';
									if($l->status == 0){
										$status = '사용';
										$btn_class = 'btn-primary';
									} 
							?>
									<tr>
										<td><?= $l->id;?></td>
										<td><?= $l->access_ip ;?></td>
										<td><button type="button" onclick="change_status(<?=$l->id;?>,<?=$l->status;?>)" class="btn btn-xs <?=$btn_class;?>"><?= $status ;?></button></td>
										<td><?= $l->created->format('Y-m-d H:i:s');?></td>
										<td><?= $l->updated->format('Y-m-d H:i:s');?></td>
										<td><?= $l->last_id ;?></td>
										<td><button type="button" onclick="delete_ip(<?=$l->id;?>)" class="btn btn-xs btn-danger"><?= __('삭제');?></button></td>
									</tr>
							<?php } ?>
							<?php  if(count($list) < 1) {
								echo "<tr class='even'><td colspan = '6'>".__('No record found')."</td></tr>"; } ?>	
							</tbody>
						</table>
                    </div>
                </div>
            </div>
        </div>
    </section> 
  </div>
<script>
	function add_ip(){
		if($('#access_ip').val() == ''){
			alert('ip를 입력해주세요');
			$('#access_ip').focus();
			return;
		}
		$('#frm').submit();
	}
	function change_status(id,status){
		if(confirm('변경하시겠습니까?')){
			$.ajax({
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
				url: "/tech/settings/ipstatuschange",
				type:'POST',
				data: {
					"id" : id,
					"status" : status
				},
				success: function(resp) {
					location.reload();
				},
				error: function (e) {
				}
			});
		}
	}
	function delete_ip(id){
		alert('준비중입니다.');
	}
</script>