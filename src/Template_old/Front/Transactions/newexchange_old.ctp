<div class="content-wrapper dashboardCon" style="z-index:1080">
<?php 
$curerntDate = time(); 

$launchDate = strtotime("2018-01-20 13:30:00");
$LchTime = strtotime("2018-04-15 13:30:00"); 

$getDiff = $LchTime - $curerntDate;

if($launchDate > $curerntDate) { 
?>
<script>
$('document').ready(function(){
/*  $('#usd').bind("paste",function(e) {
     e.preventDefault();
 }); */
 
  $('#coin_val').bind("paste",function(e) {
     e.preventDefault();
 });
 
  $('#btc_val').bind("paste",function(e) {
     e.preventDefault();
 });
  });
  
</script>
  <div class="outerTempCon">
    <div class="innerTempCon text-center">
      <h3>Thanks for Joining Hedgeconnect.co! </h3>
      <br />
      <p>This functionality currently unavailable.</p>
    </div>
  </div>
<?php } 
if($getDiff<0){
	?>
	<style>#time{display:none}</style>
	<?php
}
?>
<script>

  
  
  function startTimer(duration, display) {
     var timer = duration,days, hours, minutes, seconds;
    setInterval(function () {
        days = parseInt(timer / (3600*24), 10)
		hours = parseInt(timer%(3600*24) / 3600, 10)
		minutes = parseInt(timer % 3600 / 60, 10)
        seconds = parseInt(timer % 60, 10);

        days = days < 10 ? "0" + days : days;
		hours = hours < 10 ? "0" + hours : hours;
		minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.html('<span class="bgSpan" style="">'+hours+'h </span><span class="bgSpan" style="">'+minutes + 'm </span><span class="bgSpan" style="">' + seconds+'s </span> ');

        if (--timer < 0) {
            timer = duration;
			$("#time").hide();
			$("#time_counter").hide();
			
        }
    }, 1000);
}

jQuery(function ($) {
    var fiveMinutes = <?php echo $getDiff; ?>,
        display = $('#time');
    startTimer(fiveMinutes, display);
});


  
</script>



<style>
#buy_trade_rate{ font-weight:bold; }
#sell_trade_rate{ font-weight:bold; }
</style>


  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Exchange </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active"> Exchange </li>
    </ol>
  </section>
  <!-- Main content -->
  <section id="content" class="table-layout">
    <div class="inner_content_w3_agile_info">
      <div class="clearfix"></div>
      <!-- /blank -->
      <div class="blank_w3ls_agile">
        <div class="blank-page agile_info_shadow">
          <div class="widget widget-card dynamic inverse-mode bg-gradient-black">
            <div class="widget-card-content">
              <h4 class="widget-title"><b>Exchange</b></h4>
            </div>
			<style>
			.bgSpan{
				margin: 11px;padding: 10px;color: #fff;
				background: rgba(0, 0, 0, 0.7);
				display: inline-block;font: inherit;
				vertical-align: baseline;
			}
			</style>
			<!--<h1 id="time_counter" style="color: #181818;
font-weight: 600;
font-size: 38px;
text-transform: capitalize;">
				<span class="bgSpan">Exchange Will Start In</span>
			</h1>
			
			<h1 id="time" style="color: #181818;
