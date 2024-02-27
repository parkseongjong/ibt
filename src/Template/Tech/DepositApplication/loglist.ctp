<style>
    /* The Modal (background) */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }

    /* Modal Content/Box */
    .modal-content {
        background-color: #fefefe;
        margin: 5% auto; /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 50%; /* Could be more or less, depending on screen size */
    }
    /* The Close Button */
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }
    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
	.input-area {
		margin:30px auto;
	}
	.input-area input{
		width:45%;
		margin-left: 8px;
	}
	.blue{
		color: #5f7aff;
	}
	.red{
		color: #d40000;
	}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <a href="<?php echo $this->Url->build(['controller'=>'DepositApplication','action'=>'loglist']);  ?>"><?=__("Investment Profits Log List");?></a></h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i><?=__("Home");?></a></li>
            <li class="active"> <a href="<?php echo $this->Url->build(['controller'=>'DepositApplication','action'=>'loglist']);  ?>"><?=__("Investment Profits Log List");?></a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-tables">
                <div class="w3l-table-info agile_info_shadow">
					<div class="clearfix"></div>
					<?php echo $this->element('Admin/deposit_application_menu');?>
					<form style="padding:10px" method="get" class="form-horizontal form-label-left input_mask" id="frm">
                        <div class="form-group" style="margin-top : 15px;">
							<div id="" class="col-md-2 col-sm-2 col-xs-12">
								<select id="investment_number" name="investment_number" onchange="get_inumber_list(this.value)" class="form-control">
									<option value=""><?=__('Select Stage');?></option>
									<?php 
										foreach($stage_list as $sl){
									?>
										<option value="<?=$sl->stage;?>"<?php if($this->request->query('investment_number')==$sl->stage){echo "selected";}?>><?=$sl->stage;?><?=__('Stage');?></option>
									<?php 
										}
									?>
								</select>
                            </div>
							<div id="" class="col-md-2 col-sm-2 col-xs-12">
								<select id="type" name="type" onchange="get_inumber_list(this.value)" class="form-control">
									<option value=""><?=__('Select Type');?></option>
									<option value="S" <?php if($this->request->query('type') == 'S'){echo 'selected';}?>><?=__("Deposit");?></option>
									<option value="W" <?php if($this->request->query('type') == 'W'){echo 'selected';}?>><?=__("Withdrawal");?></option>
								</select>
                            </div>
							<div id="search" class="col-md-2 col-sm-2 col-xs-12">
								<input type="text" id="search_value" name="search_value" value="<?= $this->request->query('search_value'); ?>" class="form-control col-md-7 col-xs-12">
                            </div>
							<div class="col-md-2 col-sm-2 col-xs-12">
								<button type="submit" class="btn"><?=__('Search');?></button>
								<button type="button" class="btn btn-primary" onclick="form_reset()" style="margin-left:3px;" ><?=__('Reset');?></button>
                            </div>
                        </div>
						<input type="hidden" id="sort_value" name="sort_value" value="<?= $this->request->query('sort_value'); ?>">
						<input type="hidden" id="order_value" name="order_value" value="<?= $this->request->query('order_value'); ?>">
						<input type="hidden" id="page" name="page" value="<?= $this->request->query('page'); ?>">
                    </form>
					<div id="transferHistory" class="mt10 table-responsive" style="margin-top:50px;">
						<table class="two-axis table" id="historyData">
							<thead style="background: #d3ccea; font-size: 16px;">
								<tr>
									<th style="color:#fff"><a href="javascript:void(0)" onclick="sort('DepositApplicationLog.id')">No</a></th>
									<th style="color:#fff"><?=__("Investment Stage");?></th>
									<th style="color:#fff"><a href="javascript:void(0)" onclick="sort('list_id')"><?=__("Investment Service");?> <?=__("Number");?></a></th>
									<th style="color:#fff"><a href="javascript:void(0)" onclick="sort('u.name')"><?=__("Username");?></a></th>
									<th style="color:#fff"><a href="javascript:void(0)" onclick="sort('amount')"><?=__("Amount");?></a></th>
									<th style="color:#fff"><?=__("Type");?></th>
									<th style="color:#fff"><?=__("Created");?></th>
								</tr>
							</thead>
							<tbody id="transferHistoryList">
								<?php foreach($log_list as $l) { 
									$this->add_system_log(200, $l->user_id, 1, '고객 투자 수익금 로그 조회 (이름)');
									$type = '';
									$class= '';
									if($l->type == 'S'){
										$type = __("Deposit");
										$class= 'blue';
									} else if($l->type == 'W'){
										$type = __("Withdrawal");
										$class= 'red';
									}
								?>
									<tr>
										<td><?=$l->id;?></td>
										<td><?=$l->investment_number;?></td>
										<td><?=$l->list_id;?></td>
										<td><a href="javascript:void(0)" onclick="unmasking(this,'N',<?= $l->user_id; ?>)" class="text-dark"><?= $this->masking('N',$l->u['name']);?></a></td>
										<td><?=number_format($l->amount);?> <?=__("WON");?></td>
										<td class="<?=$class;?>"><?=$type;?></td>
										<td><?=$l->created;?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
						 <?php 
							$searchArr = [];
							foreach($this->request->query as $singleKey=>$singleVal){
								$searchArr[$singleKey] = $singleVal;
							}
							$this->Paginator->options(array('url' => array('controller' => 'DepositApplication', 'action' => 'loglist')+$searchArr));
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
<script>
	function get_inumber_list(){
		$('#frm').submit();
	}
	function form_reset(){
		$('#frm')[0].reset();
		$('#investment_number').prop('selectedIndex',0);
		$('#type').prop('selectedIndex',0);
		$('#search_value').val('');
		$('#sort_value').val('');
		$('#order_value').val('');
		$('#page').val('');
		$('#frm').submit();
	}
	function sort(order_value){
		var sort_value = $('#sort_value').val();
		if(sort_value == 'desc'){
			sort_value = 'asc';
		} else {
			sort_value = 'desc';
		}
		$('#sort_value').val(sort_value);
		$('#order_value').val(order_value);
		$('#frm').submit();
	}
</script>

