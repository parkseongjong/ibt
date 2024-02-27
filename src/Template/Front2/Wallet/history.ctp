<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/wallet.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>

    .a_table_center{ width: 100%; float:none; max-height: 10%;}
    .a_table_center table{ max-height: 200px;   width: 100%;  text-align:center;     border-color: #d3ccea;
        border: 1px solid #d3ccea;}
    .a_table_center table thead{background: #6738ff; color:#fff;display: table; /* to take the same width as tr */
        width: 100%; /* - 17px because of the scrollbar width */}
    .a_table_center table tbody {
        display: block; /* to enable vertical scrolling */
        /* e.g. */
        max-height: 500px;

        overflow-y: scroll; /* keeps the scrollbar even if it doesn't need it; display purpose */
    }
    .a_table_center table th{  width: 11%;
        padding: 10px;
    }
    .a_table_center table td{
        padding: 10px;word-break: break-all;
    }
    .a_table_center table td:nth-child(1){    width: 150px;}
    .a_table_center table td:nth-child(2), .a_table_center table td:nth-child(3), .a_table_center table td:nth-child(4), .a_table_center table td:nth-child(5) {    width: 200px;}
    .a_table_center tr{
        display: table; /* display purpose; th's border */
        width: 100%;
        box-sizing: border-box
    }
    .a_table_center table tr:nth-of-type(odd) {
        background-color: rgb(211 204 234/0.12);
    }
    #ajax_coin_tr{height: 100px;
        overflow-y: scroll;
        width: 80%;
        vertical-align: center;
        display: inline-block;}

    #ajax_coin_tr tr td {
        padding: 5px 20px 2px 2px;
    }
    .in_td_block{ display:block; width:100px}

    /*.a_table_center table thead:after {
        content: "";
        background: #744afc;
        width: 23px;
        height: 91%;
        position: absolute;
        right: -18px;
        z-index: 9;
        top: 0px;
        border-top: 1.52px solid #d3ccea;
        border-bottom: 1.52px solid #878396;
    }*/
	
	@media only screen and (max-width: 1190px) {
.a_table_center table td:nth-child(2), .a_table_center table td:nth-child(3), .a_table_center table td:nth-child(4), .a_table_center table td:nth-child(5) {
    width: 150px;
}
}
    @media only screen and (max-width: 990px) {
        .a_table_center{    width: 950px; height:10%;}
        .a_table_center table td{width: 11%!important;}
        .tab_menu li {    width: 46.5%!important;}
        
#hist{margin-top: 10px;}
    }
</style>
<?php echo $this->Form->create('',['method'=>'post']);?>
<?php echo $this->Form->end();?>
<div class="container">
	<div class="wallet_box">
		<div class="page_title">
			<?=__('My Assets') ?>
		</div>
		<div class="asset_box">
			<table class="wallet_table">
				<tbody>
					<tr>
						<td rowspan="2" style="width:46%; padding-bottom:20px">
							<div class="total_asset">
								<?=__('Total KRW held:') ?>
							</div>
							<div class="total_amount">
                                <?= number_format((float)$totalKRWBalance,2) ?>