font-weight: 600;
font-size: 38px;
text-transform: capitalize;">
			</h1>-->
           
			<?php if($getDiff<0  && $otherUsers=="no"){ ?>
            <div class="widget-card-content p-b-5 p-t-0">
              <div class="panel-body">
                <div class="row">
				<?=$this->Flash->render();?>
				
				<div class="col-md-8">
				
				<ul class="nav nav-tabs bg-white">
					<li class="active" style="width: 50%">
						<a href="#create-buy-order" data-toggle="tab" class="buy" aria-expanded="true">
							<span>Buy Order</span>
						</a>
					</li>
					<?php if($notShowSellOrderBox==0) { ?>
					<li style="width: 50%" class="">
						<a href="#create-sell-order" data-toggle="tab" class="sell" aria-expanded="false">
							<span>Sell Order</span>
						</a>
					</li>
					<?php } ?>
				</ul>
				
				
				
				<div class="tab-content" >
					<div class="tab-pane active" id="create-buy-order">
					<div class="col-md-10">
					<br>
					<form method="post" id="buy_form" accept-charset="utf-8" action="<?php echo $this->Url->build(['prefix'=>'front','controller'=>'transactions' , 'action'=>'newexchange']);  ?>">
						  <input type="hidden" name="type" value="buy"/>
						  <input type="hidden" name="buy_all_btc" id="buy_all_btc" value="<?php echo number_format((float)$gerUserTotalBtc,8) ?>"/>
                            <!--<div class="form-group valid-form">
                              <div class="input-group"> <span class="input-group-addon">USD</span>
                                <input onKeyUp="getConvert('doller',this.value)" autocomplete="off" id="usd" placeholder="0.0 USD" class="form-control" name="usd_amount" required  type="text">
                              </div>
                            </div>
                            <div class="signArrow"><i class="fa fa-angle-double-left"></i> <i class="fa fa-angle-double-right"></i></div>-->
                            <div class="form-group valid-form">
                              <div class="input-group"> <span class="input-group-addon">Available BTC</span>
                                <?php echo number_format((float)$gerUserTotalBtc,8); ?> BTC <a href="javascript:void(0)" id="buy_all">All</a>
                              </div>
                            </div>
							<br>
							<div class="form-group valid-form">
                              <div class="input-group"> <span class="input-group-addon">BTC you spend</span>
                                <input  autocomplete="off" id="btc_spend" placeholder="0.0 BTC" class="form-control offer-quantity" name="btc_spend" required  type="text">
                              </div>
                            </div>
							<br>
							<div class="form-group valid-form">
                              <div class="input-group"> <span class="input-group-addon">Price per Hed (BTC)</span>
                                <input  autocomplete="off" id="price_per_hc" value="<?php echo $btcPerHcBuy; ?>" placeholder="0.0 Hed" class="form-control" name="price_per_hc" required  type="text">
                              </div>
                            </div>
							
							<br>
							<div class="form-group valid-form">
                              <div class="input-group"> <span class="input-group-addon">Price per Hed (USD)</span>
                                <input  autocomplete="off" id="buy_price_in_usd" disabled value="6" placeholder="0.0 Usd" class="form-control" name="buy_price_in_usd" required  type="text">
                              </div>
                            </div>
							<br>
							<div class="clearfix"></div>
                            <div class="form-group valid-form">
                              <div class="input-group"> <span class="input-group-addon">Hed you receive</span>
                                <input  autocomplete="off"  id="hc_receive" placeholder="0.0 Hed" class="form-control" name="hc_receive" required  type="text">
                              </div>
                            </div>
							<br>
							<div class="clearfix"></div>
							<div class="form-group valid-form">
                              <div class="input-group"> <span class="input-group-addon">Fee</span>
                                <input  autocomplete="off"  id="buy_fee" disabled class="form-control" name="buy_fee" required  type="text">
                              </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group btn-group">
                              <input id="mySubmit" class="btn btn-primary buy_order_submit" value="Create Order" type="submit">
							  <img src="<?=$this->request->webroot ?>assets/images/ajax-loader.gif" id="buy_order_img" style="display:none;"/>
                            </div>
                          </form>
						  </div>
					
					</div>
					
					<?php if($notShowSellOrderBox==0) { ?>
					<div class="tab-pane" id="create-sell-order">
						<div class="col-md-10">
						<br>
						<form method="post" id="sell_form" accept-charset="utf-8" action="<?php echo $this->Url->build(['prefix'=>'front','controller'=>'transactions' , 'action'=>'newexchange']);  ?>">
						<input type="hidden" name="type" value="sell"/>
						<input type="hidden" name="sell_all_hc" id="sell_all_hc" value="<?php echo number_format((float)$gerUserTotalHc,8) ?>"/>
					  
						  <div class="form-group valid-form">
                              <div class="input-group"> <span class="input-group-addon">Available Hed</span>
                                <?php echo number_format((float)$gerUserTotalHc,8); ?> Hed <a href="javascript:void(0)" id="sell_all">All</a>
                              </div>
                            </div>
							<br>
							<div class="form-group valid-form">
                              <div class="input-group"> <span class="input-group-addon">Hed to sell</span>
                                <input  autocomplete="off" id="hc_spend" placeholder="0.0 Hed" class="form-control" name="hc_spend" required  type="text">
                              </div>
                            </div>
							<br>
							<div class="form-group valid-form">
                              <div class="input-group"> <span class="input-group-addon">Price per Hed (BTC)</span>
                                <input  autocomplete="off" id="price_per_hc_sell" value="<?php echo $btcPerHcSell; ?>" placeholder="0.0 Hed" class="form-control price" name="price_per_hc_sell" required  type="text">
                              </div>
                            </div>
							<br>
							<div class="form-group valid-form">
                              <div class="input-group"> <span class="input-group-addon">Price per Hed (USD)</span>
                                <input  autocomplete="off" id="sell_price_in_usd" disabled value="5.5" placeholder="0.0 Usd" class="form-control" name="sell_price_in_usd" required  type="text">
                              </div>
                            </div>
							<br>
							<div class="clearfix"></div>
                            <div class="form-group valid-form">
                              <div class="input-group"> <span class="input-group-addon">BTC you receive</span>
                                <input  autocomplete="off"  id="btc_receive" placeholder="0.0 BTC" class="form-control" name="btc_receive" required  type="text">
                              </div>
                            </div>
                            <div class="clearfix"></div>
							<br>
							<div class="form-group valid-form">
                              <div class="input-group"> <span class="input-group-addon">Fee</span>
                                <input  autocomplete="off"  id="sell_fee" disabled class="form-control want-quantity" name="sell_fee" required  type="text">
                              </div>
                            </div>
							<div class="clearfix"></div>
                            <div class="form-group btn-group">
                              <input id="mySubmit" class="btn btn-primary sell_order_submit" value="Create Order" type="submit">
							  <img src="<?=$this->request->webroot ?>assets/images/ajax-loader.gif" id="sell_order_img" style="display:none;"/>
                            </div>
						</form>
						</div>
					</div>
					<?php } ?>
				</div>
				</div>
				  
                  <div class="col-md-4 col-xs-12"> <a href="#" class="widget btn-block widget-stats bg-blue inverse-mode">
                  <div class="widget-stats-left">
                    <div class="widget-stats-title">Hed - Available Balance</div>
                  </div>
                  <div class="widget-stats-right" style="max-width:100%">
                    <div class="widget-stats-value f-s-25">  <?=number_format((float)$gerUserTotalHc,8);?>  Hed </div>
                    <!--<div class="widget-desc">4th January 2018 02:14</div>-->
                  </div>
                  </a> </div>
				  
				  
				    <div class="col-md-4 col-xs-12">
					  <a href="#" class="widget btn-block widget-stats bg-blue inverse-mode">
						  <div class="widget-stats-left">
							<div class="widget-stats-title">Latest Trade Rates</div>
						  </div>
						  <div class="widget-stats-right" style="max-width:100%">
							<div class="widget-stats-value f-s-25"> Buy : <span id="buy_trade_rate">0</span> BTC </div>
							<div class="widget-stats-value f-s-25"> Sell : <span id="sell_trade_rate">0</span>  BTC </div>
							<!--<div class="widget-desc">4th January 2018 02:14</div>-->
						  </div>
					  </a> 
					
				  </div>
				  
                </div>
              </div>
            </div>
			<?php } ?>
			
			
          </div>
        </div>
      </div>
    </div>
  
    <div class="inner_content_w3_agile_info">
      <div class="clearfix"></div>
      <!-- /blank -->
	  <?php if($getDiff<0 && $otherUsers=="no"){ ?>
	  
      <div class="blank_w3ls_agile">
        <div class="blank-page agile_info_shadow">
          <div class="panel panel-inverse endless_page_template">
            <div class="widget-card-content">
              <h4 class="widget-title"><b> Buy/Sell Orders</b></h4>
            </div>
			<div class="container">
			<div class="row">
            <div class="panel-body col-md-5">
              <p class="desc">Buy Order</p>
              <div class="table-responsive" id="buy_table_list">
                <table class="table table-inverse m-b-0">
                  <thead>
                    <tr>
                      <th>&nbsp;</th>
                      <th>VOLUME(BTC)</th>
                      <th>RATE(BTC)</th>
                      <th>VOLUME(Hed)</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
					
						$count= 1;
							
						 foreach($buyListing->toArray() as $k=>$data){
							
							if($k%2==0) $class="odd";
							else $class="even";
					?>
                    <tr class="<?=$class?>">
                      <td><a href="javascript:void(0)" onclick="plus_sign(this.id);" class="btn btn-xs btn-info data-fill-sell plus_sign" id="plus_sign_<?php echo $data['id']; ?>"><i class="fa fa-plus"></i></a></td>
                      <td><?php echo number_format((float)$data['buy_btc_amount'],8);?></td>
                      <td><?php echo number_format((float)$data['price_per_hc'],8);?></td>
                      <td><?php echo number_format((float)$data['buy_hc_amount'],8);?></td>
                    </tr>
                    <?php $count++; } ?>
                    <?php  if(count($buyListing->toArray()) < 1) {
							echo "<tr class='even'><td colspan = '6'>No record found</td></tr>";
					   } ?>
                  </tbody>
				  
				  
                  <tbody>
                  </tbody>
                </table>
				
				  <?php /* $this->Paginator->options(array('url' => array('controller' => 'Transactions', 'action' => 'newbuy_exchange_search')));
						echo "<div class='pagination' style = 'float:right'>";
	 
						// the 'first' page button
						$paginator = $this->Paginator;
						echo $paginator->first("First");

						// 'prev' page button, 
						// we can check using the paginator hasPrev() method if there's a previous page
						// save with the 'next' page button
						if($paginator->hasPrev()){
						echo $paginator->prev("Prev");
						}

						// the 'number' page buttons
						echo $paginator->numbers(array('modulus' => 2));

						// for the 'next' button
						if($paginator->hasNext()){
						echo $paginator->next("Next");
						}

						// the 'last' page button
						echo $paginator->last("Last");

						echo "</div>"; */
								
				?> 
              </div>
            </div>
			
			<div class="col-md-1"></div>
			<div class="panel-body col-md-5">
              <p class="desc">Sell Order</p>
              <div class="table-responsive" id="sell_table_list">
                <table class="table table-inverse m-b-0">
                  <thead>
                    <tr>
                      <th>&nbsp;</th>
                      <th>VOLUME(BTC)</th>
                      <th>RATE(BTC)</th>
                      <th>VOLUME(Hed)</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
					
						$count= 1;
							
						 foreach($sellListing->toArray() as $k=>$data){
							
							if($k%2==0) $class="odd";
							else $class="even";
					?>
                    <tr class="<?=$class?>">
                      <td><a href="javascript:void(0)" onclick="minus_sign(this.id);" class="btn btn-xs btn-warning minus_sign" id="minus_sign_<?php echo $data['id']; ?>" ><i class="fa fa-minus"></i></a></td>
                      <td><?php echo number_format((float)$data['sell_btc_amount'],8);?></td>
                      <td><?php echo number_format((float)$data['price_per_hc'],8);?></td>
                      <td><?php echo number_format((float)$data['sell_hc_amount'],8);?></td>
                    </tr>
                    <?php $count++; } ?>
                    <?php  if(count($sellListing->toArray()) < 1) {
							echo "<tr class='even'><td colspan = '6'>No record found</td></tr>";
					   } ?>
                  </tbody>
				  
				  
                  <tbody>
                  </tbody>
                </table>
				
				  <?php /* $this->Paginator->options(array('url' => array('controller' => 'Transactions', 'action' => 'newsell_exchange_search')));
						echo "<div class='pagination' style = 'float:right'>";
	 
						// the 'first' page button
						$paginator = $this->Paginator;
						echo $paginator->first("First");

						// 'prev' page button, 
						// we can check using the paginator hasPrev() method if there's a previous page
						// save with the 'next' page button
						if($paginator->hasPrev()){
						echo $paginator->prev("Prev");
						}

						// the 'number' page buttons
						echo $paginator->numbers(array('modulus' => 2));

						// for the 'next' button
						if($paginator->hasNext()){
						echo $paginator->next("Next");
						}

						// the 'last' page button
						echo $paginator->last("Last");

						echo "</div>"; */
								
				?> 
              </div>
            </div>
			</div>
			</div>
			
          </div>
        </div>
      </div>
	  <?php } ?>
	  
	  
	  
    </div>
    <br />
    <br />
	
	
	
	
	
	
	
	
	
	
  </section>
