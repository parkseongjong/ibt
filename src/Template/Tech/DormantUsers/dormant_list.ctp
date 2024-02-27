<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><a href="<?php echo $this->Url->build(['controller'=>'dormantUsers','action'=>'dormantList']);  ?>"> <?= __('Dormant Users List');?></a> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home');?></a></li>
            <li class="active"><a href="<?php echo $this->Url->build(['controller'=>'dormantUsers','action'=>'dormantList']);  ?>"> <?= __('Dormant Users List');?></a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-tables">
            <div class="w3l-table-info agile_info_shadow">
				<div class="clearfix"></div>
 				<form id="frm" method="get" > 
					<input type="hidden" id="export" name="export"  />
					<div class="form-group">
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <?php  echo $this->Form->input('search_value',array('empty'=>__('Please select user'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text','value'=>$this->request->query('search_value'))); ?>
                        </div>
						<div class="col-md-2 col-sm-2 col-xs-12">
							<?php  echo $this->Form->input('start_date',array('placeholder'=>__('Start Date'),'class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text','value'=>$this->request->query('start_date'))); ?>
						</div>
						<div class="col-md-2 col-sm-2 col-xs-12">
							<?php  echo $this->Form->input('end_date',array('placeholder'=>__('End Date'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text','value'=>$this->request->query('end_date'))); ?>
						</div>
						<div class="col-md-2 col-sm-2 col-xs-12">
							<?php  //echo $this->Form->input('pagination',array('empty'=>'No of record','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>array(10=>10,25=>25,50=>50,100=>100))); ?>
							<select id="pagination" name="pagination" class="form-control col-md-7 col-xs-12" onchange="">
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
					</div>
				</form>
				<?php echo $this->Form->create('');?>
				<?php echo $this->Form->end();?>
				<div class="clearfix"></div>
                <div class="table-responsive m-t-20">
                    <table id="table-two-axis" class="two-axis table" >
                        <thead>
							<tr style="text-align: center;vertical-align: middle">
								<th  style="text-align: center;vertical-align: middle">ID</th>
								<th  style="text-align: center;vertical-align: middle"><?= __('Email'); ?></th>
								<th  style="text-align: center;vertical-align: middle"><?= __('Name'); ?></th>
								<th  style="text-align: center;vertical-align: middle"><?= __('Phone Number'); ?></th>
								<th  style="text-align: center;vertical-align: middle"><?= __('Last');?> <?=__('Login'); ?></th>
								<th  style="text-align: center;vertical-align: middle"><?= __('Created'); ?></th>
								<th  style="text-align: center;vertical-align: middle"><?= __('Annual Membership'); ?></th>
								<th  style="text-align: center;vertical-align: middle"><?= __('Dormant Date'); ?></th>
							</tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach($dormant_list as $l){
							$this->add_system_log(200, $l->id, 1, '휴면 계정 고객 목록 조회');
                        ?>
						<tr  style="text-align: center;vertical-align: middle">
                            <td><?=$l->id;?></td>
                            <td><a href="javascript:void(0)" onclick="unmasking(this,'N',<?= $l->id; ?>)" class="text-dark"><?= $this->masking('N',$l->name); ?></a></td>
							<td><a href="javascript:void(0)" onclick="unmasking(this,'E',<?= $l->id; ?>)" class="text-dark"><?= $this->masking('E',$l->email); ?></a></td>
							<td><a href="javascript:void(0)" onclick="unmasking(this,'P',<?= $l->id; ?>)" class="text-dark"><?= $this->masking('P',$l->phone_number); ?></a></td>
							<td><?= $l->last_login->format('Y-m-d H:i:s'); ?></td>
							<td><?= $l->created->format('Y-m-d H:i:s'); ?></td>
							<td><?= $l->annual_membership; ?></td>
							<td><?= $l->dormant_date->format('Y-m-d H:i:s'); ?></td>
                        </tr>	
                            <?php }  ?>
                        <?php  if(count($dormant_list) < 1) {
                            echo "<tr class='even'><td colspan = '10'>".__('No record found')."</td></tr>";
                        } ?>
                        </tbody>
                    </table>
                    <?php 
						$searchArr = [];
						foreach($this->request->query as $singleKey=>$singleVal){
							$searchArr[$singleKey] = $singleVal;
						}
						$this->Paginator->options(array('url' => array('controller' => 'DormantUsers', 'action' => 'dormantList')+$searchArr));
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
		//user_search_select2('user_name'); /* user name search */
		username_ajax_check('user_name'); // 검색 후 selected 처리
		user_email_select2('user_email'); /* user email search */
		email_ajax_check('user_email'); // 검색 후 selected 처리
		datepicker_set('start-date');
		datepicker_set('end-date');
	});

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
</script>