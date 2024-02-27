<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><a href="<?php echo $this->Url->build(['controller'=>'exchange','action'=>'transaction']);  ?>"> <?= __('Exchange Transactions');?> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home');?></a></li>
            <li class="active"><a href="<?php echo $this->Url->build(['controller'=>'exchange','action'=>'transaction']);  ?>"> <?= __('Transactions');?></a></li>
        </ol>
    </section>
    <br/>
    <?php echo $this->element('Admin/exchange_menu');?>
    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-tables">
                <div class="w3l-table-info agile_info_shadow">
                    <div class="clearfix"></div>
                    <form method="get" id="frm" >
                        <input type="hidden" id="order_value" name="order_value" value="<?=$this->request->query('order_value');?>">
                        <div class="form-group">
                            <input type="hidden" name="type" value="<?=$type;?>"/>
                            <div id="selectcoin" class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('coin_first_id',array('id'=>'coin_first_id','class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"select","options"=>[''=>__('Select coin spent')]+$coinList,'value'=>(!empty($_GET['coin_first_id']) ? $_GET['coin_first_id'] : "")));?>
                            </div>
                            <div id="selectcoins" class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('coin_second_id',array('id'=>'coin_second_id','class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"select","options"=>[''=>__('Select coin received')]+$coinList,'value'=>(!empty($_GET['coin_second_id']) ? $_GET['coin_second_id'] : "")));?>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12">
                                <?php  echo $this->Form->input('start_date',array('placeholder'=>__('Start Date'),'class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text','value'=>(!empty($_GET['start_date']) ? $_GET['start_date'] : ""))); ?>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12">
                                <?php  echo $this->Form->input('end_date',array('placeholder'=>__('End Date'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text','value'=>(!empty($_GET['end_date']) ? $_GET['end_date'] : ""))); ?>
                            </div>
						    <div class="col-md-2 col-sm-1 col-xs-12">
								<?php  echo $this->Form->input('pagination',array('empty'=>__('No of records'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>array(10=>10,25=>25,50=>50,100=>100))); ?>
						    </div>
							<div class="col-md-1">
                                <button type="submit" class="btn btn-success"><?= __('Search') ?></button>
                            </div>
							<div class="col-md-1 col-sm-1 col-xs-12 token-button-list-container">
								<button type="button" onclick="sort('asc')"  class="btn btn-tokenss <?php if($this->request->query('order_value') == 'asc') {echo 'selected';}?>"><?= __('Ascending');?></button>
							</div>
							<div class="col-md-1 col-sm-1 col-xs-12 token-button-list-container">
								<button type="button" onclick="sort('desc')" class="btn btn-tokenss <?php if($this->request->query('order_value') == 'desc') {echo 'selected';}?>"><?= __('Descending');?></button>
							</div>
                        </div>
						<div class="clearfix"></div>
                        <!-- Token Button List -->
                        <div class="token-button-list-checkbox-container">
                            <label for=""><?= __('View in scroll form') ?></label>
                            <input type="checkbox" id="token-button-list-checkbox" class="token-button-list-checkbox">
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
                        <!-- /Token Button List -->
				    </form>
                    <div class="clearfix"></div>
                    <div id="main_wallet_transaction_div" class="mt10 table-responsive">
                        <table id="table-two-axis" class="two-axis table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?= __('Amount');?></th>
                                    <th><?= __('Exchange Coin');?></th>
                                    <th><?= __('Price');?></th>
                                    <th><?= __('Date & Time Created');?></th>
                                    <th><?= __('Date & Time Updated');?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = $serial_num;
                                foreach($listing->toArray() as $k=>$data){
                                    if($k%2==0) $class="odd";
                                    else $class="even";
                                    $price = ($data['get_per_price']>$data['spend_per_price']) ? $data['get_per_price'] : $data['spend_per_price']; ?>
                                <tr class="<?=$class?>">
                                    <td> <?=$count?></td>
                                    <td><?php echo number_format((float)$data['get_amount'],4); ?> </td>
                                    <td><?php echo $data['spendcryptocoin']['short_name']." <i class='fa fa-exchange'></i> ".$data['getcryptocoin']['short_name']; ?> </td>
                                    <td><?php echo number_format((float)$price,4)?> </td>
                                    <td><?=$data['created_at']->format('Y-m-d H:i:s');?> </td>
                                    <td><?=$data['updated_at']->format('Y-m-d H:i:s');?> </td>
                                </tr>
                                    <?php $count++; } ?>
                                <?php  if(count($listing->toArray()) < 1) {
                                    echo "<tr class='even'><td colspan = '10'>".__('No record found')."</td></tr>";
                                } ?>
                            </tbody>
                        </table>
                        <?php $searchArr = [];
                        foreach($this->request->query as $singleKey=>$singleVal){
                            $searchArr[$singleKey] = $singleVal;
                        }
                        $this->Paginator->options(array('url' => array('controller' => 'Exchange', 'action' => 'transaction')+$searchArr));
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
                        echo "</div>"; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
	$(document).ready(function() {
		datepicker_set('start-date');
		datepicker_set('end-date');
	    $("#coin_first_id").select2();
	    $("#coin_second_id").select2();
	    tokenButtonList();
    });
	/* asc, desc form submit */
	function sort(type){
		$('#order_value').val(type);
		$('#frm').submit();
	}
</script>