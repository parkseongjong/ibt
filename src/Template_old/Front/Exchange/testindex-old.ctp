<style>
    .panel-default>.panel-heading{border-color:#216ae5;padding:10px;font-size:larger;border-bottom:1px solid #dcdcdc}#chartdiv{width:100%;height:300px}.pp{padding-right:0;padding-left:0}.buy_value{margin-bottom:20px;display:block}.buy_value .btn{width:80px;border:none;padding:0}.buy_value li{display:inline-block;margin-right:18px}.buy_value li input{border:1px solid #ccc;height:30px;padding-left:5px}.buy_value li label{font-weight:500;margin-right:5px}.col-lg-1,.col-lg-10,.col-lg-11,.col-lg-12,.col-lg-2,.col-lg-3,.col-lg-4,.col-lg-5,.col-lg-6,.col-lg-7,.col-lg-8,.col-lg-9,.col-md-1,.col-md-10,.col-md-11,.col-md-12,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-7,.col-md-8,.col-md-9,.col-sm-1,.col-sm-10,.col-sm-11,.col-sm-12,.col-sm-2,.col-sm-3,.col-sm-4,.col-sm-5,.col-sm-6,.col-sm-7,.col-sm-8,.col-sm-9,.col-xs-1,.col-xs-10,.col-xs-11,.col-xs-12,.col-xs-2,.col-xs-3,.col-xs-4,.col-xs-5,.col-xs-6,.col-xs-7,.col-xs-8,.col-xs-9{position:relative;min-height:1px;padding-right:8px;padding-left:8px}
</style>
<!-- OpenTok -->

<script src="https://static.opentok.com/v2/js/opentok.min.js"></script>
<!-- Polyfill for fetch API so that we can fetch the sessionId and token in IE11 -->
<script src="https://cdn.jsdelivr.net/npm/promise-polyfill@7/dist/polyfill.min.js" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fetch/2.0.3/fetch.min.js" charset="utf-8"></script>

<!-- OpenTok -->

<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script> 
<script src="<?php echo $this->request->webroot ?>datepicker/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
 
</script>
<link rel="stylesheet" href="<?php echo $this->request->webroot ?>datepicker/bootstrap-datepicker.min.css" />
<aside class="aside asideBuySell">
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
            <!--<li class="">

			<?=$this->Flash->render();?>
			
			 <div class="bs-example bs-example-tabs" data-example-id="togglable-tabs">
				<ul id="myTabs" class="nav nav-tabs nav-tabs-noboder minus-margin-tab" role="tablist">
				  <li role="presentation" class="active"><a href="https://xbootstrap.com/demo/crypto/cryptoapp/index-6.html#home" class="tab-link-pad" id="home-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true"><i class="fa fa-gavel"></i> Buy</a></li>
				  <li role="presentation" class=""><a href="https://xbootstrap.com/demo/crypto/cryptoapp/index-6.html#profile" class="tab-link-pad" role="tab" id="profile-tab" data-toggle="tab" aria-controls="profile" aria-expanded="false"><i class="fa fa-bullhorn"></i> Sell</a></li>
				</ul>
				<div id="myTabContent" class="tab-content tab-content-BuySell">
				
				
				  <div role="tabpanel" class="tab-pane fade active in" id="home" aria-labelledby="home-tab">
					<div class="">
					  <div class="panel-heading"> </div>
					  <div class="panel-wrapper collapse in h-auto" aria-expanded="true">
						
					  </div>
					</div>
				  </div>
				</form>
				  
				
					<div role="tabpanel" class="tab-pane fade" id="profile" aria-labelledby="profile-tab">
						<div class="">
						  <div class="panel-heading"> </div>
						  <div class="panel-wrapper collapse in h-auto" aria-expanded="true">
							
							
						  </div>
						</div>
					
					</div>
				
				
				
				</div>
		
		 
		</li>-->
            <div class="panel-wrapper collapse in" aria-expanded="true" style="">
                <div class="panel panel-default">

                    <div class="panel-heading">All Markets</div>
                    <div id="datatable1_wrapper" class="dataTables_wrapper form-inline no-footer">
                        <div class="row">


                        </div>
                        <table id="datatable1" class="table table-striped table-hover dataTable no-footer" role="grid" aria-describedby="datatable1_info">
                            <thead>
                                <tr role="row">
                                    <th class="tableSmallPad sorting_asc" tabindex="0" aria-controls="datatable1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Coin: activate to sort column descending" style="width: 0px;">Coin</th>
                                    <th class="tableSmallPad sorting" tabindex="0" aria-controls="datatable1" rowspan="1" colspan="1" aria-label="Price: activate to sort column ascending" style="width: 0px;">Price</th>


                                </tr>
                            </thead>
                            <tbody>
                                <?php
								
						  foreach($getCoinPairList as $getCoinPairSingle){
							  $color = '';
							if($currentCoinPairDetail['id']==$getCoinPairSingle['id']){
								$color = "style='color:green'";
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
                                            <a style="color:#000;" href="<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'testindex',$getCoinPairSingle['cryptocoin_first']['short_name'],$getCoinPairSingle['cryptocoin_second']['short_name']]); ?>">
                                                <?php echo $getCoinPairSingle['cryptocoin_first']['short_name']."/".$getCoinPairSingle['cryptocoin_second']['short_name']; ?>
                                            </a>
                                        </td>
                                        <td class="tableSmallPad" id="current_price_<?php echo $getCoinPairSingle['cryptocoin_first']['short_name']." _ ".$getCoinPairSingle['cryptocoin_second']['short_name']; ?>">
                                            <?php echo $getMyCustomPrice.$symbol; ?>
                                        </td>


                                    </tr>
                                    <?php } ?>
                            </tbody>
                        </table>

                    </div>
                </div>


            </div>
        </ul>

    </nav>
</aside>


<section>
    <section class="main-content">

        <?php $showLink = $this->Url->build(['controller'=>'pages','action'=>'mywallet']);  ?>

        <div class="right_btn pull-right">

            <!-- <a href="#">History</a>-->

            <a href="<?php echo $showLink; ?>"> Wallet</a>

            <a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'support']) ?>"> Support</a>

        </div>


        <div class="row">
            <div class="col-md-9 mdpart">

                <h4 class="pull-left colorfff">RAM Token </h4>
                <div class=" man_button">
                    <div class="dropdown">
                        <a class="dropdown_tog" type="button" data-toggle="dropdown">Order
  <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'mybuyorderlist',$firstCoin,$secondCoin]) ?>"> Buy Orders</a>
                            </li>
                            <li>
                                <a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'mysellorderlist',$firstCoin,$secondCoin]) ?>">Sell Orders</a>
                            </li>

                        </ul>
                    </div>
                    <div class="dropdown">
                        <a class="dropdown_tog" type="button" data-toggle="dropdown">BTC markets
  <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Coming Soon</a></li>
                            <!--   <li><a href="#">BTC markets</a></li>
    <li><a href="#">BTC markets</a></li>-->
                        </ul>
                    </div>
                    <div class="dropdown">
                        <a class="dropdown_tog" href="<?php echo $this->Url->build(['prefix'=>'front','controller'=>'exchange' , 'action'=>'testindex','USD',$secondCoin]);  ?>" type="button">USD markets
  </a>
                        <!--<ul class="dropdown-menu">
    <li><a href="#">USD markets</a></li>
    <li><a href="#">USD markets</a></li>
    <li><a href="#">3USD markets</a></li>
  </ul>-->
                    </div>
                    <div class="dropdown">
                        <a class="dropdown_tog" href="<?php echo $this->Url->build(['prefix'=>'front','controller'=>'exchange' , 'action'=>'testindex','ETH',$secondCoin]);  ?>" type="button">ETH markets
  </a>
                        <!--<ul class="dropdown-menu">
    <li><a href="#">ETH markets</a></li>
    <li><a href="#">ETH markets</a></li>
    <li><a href="#">ETH markets</a></li>
  </ul>-->
                    </div>
                </div>


                <!-- First Row Starts Here -->
                <!-- <div class="row">
            <div class="col-lg-3 col-sm-6">
              <div data-toggle="play-animation" data-play="fadeInDown" data-offset="0" data-delay="100" class="panel widget anim-running anim-done" style="">
                <div class="panel-body bg-primary boxh1">
                  <div class="row row-table row-flush">
                    <div class="col-xs-12">
                      <p class="mb0">$759,781,417.92 <em class="fa fa-level-up"> </em> </p>
                      <h4 class="m0">Market Cap</h4>
                      <span class="m-t-10"><i class="fa fa-dollar"></i> Total Market Capital </span> </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-sm-6">
              <div data-toggle="play-animation" data-play="fadeInDown" data-offset="0" data-delay="500" class="panel widget anim-running anim-done" style="">
                <div class="panel-body bg-warning  boxh1">
                  <div class="row row-table row-flush">
                    <div class="col-xs-12">
                      <p class="mb0">$1,947,201.24 <em class="fa fa-level-down"></em></p>
                      <h4 class="m0">Trade Volume</h4>
                      <span class="f-left m-t-10"> <i class="fa fa-dollar"></i> 24h Trade Volume </span> </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-sm-6">
              <div data-toggle="play-animation" data-play="fadeInDown" data-offset="0" data-delay="1000" class="panel widget anim-running anim-done" style="">
                <div class="panel-body bg-danger  boxh1">
                  <div class="row row-table row-flush">
                    <div class="col-xs-12">
                      <p class="mb0">32.4 Billions <em class="fa fa-refresh"></em></p>
                      <h4 class="m0">Circ Supply</h4>
                      <span class="m-t-10"> <i class="text-c-green f-16 fa fa-refresh"></i> Circulating Supply </span> </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-sm-6">
              <div data-toggle="play-animation" data-play="fadeInDown" data-offset="0" data-delay="1500" class="panel widget anim-running anim-done" style="">
                <div class="panel-body bg-success  boxh1">
                  <div class="row row-table row-flush">
                    <div class="col-xs-12">
                      <p class="mb0">40 Billions <em class="fa fa-money"></em></p>
                      <h4 class="m0">Total Supply</h4>
                      <span class="f-left m-t-10"> <i class="fa fa-money"></i> Total Supply </span> </div>
                  </div>
                </div>
              </div>
            </div>
          </div>-->

                <!-- Chart Starts Here -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-collapse">
                                <div class="panel-body">
                                    <h4>
                                        <?php echo $firstCoin."-".$secondCoin; ?>
                                    </h4>



                                    <div id="container" style="height: 400px; min-width: 310px"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Chart Ends Here -->

                <div class="row">
                    <div class="col-md-6">
                        <div class="buybtcleft">
                            <form method="post" id="buy_form" onsumit="return false;" accept-charset="utf-8" action="<?php echo $this->Url->build(['prefix'=>'front','controller'=>'exchange' , 'action'=>'index',$firstCoin,$secondCoin]);  ?>">
                                <input type="hidden" name="type" value="buy" />
                                <div class="pannel panel-body">
                                    <div class="input-group col-sm-12 m-b"> <span id="span_buy_volume" class="input-group-addon btn-primary group-btn-hover darkformfield">ALL <?php echo $firstCoin; ?></span>
                                        <input type="text" readonly placeholder="Volume" id="span_buy_volume_all" value="<?php echo $firstCoinSum; ?>" class="form-control text-right">
                                    </div>
                                    <div class="label1">
                                        Amount To Buy
                                    </div>
                                    <div class="input1">
                                        <div class="input-group col-sm-12 m-b"> <span class="input-group-addon btn-primary group-btn-hover darkformfield"><?php echo $secondCoin; ?></span>
                                            <input type="text" placeholder="Volume" autocomplete="false" required id="buy_volume" name="volume" class="form-control text-right">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="label1">
                                        Price Per
                                        <?php echo $secondCoin; ?>
                                    </div>
                                    <div class="input1">
                                        <div class="input-group m-b">
                                            <div class="input-group-btn">
                                                <button type="button" data-toggle="dropdown" class="btn btn-primary form-btn-padding dropdown-toggle"><?php echo $firstCoin; ?> </button>
                                            </div>
                                            <input type="text" class="form-control text-right" required autocomplete="false" id="buy_per_price" name="per_price" placeholder="Price Per <?php echo $secondCoin; ?>">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="label1">
                                        <?php echo $firstCoin; ?> To Spend
                                    </div>
                                    <div class="input1">
                                        <div class="input-group m-b">
                                            <div class="input-group-btn">
                                                <button type="button" data-toggle="dropdown" class="btn btn-primary form-btn-padding dropdown-toggle"><?php echo $firstCoin; ?> </button>
                                            </div>
                                            <input type="text" class="form-control text-right" required autocomplete="false" id="buy_total_amount" placeholder="Total Amount">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="label1">
                                        0.5% Admin Fee
                                    </div>

                                    <div class="input1">
                                        <div class="input-group m-b">
                                            <div class="input-group-btn">
                                                <button type="button" data-toggle="dropdown" class="btn btn-primary form-btn-padding dropdown-toggle"><?php echo $firstCoin; ?> </button>
                                            </div>
                                            <input type="text" class="form-control text-right" autocomplete="false" id="buy_admin_fee" disabled placeholder="0.5% Admin Fee">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>


                                    <div class="m-t-9">
                                        <div id="show_buy_resp"></div>
                                        <input type="submit" class="btn btn-primary btn-block" value="Buy <?php echo $secondCoin; ?>">
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="buybtcleft">
                            <form method="post" id="sell_form" onsumit="return false;" accept-charset="utf-8" action="<?php echo $this->Url->build(['prefix'=>'front','controller'=>'exchange' , 'action'=>'index',$firstCoin,$secondCoin]);  ?>">
                                <input type="hidden" name="type" value="sell" />
                                <div class="pannel panel-body">
                                    <div class="input-group col-sm-12 m-b">
                                        <span id="span_sell_volume" class="input-group-addon btn-primary group-btn-hover darkformfield">ALL <?php echo $secondCoin; ?></span>
                                        <input type="text" readonly placeholder="Volume" id="span_sell_volume_all" value="<?php echo $secondCoinSum; ?>" class="form-control text-right">
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="label1">
                                        Amount To Spend
                                    </div>
                                    <div class="input1">
                                        <div class="input-group col-sm-12 m-b">
                                            <span class="input-group-addon btn-primary group-btn-hover darkformfield"><?php echo $secondCoin; ?></span>
                                            <input type="text" placeholder="Volume" required autocomplete="false" id="sell_volume" name="volume" class="form-control text-right">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="label1">
                                        Price Per
                                        <?php echo $secondCoin; ?>
                                    </div>

                                    <div class="input1">
                                        <div class="input-group m-b">
                                            <div class="input-group-btn">
                                                <button type="button" data-toggle="dropdown" class="btn btn-primary form-btn-padding dropdown-toggle"><?php echo $firstCoin; ?> </button>
                                            </div>
                                            <input type="text" class="form-control text-right" required autocomplete="false" id="sell_per_price" name="per_price" placeholder="Price Per <?php echo $secondCoin; ?>">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="label1">
                                        <?php echo $firstCoin; ?> To Receive
                                    </div>
                                    <div class="input1">
                                        <div class="input-group m-b">
                                            <div class="input-group-btn">
                                                <button type="button" data-toggle="dropdown" class="btn btn-primary form-btn-padding dropdown-toggle"><?php echo $firstCoin; ?> </button>
                                            </div>
                                            <input type="text" class="form-control text-right" required autocomplete="false" id="sell_total_amount" placeholder="Total Amount">
                                        </div>
                                    </div>

                                    <div class="clearfix"></div>

                                    <div class="label1">
                                        0.5% Admin Fee
                                    </div>

                                    <div class="input1">
                                        <div class="input-group m-b">
                                            <div class="input-group-btn">
                                                <button type="button" data-toggle="dropdown" class="btn btn-primary form-btn-padding dropdown-toggle"><?php echo $firstCoin; ?> </button>
                                            </div>
                                            <input type="text" class="form-control text-right" autocomplete="false" id="sell_admin_fee" disabled placeholder="0.5% Admin Fee">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="m-t-9">
                                        <div id="show_sell_resp"></div>
                                        <input type="submit" class="btn btn-primary btn-block" value="Sell <?php echo $secondCoin; ?>">
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">

                            <div class="panel-wrapper collapse in" aria-expanded="true" style="">

                                <div class="panel-body">
                                    <div class="row">

                                        <div class="col-md-4 col-sm-12">
                                            <h4 class="h4color">Buy Order</h4>
                                            <div class="table-responsive" style="overflow-y:auto;max-height:400px;">

                                                <table class="table table-striped table-hover table-condensed table-responsive">
                                                    <thead>
                                                        <tr>
                                                            <th> Price Per
                                                                <?php echo $secondCoin; ?> </th>
                                                            <th>
                                                                <?php echo $secondCoin; ?> Amount </th>
                                                            <th>
                                                                <?php echo $firstCoin; ?> Amount </th>
                                                        </tr>
                                                    </thead>
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



                                        <div class="col-md-4 col-sm-12">
                                            <h4 class="h4color">Sell Order</h4>
                                            <div class="table-responsive" style="overflow-y:auto;max-height:400px;">

                                                <table class="table table-striped table-hover table-condensed">
                                                    <thead>
                                                        <tr>
                                                            <th> Price Per
                                                                <?php echo $secondCoin; ?> </th>
                                                            <th>
                                                                <?php echo $secondCoin; ?> Amount </th>
                                                            <th>
                                                                <?php echo $firstCoin; ?> Amount </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="sellAjaxData">
                                                        <tr>
                                                            <td class="number" colspan='2'><img src="<?php echo $this->request->webroot ?>ajax-loader.gif" /></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <h4 class="h4color">Market History</h4>
                                            <div class="table-responsive" style="overflow-y:auto;max-height:400px;">
                                                <table id="market_history_tbl" class="table table-striped table-hover table-condensed" style="width: 600px;">
                                                    <thead>
                                                        <tr>
                                                            <th>Date</th>
                                                            <th>Type</th>
                                                            <th>Price Per
                                                                <?php echo $secondCoin; ?>
                                                            </th>
                                                            <th>
                                                                <?php echo $secondCoin; ?> Amount</th>
                                                            <th>
                                                                <?php echo $firstCoin; ?> Amount</th>
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
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-lg-3">
                <div class="panel panel-default">
                    <div class="panel-heading heading2 ">Online Chat</div>
                    
	                    
	                    <div id="textchat">
                            
                            <ul class="chat panel-body">
                                
		                       
		                        <!-- <li class="left clearfix">
		                            <div class="chat-body clearfix">
		                                <div class="header">
		                                    <strong class="primary-font"><?php echo $_messages['user_name']; ?></strong> <small class="pull-right text-muted">
		                                        <span class="glyphicon glyphicon-time"></span>12 mins ago</small>
		                                </div>
                                            <p><?php echo $_messages['msg']; ?></p>
                                    </div>
		                        </li> -->
                                <?php foreach ($get_messages as $key => $_messages) { ?>
                                    
                                    <p>
                                        <strong><?php echo $_messages['user_name']; ?>:</strong>
                                        <?php echo $_messages['msg']; ?>
                                        
                                    </p>
                                
                                <?php } ?>
                                 
                                <!-- <li class="right clearfix">
                                    <div class="chat-body clearfix">
                                        <div class="header">
                                            <small class=" text-muted"><span class="glyphicon glyphicon-time"></span>13 mins ago</small>
                                            <strong class="pull-right primary-font">Suresh Sharma</strong>
                                        </div>
                                        <p id="history"></p>
                                    </div>
                                </li> -->
                                <p id="history"></p>
                            </ul>
                        
                        </div>

	                    <div class="panel-footer">
                            
                            <script type="text/javascript">
                                jQuery('#chat_form').keydown(function( e ){
                                    var key = e.which;
                                    
                                    
                                    if( key == 13 ){
                                    
                                        jQuery('#chat_form').submit();
                                    }
                                });

                                $(document).ready(function(){
                                    
                                    $('#msgTxt').click(function(){
                                        $("#msgTxt").val('');
                                    });

                                    $('#btn-chat-send').click(function(){
                                        $("#msgTxt").val('');
                                    });
                                });
                            </script>
	                        <div class="input-group">
	                            
	                            <form id="chat_form" onsubmit="event.preventDefault();">
	                                
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input type="text" class="form-control input-xs" placeholder="Type your message..." id="msgTxt" name="msgTxt"></input>
                                            <input type="hidden" id="sender_id" name="send_name" value="<?php echo $user_name;  ?>">
                                        </div>
                                        <div class="col-md-2">
                                            <span class="input-group-btn">
                                                <button type="submit" hidden="true" onclick="add_message();"  href="javascript:void(0);" class="btn " id="btn-chat-send">
                                                    Send
                                                </button>
                                            </span>
                                        </div>
                                    </div>
	                                
								<form>

	                        </div>
	                    </div>
                </div>

                <a class="twitter-timeline" data-height="570" href="https://twitter.com/liveCryptoEX">Tweets by liveCrypto_exc</a>
                <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

            </div>
        </div>
        <script>
            var input = document.getElementById("msgTxt");
            input.addEventListener("keyup", function(event) {
                event.preventDefault();
            });
        </script>

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
                        text: '<?php echo $secondCoin; ?> Price'
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
            var fee = 0.50000000;
            	$(document).ready(function(){
            		
            		$("#span_buy_volume").click(function(){
            			var getVal = $("#span_buy_volume_all").val();
            			$("#buy_total_amount").val(getVal).change();
            		});
            		
            		$("#span_sell_volume").click(function(){
            			var getVal = $("#span_sell_volume_all").val();
            			$("#sell_total_amount").val(getVal).change();
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
            		setInterval(function(){ getCurrenPrice(); }, 10000); */
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
            					if( resp.error == 0 ){
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
            						
            						html = html + '<tr>';
            						html = html + '<td>'+getdateTime+'</td>';
            						html = html + '<td>'+ucfirst(value.extype)+'</td>';
            						html = html + '<td>'+perPrice+'</td>';
            						html = html + '<td>'+sellPurchaseAmt+'</td>';
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
            							action = "<a href='javascript:void(0)' id='buy_"+value.id+"' onClick='deleteOrder(this.id)'>Delete</a>";
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
            							action = "<a href='javascript:void(0)' id='sell_"+value.id+"' onClick='deleteOrder(this.id)'>Delete</a>";
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
            					var returnPrice = resp.current_price.get_per_price;
            					returnPrice = parseFloat(returnPrice).toFixed(8);
            					var currentPriceInUsd = returnPrice*<?php echo $baseCoinPriceInUsd; ?>;
            					currentPriceInUsd = parseFloat(currentPriceInUsd).toFixed(8);
            					$("#current_price").html(returnPrice);
            					
            					$("#current_price_<?php echo $firstCoin."_".$secondCoin; ?>").html(returnPrice+symbol);
            					$("#current_price_usd").html(currentPriceInUsd);
            				}
            				
            				// for curren volume
            				if($.isEmptyObject(resp.current_volume)){
            					$("#current_volume").html('0.00000000');
            				}
            				else {
            					var returnVolume = parseFloat(resp.current_volume).toFixed(8);
            					$("#current_volume").html(returnVolume);
            					
            				}
            			}
            		});
            	}	
            	
            	
            	function deleteOrder(getId){
            		if(confirm("Are you really want to delete this ?")){
            			$("#"+getId).remove();
            		var splitId = getId.split("_");
            		var tableType = splitId[0]; 
            		var tableId = splitId[1];
            		
            		$.ajax({
            				url : "<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'deleteMyOrder']); ?>/"+tableId+"/"+tableType,
            				type : 'post',
            				dataType : 'json',
            				success : function(resp){
            					$("#"+getId).closest('tr').remove();
            				}
            			});
            		}
            		
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

    </section>
    <?php
		//if(in_array($authUserId,[10003090,10003992])){
	?>
    <script>
        $(document).ready(function(){
        		
        		$('#start_date').datepicker({format: 'yyyy-mm-dd'});
        		$('#end_date').datepicker({format: 'yyyy-mm-dd'});
        		//$('form#volume_form').submit();
        		setTimeout(function(){ $('form#volume_form').submit() }, 2000);
        		$('form#volume_form').submit(function(event) {
        			
        			event.preventDefault(); // Prevent the form from submitting via the browser
        			
        			var form = $(this);
        			var formData = new FormData(this);
        			
        			// ajax for market History list 
        			$.ajax({
        				beforeSend : function(){
        					$('#my_buy_volume').html("<img src='<?php echo $this->request->webroot ?>ajax-loader.gif' />");
        					$('#my_sell_volume').html("<img src='<?php echo $this->request->webroot ?>ajax-loader.gif' />");
        					$('#my_totalsum_volume').html("<img src='<?php echo $this->request->webroot ?>ajax-loader.gif' />");
        				},
        				url : '<?php echo $this->Url->build(['prefix'=>'front','controller'=>'exchange','action'=>'getMyVolume',$firstCoinId,$secondCoinId]);  ?>',
        				type : 'post',
        				data : formData,
        				contentType: false,
        				//cache: false,
        				processData:false,
        				dataType:'json',
        				success : function(resp){
        					$('#my_buy_volume').html(resp.myBuyVolumeSum);
        					$('#my_sell_volume').html(resp.mySellVolumeSum);
        					$('#my_totalsum_volume').html(resp.totalVolumeSum);
        					
        				}
        			})
        			
        		});	
        	});
    </script>
    <?php //} ?>
    <!-- FOOTER -->
    <?php echo $this->element('Front/footer'); ?>
    <!-- end FOOTER -->
</section>
<script type="text/javascript">

/* eslint-disable no-unused-vars */

// Make a copy of this file and save it as config.js (in the js directory).

// Set this to the base URL of your sample server, such as 'https://your-app-name.herokuapp.com'.
// Do not include the trailing slash. See the README for more information:

var SAMPLE_SERVER_BASE_URL = 'http://localhost:8080';

// OR, if you have not set up a web server that runs the learning-opentok-php code,
// set these values to OpenTok API key, a valid session ID, and a token for the session.
// For test purposes, you can obtain these from https://tokbox.com/account.

var API_KEY = '46209892';
var SESSION_ID = '2_MX40NjIwOTg5Mn5-MTU0MzMxNjMzOTUzNn5TVWFrZVdCL21UTldUU1N2Sm9PQ1hpeUZ-fg';
var TOKEN = 'T1==cGFydG5lcl9pZD00NjIwOTg5MiZzaWc9YWU3MWM4MGE5YzgyZjJlYWQyNzIwZjBmZjdlOTY5OGIxY2RmMGY3OTpzZXNzaW9uX2lkPTJfTVg0ME5qSXdPVGc1TW41LU1UVTBNek14TmpNek9UVXpObjVUVldGclpWZENMMjFVVGxkVVUxTjJTbTlQUTFocGVVWi1mZyZjcmVhdGVfdGltZT0xNTQzMzE2Mzg3Jm5vbmNlPTAuMDU4MTQ3NTI0NjI4MDQxMTgmcm9sZT1wdWJsaXNoZXImZXhwaXJlX3RpbWU9MTU0MzkyMTE4NSZpbml0aWFsX2xheW91dF9jbGFzc19saXN0PQ==';


var apiKey;
var session;
var sessionId;
var token;

function initializeSession() {
  session = OT.initSession(apiKey, sessionId);

  // Subscribe to a newly created stream
  session.on('streamCreated', function streamCreated(event) { 
    var subscriberOptions = {
      insertMode: 'append',
      width: '100%',
      height: '100%'
    };
    session.subscribe(event.stream, 'subscriber', subscriberOptions, function callback(error) {
      if (error) {
        console.error('There was an error publishing: ', error.name, error.message);
      }
    });
  });

  session.on('sessionDisconnected', function sessionDisconnected(event) {
    console.error('You were disconnected from the session.', event.reason);
  });

  // Initialize the publisher
  var publisherOptions = {
    insertMode: 'append',
    width: '100%',
    height: '100%'
  };
  var publisher = OT.initPublisher('publisher', publisherOptions, function initCallback(initErr) {
    if (initErr) {
      console.error('There was an error initializing the publisher: ', initErr.name, initErr.message);
      return;
    }
  });

  // Connect to the session
  session.connect(token, function callback(error) {
    // If the connection is successful, initialize a publisher and publish to the session
    if (!error) {
      // If the connection is successful, publish the publisher to the session
      session.publish(publisher, function publishCallback(publishErr) {
        if (publishErr) {
          console.error('There was an error publishing: ', publishErr.name, publishErr.message);
        }
      });
    
    } else {
      	
      	console.error('There was an error connecting to the session: ', error.name, error.message);
    }
  
  });

  // Receive a message and append it to the history
  var msgHistory = document.querySelector('#history');
	  
	  session.on('signal:msg', function signalCallback(event) {
	    var msg = document.createElement('p');
	    msg.textContent = event.data;
        console.log(msg.textContent);
	    
        msg.className = event.from.connectionId === session.connection.connectionId ? 'mine' : 'theirs';
	    msgHistory.appendChild(msg);
	    //msg.scrollIntoView();
	  
      });
}

// Text chat
var form = document.querySelector('form');
//var msgTxt = document.querySelector('#msgTxt');
var msgTxtVal = document.querySelector('#msgTxt');
var senderName = document.querySelector('#sender_id');

// Send a signal once the user enters data in the form
	$("#btn-chat-send").on('click', function () {

		event.preventDefault();

        var message = msgTxtVal.value;
        var sender = senderName.value;

        var msgTxt = sender+' : '+message;

	    session.signal({
	      type: 'msg',
	      data: msgTxt
	    }, 
	    function signalCallback(error) {
	      if (error) {
	        console.error('Error sending signal:', error.name, error.message);
	      } else {
	        msgTxt.value = '';
	      }
	    });
        
	});

    function add_message(){

        var msg = $("#msgTxt").val();
        var sender = $("#sender_id").val();

            $.ajax({
                        type: "POST",
                        url: '<?php echo $this->Url->build(['controller'=>'exchange','action'=>'add_chat_message']); ?>/'+msg+'/'+sender,
                        beforeSend: function (xhr) { xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val()); },
                        success: function(response){
                                                   
                        }
            });
        
        
    }


// See the config.js file.
if (API_KEY && TOKEN && SESSION_ID) {
  apiKey = API_KEY;
  sessionId = SESSION_ID;
  token = TOKEN;
  initializeSession();
} else if (SAMPLE_SERVER_BASE_URL) {
  // Make an Ajax request to get the OpenTok API key, session ID, and token from the server
  fetch(SAMPLE_SERVER_BASE_URL + '/session').then(function fetch(res) {
    return res.json();
  }).then(function fetchJson(json) {
    apiKey = json.apiKey;
    sessionId = json.sessionId;
    token = json.token;

    initializeSession();
  }).catch(function catchErr(error) {
    console.error('There was an error fetching the session information', error.name, error.message);
    alert('Failed to get opentok sessionId and token. Make sure you have updated the config.js file.');
  });
}

</script>