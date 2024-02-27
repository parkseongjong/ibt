<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/assets.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<style>
    .assets_box .tab_menu li {
        float: left;
        width: 25%;
        height: 60px;
        line-height: 60px;
        text-align: center;
        margin-top: 20px;
        border-top: solid 1px #b5b5b5;
        border-bottom: solid 1px #b5b5b5;
        border-right: solid 1px #b5b5b5;
        background: #ffffff;
    }
    .assets_box .tab_menu li:nth-child(1) {
        width: 24%;
        border-left: solid 1px #b5b5b5;
    }

    .assets_box .tab_menu li a {
        font-size: 15px;
        font-weight: bold;
        color: #000000;
    }
  
.containers3 {    max-width: 1170px;    margin: 30px auto;}

</style>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<div class="containers containers3" >
    <div class="">
        <?php echo $this->element('Front2/profile_menu'); ?>

        <div class="assets_box" style="width:auto;">
        <div class="left mycoinleft">

            <div class="my_assets">
                <ul class="total_assets">
                    <li class="title"><?=__('Total assets held') ?></li>
                    <li class="unit">KRW</li>
                    <li class="pricetotal1223 price">0</li>
                </ul>

                <input type="text" id="search_coin" name="search_coin" class="search_coin" placeholder="<?=__('Coin search') ?>" />

                <div class="options">
                    <label><input type="checkbox" name="" id="radioclick" value="" /><?=__('View only retained coins') ?></label>
                </div>
            </div>

            <div class="my_coins">
                <table>
                    <thead>
                    <tr>
                        <td><b><span><?=__('Coin name')?></span></b></td>
                        <td style="width:21%"><b><span><?=__('Retained quantity')?></span></b></td>
                        <td style="width:24%"><b><span>KRW</span></b></td>
                    </tr>
                    </thead>
                    <tbody id="mycoinlist">
                    <?php
                        $total_value="";
                        $principalBalanceTotal=""; 
						$reserveBalance ="";
                    if(!empty($mainRespArr)){
                        foreach($mainRespArr as $key=> $value){
                            if(!empty($value['principalBalance'])){
                            ?>
                            <tr class="on">
                                <td>

					<span class="setvalue"  data-id="coin_name_<?php echo $key;?>" style="cursor:pointer"><?php
                        echo $value['coinShortName'];
                        ?>

                        <?php
                        if(!empty($value['icon'])){
                            ?>
                            <img src="http://coinibt.io/uploads/cryptoicon/<?php echo $value['icon'];?> " width="40px" max-height="40px">
                            <?php
                        }
                        ?>
						</span>

                                </td>
                                <td><span><?php
                                        echo $value['principalBalance'];
                                        ?></span></td>
                                <td><span>
						<?php
                        $getMyCustomPrice = $this->CurrentPrice->getCurrentPrice($value['coinId'],20);
$getMyCustomPrice  = number_format($getMyCustomPrice,8);
                        $getMyCustomPrice = str_replace(',', '', $getMyCustomPrice);
                        ?>




                        <?php
                        $krw_value=$value['principalBalance'] * $getMyCustomPrice;
                        echo $value['principalBalance'] * $getMyCustomPrice;
                        $total_value+= $value['principalBalance'] * $getMyCustomPrice;
                        $principalBalanceTotal+=$value['principalBalance'];
							$reserveBalance+=$value['reserveBalance'];
                        ?></span></td>
                                <input type="hidden" value=<?php echo $value['principalBalance'];?> id="quantity_<?php echo $key ;?>" name="quantity_<?php echo $key ;?>"/>
                                <input type="hidden" value=<?php echo $krw_value;?> id="krw_<?php echo $key ;?>" name="krw_<?php echo $key ;?>"/>
                                <input type="hidden" value=<?php echo $value['coinShortName'];?> id="coin_name_<?php echo $key ;?>" name="coin_name_<?php echo $key ;?>"/>
                                <input type="hidden" value=<?php echo $getMyCustomPrice;?> id="coin_id_<?php echo  $value['coinShortName'] ;?>" name="coin_id_<?php echo  $value['coinShortName'] ;?>"/>

                                <input type="hidden" value=<?php echo $value['tradingBalance'];?> id="tradingBalance_<?php echo  $key ;?>" name="tradingBalance_<?php echo  $key ;?>"/>
                                <input type="hidden" value=<?php echo $value['coinAddress'];?> id="coinAddress_<?php echo $key ;?>" name="coinAddress_<?php echo $key ;?>"/>
                                <input type="hidden" value=<?php echo $value['reserveBalance'];?> id="reserveBalance_<?php echo $key ;?>" name="reserveBalance_<?php echo $key ;?>"/>

                            </tr>
                            <?php
                        }else{
                            ?>
<tr class="on hide_currency">
                                <td>

					<span class="setvalue"  data-id="coin_name_<?php echo $key;?>" style="cursor:pointer"><?php
                        echo $value['coinShortName'];
                        ?>

                        <?php
                        if(!empty($value['icon'])){
                            ?>
                            <img src="http://coinibt.io/uploads/cryptoicon/<?php echo $value['icon'];?> " width="40px" max-height="40px">
                            <?php
                        }
                        ?>
						</span>

                                </td>
                                <td><span><?php
                                        echo $value['principalBalance'];
                                        ?></span></td>
                                <td><span>
						<?php
                        $getMyCustomPrice = $this->CurrentPrice->getCurrentPrice($value['coinId'],20);
$getMyCustomPrice  = number_format($getMyCustomPrice,8);
                        $getMyCustomPrice = str_replace(',', '', $getMyCustomPrice);
                        ?>




                        <?php
                        $krw_value=$value['principalBalance'] * $getMyCustomPrice;
                        echo $value['principalBalance'] * $getMyCustomPrice;
                        $total_value+= $value['principalBalance'] * $getMyCustomPrice;
                        $principalBalanceTotal+=$value['principalBalance'];
							$reserveBalance+=$value['reserveBalance'];

                        ?></span></td>
                                <input type="hidden" value=<?php echo $value['principalBalance'];?> id="quantity_<?php echo $key ;?>" name="quantity_<?php echo $key ;?>"/>
                                <input type="hidden" value=<?php echo $krw_value;?> id="krw_<?php echo $key ;?>" name="krw_<?php echo $key ;?>"/>
                                <input type="hidden" value=<?php echo $value['coinShortName'];?> id="coin_name_<?php echo $key ;?>" name="coin_name_<?php echo $key ;?>"/>
                                <input type="hidden" value=<?php echo $getMyCustomPrice;?> id="coin_id_<?php echo  $value['coinShortName'] ;?>" name="coin_id_<?php echo  $value['coinShortName'] ;?>"/>

                                <input type="hidden" value=<?php echo $value['tradingBalance'];?> id="tradingBalance_<?php echo  $key ;?>" name="tradingBalance_<?php echo  $key ;?>"/>
                                <input type="hidden" value=<?php echo $value['coinAddress'];?> id="coinAddress_<?php echo $key ;?>" name="coinAddress_<?php echo $key ;?>"/>
                                <input type="hidden" value=<?php echo $value['reserveBalance'];?> id="reserveBalance_<?php echo $key ;?>" name="reserveBalance_<?php echo $key ;?>"/>

                            </tr>

                    <?php
                    
                }
                    
                    }
                    }

                    
                    ?>
                        <input type="hidden" class="totalkrw" value="<?php echo $total_value;?>"/>
						<input type="hidden" class="principalBalanceTotal" value="<?php echo $principalBalanceTotal;?>"/>
						<input type="hidden" class="reserveBalance" value="<?php echo $reserveBalance;?>"/>                    </tbody>
                </table>
            </div>



        </div>


        <div class="mycoinrigth">
            <div class="mycoinrigth_pp">





                <?php //echo $this->element('Front2/assets_menu'); ?>



                <div class="rbtc_box">
				<span class="reblocks" style="float:left">
								Coin <span style="font-weight:bold" id="selected_coin">Select</span> 
							</span>
                Main Balance <span style="font-weight:bold" class="main_Balance11">0</span>
								
								<span class="reblock">
								Trading Account <span style="font-weight:bold" class="TradingAccount">0</span> 
							</span>
                </div>

                <ul class="tab_menu">
                    <li class="deposit"  id="deposit_on" onClick="tabClick('deposit')"><a  href="javascript:void(0);<?php //echo $this->Url->build(['controller'=>'assets','action'=>'deposit']) ?>"><?=__('Deposit')?></a></li>
                    <li class="withdrawal" id="withdrawal_on" onClick="tabClick('withdrawal')"><a  href="javascript:void(0);<?php //echo $this->Url->build(['controller'=>'assets','action'=>'withdrawal']) ?>"><?=__('Withdrawal')?></a></li>
                    <li class="details" id="breakdown_on"  onClick="tabClick('breakdown')"><a href="javascript:void(0);<?php //echo $this->Url->build(['controller'=>'assets','action'=>'details']) ?>"><?=__('Breakdown')?></a></li>
                    <li class="address" id="withdrawal_addr_on" onClick="tabClick('withdrawal_addr')"><a href="javascript:void(0);<?php //echo $this->Url->build(['controller'=>'assets','action'=>'address']) ?>"><?=__('Withdrawal address management')?></a></li>
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
                    <p class="gda">
                        <?php echo __('Please Select Coin');  ?>
                    </p>

                </div>

                <div style="display:none;" class="common_tab qr_code_man"  id="deposit_tab_content" >

                    <span  id="qr_code_image"></span>
                    <ul class="copy_address">
                        <li style="float:left; width:100%; color:#000;">
                            <input type="text" name="" value="" readonly id="wallet_addr_input" class="text" />
                            <div class="address_li" onClick="copyToClipboard();" >
                                <?=__('Copy Address') ?>
                            </div>
                        </li>

                    </ul>
                    <div id="copy_msg" class="alert alert-success" style="display:none;"></div>
                </div>



                <div style="display:none;" class="common_tab"  id="withdrawal_tab_content" >
                    <div class="exting">
                        <label><input type="radio" name="withdrawal_type"  id="withdrawal_type_out" value="OUT" checked /> <?=__('External withdrawal')?> </label>
                        <label><input type="radio" name="withdrawal_type" id="withdrawal_type_in" value="IN" /> <?=__('Internal withdrawal')?> </label>
                    </div>

                    <span id="table_withdrowl1">
					<div class="table_withdrowl">
						<table class="withdrawal">
							<tr>
								<td class="title">
									<?=__('Withdrawable amount')?>
								</td>
								<td colspan="3" class="right">
									<span class="amount" id="amount_data"></span><span class="unit" id="unit_data"></span>
								</td>
							</tr>
							<tr>
								<td class="title height-100">
									<?=__('Withdrawal request amount')?>
								</td>
								<td class="no-border right height-100">
									<input type="number" name="req_amount"  id="req_amount" class="req_amount" placeholder="<?=__('Enter withdrawal request amount')?>" /><span class="unit"></span>
								</td>
								<td class="title no-border height-100"><img src="/wb/imgs/equal2.png" /></td>
								<td class="right height-100" style="width: 230px">
									<span class="amount" id="amountkrw">0</span><span class="unit">KRW</span>
								</td>
							</tr>
							<tr>
								<td class="title">
									<?=__('Withdrawal fee')?>
								</td>
								<td colspan="3" class="right">
									<span class="amount">0</span><span class="unit unit_data"></span>
								</td>
							</tr>
							<tr>
								<td class="title blue">
									<?=__('Total withdrawal digital assets')?>
								</td>
								<td colspan="3" class="right gray_back">
									<span class="amount blue" id="totalValuekrw">0</span><span class="unit unit_data"></span>
								</td>
							</tr>
						</table>
					</div>
                    <select name="wallet_address"  class="form-control" id="wallet_address" style="margin-top: 15px; height: 48px;">
                    <option value="">Please Select</option>

                    </select>
					<!-- <input type="text" id="wallet_address" name="wallet_address" value="" class="wallet_address" placeholder="<?=__('No registered wallet address.') ?> <?=__('Please register your wallet address.') ?>" /> -->
					<div class="otp_number">
						<span><?=__('Enter OTP Number') ?></span><input type="text" id="otp_number" name="otp_number" disabled="disabled" value="" placeholder="<?=__('Please enter the OTP number.') ?>" />
						<input type="button" id="button_value" name="button_value" value="Get OTP"/>

					</div>
					<br/>
					<div id="otp_success" class="alert alert-success" style="    display: none;">
					</div>
					<br/>
					<div >
						<button name=""  id="withdrawrequestdata"class="middle"><?=__('Withdrawal request') ?></button>
					</div>
				</span>
                    <span id="table_withdrowl2">
					<table class="table table-striped">
								<thead>
								<tr>
									<th style="color:#000">Coin Name</th>
									<th style="color:#000">Main Account</th>
									<th style="color:#000">Transfer</th>
									<th style="color:#000">Trading Account</th>
									<th style="color:#000">Reserved</th>
								</tr>
								</thead><thead>
								</thead><tbody>
								<tr><td>
								<span id="unitdatatable_withdrowl2"></span>
								<strong></strong> </td>
								<td><span id="amount_datatable_withdrowl2"></span></td>
								<td>
								<span id="transfer_amount"></span>
								</td><td>
								<span id="tradingBalancetable_withdrowl2"></span></td><td>
								<span id="radingBalanceReserved"></span>
								</td></tr></tbody>
							</table>



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

                <div style="display:none;" class="common_tab common_tab22 asset_list"  id="breakdown_tab_content" >
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
                <form   class="rwwd_modal_form1"  onSubmit="return false;">
                    <div style="display:none;" class="common_tab"  id="withdrawal_addr_tab_content" >
                        <div class="asset_list common_tab22">
                            <table>
                                <thead>
                                <tr>
                                    <td></td>
                                    <td><?=__('Wallet Name')?></td>
                                    <td><?=__('Wallet Address')?></td>
                                    <td><?=__('Date and time of registration')?></td>
                                </tr>
                                </thead>
                                <tbody id="withdrawal_addr">



                                </tbody>

                            </table>
                        </div>

                        <table style="width:100%">
                            <tr>
                                <td style="text-align:left">
                                    <button type="submit"  name="" id="deleteAddress" class="white"><?=__('Delete')?></button>
                                </td>
                                <td style="text-align:right">
                                    <a name="" onclick="openAddWithdrawalWalletAddrModel()" >+ <?=__('Register withdrawal address')?></a>
                                </td>
                            </tr>
                        </table>


                        <div class="desc dest_mt70" style="margin-top:70px">
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
                                <button><?=__('Registration')?></button>
                            </div>
                        </div>
                    </div>
                </form>


                </td>
                </tr>
                </table>

            </div>
        </div>
        <div class="cls"></div>
    </div>
    </div>
