<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/assets.css" />
<div class="container">

	<div class="assets_box">

		<table class="frame">
		<tr>
			<td class="left">

				<div class="my_assets">
			<ul class="total_assets">
				<li class="title"><?=__('Total assets held') ?></li>
				<li class="unit"><?=__('WON') ?></li>
				<li class="price">0</li>
			</ul> 

			<input type="text" name="search_coin" class="search_coin" placeholder="<?=__('Coin search') ?>" />

			<div class="options">
				<label><input type="checkbox" name="" class="" value="Y" /><?=__('View only retained coins') ?></label>
			</div>
		</div>

		<div class="my_coins">
			<table>
				<thead>
					<tr>
						<td><span><?=__('Coin name')?></span></td>
						<td style="width:21%"><span><?=__('Retained quantity')?></span></td>
						<td style="width:24%"><span>KRW</span></td>
					</tr>
				</thead>
				<tbody id="mycoinlist">
					<!--<tr class="on">
						<td><span class="coin_mark">B</span><span>Smart Bitcoun (RBTC)</span></td>
						<td><span>500</span></td>
						<td><span>17,300,000</span></td>
					</tr>
					-->
				</tbody>
			</table>
		</div>
		
		<script>
		$(document).ready(function(){
			coinList();
		})
		function coinList(){
			$.ajax({
				url:'<?php echo $this->Url->build(["controller"=>"Assets","action"=>"getusercoinlistajax"]) ?>',
				type:'get',
				dataType:'JSON',
				success:function(resp){
					var getHtml='';
					$.each(resp,function(key,value){
						var firstLetter = value.short_name.charAt(0)
						var callFuntion = "sideBarCoinClick('"+value.short_name+"')";
						getHtml = getHtml+'<tr onClick="'+callFuntion+'">';
						getHtml = getHtml+'	<td><span class="coin_mark coin_'+firstLetter+'">'+firstLetter+'</span><span>'+value.short_name+'</span></td>';
						getHtml = getHtml+'	<td><span>25</span></td>';
						getHtml = getHtml+'	<td><span>580,000</span></td>';
						getHtml = getHtml+'</tr>';
					});
					$("#mycoinlist").html(getHtml);
				}
			});
		}
		</script>
		

			</td>

			<td class="right">

				<?php //echo $this->element('Front2/assets_menu'); ?>
				
					<table style="width:100%">
						<tr>
							<td>
								<div class="page_title " id="coin_name">
									
								</div>
							</td>
							<td style="text-align:right; line-height: 4; font-weight:300">
								<?=__('Available Digital Assets') ?> <span style="font-weight:bold">0</span> RBTC
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?=__('Frozen Digital Assets') ?> <span style="font-weight:bold">0</span> RBTC
							</td>
						</tr>
					</table>

					<ul class="tab_menu">
						<li class="deposit on" onClick="tabClick('deposit')"><a  href="javascript:void(0);<?php //echo $this->Url->build(['controller'=>'assets','action'=>'deposit']) ?>"><?=__('Deposit')?></a></li>
						<li class="withdrawal" onClick="tabClick('withdrawal')"><a  href="javascript:void(0);<?php //echo $this->Url->build(['controller'=>'assets','action'=>'withdrawal']) ?>"><?=__('Withdrawal')?></a></li>
						<li class="details"  onClick="tabClick('breakdown')"><a href="javascript:void(0);<?php //echo $this->Url->build(['controller'=>'assets','action'=>'details']) ?>"><?=__('Breakdown')?></a></li>
						<li class="address" onClick="tabClick('withdrawal_addr')"><a href="javascript:void(0);<?php //echo $this->Url->build(['controller'=>'assets','action'=>'address']) ?>"><?=__('Withdrawal address management')?></a></li>
					</ul>

					<script>
				/* 	$(document).ready(function(){
					<?php //if (isset($kind)) { ?>
						$(".<?php //echo $kind ?>").addClass('on');
					<?php //} ?>
					}); */
					</script>

				

				<div style="text-align: center; margin-top:100px" class="common_tab" id="default_content">

					<!--<button class="big" onclick="createWallet()"><?=__('Generate Deposit Address')?></button>

					<p style="margin-top: 50px; font-weight: 300; font-size: 15px;">
						<?php //echo __('Generate Deposit Address Text')?>
					</p>-->
					<p style="margin-top: 50px; font-weight: 300; font-size: 15px;">
						<?php echo __('Please Select Coin');  ?>
					</p>
	
				</div>
				
				<div style="text-align: center; margin-top:100px;display:none;" class="common_tab"  id="deposit_tab_content" >

					<img src="" style="width:170px; height:170px;" id="qr_code_image" />

					<ul class="copy_address">
						<li style="float:left; width:400px;">
							<input type="text" name="" value="" readonly id="wallet_addr_input" class="text" />
						</li>
						<li style="float:right; width:152px; background:#240978;">
							<?=__('Copy Address') ?>
						</li>
					</ul>

				</div>
				
				
				<div style="display:none;" class="common_tab"  id="withdrawal_tab_content" >

					
					<div style="margin: 50px 0 30px 0;">
						<label><input type="radio" name="withdrawal_type" value="OUT" checked /> <?=__('External withdrawal')?> </label>
						<label><input type="radio" name="withdrawal_type" value="IN" /> <?=__('Internal withdrawal')?> </label>
					</div>

					<div>
						<table class="withdrawal">
							<tr>
								<td class="title">
									<?=__('Withdrawable amount')?>
								</td>
								<td colspan="3" class="right">
									<span class="amount">100,000,000</span><span class="unit">RBTC</span>
								</td>
							</tr>
							<tr>
								<td class="title height-100">
									<?=__('Withdrawal request amount')?>
								</td>
								<td class="no-border right height-100">
									<input type="text" name="" value="" class="req_amount" placeholder="<?=__('Enter withdrawal request amount')?>" /><span class="unit">RBTC</span>
								</td>
								<td class="title no-border height-100"><img src="/wb/imgs/equal2.png" /></td>
								<td class="right height-100" style="width: 230px">
									<span class="amount">225,000</span><span class="unit">KRW</span>
								</td>
							</tr>
							<tr>
								<td class="title">
									<?=__('Withdrawal fee')?>
								</td>
								<td colspan="3" class="right">
									<span class="amount">20</span><span class="unit">RBTC</span>
								</td>
							</tr>
							<tr>
								<td class="title blue">
									<?=__('Total withdrawal digital assets')?>
								</td>
								<td colspan="3" class="right gray_back">
									<span class="amount blue">180,000</span><span class="unit">RBTC</span>
								</td>
							</tr>
						</table>
					</div>

					<input type="text" name="wallet_address" value="" class="wallet_address" placeholder="<?=__('No registered wallet address.') ?> <?=__('Please register your wallet address.') ?>" />

					<div class="otp_number">
						<span><?=__('Enter OTP Number') ?></span><input type="text" name="otp_number" value="" placeholder="<?=__('Please enter the OTP number.') ?>" />
					</div>

					<div style="margin-top:30px; text-align: center;">
						<button name="" class="middle"><?=__('Withdrawal request') ?></button>
					</div>
					<div class="desc_title">
						<?=__('Notes withdrawal')?>
					</div>

					<div class="desc">
						<p>- <?=__('Notes withdrawal1')?></p>

						<p>- <?=__('Notes withdrawal2')?></p>

						<p>- <?=__('Notes withdrawal3')?></p>

						<p>- <?=__('Notes withdrawal4')?></p>

						<p>- <?=__('Notes withdrawal5')?></p>

						<p>- <?=__('Notes withdrawal6')?>
						  <?=__('Notes withdrawal7')?></p>

						<p class="red">- <?=__('Notifications for deposit requests6')?></p>
					</div>

				</div>
				
				<div style="text-align: center; margin-top:100px;display:none;" class="common_tab asset_list"  id="breakdown_tab_content" >
					<table>
							<thead>
								<tr>
									<td><?=__('Division')?></td>
									<td><?=__('Request amount')?>(RBTC)</td>
									<td><?=__('Fee')?>(RBTC)</td>
									<td><?=__('Amount')?>(RBTC)</td>
									<td><?=__('Date')?></td>
									<td><?=__('State')?></td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="6" class="blank">
										<?=__('No transaction details') ?>
									</td>
								</tr>
							</tbody>
						</table>
				</div>
				
				<div style="display:none;" class="common_tab"  id="withdrawal_addr_tab_content" >
					<div class="asset_list" style="margin-top:90px; margin-bottom:20px">
						<table>
							<thead>
								<tr>
									<td><input type="checkbox" class="check" name="" value="1" /></td>
									<td><?=__('Wallet Name')?></td>
									<td><?=__('Wallet Address')?></td>
									<td><?=__('Date and time of registration')?></td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="4" class="blank">
										<?=__('There is no registered wallet')?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>

					<table style="width:100%">
						<tr>
							<td style="text-align:left">
								<button name="" class="white"><?=__('Delete')?></button>
							</td>
							<td style="text-align:right">
								<button name="" onclick="addAddress()" >+ <?=__('Register withdrawal address')?></button>
							</td>
						</tr>
					</table>


					<div class="desc" style="margin-top:70px">
						<p>- <?=__('Notes Address Text1')?></p>

						<p>- <?=__('Notes Address Text2')?></p>
					</div>


					<div id="add_address">
						<div style='margin:40px 0 30px; color:#000000; font-size: 22px;'><?=__('Register withdrawal address')?></div>
						<table>
							<tr>
								<td class="title">
									<?=__('Name2')?>
								</td>
								<td>
									<input type="text" name="" value="" />
								</td>
							</tr>
							<tr>
								<td class="title">
									<?=__('Wallet Address')?>
								</td>
								<td>
									<input type="text" name="" value="" />
								</td>
							</tr>
							<tr>
								<td class="title">
									<?=__('Enter OTP Number')?>
								</td>
								<td>
									<input type="text" name="" value="" />
								</td>
							</tr>
						</table>
						<div>
							<button class='white' onclick='hideMsgWindow()'><?=__('Cancel') ?></button>
							<button onclick='hideMsgWindow()'><?=__('Registration')?></button>
						</div>
					</div>
				</div>
				
				

			</td>
		</tr>
		</table>

	</div>

