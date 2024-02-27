 <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" />
 <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" />

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="<?php  echo $this->request->webroot ?>js/jquery-ui.min.js"></script> 
<?php
	if($secondCoin!="USDT" && $secondCoin!="ETH" && $secondCoin!="USDT" && $secondCoin!="XRP"  && $secondCoin!="BTC" && $secondCoin!="BNB") {
		$selector_id=0;
	}else{
		$selector_id=0;
	}

?>
 <style>
.dataTables_wrapper{
	background-color: #fff;
}

input[type=number] {
    -moz-appearance:textfield;
}
.modal-open {
	overflow: hidden
}

.modal {
	position: fixed;
	top: 0;
	right: 0;
	bottom: 0;
	left: 0;
	z-index: 1050;
	display: none;
	overflow: hidden;
	-webkit-overflow-scrolling: touch;
	outline: 0
}

.modal.fade .modal-dialog {
	-webkit-transform: translate(0, -25%);
	-ms-transform: translate(0, -25%);
	-o-transform: translate(0, -25%);
	transform: translate(0, -25%);
	-webkit-transition: -webkit-transform .3s ease-out;
	-o-transition: -o-transform .3s ease-out;
	transition: -webkit-transform .3s ease-out;
	transition: transform .3s ease-out;
	transition: transform .3s ease-out, -webkit-transform .3s ease-out, -o-transform .3s ease-out
}

.modal.in .modal-dialog {
	-webkit-transform: translate(0, 0);
	-ms-transform: translate(0, 0);
	-o-transform: translate(0, 0);
	transform: translate(0, 0)
}

.modal-open .modal {
	overflow-x: hidden;
	overflow-y: auto
}

.modal-dialog {
	position: relative;
	width: auto;
	margin: 10px
}

.modal-content {
	position: relative;
	background-color: #fff;
	background-clip: padding-box;
	border: 1px solid #999;
	border: 1px solid rgba(0, 0, 0, .2);
	border-radius: 6px;
	-webkit-box-shadow: 0 3px 9px rgba(0, 0, 0, .5);
	box-shadow: 0 3px 9px rgba(0, 0, 0, .5);
	outline: 0
}

.modal-backdrop {
	position: fixed;
	top: 0;
	right: 0;
	bottom: 0;
	left: 0;
	z-index: 1040;
	background-color: #000
}

.modal-backdrop.fade {
	filter: alpha(opacity=0);
	opacity: 0
}

.modal-backdrop.in {
	filter: alpha(opacity=50);
	opacity: .5
}

.modal-header {
	padding: 15px;
	border-bottom: 1px solid #e5e5e5
}

.modal-header .close {
	margin-top: -2px
}

.modal-title {
	margin: 0;
	line-height: 1.42857143
}

.modal-body {
	position: relative;
	padding: 15px
}

.modal-footer {
	padding: 15px;
	text-align: right;
	border-top: 1px solid #e5e5e5
}

.modal-footer .btn+.btn {
	margin-bottom: 0;
	margin-left: 5px
}

.modal-footer .btn-group .btn+.btn {
	margin-left: -1px
}

.modal-footer .btn-block+.btn-block {
	margin-left: 0
}

.modal-scrollbar-measure {
	position: absolute;
	top: -9999px;
	width: 50px;
	height: 50px;
	overflow: scroll
}

@media (min-width:768px) {
	.modal-dialog {
		width: 600px;
		margin: 30px auto
	}
	.modal-content {
		-webkit-box-shadow: 0 5px 15px rgba(0, 0, 0, .5);
		box-shadow: 0 5px 15px rgba(0, 0, 0, .5)
	}
	.modal-sm {
		width: 300px
	}
}

@media (min-width:992px) {
	.modal-lg {
		width: 900px
	}
}


.contents .base_coin ul.results .red{
	font-size:14px !important;
}
.contents .base_coin ul.results .unit{
	font-size:14px !important;
}
.contents .base_coin ul.results .blue{
	font-size:14px !important;
}

 </style>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>Coming Soon .......</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<?php if(in_array($firstCoin,["BTC",'ETH','USDT'])){ ?>
<script>
 $(document).ready(function(){
	 $("#myModal").modal('show');
 })
</script>
<?php }  else { ?>

<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/new_exchange.css?ti=<?php echo time(); ?>">
<script src="<?php echo $this->request->webroot ?>js/highstock.js"></script>

<div class="container containerpp" >

	<div class="exchange_frame">
<!--<a id="nav_bar" href="#"><i class="fa fa-bars"></i></a>-->
		<div class="section_ex left_info">
			<ul class="coin_tab">
				<li class="<?php echo $krwTabClass; ?>"><a  href="<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'index','USDT','KRW']); ?>" >KRW</a></li>
				<li class="<?php echo $ethTabClass; ?>"><a data-toggle="modal" data-target="#myModal"  href="javascript:void(0)<?php //echo $this->Url->Build(['controller'=>'exchange','action'=>'index','BCHSV','ETH']); ?>" >ETH</a></li>
				<li class="<?php echo $btcTabClass; ?>"><a data-toggle="modal" data-target="#myModal"  href="javascript:void(0)<?php //echo $this->Url->Build(['controller'=>'exchange','action'=>'index','ETH','BTC']); ?>" >BTC</a></li>
				<li class="<?php echo $usdtTabClass; ?>"><a  data-toggle="modal" data-target="#myModal"  href="javascript:void(0)<?php //echo $this->Url->Build(['controller'=>'exchange','action'=>'index','ETH','USDT']); ?>" >USDT</a></li>
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

							  
							 
							  /* if($authUserId != 10003992 && $getCoinPairSingle['id'] == 7){
							    continue;
							
							   } */
							  $color = '';
							if($currentCoinPairDetail['id']==$getCoinPairSingle['id']){
								$color = "style='color:#07ff07'";
							}
							
							$symbol = '';
							if($getCoinPairSingle['cryptocoin_first']['id']==5){
								$symbol = ' $';
							}
							//$getMyCustomPrice = $this->CurrentPrice->getCurrentPrice($getCoinPairSingle['cryptocoin_second']['id']);
							$getMyCustomPrice = $this->CurrentPrice->getCurrentPrice($getCoinPairSingle['cryptocoin_first']['id'],$getCoinPairSingle['cryptocoin_second']['id']);		
							//$getMyCustomPrice  = number_format($getMyCustomPrice,8); 
								if(!empty($principalBalance)){
								
								
						  ?>
					
					<tr class="show_currency <?php echo $secondCoinId==$getCoinPairSingle['cryptocoin_first']['id'] ? "on" : ""; ?>">
						<td class="left"><a  href="<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'index',$getCoinPairSingle['cryptocoin_first']['short_name'],$getCoinPairSingle['cryptocoin_second']['short_name']]); ?>" > <span class="bold"><?php echo $getCoinPairSingle['cryptocoin_first']['short_name']; ?></span> (<?php  echo $getCoinPairSingle['cryptocoin_second']['short_name']; ?>)</a></td>
						<td ><span id="percent_current_price_<?php echo $getCoinPairSingle['cryptocoin_first']['short_name']."_".$getCoinPairSingle['cryptocoin_second']['short_name']; ?>"></span></td>
						<td class="right"  id="current_price_<?php echo $getCoinPairSingle['cryptocoin_first']['short_name']."_".$getCoinPairSingle['cryptocoin_second']['short_name']; ?>"><?php echo round($getMyCustomPrice,4);//.$symbol; ?></td>
					</tr>
					<?php }else{
						?>	
					<tr  class="<?php echo $secondCoinId==$getCoinPairSingle['cryptocoin_first']['id'] ? "on" : ""; ?> hide_currency">
						<td class="left"><a  href="<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'index',$getCoinPairSingle['cryptocoin_first']['short_name'],$getCoinPairSingle['cryptocoin_second']['short_name']]); ?>" > <span class="bold"><?php echo $getCoinPairSingle['cryptocoin_first']['short_name']; ?></span> (<?php  echo $getCoinPairSingle['cryptocoin_second']['short_name']; ?>)</a></td>
						<td ><span id="percent_current_price_<?php echo $getCoinPairSingle['cryptocoin_first']['short_name']."_".$getCoinPairSingle['cryptocoin_second']['short_name']; ?>"></span></td>
						<td class="right"  id="current_price_<?php echo $getCoinPairSingle['cryptocoin_first']['short_name']."_".$getCoinPairSingle['cryptocoin_second']['short_name']; ?>"><?php echo round($getMyCustomPrice,4);//.$symbol; ?></td>
					</tr>

						<?php

					}} ?>
					
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

			<div class="base_graph" id="container">
			</div>
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
			<table class="result">
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
			<table class="result">
				<thead>
					<tr class="buy">
												
						<td><?=__('Buy') ?> <?=__('Order balance') ?> <span class="right"  id="buy_order_balance">0</span></td>
						
					</tr>
				</thead>
			</table>
		</div>
	
			
			
			<div class="section_ex contents contents22">
			<span id="current_price" style="display:none;">0</span>
			<div class="tranx">






					<div class="buy buy_man">
					<div class="paddbs">
						<script>
						$(document).ready(function(){
							$('input[type=radio]').click(function(){
								var getVal = $(this).val();
								if(getVal=="buy_limit_tab"){
									currentPrice =  "";
									$("#buy_per_price").val(0).change();
									$("#buy_per_price_div").show();
								}
								else if(getVal=="buy_market_tab"){
									currentPrice =  $("#current_price").html();

									$("#buy_per_price").val(currentPrice).change();
									$("#buy_per_price_div").hide();
								}
								else if(getVal=="sell_limit_tab"){
									currentPrice =  "";
									$("#sell_per_price").val(0).change();
									$("#sell_per_price_div").show();
								}
								else if(getVal=="sell_market_tab"){
									currentPrice =  $("#current_price").html();
									$("#sell_per_price").val(currentPrice).change();
									$("#sell_per_price_div").hide();
								}
							})
						})
						</script>
						<ul class="opt_row">
							<li>
								<h2><?=__('Buy') ?></h2>
							</li>
							<li style="line-height:4">
								<label><input type="radio" name="buy_control" value="buy_limit_tab" checked /> <?=__('Limits') ?> </label>
								<label><input type="radio" name="buy_control" value="buy_market_tab" /> <?=__('Market price') ?> </label>
							</li>
						</ul>
						<?php if(!empty($authUserId)/*  && $googleAuthVerify=='Y' */) { ?>
							<form method="post" id="buy_form" onsumit="return false;" accept-charset="utf-8" action="<?php echo $this->Url->build(['prefix'=>'front2','controller'=>'exchange' , 'action'=>'index',$firstCoin,$secondCoin]);  ?>">
						<?php } ?>
						<input type="hidden" name="type" value="buy"/>
						<ul class="row won_box">
							<li style="width: auto;">
								<span class="bold"><?=__('Order available') ?></span>
							</li>
							<li style="width: auto;float:right">
								<span id="span_buy_volume_all"><?php echo number_format((float)$firstCoinSum,2); ?></span> <span class="bold unit"><?=__('WON') ?>(<?php echo $firstCoin; ?>)</span>
							</li>
						</ul>

						<ul class="row input_ul" id="buy_per_price_div">
							<li class="monone">
								<?=__('Price') ?> (<?php echo $firstCoin; ?>)
							</li>
							<li>
								<div class="price2 pr28">
								<?php
								$min="0.00";
								$step ="1.00";
									if($secondCoin=="TP3"){
										$min='0.10';
										$step="0.10";

									}
									if($secondCoin=="BTC"){
										$min='0.0';
										$step="5000.0";

									}
									if($secondCoin=="CTC"){
										$min='0.0';
										$step="0.1";

									}
									if($secondCoin=="ETH"){
										$min='0.0';
										$step="500.0";

									}
									if($secondCoin=="USDT"){
										$min='0.0';
										$step="0.01";

									}
									if($secondCoin=="XRP"){
										$min='0.0';
										$step="0.10";

									}


								?>
									 <input placeholder="<?=__('Price') ?> (<?php echo $firstCoin; ?>)" type="text"  step="<?php echo $step;?>" min="<?php echo $min;?>" required autocomplete="false" id="buy_per_price" name="per_price" onkeypress="return isNumberKey(this, event);" />