</div>
<script>  

/* function deletePopup(id){
	$("#delete_exchange_id").val(id);
	$('#myDeleteModal').modal('show');
}

$('document').ready(function(){
	$("#delete_yes").click(function(){
		var id = $("#delete_exchange_id").val();
		var getUrl = window.location;
		var baseUrl = getUrl.protocol + "//" + getUrl.host + "/";
		var deleteUrl = baseUrl+"front/transactions/exchange-delete/"+id;
		//window.location.href = deleteUrl
	});
	$("#delete_no").click(function(){
		$("#delete_exchange_id").val('');
	});
}); */


function plus_sign(id){
		var row = $("#"+id).closest('tr');
		var btc = row.find('td:nth-child(2)').first().text();
		var price_per_hc = row.find('td:nth-child(3)').first().text();
		var hc = row.find('td:nth-child(4)').first().text();
		$("#hc_spend").val(hc).trigger('input');
		$("#price_per_hc_sell").val(price_per_hc).trigger('input');
		$("html, body").animate({scrollTop: 0}, 100);
		$(".sell").click();
}

function minus_sign(id){
	var row = $("#"+id).closest('tr');

	var btc = row.find('td:nth-child(2)').first().text();
	var price_per_hc = row.find('td:nth-child(3)').first().text();
	var hc = row.find('td:nth-child(4)').first().text();
	$("#btc_spend").val(btc).trigger('input');
	$("#price_per_hc").val(price_per_hc).trigger('input');
	$("html, body").animate({scrollTop: 0}, 100);
	$(".buy").click();
}

