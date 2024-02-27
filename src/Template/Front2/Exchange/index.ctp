<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" />
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script> -->
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php  echo $this->request->webroot ?>js/jquery-ui.min.js"></script>
<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/new_exchange.css?ti=<?php echo time(); ?>">
<script src="<?php echo $this->request->webroot ?>js/highstock.js"></script>
<?php
	$selector_id=0;
?>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p><?=__('Coming Soon.................!');?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?=__('Close');?></button>
      </div>
    </div>
  </div>
</div>
<?php if(in_array($firstCoin,["BTC",'ETH'])){ ?>
	<script>
	 $(document).ready(function(){
		 $("#myModal").modal('show');
	 })
	</script>
<?php }  ?>

<div class="container containerpp" >
	<input type="hidden" id="authUserType" value="<?= $authUserType;?>">
    <input type="hidden" id="authUserId" name="authUserId" value="<?= $authUserId; ?>" />
    <input type="hidden" name="secondCoin" id="secondCoin" value="<?= $secondCoin; ?>" />
    <input type="hidden" name="firstCoin" id="firstCoin" value="<?= $firstCoin; ?>" />
    <input type="hidden" name="secondCoinId" id="secondCoinId" value="<?= $secondCoinId; ?>" />
    <input type="hidden" name="firstCoinId" id="firstCoinId" value="<?= $firstCoinId; ?>" />
    <input type="hidden" name="baseCoinPriceInUsd" id="baseCoinPriceInUsd" value="<?= $baseCoinPriceInUsd; ?>" />
    <input type="hidden" name="binancePrice" id="binancePrice" value="<?= $binancePrice; ?>" />
    <input type="hidden" name="max_buysell_per" id="max_buysell_per" value="<?= $max_buysell_per; ?>" />
    <input type="hidden" name="min_buysell_per" id="min_buysell_per" value="<?= $min_buysell_per; ?>" />
    <input type="hidden" name="max_market_per" id="max_market_per" value="<?= $max_market_per; ?>" />
    <input type="hidden" name="min_market_per" id="min_market_per" value="<?= $min_market_per; ?>" />
    <input type="hidden" name="mid_night_price" id="mid_night_price" value="<?= $mid_night_price; ?>" />
	<input type="hidden" name="daysRemaining" id="daysRemaining" value="<?= $daysRemaining; ?>" />
	<?php 
		$krwTabClass = ($firstCoinId==20) ? 'on' : '';
		$ethTabClass = ($firstCoinId==18) ? 'on' : '';
		$usdtTabClass = ($firstCoinId==5) ? 'on' : '';
		$btcTabClass = ($firstCoinId==1) ? 'on' : '';
	?>
	<div class="exchange_frame">
		<div class="section_ex left_info">
			<ul class="coin_tab">
				<li style="width: 34%" class="<?php echo $usdtTabClass; ?>"><a href="<?= $this->Url->Build(['controller'=>'exchange','action'=>'index','TP3','USDT']); ?>" >USDT</a></li>
				<!--<li class="<?php /*echo $krwTabClass; */?>"><a href="<?php /*echo $this->Url->Build(['controller'=>'exchange','action'=>'index','TP3','KRW']); */?>" >KRW</a></li>-->
				<li style="width: 33%" class="<?php echo $ethTabClass; ?>"><a data-toggle="modal" data-target="#myModal" href="#myModal">ETH</a></li>
				<li style="width: 33%" class="<?php echo $btcTabClass; ?>"><a data-toggle="modal" data-target="#myModal" href="#myModal">BTC</a></li>

			</ul>
			<div class="opt_row">
				<label><input type="checkbox"  id="radioclick"name="radioName" value="" /> <?=__('View my own coins') ?> </label>
			</div>
			<table class="list">
				<thead>
					<tr>
						<td><?=__('Assets name') ?></td>
						<td></td>
						<td><?=__('Price') ?></td>
					</tr>
				</thead>
			</table>
			<div class="ex_left1">
				<table class="list">
					<tbody>
					<?php
						foreach($getCoinPairList as $getCoinPairSingle){
							$principalBalance = [];
							if(!empty($_SESSION['Auth']['User'])){
								$principalBalance = $this->CurrentPrice->getUserPricipalBalance($_SESSION['Auth']['User']['id'],$getCoinPairSingle['coin_first_id']);
							}
							$color = '';
							if($currentCoinPairDetail['id']==$getCoinPairSingle['id']){
								$color = "style='color:#07ff07'";
							}
							
							$symbol = '';
							if($getCoinPairSingle['cryptocoin_first']['id']==5){
								$symbol = ' $';
							}
							$getMyCustomPrice = $this->CurrentPrice->getCurrentPrice($getCoinPairSingle['cryptocoin_first']['id'],$getCoinPairSingle['cryptocoin_second']['id']);
							$tr_class_name = 'hide_currency';
							if(!empty($principalBalance)){
								$tr_class_name = 'show_currency';
							}
						  ?>
						<tr class="<?=$tr_class_name;?> <?php echo $secondCoinId==$getCoinPairSingle['cryptocoin_first']['id'] ? "on" : ""; ?>">
							<td class="left"><a  href="<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'index',$getCoinPairSingle['cryptocoin_first']['short_name'],$getCoinPairSingle['cryptocoin_second']['short_name']]); ?>" > <span class="bold"><?php echo $getCoinPairSingle['cryptocoin_first']['short_name']; ?></span> (<?php  echo $getCoinPairSingle['cryptocoin_second']['short_name']; ?>)</a></td>
							<td><span id="percent_current_price_<?php echo $getCoinPairSingle['cryptocoin_first']['short_name']."_".$getCoinPairSingle['cryptocoin_second']['short_name']; ?>"></span></td>
							<!--<td class="right"  id="current_price_<?php /*echo $getCoinPairSingle['cryptocoin_first']['short_name']."_".$getCoinPairSingle['cryptocoin_second']['short_name']; */?>"><?php /*echo round($getMyCustomPrice,4);//.$symbol; */?></td>-->
                            <td class="right"  id="current_price_<?php echo $getCoinPairSingle['cryptocoin_first']['short_name']."_".$getCoinPairSingle['cryptocoin_second']['short_name']; ?>"><?php echo $getMyCustomPrice;//.$symbol; ?></td>
						</tr>
						<?php  } ?>
					</tbody>
				</table>
			</div>
            <div class="monone">
				<div class="box_title">
					<?=__('Market breakdown') ?>
				</div>
				<table class="list">
					<thead>
						<tr>
							<td class="left"><?=__('Date') ?></td>
							<td class="left"><?=__('Price') ?></td>
							<td class="right"><?=__('Amount(WON)') ?></td>
						</tr>
					</thead>
				</table>
				<div class="ex_left2">
					<table class="list">
					<tbody class="market_history">
						<tr>
						  <td colspan=3><img src="<?php echo $this->request->webroot ?>ajax-loader.gif" /></td>
						</tr>
					</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="section_ex contents">
			<div id="days_remaining_dialog" title="<?= __('Membership Expiry Notice!');?>" style="display:none;" class="ui-dialog">
			<?php if(!empty($authUserId) && !empty($daysRemaining) && $daysRemaining != 'non_annual_membership' && $daysRemaining != 'lot_remain'){
				$lang = isset($_COOKIE['Language']) ? $_COOKIE['Language'] : 'en_US';
				if($lang == 'en_US'){
					echo "<div style='color: red'>Only <b> ".$daysRemaining." </b> day(s) remaining. Please deposit amount to renew your membership</div>";
				} else {
					echo "<div style='color: red'>멤버십 기간이 <b> ".$daysRemaining." </b> 일 남았습니다. 갱신을 원하시면 연간회원권을 구매하세요!</div>";
				}
			}?>
			<div style="margin-top:10px;">
				<button type="button" class="ui-button" onclick="popup_hide(0)"><?=__('Close')?></button>
				<button type="button" class="ui-button" onclick="popup_hide(1)"><?=__("Don't show again today")?></button>
			</div>
		</div>
		<div class="base_coin">
			<table>
				<tr>
					<td class="left" style="width:20%">
						<span class="token"><?php echo $secondCoin; ?></span>
					</td>
					<td class="right" style="width:80%">
						<ul class="results">
							<li><?=__('Prev. Close') ?><br /><span class="red" id="change_in_one_day"><img src="<?php echo $this->request->webroot ?>ajax-loader.gif" /></span></li>
							<li><?=__('Low price') ?><br /><span class="red" id="min_price"><img src="<?php echo $this->request->webroot ?>ajax-loader.gif" /></span><span class="unit"><?php echo $firstCoin; ?></span></li>
							<li><?=__('High price') ?><br /><span class="blue" id="max_price"><img src="<?php echo $this->request->webroot ?>ajax-loader.gif" /></span><span class="blue unit" ><?php echo $firstCoin; ?></span></li>
							<li><?=__('Volume') ?><br /><span class="amount red" id="current_volume"><img src="<?php echo $this->request->webroot ?>ajax-loader.gif" /></span><span class="unit"><?php echo $firstCoin; ?></span></li>
						</ul>
					</td>
				</tr>
			</table>
		</div>
		<div class="base_graph" id="container"></div>
	</div>
	<div class="section_ex right_info">
		<table class="list">
			<thead>
				<tr>
					<td style="width: 30%"><?=__('Price') ?></td>
					<td style="width: 33%"><?=__('Trx Qty') ?></td>
					<td style="width: 37%"><?=__('Total Price') ?></td>
				</tr>
			</thead>
		</table>
		<table class="result" style="display:none;">
			<thead>
				<tr class="sell">
					<td><?=__('Sell') ?> <?=__('Order balance') ?> <span class="right" id="sell_order_balance">0</span></td>
				</tr>
			</thead>
		</table>
		<div class="ex_right"  style="overflow:inherit;">
			<div  id="sell_order_show_div " class="s_sell">
				<table class="list">
					<tbody id="sellAjaxData">
					</tbody>
				</table>
			</div>
			<table class="list">
				<tbody  id="middle_current_price">	
				</tbody>
			</table>
			<div class="s_sell_b" >
				<table class="list">
					<tbody id="buyAjaxData">		
					</tbody>
				</table>
			</div>
		</div>
		<table class="result" style="display:none;">
			<thead>
				<tr class="buy">
					<td><?=__('Buy') ?> <?=__('Order balance') ?> <span class="right"  id="buy_order_balance">0</span></td>
				</tr>
			</thead>
		</table>
	</div>
	<?php echo $this->Form->create('',array('method'=>'post','id'=>'buy_form','onSubmit'=>'return false;','accept-charset'=>'utf-8','url'=>['prefix'=>'front2','controller'=>'exchange','action'=>'index',$firstCoin,$secondCoin])); ?>
	<div class="section_ex contents contents22">
		<span id="current_price" style="display:none;">0</span>
		<div class="tranx">
			<div class="buy buy_man">
				<div class="paddbs">
					<ul class="opt_row">
						<li>
							<h2><?=__('Buy') ?></h2>
						</li>
						<li style="line-height:4">
							<label><input type="radio" name="buy_control" value="buy_limit_tab" checked /> <?=__('Limits') ?> </label>
							<label><input type="radio" name="buy_control" value="buy_market_tab" /> <?=__('Market price') ?> </label>
						</li>
					</ul>
					<input type="hidden" name="type" id="submit_type" value=""/>
					<input type="hidden" name="per_price" id="submit_per_price" value=""/>
					<input type="hidden" name="volume" id="submit_volume" value=""/>
					<ul class="row won_box">
						<li style="width: auto;">
							<span class="bold"><?=__('Order available') ?></span>
						</li>
						<li style="width: auto;float:right">
							<span id="span_buy_volume_all"><img src="<?php echo $this->request->webroot ?>ajax-loader.gif" /></span> <span class="bold unit"><?=__('USD') ?>(<?php echo $firstCoin; ?>)</span>
						</li>
					</ul>
					<ul class="row input_ul" id="buy_per_price_div">
						<li class="monone">
							<?=__('Price') ?> (<?php echo $firstCoin; ?>)
						</li>
						<li>
							<div class="price2 pr28">
								<input placeholder="<?=__('Price') ?> (<?php echo $firstCoin; ?>)" type="number"  required autocomplete="false" id="buy_per_price" step="0.01" min="0.01" onkeypress="return isNumberKey(this, event);" onblur="toDecimals('buy_per_price')" oninput="calculateForm('buy_per_price','buy')"/>
								<span class="up1" id="buy_price_up" onclick="operation('increment','buy')"><i class="fa fa-caret-up"></i></span>
								<span class="up2" id="buy_price_down" onclick="operation('decrement','buy')"><i class="fa fa-caret-down"></i></span>
							</div>
						</li>
					</ul>
					<ul class="row input_ul	">
						<li class="monone">
							<?=__("Q'ty") ?> (<?php echo $secondCoin; ?>)
						</li>
						<li>
							<div class="price2 pr28">
								<input placeholder="<?=__("Q'ty") . ' (' . $secondCoin . ')'; ?>" pattern="^\d+(?:\.\d{1,2})?$" type="number" min="0.01" step="0.01" autocomplete="false" required id="buy_volume" onkeypress="return isNumberKey(this, event);" onblur="toDecimals('buy_volume')" oninput="calculateForm('buy_volume','buy')" onchange="calculateForm('buy_volume','buy')" />
								<span class="up1" id="buy_quantity_up" onclick="operationVol('increment','buy','1')"><i class="fa fa-caret-up"></i></span>
								<span class="up2" id="buy_quantity_down" onclick="operationVol('decrement','buy','1')"><i class="fa fa-caret-down"></i></span>
							</div>
						</li>
					</ul>
					<ul class="mytest " style="overflow:visible;">
						<li><a href="javascript:void(0);" onClick="setAmount(25,'buy')">25%</a></li>
						<li><a href="javascript:void(0);" onClick="setAmount(50,'buy')">50%</a></li>
						<li><a href="javascript:void(0);" onClick="setAmount(75,'buy')">75%</a></li>
						<li><a href="javascript:void(0);" onClick="setAmount(100,'buy')">100%</a></li>
					</ul>
					<ul class="row input_ul">
						<li>
							<span class="bold"><?=__('Order amount') ?></span>
						</li>
						<li>
							<input class="bold price" id="buy_total_amount" onchange="calculateForm('buy_total_amount','buy')" oninput="calculateForm('buy_total_amount','buy')" value=0 readonly /> (<?= $firstCoin; ?>)
						</li>
					</ul>
					<input type="hidden" class="form-control text-right" autocomplete="false" id="buy_admin_fee" disabled placeholder="0.5% Admin Fee">
					<?php 
						$onclick_function = 'goLogin();';
						if(!empty($authUserId)) {
							$onclick_function = "modalDialog('buy',this.value);";
						}						
					?>
					<input type="button" class="button buy buy_ntr" <?php if(!empty($authUserId)) {echo 'data-toggle="modal" data-target="#myModalBuy"';}?> onclick="<?=$onclick_function;?>" value="<?=__('Buy') ?>">
					<span id="admin_show_fee" style="float:right;font-size:12px;"> <?= __('Fees');?> - <?php echo $adminFee; ?>%</span>
				</div>
			</div>
            <div class="sell sell_man">
                <div class="paddbs">
					<ul class="opt_row">
						<li>
							<h2><?=__('Sell') ?></h2>
						</li>
						<li style=" line-height:4">
							<label><input type="radio" name="sell_control" value="sell_limit_tab" checked/> <?=__('Limits') ?> </label>
							<label><input type="radio" name="sell_control" value="sell_market_tab"  /> <?=__('Market price') ?> </label>
						</li>
					</ul>
					<ul class="row won_box">
						<li><?=__('Order available') ?></li>
						<li>
							<span id="span_sell_volume_all"><img src="<?php echo $this->request->webroot ?>ajax-loader.gif" /></span><span class="bold unit">(<?php echo $secondCoin; ?>)</span>
						</li>
					</ul>
					<ul class="row input_ul" id="sell_per_price_div">
						<li class="monone">
							<?=__('Price') ?> (<?php echo $firstCoin; ?>)
						</li>
						<li>
							<div class="price2 pr28">
								<input placeholder="<?= __('Price').' ('.$firstCoin.')'; ?>"
									   type="number"  required autocomplete="false" id="sell_per_price" step="0.01" min="0.01" onkeypress="return isNumberKey(this, event);" onblur="toDecimals('sell_per_price')" oninput="calculateForm('sell_per_price','sell')" />
								<span class="up1" id="sell_price_up" onclick="operation('increment','sell')"><i class="fa fa-caret-up"></i></span>
								<span class="up2" id="sell_price_down" onclick="operation('decrement','sell')"><i class="fa fa-caret-down"></i></span>
							</div>
						</li>
					</ul>
					<ul class="row input_ul">
						<li class="monone">
							<?=__("Q'ty") ?> (<?php echo $secondCoin; ?>)
						</li>
						<li>
							<div class="price2 pr28">
								<input type="number" pattern="^\d+(?:\.\d{1,2})?$"  placeholder="<?= __("Q'ty") . ' (' . $secondCoin . ')'; ?>"  required autocomplete="false" min="0.01" step="0.01" id="sell_volume" onkeypress="return isNumberKey(this, event);" onblur="toDecimals('sell_volume')" oninput="calculateForm('sell_volume','sell')" onchange="calculateForm('sell_volume','sell')" />
								<span class="up1" id="sell_quantity_up" onclick="operationVol('increment','sell','1')"><i class="fa fa-caret-up"></i></span>
								<span class="up2" id="sell_quantity_down" onclick="operationVol('decrement','sell','1')"><i class="fa fa-caret-down"></i></span>
							</div>
						</li>
					</ul>
					<ul class="mytest" style="overflow:visible;">
						<li><a href="javascript:void(0);" onClick="setAmount(25,'sell')">25%</a></li>
						<li><a href="javascript:void(0);" onClick="setAmount(50,'sell')">50%</a></li>
						<li><a href="javascript:void(0);" onClick="setAmount(75,'sell')">75%</a></li>
						<li><a href="javascript:void(0);" onClick="setAmount(100,'sell')">100%</a></li>
					</ul>
					<ul class="row input_ul">
						<li>
							<span class="bold"><?=__('Order amount') ?></span>
						</li>
						<li>
							<!--span class="bold price" id="sell_total_amount">0</span>-->
							<input class="bold price" id="sell_total_amount" value=0 readonly />
							(<?php echo $firstCoin; ?>)
						</li>
					</ul>
					<input type="hidden" class="form-control text-right" autocomplete="false" id="sell_admin_fee" disabled placeholder="0.5% Admin Fee">
					<?php 
						$onclick_function = 'goLogin();';
						if(!empty($authUserId)) {
							$onclick_function = "modalDialog('sell',this.value)";
						}
					?>
                    <input type="button" class="button sell sell_ntr" <?php if(!empty($authUserId)) {echo 'data-toggle="modal" data-target="#myModalBuy"';}?> onclick="<?=$onclick_function;?>" value="<?=__('Sell') ?>">
					<span id="admin_show_fee" style="float:right;font-size:12px;"> <?= __('Fees');?> - <?php echo $adminFee; ?>%</span>
                    </div>
                </div>
				<?php echo $this->Form->end();?>
                <div class="clb"></div>
			</div>
		</div>
		<div class="clb"></div>
		<div class="moblock left_info">
			<div class="box_title">
				<?=__('Market breakdown') ?>
			</div>
			<table class="list">
				<thead>
					<tr>
						<td class="left"><?=__('Date') ?></td>
						<td class="left"><?=__('Price') ?></td>
						<td class="right"><?=__('Amount(WON)') ?></td>
					</tr>
				</thead>
			</table>
			<div class="ex_left2">
				<table class="list">
				<tbody class="market_history">
					<tr>
                      <td colspan=3><img src="<?php echo $this->request->webroot ?>ajax-loader.gif" /></td>
                    </tr>
				</tbody>
				</table>
			</div>
		</div>
        <div class="section_ex history2">
            <ul class="tab_menu">
                <li class="on purchase_li myorder_li" style="cursor:pointer;" id="purchase_li"><?=__('Buy') ?></li>
                <li class="sale_li myorder_li"  style="cursor:pointer;" id="sale_li"><?=__('Sell') ?></li>
            </ul>
            <div class="order_tab_system" id="purchase_li_div">
                <table class="list tablewidth" id="ajaxdata123" style="background:#fff;">
                    <thead>
						<tr>
							<td><?=__('Order date') ?></td>
							<td><?=__('Price per ') ?><?= $secondCoin;?></td>
							<td><?= $secondCoin; ?> <?=__('Amount') ?></td>
							<td><?=__($firstCoin.' amount') ?></td>
							<td><?=__('State') ?></td>
							<td><?=__('Action') ?></td>
						</tr>
                    </thead>
                    <tbody id="myBuyOrderlist">
                    </tbody>
                </table>
            </div>
            <div class="order_tab_system" id="sale_li_div" style="display:none">
                <table class="list" id="mySellOrderlist_table">
                    <thead>
						<tr>
							<td><?=__('Order date') ?></td>
							<td><?=__('Price per '); ?><?= $secondCoin;?></td>
							<td><?php echo $secondCoin; ?> <?=__('Amount') ?></td>
							<td><?=__($firstCoin.' amount') ?></td>
							<td><?=__('State') ?></td>
							<td><?=__('Action') ?></td>
						</tr>
                    </thead>
                    <tbody id="mySellOrderlist">
                    </tbody>
                </table>
            </div>
        </div>
	</div>