<span class="up1" id="buy_price_up" onclick="increment('<?php echo $step;?>')"><i class="fa fa-caret-up"></i></span>
<span class="up2" id="buy_price_down" onclick="decrement('<?php echo $step;?>')"><i class="fa fa-caret-down"></i>
</span>
								</div>

							</li>
						</ul>
						<ul class="row input_ul	">
							<li class="monone">
								<?=__("Q'ty") ?> (<?php echo $secondCoin; ?>)
							</li>

							<li>
								<div class="price2 pr28">
								<input placeholder="<?=__("Q'ty") ?> (<?php echo $secondCoin; ?>)" type="text"    autocomplete="false" required id="buy_volume" name="volume" onkeypress="return isNumberKey(this, event);"/>
								<span class="up1" id="buy_quantity_up" onclick="increment2('1')"><i class="fa fa-caret-up"></i></span>
<span class="up2" id="buy_quantity_down" onclick="decrement2('1')"><i class="fa fa-caret-down"></i>
</span>

								</div>

							</li>
						</ul>
						 <ul class="mytest " style="overflow:visible;">
										  <li><a href="javascript:void(0);" onClick="setAmount(this,'buy')" data-id="25">25%</a></li>
										  <li><a href="javascript:void(0);" onClick="setAmount(this,'buy')" data-id="50">50%</a></li>
										  <li><a href="javascript:void(0);" onClick="setAmount(this,'buy')" data-id="75">75%</a></li>
										  <li><a href="javascript:void(0);" onClick="setAmount(this,'buy')" data-id="100">100%</a></li>
										</ul>

						<ul class="row input_ul">
							<li>
								<span class="bold"><?=__('Order amount') ?></span>
							</li>
							<li>
								<!--<span class="bold price" id="buy_total_amount">0</span> -->
								<input class="bold price" id="buy_total_amount" value=0 readonly /> (<?php echo $firstCoin; ?>)
							</li>
						</ul>
						<input type="hidden" class="form-control text-right" autocomplete="false" id="buy_admin_fee" disabled placeholder="0.5% Admin Fee">
						<div id="show_buy_resp"></div>

						<?php if(!empty($authUserId)/*  && $googleAuthVerify=='Y' */) { ?>
							<input type="submit" class="button buy buy_ntr" value="<?=__('Buy') ?>">
						<?php
							} else {
								if(empty($authUserId)){
									//echo '<a  class="button buy buy_ntr" href="/front2">Login </a>';
									echo '<a class="button buy buy_ntr" href="/front2">Login</a>';
								}
								else {
									echo '<a  class="button buy buy_ntr" href="/front2/users/security">Verify Authenticator</a>';
								}

							} ?>
						 <?php if(!empty($authUserId)/*  && $googleAuthVerify=='Y' */) { ?>
						 </form>
						 <?php } ?>
						 <span id="admin_show_fee" style="float:right;font-size:12px;"> Fee - <?php echo $adminFee; ?>%</span>
					</div>
					</div>





                <div class="sell sell_man">
                    <div class="paddbs">
                        <?php if(!empty($authUserId)/*  && $googleAuthVerify=='Y' */) { ?>
                        <form method="post" id="sell_form" onsumit="return false;" accept-charset="utf-8" action="<?php echo $this->Url->build(['prefix'=>'front2','controller'=>'exchange' , 'action'=>'index',$firstCoin,$secondCoin]);  ?>">
                            <?php } ?>
                            <input type="hidden" name="type" value="sell"/>
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
                                    <span id="span_sell_volume_all"><?php echo number_format((float)$secondCoinSum,2); ?> </span><span class="bold unit">(<?php echo $secondCoin; ?>)</span>
                                </li>
                            </ul>

                            <ul class="row input_ul" id="sell_per_price_div">
                                <li class="monone">
                                    <?=__('Price') ?> (<?php echo $firstCoin; ?>)
                                </li>
                                <li>
                                    <div class="price2 pr28">
                                        <?php
                                        $min1="0.00";
                                        $step1 ="1.00";
                                        if($secondCoin=="TP3"){
                                            $min1='0.10';
                                            $step1="0.10";

                                        }
                                        if($secondCoin=="BTC"){
                                            $min1='0.0';
                                            $step1="5000.0";

                                        }
                                        if($secondCoin=="CTC"){
                                            $min1='0.00';
                                            $step1="0.1";

                                        }
                                        if($secondCoin=="ETH"){
                                            $min1='0.0';
                                            $step1="500";

                                        }
                                        if($secondCoin=="USDT"){
                                            $min1='0.0';
                                            $step1="0.01";

                                        }
                                        if($secondCoin=="XRP"){
                                            $min1='0.0';
                                            $step1="0.1";

                                        }

                                        ?>
                                        <input placeholder="<?=__('Price') ?> (<?php echo $firstCoin; ?>)"
                                               step="<?php echo $step;?>" min="<?php echo $min;?>"
                                               type="text"  required autocomplete="false" id="sell_per_price" name="per_price" onkeypress="return isNumberKey(this, event);" />
                                        <span class="up1" id="sell_price_up" onclick="increment1('<?php echo $step1;?>')"><i class="fa fa-caret-up"></i></span>
                                        <span class="up2" id="sell_price_down" onclick="decrement1('<?php echo $step1;?>')"><i class="fa fa-caret-down"></i></span>
                                    </div>

                                </li>
                            </ul>

                            <ul class="row input_ul">
                                <li class="monone">
                                    <?=__("Q'ty") ?> (<?php echo $secondCoin; ?>)
                                </li>
                                <li>
                                    <div class="price2 pr28">
                                        <input type="text"   placeholder="<?=__("Q'ty") ?> (<?php echo $secondCoin; ?>)"  required autocomplete="false"  id="sell_volume" name="volume" onkeypress="return isNumberKey(this, event);">
                                        <span class="up1" id="sell_quantity_up" onclick="increment3('1')"><i class="fa fa-caret-up"></i></span>
                                        <span class="up2" id="sell_quantity_down" onclick="decrement3('1')"><i class="fa fa-caret-down"></i>
