<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Dashboard <small>Control panel</small> </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Dashboard</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
  <!-- Small boxes (Stat box) -->
  <div class="row row-border">
	<div id="changedata">
		<div class="col-md-3">
		  <div class="currency_cont_box">
			<h4>USD</h4>
			<h2><i class="fa fa-dollar"></i> <span class="count" id="header_usd_rate"><?php echo $buyUsd ?></span></h2>
			<small class="green_c" id="usd_rate">-0.51%</small> <span class="bit_price_head"><i class="fa fa-btc"></i> 1.00000000</span> </div>
		</div>
		
		<div class="col-md-3">
		  <div class="currency_cont_box">
			<h4>EUR</h4>
			<h2><i class="fa fa-eur"></i> <span class="count" id="header_usd_rate"><?php echo $buyEur ?></span></h2>
			<small class="green_c" id="eur_rate">-0.51%</small> <span class="bit_price_head"><i class="fa fa-btc"></i> 1.00000000</span> </div>
		</div>
		<div class="col-md-3">
		  <div class="currency_cont_box">
			<h4>GBP</h4>
			<h2><i class="fa fa-gbp"></i> <span class="count" id="header_usd_rate"><?php echo $buyGbp ?></span></h2>
			<small class="green_c" id="eur_rate">-0.51%</small> <span class="bit_price_head"><i class="fa fa-btc"></i> 1.00000000</span> </div>
		</div>
	</div>
    <div class="col-md-3">
      <div class="currency_cont_box">
        <h4>AGC </h4>
        <h2><i class="fa fa-usd"></i> <span class="count" id="header_usd_rate"><?php echo $buyDoller ?> </span></h2>
        <small class="green_c" id="eur_rate"><?php echo  number_format((float)$buyAgc,8); ?></small> <span class="bit_price_head"><i class="fa fa-btc"></i> 1.00000000</span> </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-4 col-xs-6">
      <div class="form-group">
        <div class="small-box bg-aqua">
          <div class="inner">
            <label class="field prepend-icon">Earn by referring new members</label>
            <div class="input-group">
              <input id="referral-link-header"  class="gui-input input-lg form-control" placeholder="Address" name="address" value="<?= BASEURL.$authUser['referral_code']?>" readonly="" type="text">
              <a onclick="copyToClipboard();" class="input-group-addon" title="Copy your referral link"> <i class="fa fa-link copy-link-ref"> Copy</i> </a> </div>
            <span id="link-copied-message" class="btn link-copied-message"></span> </div>
        </div>
      </div>
    </div>
    <div class="col-lg-8">
      <div class="row">
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>
                <?=number_format((float)$getCompletedAgcCoinCount,8);?>
              </h3>
              <p>Your AGC</p>
            </div>
            <div class="icon"> <i><img src="<?php echo $this->request->webroot;?>css/Admin/bower_components/dist/img/z-icon.png"></i> </div>
            <!-- <a href="<?php //echo $this->Url->build(['controller'=>'transactions','action'=>'transaction','Galaxy']);  ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
            <span class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></span> </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>
                <? //=$totalGalaxyCoins - $total_sold ?>
                <?php echo $totalAMXCoin['total_token']; ?></h3>
              <p>Total AGC Tokens</p>
            </div>
            <div class="icon"> <i><img src="<?php echo $this->request->webroot;?>css/Admin/bower_components/dist/img/money.png"></i> </div>
            <a href="javascript:void(0)" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>
                <?php //echo $total_sold;  ?>
                <?php echo $totalAgcSoldCoin; ?></h3>
              <p>Sold AGC Tokens</p>
            </div>
            <div class="icon"> <i><img src="<?php echo $this->request->webroot;?>css/Admin/bower_components/dist/img/icon1.png"></i> </div>
            <a href="javascript:void(0)" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
        </div>
      </div>
    </div>
    <!--<div class="col-lg-3 col-xs-6"> 
       <div class="small-box bg-red">
          <div class="inner">
            <h3><?php //echo $total_sold;  ?> <?php //echo $totalAMXCoin['total_btc']; ?></h3>
            <p>Total BTC Coins</p>
          </div>
          <div class="icon"> <i><img src="<?php //echo $this->request->webroot;?>css/Admin/bower_components/dist/img/icon1.png"></i> </div>
          <a href="javascript:void(0)" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
      </div>-->
    <div class="clearfix"></div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="button_set_one three one agile_info_shadow">
        <h4 class="w3_inner_tittle two"  align="center"><strong style="color:#F90">Wallet Address :</strong>
          <?php echo $currentUserWallet; ?>
          AGC </h4>
		<h2 class="w3_inner_tittle two" align="center"><strong>Balance :</strong>
          <?=number_format((float)$getCompletedAgcCoinCount,8);?>
          AGC </h2>
        <h2 class="w3_inner_tittle two" align="center"><strong>Pending Balance :</strong>
          <?=number_format((float)$getPendingAgcCoinCount,8);?>
          AGC </h2>
        <div align="center">&nbsp;
          <div class="clearfix"></div>
        </div>
        <!-- Standard button -->
      </div>
    </div>
    <div class="col-md-6">
      <div class="button_set_one three one agile_info_shadow">
        <h3 class="w3_inner_tittle two" align="center">Buy AGC</h3>
        <div class="form-body form-body-info"  style="display: inline-block;width: 100%;">
          <?php //echo $this->Form->create('front/transactions/buy/BTC',array('method'=>'post'));?>
          <form method="post" accept-charset="utf-8" action="transactions/buy/BTC">
            <div class="form-group">
            <div class="signArrow"><i class="fa fa-angle-double-left"></i> <i class="fa fa-angle-double-right"></i></div>
            <div class="col-md-6 form-group valid-form">
              <label class="input-group">
                <input onKeyUp="convert_btc(this.value)" id="btc" placeholder="0.0 AGC" class="form-control" name="agc_amount" required  style="border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px" type="text">
                <span id="input-group-addon">AGC</span> </label>
            </div>
            <div class="col-md-6 form-group valid-form">
              <label class="input-group">
                <input onKeyUp="convert_agc(this.value)"  id="btc_value" placeholder="0.0 BTC" class="form-control" name="btc_amount" required  style="border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px" type="text">
                <span id="input-group-addon">BTC</span> </label>
            </div>
            <div class="clearfix"></div>
            <div class="form-group col-md-12">
              <input id="mySubmit" class="btn btn-primary" value="Purchase" type="submit">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="clearfix"></div>
