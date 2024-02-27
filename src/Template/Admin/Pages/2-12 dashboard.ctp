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
            <h3><?php echo $totalGalaxyCoins; ?></h3>
            <p>Galaxy COIN</p>
          </div>
          <div class="icon"> <i><img src="<?php echo $this->request->webroot;?>css/Admin/bower_components/dist/img/z-icon.png"></i> </div>
          <a href="<?php echo $this->Url->build(['controller'=>'reports','action'=>'report','Galaxy']);  ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6"> 
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner">
            <h3><?php echo $totalBtcCoins; ?></h3>
            <p>BTC</p>
          </div>
          <div class="icon"> <i><img src="<?php echo $this->request->webroot;?>css/Admin/bower_components/dist/img/B-coin.png"></i> </div>
          <a href="<?php echo $this->Url->build(['controller'=>'reports','action'=>'report','BTC']);  ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
      </div>
      
	  <div class="col-lg-3 col-xs-6"> 
        <!-- small box -->
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3><?=$totalGalaxyCoins - $total_sold?></h3>
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
            <h3><?php echo $total_sold; ?></h3>
            <p>Sold Coins</p>
          </div>
          <div class="icon"> <i><img src="<?php echo $this->request->webroot;?>css/Admin/bower_components/dist/img/icon1.png"></i> </div>
          <a href="<?php echo $this->Url->build(['controller'=>'reports','action'=>'report','Galaxy']);  ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
      </div>
    <?php } ?>
      <div class="clearfix"></div>
      
      <div class="buttons_w3ls_agile">
		  
		<?php if (in_array('all',$accessModulesRecords) || in_array('Galaxy',$accessModulesRecords)) { ?>	
        <div class="col-md-6 button_set_one three one agile_info_shadow">
          <h3 class="w3_inner_tittle two" align="center">Your Galaxy Wallet</h3>
          <h3 class="w3_inner_tittle two" align="center">
            <li style="list-style:none;"><img src="<?php echo $this->request->webroot;?>css/Admin/bower_components/dist/img/zcash-i.png"></li>
          </h3>
          <h2 class="w3_inner_tittle two" align="center">Balance : <?php echo $totalGalaxyCoins; ?></h2>
          <div align="center">&nbsp;
            <div class="clearfix"></div>
          </div>
          <!-- Standard button -->
         
          <div align="center"> <div href="zuobuy.html">
            
          
             <a href="<?php echo $this->Url->build(['controller'=>'Galaxy','action'=>'send']);  ?>">
            <button type="button" class="btn btn-primary" style="background:#FF9900"><i class="fa fa-upload" aria-hidden="true"></i> Send</button>
            </a>
              </div align="center">&nbsp;
              <div class="clearfix"></div>
          </div>
            <div align="center">&nbsp;
                <div class="clearfix"></div>
            </div>
            <div align="center">
               <a href="<?php echo $this->Url->build(['controller'=>'Galaxy','action'=>'transaction']);  ?>">
            <button type="button" class="btn btn-primary" style="background:#5E1F00"><i class="fa fa-exchange" aria-hidden="true"></i> Transaction</button>
            </a>
          </div>
          </div>
		<?php }if (in_array('all',$accessModulesRecords) || in_array('Btc',$accessModulesRecords)) {  ?>
          <div class="col-md-6 button_set_one three one agile_info_shadow">
              <!-- Standard button -->
              <h3 class="w3_inner_tittle two" align="center">Your BTC Wallet</h3>
              <h3 class="w3_inner_tittle two" align="center">
                  <li class="fa fa-btc"></li>
              </h3>
              <h2 class="w3_inner_tittle two" align="center">Balance : <?php echo $totalBtcCoins; ?></h2>
              <div align="center">&nbsp;
                  <div class="clearfix"></div>
              </div>
              <!-- Standard button -->

              <div align="center">
                  <a href="<?php echo $this->Url->build(['controller'=>'Btc','action'=>'request']);  ?>">
                      <button type="button" class="btn btn-primary" style="background:#006699"><i class="fa fa-download" aria-hidden="true"></i> Requests</button>
                  </a>
                  <a href="<?php echo $this->Url->build(['controller'=>'Btc','action'=>'send']);  ?>">
                      <button type="button" class="btn btn-primary" style="background:#FF9900"><i class="fa fa-upload" aria-hidden="true"></i> Send</button>
                  </a>
              </div>
              <div align="center">&nbsp;
                  <div class="clearfix"></div>
              </div>
              <div align="center">&nbsp;
                  <div class="clearfix"></div>
              </div>
              <div align="center"> <a href="<?php echo $this->Url->build(['controller'=>'Btc','action'=>'transaction']);  ?>">
                      <button type="button" class="btn btn-primary" style="background:#5E1F00"><i class="fa fa-exchange" aria-hidden="true"></i> Transaction</button>
                  </a> </div>
          </div>
			<?php }?>
          <div class="clearfix"></div>
          <div class="w3agile-validation w3ls-validation mt20 col-md-4">

              <div class="agile-validation agile_info_shadow">
                  <h3 class="text-center">TODAY LOGS</h3>
              <div class="table-responsive">

                  <table id="table-two-axis" class="two-axis table">
                      <thead>
                      <tr>
                          <!--th>#</th-->
                          <th>Name</th>
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
                              <!--td><?=$count?></td-->
                              <td><?php echo $data['user']['name']; ?></td>
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
          <div class="w3agile-validation w3ls-validation mt20 col-md-4">

              <div class="agile-validation agile_info_shadow">
                  <h3 class="text-center">BTC </h3>
                  <div class="table-responsive">

                      <table id="table-two-axis" class="two-axis table">
                          <thead>
                          <tr>
                              <!--th>#</th-->
                              <th>Name</th>
                              <th>Trans. id</th>
                              <th>Coin</th>
                              <th>Time</th>
                          </tr>
                          </thead>
                          <tbody>
                          <?php
                          $count= 1;
                          foreach($btc_listing as $k=>$data){

                              if($k%2==0) $class="odd";
                              else $class="even";
                              ?>
                              <tr class="<?=$class?>">
                                  <!--td><?=$count?></td-->
                                  <td><?php echo $data['user']['name']; ?></td>
                                  <td><?php echo $data['transaction_id']; ?></td>
                                  <td><?php echo $data['amount']; ?></td>
                                  <td><?php echo $data['date']; ?></td>
                              </tr>
                              <?php $count++;} ?>
                          <?php  if(count($btc_listing) < 1) {
                              echo "<tr class='even'><td colspan = '6'>No record found</td></tr>";
                          } ?>
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
          <div class="w3agile-validation w3ls-validation mt20 col-md-4">

              <div class="agile-validation agile_info_shadow">
                  <h3 class="text-center">GALAXY </h3>
                  <div class="table-responsive">

                      <table id="table-two-axis" class="two-axis table">
                          <thead>
                          <tr>
                              <!--th>#</th-->
                              <th>Name</th>
                              <th>Coin</th>
                              <th>Time</th>
                          </tr>
                          </thead>
                          <tbody>
                          <?php
                          $count= 1;
                          foreach($galaxy_listing as $k=>$data){

                              if($k%2==0) $class="odd";
                              else $class="even";
                              ?>
                              <tr class="<?=$class?>">
                                  <!--td><?=$count?></td-->
                                  <td><?php echo $data['user']['name']; ?></td>
                                  <td><?php echo $data['amount']; ?></td>
                                  <td><?php echo $data['date']; ?></td>
                              </tr>
                              <?php $count++;} ?>
                          <?php  if(count($galaxy_listing) < 1) {
                              echo "<tr class='even'><td colspan = '6'>No record found</td></tr>";
                          } ?>
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
          <div class="clearfix"></div>
          <div class="w3agile-validation w3ls-validation mt20 ">
              <div class="agile-validation agile_info_shadow">
                  <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                      <div id="chartContainer" style="height: 300px; width: 100%;"></div>
                  </div>
              </div>
          </div>
          <div class="clearfix"></div>
          <div class="w3agile-validation w3ls-validation mt20 ">
              <div class="agile-validation agile_info_shadow">
                  <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                      <div id="chartContainer1" style="height: 300px; width: 100%;"></div>
                  </div>
              </div>
          </div>          <div align="center">&nbsp;
            <div class="clearfix"></div>
          </div>
          <div align="center">  </div>
        </div>
        

      </div>
        </section>
        <!-- /.Left col --> 
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <!-- right col --> 
        
      </div>
     
      </div>
      
      
      
      
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