</span>
                                    </div>
                                </li>
                            </ul>
                            <ul class="mytest" style="overflow:visible;">
                                <li><a href="javascript:void(0);" onClick="setAmount(this,'sell')" data-id="25">25%</a></li>
                                <li><a href="javascript:void(0);" onClick="setAmount(this,'sell')" data-id="50">50%</a></li>
                                <li><a href="javascript:void(0);" onClick="setAmount(this,'sell')" data-id="75">75%</a></li>
                                <li><a href="javascript:void(0);" onClick="setAmount(this,'sell')" data-id="100">100%</a></li>
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

                            <!-- empty : need space
                            <ul class="row">
                                <li>&nbsp;</li>
                            </ul>
                            <ul class="row">
                                <li>&nbsp;</li>
                            </ul>
                            empty : need space -->
                            <input type="hidden" class="form-control text-right" autocomplete="false" id="sell_admin_fee" disabled placeholder="0.5% Admin Fee">
                            <div id="show_sell_resp"></div>
                            <?php if(!empty($authUserId)/*  && $googleAuthVerify=='Y' */) { ?>
                                <input type="submit" class="button sell sell_ntr" value="<?=__('Sell') ?>" >
                                <?php
                            } else {
                                if(empty($authUserId)){
                                    //echo '<a  class="button sell sell_ntr" href="/front2" >Login</a>';
				    echo '<a class="button sell sell_ntr" href="/front2">Login</a>';
                                }
                                else {
                                    echo '<a  class="button sell sell_ntr" href="/front2/users/security">Verify Authenticator</a>';
                                }

                            } ?>
							 <?php if(!empty($authUserId)/*  && $googleAuthVerify=='Y' */) { ?>
						 </form>
						 <?php } ?>
						<span id="admin_show_fee" style="float:right;font-size:12px;"> Fee - <?php echo $adminFee; ?>%</span>
								
                    </div>
                </div>





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
				
				<!--<div class="tablewidth" style="background:#fff; height:170px; overflow-x: auto; overflow-y:auto;">
					<table class="list" id="21222123">
					
					
					</table>
				</div>-->
			</div>
			<div class="order_tab_system" id="sale_li_div" style="display:none">
				<table class="list" id="mySellOrderlist_table">
					<thead>
						<tr>
							<td><?=__('Order date') ?></td>
							<td><?=__('Price per '.$secondCoin) ?></td>
							<td><?php echo $secondCoin; ?> <?=__('Amount') ?></td>
							<td><?=__($firstCoin.' amount') ?></td>
							<td><?=__('State') ?></td>
							<td><?=__('Action') ?></td>
						</tr>
					</thead>
					<tbody id="mySellOrderlist">
						
					  </tbody>
				</table>

				<!--<div class="tablewidth" style="background:#fff; height:170px; overflow-x: auto; overflow-y:auto">
					<table class="list" id="mySellOrderlist11">
					
					
					</table>
				</div>-->
			</div>

		</div>

	</div>

</div>
<!-- Modal -->
<div id="priceRangeModal" class="modal fade" style="top:25%;" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" onClick="closeModel();" style="float:right;" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Mesage</h4>
      </div>
      <div class="modal-body">
        <p id="priceRangeMessage"></p>
      </div>
    </div>

  </div>
</div>
    <script type="text/javascript">
        function isNumberKey(txt, evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode === 46) {
                //Check if the text already contains the . character
                return txt.value.indexOf('.') === -1;
            } else {
                if (charCode > 31 &&
                    (charCode < 48 || charCode > 57))
                    return false;
            }
            return true;
        }

        function confirm_alert() {

                var language = window.navigator.userLanguage || window.navigator.language;

                if(language === "ko-KR"){
                    return confirm("서버 점검 안내 \n2020년12월 29일 18시부터 \n2020년 12월 30일 18시까지");
                } else {
                    return confirm("Service temporary unavailable \nFrom 18:00 on December 29, 2020 \nTill 18:00 December 30, 2020");
                }//works IE/SAFARI/CHROME/FF
        }
    </script>