</div>
<div class="row tableDataRow">
  <div class="col-xs-4 pn-h-small mt20">
    <h4>TODAY'S lOG (Top 5)</h4>
    <h6>LOGIN LOG</h6>
    <div class="table-responsive  before-doc-ready" style="">
      <div id="bid_datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
        <table class="table table-striped table-hover display table-bordered dataTable no-footer" id="bid_datatable" role="grid" style="width: 100%;" width="100%" cellspacing="0">
          <thead>
            <tr role="row">
              <th>#</th>
              <th>Ip Address</th>
              <th>Time</th>
            </tr>
          </thead>
          <tbody>
            <?php
									  $count= 1;
									  foreach($log_records as $k=>$data){

										  if($k%2==0) $class="odd";
										  else $class="even";
										  ?>
            <tr class="<?=$class?>">
              <td><?=$count?></td>
              <td><?php echo $data['ip_address']; ?></td>
              <td><?php echo $data['date']; ?></td>
            </tr>
            <?php $count++;} ?>
            <?php  if(count($log_records) < 1) {
										  echo "<tr><th colspan = '6'>No record found</th></tr>";
									  } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-xs-4 pn-h-small mt20">
    <h4>Completed Transactions (Last 5)</h4>
    <h6>Transactions LOG</h6>
    <div class="table-responsive  before-doc-ready" style="">
      <div id="bid_datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
        <table class="table table-striped table-hover display table-bordered dataTable no-footer" id="bid_datatable" role="grid" style="width: 100%;" width="100%" cellspacing="0">
          <thead>
            <tr role="row">
              <th>#</th>
              <th>Agc Token</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php
										if(!empty($lastCompletedCoin)) {
											$count= 1;
											foreach($lastCompletedCoin as $k=>$data){

											if($k%2==0) $class="odd";
											else $class="even";
											?>
            <tr class="<?=$class?>">
              <td><?=$count?></td>
              <td><?php echo $data['agc_coins']; ?></td>
              <td><?php echo date("d/m/Y H:i:s",strtotime($data['created_at'])); ?></td>
            </tr>
            <?php $count++;
										  }
										}
										else {
											echo "<tr><th colspan = '6'>No record found</th></tr>";
										} ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-xs-4 pn-h-small mt20">
    <h4>Pending Transactions (Last 5)</h4>
    <h6>Transactions LOG</h6>
    <div class="table-responsive  before-doc-ready" style="">
      <div id="bid_datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
        <table class="table table-striped table-hover display table-bordered dataTable no-footer" id="bid_datatable" role="grid" style="width: 100%;" width="100%" cellspacing="0">
          <thead>
            <tr role="row">
              <th>#</th>
              <th>Agc Token</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php
									if(!empty($lastPendingCoin)) {
									  $count= 1;
									  foreach($lastPendingCoin as $k=>$data){

										  if($k%2==0) $class="odd";
										  else $class="even";
										  ?>
            <tr class="<?=$class?>">
              <td><?=$count?></td>
              <td><?php echo $data['agc_coins']; ?></td>
              <td><?php echo date("d/m/Y H:i:s",strtotime($data['created_at'])); ?></td>
            </tr>
            <?php $count++;}
									}
									else {
										echo "<tr><th colspan = '6'>No record found</th></tr>";
									} ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="clearfix"></div>
