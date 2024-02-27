<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><a href="<?php echo $this->Url->build(['controller'=>'exchange','action'=>'sell-list']);  ?>"> <?= __('Sell List');?> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home');?></a></li>
            <li class="active"><a href="<?php echo $this->Url->build(['controller'=>'exchange','action'=>'sell-list']);  ?>"> <?= __('Sell List');?></a></li>
        </ol>
    </section>
<br/>
    <!-- Main content -->
    <?php echo $this->element('Admin/exchange_menu');?>

    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-tables">
                <div class="w3l-table-info agile_info_shadow">
                    <div class="clearfix"></div>
                    <form method="get" id="frm">
                        <input type="hidden" id="order_value" name="order_value" value="<?=$this->request->query('order_value');?>">
                        <div class="form-group">
                            <input type="hidden" name="type" value="<?=$type?>"/>
                            <div id="selectuser" class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('user_name',array('empty'=>__('Please select user'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','id'=>"user_name")); ?>
                            </div>
                            <div id="selectcoin" class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('coin_first_id',array('id'=>'coin_first_id','class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"select","options"=>[''=>__('Select coin spent')]+$coinList,'value'=>(!empty($_GET['coin_first_id']) ? $_GET['coin_first_id'] : "")));?>
                            </div>
                            <div id="selectcoin" class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('coin_second_id',array('id'=>'coin_second_id','class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"select","options"=>[''=>__('Select coin received')]+$coinList,'value'=>(!empty($_GET['coin_second_id']) ? $_GET['coin_second_id'] : "")));?>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('status',array('id'=>'status','empty'=>__('Select Status'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>[''=>__('Select Status'),'completed'=>__('Completed'),'deleted'=>__('Deleted'),'pending'=>__('Pending')],'value'=>(!empty($_GET['status']) ? $_GET['status'] : ""))); ?>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('start_date',array('placeholder'=>__('Start Date'),'class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text','value'=>(!empty($_GET['start_date']) ? $_GET['start_date'] : ""))); ?>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('end_date',array('placeholder'=>__('End Date'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text','value'=>(!empty($_GET['end_date']) ? $_GET['end_date'] : ""))); ?>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('pagination',array('empty'=>__('No of records'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>array(10=>10,25=>25,50=>50,100=>100),'value'=>(!empty($_GET['pagination']) ? $_GET['pagination'] : ""))); ?>
                                <input type="hidden" name="export" id="export" />
                            </div>
							<div class="col-md-2 col-sm-2 col-xs-12">
                                <input type="text" name="per_price" id="per_price" value="<?=$this->request->query('per_price');?>" class="form-control col-md-7 col-xs-12" placeholder="요금료"/>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12">
                                <button type="submit" class="btn btn-success"><?= __('Search') ?></button>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12">
                                <?php  echo $this->Form->input('',array('id'=>'total_amount','placeholder'=>__('Total Sold amount'),'class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text','value'=>number_format(!empty($totalBuyAmount) ? $totalBuyAmount : 0 ,2),'readonly'=>'readonly')); ?>
                            </div>
							<div class="col-md-1 col-sm-1 col-xs-12 token-button-list-container">
								<button type="button" onclick="sort('asc')"  class="btn btn-tokenss <?php if($this->request->query('order_value') == 'asc') {echo 'selected';}?>"><?= __('Ascending');?></button>
							</div>
							<div class="col-md-1 col-sm-1 col-xs-12 token-button-list-container">
								<button type="button" onclick="sort('desc')" class="btn btn-tokenss <?php if($this->request->query('order_value') == 'desc') {echo 'selected';}?>"><?= __('Descending');?></button>
							</div>
                        </div>
                        <!-- Token Button List -->
                        <div class="token-button-list-checkbox-container">
                            <label for=""><?= __('View in scroll form') ?></label>
                            <input type="checkbox" id="token-button-list-checkbox" class="token-button-list-checkbox">
                        </div>
                        <div class="token-button-list-container"> <?= __('Status: ') ?>
                            <?php 
								foreach($statusList as $k=>$data) { ?>
									<button type="button" onclick="form_submit('status','<?=$data['DISTINCT SellExchange']['status'];?>')"  class="btn btn-tokenss m-r-2 <?php if($this->request->query('status') ==  $data['DISTINCT SellExchange']['status']) {echo 'selected';}?>"><?= __($data['DISTINCT SellExchange']['status']);?></button>
                            <?php } ?>
                        </div>
                        <div class="token-button-list-container"> <?= __('Spent Coin: ');?>
                            <?php 
								foreach($coinList as $k=>$data) { ?>
 									<button type="button" onclick="form_submit('coin_first_id',<?=$k;?>)" class="btn btn-token <?php if($this->request->query('coin_first_id') == $k) {echo 'selected';}?>"><?=$data;?></button>
                            <?php } ?>
                        </div>
                        <div class="token-button-list-container"> <?= __('Received Coin: ');?>
                            <?php 
								foreach($coinList as $k=>$data) { ?>
									<button type="button" onclick="form_submit('coin_second_id',<?=$k;?>)"  class="btn btn-tokens <?php if($this->request->query('coin_second_id') ==  $k) {echo 'selected';}?>"><?=$data;?></button>
                            <?php } ?>
                        </div>
                    </form>
                    <div class="dropdown m-b-20" >
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><?= __('Export') ?>
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:void(0)" onclick="export_f('c')">CSV</a></li>
                        </ul>
                    </div>
                    <div id="main_wallet_transaction_div" class="mt10 table-responsive">
                        <table id="table-two-axis" class="two-axis table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?= __('Username'); ?></th>
                                    <th><?= __('Phone Number'); ?></th>
                                    <th><?= __('Total Amount Spent'); ?></th>
                                    <th><?= __('Amount Spent'); ?></th>
                                    <th><?= __('Coins Spent'); ?></th>
                                    <th><?= __('Total Amount Received'); ?></th>
                                    <th><?= __('Amount Received'); ?></th>
                                    <th><?= __('Coins Received'); ?></th>
                                    <th><?= __('Rate'); ?></th>
                                    <th><?= __('Admin Fees'); ?></th>
                                    <th><?= __('Status'); ?></th>
									<th><?= __('action'); ?></th>
                                    <th><?= __('Date & Time Created'); ?></th>
                                    <th><?= __('Date & Time Updated'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $count= $serial_num;
                                foreach($listing->toArray() as $k=>$data){
									$this->add_system_log(200, $data['user']['id'], 1, '판매 목록 조회 (이름, 전화번호)');
                                    if($k%2==0) $class="odd";
                                    else $class="even"; ?>

                                    <tr class="<?=$class?>">
                                        <td><?=$count?></td>
                                        <td><a href="javascript:void(0)" onclick="unmasking(this,'N',<?= $data['user']['id']; ?>)" class="text-dark"><?= $this->masking('N',$data['user']['name']); ?></a></td>
                                        <td><a href="javascript:void(0)" onclick="unmasking(this,'P',<?= $data['user']['id']; ?>)" class="text-dark"><?= $this->masking('P',$data['user']['phone_number']); ?></a></td>
                                        <td><?= number_format((float)$data['total_sell_spend_amount'],4)?> </td>
                                        <td><?= number_format((float)$data['sell_spend_amount'],4)?> </td>
                                        <td><?= $data['spendcryptocoin']['short_name']?> </td>
                                        <td><?= number_format((float)$data['total_sell_get_amount'],4)?> </td>
                                        <td><?= number_format((float)$data['sell_get_amount'],4)?> </td>
                                        <td><?= $data['getcryptocoin']['short_name']?> </td>
                                        <td><?= number_format((float)$data['per_price'],4); ?></td>
                                        <td><?= number_format((float)$data['sell_fees'],4); ?></td>
                                        <td id="status_<?=$data['id'];?>"><?= __(ucfirst($data['status']));?> </td>
										<td>
										<?php
											if($data['status'] == 'pending') {
										?>
											<button type="button" onclick="deleteOrder(<?=$data['id'];?>,'sell',<?= $data['user']['id']; ?>,this)" class="btn btn-primary btn-xs">삭제</button>
										<?php }?>
										</td>
                                        <td><?= $data['created_at']->format('Y-m-d H:i:s');?> </td>
                                        <td id="update_at_<?=$data['id'];?>"><?php if(!empty($data['update_at'])){
                                                echo $data['update_at']->format('Y-m-d H:i:s');}?> </td>
<!--											<td>--><?php // echo date('d M Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($data['created_at']))); ?><!-- </td>-->
										</tr>
										<?php $count++; } ?>
										<?php  if(count($listing->toArray()) < 1) {
											echo "<tr class='even'><td colspan = '13'>".__('No record found')."</td></tr>";
									   } ?>	
										</tbody>
									</table>
								   <?php 
										$searchArr = [];
										foreach($this->request->query as $singleKey=>$singleVal){
											$searchArr[$singleKey] = $singleVal;
										}
										$this->Paginator->options(array('url' => array('controller' => 'Exchange', 'action' => 'sellList')+$searchArr));
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
<script>
	$(document).ready(function() {
		datepicker_set('start-date');
		datepicker_set('end-date');
	    $("#coin_first_id").select2();
	    $("#coin_second_id").select2();
		user_search_select2('user_name'); /* user name search */
		username_ajax_check('user_name'); // 검색 후 selected 처리
	    tokenButtonList();
    });
	/* asc, desc form submit */
	function sort(type){
		$('#order_value').val(type);
		$('#frm').submit();
	}
	/* pending 주문 취소 */
	function deleteOrder(tableId,tableType,userId,btn){
		if (confirm("삭제하시겠습니까?")) {
			$.ajax({
				url: "/tech/exchange/deleteMyOrder",
				type: 'post',
				data:{
					"tableId" : tableId,
					"tableType" : tableType,
					"userId" : userId,
				},
				dataType: 'json',
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
				success: function (resp) {
					if(resp.success == 'true'){
						$(btn).hide();
						$('#status_'+tableId).html('deleted');
						$('#update_at_'+tableId).html(resp.message);
					} else {
						alert(resp.message);
						return;
					}
				}
			});
		}
	}
</script>