<script>
function closeModel(){
	$("#priceRangeModal").hide();
}
var pairCurrentPrice = 0;
var fee = 0.50;
	$(document).ready(function(){
		
		var interval;
		
		// buy price 
		$("#buy_price_up").mousedown(function(e) {
			
			 interval = setInterval(function() {
				$("#buy_price_up").click().trigger();
			},100); // 500ms between each frame 
		});
		$("#buy_price_up").mouseup(function(e) {
			clearInterval(interval);
			
		});
		
		$("#buy_price_down").mousedown(function(e) {
			
			 interval = setInterval(function() {
				$("#buy_price_down").click().trigger();
			},100); // 500ms between each frame 
		});
		$("#buy_price_down").mouseup(function(e) {
			clearInterval(interval);
			
		});
		
		// buy quantity 
		$("#buy_quantity_up").mousedown(function(e) {
			
			 interval = setInterval(function() {
				$("#buy_quantity_up").click().trigger();
			},100); // 500ms between each frame 
		});
		$("#buy_quantity_up").mouseup(function(e) {
			clearInterval(interval);
			
		});
		
		$("#buy_quantity_down").mousedown(function(e) {
			
			 interval = setInterval(function() {
				$("#buy_quantity_down").click().trigger();
			},100); // 500ms between each frame 
		});
		$("#buy_quantity_down").mouseup(function(e) {
			clearInterval(interval);
			
		});
		
		
		
		// sell price 
		$("#sell_price_up").mousedown(function(e) {
			
			 interval = setInterval(function() {
				$("#sell_price_up").click().trigger();
			},100); // 500ms between each frame 
		});
		$("#sell_price_up").mouseup(function(e) {
			clearInterval(interval);
			
		});
		
		$("#sell_price_down").mousedown(function(e) {
			
			 interval = setInterval(function() {
				$("#sell_price_down").click().trigger();
			},100); // 500ms between each frame 
		});
		$("#sell_price_down").mouseup(function(e) {
			clearInterval(interval);
			
		});
		
		// sell quantity 
		$("#sell_quantity_up").mousedown(function(e) {
			
			 interval = setInterval(function() {
				$("#sell_quantity_up").click().trigger();
			},100); // 500ms between each frame 
		});
		$("#sell_quantity_up").mouseup(function(e) {
			clearInterval(interval);
			
		});
		
		$("#sell_quantity_down").mousedown(function(e) {
			
			 interval = setInterval(function() {
				$("#sell_quantity_down").click().trigger();
			},100); // 500ms between each frame 
		});
		$("#sell_quantity_down").mouseup(function(e) {
			clearInterval(interval);
			
		});
		
		
		
		
		
		
		setGraph();
		//getLastTwentyFourHourTicker();
		setInterval(function(){ checkExchange(); getPairCurrentPrice(); getLastTwentyFourHourTicker();  }, 5000);
		setInterval(function(){ setGraph();  }, 10000);
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
					"url": "https://www.coinibt.io/datatable_language/<?php echo __('datatable_language') ?>.json"
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
						"url": "https://www.coinibt.io/datatable_language/<?php echo __('datatable_language') ?>.json"
					}
					
				}); 
				},1000); 
			})
			
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


        //var iOS = /iPad/.test(navigator.userAgent);
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
			$("#coin_show").toggle()
			})
		//exchange js
		
		$("#span_buy_volume").click(function(){
			var getVal = $("#span_buy_volume_all").val();
			$("#buy_total_amount").val(getVal);
		});
		
		$("#span_sell_volume").click(function(){
			var getVal = $("#span_sell_volume_all").val();
			//$("#sell_total_amount").val(getVal).change();
			$("#sell_volume").val(getVal).change();
		});
		
		$('#buy_volume').on('input', function () { 
			calculateForm($(this).attr('id'),'buy')
		});

		$('#buy_per_price').on('input', function () {
			calculateForm($(this).attr('id'),'buy')
		});
		
		$('#buy_total_amount').on('change', function () {
			calculateForm($(this).attr('id'),'buy')
		});
		
		$('#buy_total_amount').on('input', function () {
			calculateForm($(this).attr('id'),'buy')
		});
		
		$('#sell_volume').on('input', function () { 
			calculateForm($(this).attr('id'),'sell')
		});

		$('#sell_per_price').on('input', function () {
			calculateForm($(this).attr('id'),'sell')
		});
		
		$('#sell_total_amount').on('change', function () {
			calculateForm($(this).attr('id'),'sell')
		});
		
		$('#sell_total_amount').on('input', function () {
			calculateForm($(this).attr('id'),'sell')
		});
		
		
		// my order tab fuction
		$(".myorder_li").click(function(){
			$(".myorder_li").removeClass("on");
			$(this).addClass("on");
			var getId = $(this).attr('id');
			var showDivId = getId+"_div";
			$(".order_tab_system").hide();
			$("#"+showDivId).show();
		});
				
		<?php if(!empty($authUserId)/*  && $googleAuthVerify=='Y' */) { ?>
			$('form#buy_form').submit(function(event) {
				
				event.preventDefault(); // Prevent the form from submitting via the browser
				
				
				
				var buyPerPrice =  $("#buy_per_price").val();
				
				//max_buysell_per
				var getTenPercentHighOfCurrentPrice = pairCurrentPrice*<?php echo $max_buysell_per ?>/100;
				var maxPerPrice = parseFloat(pairCurrentPrice) + parseFloat(getTenPercentHighOfCurrentPrice);
				if(buyPerPrice > maxPerPrice){
						
						$("#priceRangeMessage").html("The daily upper limit price has been exceededs.");
						$('#priceRangeModal').show();
						return false;
				}
				//min_buysell_per
				var getTenPercentLowOfCurrentPrice = pairCurrentPrice*<?php echo $min_buysell_per ?>/100;
				var minPerPrice = parseFloat(pairCurrentPrice) - parseFloat(getTenPercentLowOfCurrentPrice);
				if(buyPerPrice < minPerPrice){
						
						$("#priceRangeMessage").html("The daily lower price has been exceededs.");
						$('#priceRangeModal').show();
						return false;
				}
				
				//max_market_per
				var getTenPercentHighOfCurrentPrice =<?php echo $mid_night_price*$max_market_per/100 ?>;
				var maxPerPrice = parseFloat(pairCurrentPrice) + parseFloat(getTenPercentHighOfCurrentPrice);
				if(buyPerPrice > maxPerPrice){
						
						$("#priceRangeMessage").html("The daily upper limit price has been exceeded.");
						$('#priceRangeModal').show();
						return false;
				}
				//min_market_per
				var getTenPercentLowOfCurrentPrice = <?php echo $mid_night_price*$min_market_per/100 ?>;
				var minPerPrice = parseFloat(pairCurrentPrice) - parseFloat(getTenPercentLowOfCurrentPrice);
				if(buyPerPrice < minPerPrice){
						
						$("#priceRangeMessage").html("The daily lower price has been exceeded.");
						$('#priceRangeModal').show();
						return false;
				}
				
				
				
				
				
				$('form#buy_form [type=submit]').hide();
				var form = $(this);
				var formData = new FormData(this);
				
				
				// ajax for market History list 
				$.ajax({
					beforeSend : function(){ 
						$('#show_buy_resp').html("<img src='<?php echo $this->request->webroot ?>ajax-loader.gif' />");
					},
					//url : '<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'index',$firstCoin,$secondCoin]); ?>',
					url : '<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'index',$secondCoin,$firstCoin]); ?>',
					type : 'post',
					data : formData,
					contentType: false,
					//cache: false,
					dataType:'json',
					processData:false,
					success : function(resp){ 
						$('#show_buy_resp').html(resp.message);
						setTimeout(function(){ $('#show_buy_resp').html(''); },5000);
						//call to exchange
						if(resp.error==0){
							callAjaxExchange(formData,'buy');
						}
						else {
							$('form#buy_form [type=submit]').show();
							
						}
						//window.location.reload();
					}
				})
				
			});	
			
			
			$('form#sell_form').submit(function(event) {
				
				event.preventDefault(); // Prevent the form from submitting via the browser
				
				
				
				var sellPerPrice =  $("#sell_per_price").val();
				//max_buysell_per
				
				var getTenPercentHighOfCurrentPrice = pairCurrentPrice*<?php echo $max_buysell_per ?>/100;
				var maxPerPrice = parseFloat(pairCurrentPrice) + parseFloat(getTenPercentHighOfCurrentPrice);
				if(sellPerPrice > maxPerPrice){
						
						$("#priceRangeMessage").html("The daily upper limit price has been exceeded.s");
						$('#priceRangeModal').show();
						return false;
				}
				//min_buysell_per
				var getTenPercentLowOfCurrentPrice = pairCurrentPrice*<?php echo $min_buysell_per ?>/100;
				var minPerPrice = parseFloat(pairCurrentPrice) - parseFloat(getTenPercentLowOfCurrentPrice);
				if(sellPerPrice < minPerPrice){
						
						$("#priceRangeMessage").html("The daily lower price has been exceededs.");
						$('#priceRangeModal').show();
						return false;
				}
				
				//max_market_per
				var getTenPercentHighOfCurrentPrice =<?php echo $mid_night_price*$max_market_per/100 ?>;
				var maxPerPrice = parseFloat(pairCurrentPrice) + parseFloat(getTenPercentHighOfCurrentPrice);
				if(sellPerPrice > maxPerPrice){
						
						$("#priceRangeMessage").html("The daily upper limit price has been exceeded.");
						$('#priceRangeModal').show();
						return false;
				}
				//min_market_per
				var getTenPercentLowOfCurrentPrice = <?php echo $mid_night_price*$min_market_per/100 ?>;
				var minPerPrice = parseFloat(pairCurrentPrice) - parseFloat(getTenPercentLowOfCurrentPrice);
				if(sellPerPrice < minPerPrice){
						
						$("#priceRangeMessage").html("The daily lower price has been exceeded.");
						$('#priceRangeModal').show();
						return false;
				}
				
				$('form#sell_form [type=submit]').hide();
				var form = $(this);
				var formData = new FormData(this);
				
				// ajax for market History list 
				$.ajax({
					beforeSend : function(){
						$('#show_sell_resp').html("<img src='<?php echo $this->request->webroot ?>ajax-loader.gif' />");
					},
					//url : '<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'index',$firstCoin,$secondCoin]); ?>',
					url : '<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'index',$secondCoin,$firstCoin]); ?>',
					type : 'post',
					data : formData,
					contentType: false,
					//cache: false,
					processData:false,
					dataType:'json',
					success : function(resp){
						$('#show_sell_resp').html(resp.message);
						setTimeout(function(){ $('#show_sell_resp').html('') },5000);
						//call to exchange
						if(resp.error==0){
							callAjaxExchange(formData,'sell');
						}
						else {
							$('form#sell_form [type=submit]').show();
						}
						//window.location.reload();
					}
				})
				
			});	

		 <?php } ?>			
		
		
		
		
		
		
		//graph show
		 
	/* 	var jsonData = [];
		<?php  //foreach($getGrpData as $getLastTrans) {?>
			var ddt = [
				<?php //echo strtotime($getLastTrans['datecol'])."000" ?>,
				<?php //echo $getLastTrans['open_price'];  ?>,
				<?php //echo $getLastTrans['max_price'];  ?>,
				<?php //echo $getLastTrans['min_price'];  ?>,
				<?php //echo $getLastTrans['close_price'];  ?>
			 ];
			jsonData.push(ddt);
		<?php //} ?> */
	
		// create the chart
	/* 	Highcharts.stockChart('container', {


			rangeSelector: {
				selected: 1
			},

			title: {
				//text: '<?php //echo $secondCoin; ?> Price'
			},

           

			series: [{
				type: 'candlestick',
				name: '<?php echo $secondCoin; ?> Price',
				data: jsonData,
				dataGrouping: {
					units: [
					
						[
							'day', // unit name
							[1] // allowed multiples
						],
						[
							'week', // unit name
							[1] // allowed multiples
						], [
							'month',
							[1, 2, 3, 4, 6]
						]
					]
				}
			}]
		}); */
		
		/* marketHistory();
		getCurrenPrice();
		notCompletedOrderList();
		myOrderListAjax(); */
		callAllFunctions();
        var chatHistory = document.getElementById("sell_order_show_div");
        chatHistory.scrollTop = chatHistory.scrollHeight;
		
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
							yAxis: [{
									labels: {
										align: 'right',
										x: -3
									},
									title: {
										text: 'OHLC'
									},
									height: '70%',
									lineWidth: 2,
									resize: {
										enabled: true
									}
								}, {
									labels: {
										align: 'right',
										x: -3
									},
									title: {
										text: '<?=__('Volume')?>'
									},
									top: '72%',
									height: '20%',
									offset: 0,
									lineWidth: 2
								}],
							 rangeSelector: {
								 selected : '<?php echo $selector_id;?>',
									 

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
								     buttons: [
										// {
										// 		type: 'minute',
										// 		count: 1,
										// 		text: '1m',

										// 		/* events: {
										// 			click: function() {
										// 				alert('Clicked button');
										// 			}
										// 		} */
										// 	},
										// {
										// 		type: 'minute',
										// 		count: 5,
										// 		text: '5m',

										// 		/* events: {
										// 			click: function() {
										// 				alert('Clicked button');
										// 			}
										// 		} */
										// 	},
										// {
										// 		type: 'minute',
										// 		count: 30,
										// 		text: '30m',
										// 		/* events: {
										// 			click: function() {
										// 				alert('Clicked button');
										// 			}
										// 		} */
										// 	},
										// {
										// 		type: 'hour',
										// 		count: 12,
										// 		text: '12h',
										// 		/* events: {
										// 			click: function() {
										// 				alert('Clicked button');
										// 			}
										// 		} */
										// 	},
											// {
											// 	type: 'day',
											// 	count: 1,
											// 	text: '1d',
											// 	/* events: {
											// 		click: function() {
											// 			alert('Clicked button');
											// 		}
											// 	} */
											// },
											{
												type: 'day',
												count: 15,
												text: '15d',
												/* events: {
													click: function() {
														alert('Clicked button');
													}
												} */
											},
											//  {
											// 	type: 'month',
											// 	count: 1,
											// 	text: '1M'
											// },{
											// 	type: 'month',
											// 	count: 3,
											// 	text: '3M'
											// }, {
											// 	type: 'month',
											// 	count: 6,
											// 	text: '6M'
											// }, {
											// 	type: 'ytd',
											// 	text: 'YTD'
											// }, {
											// 	type: 'year',
											// 	count: 1,
											// 	text: '1y'
											// },
                                            {
												type: 'all',
												text: '<?= __('All') ?>'
											}]
							 },

							 title: {
								 text: '<?php echo $secondCoin; ?> <?= __('Price') ?>'
							 },
						
							 
							 series: [{
								 type: 'candlestick',
								// name: '<?php echo $secondCoin; ?> Price',
								// data: jsonData,
								 dataGrouping: {
									 units: [
										
										[
											 'minute', // unit name
											 [1,5,30] // allowed multiples
										 ],
										 [
											 'day', // unit name
											 [1] // allowed multiples
										 ],
										 [
											 'week', // unit name
											 [1] // allowed multiples
										 ], [
											 'month',
											 [1, 2, 3, 4, 6]
										 ]
									 ]
								 }
							 }, {
								type: 'column',
								//name: 'Volume',
								//data: volume,
								yAxis: 1,
								dataGrouping: {
									units: [
										[
											 'minute', // unit name
											 [1,5,30] // allowed multiples
										 ],
										[
											 'day', // unit name
											 [1] // allowed multiples
										 ],
										 [
											 'week', // unit name
											 [1] // allowed multiples
										 ], [
											 'month',
											 [1, 2, 3, 4, 6]
										 ]
									 ]
								}
							}],
                            lang:{
							    months: [
							        '1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월','12월'
                                ],
                                weekdays: [
                                    '월요일', '화요일', '수요일', '목요일', '금요일', '토요일', '일요일'
                                ]
                            }
						 }, function(chart){

							// apply the date pickers
							setTimeout(function () {
								$('input.highcharts-range-selector', $(chart.container).parent())
									.datepicker();
							}, 0);
						});	
	function callAllFunctions() {
		//setGraph();
		notCompletedOrderList();
		myOrderListAjax();
		marketHistory();
		getUserBalance();
		getCurrenPrice();
	}
	
	
	function setGraph(){
		<?php if($binancePrice=="N") { ?>
		  // ajax for market History list from DB
			$.ajax({
				
				url : '<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'getGraphData',$firstCoinId,$secondCoinId]); ?>',
				type : 'GET',
				dataType:'json',
				success : function(resp){ 
				var jsonData = [];
					var jsonDataVolume = [];
					if(resp.success=="true"){
						$.each(resp.data,function(key,valE){
							var ddt = [
								parseFloat(valE.datecol+"000"),
								parseFloat(valE.open_price),
								parseFloat(valE.max_price),
								parseFloat(valE.min_price),
								parseFloat(valE.close_price)
							];
							jsonData.push(ddt);
							
							
							var setColorColumn = (parseFloat(valE.close_price) > parseFloat(valE.open_price)) ? '#0c45d5' : '#d80000';
							//var ddtVolume = [parseFloat(valE[0]),parseFloat(valE[5]),color: '#f00'];
							jsonDataVolume.push({x:parseFloat(valE.datecol+"000"),y:parseFloat(valE.open_price),color: setColorColumn});
							
							/* var ddtVolume = [parseFloat(valE.datecol+"000"),parseFloat(valE.open_price)];
							jsonDataVolume.push(ddtVolume); */

						})
						
						myRealGraph.series[0].setData(jsonData);
						myRealGraph.series[1].setData(jsonDataVolume);
						
						
					}
					
				}
			})
			
		<?php } else {  ?>
		 // ajax for market History list From Binance 
			$.ajax({
				
				//url : 'https://api.binance.com/api/v3/klines?symbol=BTCBKRW&interval=1d',
				url : 'https://api.binance.com/api/v3/klines?symbol=<?php echo $secondCoin ?>B<?php echo $firstCoin ?>&interval=1m&limit=10000',
				type : 'GET',
				dataType:'json',
				success : function(resp){ 
				
				var jsonData = [];
				var jsonDataVolume = [];
					//if(resp.success=="true"){
						 $.each(resp,function(key,valE){
							var ddt = [
								parseFloat(valE[0]),
								parseFloat(valE[1]),
								parseFloat(valE[2]),
								parseFloat(valE[3]),
								parseFloat(valE[4])
								//parseFloat(valE[3])
							];
							jsonData.push(ddt);
							var setColorColumn = (parseFloat(valE[4]) > parseFloat(valE[1])) ? '#0c45d5' : '#d80000';
							//var ddtVolume = [parseFloat(valE[0]),parseFloat(valE[5]),color: '#f00'];
							jsonDataVolume.push({x:parseFloat(valE[0]),y:parseFloat(valE[5]),color: setColorColumn});
						}) 
						
						
						myRealGraph.series[0].setData(jsonData);
						myRealGraph.series[1].setData(jsonDataVolume);
						
					//}
					
				}
			})
		
		<?php } ?>
	  } 
	// ajax for user balance
	function checkExchange() {
		$.ajax({
			url : '<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'checkExchange',$firstCoinId,$secondCoinId]); ?>',
			type : 'get',
			//dataType : 'json',
			success : function(resp){
				if(resp==1){
					callAllFunctions();
				}
				
				
			}
		});
	}	
	function updateCurrentPrice() {
		$.ajax({
			url : '<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'updateMyPrice',$firstCoinId,$secondCoinId]); ?>',
			type : 'get',
			dataType : 'json',
			success : function(resp){
				
				
			}
		});
	}
	setInterval(function(){ updateCurrentPrice(); }, 5000);

	updateCurrentPrice();
	// ajax for user balance
	function getUserBalance() {
			<?php if(!empty($authUserId)/*  && $googleAuthVerify=='Y' */) { ?>
		$.ajax({
			url : '<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'getUserBalance',$firstCoinId,$secondCoinId]); ?>',
			type : 'get',
			dataType : 'json',
			success : function(resp){
				var firstCoinBalance = parseFloat(resp.firstCoinBalance).toFixed(2);
				var secondCoinBalance = parseFloat(resp.secondCoinBalance).toFixed(2);
				$("#span_buy_volume_all").val(firstCoinBalance);
				$("#span_sell_volume_all").val(secondCoinBalance);
			}
		});
			<?php } ?>
	}
	function clearBuyForm(){
		$("#buy_volume").val('');
		$("#buy_per_price").val('');
		$("#buy_total_amount").val('');
		$("#buy_admin_fee").val('');
	}
	
	
	function clearSellForm(){
		$("#sell_volume").val('');
		$("#sell_per_price").val('');
		$("#sell_total_amount").val('');
		$("#sell_admin_fee").val('');
	}
	
	
	function callAjaxExchange(formData,requestType){
		<?php if(!empty($authUserId)/*  && $googleAuthVerify=='Y' */) { ?>
			$.ajax({
				url : '<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'exchange',$firstCoin,$secondCoin]); ?>',
				type : 'post',
				data : formData,
				contentType: false,
				//cache: false,
				processData:false,
				success : function(resp){
					if(requestType=='buy'){
						clearBuyForm();	
						$('form#buy_form [type=submit]').show();
					}
					else {
						clearSellForm();
						$('form#sell_form [type=submit]').show();
					}
					
					
				}
			});
		<?php } ?>
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
		var volume = $("#"+exType+"_volume").val();
		var volume = parseFloat(volume);
		var volume = volume;
		
		var totalAmt = $("#"+exType+"_total_amount").val();
		var totalAmt = parseFloat(totalAmt);
		var totalAmt = totalAmt;
		
		var perPrice = $("#"+exType+"_per_price").val();
		var perPrice = parseFloat(perPrice);
		var perPrice = perPrice;
		
		if(thisId == exType+"_volume" && !isNaN(perPrice)){
			// calculate total 
			var totalAmt = volume*perPrice;
			totalAmt = parseFloat(totalAmt);
			totalAmt = totalAmt;
			if(!isNaN(totalAmt)){
				$("#"+exType+"_total_amount").val(totalAmt);
				// calculate fee
				var calFee = calAdminFee(totalAmt);
				$("#"+exType+"_admin_fee").val(calFee);
			}
			
			
		}
		
		if(thisId == exType+"_per_price"){ 
			if(!isNaN(volume)){ 
				var totalAmt = volume*perPrice;
				totalAmt = parseFloat(totalAmt);
				totalAmt = totalAmt;
				if(!isNaN(totalAmt)){
					$("#"+exType+"_total_amount").val(totalAmt);
					// calculate fee
					var calFee = calAdminFee(totalAmt);
					$("#"+exType+"_admin_fee").val(calFee);
				}
			}
			else{
				
				var totalAmt = $("#"+exType+"_total_amount").val();
				var volume = totalAmt/perPrice;
				volume = parseFloat(volume);
				volume = volume;
				if(!isNaN(volume)){
					if(volume!=0){
						$("#"+exType+"_volume").val(volume);
					}
					// calculate fee
					var calFee = calAdminFee(totalAmt);
					$("#"+exType+"_admin_fee").val(calFee);
				}
				
			}
		}
		
		if(thisId == exType+"_total_amount" && !isNaN(perPrice)){
			var totalAmt = $("#"+thisId).val();
			var volume = totalAmt/perPrice;
			volume = parseFloat(volume);
			volume = volume;
			if(!isNaN(volume)){
				if(volume!=0){
					$("#"+exType+"_volume").val(volume);
				}
				// calculate fee
				var calFee = calAdminFee(totalAmt);
				$("#"+exType+"_admin_fee").val(calFee);
			}
		}
	}	

	
	
	
	
	// ajax for market History list
	function marketHistory() {
		<?php if($binancePrice=="N") { ?>
			$.ajax({
				url : '<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'marketHistory',$firstCoinId,$secondCoinId]); ?>',
				type : 'get',
				dataType : 'json',
				success : function(resp){
					// my buyOrderList data
					var html = '';
					if($.isEmptyObject(resp)){
						html = html + '<tr>';
						html = html + "<td colspan=5><?= __('Order not available')?></td>";
						html = html + '</tr>';
					}
					else {
						$.each(resp,function(key,value){
							var sellPurchaseType = "";
							var perPrice = "";
							var sellPurchaseAmt = '';
						/* 	if(value.get_cryptocoin_id==<?php echo $secondCoinId; ?>){
								var sellPurchaseType = "Buy";
							}
							else {
								var sellPurchaseType = "Sell";
							} */
							
							if(value.get_cryptocoin_id==<?php echo $secondCoinId; ?>){
								var perPrice = (value.get_per_price);
							}
							else {
								var perPrice = (value.spend_per_price);
							}
							
							if(value.get_cryptocoin_id==<?php echo $secondCoinId; ?>){
								var sellPurchaseAmt = (value.get_amount);
							}
							else {
								var sellPurchaseAmt = (value.spend_amount);
							}
							
							var totalPrice = (sellPurchaseAmt*perPrice);
							var splitDateTime = value.created_at;
							var splitDateTime = splitDateTime.split("+");
							var getdateTime = splitDateTime[0];
							var newSplitTime = getdateTime.split("T");
							var getdateTime = getdateTime.replace("T"," ");
							var setColor = (value.extype=="buy") ? "blue " : "red";
							html = html + '<tr>';
							html = html + '<td class="left"><div class="bold">'+newSplitTime[0]+'</div>'+newSplitTime[1]+'</td>';
							//html = html + '<td>'+ucfirst(value.extype)+'</td>';
							html = html + '<td class="left"><div class="'+setColor+'">'+numberWithCommas(parseFloat(perPrice).toFixed(2))+'</div></td>';
							//html = html + '<td>'+sellPurchaseAmt+'</td>';
							html = html + '<td class="right">'+numberWithCommas(parseFloat(totalPrice).toFixed(2))+'</td>';
							html = html + '</tr>';
						});
					}
					
					$(".market_history").html(html);
				}
			});
		<?php }else { ?>
			$.ajax({
				url : 'https://api.binance.com/api/v3/trades?symbol=<?php echo $secondCoin ?>B<?php echo $firstCoin ?>',
				type : 'get',
				dataType : 'json',
				success : function(resp){
					resp = resp.reverse();
					// my buyOrderList data
					var html = '';
					if($.isEmptyObject(resp)){
						html = html + '<tr>';
						html = html + "<td colspan=5><?= __('Order not found')?></td>";
						html = html + '</tr>';
					}
					else {
						$.each(resp,function(key,value){
							var perPrice = value.price;
							var setColor = (value.isBuyerMaker==false) ? "blue " : "red";
							var totalPrice = value.quoteQty;
							var myDate = new Date(value.time);
							var showOnlyDate = myDate.getFullYear()  + "-" + (myDate.getMonth()+1) + "-" + myDate.getDate();
							var showOnlyTime = myDate.getHours() + ":" + myDate.getMinutes()+ ":" + myDate.getSeconds();
							//var explodeDateTime = setDateIt.split(", ");
						 	//
						/*	var splitDateTime = value.created_at;
							var splitDateTime = splitDateTime.split("+");
							var getdateTime = splitDateTime[0];
							var newSplitTime = getdateTime.split("T");
							var getdateTime = getdateTime.replace("T"," ");
							 */
							html = html + '<tr>';
							html = html + '<td class="left"><div class="bold">'+showOnlyDate+'</div>'+showOnlyTime+'</td>';
							html = html + '<td class="left"><div class="'+setColor+'">'+numberWithCommas(parseFloat(perPrice).toFixed(2))+'</div></td>';
							html = html + '<td class="right">'+numberWithCommas(parseFloat(totalPrice).toFixed(2))+'</td>';
							html = html + '</tr>';
						});
					}
					
					$(".market_history").html(html);
				}
			});

		<?php } ?>
	}
	
	// ajax for user balance
	function getCurrenPrice() {
		$.ajax({
			url : '<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'getCurrenPrice',$firstCoinId,$secondCoinId]); ?>',
			type : 'get',
			dataType : 'json',
			success : function(resp){
				if($.isEmptyObject(resp.current_price)){
				}
				else {
					var returnPrice = resp.current_price[0].get_per_price;
					returnPrice = parseFloat(returnPrice).toFixed(2);
					returnPriceInThousands = numberWithCommas(returnPrice); 
					
					var currentPriceInUsd = returnPrice*<?php echo $baseCoinPriceInUsd; ?>;
					currentPriceInUsd = parseFloat(currentPriceInUsd).toFixed(2);
					$("#current_price").html(returnPriceInThousands);
					pairCurrentPrice = returnPrice;
					var setHtml = (resp.goto=="down") ? "&#9660;" :"&#9650;";
					//$("#middle_current_price").html(returnPrice+setHtml);
					$("#middle_current_price").html('<tr onClick="fill_data_middle()"><td colspan="3"><div class="updown_rate"><span id="middle_only_price">'+returnPriceInThousands+'</span><span class="updown_arrow" style="font-size:16px; line-height: 1;">'+setHtml+'</span></div></td></tr>');
					
					$("#current_price_<?php echo $secondCoin."_".$firstCoin; ?>").html(returnPriceInThousands);
					$("#current_price_usd").html(currentPriceInUsd);
				}
				<?php if($binancePrice=="N") { ?>
				var newMyClass = (resp.change_in_one_day<0) ? "red" : "blue"; 
				var newSignPrcNew = (resp.change_in_one_day<0) ? "-" : "+"; 
				$("#change_in_one_day").html(newSignPrcNew+""+Math.abs(parseFloat(resp.change_in_one_day).toFixed(2))+"%").removeClass("blue").removeClass("red").addClass(newMyClass);
				
				// for curren volume
				if($.isEmptyObject(resp.current_volume)){
					$("#current_volume").html('');
				}
				else {
					var returnVolume = numberWithCommas(parseFloat(resp.current_volume).toFixed(2));
					$("#current_volume").html(returnVolume);
					
				}
				
				
				// for min price
				if($.isEmptyObject(resp.min_price)){
					$("#min_price").html('');
				}
				else {
					var minPrice = numberWithCommas(parseFloat(resp.min_price).toFixed(2));
					$("#min_price").html(minPrice);
					
				}
				
				
				// for max price
				if($.isEmptyObject(resp.max_price)){
					$("#max_price").html('');
				}
				else {
					var maxPrice = numberWithCommas(parseFloat(resp.max_price).toFixed(2));
					$("#max_price").html(maxPrice);
					
				}
				<?php } ?>
			}
		});
	}	
	
	function getLastTwentyFourHourTicker(){
		// ajax for get not completed order list of buy orders
		<?php if($binancePrice=="Y") { ?>
		$.ajax({
			url : 'https://api.binance.com/api/v3/ticker/24hr?symbol=<?php echo $secondCoin ?>B<?php echo $firstCoin ?>',
			type : 'get',
			dataType : 'json',
			success : function(resp){
				
				var highPrice = numberWithCommas(parseFloat(resp.highPrice).toFixed(2));
				var lowPrice = numberWithCommas(parseFloat(resp.lowPrice).toFixed(2));
				var getMyVolume = numberWithCommas(parseFloat(resp.quoteVolume).toFixed(2));
				var getMyPercent = parseFloat(resp.priceChangePercent).toFixed(2);
				
				var newClass = (getMyPercent<0) ? "red" : "blue"; 
				var newSignPrc = (getMyPercent<0) ? "-" : "+"; 
				$("#current_volume").html(getMyVolume);
				$("#max_price").html(highPrice);
				$("#min_price").html(lowPrice);
				$("#change_in_one_day").html(newSignPrc+""+Math.abs(getMyPercent)+"%").removeClass("red").removeClass("blue").addClass(newClass);
			}
		});	
		<?php } else { ?>
			getCurrenPrice();
		<?php } ?>
	}