$(document).ready(function() {

	$("#buy_all").click(function(){
		var getAllBTc = $("#buy_all_btc").val();
		$("#btc_spend").val(getAllBTc).trigger('input');
	});
	
	$("#sell_all").click(function(){
		var getAllBTc = $("#sell_all_hc").val();
		$("#hc_spend").val(getAllBTc).trigger('input');
	});
	
	

	$('form[id="buy_form"]').submit(function() {
		$("#buy_order_img").show();
		$(".buy_order_submit").remove();
	});
	
	$('form[id="sell_form"]').submit(function() {
		$("#sell_order_img").show();
		$(".sell_order_submit").remove();
	});
	
	/* $(".buy_order_submit").click(function(){
		var form = document.getElementById("buy_form");
		form.submit();
		
		
	}); */
	
	/* $(".sell_order_submit").click(function(){
		$("#sell_form").submit();
		$("#sell_order_img").show();
		$(".sell_order_submit").remove();	
	}); */
	
	
	
	
	/* $(".plus_sign").click(function(){
		var row = $(this).closest('tr');

		var btc = row.find('td:nth-child(2)').first().text();
		var price_per_hc = row.find('td:nth-child(3)').first().text();
		var hc = row.find('td:nth-child(4)').first().text();
		$("#hc_spend").val(hc).trigger('input');
		$("#price_per_hc_sell").val(price_per_hc).trigger('input');
		$("html, body").animate({scrollTop: 0}, 100);
		$(".sell").click();
	});
	
	
	
	
	$(".minus_sign").click(function(){
		var row = $(this).closest('tr');

		var btc = row.find('td:nth-child(2)').first().text();
		var price_per_hc = row.find('td:nth-child(3)').first().text();
		var hc = row.find('td:nth-child(4)').first().text();
		$("#btc_spend").val(btc).trigger('input');
		$("#price_per_hc").val(price_per_hc).trigger('input');
		$("html, body").animate({scrollTop: 0}, 100);
		$(".buy").click();
	}); */

/* 	$('body').on('click', '[data-fill-sell]', function (e) {
		e.preventDefault()

		var row = $(this).closest('tr');

		var offer_quantity = row.find('td:nth-child(4)').first().text();
		var price = row.find('td:nth-child(3)').first().text();

		$('a.sell').click();
		$('#S-offer_quantity').val(offer_quantity)
		$('#S-price').val(price).trigger('input')

		$(document).scrollTop($('#create-an-order-panel').offset().top);
	}); */

});