</div>
<div id="myModalBuy" class="modal fade" role="dialog" aria-hidden="true" >
	<div class="modal-dialog modal-dialog-centered bd-example-modal-sm">
		<!-- Modal content-->
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h5 class="modal-title"><?= __('Confirm Order') ?></h5>
				</div>
				<div class="modal-body">
					<div style="text-align: center;" class="common_tab" id="default_content">
						<div class="krw-info-area">
							<div class="krw-info-top">
								<div class="krw-account-info-area">
									<div class="krw-info-grid">
										<div class="grid-row">
											<div class="grid-col grid-col-4 grid-title">
												<?=__('Coin') ?>
											</div>
											<div class="grid-col grid-col-10">
												<span><?= $secondCoin; ?></span>
											</div>
										</div>
										<div class="grid-row">
											<div class="grid-col grid-col-4 grid-title">
												<?=__('Price') . ' (' . $firstCoin . ')'; ?>
											</div>
											<div class="grid-col grid-col-10">
												<span id="buy_span_price"></span>
											</div>
										</div>
										<div class="grid-row">
											<div class="grid-col grid-col-4 grid-title">
												<?=__("Q'ty") . ' (' . $secondCoin . ')'; ?>)
											</div>
											<div class="grid-col grid-col-10">
												<span id="buy_span_qty"></span>
											</div>
										</div>
										<div class="grid-row">
											<div class="grid-col grid-col-4 grid-title">
												<?= __('Amount');?>
											</div>
											<div class="grid-col grid-col-10">
												<span id="buy_span_amount"></span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div id="show_buy_resp"></div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn_buy" id="submit_btn"><?= __('Buy') ?></button> 
					<button type="button" class="btn_cancel" data-dismiss="modal"><?= __('Cancel'); ?></button>
				</div>
			</div>
		</div>
	</div>