<!--                                <span class="unit">--><?//=__('KRW') ?><!--</span>-->
							</div>
						</td>
					</tr>
                    <tr>
                        <td style="padding-left: 10%; width: 200px;">
                            <div class="total_asset">
                                <?=__('Total Coins held: ') ?>
                            </div>
                            <!--     <div class="total_amount"> <?= number_format((float)$totalCoinsBalance,2) ?>
                                <span class="unit"><?=__('Coins') ?></span>
                              <span class="unit"><?//= $coinShortName?>
                            </div>-->
                        </td>
                        <td id="ajax_coin_tr" ><img src="<?php echo $this->request->webroot ?>ajax-loader.gif" /></td>
                    </tr>
				</tbody>
			</table>
		</div>
		<ul class="tab_menu">
			<li id="index"><a href="<?php echo $this->Url->build(['controller'=>'wallet','action'=>'index']) ?>"><?=__('Transaction Details') ?></a></li>
			<li id="history" class="on"><a href="<?php echo $this->Url->build(['controller'=>'wallet','action'=>'history']) ?>"><?=__('Deposit Details') ?></a></li>
		</ul>
		<div class="filter">
			<ul>
				<li>
					<select id="hours" class="select w140">
                        <option value="0"> <?=__('Select time')?> </option>
                        <option value="12"> 12<?=__(' hours')?> </option>
                        <option value="24"> 24<?=__(' hours')?> </option>
                        <option value="36"> 36<?=__(' hours')?> </option>
                        <option value="48"> 48<?=__(' hours')?> </option>
                        <option value="56"> 56<?=__(' hours')?> </option>
                        <option value="72"> 72<?=__(' hours')?> </option>
					</select>
				</li>
				<li>
					<select id="coins" class="select w190">
                        <option value="0"> <?=__('All coins') ?> </option>
                        <option value="17"> TP3 Token Pay (TP3) </option>
                        <option value="21"> CyberTronCoin (CTC) </option>
                        <option value="18"> Ethereum (ETH) </option>
                        <option value="1"> Bitcoin (BTC) </option>
                        <option value="2"> US Dollar (USDT) </option>
                        <option value="19"> Market Coin (MC) </option>
                        <option value="22"> Ripple (XRP) </option>
                        <option value="27"> Binance Coin (BNB) </option>
                        <option value="20"> Korean Won (KRW) </option>
					</select>

				</li>
                <li>
                    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 3px; border: 1px solid #ccc; width: 100%;">

                        <i class="fa fa-calendar"></i>&nbsp;&nbsp;
                        <span></span>

                    </div>
                </li>
				<li style="float: right">
					<span class="helpmsg"><?=__('Maximum 180 days') ?></span>
				</li>
			</ul>

		</div>

		<div class="asset_list">
		<div class="assets_box_wallet">

            <div class="a_table_center">
                <table class="list" border="1">
                    <thead style="position: sticky">
                    <tr>
                        <td style="color: #f0f0f0"><?=__('Time') ?></td>
                        <td style="color: #f0f0f0"><?=__('Coin') ?></td>
                        <td style="color: #f0f0f0"><?=__('Transaction') ?></td>
                        <td style="color: #f0f0f0"><?=__('Wallet Address') ?></td>
                        <td style="color: #f0f0f0"><?=__('Tamount') ?></td>
                        <td style="color: #f0f0f0"><?=__('Fee') ?></td>
                    </tr>
				</thead>
				<tbody id="withDrawHistoryList">
					<tr>
						<td colspan="6" class="blank">
<!--							--><?//=__('No transaction details') ?>
                            <img src="<?php echo $this->request->webroot ?>ajax-loader.gif" />
						</td>
					</tr>
				</tbody>
			</table>
            </div>
		</div>
		</div>

	</div>

</div>