function notCompletedOrderList(){
    // ajax for get not completed order list of buy orders
    $.ajax({
        url : '<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'notCompletedOrderListAjax',$firstCoinId,$secondCoinId]); ?>',
        type : 'get',
        dataType : 'json',
        success : function(resp){
            var html = '';
            if($.isEmptyObject(resp.buyOrderList)){
                html = html + '<tr>';
                html = html + "<td colspan=3><?= __('Order not found')?></td>";
                html = html + '</tr>';
            }
            else {
                var buyOrderBalance = 0;
                $.each(resp.buyOrderList,function(key,value){
                    html = html + '<tr onClick="fill_data(this,\'sell\')" style="cursor:pointer;">';
                    html = html + '<td class="fill_per_price"><div class="bold red">'+numberWithCommas(parseFloat(value.per_price).toFixed(2))+'</div></td>';
                    html = html + '<td class="fill_amount">'+numberWithCommas(parseFloat(value.sum).toFixed(2))+'</td>';
                    html = html + '<td class="right">'+numberWithCommas(parseFloat(parseFloat(value.per_price).toFixed(2)*parseFloat(value.sum).toFixed(2)).toFixed(2))+'</td>';
                    html = html + '</tr>';
                    buyOrderBalance += parseFloat(value.per_price).toFixed(2)*parseFloat(value.sum).toFixed(2);

                });

                buyOrderBalance  = numberWithCommas(parseFloat(buyOrderBalance).toFixed(2));
                $("#buy_order_balance").html(buyOrderBalance + " KRW");
            }
            $("#buyAjaxData").html(html);

            // add data to sell table

            var html = '';
            if($.isEmptyObject(resp.sellOrderList)){
                html = html + '<tr>';
                html = html + "<td colspan=3><?= __('Order not found')?></td>";
                html = html + '</tr>';
            }
            else {
                var sellOrderBalance = 0;
                $.each(resp.sellOrderList,function(key,value){

                    html = html + '<tr onClick="fill_data(this,\'buy\')" style="cursor:pointer;">';
                    html = html + '<td class="fill_per_price"><div class="bold blue">'+numberWithCommas(parseFloat(value.per_price).toFixed(2))+'</div></td>';
                    html = html + '<td class="fill_amount">'+numberWithCommas(parseFloat(value.sum).toFixed(2))+'</td>';
                    html = html + '<td class="right">'+numberWithCommas(parseFloat(parseFloat(value.per_price).toFixed(2)*parseFloat(value.sum).toFixed(2)).toFixed(2))+'</td>';
                    html = html + '</tr>';
                    sellOrderBalance += parseFloat(value.per_price).toFixed(2)*parseFloat(value.sum).toFixed(2);
                });
               // sellOrderBalance = sellOrderBalance;

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
	
	function myOrderListAjax(){
		
		<?php if(!empty($authUserId)/*  && $googleAuthVerify=='Y' */) { ?>
		// ajax for myOrder list 
		$.ajax({
			url : '<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'myOrderListAjax',$firstCoinId,$secondCoinId]); ?>',
			type : 'get',
			dataType : 'json',
			success : function(resp){
				
				// my buyOrderList data
				var html = '';
				if($.isEmptyObject(resp.myBuyOrderList)){
					html = html + '<tr>';
					html = html + "<td colspan=6><?= __('There is no transaction history.')?></td>";
					html = html + '</tr>';
				}
				else {
					$.each(resp.myBuyOrderList,function(key,value){
						var action = '&nbsp;';
						var showAmount = numberWithCommas(parseFloat(value.total_buy_get_amount).toFixed(2));
						if(value.status =='pending'){
							action = "<a class='button sell sell_ntr' href='javascript:void(0)' id='buy_"+value.id+"' onClick='deleteOrder(this.id)'>"+'<?= __('Cancel')?>'+"</a>";
							showAmount = numberWithCommas(parseFloat(value.buy_get_amount).toFixed(2));
						}
						if(value.status == 'deleted'){

                        }
						var splitDateTime = value.created_at;
						var splitDateTime = splitDateTime.split("+");
						var getdateTime = splitDateTime[0];
						var getdateTime = getdateTime.replace("T"," ");
						var status = ucfirst(value.status);
						html = html + '<tr>';
						html = html + '<td>'+getdateTime+'</td>';
						html = html + '<td>'+numberWithCommas(parseFloat(value.per_price).toFixed(2))+'</td>';
						html = html + '<td>'+showAmount+'</td>';
						html = html + '<td>'+numberWithCommas(parseFloat((parseFloat(value.per_price).toFixed(2))*(parseFloat(value.buy_get_amount).toFixed(2))).toFixed(2))+'</td>';
						if(status == 'Completed') {
                            html = html + '<td>' + '<?= __('Completed')?>' + '</td>';
                        } else if (status == 'Pending'){
                            html = html + '<td>' + '<?= __('Pending')?>' + '</td>';
                        } else if (status == 'Deleted'){

                        }
						else {
							html = html + '<td>&nbsp;</td>'
						}
						html = html + '<td>'+action+'</td>';
						html = html + '</tr>';
					});
					
				}
				
				//$.fn.dataTable.ext.errMode = 'none';
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
										"url": "https://www.coinibt.io/datatable_language/<?php echo __('datatable_language') ?>.json"
									}
								});
				
				// my seller order list data
				var html = '';
				if($.isEmptyObject(resp.mySellOrderList)){
					html = html + '<tr>';
					html = html + "<td colspan=6><?= __('Transaction history is not available')?></td>";
					html = html + '</tr>';
				}
				else { 
					$.each(resp.mySellOrderList,function(key,value){
							
						var action = '&nbsp;';
						var showAmount = numberWithCommas(parseFloat(value.total_sell_get_amount).toFixed(2));
						if(value.status=='pending'){
							action = "<a class='button sell sell_ntr' style='background-color: #0c45d5;' href='javascript:void(0)' id='sell_"+value.id+"' onClick='deleteOrder(this.id)'>"+'<?= __('Cancel')?>'+"</a>";
							showAmount = numberWithCommas(parseFloat(value.sell_get_amount).toFixed(2));
						}
                        if(value.status == 'deleted'){

                        }
						var splitDateTime = value.created_at;
						var splitDateTime = splitDateTime.split("+");
						var getdateTime = splitDateTime[0];
						var getdateTime = getdateTime.replace("T"," ");
                        var status = ucfirst(value.status);
						html = html + '<tr>';
						html = html + '<td>'+getdateTime+'</td>';
						html = html + '<td>'+numberWithCommas(parseFloat(value.per_price).toFixed(2))+'</td>';
						html = html + '<td>'+numberWithCommas(parseFloat(parseFloat(value.sell_get_amount).toFixed(2)/parseFloat(value.per_price).toFixed(2)).toFixed(2))+'</td>';
						html = html + '<td>'+numberWithCommas(parseFloat(value.sell_get_amount).toFixed(2))+'</td>';
                        if(status == 'Completed') {
                            html = html + '<td>' + '<?= __('Completed')?>' + '</td>';
                        } else{
                            html = html + '<td>' + '<?= __('Pending')?>' + '</td>';
                        }

                        html = html + '<td>' + action + '</td>';

						html = html + '</tr>';
					});
				}
				var checkDisplay = $("#sale_li_div").css("display");
				if(checkDisplay!="none") {
					
					$("#mySellOrderlist_table").dataTable().fnDestroy();
					$("#mySellOrderlist").html(html);
					$('#mySellOrderlist_table').DataTable({
						bSort: false,
						pageLength: 15,
						scrollY:        "300px",
						scrollX:        false,
						scrollCollapse: true,
						paging:         false,
						fixedColumns:   {
							leftColumns: 1,
							rightColumns: 1
						},
						language: {
							"url": "https://www.coinibt.io/datatable_language/<?php echo __('datatable_language') ?>.json"
						}
					});
				}
				else {
					$("#mySellOrderlist").html(html);
				}
				
			}
		});
		<?php } ?>
	}
	
	function ucfirst(str){
		if (str != null){
			var str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
				return letter.toUpperCase();
			});
		}
		else {
			var str='';
		}
		return str;
	}
	
	function deleteOrder(getId){
		<?php if(!empty($authUserId)/*  && $googleAuthVerify=='Y' */) { ?>
		if(confirm("Are you really want to delete this ?")){
		//	$("#"+getId).remove();
		$("#"+getId).closest('tr').remove();
		var splitId = getId.split("_");
		var tableType = splitId[0]; 
		var tableId = splitId[1];
		
		$.ajax({
				url : "<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'deleteMyOrder']); ?>/"+tableId+"/"+tableType,
				type : 'post',
				dataType : 'json',
				success : function(resp){
				}
			});
		}
			<?php } ?>
	} 

	function fill_data(getTable,getTableType){
		var fillPerPrice = $(getTable).find("td.fill_per_price div").html();
		var fillAmount = $(getTable).find("td.fill_amount").html();
		fillPerPrice = fillPerPrice.replace(",","");
		fillAmount = fillAmount.replace(",","");
		if(getTableType === "sell"){
			
			//$("#sell_volume").val(fillAmount).trigger("input");
			$("#sell_per_price").val(fillPerPrice).trigger("input");
			$("#buy_per_price").val(fillPerPrice).trigger("input");

			$("#profile-tab").click();
		}
		if(getTableType === "buy"){
			//$("#buy_volume").val(fillAmount).trigger("input");
			$("#sell_per_price").val(fillPerPrice).trigger("input");
			$("#buy_per_price").val(fillPerPrice).trigger("input");
			$("#home-tab").click();
		}
	}

