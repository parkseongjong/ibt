<?php $getBalanceOfRealToken = json_decode($getBalanceOfRealToken,true); ?>

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
      <div class="row">
      <?php if (in_array('all',$accessModulesRecords) || in_array('Reports',$accessModulesRecords)) { ?>	
      <div class="col-lg-3 col-xs-6"> 
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3><?php echo 500000000; ?></h3>
            <p><?php echo $coinNameStatic; ?></p>
          </div>
          <div class="icon"> <i><img src="<?php echo $this->request->webroot;?>assets/hedge/images/logo2-small.png"></i> </div>
          <a href="<?php echo $this->Url->build(['controller'=>'reports','action'=>'report','Galaxy']);  ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
      </div>
      
      
	  <div class="col-lg-3 col-xs-6"> 
        <!-- small box -->
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3><?php echo 500000000-(int)$getCompletedAgcCoinCount; ?></h3>
            <p>Avaliable Coins</p>
          </div>
          <div class="icon"> <i><img src="<?php echo $this->request->webroot;?>css/Admin/bower_components/dist/img/money.png"></i> </div>
          <a href="<?php echo $this->Url->build(['controller'=>'reports','action'=>'report','Galaxy']);  ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
      </div>
    
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6"> 
        <!-- small box -->
        <div class="small-box bg-red">
          <div class="inner">
            <h3><?php echo (int)$getCompletedAgcCoinCount; //$totalAgcSoldCoin; ?></h3>
            <p>Sold Coins</p>
          </div>
          <div class="icon"> <i><img src="<?php echo $this->request->webroot;?>css/Admin/bower_components/dist/img/icon1.png"></i> </div>
          <a href="<?php echo $this->Url->build(['controller'=>'reports','action'=>'report','Galaxy']);  ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
      </div>
	  
	  <!-- ./col -->
      <div class="col-lg-3 col-xs-6"> 
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner">
            <h3><?php echo number_format((float)$totalCollectBtcCoin,8); ?></h3>
            <p>BTC</p>
          </div>
          <div class="icon"> <i><img src="<?php echo $this->request->webroot;?>css/Admin/bower_components/dist/img/B-coin.png"></i> </div>
          <a href="<?php echo $this->Url->build(['controller'=>'reports','action'=>'report','BTC']);  ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
      </div>
      <?php } ?>
      <div class="clearfix"></div>
      
      </div>
      
      <div class="row">
                  
                <?php if (in_array('all',$accessModulesRecords) || in_array('Galaxy',$accessModulesRecords)) { ?>	
                <div class="col-md-6">
                	<div class="button_set_one three one agile_info_shadow">
                  <h3 class="w3_inner_tittle two" align="center">Your <?php echo $coinNameStatic ?> Wallet</h3>
				    <h5 class="text-center"><a target="_blank" href="https://etherscan.io/address/<?php echo $tokenWalletAddress; ?>"><?php echo $tokenWalletAddress; ?></a> </h5>
                  <h3 class="w3_inner_tittle two" align="center">
                    <li style="list-style:none;"><img src="<?=$this->request->webroot ?>assets/hedge/images/logo2-small.png" style="background-color:#29363e;padding:10px;" alt="HedgeConnect"></li>
                  </h3>
                  <h2 class="w3_inner_tittle two" align="center">HEDGE Balance : <?php echo $getBalanceOfRealToken['hedge']; ?></h2>
                  <h2 class="w3_inner_tittle two" align="center">Ether Balance : <?php echo $getBalanceOfRealToken['ether']; ?></h2>
                  <div align="center">&nbsp;
                    <div class="clearfix"></div>
                  </div>
                  <!-- Standard button -->
                 
                  <div align="center"> <div href="zuobuy.html">
                    
                  
                    <!-- <a href="<?php //echo $this->Url->build(['controller'=>'Transactions','action'=>'send']);  ?>">
                    <button type="button" class="btn btn-primary" style="background:#FF9900"><i class="fa fa-upload" aria-hidden="true"></i> Send</button>
                    </a>-->
                      </div align="center">&nbsp;
                      <div class="clearfix"></div>
                  </div>
                    <div align="center">&nbsp;
                        <div class="clearfix"></div>
                    </div>
                    <div align="center">
                       <!--<a href="<?php //echo $this->Url->build(['controller'=>'Transactions','action'=>'transaction']);  ?>">
                    <button type="button" class="btn btn-primary" style="background:#5E1F00"><i class="fa fa-exchange" aria-hidden="true"></i> Transaction</button>
                    </a>-->
                  </div>
                  </div>
				  
                  </div>
                <?php }if (in_array('all',$accessModulesRecords) || in_array('Btc',$accessModulesRecords)) {  ?>
                  <div class="col-md-6">
                  	<div class="button_set_one three one agile_info_shadow">
                      <!-- Standard button -->
                      <h3 class="w3_inner_tittle two" align="center">Your BTC Wallet</h3>
                      <h3 class="w3_inner_tittle two" align="center">
                          <li class="fa fa-btc"></li>
                      </h3>
                      <h2 class="w3_inner_tittle two" align="center">Balance : <?php echo number_format((float)$totalCollectBtcCoin,8); ?></h2>
                      <div align="center">&nbsp;
                          <div class="clearfix"></div>
                      </div>
                      <!-- Standard button -->
        
                     <!-- <div align="center">
                          <a href="<?php //echo $this->Url->build(['controller'=>'Btc','action'=>'request']);  ?>">
                              <button type="button" class="btn btn-primary" style="background:#006699"><i class="fa fa-download" aria-hidden="true"></i> Requests</button>
                          </a>
                          <a href="<?php //echo $this->Url->build(['controller'=>'Btc','action'=>'send']);  ?>">
                              <button type="button" class="btn btn-primary" style="background:#FF9900"><i class="fa fa-upload" aria-hidden="true"></i> Send</button>
                          </a>
						</div>
						<div align="center">&nbsp;
							<div class="clearfix"></div>
						</div>
						<div align="center">&nbsp;
							<div class="clearfix"></div>
						</div>
						<div align="center"> <a href="<?php //echo $this->Url->build(['controller'=>'Btc','action'=>'transaction']);  ?>">
                              <button type="button" class="btn btn-primary" style="background:#5E1F00"><i class="fa fa-exchange" aria-hidden="true"></i> Transaction</button>
                          </a> 
						</div>-->
                  </div>
                  </div>
                    <?php }?>
                  <div class="clearfix"></div>
   
      </div>
		  
      <div class="row">
		  
			<div class="col-xs-12 pn-h-small mt20">
                    <h4>TODAY'S lOG (top 10)</h4>
                    <div class="row">
                    	<div class="col-md-4  pn">
                      <h6>LOGIN LOG</h6>
                      <div class="table-responsive  before-doc-ready" style="">
                        <div id="bid_datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                          
                          <div class="row">
                            <div class="col-sm-12">
                              <table class="table table-striped table-hover display table-bordered dataTable no-footer" id="bid_datatable" role="grid" style="width: 100%;" width="100%" cellspacing="0">
                                <thead>
                                  <tr role="row">
									  <th>Name</th>
									  <!--<th>Ip Address</th>-->
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
											  <!--td><?=$count?></td-->
											  <td><?php echo $data['user']['name']; ?></td>
											  <!--<td><?php //echo $data['ip_address']; ?></td>-->
											  <td><?php echo $data['date']; ?></td>
										  </tr>
										  <?php $count++;} ?>
									  <?php  if(count($log_records) < 1) {
										  echo "<tr><th colspan = '3'>No record found</th></tr>";
									  } ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          
                        </div>
                      </div>
                    </div>
                    	<div class="col-md-4 pn ">
                      <h6>Pending Transaction</h6>
                      <div class="table-responsive before-doc-ready" style="">
                        <div id="ask_datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                          
                          <div class="row">
                            <div class="col-sm-12">
                              <table class="table table-striped table-hover display table-bordered dataTable no-footer" id="ask_datatable" role="grid" style="width: 100%;" width="100%" cellspacing="0">
                                <thead>
                                  <tr role="row">
                                     <th>Name</th>
									  <th><?php echo $coinNameStatic; ?> Token</th>
									  <th>Type</th>
									  <th>Time</th>
                                  </tr>
                                </thead>
                                <tbody>
									 <?php
									  $count= 1;
									  foreach($lastPendingCoin as $k=>$data){

										  if($k%2==0) $class="odd";
										  else $class="even";
										  ?>
										  <tr class="<?=$class?>">
											  <td><?php echo $data['user']['name']; ?></td>
											  <td><?php echo $data['agc_coins']; ?></td>
											  <td><?php echo $data['trans_type']; ?></td>
											  <td><?php echo $data['created_at']; ?></td>
										  </tr>
										  <?php $count++;} ?>
									  <?php  if(count($lastPendingCoin) < 1) {
										  echo "<tr class='even'><td colspan = '6'>No record found</td></tr>";
									  } ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 pn ">
                      <h6>Completed Transaction</h6>
                      <div class="table-responsive before-doc-ready" style="">
                        <div id="ask_datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                          
                          <div class="row">
                            <div class="col-sm-12">
                              <table class="table table-striped table-hover display table-bordered dataTable no-footer" id="ask_datatable" role="grid" style="width: 100%;" width="100%" cellspacing="0">
                                <thead>
                                  <tr role="row">
                                     <th>Name</th>
									  <th><?php echo $coinNameStatic; ?> Token</th>
									  <th>Type</th>
									  <th>Time</th>
                                  </tr>
                                </thead>
                                <tbody>
									 <?php
									  $count= 1;
									  foreach($lastCompletedCoin as $k=>$data){

										  if($k%2==0) $class="odd";
										  else $class="even";
										  ?>
										  <tr class="<?=$class?>">
											  <td><?php echo $data['user']['name']; ?></td>
											  <td><?php echo $data['agc_coins']; ?></td>
											  <td><?php echo $data['trans_type']; ?></td>
											  <td><?php echo $data['created_at']; ?></td>
										  </tr>
										  <?php $count++;} ?>
									  <?php  if(count($lastCompletedCoin) < 1) {
										  echo "<tr class='even'><td colspan = '6'>No record found</td></tr>";
									  } ?>
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
				 
           
      <div class="clearfix"></div>


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
</script>