$(window).load(function(){
                $('#onload').modal('show');
				
            });
	
	var buy_fee = 0.25000000;
    var sell_fee = 0.25000000;
	var btc_to_usd = 10103.00000000;
	var btc_to_usd = 10103.00000000;	
	var buyPriceInUsd = 6;
	var sellPriceInUsd = 5.5;
	var currentBuyPricePerHed = <?php echo $btcPerHcBuy; ?>;
	var currentSellPricePerHed = <?php echo $btcPerHcSell; ?>;
	
	$('#btc_spend').on('input', function () {
		calculateBuyForm($(this).attr('id'), 'want_quantity')
	})

	$('#price_per_hc').on('input', function () {
		calculateBuyForm($(this).attr('id'), 'want_quantity')
	})

	$('#hc_receive').on('input', function () {
		calculateBuyForm($(this).attr('id'), 'offer_quantity')
	})
	
	
	function calculateBuyForm(thisId, field) {
		
		var btcSpendVal = $("#btc_spend").val();
		var btcSpendVal = parseFloat(btcSpendVal);
		var pricePerHc = $("#price_per_hc").val();
		var pricePerHc = parseFloat(pricePerHc);
		var hcReceive = $("#hc_receive").val();
		var hcReceive = parseFloat(hcReceive);
		
		if(thisId == "btc_spend" || thisId == "price_per_hc"){
			var btcSpendVal = btcSpendVal.toFixed(8);
			var adminFee = btcSpendVal*(buy_fee/100);
			var adminFee = parseFloat(adminFee); 
			var adminFee = adminFee.toFixed(8);
			var remainingBtcSpend = btcSpendVal-adminFee;
			var remainingBtcSpend = remainingBtcSpend.toFixed(8);
			var hcReceiveAmt = (remainingBtcSpend/pricePerHc).toFixed(8);
			$("#hc_receive").val(hcReceiveAmt);
			var adminFee = parseFloat(adminFee); 
			var adminFee = adminFee.toFixed(8);
			$("#buy_fee").val(adminFee);
			
		}
		
		if(thisId == "hc_receive"){
			var hcReceiveAmt = hcReceive.toFixed(8);
			var remainingBtcSpend = hcReceive*pricePerHc;
			var remainingBtcSpend = remainingBtcSpend.toFixed(8);
			//var btcSpendVal = (remainingBtcSpend*100)/(100-(buy_fee/100));
			var btcSpendVal =remainingBtcSpend/(1-0.0025);
			//var adminFee = btcSpendVal*(buy_fee/100);
			btcSpendVal = btcSpendVal.toFixed(8);
			$("#btc_spend").val(btcSpendVal);
			var adminFee = btcSpendVal*(buy_fee/100);
			adminFee = adminFee.toFixed(8);
			$("#buy_fee").val(adminFee);
		}
		
		var buy_price_in_usd = ((6/currentBuyPricePerHed)*pricePerHc).toFixed(8);
		$("#buy_price_in_usd").val(buy_price_in_usd);
	}		


	$('#hc_spend').on('input', function () {
		calculateSellForm($(this).attr('id'), 'want_quantity')
	})

	$('#price_per_hc_sell').on('input', function () {
		calculateSellForm($(this).attr('id'), 'want_quantity')
	})

	$('#btc_receive').on('input', function () {
		calculateSellForm($(this).attr('id'), 'offer_quantity')
	})
	
	function calculateSellForm(thisId, field) {
		var hcSpendVal = $("#hc_spend").val();
		var hcSpendVal = parseFloat(hcSpendVal);;
		var pricePerHcSell = $("#price_per_hc_sell").val();
		var pricePerHcSell = parseFloat(pricePerHcSell);;
		var BtcReceive = $("#btc_receive").val();
		var BtcReceive = parseFloat(BtcReceive);;
		
		if(thisId == "hc_spend" || thisId == "price_per_hc_sell"){
			var btcReceive = hcSpendVal*pricePerHcSell;
			var btcReceive = btcReceive.toFixed(8);
			var adminFee = btcReceive*(buy_fee/100);
			var adminFee = parseFloat(adminFee); 
			var adminFee = adminFee.toFixed(8);
			var remainingBtcReceive = btcReceive-adminFee;
			var remainingBtcReceive = remainingBtcReceive.toFixed(8);
			$("#btc_receive").val(remainingBtcReceive);
			var adminFee = parseFloat(adminFee); 
			var adminFee = adminFee.toFixed(8);
			$("#sell_fee").val(adminFee);
		}
		
		if(thisId == "btc_receive"){
			
			var remainingBtcReceive = BtcReceive.toFixed(8);
			var adminFee = remainingBtcReceive*(buy_fee/100);
			var btcReceive = parseFloat(remainingBtcReceive)+parseFloat(adminFee);
			var btcReceive = parseFloat(btcReceive);
			var btcReceive = btcReceive.toFixed(8); 
			var pricePerHcSell = pricePerHcSell.toFixed(8);
			var hcSpendVal = btcReceive/pricePerHcSell;
			var hcSpendVal = hcSpendVal.toFixed(8);
			$("#hc_spend").val(hcSpendVal);
			var adminFee = parseFloat(adminFee); 
			var adminFee = adminFee.toFixed(8);
			$("#sell_fee").val(adminFee);
		}
		var sell_price_in_usd = ((5.5/currentBuyPricePerHed)*pricePerHcSell).toFixed(8);
		$("#sell_price_in_usd").val(sell_price_in_usd);
	}		


