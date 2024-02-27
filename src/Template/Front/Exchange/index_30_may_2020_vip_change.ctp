<style>
.panel-default > .panel-heading {
	      border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    padding: 10px;
    font-size: larger;
}
body{font-size:12px!important; }
#chartdiv {
	width	: 100%;
	height	: 300px;
}		
.pp{padding-right: 0;padding-left: 0;}
.buy_value{margin-bottom: 20px;display: block;}
.buy_value .btn{width: 80px;border: none;padding: 0px;}
.buy_value li{display: inline-block;margin-right: 18px;}
.buy_value li input{border: 1px solid #ccc;height: 30px;padding-left: 5px;}
.buy_value li label{font-weight: 500;margin-right: 5px;}
.showit {
    padding: 10px;
    margin: 0 auto;
    width: 20%;
	text-align:center;
}
</style>
	<script src="https://widgets.coingecko.com/coingecko-coin-price-static-headline-widget.js"></script>

<script src="<?php echo $this->request->webroot ?>js/highstock.js"></script>
<script src="<?php echo $this->request->webroot ?>js/exporting.js"></script>
<script src="<?php echo $this->request->webroot ?>datepicker/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="<?php echo $this->request->webroot ?>datepicker/bootstrap-datepicker.min.css" />
	<!--<aside class="aside asideBuySell">
	<nav class="sidebar">
	  <ul class="nav">
	<li>
		  <div class="item user-block has-submenu">
			<div class="user-block-picture">
			<?php if(!empty($user->image)) { ?>
			<img src="<?php echo $this->request->webroot.'uploads/user_thumb/'.$user->image; ?>" alt="Avatar" width="60" height="60" class="img-thumbnail img-circle account-img-mb"> </div>
			<?php } else { ?>
				<img src="<?php echo $this->request->webroot ?>assets/html/images/02.jpg" alt="Avatar" width="60" height="60" class="img-thumbnail img-circle account-img-mb"> </div>
			<?php } ?>
			<div class="user-block-info"> <span class="user-block-name item-text"><?php echo $user->username; ?></span> <span class="user-block-role"><i class="fa fa-check text-green"></i> Verified</span>
			  <div class="label label-primary"><a style="color:#fff;" href="<?php echo $this->Url->build(['controller'=>'users','action'=>'logout']); ?>"><i class="fa fa-lock"></i> Logout</a></div>
			</div>
		  </div>
		</li>
		<li class="">

			<?=$this->Flash->render();?>
			 
			  <div class="bs-example bs-example-tabs" data-example-id="togglable-tabs">
				<ul id="myTabs" class="nav nav-tabs nav-tabs-noboder minus-margin-tab" role="tablist">
				  <li role="presentation" class="active"><a href="https://xbootstrap.com/demo/crypto/cryptoapp/index-6.html#home" class="tab-link-pad" id="home-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true"><i class="fa fa-gavel"></i> Buy</a></li>
				  <li role="presentation" class=""><a href="https://xbootstrap.com/demo/crypto/cryptoapp/index-6.html#profile" class="tab-link-pad" role="tab" id="profile-tab" data-toggle="tab" aria-controls="profile" aria-expanded="false"><i class="fa fa-bullhorn"></i> Sell</a></li>
				</ul>
				<div id="myTabContent" class="tab-content tab-content-BuySell">
				
				
				  
				  
				
					
					
					
				
				
				
				</div>
		
		 
		</li>
	  </ul>
	</nav>
</aside>-->


<div>
   
      <?php $showLink = $this->Url->build(['controller'=>'pages','action'=>'mywallet']);  ?>
      <!--<a href="<?php echo $showLink; ?>" class="btn btn-labeled  pull-right"> <span class="btn-label"><i class="fa fa-dollar"></i> </span>Goto Wallet</div></a>-->
      
      <div class="row row2">
	  <?php if(empty($authUserId)){  ?>
      <div class="col-md-12"><div class="col-md-2 showit">BTC : <span id="btc_inr_home"></span> INR </div><div class="col-md-2 showit">ETH : <span id="eth_inr_home"></span> INR</div><div class="col-md-2 showit">XRP : <span id="xrp_inr_home"></span> INR</div><div class="col-md-2 showit">LTC:<span id="ltc_inr_home"></span> INR</div><div class="col-md-2 showit">BCH:<span id="bch_inr_home"></span> INR</div></div>
	  <?php } ?>
	   <div class="col-md-3">

        <div class="panel-wrapper collapse in" aria-expanded="true" style="">
                  <div class="panel panel-default">
				  <!--<div class="panel-heading">All Markets</div>-->
				  <ul class="panel_heading_man">
				   <li class="<?php echo $btcTabClass; ?>"><a style="color:#fff;" href="<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'index','BTC','INR']); ?>" > BTC </a></li>
				   <li class="<?php echo $ethTabClass; ?>"><a style="color:#fff;" href="<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'index','ETH','INR']); ?>" > ETH </a></li>
				   <li class="<?php echo $xrpTabClass; ?>"><a style="color:#fff;" href="<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'index','XRP','INR']); ?>" > XRP </a></li> 
				   <li class="<?php echo $ltcTabClass; ?>"><a style="color:#fff;" href="<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'index','LTC','INR']); ?>" > LTC </a></li> 
				   <li class="<?php echo $bchTabClass; ?>"><a style="color:#fff;" href="<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'index','BCH','INR']); ?>" > BCH </a></li>
				  </ul>
                    <div class="panel-body">
                      <div id="datatable1_wrapper" class="dataTables_wrapper form-inline no-footer"> 
                        <table id="datatable1" class="table table-striped table-hover dataTable no-footer" role="grid" aria-describedby="datatable1_info">
                          <thead>
                            <tr role="row">
                              <th class="tableSmallPad sorting_asc" tabindex="0" aria-controls="datatable1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Coin: activate to sort column descending" style="width: 0px;">Coin</th>
                              <th class="tableSmallPad sorting tr" tabindex="0" aria-controls="datatable1" rowspan="1" colspan="1" aria-label="Price: activate to sort column ascending" style="width: 0px;">Price</th>
                             
                              
                            </tr>
                          </thead>
                          <tbody>
                          <?php
								
						  foreach($getCoinPairList as $getCoinPairSingle){
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
							$getMyCustomPrice  = number_format($getMyCustomPrice,8); 
						  ?>
                            <tr class="clickable-row odd" data-href="index.html" role="row">
                              <td class="tableSmallPad sorting_1">
                                
                                <input type="radio" class="radio_item" value="" name="BCH" id="radio1">
                                <label class="label_item" for="radio1"> <i class="fa fa-star text-c-blue" <?php echo $color; ?>></i> </label>
								<a style="color:#fff;" href="<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'index',$getCoinPairSingle['cryptocoin_first']['short_name'],$getCoinPairSingle['cryptocoin_second']['short_name']]); ?>" > 
									<?php echo $getCoinPairSingle['cryptocoin_first']['short_name']."<span style='color:#c7c9db;'>/".$getCoinPairSingle['cryptocoin_second']['short_name']; ?></span> 
								</a>
							   </td>
                              <td class="tableSmallPad tr" id="current_price_<?php echo $getCoinPairSingle['cryptocoin_first']['short_name']."_".$getCoinPairSingle['cryptocoin_second']['short_name']; ?>"><?php echo $getMyCustomPrice;//.$symbol; ?></td>
                             
								
                            </tr>
						  <?php } ?>
                          </tbody>
                        </table>
                        
                      </div>
                    </div>
                  </div>
                 
                </div>
       
          <div class="panel panel-default">
            <div class="panel-heading heading2">Market History </div>
            <div class="panel-wrapper " aria-expanded="true" style="">
             <div class="panel-body">
              <div class="table-responsive" style="overflow-y:auto;max-height:400px;">
                <table class="table table-striped table-hover table-condensed">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <!--<th>Type</th>-->
                      <th>Price(<?php echo $secondCoin; ?>)</th>
                     <!-- <th><?php// echo $secondCoin; ?> Amount</th>-->
					  <th class="tr"> Amount(<?php echo $firstCoin; ?>)</th>
                    </tr>
                  </thead>
                  <tbody id="market_history">
                    <tr>
                      <td colspan=5><img src="<?php echo $this->request->webroot ?>ajax-loader.gif" /></td>
                    </tr>
                    
                  </tbody>
                </table>
              </div>
            </div>
			
            </div>
          </div>
        
	  </div>
	  <div class="col-md-9">
	  <div data-toggle="play-animation" data-play="fadeInLeft" data-offset="0" data-delay="1400" class="panel widget anim-running anim-done" style="">
                <div class="panel-body top_price_value">
                  <h4><?php echo $firstCoin."/".$secondCoin; ?></h4>
                  <p class="" ><!--<i class="fa fa-money"></i>--> <span id="current_price"> <img src="<?php echo $this->request->webroot ?>ajax-loader.gif" /></span></p>
				  <?php if($firstCoin!="RAM" && $secondCoin!="ADMC" ) { ?>
                <!-- <p class="text-success"   style="color:#6f6f6f;"><i class="fa fa-usd"></i> <span id="current_price_usd"> <img src="<?php //echo $this->request->webroot ?>ajax-loader.gif" /></span></p>-->
				  <?php } ?>
				   <p class=" retextp"><b style="margin-right: 5px;">24H Change</b> <span id="change_in_one_day"> <img src="<?php echo $this->request->webroot ?>ajax-loader.gif" /></span></p>
				   <p class=" retextp"><b style=" margin-right: 5px;">24H High</b> <span id="max_price"> <img src="<?php echo $this->request->webroot ?>ajax-loader.gif" /></span></p>
				    <p class=" retextp"><b style=" margin-right: 5px;">24H Low</b> <span id="min_price"> <img src="<?php echo $this->request->webroot ?>ajax-loader.gif" /></span></p>
				  <p class="retextp"><b style=" margin-right: 5px;">24H Volume</b> <span id="current_volume"> <img src="<?php echo $this->request->webroot ?>ajax-loader.gif" /></span></p>
                </div>
              </div>
	  <div class="row">
	   
        <div class="col-md-8 mdpart"> 
              <div class="panel panel-default">
                <div class="panel-collapse">
                  <div class="panel-body">
                   <!-- <h4><?php //echo $firstCoin."-".$secondCoin; ?></h4>	-->	
						<div id="container" style="height: 400px; min-width: 310px"></div>
                  </div>
                </div>
            
          </div>
         
          <div class="row">
           
			
			 <!-- 
			<div class="col-md-6">
              <div data-toggle="play-animation" data-play="fadeInLeft" data-offset="0" data-delay="1400" class="panel widget anim-running anim-done" style="">
                <div class="panel-body">
                 
                  <h3 class="mt0">Total Volume</h3>
                  <p class="text-success"> <span id="current_volume"> <img src="<?php echo $this->request->webroot ?>ajax-loader.gif" /></span></p>
               
                </div>
              </div>
            </div>-->
			
			
			<?php
			//if(in_array($authUserId,[10003090,10003992])){
				$cudate = date('Y-m-d',strtotime(' -1 day' ) );
			?>
			
          
          </div>
		  
		  <div class="panel panel-default">
		   <div class="panel-collapse">
		   <div class="panel-body">
		  <div class="row">
		  <div class="col-md-12">
				<div class="col-md-2" onClick="limitMarketClick(this.id);" id="limit" style="padding:10px;border:1px solid #fff;text-align:center;">LIMIT</div>
				<div class="col-md-2" onClick="limitMarketClick(this.id);"  id="market" style="padding:10px;border:1px solid  #d5cccc;color: #d5cccc;text-align:center;">Market</div>	
				<span style="float: right;"> Maker : 0.5%, Taker : 0.5%</span>				
		  </div>
	
		
		<div class="col-md-6">
		<div role="tabpanel"  id="profile" aria-labelledby="profile-tab" class="panel panel-default">
		 <div class="panel-heading">Buy </div>
		 <div class="panel-body">
						
						 
						  <div class="panel-wrapper collapse in h-auto" aria-expanded="true">
						 <?php if(!empty($authUserId) && $googleAuthVerify=='Y') { ?>
							<form method="post" id="sell_form" onsumit="return false;" accept-charset="utf-8" action="<?php echo $this->Url->build(['prefix'=>'front','controller'=>'exchange' , 'action'=>'index',$firstCoin,$secondCoin]);  ?>">
						  <?php } ?>
							<input type="hidden" name="type" value="sell"/>
							<div >
								<div class="input-group m-b"> 
									<span id="span_sell_volume"   class="input-group-addon darkformfield">Available</span>
									<input type="text" readonly   placeholder="Volume"  id="span_sell_volume_all"  value="<?php echo $secondCoinSum; ?>" class="form-control text-right">
									<span id="span_sell_volume"   class="input-group-addon darkformfield"><?php echo $secondCoin; ?></span>
								</div>
							    <!--<div class="m-t-9" >
									Price <?php echo $secondCoin; ?>
								</div>-->
							  <div class="m-t-9" id="sell_price_div">
								<div class="input-group m-b">
								  <span class="input-group-addon darkformfield">Price</span>
								  <input type="text" class="form-control text-right" required autocomplete="false" id="sell_per_price" name="per_price" placeholder="Price Per <?php echo $secondCoin; ?>">
									<span class="input-group-addon darkformfield"><?php echo $secondCoin; ?> </span>
								</div>
							  </div>
							  <!--<div class="m-t-9" >
									Amount 
								</div>-->
								<div class="m-t-9">
									<div class="input-group m-b"> 
										<span class="input-group-addon darkformfield">Amount</span>
										<input type="text" placeholder="Volume" required autocomplete="false" id="sell_volume" name="volume" class="form-control text-right">
										<span class="input-group-addon darkformfield"><?php echo $firstCoin; ?></span>
									</div>
								</div>
							  
								<!--<div class="m-t-9" >
									<?php echo $firstCoin; ?> Total
								</div>-->
							  
							  
							  <div class="m-t-9">
								<div class="input-group m-b">
								  <span class="input-group-addon darkformfield">Total</span>
									
								  <input type="text" class="form-control text-right" required autocomplete="false" id="sell_total_amount"  placeholder="Total Amount">
								  <span class="input-group-addon darkformfield"><?php echo $secondCoin	; ?> </span>
								
								</div>
							  </div>
				
							  <input type="hidden" class="form-control text-right" autocomplete="false" id="sell_admin_fee" disabled placeholder="0.5% Admin Fee">
							  
							  <div class="m-t-9">
							    <div id="show_sell_resp"></div> 
								<?php if(!empty($authUserId) && $googleAuthVerify=='Y') { ?>
									<input type="submit" class="btn  btn-block sell_ntr" style="background:#50ba6c;" value="Buy <?php echo $firstCoin; ?>">
								<?php 
								} else {
									if(empty($authUserId)){  		
										echo '<a  class="btn  btn-block buy_ntr" href="/front">Login</a>';
									}
									else {
										echo '<a  class="btn  btn-block buy_ntr" href="/front/users/security">Verify Authenticator</a>';
									}

								} ?>
							  </div>
							  
							</div>
							<?php if(!empty($authUserId) && $googleAuthVerify=='Y') { ?>
							</form>
							<?php } ?>
							
						  </div>
						</div></div>
		</div>
		
		
		
		    <div class="col-md-6">
		 <div role="tabpanel" id="home" aria-labelledby="home-tab" class="panel panel-default">
		  <div class="panel-heading"> Sell</div>
		 <div class="panel-body">
					
					 
					  <div class="panel-wrapper collapse in h-auto" aria-expanded="true">
					 
					  <?php if(!empty($authUserId) && $googleAuthVerify=='Y') { ?>
						<form method="post" id="buy_form" onsumit="return false;" accept-charset="utf-8" action="<?php echo $this->Url->build(['prefix'=>'front','controller'=>'exchange' , 'action'=>'index',$firstCoin,$secondCoin]);  ?>">
					  <?php } ?>
						<input type="hidden" name="type" value="buy"/>
						<div >
						  <div class="input-group m-b">
						  <span id="span_buy_volume" class="input-group-addon  darkformfield" >Available</span>
							<input type="text" readonly placeholder="Volume" id="span_buy_volume_all"   value="<?php echo $firstCoinSum; ?>" class="form-control text-right">
							<span id="span_buy_volume" class="input-group-addon  group-btn-hover darkformfield"  ><?php echo $firstCoin; ?></span>
						  </div>
						  <!--<div class="m-t-9" >
								Price Per <?php echo $secondCoin; ?>
						  </div>-->
						  <div class="m-t-9" id="buy_price_div">
							 <div class="input-group m-b">
								<span class="input-group-addon  darkformfield">Price</span>
							    <input type="text" class="form-control text-right" required autocomplete="false" id="buy_per_price" name="per_price" placeholder="Price Per <?php echo $secondCoin; ?>">
								<span class="input-group-addon  darkformfield"><?php echo $secondCoin; ?> </span>
							</div>
						  </div>
						  <!--<div class="m-t-9" >
								Amount
						  </div>-->
						  <div class="m-t-9">	
							  <div class="input-group m-b"> <span class="input-group-addon  group-btn-hover darkformfield">Amount</span>
								<input type="text" placeholder="Volume" autocomplete="false" required id="buy_volume" name="volume" class="form-control text-right">
								 <span class="input-group-addon  group-btn-hover darkformfield"><?php echo $firstCoin; ?></span>
							  </div>
						   </div>
						   <!--<div class="m-t-9" >
								<?php echo $firstCoin; ?> Total
						  </div>-->
						  <div class="m-t-9">
							<div class="input-group m-b">
							  <span  class="input-group-addon  darkformfield">Total </span>
							  <input type="text" class="form-control text-right" required autocomplete="false" id="buy_total_amount"   placeholder="Total Amount">
								<span  class="input-group-addon  darkformfield"><?php echo $secondCoin; ?> </span>
							</div>
						  </div>
						  
						   <!--<div class="m-t-9" >
								 Fee
						  </div>-->
						   <input type="hidden" class="form-control text-right" autocomplete="false" id="buy_admin_fee" disabled placeholder="0.5% Admin Fee">
						 
						  
						  
						  
						  <div class="m-t-9">
							<div id="show_buy_resp"></div>
							
								<?php if(!empty($authUserId) && $googleAuthVerify=='Y') { ?>
							<input type="submit" class="btn  btn-block buy_ntr"  style="background:#ef5057;" value="Sell <?php echo $firstCoin; ?>">
								<?php 
								} else {
									if(empty($authUserId)){  		
										echo '<a  class="btn  btn-block buy_ntr" href="/front">Login</a>';
									}
									else {
										echo '<a  class="btn  btn-block buy_ntr" href="/front/users/security">Verify Authenticator</a>';
									}

								} ?>
						  </div>
						 
						</div>
						 <?php if(!empty($authUserId) && $googleAuthVerify=='Y') { ?>
						 </form>
						 <?php } ?>
						
					  </div>
					</div>
				  
				
				
		</div></div>
		  </div>
		  
		  
		  </div>
		  </div>
		  </div>
		  
		  
		  
        </div>
        
   
	  
	   <script>
	  
	  
	var jsonData = [];
			<?php  foreach($getGrpData as $getLastTrans) {?>
				var ddt = [
					<?php echo strtotime($getLastTrans['datecol'])."000" ?>,
					<?php echo $getLastTrans['open_price'];  ?>,
					<?php echo $getLastTrans['max_price'];  ?>,
					<?php echo $getLastTrans['min_price'];  ?>,
					<?php echo $getLastTrans['close_price'];  ?>
				 ];
				jsonData.push(ddt);
				<?php } ?>
	
	
	

    // create the chart
    Highcharts.stockChart('container', {


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
					/* [
                        'hour', // unit name
                        [10] // allowed multiples
                    ], */
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
    });

	  </script>
	  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
	
	function limitMarketClick(getClickId){
			  $("#"+getClickId).css("border","1px solid #fff").css("color","#fff");
			  var otherId = (getClickId=="limit") ? "market" : "limit"; 
			  $("#"+otherId).css("border","1px solid #d5cccc").css("color","#d5cccc");
			  var setPrice = (getClickId=="limit") ? "" : $("#current_price").html();
			  $("#buy_per_price").val(setPrice);
			  $("#sell_per_price").val(setPrice);
			  if(getClickId=="market") {
				$("#buy_price_div").hide();
				$("#sell_price_div").hide();
			  }
			  else {
				$("#buy_price_div").show();
				$("#sell_price_div").show();
			  }
			  
		  }
	
	var fee = 0.50000000;
	$(document).ready(function(){
		
		$("#span_buy_volume").click(function(){
			var getVal = $("#span_buy_volume_all").val();
			$("#buy_total_amount").val(getVal).change();
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
		
	});
	
	
	function calAdminFee(totalAmt){
		var calFee = (totalAmt*fee)/100;
		calFee = parseFloat(calFee);
		calFee = calFee.toFixed(8);
		if(!isNaN(calFee)){
			return calFee;	
		}
		return '';
	}
	
	function calculateForm(thisId,exType) {
		var volume = $("#"+exType+"_volume").val();
		var volume = parseFloat(volume);
		var volume = volume.toFixed(8);
		
		var totalAmt = $("#"+exType+"_total_amount").val();
		var totalAmt = parseFloat(totalAmt);
		var totalAmt = totalAmt.toFixed(8);
		
		var perPrice = $("#"+exType+"_per_price").val();
		var perPrice = parseFloat(perPrice);
		var perPrice = perPrice.toFixed(8);
		
		if(thisId == exType+"_volume" && !isNaN(perPrice)){
			// calculate total 
			var totalAmt = volume*perPrice;
			totalAmt = parseFloat(totalAmt);
			totalAmt = totalAmt.toFixed(8);
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
				totalAmt = totalAmt.toFixed(8);
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
				volume = volume.toFixed(8);
				if(!isNaN(volume)){
					$("#"+exType+"_volume").val(volume);
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
			volume = volume.toFixed(8);
			if(!isNaN(volume)){
				$("#"+exType+"_volume").val(volume);
				// calculate fee
				var calFee = calAdminFee(totalAmt);
				$("#"+exType+"_admin_fee").val(calFee);
			}
		}
		
		
		
		
	}	

	
		

	
	
   	     
	/* google.charts.load('current', {'packages':['annotatedtimeline']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('date', 'Date');
        data.addColumn('number', '<?php echo $secondCoin; ?>');
        data.addColumn('string', 'title1');
        data.addColumn('string', 'text1');
        data.addRows([
			[new Date(2018, 6, 7), 0, undefined, undefined],
            <?php if(!empty($sendGraphData)) { foreach($sendGraphData as $getLastTrans) {

                $d = date("d",strtotime($getLastTrans['time']));
                $y = date("Y",strtotime($getLastTrans['time']));
                $m = date("m",strtotime($getLastTrans['time']));

                ?>
           [new Date(<?php echo $y; ?>, <?php echo $m-1; ?> ,<?php echo $d; ?>), <?php echo $getLastTrans['amt']; ?>, undefined, undefined] ,
			<?php } } else { 
            
            $year = date("Y");
            $month = date("m");
            $date = date("d"); ?>
            [new Date(<?php echo $year; ?>, <?php echo $month-1; ?> ,<?php echo $date; ?>), 0, undefined, undefined] 
            
            <?php } ?>
			
        ]);
		
		 var options = {
					width: 630,
					height: 300,
					displayAnnotations : false
					};
		
        var chart = new google.visualization.AnnotatedTimeLine(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
     */


		
  </script>
	  <script>
	  $(document).ready(function(){
		
		
		callAllFunctions();
		setInterval(function(){ checkExchange(); }, 5000);
		/* notCompletedOrderList();
		myOrderListAjax();
		marketHistory();
		getUserBalance();
		getCurrenPrice(); */
		
		/* setInterval(function(){ notCompletedOrderList(); }, 10000);
		setInterval(function(){ myOrderListAjax(); }, 10000);
		setInterval(function(){ marketHistory(); }, 10000);
		setInterval(function(){ getUserBalance(); }, 10000);
		setInterval(function(){ getCurrenPrice(); }, 10000);
 */
 <?php if(!empty($authUserId) && $googleAuthVerify=='Y') { ?>
		$('form#buy_form').submit(function(event) {
			
			event.preventDefault(); // Prevent the form from submitting via the browser
			$('form#buy_form [type=submit]').hide();
			var form = $(this);
			var formData = new FormData(this);
			
			// ajax for market History list 
			$.ajax({
				beforeSend : function(){
					$('#show_buy_resp').html("<img src='<?php echo $this->request->webroot ?>ajax-loader.gif' />");
				},
				url : '<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'index',$firstCoin,$secondCoin]); ?>',
				type : 'post',
				data : formData,
				contentType: false,
				//cache: false,
				dataType:'json',
				processData:false,
				success : function(resp){ 
					$('#show_buy_resp').html(resp.message);
					setTimeout(function(){ $('#show_buy_resp').html(''); },7000);
					//call to exchange
					if(resp.error==0){
						callAjaxExchange(formData);
					}
					
				}
			})
			
		});	
		
		
		$('form#sell_form').submit(function(event) {
			
			event.preventDefault(); // Prevent the form from submitting via the browser
			$('form#sell_form [type=submit]').hide();
			var form = $(this);
			var formData = new FormData(this);
			
			// ajax for market History list 
			$.ajax({
				beforeSend : function(){
					$('#show_sell_resp').html("<img src='<?php echo $this->request->webroot ?>ajax-loader.gif' />");
				},
				url : '<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'index',$firstCoin,$secondCoin]); ?>',
				type : 'post',
				data : formData,
				contentType: false,
				//cache: false,
				processData:false,
				dataType:'json',
				success : function(resp){
					$('#show_sell_resp').html(resp.message);
					setTimeout(function(){ $('#show_sell_resp').html('') },7000);
					//call to exchange
					if(resp.error==0){
						callAjaxExchange(formData);
					}
					
				}
			})
			
		});	

 <?php } ?>	
			
		});
	  
	
	
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
	
	
	function callAjaxExchange(formData){
		<?php if(!empty($authUserId) && $googleAuthVerify=='Y') { ?>
			$.ajax({
				url : '<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'exchange',$firstCoin,$secondCoin]); ?>',
				type : 'post',
				data : formData,
				contentType: false,
				//cache: false,
				processData:false,
				success : function(resp){
					clearBuyForm();
					clearSellForm();
					$('form#buy_form [type=submit]').show();
					$('form#sell_form [type=submit]').show();
				}
			});
		<?php } ?>
	}
	
	
	
	// ajax for market History list
	function marketHistory() {
		$.ajax({
			url : '<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'marketHistory',$firstCoinId,$secondCoinId]); ?>',
			type : 'get',
			dataType : 'json',
			success : function(resp){
				// my buyOrderList data
				var html = '';
				if($.isEmptyObject(resp)){
					html = html + '<tr>';
					html = html + "<td colspan=5>No Order found</td>";
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
							var perPrice = (value.get_per_price).toFixed(8);
						}
						else {
							var perPrice = (value.spend_per_price).toFixed(8);
						}
						
						if(value.get_cryptocoin_id==<?php echo $secondCoinId; ?>){
							var sellPurchaseAmt = (value.get_amount).toFixed(8);
						}
						else {
							var sellPurchaseAmt = (value.spend_amount).toFixed(8);
						}
						
						var totalPrice = (sellPurchaseAmt*perPrice).toFixed(8);
						var splitDateTime = value.created_at;
						var splitDateTime = splitDateTime.split("+");
						var getdateTime = splitDateTime[0];
						var getdateTime = getdateTime.replace("T"," ");
						var setColor = (value.extype=="buy") ? "#00b275 " : "#ef5057";
						html = html + '<tr>';
						html = html + '<td>'+getdateTime+'</td>';
						//html = html + '<td>'+ucfirst(value.extype)+'</td>';
						html = html + '<td style="color:'+setColor+'">'+perPrice+'</td>';
						//html = html + '<td>'+sellPurchaseAmt+'</td>';
						html = html + '<td>'+totalPrice+'</td>';
						html = html + '</tr>';
					});
				}
				
				$("#market_history").html(html);
			}
		});
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
	
	
	function myOrderListAjax(){
		<?php if(!empty($authUserId) && $googleAuthVerify=='Y') { ?>
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
					html = html + "<td colspan=4>No Order found</td>";
					html = html + '</tr>';
				}
				else {
					$.each(resp.myBuyOrderList,function(key,value){
						var action = '&nbsp;';
						var showAmount = value.total_buy_get_amount;
						if(value.status=='pending'){
							action = "<a class href='javascript:void(0)' id='buy_"+value.id+"' onClick='deleteOrder(this.id)'>Cancel</a>";
							showAmount = value.buy_get_amount;
						}
						
						html = html + '<tr>';
						html = html + '<td>'+(value.per_price).toFixed(8)+'</td>';
						html = html + '<td>'+(showAmount).toFixed(8)+'</td>';
						html = html + '<td>'+(parseFloat(value.per_price)*parseFloat(showAmount)).toFixed(8)+'</td>';
						html = html + '<td>'+ucfirst(value.status)+'</td>';
						html = html + '<td>'+action+'</td>';
						html = html + '</tr>';
					});
				}
				$("#myBuyOrderlist").html(html);
				
				// my seller order list data
				var html = '';
				if($.isEmptyObject(resp.mySellOrderList)){
					html = html + '<tr>';
					html = html + "<td colspan=4>No Order found</td>";
					html = html + '</tr>';
				}
				else { 
					$.each(resp.mySellOrderList,function(key,value){
							
						var action = '&nbsp;';
						var showAmount = value.total_sell_get_amount;
						if(value.status=='pending'){
							action = "<a href='javascript:void(0)' id='sell_"+value.id+"' onClick='deleteOrder(this.id)'>Cancel</a>";
							showAmount = value.sell_get_amount;
						}
						
						html = html + '<tr>';
						html = html + '<td>'+(value.per_price).toFixed(8)+'</td>';
						html = html + '<td>'+(showAmount/parseFloat(value.per_price)).toFixed(8)+'</td>';
						html = html + '<td>'+parseFloat(showAmount).toFixed(8)+'</td>';
						html = html + '<td>'+ucfirst(value.status)+'</td>';
						html = html + '<td>'+action+'</td>';
						html = html + '</tr>';
					});
				}
				$("#mySellOrderlist").html(html);
			}
		});
		<?php } ?>
	}
	
	function fill_data(getTable,getTableType){
		var fillPerPrice = $(getTable).find("td.fill_per_price").html();
		var fillAmount = $(getTable).find("td.fill_amount").html();
	
		if(getTableType=="buy"){
			$("#sell_volume").val(fillAmount).trigger("input");
			$("#sell_per_price").val(fillPerPrice).trigger("input");
			$("#profile-tab").click();
		}
		if(getTableType=="sell"){
			$("#buy_volume").val(fillAmount).trigger("input");
			$("#buy_per_price").val(fillPerPrice).trigger("input");
			$("#home-tab").click();
		}
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
					html = html + "<td colspan=3>No Order found</td>";
					html = html + '</tr>';
				}
				else {
					$.each(resp.buyOrderList,function(key,value){
							html = html + '<tr onClick="fill_data(this,\'buy\')" style="cursor:pointer;">';
							html = html + '<td class="fill_per_price">'+(value.per_price).toFixed(8)+'</td>';
							html = html + '<td class="fill_amount">'+value.sum+'</td>';
							html = html + '<td>'+(parseFloat(value.per_price)*parseFloat(value.sum)).toFixed(8)+'</td>';
							html = html + '</tr>';
					});
					
				} 
				$("#buyAjaxData").html(html);
				
				// add data to sell table
				
				var html = '';
				if($.isEmptyObject(resp.sellOrderList)){
					html = html + '<tr>';
					html = html + "<td colspan=3>No Order found</td>";
					html = html + '</tr>';
				}
				else {
					$.each(resp.sellOrderList,function(key,value){
							
							html = html + '<tr onClick="fill_data(this,\'sell\')" style="cursor:pointer;">';
							html = html + '<td class="fill_per_price">'+(value.per_price).toFixed(8)+'</td>';
							html = html + '<td class="fill_amount">'+value.sum+'</td>';
							html = html + '<td>'+(parseFloat(value.per_price)*parseFloat(value.sum)).toFixed(8)+'</td>';
							html = html + '</tr>';
							
							
					});
					
				}
				$("#sellAjaxData").html(html);
			}
		});
		
		
	}
	
	// ajax for user balance
	function getUserBalance() {
		$.ajax({
			url : '<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'getUserBalance',$firstCoinId,$secondCoinId]); ?>',
			type : 'get',
			dataType : 'json',
			success : function(resp){
				$("#span_buy_volume_all").val(resp.firstCoinBalance);
				$("#span_sell_volume_all").val(resp.secondCoinBalance);
			}
		});
	}
	
	var symbol = '';
	<?php if($firstCoinId == 5) { ?>
	 symbol = ' $';
	<?php } ?>
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
					returnPrice = parseFloat(returnPrice).toFixed(8);
					var currentPriceInUsd = returnPrice*<?php echo $baseCoinPriceInUsd; ?>;
					currentPriceInUsd = parseFloat(currentPriceInUsd).toFixed(8);
					$("#current_price").html(returnPrice);
					var setHtml = (resp.goto=="down") ? " <i class='fa fa-arrow-down' style='color:#ef5057'></i>" :"<i class='fa fa-arrow-up' style='color:#7ac162;'></i>"
					$("#middle_current_price").html(returnPrice+setHtml);
					
					$("#current_price_<?php echo $firstCoin."_".$secondCoin; ?>").html(returnPrice+symbol);
					$("#current_price_usd").html(currentPriceInUsd);
				}
				$("#change_in_one_day").html(resp.change_in_one_day+"%");
				
				// for curren volume
				if($.isEmptyObject(resp.current_volume)){
					$("#current_volume").html('0.00000000');
				}
				else {
					var returnVolume = parseFloat(resp.current_volume).toFixed(8);
					$("#current_volume").html(returnVolume);
					
				}
				
				
				// for min price
				if($.isEmptyObject(resp.min_price)){
					$("#min_price").html('0.00000000');
				}
				else {
					var minPrice = parseFloat(resp.min_price).toFixed(8);
					$("#min_price").html(minPrice);
					
				}
				
				
				// for max price
				if($.isEmptyObject(resp.max_price)){
					$("#max_price").html('0.00000000');
				}
				else {
					var maxPrice = parseFloat(resp.max_price).toFixed(8);
					$("#max_price").html(maxPrice);
					
				}
			}
		});
	}	
	
	
	function deleteOrder(getId){
		<?php if(!empty($authUserId) && $googleAuthVerify=='Y') { ?>
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
	 

	function callAllFunctions() {
		notCompletedOrderList();
		myOrderListAjax();
		marketHistory();
		getUserBalance();
		getCurrenPrice();
	}		
	
	  
	  </script>
	  
      <!-- Orders Book -->
      
	  
	 
	  
        <div class="col-md-4" style="height:764px;">
          <div class="panel panel-default" style="height:100%;">
            <div class="panel-heading heading2 ">Orders Book  </div>
            <div class="panel-wrapper collapse in" aria-expanded="true" style="">
              
              <div class="panel-body">
                <div class="row">
                  <table class="table table-striped table-hover table-condensed table-responsive">
                        <thead>
                          <tr>
                            <th> Price(<?php echo $secondCoin; ?>) </th>
							<th> Amount(<?php echo $secondCoin; ?>)  </th>
                            <th class="tr"> Amount(<?php echo $firstCoin; ?>)  </th>
                          </tr>
                        </thead>
                        
                      </table>
				   <div class="col-md-12 col-sm-12" style="background-color:#257a94;padding:8px;height:310px;" >
                    
					<div class="table-responsive" style="overflow-y:auto;max-height:400px;">
					
                      <table class="table table-striped table-hover table-condensed">
                       
                        <tbody id="sellAjaxData">
                           <tr>
                            <td class="number" colspan='2'><img src="<?php echo $this->request->webroot ?>ajax-loader.gif" /></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    
                   <!-- <div class="float-right">
                     View All
                    </div>-->
                  </div>
				  
				 <div class="col-md-12 col-sm-12" id="middle_current_price" style="padding:4px;text-align:center;font-size:20px;width:93%;border:1px solid #ddd;margin:12px;">
				 0.401210
				 </div>
                 
				  
				  
				    <div class="col-md-12 col-sm-12" style="background-color:#65508c;margin-top:0px;padding:8px;height:320px;">
					 
                    <div class="table-responsive" style="overflow-y:auto;max-height:400px;">
					
                      <table class="table table-striped table-hover table-condensed table-responsive">
                        
                        <tbody id="buyAjaxData">
                          <tr>
                            <td class="number" colspan='2'><img src="<?php echo $this->request->webroot ?>ajax-loader.gif" /></td>
                          </tr>
                          
                        </tbody>
                      </table>
                    </div>
                  
                    <!--<div class="float-right">
                     View All
                    </div>-->
                  </div>
				  
				  
                </div>
              </div>
            </div>
          </div>
        </div>
		
		
		 <!-- My Buy Order list -->
        <div class="col-md-12">
          <div class="panel panel-default">
            <div class="panel-heading heading2">My Buy Orders </div>
            <div class="panel-wrapper collapse in" aria-expanded="true" style="">
             <div class="panel-body">
              <div class="table-responsive" >
                <table class="table table-striped table-hover table-condensed">
                  <thead>
                    <tr>
                       <th>Price Per <?php echo $secondCoin; ?></th>
                      <th><?php echo $secondCoin; ?> Amount</th>
					  <th><?php echo $firstCoin; ?> Amount</th>
                      <th>Status</th>
                      <th class="tr"><i class="fa fa-times"></i></th>
                    </tr>
                  </thead>
                  <tbody id="mySellOrderlist">
                    <tr> 
                      <td colspan=5 style="text-align:center;">
						  <?php if(!empty($authUserId) && $googleAuthVerify=='Y') { ?>
						  <img src="<?php echo $this->request->webroot ?>ajax-loader.gif" />
						  <?php }else { ?> 
						  No Order Found
						  <?php } ?>
					  </td>
                     
                    </tr>
                  </tbody>
                </table>
              </div>
			    <?php if(!empty($authUserId) && $googleAuthVerify=='Y') { ?>
			  <div class="float-right">
                    <a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'mybuyorderlist',$firstCoin,$secondCoin]) ?>" > View All</a>
                </div>
				<?php } ?>
              </div>
              
            </div>
          </div>
        </div>
		
		
        <!-- My Sell Order list -->
        <div class="col-md-12">
          <div class="panel panel-default ">
            <div class="panel-heading heading2">My Sell Orders  </div>
            <div class="panel-wrapper collapse in" aria-expanded="true" style="">
            <div class="panel-body">
              <div class="table-responsive">
                <table class="table table-striped table-hover table-condensed">
                  <thead>
                    <tr>
                      <th>Price Per <?php echo $secondCoin; ?></th>
                      <th><?php echo $secondCoin; ?> Amount</th>
					  <th><?php echo $firstCoin; ?> Amount</th>
                      <th>Status</th>
                      <th class="tr"><i class="fa fa-times"></i></th>
                    </tr>
                  </thead>
                  <tbody id="myBuyOrderlist">
                    <tr>
                      <td colspan=5 style="text-align:center;">
					  <?php if(!empty($authUserId) && $googleAuthVerify=='Y') { ?>
					  <img src="<?php echo $this->request->webroot ?>ajax-loader.gif" />
					  <?php }else { ?> 
					  No Order Found
					  <?php } ?>
					  </td>
                     
                    </tr>
                  </tbody>
                </table>
              </div>
			    <?php if(!empty($authUserId) && $googleAuthVerify=='Y') { ?>
				<div class="float-right">
                    <a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'mysellorderlist',$firstCoin,$secondCoin]) ?>" > View All</a>
                </div>
				<?php } ?>
			  
             </div>
            </div>
          </div>
        </div>
		 

      </div>
	  </div>

    </div>
	  </div>
	<?php
		//if(in_array($authUserId,[10003090,10003992])){
	?>
	<script>
	$(document).ready(function(){
		
		$('#start_date').datepicker({format: 'yyyy-mm-dd'});
		$('#end_date').datepicker({format: 'yyyy-mm-dd'});
		
		$.ajax({
		url:"<?php echo $this->Url->build(['controller'=>'pages','action'=>'getcurrentpricehome','prefix'=>false]) ?>",
		type:'POST',
		dataType:'JSON',
		success:function(resp){
			$("#btc_inr_home").html(Math.round(1/resp.data.btc_per_inr));
			$("#eth_inr_home").html(Math.round(1/resp.data.eth_per_inr));
			$("#ltc_inr_home").html(Math.round(1/resp.data.ltc_per_inr));
			$("#xrp_inr_home").html(Math.round(1/resp.data.xrp_per_inr));
			$("#bch_inr_home").html(Math.round(1/resp.data.bch_per_inr));
		}
	});
		
	});
	</script>
	<?php //}
	?>

    <!-- FOOTER -->
    <?php echo $this->element('Front/footer'); ?>
    <!-- end FOOTER --> 