</div>
<div id="myModalDeposit" class="modal fade" role="dialog" >
    <div class="modal-dialog" style='color:#000;' >

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span id="modal_coin_name"></span> Transfer to <span id="wallet_name"> </span> account</h4>
            </div>
            <div class="modal-body" style="text-align:center;">
                <form action="#" autocomplete="off" id="deposit_modal_form" enctype="multipart/form-data">
                    <input type="hidden" class="form-control" id="coin_id" name="coin_id">
                    <input type="hidden" class="form-control" id="transfer_to" name="transfer_to">


                    <div class="form-group">
                        <label for="email">Amount:</label>
                        <input type="text" class="form-control" required placeholder="Enter Amount" name="amount">
                    </div>


                    <button type="submit" class="btn btn-default" id="btnSubmitNew">Submit</button>
                    <img id="model_qr_code_flat" style="display:none;" src="/ajax-loader.gif" />
                    <div id="get_resp" style="display:none;"></div>

                </form>
            </div>

        </div>

    </div>
</div>
<input  type="hidden" id="selected_coind_id" />




<!-- Modal -->
<div id="myModalAddWithdrawalWalletAddr" class="modal fade" role="dialog" >
    <div class="modal-dialog" style='color:#000;' >
        <?php echo $this->Form->create('',['url'=>['controller'=>'Assets','action'=>'registerWithdrawalWalletAddrAjax']]);?>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Register Withdrawal Wallet Address</h4>
            </div>
            <div class="modal-body" style="text-align:center;">
                <!-- <form action="#" autocomplete="off" id="rwwd_modal_form"  onSubmit="return false;" enctype="multipart/form-data"> -->
                <input type="hidden" class="form-control" id="rwwd_coin_id" name="coin_id" >



                <div class="form-group">
                    <label for="email">Wallet Name:</label>
                    <input type="text" class="form-control" style="background-color:#fff!important;;" required placeholder="Wallet Name" id="rwwd_wallet_name" name="rwwd_wallet_name">
                </div>

                <div class="form-group">
                    <label for="email">Wallet Address:</label>
                    <input type="text" class="form-control" style="background-color:#fff!important;" required placeholder="Enter Address"  id="rwwd_wallet_addr" name="rwwd_wallet_addr">
                </div>


                <button type="button" class="btn btn-default" id="btnSubmit">Submit</button>
                <img id="rwwd_model_ajax" style="display:none;" src="/ajax-loader.gif" />
                <div id="rwwd_get_resp" class="alert" style="display:none;"></div>

                <!-- </form> -->
            </div>

        </div>
        <?php echo $this->Form->end();?>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script>
    $(document).ready(function(){
        var price=$(".totalkrw").val();
var principalBalanceTotal=$(".principalBalanceTotal").val();
var reserveBalance=$(".reserveBalance").val();
$(".main_Balance11").html(principalBalanceTotal);
$(".TradingAccount").html(reserveBalance);
$(".pricetotal1223").html(price);
	





       
       
        $("#table_withdrowl2").hide();
        coinList();
        $("#rwwd_modal_form").submit(function(){
            $("#rwwd_model_ajax").show();
            $.ajax({
                url:'<?php echo $this->Url->build(["controller"=>"Assets","action"=>"registerWithdrawalWalletAddrAjax"]) ?>',
                type:'POST',
                data:$(this).serialize(),
                dataType:'JSON',
                success:function(resp){
                    if(resp.success=="false"){
                        $("#rwwd_get_resp").removeClass("alert-success alert-danger").addClass("alert-danger").html(resp.message).show();

                    }
                    else if(resp.success=="true"){
                        $("#rwwd_get_resp").removeClass("alert-success alert-danger").addClass("alert-success").html(resp.message).show();
                        $("#rwwd_modal_form").trigger("reset");
                    }
                    setTimeout(function(){
                        $("#rwwd_get_resp").html("").hide();
                    },6000);
                    $("#rwwd_model_ajax").hide();

                }
            });
        })
    })


    function openAddWithdrawalWalletAddrModel(){
        var coin_id = $("#selected_coind_id").val();
        $("#rwwd_coin_id").val(coin_id);
        $("#myModalAddWithdrawalWalletAddr").modal('show');
    }
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
                $("#deposit_on").addClass("deposit on");
                $("#withdrawal_on").removeClass("on");
                $("#breakdown_on").removeClass("on");
                $("#withdrawal_addr_on").removeClass("on");
        }
        else if(tab_name=="withdrawal"){
            $("#withdrawal_tab_content").show();
            $("#table_withdrowl1").show();
            $("#table_withdrowl2").hide();
            $("#deposit_on").removeClass("on");
            $("#withdrawal_on").addClass("deposit on");
            $("#breakdown_on").removeClass("on");
            $("#withdrawal_addr_on").removeClass("on");

        }else if(tab_name=="breakdown"){
            $("#breakdown_tab_content").show()

            $("#deposit_on").removeClass("on");
            $("#withdrawal_on").removeClass("on");
            $("#breakdown_on").addClass("deposit on");
            $("#withdrawal_addr_on").removeClass("on");
        }
        else if(tab_name=="withdrawal_addr"){
            $("#withdrawal_addr_tab_content").show()

            $("#deposit_on").removeClass("on");
            $("#withdrawal_on").removeClass("on");
            $("#breakdown_on").removeClass("on");
            $("#withdrawal_addr_on").addClass("deposit on");
        }
    }
    function createWallet() {
        document.location.href = "<?php echo $this->Url->build(['controller'=>'assets','action'=>'deposit2']) ?>";
    }

    function copyToClipboard(){

        if($("#wallet_addr_input").val() !== '') {
            $("#wallet_addr_input").select();
            document.execCommand("copy");
            $("#copy_msg").html("Wallet Address Copied").show();
            setTimeout(function () {
                $("#copy_msg").html("").hide();
            }, 5000);
        }
    }


    function coinList(){

    }