</div>
<input  type="hidden" id="selected_coind_id" />
<script>
var coin="BTC";
function sideBarCoinClick(coin){
	$(".common_tab").hide();
	//alert(coin);
	$("#coin_name").html(coin);
	$("#default_content").hide();
	var btcAddr = '<?php echo $userDetail["btc_address"] ?>';
	var ethAddr = '<?php echo $userDetail["eth_address"] ?>';
	var walletAddr = (coin=="BTC") ? btcAddr : ethAddr;
	
	var qrCodeUrl = "https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl="+walletAddr;
	$("#qr_code_image").attr('src',qrCodeUrl);
	$("#wallet_addr_input").val(walletAddr);
	$("#selected_coind_id").val(coin);
	$("#deposit_tab_content").show();
	
	
}


function tabClick(tab_name){
	$(".common_tab").hide();
	var selectedCoinId = $("#selected_coind_id").val();
	 if(tab_name=="deposit"){
		$("#deposit_tab_content").show();
	}
	else if(tab_name=="withdrawal"){
		$("#withdrawal_tab_content").show();
	}else if(tab_name=="breakdown"){
		$("#breakdown_tab_content").show()
	}
	else if(tab_name=="withdrawal_addr"){
		$("#withdrawal_addr_tab_content").show()
	}
}
function createWallet() {
	document.location.href = "<?php echo $this->Url->build(['controller'=>'assets','action'=>'deposit2']) ?>";
}
</script>