</script>
<script>
	   $(document).ready(function() {
	  $('#start-date').datepicker({
				
				format: 'yyyy-mm-dd',
				maxDate: '0'

			});
			$('#end-date').datepicker({
				
				format: 'yyyy-mm-dd',
				maxDate: '0'

			});


      });
	jQuery('#buy_table_list').on('click','.pagination li a',function(event){
		event.preventDefault() ;
		
		var keyy = $('form').serialize();
		var urli = jQuery(this).attr('href');
		jQuery.ajax({ 
				url: urli,
				data: {key:keyy},
				type: 'POST',
				success: function(data) {
					if(data){
						
						jQuery('#buy_table_list').html(data);
						
					}
				}
		});
		
	});
	
	jQuery('#sell_table_list').on('click','.pagination li a',function(event){
		event.preventDefault() ;
		
		var keyy = $('form').serialize();
		var urli = jQuery(this).attr('href');
		jQuery.ajax({ 
				url: urli,
				data: {key:keyy},
				type: 'POST',
				success: function(data) {
					if(data){
						
						jQuery('#sell_table_list').html(data);
						
					}
				}
		});
		
	});	
	
	
	function getTradeRates() {
		jQuery.ajax({ 
			url      : '<?php echo $this->Url->build(['controller'=>'pages' , 'action'=>'trade-rates']);  ?>',
			dataType : 'json',
			success  : function(data) { 
				$("#buy_trade_rate").html(data.buy_exchange.price.toFixed(8)).fadeOut().fadeIn('slow');
				$("#buy_trade_rate").css("color",data.buy_exchange.color);
				$("#sell_trade_rate").html(data.sell_exchange.price.toFixed(8)).fadeOut().fadeIn('slow');
				$("#sell_trade_rate").css("color",data.sell_exchange.color);
				//$("#changedata").html(data);
			}
		});
	}
	setInterval(function(){getTradeRates();}, 5000);
	$('document').ready(function(){
		getTradeRates();
	})
		
	
	/* jQuery('#my_buy_table_list').on('click','.pagination li a',function(event){
		event.preventDefault() ;
		
		var keyy = $('form').serialize();
		var urli = jQuery(this).attr('href');
		jQuery.ajax({ 
				url: urli,
				data: {key:keyy},
				type: 'POST',
				success: function(data) {
					if(data){
						
						jQuery('#my_buy_table_list').html(data);
						
					}
				}
		});
		
	});
	
	jQuery('#my_sell_table_list').on('click','.pagination li a',function(event){
		event.preventDefault() ;
		
		var keyy = $('form').serialize();
		var urli = jQuery(this).attr('href');
		jQuery.ajax({ 
				url: urli,
				data: {key:keyy},
				type: 'POST',
				success: function(data) {
					if(data){
						
						jQuery('#my_sell_table_list').html(data);
						
					}
				}
		});
		
	});	 */
	
	
		
  </script>

