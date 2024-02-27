

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
            <h3 id="total_users"></h3>
            <p>Total Users</p>
          </div>
          <div class="icon"> <i class="fa fa-users" style="color:#fff;font-size:65px"></i> </div>
          <a href="<?php echo $this->Url->build(['controller'=>'reports','action'=>'users']);  ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
      </div>
      
      
	  <div class="col-lg-3 col-xs-6"> 
        <!-- small box -->
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3 id="total_coins"></h3>
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
            <h3  id="total_coin_pairs"></h3>
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
            <h3 id="total_exchange"></h3>
            <p>Total Exchange Records</p>
          </div>
          <div class="icon"> <i><img src="<?php echo $this->request->webroot;?>css/Admin/bower_components/dist/img/B-coin.png"></i> </div>
          <a href="<?php echo $this->Url->build(['controller'=>'Exchange','action'=>'transaction']);  ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
      </div>
     
      <div class="clearfix"></div>
      
      </div>
      
      <div class="row" id="showCoinsTotal">
            
				
				
    
		</div>
	</section>

</div>
  <!-- /.content-wrapper -->



<script>
  $(document).ready(function(){
	  $.ajax({
		  url:'<?php echo $this->Url->build(["controller"=>"Pages","action"=>"dashboardajax"]) ?>',
		  type:"GET",
		  dataType:"JSON",
		  success : function(resp){
			  var html = "";
			  $.each(resp,function(getKey,getVal){
				  console.log(resp);
				 html = html+' <a href="<?php echo $this->Url->build(["controller"=>"Reports","action"=>"usercoinbalance"]); ?>/'+getVal.id+'">';
                html = html+'<div class="col-md-3">';
                html = html+'	<div class="button_set_one three one agile_info_shadow">';
				html = html+'	<h3 class="w3_inner_tittle two" align="center">Total '+getVal.short_name+' </h3>';
				html = html+'		<h4 class="text-center">'+getVal.total_sum+'</h4>';
				html = html+'	</div>';
				html = html+'	<div class="clearfix"></div>';
				html = html+'<div class="clearfix"></div>';
				html = html+'</div>';
				html = html+'</a>';
			  });
			  $("#showCoinsTotal").html(html);
		  }
	  }) 
	  
	  $.ajax({
		  url:'<?php echo $this->Url->build(["controller"=>"Pages","action"=>"dashboardtotalajax"]) ?>',
		  type:"GET",
		  dataType:"JSON",
		  success : function(resp){
			 $("#total_users").html(resp.total_users);
			 $("#total_coins").html(resp.total_coins);
			 $("#total_coin_pairs").html(resp.total_coin_pairs);
			 $("#total_exchange").html(resp.total_exchange);
			
			  
		  }
	  })
  })
</script>
