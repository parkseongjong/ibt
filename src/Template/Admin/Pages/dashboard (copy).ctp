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
      
      <div class="col-lg-3 col-xs-6"> 
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3><?php echo $setting['zuo_amount']; ?></h3>
            <p>Galaxy COIN</p>
          </div>
          <div class="icon"> <i><img src="<?php echo $this->request->webroot;?>css/Admin/bower_components/dist/img/z-icon.png"></i> </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6"> 
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner">
            <h3><?php echo $setting['btc_amount']; ?></h3>
            <p>BTC</p>
          </div>
          <div class="icon"> <i><img src="<?php echo $this->request->webroot;?>css/Admin/bower_components/dist/img/B-coin.png"></i> </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6"> 
        <!-- small box -->
        <div class="small-box bg-purple">
          <div class="inner">
            <h3><?php echo $setting['usd_amount']; ?></h3>
            <p>USD Coins</p>
          </div>
          <div class="icon"> <i><img src="<?php echo $this->request->webroot;?>css/Admin/bower_components/dist/img/usd.png"></i> </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6"> 
        <!-- small box -->
        <div class="small-box bg-teal">
          <div class="inner">
            <h3><?php echo $setting['eth_amount']; ?></h3>
            <p>ETH Coins</p>
          </div>
          <div class="icon"> <i><img src="<?php echo $this->request->webroot;?>css/Admin/bower_components/dist/img/eth-coin.png"></i> </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6"> 
        <!-- small box -->
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3><?php $total = 0;
                $total = $setting['zuo_amount']+$setting['usd_amount']+$setting['btc_amount']+$setting['eth_amount'];
                echo $total;
            ?></h3>
            <p>Avaliable Coins</p>
          </div>
          <div class="icon"> <i><img src="<?php echo $this->request->webroot;?>css/Admin/bower_components/dist/img/money.png"></i> </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6"> 
        <!-- small box -->
        <div class="small-box bg-red">
          <div class="inner">
            <h3><?php echo $totalSoldCoins; ?></h3>
            <p>Sold Coins</p>
          </div>
          <div class="icon"> <i><img src="<?php echo $this->request->webroot;?>css/Admin/bower_components/dist/img/icon1.png"></i> </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
      </div>
    
      <div class="clearfix"></div>
      
      <div class="buttons_w3ls_agile">
        <div class="col-md-6 button_set_one three one agile_info_shadow">
          <h3 class="w3_inner_tittle two" align="center">Your Galaxy Wallet</h3>
          <h3 class="w3_inner_tittle two" align="center">
            <li style="list-style:none;"><img src="<?php echo $this->request->webroot;?>css/Admin/bower_components/dist/img/zcash-i.png"></li>
          </h3>
          <h2 class="w3_inner_tittle two" align="center">Balance : <?php echo $setting['zuo_amount']; ?></h2>
          <div align="center">&nbsp;
            <div class="clearfix"></div>
          </div>
          <!-- Standard button -->
          
          <div align="center"> <a href="zuobuy.html">
            <a href="<?php echo $this->Url->build(['controller'=>'Transactions','action'=>'request','ZUO']);  ?>">
            <button type="button" class="btn btn-primary" style="background:#006699"><i class="fa fa-download" aria-hidden="true"></i> Requests</button>
            </a> <a href="<?php echo $this->Url->build(['controller'=>'Transactions','action'=>'send','ZUO']);  ?>">
            <button type="button" class="btn btn-primary" style="background:#FF9900"><i class="fa fa-upload" aria-hidden="true"></i> Send</button>
            </a> 
          </div>
          <div align="center">&nbsp;
            <div class="clearfix"></div>
          </div>
          <div align="center">&nbsp;
            <div class="clearfix"></div>
          </div>
          <div align="center"> <a href="zuotransaction.html">
            <button type="button" class="btn btn-primary" style="background:#5E1F00"><i class="fa fa-exchange" aria-hidden="true"></i> Transaction</button>
            </a> </div>
        </div>
        
        <div class="col-md-6 button_set_one three one agile_info_shadow"> 
          <!-- Standard button -->
          <h3 class="w3_inner_tittle two" align="center">Your BTC Wallet</h3>
          <h3 class="w3_inner_tittle two" align="center">
            <li class="fa fa-btc"></li>
          </h3>
          <h2 class="w3_inner_tittle two" align="center">Balance : <?php echo $setting['btc_amount']; ?></h2>
          <div align="center">&nbsp;
            <div class="clearfix"></div>
          </div>
          <!-- Standard button -->
          
          <div align="center"> <a href="<?php echo $this->Url->build(['controller'=>'Transactions','action'=>'request','BTC']);  ?>">
            <button type="button" class="btn btn-primary" style="background:#006699"><i class="fa fa-download" aria-hidden="true"></i> Requests</button>
            </a> <a href="<?php echo $this->Url->build(['controller'=>'Transactions','action'=>'send','BTC']);  ?>">
            <button type="button" class="btn btn-primary" style="background:#FF9900"><i class="fa fa-upload" aria-hidden="true"></i> Send</button>
            </a> </div>
          <div align="center">&nbsp;
            <div class="clearfix"></div>
          </div>
          <div align="center">&nbsp;
            <div class="clearfix"></div>
          </div>
          <div align="center"> <a href="zuotransaction.html">
            <button type="button" class="btn btn-primary" style="background:#5E1F00"><i class="fa fa-exchange" aria-hidden="true"></i> Transaction</button>
            </a> </div>
        </div>
        <div class="clearfix"></div>
      </div>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
      <div class=""> 
      
        <!-- Left col -->
        <section class="col-lg-7 connectedSortable mt20"> 
          <!-- Custom tabs (Charts with tabs)-->
          <div class="nav-tabs-custom"> 
            <!-- Tabs within a box -->
              <canvas id="myChart" width="400" height="400"></canvas>
          </div>
            <?php //pr($months);die;?>
          <!-- /.nav-tabs-custom -->
            <script>
               // var ctx = document.getElementById("myChart").getContext('2d');
                var canvas = document.getElementById("myChart");
                var ctx = canvas.getContext('2d');

                // Global Options:
                Chart.defaults.global.defaultFontColor = 'black';
                Chart.defaults.global.defaultFontSize = 16;

                var data = {
                    labels: [<?php echo implode(',',$months_zuo); ?>],
                    datasets: [{
                        label: "ZUO",
                        fill: false,
                        lineTension: 0.1,
                        backgroundColor: "rgba(225,0,0,0.4)",
                        borderColor: "red", // The main line color
                        borderCapStyle: 'square',
                        borderDash: [], // try [5, 15] for instance
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "black",
                        pointBackgroundColor: "white",
                        pointBorderWidth: 1,
                        pointHoverRadius: 8,
                        pointHoverBackgroundColor: "yellow",
                        pointHoverBorderColor: "brown",
                        pointHoverBorderWidth: 2,
                        pointRadius: 4,
                        pointHitRadius: 10,
                        // notice the gap in the data and the spanGaps: true
                        data: [<?php echo implode(',',$amt_zuo); ?>],
                        spanGaps: true,
                    },
                        {
                        label: "BTC",
                        fill: true,
                        lineTension: 0.1,
                        backgroundColor: "rgba(167,105,0,0.4)",
                        borderColor: "rgb(167, 105, 0)",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "white",
                        pointBackgroundColor: "black",
                        pointBorderWidth: 1,
                        pointHoverRadius: 8,
                        pointHoverBackgroundColor: "brown",
                        pointHoverBorderColor: "yellow",
                        pointHoverBorderWidth: 2,
                        pointRadius: 4,
                        pointHitRadius: 10,
                        // notice the gap in the data and the spanGaps: false
                        data: [<?php echo implode(',',$amt_btc); ?>],
                        spanGaps: false,
                    }]
                };

                // Notice the scaleLabel at the same level as Ticks
                var options = {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Sales',
                                fontSize: 20
                            }
                        }]
                    }
                };

                // Chart declaration:
                var myBarChart = new Chart(ctx, {
                    type: 'line',
                    data: data,
                    options: options
                });
            </script>
        </section>
        <!-- /.Left col --> 
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-5 connectedSortable mt20"> 
          
          <!-- Map box --> 
          
          <!-- /.box --> 
          
          <!-- solid sales graph -->
          <div class="box box-solid bg-teal-gradient">
            <div class="box-header"> <i class="fa fa-th"></i>
              <h3 class="box-title">Sales Graph</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i> </button>
                <button type="button" class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i> </button>
              </div>
            </div>
            <div class="box-body border-radius-none">
              <div class="chart" id="line-chart" style="height: 250px;"></div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer no-border">
              <div class="row">
                <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
                  <input type="text" class="knob" data-readonly="true" value="20" data-width="60" data-height="60"
                         data-fgColor="#39CCCC">
                  <div class="knob-label">Mail-Orders</div>
                </div>
                <!-- ./col -->
                <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
                  <input type="text" class="knob" data-readonly="true" value="50" data-width="60" data-height="60"
                         data-fgColor="#39CCCC">
                  <div class="knob-label">Online</div>
                </div>
                <!-- ./col -->
                <div class="col-xs-4 text-center">
                  <input type="text" class="knob" data-readonly="true" value="30" data-width="60" data-height="60"
                         data-fgColor="#39CCCC">
                  <div class="knob-label">In-Store</div>
                </div>
                <!-- ./col --> 
              </div>
              <!-- /.row --> 
            </div>
            <!-- /.box-footer --> 
          </div>
          <!-- /.box --> 
          
          <!-- Calendar --> 
          
          <!-- /.box --> 
          
        </section>
        <!-- right col --> 
        
      </div>
     
      </div>
      
      
      
      
    </section>
    <!-- /.content --> 

  <!-- /.control-sidebar --> 
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
  <!-- /.content-wrapper -->
  