<!--  
      <div class="row">
          	<div class="col-sm-6">
            	 <div class="w3agile-validation w3ls-validation mt20 ">
                      <div class="agile-validation agile_info_shadow">
                          <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                              <div id="chartContainer" style="height: 300px; width: 100%;"></div>
                          </div>
                      </div>
                  </div>
            </div>
            
            <div class="col-sm-6">
            	<div class="w3agile-validation w3ls-validation mt20 ">
                      <div class="agile-validation agile_info_shadow">
                          <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                              <div id="chartContainer1" style="height: 300px; width: 100%;"></div>
                          </div>
                      </div>
                  </div>
            </div>
          </div>-->
</section>
</div>
<!-- /.content-wrapper -->
<!-- /.content-wrapper -->
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>
    window.onload = function () {
        var parse = JSON.parse( '<?php echo $btc_records; ?>');
        var arr1 = [];
        for(var k in parse){
            arr1.push({"x":new Date(parse[k].x),"y":parse[k].y});

        }
        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            theme: "light2",
            title:{
                text: "BTC Conversion"
            },
            axisX:{
                valueFormatString: "DD MMM YY" ,
                labelAngle: -50
            },
            axisY:{
                includeZero: false
            },
            data: [{
                type: "line",
                dataPoints: arr1
            }]
        });
        chart.render();
        var parse = JSON.parse( '<?php echo $galaxy_records; ?>');
        var arr1 = [];
        for(var k in parse){
            arr1.push({"x":new Date(parse[k].x),"y":parse[k].y});

        }
        var chart = new CanvasJS.Chart("chartContainer1", {
            animationEnabled: true,
            theme: "light2",
            title:{
                text: "Galaxy Conversion"
            },
            axisX:{
                valueFormatString: "DD MMM YY" ,
                labelAngle: -50
            },
            axisY:{
                includeZero: false
            },
            data: [{
                type: "line",
                dataPoints: arr1
            }]
        });
        chart.render();

    }
	function getINRvalNew() {
	jQuery.ajax({ 
		url: '<?php echo $this->Url->build(['controller'=>'transactions' , 'action'=>'getINR']);  ?>',
		success: function(data) {
			$("#changedata").html(data);
			//setTimeout(function(){getINRval();}, 30000);
			
			
		}
	});
	}
	setInterval(function(){getINRvalNew();}, 20000);
	$('document').ready(function(){
		getINRvalNew();
	})
	
</script>
<script>
function convert_btc(coin){
	$("#btc_value").val(coin*<?php echo $totalAMXCoin['btc_value'] ?>);
}

function convert_agc(btc_coin){
	$("#btc").val(btc_coin/<?php echo $totalAMXCoin['btc_value'] ?>);
}

function copyToClipboard() {
  var copyText = document.getElementById("referral-link-header");
  copyText.select();
  document.execCommand("Copy");
  alert("Copied.");
}
</script>