function fill_data_middle(){
    var fillPerPrice = $("#middle_only_price").html();
    fillPerPrice = fillPerPrice.replace(",","");
        //$("#sell_volume").val(fillAmount).trigger("input");
        $("#sell_per_price").val(fillPerPrice).trigger("input");
        $("#buy_per_price").val(fillPerPrice).trigger("input");
        $("#sell_volume").val(0);
    $("#buy_volume").val(0);

        $("#profile-tab").click();

}


function setAmount(getThis,getBalanceType){
		  var getPercent = $(getThis).attr('data-id');
		  
		  var getBalanceAmt = $("#span_"+getBalanceType+"_volume_all").val();
		  var calculateSetAmt = parseFloat(getBalanceAmt*getPercent/100).toFixed(2);
		  if(getBalanceType === "sell"){
			$("#"+getBalanceType+"_volume").val(calculateSetAmt);
		  }
		  else {
			  $("#buy_total_amount").val(calculateSetAmt).change();
		  }
	}	
	
	function getPairCurrentPrice(){
		
		$.ajax({
				url : "<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'getPairCurrentPrice',$firstCoinId]); ?>",
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
						//if(isBinancePrice=="N") {
							var setPriceClass = (getPricePercent<0) ? "red" : "blue"; 
							var setPriceSign = (getPricePercent<0) ? "-" : "+"; 
							$("#percent_"+getKey).html(setPriceSign+""+Math.abs(getPricePercent)+"%").removeClass("red").removeClass("blue").addClass(setPriceClass);
						//}
						if(getKey=="current_price_<?php echo $secondCoin; ?>_<?php echo $firstCoin; ?>"){
							
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
	function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
	}


	
	





</script>

<?php }



?>
<script>
$( "#radioclick" ).click(function() {
	var isChecked = $('#radioclick').is(':checked');
	if(isChecked==true){
		$(".hide_currency").hide()
	}else{
		$(".hide_currency").show()
	}
// console.log(isChecked);


});



</script>
<script>



    function increment(value) {
		var valuecheck='<?php echo $secondCoin ;?>';
		
		var value1=$("#buy_per_price").val();
		if(value1!=undefined && value1!=null && value1!=''){
			var new_value=parseFloat(value1)+parseFloat(value);
				if(valuecheck=="USDT"){
					$("#buy_per_price").val(parseFloat(new_value).toFixed(2)).trigger('input');
				}else{
					$("#buy_per_price").val(parseFloat(new_value).toFixed(2)).trigger('input');
				}
      		 
			
		}else{
			var new_value=value;
			
			if(valuecheck=="USDT"){
					$("#buy_per_price").val(parseFloat(new_value).toFixed(2)).trigger('input');
				}else{
					$("#buy_per_price").val(parseFloat(new_value).toFixed(2)).trigger('input');
				}
		}
		
    }
    function decrement(value) {
		var valuecheck='<?php echo $secondCoin ;?>';
		var value1=$("#buy_per_price").val();
		if(value1!=undefined && value1!=null && value1!=''){
			if(value1>0){
				var new_value=parseFloat(value1)-parseFloat(value);
				if(Math.sign(new_value)==-1){
					new_value=0;
				}
				
				if(valuecheck=="USDT"){
					$("#buy_per_price").val(parseFloat(new_value).toFixed(2)).trigger('input');
				}else{
					$("#buy_per_price").val(parseFloat(new_value).toFixed(2)).trigger('input');
				}
			}
			
		}else{
			var new_value=value
			
			if(valuecheck=="USDT"){
					$("#buy_per_price").val(parseFloat(new_value).toFixed(2)).trigger('input');
				}else{
					$("#buy_per_price").val(parseFloat(new_value).toFixed(2)).trigger('input');
				}
		}
    }
</script>

<script>
    function increment1(value) {
		
		var valuecheck='<?php echo $secondCoin ;?>';
		var value1=$("#sell_per_price").val();
		if(value1!=undefined && value1!=null && value1!=''){
			var new_value=parseFloat(value1)+parseFloat(value);
			if(valuecheck=="USDT"){
					$("#sell_per_price").val(parseFloat(new_value).toFixed(2)).trigger('input');
				}else{
					$("#sell_per_price").val(parseFloat(new_value).toFixed(2)).trigger('input');
				}
		}else{
			var new_value=value
			if(valuecheck=="USDT"){
					$("#sell_per_price").val(parseFloat(new_value).toFixed(2)).trigger('input');
				}else{
					$("#sell_per_price").val(parseFloat(new_value).toFixed(2)).trigger('input');
				}
		}
		
    }
    function decrement1(value) {
		var valuecheck='<?php echo $secondCoin ;?>';
		var value1=$("#sell_per_price").val();
		if(value1!=undefined && value1!=null && value1!=''){
			if(value1>0){
				var new_value=parseFloat(value1)-parseFloat(value)
				if(Math.sign(new_value)==-1){
					new_value=0;
				}
				if(valuecheck=="USDT"){
					$("#sell_per_price").val(parseFloat(new_value).toFixed(2)).trigger('input');
				}else{
					$("#sell_per_price").val(parseFloat(new_value).toFixed(2)).trigger('input');
				}
			}
			
		}else{
			var new_value=value
			if(valuecheck=="USDT"){
					$("#sell_per_price").val(parseFloat(new_value).toFixed(2)).trigger('input');
				}else{
					$("#sell_per_price").val(parseFloat(new_value).toFixed(2)).trigger('input');
				}
		}
	}
	



	function increment2(value) {
	
		var valuecheck='<?php echo $secondCoin ;?>';
		var value1=$("#buy_volume").val();
		if(value1!=undefined && value1!=null && value1!=''){
			var new_value=parseFloat(value1)+parseFloat(value)
			$("#buy_volume").val(parseFloat(new_value).toFixed(1)).trigger('input');
				
		}else{
			var new_value=value;
			$("#buy_volume").val(parseFloat(new_value).toFixed(1)).trigger('input');
				
		}
		
    }
    function decrement2(value) {
		var valuecheck='<?php echo $secondCoin ;?>';
		var value1=$("#buy_volume").val();
		if(value1!=undefined && value1!=null && value1!=''){
			if(value1>0){
				var new_value=parseFloat(value1)-parseFloat(value)
				if(Math.sign(new_value)==-1){
					new_value=0;
				}
				
					$("#buy_volume").val(parseFloat(new_value).toFixed(1)).trigger('input');
				
			}
			
		}else{
			var new_value=value
			$("#buy_volume").val(parseFloat(new_value).toFixed(1)).trigger('input');
				
		}
	}



	function increment3(value) {
		
		var valuecheck='<?php echo $secondCoin ;?>';
		var value1=$("#sell_volume").val();
		if(value1!=undefined && value1!=null && value1!=''){
			var new_value=parseFloat(value1)+parseFloat(value)
			$("#sell_volume").val(parseFloat(new_value).toFixed(1)).trigger('input');
				
		}else{
			var new_value=value;
			$("#sell_volume").val(parseFloat(new_value).toFixed(1)).trigger('input');
				
		}
		
    }
    function decrement3(value) {
		var valuecheck='<?php echo $secondCoin ;?>';
		var value1=$("#sell_volume").val();
		if(value1!=undefined && value1!=null && value1!=''){
			if(value1>0){
				var new_value=parseFloat(value1)-parseFloat(value)
				if(Math.sign(new_value)==-1){
					new_value=0;
				}
				
					$("#sell_volume").val(parseFloat(new_value).toFixed(1)).trigger('input');
				
			}
			
		}else{
			var new_value=value
			$("#sell_volume").val(parseFloat(new_value).toFixed(1)).trigger('input');
				
		}
	}


    function thousands_separators(num)
    {
        var num_parts = num.toString().split(".");
        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return num_parts.join(".");
    }

	
</script>