</script>

<script>
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    $( ".setvalue" ).click(function() {
        var id = $(this).attr("data-id");

        let new_value=id.split("_");
        $("#withdrawal_type_out").prop("checked", true);
        $("#withdrawal_type_in").prop('checked', false);
        $("#otp_number").val('');
	    $("#otp_number").prop('disabled', true);
        if(new_value!=undefined && new_value!=null && new_value!=''){
            var quantityValue = $("#quantity_"+new_value[2]).val();
            var krwValue = $("#krw_"+new_value[2]).val();
            var coinNameValue = $("#coin_name_"+new_value[2]).val();
            var tradingBalance = $("#tradingBalance_"+new_value[2]).val();
            var coinAddress = $("#coinAddress_"+new_value[2]).val();
            var reserveBalance = $("#reserveBalance_"+new_value[2]).val();

            $("#qr_code_image").html('<img src="https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl='+coinAddress+'" style="width:170px; height:170px;" />')
            $("#wallet_addr_input").val(coinAddress);

            $("#amount_data").html(quantityValue);
            $("#unit_data").html(coinNameValue);
            $(".unit_data").html(coinNameValue);
            localStorage.setItem("amount_data", quantityValue);
            localStorage.setItem("unit_data", coinNameValue);
            localStorage.setItem("tradingBalance", tradingBalance);
            localStorage.setItem("reserveBalance", reserveBalance);


            $( "#deposit_on" ).trigger( "click" );
            $( "#deposit_on" ).addClass("deposit on");


            $("#amountkrw").html(0);
            $("#req_amount").val(0);
            $("#totalValuekrw").html(0);
			$("#selected_coin").html($(this).text());
        }
        $.ajax({
            url:'<?php echo $this->Url->build(["controller"=>"Assets","action"=>"displayWalletAddress"]) ?>',
            type:'post',
            data:{coinName:coinNameValue},
            success:function(resp){

                var resp =JSON.parse(resp);
                if(resp.success=="true"){
                    var getHtml='';
                    var getHtml1 ='<option value=""> Please Select Address </option>';
                    $.each(resp.data,function(key,value){
                        console.log(value);
                        getHtml = getHtml+'<tr id=td_data_'+value.id+'>';
                        getHtml = getHtml+'	<td><input type="checkbox"type="checkbox" name='+value.id+' data-id='+value.id+'></td>';
                        getHtml = getHtml+'	<td><span>'+value.wallet_name+'</span></td>';
                        getHtml = getHtml+'	<td><span>'+value.wallet_address+'</span></td>';
                        getHtml = getHtml+'	<td><span>'+value.modified+'</span></td>';
                        getHtml = getHtml+'</tr>';
                        getHtml1 = getHtml1+'<option value='+value.wallet_address+'> '+value.wallet_address+' </option>';
                    });

                }else{
                    getHtml = getHtml+'<tr>';
                    getHtml = getHtml+'	<td colspan="4">No data available</td>';
                    getHtml = getHtml+'</tr>';
                }

                $("#withdrawal_addr").html(getHtml);
                $("#wallet_address").html(getHtml1);
            }

        })


    })

    function display_data(id,short_name,principalBalance,krw_value,short_name,getMyCustomPrice,reserveBalance,coinAddress,){

        console.log(short_name);
        // let new_value=id.split("_");
        $("#withdrawal_type_out").prop("checked", true);
        $("#withdrawal_type_in").prop('checked', false);

        var quantityValue = principalBalance;
        var krwValue = krw_value;
        var coinNameValue = short_name;
        var tradingBalance =getMyCustomPrice;
        var coinAddress = coinAddress;
        var reserveBalance = reserveBalance;

        $("#qr_code_image").html('<img src="https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl='+coinAddress+'" style="width:170px; height:170px;" />')
        $("#wallet_addr_input").val(coinAddress);

        $("#amount_data").html(quantityValue);
        $("#unit_data").html(coinNameValue);
        $(".unit_data").html(coinNameValue);
        localStorage.setItem("amount_data", quantityValue);
        localStorage.setItem("unit_data", coinNameValue);
        localStorage.setItem("tradingBalance", tradingBalance);
        localStorage.setItem("reserveBalance", reserveBalance);


        $( "#deposit_on" ).trigger( "click" );
        $( "#deposit_on" ).addClass("deposit on");


        $("#amountkrw").html(0);
        $("#req_amount").val(0);
        $("#totalValuekrw").html(0);

        $.ajax({
            url:'<?php echo $this->Url->build(["controller"=>"Assets","action"=>"displayWalletAddress"]) ?>',
            type:'post',
            data:{coinName:coinNameValue},
            success:function(resp){

                var resp =JSON.parse(resp);
                if(resp.success=="true"){
                    var getHtml='';
                    var getHtml1 ='<option value=""> Please Select Address </option>';
                    $.each(resp.data,function(key,value){
                        console.log(value);
                        getHtml = getHtml+'<tr>';
                        getHtml = getHtml+'	<td><input type="checkbox"type="checkbox" name='+value.id+' data-id='+value.id+'></td>';
                        getHtml = getHtml+'	<td><span>'+value.wallet_name+'</span></td>';
                        getHtml = getHtml+'	<td><span>'+value.wallet_address+'</span></td>';
                        getHtml = getHtml+'	<td><span>'+value.modified+'</span></td>';
                        getHtml = getHtml+'</tr>';
                    });

                }else{
                    getHtml = getHtml+'<tr>';
                    getHtml = getHtml+'	<td colspan="4">No data available</td>';
                    getHtml = getHtml+'</tr>';
                }

                $("#withdrawal_addr").html(getHtml);
                $("#wallet_address").html(getHtml1);
            }

        })


    }


    $( "#withdrawal_type_in" ).click(function() {
        var tradingBalance=localStorage.getItem("tradingBalance");
        var unit_data=localStorage.getItem("unit_data");
        var amount_data=localStorage.getItem("amount_data");
        var reserveBalance=localStorage.getItem("reserveBalance");


        localStorage.setItem("tradingBalanceid", 1);
        localStorage.setItem("amount_dataid", 0);
        $("#unitdatatable_withdrowl2").html(unit_data);
        $("#amount_datatable_withdrowl2").html(amount_data);
        $("#tradingBalancetable_withdrowl2").html(tradingBalance);
        $("#radingBalanceReserved").html(reserveBalance);
        var rightArrowClick = "transferAmount('"+unit_data+"','trading')";
        var leftArrowClick = "transferAmount('"+unit_data+"','main')";

        var html = html + '<td><span style="cursor:pointer;" class="fa fa-arrow-right" onClick="'+rightArrowClick+'"></span><br/><span style="cursor:pointer;" class="fa fa-arrow-left"  onClick="'+leftArrowClick+'"></span></td>';
        $("#transfer_amount").html(html);

        $("#table_withdrowl1").hide();
        $("#table_withdrowl2").show();

    });

    $( "#withdrawal_type_out" ).click(function() {
        var amount_data=localStorage.getItem("amount_data");
        var unit_data=localStorage.getItem("unit_data");
        localStorage.setItem("tradingBalanceid", 0);
        localStorage.setItem("amount_dataid", 1);
        $("#table_withdrowl1").show();
        $("#table_withdrowl2").hide();
        $("#amount_data").html(amount_data);
        $("#unit_data").html(unit_data);
    });



    $('#req_amount').keyup(function(){
        var req_amount=$("#req_amount").val();
        var krwamount=$('#amount_data').text();
        var unit_data=$('#unit_data').text();
        var krw_value=$('#coin_id_'+unit_data).val();

        if(parseFloat(req_amount)<=parseFloat(krwamount)){
            $("#totalValuekrw").html(req_amount);
        }else{
            toastr.error('Please Enter Valid Amount')

        }


        var new_value=parseFloat(req_amount)*parseFloat(krw_value);
        $("#amountkrw").html(new_value);




    });

    $( "#btnSubmit" ).click(function() {
        var rwwd_wallet_name=$("#rwwd_wallet_name").val();
        var rwwd_wallet_addr= $("#rwwd_wallet_addr").val();
        if(rwwd_wallet_name===undefined || rwwd_wallet_name===null || rwwd_wallet_name===''){
            toastr.error('Please Enter wallet name');
            return false;
        }
        if(rwwd_wallet_addr===undefined || rwwd_wallet_addr===null || rwwd_wallet_addr===''){
            toastr.error('Please enter wallet address');
            return false;
        }
        var coinName=$("#unit_data").text();
        if(coinName===undefined || coinName===null || coinName===''){
            toastr.error('Please enter Coin');
            return false;
        }
		if(coinName.toLowerCase()!="btc" && validateInputAddresses(rwwd_wallet_addr)==false){
			toastr.error('Please enter valid wallet address');
            return false;
		}
        $("#btnSubmit").prop("disabled",true)
        $.ajax({
            url:'<?php echo $this->Url->build(["controller"=>"Assets","action"=>"insertWalletAddress"]) ?>',
            type:'post',
            data:{rwwd_wallet_name:rwwd_wallet_name,rwwd_wallet_addr:rwwd_wallet_addr,coinName:coinName},
            success:function(resp){

                var result=JSON.parse(resp);
                if(result.success=="true"){
                    toastr.success("Sucess");
                    $.ajax({
                        url:'<?php echo $this->Url->build(["controller"=>"Assets","action"=>"displayWalletAddress"]) ?>',
                        type:'post',
                        data:{coinName:coinName},
                        success:function(resp){

                            var resp =JSON.parse(resp);
                            if(resp.success=="true"){
                                var getHtml='';
                                var getHtml1='<option value=""> Please Select Address </option>';
                                $.each(resp.data,function(key,value){
                                    console.log(value);
                                    getHtml = getHtml+'<tr id=td_data_'+value.id+'>';
                                    getHtml = getHtml+'	<td><input type="checkbox" name='+value.id+' data-id='+value.id+'></td>';
                                    getHtml = getHtml+'	<td><span>'+value.wallet_name+'</span></td>';
                                    getHtml = getHtml+'	<td><span>'+value.wallet_address+'</span></td>';
                                    getHtml = getHtml+'	<td><span>'+value.modified+'</span></td>';
                                    getHtml = getHtml+'</tr>';
                                });

                            }else{
                                getHtml = getHtml+'<tr>';
                                getHtml = getHtml+'	<td colspan="4">No data available</td>';
                                getHtml = getHtml+'</tr>';
                            }

                            $("#withdrawal_addr").html(getHtml);
                            $("#wallet_address").html(getHtml1);
                        }

                    })
                    $("#btnSubmit").prop("disabled",false);
                    $('#myModalAddWithdrawalWalletAddr').modal('hide');

                    return false;
                }else{
                    toastr.error(result.message);
                    $("#btnSubmit").prop("disabled",false);
                   // $('#myModalAddWithdrawalWalletAddr').modal('hide');
                    return false;

                }

                $("#btnSubmit").prop("disabled",false);


            }

        })

        return false;

    });
    $( document ).ready(function() {

        $( ".rwwd_modal_form1" ).submit(function() {
            var id=$(".rwwd_modal_form1").serialize();

            if(id==undefined || id==null || id==''){
                toastr.error('Please select address To delete');
            }else{

                $.confirm({
                    title: 'Confirm!',
                    content: 'You want to delete!',
                    buttons: {
                        confirm: function () {
                            $.ajax({
                                url:'<?php echo $this->Url->build(["controller"=>"Assets","action"=>"deleteWalletAddress"]) ?>',
                                type:'post',
                                data:{id:id},
                                success:function(resp){
                                    var resp =JSON.parse(resp);
                                    toastr.success('success');
                                    $.each(resp.data,function(key,value){
                                        $("#td_data_"+value).remove();
                                    })
                                }
                            })
                        },
                        cancel: function () {

                        },

                    }

                });

            }
        });
    });


    $("#button_value").click(function() {
        var unitdata=$("#unit_data").text();
        if(unitdata==undefined || unitdata==null || unitdata==''){
            toastr.error('Please select coin');
            return false;
        }
        $("#otp_number").prop("disabled",false);
        $("#button_value").prop("disabled",true);
        $.ajax({
            url:'<?php echo $this->Url->build(["controller"=>"Assets","action"=>"otpWalletAddress"]) ?>',
            type:'post',
            success:function(resp){
                var resp =JSON.parse(resp);
                //toastr.success('success');
				$("#otp_success").html(resp.message).show();
				setTimeout(function(){ $("#otp_success").hide() },6000)
                $("#button_value").prop("disabled",false);
            }
        })

    });


    $("#withdrawrequestdata").click(function() {
        var unitdata=$("#unit_data").text();
        var req_amount=$("#req_amount").val();
        var wallet_address=$("#wallet_address").val();
        var amountkrw=$("#amount_data").text();
        var otp_number=$("#otp_number").val();

        if(unitdata==undefined || unitdata==null || unitdata==''){
            toastr.error('Please select coin');
            return false;
        }
        if(req_amount==undefined || req_amount==null || req_amount==''){
            toastr.error('Please enter amount you want to withdraw');
            return false;
        }
        if(wallet_address==undefined || wallet_address==null || wallet_address==''){
            toastr.error('Please enter wallet address');
            return false;
        }
        if(req_amount > amountkrw ){
            toastr.error('Please enter valid withdraw amount');
            return false;
        }
        if(otp_number==undefined || otp_number==null || otp_number==''){
            toastr.error('Please enter otp');
            return false;
        }
        var tradingBalanceid=localStorage.getItem("tradingBalanceid");
        var amount_dataid=localStorage.getItem("amount_dataid");

        var value="external";
        if(tradingBalanceid!=undefined && tradingBalanceid!=null && tradingBalanceid!=''){
            if(tradingBalanceid==1){
                value="internal"
            }
        }
        if(amount_dataid!=undefined && amount_dataid!=null && amount_dataid!=''){
            if(amount_dataid==1){
                if(tradingBalanceid==1){
                    value="external"
                }
            }
        }
        $("#button_value").prop("disabled",true);
        $.ajax({
            url:'<?php echo $this->Url->build(["controller"=>"Assets","action"=>"rquestWithdrawWalletAddress"]) ?>',
            type:'post',
            data:{wallet_address:wallet_address,req_amount:req_amount,coinName:unitdata,otp_number:otp_number,value:value},
            success:function(resp){
                var resp =JSON.parse(resp);
                if(resp.success=="false"){
                    toastr.error(resp.message);
                    $("#button_value").prop("disabled",false);
                    return false;
                }else{
                    toastr.success('success');
                    $("#button_value").prop("disabled",false);

                }
                //toastr.success('success');

            }
        })

    });

    function transferAmount(coinName,transferTo){

        $("#modal_coin_name").html(coinName);
        $("#wallet_name").html(transferTo);
        $("#coin_id").val(coinName);
        $("#transfer_to").val(transferTo);
        $('#myModalDeposit').modal('show');
    }

    $("#deposit_modal_form").submit(function(event){

        //stop submit the form, we will post it manually.
        event.preventDefault();
        $("#btnSubmit").prop("disabled", true);
        $("#model_qr_code_flat").show();
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "<?php echo $this->Url->build(['controller'=>'Wallet','action'=>'transgetToAccountNew']); ?>",
            data: $("#deposit_modal_form").serialize(),
            dataType: 'JSON',
            success: function (resp) {
                $("#model_qr_code_flat").hide();
                if(resp.status=='true'){
                    var transfer_to=jQuery('input[name="transfer_to"]').val();
                    var amountData=jQuery('input[name="amount"]').val();

                    if(transfer_to=="main"){
                        var data1=$("#amount_datatable_withdrowl2").text();
                        data1=parseFloat(data1)+parseFloat(amountData);
                        $("#amount_datatable_withdrowl2").html(data1);
                        var data2=$("#tradingBalancetable_withdrowl2").text();
                        data2=data2.replace(',', '');
                        data2=parseFloat(data2)-parseFloat(amountData);
                        $("#tradingBalancetable_withdrowl2").html(data2);
                        $("#get_resp").html(resp.message).addClass('alert alert-success').show();
                        setTimeout(function(){ $("#get_resp").html("").removeClass('alert alert-success').hide(); },2500)
                    }else{
                        var data1=$("#amount_datatable_withdrowl2").text();
                        data1=parseFloat(data1)-parseFloat(amountData);
                        $("#amount_datatable_withdrowl2").html(data1);
                        var data2=$("#tradingBalancetable_withdrowl2").text();
                        data2=data2.replace(',', '');
                        data2=parseFloat(data2)+parseFloat(amountData);
                        $("#tradingBalancetable_withdrowl2").html(data2);
                        $("#get_resp").html(resp.message).addClass('alert alert-success').show();
                        setTimeout(function(){ $("#get_resp").html("").removeClass('alert alert-success').hide(); },2500)
                    }
                }
                else if(resp.status=='false'){
                    $("#get_resp").html(resp.message).addClass('alert alert-danger').show();
                    setTimeout(function(){ $("#get_resp").html("").removeClass('alert alert-danger').hide(); },2500)
                }
                $("#deposit_modal_form")[0].reset();
                $("#btnSubmit").prop("disabled", false);
                getCoinList();
            },
            error: function (e) {

                $("#model_qr_code_flat").hide();
                $("#btnSubmit").prop("disabled", false);

            }
        });
    });


    $('#search_coin').keyup(function(){
        var coin_name=$("#search_coin").val();
        $.ajax({
            url:'<?php echo $this->Url->build(["controller"=>"Assets","action"=>"getusercoinlistajax"]) ?>',
            type:'post',
            data:{coin_name:coin_name},
            dataType:'JSON',
            success:function(resp){
                var getHtml='';
                $("#mycoinlist").html('mycoinlist')
                $.each(resp,function(key,value){
                    console.log(value);
                    var str=value.krw_value;
                    var icon="";

                    if(value.icon!=undefined && value.icon!=null && value.icon!='' ){
                        var icon='<img src="http://coinibt.io/uploads/cryptoicon/'+value.icon+'" width="40px" max-height="40px"></td>';
                    }

                    var getMyCustomPrice= value.principalBalance * str
                    var firstLetter = value.short_name.charAt(0)
                    var callFuntion = "display_data('"+key+"','"+value.short_name+"','"+value.principalBalance+"','"+str+"','"+value.short_name+"','"+getMyCustomPrice+"','"+value.reserveBalance+"','"+value.coinAddress+"')";
                    if( value.principalBalance!=0){
                        getHtml = getHtml+'<tr class="setvalue" onclick='+callFuntion+' data-id=coin_name_'+key+'  style="cursor:pointer;">';getHtml = getHtml+'	<td><span  style="cursor:pointer">'+value.short_name+" "+icon+'</span>';
                    }else{
                        getHtml = getHtml+'<tr class="setvalue hide_currency" onclick='+callFuntion+' data-id=coin_name_'+key+'  style="cursor:pointer;">';getHtml = getHtml+'	<td><span  style="cursor:pointer">'+value.short_name+" "+icon+'</span>'; 
                    }
                    

                    getHtml = getHtml+'	<td><span>'+value.principalBalance+'</span></td>';
                    getHtml = getHtml+'	<td><span>'+getMyCustomPrice+'</span></td>';
                    getHtml = getHtml+'</tr>'




                });
                $("#mycoinlist").html(getHtml);
            }
        });
    })







</script>
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

function validateInputAddresses(address) {
        return (/^(0x){1}[0-9a-fA-F]{40}$/i.test(address));
}
</script>