</div>
<?php /*echo $this->element('Front2/password_info');*/?>
<script type="text/javascript">
	const authUserType = $('#authUserType').val();
	const firstCoinId = $("#firstCoinId").val();
	const secondCoinId = $("#secondCoinId").val();
	const authUserId = $("#authUserId").val();
	const firstCoin = $("#firstCoin").val();
	const secondCoin = $("#secondCoin").val();
	const binancePrice = $("#binancePrice").val();
	let datatable_language = 'English';
	if(getlang() === 'kr'){
		datatable_language = 'Korean';
	}

	var pairCurrentPrice = 0;
	var fee = 0.50;
	$(document).ready(function(){
		getUserBalance();
		getPairCurrentPrice();
		setGraph();
		//setInterval(function(){ checkExchange(); getPairCurrentPrice(); getLastTwentyFourHourTicker();  }, 5000); // 210426 18:30 YMJ
		//setInterval(function(){ setGraph();  }, 10000); // 210426 18:30 YMJ
		setInterval(function(){ checkExchange(); }, 10000); // 210715 20:00 LCH
		$('input[type=radio]').click(function(){
			let getVal = $(this).val();
			let currentPrice =  "";
			if(getVal === "buy_limit_tab"){
				$("#buy_per_price").val(0).change();
				$("#buy_per_price_div").show();
			}
			else if(getVal === "buy_market_tab"){
				currentPrice =  $("#current_price").html();
				$("#buy_per_price").val(currentPrice).change();
				$("#buy_per_price_div").hide();
			}
			else if(getVal === "sell_limit_tab"){
				$("#sell_per_price").val(0).change();
				$("#sell_per_price_div").show();
			}
			else if(getVal === "sell_market_tab"){
				currentPrice =  $("#current_price").html();
				$("#sell_per_price").val(currentPrice).change();
				$("#sell_per_price_div").hide();
			}
		});
		$('#ajaxdata123').DataTable({
			bSort: false,
			pageLength: 15,
			scrollY:        "300px",
			scrollX:        true,
			scrollCollapse: true,
			paging:         false,
			fixedColumns:   {
				leftColumns: 1,
				rightColumns: 1
			},
			language: {
				"url": "https://www.coinibt.io/datatable_language/" + datatable_language + ".json"
			}
		});
			
		$('#mySellOrderlist_table').DataTable({
			bSort: false,
			pageLength: 15,
			scrollY:        "300px",
			scrollX:        false,
			scrollCollapse: true,
			paging:         true,
			fixedColumns:   {
				leftColumns: 1,
				rightColumns: 1
			},
			language: {
				"url": "https://www.coinibt.io/datatable_language/" + datatable_language + ".json"
			}
			
		}); 
			 
		$("#sale_li").click(function(){
			setTimeout(function(){
			$('#mySellOrderlist_table').dataTable().fnDestroy();
			$('#mySellOrderlist_table').DataTable({
				bSort: false,
				pageLength: 15,
				scrollY:        "300px",
				scrollX:        false,
				scrollCollapse: true,
				paging:         true,
				fixedColumns:   {
					leftColumns: 1,
					rightColumns: 1
				},
				language: {
					"url": "https://www.coinibt.io/datatable_language/" + datatable_language + ".json"
				}
				
			}); 
			},0); 
		});
		// Set the datepicker's date format
		$.datepicker.setDefaults({
			dateFormat: 'dd.mm.yy',
			onSelect: function(dateText) {
				this.onchange();
				this.onblur();
			}
		});
		var currentMyUrl = window.location.href;
		var breakUrl = currentMyUrl.split("?");

        var win = $(this); //this = window
        if (win.width() >= 1024) { //This will check windows resolution means width

        } else {
            if (typeof breakUrl[1] === 'undefined' && typeof window.orientation !== 'undefined') {
                $('html, body').animate({
                    scrollTop: $(".tranx").offset().top
                }, 1000);
            }
        }

		$("#nav_bar").click(function(){
			$("#coin_show").toggle();
		});
		//exchange js
		$("#span_buy_volume").click(function(){
			var getVal = $("#span_buy_volume_all").val();
			$("#buy_total_amount").val(getVal);
		});
		
		$("#span_sell_volume").click(function(){
			var getVal = $("#span_sell_volume_all").val();
			$("#sell_volume").val(getVal).change();
		});
		
		// my order tab fuction
		$(".myorder_li").click(function(){
			$(".myorder_li").removeClass("on");
			$(this).addClass("on");
			let getId = $(this).attr('id');
			let showDivId = getId+"_div";
			$(".order_tab_system").hide();
			$("#"+showDivId).show();
		});
				
		if(authUserId !== '') {
			let daysRemaining = $("#daysRemaining").val();
			popup_show(daysRemaining);
		}	
		
		callAllFunctions();
		var chatHistory = document.getElementById("sell_order_show_div");
		if(chatHistory!= null){
			chatHistory.scrollTop = chatHistory.scrollHeight;
		}
		
	})

	var myRealGraph = Highcharts.stockChart('container', {
		chart: {
			renderTo: 'container',
			backgroundColor: '#fff',
			borderColor: 'transparent',
			borderWidth: 0
		},
		credits: {
			enabled: false
		},
		navigator: {
			enabled: false
		},
		plotOptions: {
			series: {
			turboThreshold: 0
			}
		},
		yAxis: [{
				labels: { align: 'right', x: -3 },
				title: { text: 'OHLC' },
				height: '70%',
				lineWidth: 2,
				resize: { enabled: true }
			}, { labels: { align: 'right', x: -3 },
				title: { text: __('Volume') },
				top: '72%',
				height: '20%',
				offset: 0,
				lineWidth: 2
			}],
		 rangeSelector: {
			 selected : 0,
				inputDateFormat: '%d.%m.%y',
				inputEditDateFormat: '%d.%m.%y',
				inputDateParser: function(value) {
					value = value.split(/[\.]/);
					return Date.UTC(
						parseInt(value[2]), 
						parseInt(value[1]) - 1, 
						parseInt(value[0])
					);
				},
				 buttons: [{ type: 'day', count: 15, text: '15d', },
						{ type: 'all', text: __('All') }]
		 },
		 title: { text: secondCoin + __(' Price') },
		 series: [{ type: 'candlestick', dataGrouping: { units: [
					['minute', // unit name
						 [1,5,30] // allowed multiples
					 ],
					 ['day', // unit name
						 [1] // allowed multiples
					 ],
					 ['week', // unit name
						 [1] // allowed multiples
					 ], ['month',[1, 2, 3, 4, 6]
					 ]
				 ]
			 }
		 }, {
			type: 'column',
			yAxis: 1,
			dataGrouping: {
				units: [['minute', [1,5,30]],
					['day', [1]],
					 ['week', [1]], 
					 ['month',[1, 2, 3, 4, 6]]
				 ]
			}
		}],
		lang:{
			months: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월','12월'],
			weekdays: ['월요일', '화요일', '수요일', '목요일', '금요일', '토요일', '일요일']
		}
	 }, function(chart){
		// apply the date pickers
		setTimeout(function () {
			$('input.highcharts-range-selector', $(chart.container).parent())
				.datepicker();
		}, 0);
	});
	$( "#radioclick" ).click(function() {
		var isChecked = $('#radioclick').is(':checked');
		if(isChecked==true){
			$(".hide_currency").hide();
		}else{
			$(".hide_currency").show();
		}
	});
	/* 전체 함수 */
	function callAllFunctions() {
		notCompletedOrderList(); // 미체결주문 리스트
		myOrderListAjax(); // 내 주문 리스트
		marketHistory(); // 체결 리스트
		//getCurrenPrice();
		getLastTwentyFourHourTicker();
		getPairCurrentPrice();
		getUserCurrentBalance();
		//setGraph();
	}
	/* 그래프 */
	function setGraph(){
        if(binancePrice === "N") {
		//if(binancePrice === "Y") {
			// ajax for market History list from DB
			$.ajax({
				url : '/front2/exchange/getGraphData/'+firstCoinId+'/'+secondCoinId,
				type : 'GET',
				dataType:'json',
				success : function(resp){
					let jsonData = [];
					let jsonDataVolume = [];
					if(resp.success === "true"){
						$.each(resp.data,function(key,valE){
							let ddt = [
								parseFloat(valE.datecol+"000"),
								parseFloat(valE.open_price),
								parseFloat(valE.max_price),
								parseFloat(valE.min_price),
								parseFloat(valE.close_price)
							];
							jsonData.push(ddt);
							let setColorColumn = (parseFloat(valE.close_price) > parseFloat(valE.open_price)) ? '#0c45d5' : '#d80000';
							jsonDataVolume.push({x:parseFloat(valE.datecol+"000"),y:parseFloat(valE.open_price),color: setColorColumn});
						});
						myRealGraph.series[0].setData(jsonData);
						myRealGraph.series[1].setData(jsonDataVolume);
					}
				}
			});
		} else {
			var firstCoinForUrl = (firstCoin=="USD") ? "B"+firstCoin : firstCoin;
			// ajax for market History list From Binance
			$.ajax({
				url: 'https://api.binance.com/api/v3/klines?symbol=' + secondCoin + firstCoinForUrl + '&interval=1m&limit=10000',
				type: 'GET',
				dataType: 'json',
				success: function (resp) {
					let jsonData = [];
					let jsonDataVolume = [];
					$.each(resp, function (key, valE) {
						let ddt = [
							parseFloat(valE[0]),
							parseFloat(valE[1]),
							parseFloat(valE[2]),
							parseFloat(valE[3]),
							parseFloat(valE[4])
						];
						jsonData.push(ddt);
						let setColorColumn = (parseFloat(valE[4]) > parseFloat(valE[1])) ? '#0c45d5' : '#d80000';
						jsonDataVolume.push({x: parseFloat(valE[0]), y: parseFloat(valE[5]), color: setColorColumn});
					});
					myRealGraph.series[0].setData(jsonData);
					myRealGraph.series[1].setData(jsonDataVolume);
				}
			});
		}
	}
	/* 거래 내역 변동 확인 */
	function checkExchange() {
		$.ajax({
			url : '/front2/exchange/checkExchange/'+firstCoinId+'/'+secondCoinId,
			type : 'get',
			//dataType : 'json',
			success : function(resp){
				if(resp==1){ // 있을 경우 전체 업데이트
					callAllFunctions();
				}
			}
		});
	}	
	function updateCurrentPrice() {
		$.ajax({
			url : '/front2/exchange/updateMyPrice/'+firstCoinId+'/'+secondCoinId,
			type : 'get',
			dataType : 'json',
			success : function(resp){
				
			}
		});
	}
	updateCurrentPrice();
	/* form reset */
	function clearForm(){
		$("#buy_volume").val('');
		$("#buy_per_price").val('');
		$("#buy_total_amount").val('');
		$("#buy_admin_fee").val('');
		$("#sell_volume").val('');
		$("#sell_per_price").val('');
		$("#sell_total_amount").val('');
		$("#sell_admin_fee").val('');
	}
	
	function calAdminFee(totalAmt){
		var calFee = (totalAmt*fee)/100;
		calFee = parseFloat(calFee);
        		calFee = calFee;
		if(!isNaN(calFee)){
			return calFee;	
		}
		return '';
	}
	
	function calculateForm(thisId,exType) {
		let volume = $("#"+exType+"_volume").val();
		volume = parseFloat(volume);
		let totalAmt = $("#"+exType+"_total_amount").val();
		totalAmt = parseFloat(totalAmt);
		let perPrice = $("#"+exType+"_per_price").val();
		perPrice = parseFloat(perPrice);

		if(thisId === exType+"_volume" && !isNaN(perPrice)){
			// calculate total
			totalAmt = volume * perPrice;
			totalAmt = parseFloat(totalAmt);
			/*totalAmt = removeZeros(totalAmt);*/
			if(!isNaN(totalAmt)){
				$("#"+exType+"_total_amount").val(totalAmt);
				// calculate fee
				let calFee = calAdminFee(totalAmt);
				$("#"+exType+"_admin_fee").val(calFee);
			}
		}

		if(thisId === exType+"_per_price"){
			if(!isNaN(volume)){
				totalAmt = volume * perPrice;
				totalAmt = parseFloat(totalAmt);
				totalAmt = removeZeros(totalAmt);
				if(!isNaN(totalAmt)){
					$("#"+exType+"_total_amount").val(totalAmt);
					let calFee = calAdminFee(totalAmt);
					$("#"+exType+"_admin_fee").val(calFee);
				}
			} else {
				totalAmt = $("#"+exType+"_total_amount").val();
				volume = totalAmt/perPrice;
				volume = parseFloat(volume);
				if(!isNaN(volume)){
					if(volume !== 0){
						$("#"+exType+"_volume").val(volume);
					}
					// calculate fee
					let calFee = calAdminFee(totalAmt);
					$("#"+exType+"_admin_fee").val(calFee);
				}
			}
		}

		if(thisId === exType+"_total_amount" && !isNaN(perPrice)){
			let totalAmt = $("#"+thisId).val();
			let volume = totalAmt/perPrice;
			volume = parseFloat(volume);
			if(!isNaN(volume)){
				if(volume !== 0){
					$("#"+exType+"_volume").val(volume);
				}
				let calFee = calAdminFee(totalAmt);
				$("#"+exType+"_admin_fee").val(calFee);
			}
		}
	}
	// ajax for market History list
	function marketHistory() {
        //거래 시작시 
        if(binancePrice === "N") {
        //if(binancePrice === "Y") {
			$.ajax({
				url : '/front2/exchange/marketHistory/'+ firstCoinId + '/' +secondCoinId,
				type : 'get',
				dataType : 'json',
				success : function(resp){
					// my buyOrderList data
					var html = '';
					if($.isEmptyObject(resp)){
						html += '<tr>';
						html += "<td colspan=5>"+__('Order not available')+"</td>";
						html += '</tr>';
					} else {
						$.each(resp,function(key,value){
							let sellPurchaseType = "";
							let perPrice = "";
							let sellPurchaseAmt = '';

							if(value.get_cryptocoin_id === secondCoinId){
								perPrice = (value.get_per_price);
							} else {
								perPrice = (value.spend_per_price);
							}

							if(value.get_cryptocoin_id === secondCoinId){
								sellPurchaseAmt = (value.get_amount);
							} else {
								sellPurchaseAmt = (value.spend_amount);
							}

							let totalPrice = (sellPurchaseAmt*perPrice);
							let splitDateTime = value.created_at.split("+")[0].split("T");
							let setColor = (value.extype === "buy") ? "blue " : "red";
							html += '<tr>';
							html += '<td class="left"><div class="bold">'+splitDateTime[0]+'</div>'+splitDateTime[1]+'</td>';
/*							html += '<td class="left"><div class="'+setColor+'">'+numberWithCommas(parseFloat(perPrice).toFixed(2))+'</div></td>';
							html += '<td class="right">'+numberWithCommas(parseFloat(totalPrice).toFixed(2))+'</td>';*/
                            html += '<td class="left"><div class="'+setColor+'">'+perPrice+'</div></td>';
                            html += '<td class="right">'+totalPrice+'</td>';
							html += '</tr>';
						});
					}
					$(".market_history").html(html);
				}
			});
		} else {
			var firstCoinForUrl = (firstCoin=="KRW") ? "B"+firstCoin : firstCoin;
			$.ajax({
				url : 'https://api.binance.com/api/v3/trades?symbol=' + secondCoin + firstCoinForUrl,
				type : 'get',
				dataType : 'json',
				success : function(resp){
					resp = resp.reverse();
					// my buyOrderList data
					let html = '';
					if($.isEmptyObject(resp)){
						html = html + '<tr>';
						html = html + "<td colspan=5>" + __('Order not found') + "</td>";
						html = html + '</tr>';
					}
					else {
						$.each(resp,function(key,value){
							let perPrice = value.price;
							let setColor = (value.isBuyerMaker === false) ? "blue " : "red";
							let totalPrice = value.quoteQty;
							let myDate = new Date(value.time);
							let showOnlyDate = myDate.getFullYear()  + "-" + (myDate.getMonth()+1) + "-" + myDate.getDate();
							let showOnlyTime = myDate.getHours() + ":" + myDate.getMinutes()+ ":" + myDate.getSeconds();
							html += '<tr>';
							html += '<td class="left"><div class="bold">'+showOnlyDate+'</div>'+showOnlyTime+'</td>';
							html += '<td class="left"><div class="'+setColor+'">'+numberWithCommas(parseFloat(perPrice).toFixed(2))+'</div></td>';
							html += '<td class="right">'+numberWithCommas(parseFloat(totalPrice).toFixed(2))+'</td>';
							html += '</tr>';
						});
					}
					$(".market_history").html(html);
				}
			});
		}
	}
	
	/* 용량, 최고가, 최저가, 전일대비, 각코인 금액, 중간 금액 */
	function getCurrenPrice() {
		$.ajax({
			url : '/front2/exchange/getCurrenPrice/'+ firstCoinId +'/' + secondCoinId,
			type : 'get',
			dataType : 'json',
			success : function(resp){
				if ($.isEmptyObject(resp.current_price)) {
				} else {
					let returnPrice = resp.current_price[0].get_per_price;
					returnPrice = parseFloat(returnPrice).toFixed(2);
					let returnPriceInThousands = numberWithCommas(returnPrice);
					let baseCoinPriceInUsd = $('#baseCoinPriceInUsd').val();
					let currentPriceInUsd = returnPrice * parseFloat(baseCoinPriceInUsd);
					currentPriceInUsd = parseFloat(currentPriceInUsd).toFixed(2);
					$("#current_price").html(returnPriceInThousands);
					pairCurrentPrice = returnPrice;
					let setHtml = (resp.goto === "down") ? "&#9660;" :"&#9650;";
					$("#middle_current_price").html('<tr onClick="fill_data_middle()"><td colspan="3"><div class="updown_rate"><span id="middle_only_price">'+returnPriceInThousands+'</span><span class="updown_arrow" style="font-size:16px; line-height: 1;">'+setHtml+'</span></div></td></tr>');
					$("#current_price_"+ secondCoin + "_" +firstCoin).html(returnPriceInThousands);
					$("#current_price_usd").html(currentPriceInUsd);
				}
				if(binancePrice === "N") {
					let newMyClass = (resp.change_in_one_day < 0) ? "red" : "blue";
					let newSignPrcNew = (resp.change_in_one_day < 0) ? "-" : "+";
					$("#change_in_one_day").html(newSignPrcNew+""+Math.abs(parseFloat(resp.change_in_one_day).toFixed(2))+"%").removeClass("blue").removeClass("red").addClass(newMyClass);
					// for current volume
					if($.isEmptyObject(resp.current_volume)){
						$("#current_volume").html(0);
					} else {
						let returnVolume = numberWithCommas(parseFloat(resp.current_volume).toFixed(2));
						$("#current_volume").html(returnVolume);
					}
					// for min price
					if(resp.min_price==""){
						$("#min_price").html('');
					} else {
						let minPrice = numberWithCommas(parseFloat(resp.min_price).toFixed(2));
						$("#min_price").html(minPrice);
					}
					// for max price
					if(resp.max_price==""){
						$("#max_price").html('');
					} else {
						let maxPrice = numberWithCommas(parseFloat(resp.max_price).toFixed(2));
						$("#max_price").html(maxPrice);
					}
				}
			}
		});
	}
	/* binance API */
	function getLastTwentyFourHourTicker(){
		if(binancePrice === "Y") {
			var firstCoinForUrl = (firstCoin=="KRW") ? "B"+firstCoin : firstCoin;
			$.ajax({
				url : 'https://api.binance.com/api/v3/ticker/24hr?symbol='+ secondCoin + firstCoinForUrl,
				type : 'get',
				dataType : 'json',
				success : function(resp){
					let highPrice = numberWithCommas(parseFloat(resp.highPrice).toFixed(2));
					let lowPrice = numberWithCommas(parseFloat(resp.lowPrice).toFixed(2));
					let getMyVolume = numberWithCommas(parseFloat(resp.quoteVolume).toFixed(2));
					let getMyPercent = parseFloat(resp.priceChangePercent).toFixed(2);
					let newClass = (getMyPercent<0) ? "red" : "blue";
					let newSignPrc = (getMyPercent<0) ? "-" : "+";
					$("#current_volume").html(getMyVolume);
					$("#max_price").html(highPrice);
					$("#min_price").html(lowPrice);
					$("#change_in_one_day").html(newSignPrc+""+Math.abs(getMyPercent)+"%").removeClass("red").removeClass("blue").addClass(newClass);
				}
			});
		} else {
			getCurrenPrice();
		}
	}
	/* 미체결 주문 리스트 */
	function notCompletedOrderList(){
		// ajax for get not completed order list of buy orders
		$.ajax({
			url : '/front2/exchange/notCompletedOrderListAjax/' + firstCoinId + '/' + secondCoinId,
			type : 'get',
			dataType : 'json',
			beforeSend : function(xhr){
				//$("#buyAjaxData").html('<img src="/ajax-loader.gif" />');
				//$("#sellAjaxData").html('<img src="/ajax-loader.gif" />');
			},
			success : function(resp){
				let html = '';
				let list_count = 0;
				let total_list_count = 10;
				if($.isEmptyObject(resp.buyOrderList)){
					html += '<tr>';
					html += "<td colspan=3><?= __('Order not found')?></td>";
					html += '</tr>';
				} else {
					var buyOrderBalance = 0;
					list_count = 0;
					if(authUserType == 'A' || authUserId == 3818 || authUserId == 1102){
						total_list_count = resp.buyOrderList.length + 1;
					}
					$.each(resp.buyOrderList,function(key,value){
						if(list_count < total_list_count){
							html += '<tr onClick="fill_data(this,\'sell\')" style="cursor:pointer;">';
							/*html += '<td class="fill_per_price"><div class="bold red">'+numberWithCommas(parseFloat(value.per_price).toFixed(2))+'</div></td>';*/
                            html += '<td class="fill_per_price"><div class="bold red">'+value.per_price+'</div></td>';
							html += '<td class="fill_amount">'+numberWithCommas(parseFloat(value.sum).toFixed(2))+'</td>';
							html += '<td class="right">'+numberWithCommas(parseFloat(parseFloat(value.per_price).toFixed(2)*parseFloat(value.sum).toFixed(2)).toFixed(2))+'</td>';
							html += '</tr>';
							buyOrderBalance += parseFloat(value.per_price).toFixed(2)*parseFloat(value.sum).toFixed(2);
						}
						list_count++;
					});

					buyOrderBalance  = numberWithCommas(parseFloat(buyOrderBalance).toFixed(2));
					$("#buy_order_balance").html(buyOrderBalance + " USD");
				}
				$("#buyAjaxData").html(html);
				// add data to sell table
				html = '';
				if($.isEmptyObject(resp.sellOrderList)){
					html += '<tr>';
					html += "<td colspan=3><?= __('Order not found')?></td>";
					html += '</tr>';
				}
				else {
					var sellOrderBalance = 0;
					list_count = resp.sellOrderList.length - 10;
					if(authUserType == 'A' || authUserId == 3818 || authUserId == 1102){
						list_count = 0;
					}
					$.each(resp.sellOrderList,function(key,value){
						if(list_count <= key){
							html += '<tr onClick="fill_data(this,\'buy\')" style="cursor:pointer;">';
							/*html += '<td class="fill_per_price"><div class="bold blue">'+numberWithCommas(parseFloat(value.per_price).toFixed(2))+'</div></td>';*/
                            html += '<td class="fill_per_price"><div class="bold blue">'+value.per_price+'</div></td>';
							html += '<td class="fill_amount">'+numberWithCommas(parseFloat(value.sum).toFixed(2))+'</td>';
							html += '<td class="right">'+numberWithCommas(parseFloat(parseFloat(value.per_price).toFixed(2)*parseFloat(value.sum).toFixed(2)).toFixed(2))+'</td>';
							html += '</tr>';
							sellOrderBalance += parseFloat(value.per_price).toFixed(2)*parseFloat(value.sum).toFixed(2);
						}
					});
					sellOrderBalance  = numberWithCommas(parseFloat(sellOrderBalance).toFixed(2));
					$("#sell_order_balance").html(sellOrderBalance + " KRW");
				}
				$("#sellAjaxData").html(html);
				var objDiv = document.getElementById("sell_order_show_div");
				if(objDiv!= null){
					objDiv.scrollTop = objDiv.scrollHeight;
				}
			}
		});
	}
	/* 내 주문 리스트 */
	function myOrderListAjax(){
		if(authUserId !== '') {
		// ajax for myOrder list 
			$.ajax({
				url: '/front2/exchange/myOrderListAjax/'+ firstCoinId + '/' + secondCoinId,
				type : 'get',
				dataType : 'json',
				success : function(resp){
					// my buyOrderList data
					var html = '';
					if($.isEmptyObject(resp.myBuyOrderList)){
						html += '<tr>';
						html += "<td colspan=6>" + __('There is no transaction history.') + "</td>";
						html += '</tr>';
						$("#myBuyOrderlist").html(html);
					} else {
						$.each(resp.myBuyOrderList,function(key,value){
							var action = '&nbsp;';
							var showAmount = numberWithCommas(parseFloat(value.total_buy_get_amount).toFixed(2));
							if(value.status ==='pending'){
								action = "<a class='button sell sell_ntr' href='javascript:void(0)' id='buy_"+value.id+"' onClick='deleteOrder(this.id)'>"+'<?= __('Cancel')?>'+"</a>";
								showAmount = numberWithCommas(parseFloat(value.buy_get_amount).toFixed(2));
							}
							if(value.status === 'deleted'){

							}
							var created_at = value.created_at.split("+")[0].replace("T"," ");
							var status = ucfirst(value.status);
							html += '<tr>';
							html += '<td>'+created_at+'</td>';
							html += '<td>'+numberWithCommas(parseFloat(value.per_price).toFixed(2))+'</td>';
							html += '<td>'+showAmount+'</td>';
							if(parseFloat(value.buy_get_amount) === 0.0){
								html += '<td>'+numberWithCommas(parseFloat((parseFloat(value.per_price).toFixed(2))*(parseFloat(value.total_buy_get_amount).toFixed(2))).toFixed(2))+'</td>';
							} else {
								html += '<td>'+numberWithCommas(parseFloat((parseFloat(value.per_price).toFixed(2))*(parseFloat(value.buy_get_amount).toFixed(2))).toFixed(2))+'</td>';
							}
							if(status === 'Completed') {
								html += '<td>' + __('Completed') + '</td>';
							} else if (status === 'Pending'){
								html += '<td>' + __('Pending') + '</td>';
							} else if (status === 'Deleted'){

							}
							else {
								html += '<td>&nbsp;</td>'
							}
							html += '<td>'+action+'</td>';
							html += '</tr>';
						});
						
						$("#ajaxdata123").dataTable().fnDestroy();
						$("#myBuyOrderlist").html(html);
						$('#ajaxdata123').DataTable({
							bSort: false,
							pageLength: 15,
							scrollY:        "300px",
							scrollX:        false,
							scrollCollapse: true,
							fixedColumns:   {
								leftColumns: 1,
								rightColumns: 1
							},
							language: {
								"url": "https://www.coinibt.io/datatable_language/" + datatable_language + ".json"
							}
						}); 
					}
					// my seller order list data
					html = '';
					if($.isEmptyObject(resp.mySellOrderList)){
						html += '<tr>';
						html += "<td colspan=6>" + __('Transaction history is not available') + "</td>";
						html += '</tr>';
						$("#mySellOrderlist").html(html);
					} else { 
						$.each(resp.mySellOrderList,function(key,value){
							var action = '&nbsp;';
							var showAmount = numberWithCommas(parseFloat(value.total_sell_get_amount).toFixed(2));
							if(value.status === 'pending'){
								action = "<a class='button sell sell_ntr' style='background-color: #0c45d5;' href='javascript:void(0)' id='sell_"+value.id+"' onClick='deleteOrder(this.id)'>"+'<?= __('Cancel')?>'+"</a>";
								showAmount = numberWithCommas(parseFloat(value.sell_get_amount).toFixed(2));
							}
							if(value.status === 'deleted'){

							}
							var created_at = value.created_at.split("+")[0].replace("T"," ");
							var status = ucfirst(value.status);
							html += '<tr>';
							html += '<td>'+created_at+'</td>';
							html += '<td>'+numberWithCommas(parseFloat(value.per_price).toFixed(2))+'</td>';
							html += '<td>'+numberWithCommas(parseFloat(parseFloat(value.sell_get_amount).toFixed(2)/parseFloat(value.per_price).toFixed(2)).toFixed(2))+'</td>';
							html += '<td>'+numberWithCommas(parseFloat(value.sell_get_amount).toFixed(2))+'</td>';
							if(status === 'Completed') {
								html += '<td>' + __('Completed') + '</td>';
							} else if (status === 'Pending'){
								html += '<td>' + __('Pending') + '</td>';
							} else if (status === 'Deleted'){

							}
							else {
								html += '<td>&nbsp;</td>'
							}
							html += '<td>' + action + '</td>';
							html += '</tr>';
						});
						$("#mySellOrderlist_table").dataTable().fnDestroy();
						$("#mySellOrderlist").html(html);
						$('#mySellOrderlist_table').DataTable({
							bSort: false,
							pageLength: 15,
							scrollY:        "300px",
							scrollX:        false,
							scrollCollapse: true,
							paging:         true,
							fixedColumns:   {
								leftColumns: 1,
								rightColumns: 1
							},
							language: {
								"url": "https://www.coinibt.io/datatable_language/" + datatable_language + ".json"
							}
						});
					}
				}
			});
		}
	}
	/* 주문 삭제 */
	function deleteOrder(getId){
		if(authUserId !== '') {
			if(confirm("삭제하시겠습니까?")){
				$("#"+getId).closest('tr').remove();
				var splitId = getId.split("_");
				var tableType = splitId[0]; 
				var tableId = splitId[1];
				$.ajax({
					url: "/front2/exchange/deleteMyOrder/" + tableId + "/" + tableType,
					beforeSend : function(xhr){
						xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
					},
					type : 'post',
					dataType : 'json',
					success : function(resp){}
				});
			}
		} 
	}
	/* 미체결 주문 클릭 시 이벤트 */
	function fill_data(getTable,getTableType){
		var fillPerPrice = $(getTable).find("td.fill_per_price div").html();
		var fillAmount = $(getTable).find("td.fill_amount").html();
		fillPerPrice = fillPerPrice.replace(/,/g,'');
		fillAmount = fillAmount.replace(/,/g,'');

		if(getTableType === "sell"){
			var getTenPercentHighOfCurrentPrice = pairCurrentPrice*<?php echo $max_buysell_per ?>/100;
			var maxPerPrice = parseFloat(pairCurrentPrice) + parseFloat(getTenPercentHighOfCurrentPrice);
			if(fillPerPrice > maxPerPrice){
				modal_alert('',"<?=__('The daily upper limit price has been exceeded.');?>");
				return false;
			}
			//min_buysell_per
			var getTenPercentLowOfCurrentPrice = pairCurrentPrice*<?php echo $min_buysell_per ?>/100;
			var minPerPrice = parseFloat(pairCurrentPrice) - parseFloat(getTenPercentLowOfCurrentPrice);
			if(fillPerPrice < minPerPrice){
				modal_alert('',"<?=__('The daily lower price has been exceeded.');?>");
				return false;
			}
			//max_market_per
			var getTenPercentHighOfCurrentPrice =<?php echo $mid_night_price*$max_market_per/100 ?>;
			var maxPerPrice = parseFloat(pairCurrentPrice) + parseFloat(getTenPercentHighOfCurrentPrice);
			if(fillPerPrice > maxPerPrice){
				modal_alert('',"<?=__('The daily upper limit price has been exceeded.');?>");
				return false;
			}
			//min_market_per
			var getTenPercentLowOfCurrentPrice = <?php echo $mid_night_price*$min_market_per/100 ?>;
			var minPerPrice = parseFloat(pairCurrentPrice) - parseFloat(getTenPercentLowOfCurrentPrice);
			if(fillPerPrice < minPerPrice){
				modal_alert('',"<?=__('The daily lower price has been exceeded.');?>");
				return false;
			}
			//$("#sell_volume").val(fillAmount).trigger("input");
			$("#sell_per_price").val(fillPerPrice).trigger("input");
			$("#buy_per_price").val(fillPerPrice).trigger("input");
			$("#profile-tab").click();
		}
		if(getTableType === "buy"){
			//max_buysell_per
			var getTenPercentHighOfCurrentPrice = pairCurrentPrice*<?php echo $max_buysell_per ?>/100;
			var maxPerPrice = parseFloat(pairCurrentPrice) + parseFloat(getTenPercentHighOfCurrentPrice);
			if(fillPerPrice > maxPerPrice){
				modal_alert('',"<?=__('The daily upper limit price has been exceeded.');?>");
				return false;
			}
			//min_buysell_per
			var getTenPercentLowOfCurrentPrice = pairCurrentPrice*<?php echo $min_buysell_per ?>/100;
			var minPerPrice = parseFloat(pairCurrentPrice) - parseFloat(getTenPercentLowOfCurrentPrice);
			if(fillPerPrice < minPerPrice){
				modal_alert('',"<?=__('The daily lower price has been exceeded.');?>");
				return false;
			}
			//max_market_per
			var getTenPercentHighOfCurrentPrice =<?php echo $mid_night_price*$max_market_per/100 ?>;
			var maxPerPrice = parseFloat(pairCurrentPrice) + parseFloat(getTenPercentHighOfCurrentPrice);
			if(fillPerPrice > maxPerPrice){
				modal_alert('',"<?=__('The daily upper limit price has been exceeded.');?>");
				return false;
			}
			//min_market_per
			var getTenPercentLowOfCurrentPrice = <?php echo $mid_night_price*$min_market_per/100 ?>;
			var minPerPrice = parseFloat(pairCurrentPrice) - parseFloat(getTenPercentLowOfCurrentPrice);
			if(fillPerPrice < minPerPrice){
				modal_alert('',"<?=__('The daily lower price has been exceeded.');?>");
				return false;
			}
			$("#sell_per_price").val(fillPerPrice).trigger("input");
			$("#buy_per_price").val(fillPerPrice).trigger("input");
			$("#home-tab").click();
		}
	}
	/* 최종거래가 클릭 시 이벤트 */
	function fill_data_middle(){
		let fillPerPrice = $("#middle_only_price").html();
		fillPerPrice = fillPerPrice.replace(/,/g,'');
		$("#sell_per_price").val(fillPerPrice).trigger("input");
		$("#buy_per_price").val(fillPerPrice).trigger("input");
		$("#sell_volume").val(0);
		$("#buy_volume").val(0);
		$("#profile-tab").click();
	}
	/* 매도매수 시 % 클릭 이벤트 */
	function setAmount(percent,type){
		let getBalanceAmt = parseFloat($("#span_"+type+"_volume_all").text().replaceAll(",",""));
		let volume = 0;
		let perPrice = 0;
		let totalAmount = 0;
		if(type == 'sell'){
			volume = getBalanceAmt * (parseFloat(percent)/100);
			perPrice = parseFloat($("#"+type+"_per_price").val());
			totalAmount = volume * perPrice;
		} else if (type == 'buy'){
			totalAmount = getBalanceAmt * (parseFloat(percent)/100);
			perPrice = parseFloat($("#"+type+"_per_price").val());
			volume = totalAmount / perPrice;
		}
		$("#"+type+"_volume").val(volume.toFixed(2));
		$("#"+type+"_total_amount").val(numberWithCommas(totalAmount.toFixed(2)));
	}
	/* 코인 리스트 현재 가격 업데이트 */
	function getPairCurrentPrice(){
		$.ajax({
			url : "/front2/exchange/getPairCurrentPrice/"+firstCoinId,
			type : 'GET',
			dataType : 'json',
			success : function(resp){
				$.each(resp,function(getKey,getValData){
					var getVal = getValData.price;
					var getPricePercent = getValData.price_percent;
					getPricePercent = parseFloat(getPricePercent).toFixed(2);
					var isBinancePrice = getValData.binance;
					getVal = parseFloat(getVal).toFixed(2);
					var getValInThousands = numberWithCommas(getVal);
					$("#"+getKey).html(getValInThousands);
					var setPriceClass = (getPricePercent<0) ? "red" : "blue"; 
					var setPriceSign = (getPricePercent<0) ? "-" : "+"; 
					$("#percent_"+getKey).html(setPriceSign+""+Math.abs(getPricePercent)+"%").removeClass("red").removeClass("blue").addClass(setPriceClass);
					if(getKey == "current_price_"+secondCoin+"_"+firstCoin){
						$("#middle_only_price").html(getValInThousands);
						$("#current_price").html(getValInThousands);
						var buyCheckBox = $('input[value="buy_market_tab"]:checked').length;
						if(buyCheckBox > 0){
							$("#buy_per_price").val(getVal);
						}
						var sellCheckBox = $('input[value="sell_market_tab"]:checked').length;
						if(sellCheckBox > 0){
							$("#sell_per_price").val(getVal);
						}
					}
				})
			}
		});
	}
	/* 멤버십 기간 알림 팝업 닫기 */
    function popup_hide(state){
        var user_id = "<?=$authUserId ? $authUserId : 0;?>";
        $('#days_remaining_dialog').dialog('close');
        $('#days_remaining_dialog').hide();
        if(state == 1){
            if(getCookie('daysRemainingCookie_'+user_id) != 'Y'){
                setCookie('daysRemainingCookie_'+user_id,'Y',1);
            }
        }
    }
	/* 멤버십 기간 알림 팝업 */
    function popup_show(type){
        var user_id = "<?=$authUserId ? $authUserId : 0;?>";
        if(type != 'non_annual_membership' && type != 'lot_remain'){
            if(getCookie('daysRemainingCookie_'+user_id) != 'Y'){
                $('#days_remaining_dialog').dialog();
                $('#days_remaining_dialog').show();
            }
        }
    }
	/* 유저 주문 가능 금액 */
	function getUserCurrentBalance() {
		if(authUserId !== ''){
			$.ajax({
				url : '/front2/exchange/getUserCurrentBalance/' + firstCoinId +'/'+secondCoinId,
				type : 'get',
				dataType : 'json',
				success : function(resp){
					let firstCoinBalance = numberWithCommas(parseFloat(resp.firstCoinsSum).toFixed(2));
					let secondCoinBalance = numberWithCommas(parseFloat(resp.secondCoinsSum).toFixed(2));
					$("span#span_buy_volume_all").text(""+firstCoinBalance);
					$("span#span_sell_volume_all").text(""+secondCoinBalance);
				}
			});
		} else {
			$("span#span_buy_volume_all").text("0");
			$("span#span_sell_volume_all").text("0");
		}
	}
	/* 주문 확인 창 오픈 */
	function modalDialog(type,btn_text) {

        let perPrice = $("#"+type+"_per_price").val();
		let qty = $("#"+type+"_volume").val();
		let amount = $("#"+type+"_total_amount").val();

		$("span#buy_span_price").text(""+perPrice);
		$("span#buy_span_qty").text(""+qty);
		$("span#buy_span_amount").text(""+amount);

		$("#submit_type").val(type);
		$("#submit_per_price").val(perPrice);
		$("#submit_volume").val(qty);
		$('#submit_btn').attr('onclick',"formSubmit('"+type+"')");
		$('#submit_btn').removeClass();
		if(type == 'buy'){
			$('#submit_btn').addClass('btn_buy');
		} else {
			$('#submit_btn').addClass('btn_sell');
		}
		$('#submit_btn').text(btn_text);
	}

	/* 매도/매수 form submit */
	function formSubmit(type){
		let formData = new FormData($('#buy_form')[0]);
		if(authUserId !== '') {
			let max_buysell_per = $("#max_buysell_per").val();
			let min_buysell_per = $("#min_buysell_per").val();
			let max_market_per = $("#max_market_per").val();
			let min_market_per = $("#min_market_per").val();
			let mid_night_price = $("#mid_night_price").val();
			let perPrice = $("#"+type+"_per_price").val();
			let perPrices = parseFloat(perPrice);
			//max_buysell_per
			let getTenPercentHighOfCurrentPrice = pairCurrentPrice * parseFloat(max_buysell_per) / 100;
			let maxPerPrice = parseFloat(pairCurrentPrice) + parseFloat(getTenPercentHighOfCurrentPrice);
			if (perPrices > maxPerPrice) {
				modal_alert('',"<?=__('The daily upper limit price has been exceeded.');?>");
				return false;
			}
			//min_buysell_per
			let getTenPercentLowOfCurrentPrice = pairCurrentPrice * parseFloat(min_buysell_per) / 100;
			let minPerPrice = parseFloat(pairCurrentPrice) - parseFloat(getTenPercentLowOfCurrentPrice);
			if (perPrices < minPerPrice) {
				modal_alert('',"<?=__('The daily lower price has been exceeded.');?>");
				return false;
			}
			//max_market_per
			getTenPercentHighOfCurrentPrice = parseFloat(mid_night_price) * parseFloat(max_market_per) / 100;
			maxPerPrice = parseFloat(pairCurrentPrice) + parseFloat(getTenPercentHighOfCurrentPrice);
			if (perPrices > maxPerPrice) {
				modal_alert('',"<?=__('The daily upper limit price has been exceeded.');?>");
				return false;
			}
			//min_market_per
			getTenPercentLowOfCurrentPrice = parseFloat(mid_night_price) * parseFloat(min_market_per) / 100;
			minPerPrice = parseFloat(pairCurrentPrice) - parseFloat(getTenPercentLowOfCurrentPrice);
			if (perPrices < minPerPrice) {
				modal_alert('',"<?=__('The daily lower price has been exceeded.');?>");
				return false;
			}
			$('#submit_btn').hide();
			// ajax for market History list
			$.ajax({
				beforeSend: function () {
					$('#show_buy_resp').html("<img src='/webroot/ajax-loader.gif' />");
				},
				url: '/front2/exchange/index/' + secondCoin + '/' + firstCoin,
				type: 'post',
				data: formData,
				contentType: false,
				processData: false,
				dataType: 'json',
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
				success: function (resp) {
					$('#show_buy_resp').html(resp.message);
					let seconds = 100;
					//call to exchange
					if (resp.error === 0) {
						callAjaxExchange(formData, type); // 이상 없을 경우 실제 매수/매도 처리
					} else {
						seconds = 3000;
					}
					$('#submit_btn').show();
					setTimeout(function () {
						$('#show_buy_resp').html('');
						$(".modal-header button").click();
					}, seconds);
					return;
				}
			});
		}
	}
	/* 실제 폼 전송 */
	function callAjaxExchange(formData,requestType){
		if(authUserId !== '') {
			$.ajax({
				url : '/front2/exchange/exchange/'+ firstCoin + '/' + secondCoin,
				type : 'post',
				data : formData,
				contentType: false,
				//cache: false,
				processData:false,
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				},
				success : function(resp){
					clearForm();
					callAllFunctions();
					$('#submit_btn').show();
				}
			});
		}
	}
	/* 매도 매수 가격 up down 클릭 */
	function operation(opt,type) {
		let value1= $("#"+type+"_per_price").val();
		let value = "0";
		let bPrice = parseFloat(value1).toFixed(2);
		if(bPrice !== undefined && bPrice !== null && bPrice !== ''){
			let numDec = countDecimals(bPrice);
			if(numDec !== 0 && numDec === 1 || numDec === 2){
				$("#"+type+"_per_price").attr({step:'0.01',min:'0.01'});
				value = "0.01";
			} else if(numDec === 3){
				$("#"+type+"_per_price").attr({step:'0.1',min:'0.1'});
				value = "0.1";
			} else {
				$("#"+type+"_per_price").attr({step:'1.0',min:'1.0'});
				value = "1.0";
			}
		}
		if(value1 !== undefined && value1 !== null && value1 !== ''){
			if (opt === 'increment') {
				let new_value = parseFloat(value1)+parseFloat(value);
				$("#"+type+"_per_price").val(parseFloat(new_value).toFixed(2)).trigger('input');
			}
			if (opt === 'decrement') {
				if(value1>0){
					let new_value=parseFloat(value1)-parseFloat(value);
					if(Math.sign(new_value) === -1){
						new_value=0;
					}
					$("#"+type+"_per_price").val(parseFloat(new_value).toFixed(2)).trigger('input');
				}
			}
		}else{
			let new_value = value;
			$("#"+type+"_per_price").val(parseFloat(new_value).toFixed(2)).trigger('input');
		}
	}
	/* 매도 매수 수량 up down 클릭 */
	function operationVol(opt,type,value) {
		let value1 = $("#"+type+"_volume").val();
		if(value1 !== undefined && value1 !== null && value1 !== ''){
			if (opt === 'increment'){
				let new_value = parseFloat(value1) + parseFloat(value);
				$("#"+type+"_volume").val(parseFloat(new_value).toFixed(1)).trigger('input');
			}
			if (opt === 'decrement'){
				if(value1>0){
					let new_value = parseFloat(value1) - parseFloat(value);
					if(Math.sign(new_value) === -1){
						new_value = 0;
					}
					$("#"+type+"_volume").val(parseFloat(new_value).toFixed(1)).trigger('input');
				}
			}
		}else{
			let new_value = value;
			$("#"+type+"_volume").val(parseFloat(new_value).toFixed(1)).trigger('input');
		}
	}
	function toDecimals(id){
		let num = parseFloat($("#" + id).val());
		if(!isNaN(num)) {
			/*let cleanNum = num.toFixed(5);
			$("#" + id).val(cleanNum);*/
		}
	}
	function getUserBalance() {
		if(authUserId !== ''){
			$.ajax({
				url : '/front2/exchange/getUserBalance/'+firstCoinId+'/'+secondCoinId,
				type : 'get',
				dataType : 'json',
				success : function(resp){
					let firstCoinBalance = parseFloat(resp.firstCoinBalance).toFixed(2);
					let secondCoinBalance = parseFloat(resp.secondCoinBalance).toFixed(2);
					$("#span_buy_volume_all").text(firstCoinBalance);
					$("#span_sell_volume_all").text(secondCoinBalance);
				}
			});
		} else {
			$("#span_buy_volume_all").text("0");
			$("#span_sell_volume_all").text("0");
		}
	}
</script>