<script>
    $(document).ready(function(){
        withDrawHistory();
        getCoinList();
        $('#hours').change(function () {
            var hours = $('option:selected', this).val(); //to get selected text
            selectedVal(hours,0);
        });

        $('#coins').change(function () {
            var coin = $('option:selected', this).val(); //to get selected text
            selectedVal(coin,1);
        });
    });

    function getCoinName(coinId){
        var coinName;
        switch (coinId) {
            case 1:
                coinName = 'Bitcoin (BTC)';
                break;
            case 2:
                coinName = 'US Dollar (USDT)';
                break;
            case 17:
                coinName = 'TP3 Token Pay (TP3)';
                break;
            case 18:
                coinName = 'Ethereum (ETH)';
                break;
            case 19:
                coinName = 'Market Coin (MC)';
                break;
            case 20:
                coinName = 'Korean Won (KRW)';
                break;
            case 21:
                coinName = 'CyberTronCoin (CTC)';
                break;
            case 22:
                coinName = 'Ripple (XRP)';
                break;
            case 27:
                coinName = 'Binance Coin (BNB)';
                break;
            default:
                coinName = 'No coin Selected';
        }
        return coinName;
    }

    function getCoinList(){
        $("#ajax_coin_tr").show();
        $.ajax({
            url: "<?php echo $this->Url->build(['controller'=>'Wallet','action'=>'mywalletajax']); ?>",
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
            dataType: 'JSON',
            success: function(resp) {
                var html = '';
                $.each(resp.data.coinlist,function(resKey,respVal) {
                    html = html + '<tr>';
                    if ((respVal.principalBalance === "0.000000" && respVal.tradingBalance === "0.000000") || (respVal.principalBalance === "0.0000" && respVal.tradingBalance === "0.0000") || (respVal.principalBalance === "0.00" && respVal.tradingBalance === "0.00")) {

                    } else {
                        html = html + '<td><strong>' + respVal.coinShortName + '</strong></td> <td class="in_td_block">' + 'T: ' + respVal.tradingBalance + '</td>' +
                            '<td class="in_td_block"> M: ' + respVal.principalBalance + '</td>';
                    }
                    html = html + '</tr>';
                });
                // $("#ajax_coin_tr").hide();
                $('#ajax_coin_tr').html(html);
            },
            error: function (e) {
                $("#ajax_coin_tr").hide();
            }
        });
    }

    function withDrawHistory() {
        $.ajax({
            //url : '/front2/wallet/transferHistory',
            url : '<?php echo $this->Url->Build(['controller'=>'wallet','action'=>'withDrawHistory']) ?>',
            type : 'get',
            dataType : 'json',
            success : function(resp){
                // my depositOrderList data
                var html = '';
                if($.isEmptyObject(resp)){
                    html = html + '<tr>';
                    html = html + "<td colspan=7><?= __('There is no transaction history.')?></td>";
                    html = html + '</tr>';
                }
                else {
                    $.each(resp,function(key,value){
                        var coin = (getCoinName(value.cryptocoin_id));
                        var transAmount = (value.amount);
                        if(transAmount < 0){
                            transAmount = transAmount * -1;
                        }
                        var transType = value.type;
                        var walletAddress = value.wallet_address;
                        var fees = value.fees;
                        var splitDateTime = value.created_at;
                        var splitDateTimes = splitDateTime.split("+");
                        var getdateTime = splitDateTimes[0];
                        var newSplitTime = getdateTime.split("T");
                        // var getdateTime = getdateTime.replace("T"," ");
                        // var setColor = (value.extype=="buy") ? "blue " : "red";
                        html = html + '<tr>';
                        html = html + '<td class="left"><div class="bold">'+newSplitTime[0]+'</div>'+newSplitTime[1]+'</td>';
                        html = html + '<td class="right">'+coin+'</td>';
                        //html = html + '<td>'+ucfirst(value.extype)+'</td>';
                        html = html + '<td>'+transType+'</td>';
                        html = html + '<td>'+walletAddress+'</td>';
                        html = html + '<td class="left">'+numberWithCommas(parseFloat(transAmount).toFixed(2))+'</td>';
                        html = html + '<td>'+fees+'</td>';

                        html = html + '</tr>';
                    });
                }
                $("#withDrawHistoryList").html(html);
            }
        });
    }

    function selectedVal(slct, type){
        var urlSelected;
        var method;
        if(slct === '0'){
            urlSelected = '<?= $this->Url->Build(['controller'=>'wallet','action'=>'withDrawHistory']) ?>';
            method = 'get';

        } else {
            if(type === 0){
                urlSelected = '<?= $this->Url->build(['controller' => 'wallet', 'action' => 'withDrawHistoryHours']); ?>/' + slct;
                method = 'post';
            }
            else {
                urlSelected = '<?= $this->Url->build(['controller' => 'wallet', 'action' => 'withDrawHistoryCoin']); ?>/' + slct;
                method = 'post';
            }
        }
        $.ajax({
            url : urlSelected,
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
            type : method,
            dataType : 'json',
            success : function(resp){
                var html = '';
                if($.isEmptyObject(resp)){
                    html = html + '<tr>';
                    html = html + "<td colspan=7><?= __('There is no transaction history.') ?></td>";
                    html = html + '</tr>';
                }
                else {
                    $.each(resp,function(key,value){
                        var coin = (getCoinName(value.cryptocoin_id));
                        var transAmount = (value.amount);
                        if(transAmount < 0){
                            transAmount = transAmount * -1;
                        }
                        var transType = (value.type).toString().replace(/[_]/g, " ");
                        var walletAddress = value.wallet_address;
                        var fees = value.fees;
                        var splitDateTime = value.created_at;
                        var splitDateTimes = splitDateTime.split("+");
                        var getdateTime = splitDateTimes[0];
                        var newSplitTime = getdateTime.split("T");
                        // var getdateTime = getdateTime.replace("T"," ");
                        // var setColor = (value.extype=="buy") ? "blue " : "red";
                        html = html + '<tr>';
                        html = html + '<td class="left"><div class="bold">'+newSplitTime[0]+'</div>'+newSplitTime[1]+'</td>';
                        html = html + '<td class="right">'+coin+'</td>';
                        //html = html + '<td>'+ucfirst(value.extype)+'</td>';
                        html = html + '<td>'+transType+'</td>';
                        html = html + '<td>'+walletAddress+'</td>';
                        html = html + '<td class="left">'+numberWithCommas(parseFloat(transAmount).toFixed(2))+'</td>';
                        html = html + '<td>'+fees+'</td>';

                        html = html + '</tr>';
                    });
                }
                $("#withDrawHistoryList").html(html);
            }
        });
    }

    function showSelected(start,end) {
        $.ajax({
            url : "<?= $this->Url->Build(['controller'=>'wallet','action'=>'withDrawHistoryCalendar']) ?>/" + start +"/"+ end,
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
            type : 'post',
            dataType : 'json',
            success : function(resp){
                // my depositOrderList data
                var html = '';
                if($.isEmptyObject(resp)){
                    html = html + '<tr>';
                    html = html + "<td colspan=7><?= __('There is no transaction history.')?></td>";
                    html = html + '</tr>';
                }
                else {
                    $.each(resp,function(key,value){
                        var coin = (getCoinName(value.cryptocoin_id));
                        var transAmount = (value.amount);
                        if(transAmount < 0){
                            transAmount = transAmount * -1;
                        }
                        var transType = (value.type).toString().replace(/[_]/g, " ");
                        var walletAddress = value.wallet_address;
                        var fees = value.fees;
                        var splitDateTime = value.created_at;
                        var splitDateTimes = splitDateTime.split("+");
                        var getdateTime = splitDateTimes[0];
                        var newSplitTime = getdateTime.split("T");
                        // var getdateTime = getdateTime.replace("T"," ");
                        // var setColor = (value.extype=="buy") ? "blue " : "red";
                        html = html + '<tr>';
                        html = html + '<td class="left"><div class="bold">'+newSplitTime[0]+'</div>'+newSplitTime[1]+'</td>';
                        html = html + '<td class="right">'+coin+'</td>';
                        //html = html + '<td>'+ucfirst(value.extype)+'</td>';
                        html = html + '<td>'+transType+'</td>';
                        html = html + '<td>'+walletAddress+'</td>';
                        html = html + '<td class="left">'+numberWithCommas(parseFloat(transAmount).toFixed(2))+'</td>';
                        html = html + '<td>'+fees+'</td>';

                        html = html + '</tr>';
                    });
                }
                $("#withDrawHistoryList").html(html);
            }
        });
    }

    $(function() {

        var start = moment().subtract(29, 'days');
        var end = moment();

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            var starting = start.format('YYYY-MM-DD');
            var ending = end.format('YYYY-MM-DD');
            showSelected(starting,ending);
        }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                '<?= __('Today') ?>': [moment(), moment()],
                '<?= __('Yesterday') ?>': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '<?= __('Last 7 Days') ?>': [moment().subtract(6, 'days'), moment()],
                '<?= __('Last 15 Days') ?>': [moment().subtract(14, 'days'), moment()],
                '<?= __('Last 30 Days') ?>': [moment().subtract(29, 'days'), moment()],
                '<?= __('This Month') ?>': [moment().startOf('month'), moment().endOf('month')],
                '<?= __('Last Month') ?>': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        cb(start, end);

    });

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
    }
</script>
