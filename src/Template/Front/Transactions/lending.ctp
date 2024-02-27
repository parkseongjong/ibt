<div class="content-wrapper dashboardCon">
<?php 
$curerntDate = time(); 
$launchDate = strtotime("2018-02-16 13:30:00");
if($userId==372) {
	$launchDate = strtotime("2018-02-16 08:00:00");
}
if($launchDate > $curerntDate) { 
?>
  <div class="outerTempCon">
    <div class="innerTempCon text-center">
      <h3>Thanks for Joining Hedgeconnect.co! </h3>
      <br />
      <p>This functionality currently unavailable.</p>
    </div>
  </div>
<?php
} ?>

<?php 

$LchTime = strtotime("2018-02-16 13:30:00"); 

$getDiff = $LchTime - $curerntDate;
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
		
		if(days==0 && hours==0 && minutes==0 && seconds==0){
			location.reload(); 
		}
		
        //display.text(days+"d "+hours+"h "+minutes + "m " + seconds+"s are left");
		display.text(hours+"h "+minutes + "m " + seconds+"s are left");
		
		
        if (--timer < 0) {
            timer = duration;
        }
    }, 1000);
}

jQuery(function ($) {
    var fiveMinutes = <?php echo $getDiff; ?>,
        display = $('#time');
    startTimer(fiveMinutes, display);
});


  
</script>


  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> BTC <small>Staking packages</small> </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active"> Staking packages</li>
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
              <h4 class="widget-title"><b>Staking packages</b></h4>
            </div>
            <div class="panel-heading">
              <h4 class="panel-title text-center">Staking packages</h4>
            </div>
            <div class="widget-card-content p-b-5 p-t-0">
              <div class="panel-body">
                <div class="row">
                  
                  <div class="col-sm-12">
                    <div class="receive_cont">
					
                      <div class="receive_cont_head">
                        <h4>Investment in HC </h4>
                      </div>
					  
                      <div class="receive_cont_body">
					  <p id="time" style="font-size:40px;color:red;"></p> 
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th>Staking packages Amount</th>
                              <th>Bonus</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>100-1000$</td>
                              <td></td>
                            </tr>
                            <tr>
                              <td>1010-5000$ </td>
                              <td>+.1 daily bonus</td>
                            </tr>
                            <tr>
                              <td>5010-10000$ </td>
                              <td>+.25 daily bonus</td>
                            </tr>
                            <tr>
                              <td>10010 and up </td>
                              <td>+.30 daily bonus</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="clearfix"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="inner_content_w3_agile_info">
      <div class="clearfix"></div>
      <!-- /blank -->
      <div class="blank_w3ls_agile">
        <div class="blank-page agile_info_shadow">
          <div class="widget widget-card dynamic inverse-mode bg-gradient-black">
            <div class="widget-card-content">
              <h4 class="widget-title"><b>Staking packages </b></h4>
            </div>
            <!--<div class="hoc alert alert-info m-r-10 m-l-10"> <a class="close" data-dismiss="alert"></a>The lending amount must be divisble by 10. </div>-->
            <div class="widget-card-content p-b-5">
              <div class="row">
			  <!--<div class="col-md-8 col-xs-12" style="color:red;font-size:20px;font-weight:bold">1 HC = 6$</div>-->
			  <div class="col-md-8 col-xs-12" style="color:red;font-size:20px;font-weight:bold">Internal Exchange relaunch April 15</div>
                <div class="col-md-8 col-xs-12">
				
				<?=$this->Flash->render();?>
				<form id="lending-hbc-form" method="post" accept-charset="utf-8" action="<?php echo $this->Url->build(['prefix'=>'front','controller'=>'transactions','action'=>'lending']);  ?>">
					<!--<input type="hidden" name="csrfmiddlewaretoken" value="mJZaQQmjRH6ZD20K2NMTdS2DHnUcYhnNzZOhdBraK7GJpOLhwMF0DF56CV590Fno">-->
                    <label class="control-label" type="number" >Amount in Dollar<span class="text-danger">*</span></label>
                    <div class="input-group">
                      <input type="text" name="amount" onkeypress="return isNumber(event)" required="" class="form-control" id="id_hoc_deposit"> 
                      <span class="input-group-btn">
						<input id="hbc-max-amount" type="submit" value="Submit" class="btn btn-default">
                      </span> <!--<span class="input-group-addon">USD</span>--> </div>
                    <!--<label class="control-label m-t-10" >HC</label>-->
                   <!-- <div class="input-group">
                      <input id="hbc-input" class="form-control" disabled="disabled">
                      <span class="input-group-addon">HC</span> </div>
                    <input type="hidden" name="form-type" value="hbc-form">
                    <a id="lending-hbc-a" class="btn btn-primary m-b-10 m-t-10" data-toggle="modal" href="#lending-hbc-modal" >Lend HC</a>-->
                  </form>
                </div>
                <!--<div class="col-md-4 col-xs-12"> <a href="#" class="widget btn-block widget-stats bg-blue inverse-mode">
                  <div class="widget-stats-left">
                    <div class="widget-stats-title">HC - Available Balance</div>
                  </div>
                  <div class="widget-stats-right" style="max-width:100%">
                    <div class="widget-stats-value f-s-25"> 0.000 HC </div>
                    <div class="widget-desc">4th January 2018 02:14</div>
                  </div>
                  </a> </div> -->
                <div class="col-md-4 col-xs-12"> <a href="#" class="widget btn-block widget-stats bg-blue inverse-mode">
                  <div class="widget-stats-left">
                    <div class="widget-stats-title">Staking Wallet - Active Investment</div>
                  </div>
                  <div class="widget-stats-right" style="max-width:100%">
                    <div class="widget-stats-value f-s-25"> <?=number_format((float)$getUserTotalInvestmentAmount,2);?> $</div>
                    <!--<div class="widget-desc">4th January 2018 02:14</div>-->
                  </div>
                  </a> </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="inner_content_w3_agile_info">
      <div class="clearfix"></div>
      <!-- /blank -->
      <div class="blank_w3ls_agile">
        <div class="blank-page agile_info_shadow">
          <div class="panel panel-inverse endless_page_template">
            <div class="widget-card-content">
              <h4 class="widget-title"><b>Staking HISTORY</b></h4>
            </div>
            <div class="panel-body">
              <p class="desc">Navigate through your Staking packages</p>
              <div class="table-responsive">
                <table class="table table-inverse m-b-0">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>AMOUNT ($)</th>
                      <th>Remark</th>
					  <th>Reserve Days</th>
					 <th>Remaining Reserve Days</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
						$count= 1;
						$cudate = date("Y-m-d H:i:s");	
						$cudateStr = time();
						foreach($listing->toArray() as $k=>$data){
							
							if($k%2==0) $class="odd";
							else $class="even";
						
						$enddate = strtotime($data['created_at']. ' + '.$data['amount_reserve_days'].' days');
						
						$dateDiffInDays = ($enddate - $cudateStr)/(60*60*24);
						$dateDiffInDays = (int)abs($dateDiffInDays);		
							
					?>
                    <tr class="<?=$class?>">
                      <td><?=$count?></td>
                      <td><?=number_format((float)$data['amount'],2);?></td>
                      <td><?php echo $data['type']; ?></td>
                      <td><?php echo $data['amount_reserve_days']; ?></td>
                     <td><?php echo $dateDiffInDays; ?></td>
                      <td><?php echo date("Y-m-d H:i:s",strtotime($data['created_at'])); ?></td>
                    </tr>
                    <?php $count++; } ?>
                    <?php 	if(count($listing->toArray()) < 1) {
								echo "<tr class='even'><td colspan = '4'>No record found</td></tr>";
							}
					?>
                  </tbody>
                </table>
				
				<?php $this->Paginator->options(array('url' => array('controller' => 'Transactions', 'action' => 'lending_search')));
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

						echo "</div>";
								
				?> 
				
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <br />
    <br />
  </section>
</div>
<script>  
$(window).load(function(){
                $('#onload').modal('show');
				
            });
function getConvert(frm,getval,dollerPerBtc=<?php echo $buyUsd ?>,dollerPerCoin=<?php echo $coinPrice ?>){
	jQuery.ajax({ 
		url: '<?php echo $this->Url->build(['controller'=>'transactions' , 'action'=>'getconvert']);  ?>/'+frm+"/"+getval+"/"+dollerPerBtc+"/"+dollerPerCoin,
		success: function(data) {
			var data = $.parseJSON(data);
			$("#usd").val(data.doller);
			$("#coin_val").val(data.coin);
			$("#btc_val").val(data.btc);
		}
	});
}	


function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}		

  jQuery('.table-responsive').on('click','.pagination li a',function(event){
			event.preventDefault() ;
			
			var keyy = $('form').serialize();
			var urli = jQuery(this).attr('href');
			jQuery.ajax({ 
					url: urli,
					data: {key:keyy},
					type: 'POST',
					success: function(data) {
						if(data){
							
							jQuery('.table-responsive').html(data);
							
						}
					}
			});
			
		});
</script>
