

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
            <h3><?php echo $totalUsers; ?></h3>
            <p>Total Users</p>
          </div>
          <div class="icon"> <i class="fa fa-users" style="color:#fff;font-size:65px"></i> </div>
          <a href="<?php echo $this->Url->build(['controller'=>'reports','action'=>'users']);  ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
      </div>
      
      
	  <div class="col-lg-3 col-xs-6"> 
        <!-- small box -->
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3><?php echo $totalCoins; ?></h3>
            <p>Total Coins</p>
          </div>
          <div class="icon"> <i><img src="<?php echo $this->request->webroot;?>css/Admin/bower_components/dist/img/money.png"></i> </div>
          <a href="<?php echo $this->Url->build(['controller'=>'Coin','action'=>'index']);  ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
      </div>
    
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6"> 
        <!-- small box -->
        <div class="small-box bg-red">
          <div class="inner">
            <h3><?php echo (int)$totalCoinPairs; ?></h3>
            <p>Total Coin Pairs</p>
          </div>
          <div class="icon"> <i><img src="<?php echo $this->request->webroot;?>css/Admin/bower_components/dist/img/icon1.png"></i> </div>
          <a href="<?php echo $this->Url->build(['controller'=>'Coin','action'=>'coinpairIndex']);  ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
      </div>
	  
	  <!-- ./col -->
      <div class="col-lg-3 col-xs-6"> 
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner">
            <h3><?php echo $totalExchange; ?></h3>
            <p>Total Exchange Records</p>
          </div>
          <div class="icon"> <i><img src="<?php echo $this->request->webroot;?>css/Admin/bower_components/dist/img/B-coin.png"></i> </div>
          <a href="<?php echo $this->Url->build(['controller'=>'reports','action'=>'report','BTC']);  ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
      </div>
     
      <div class="clearfix"></div>
      
      </div>
      
      <div class="row">
                  
                <div class="col-md-6">
                	<div class="button_set_one three one agile_info_shadow">
						<h3 class="w3_inner_tittle two" align="center">Total BTC </h3>
						<h4 class="text-center"><?php echo $totalBtcAmt; ?></h4>
					</div>
					<div class="clearfix"></div>
   
				
				<div class="clearfix"></div>

				</div>
				
			<div class="col-md-6">
                	<div class="button_set_one three one agile_info_shadow">
						<h3 class="w3_inner_tittle two" align="center">Total ETH </h3>
						<h4 class="text-center"><?php echo $totalEthAmt; ?></h4>
					</div>
					<div class="clearfix"></div>
   
				
				<div class="clearfix"></div>

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
