<div class="content-wrapper dashboardCon">
<?php 
$curerntDate = time(); 

$launchDate = strtotime("2018-01-20 13:30:00");
$LchTime = strtotime("2018-02-13 13:30:00"); 

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
		
		if(days==0 && hours==0 && minutes==0 && seconds==0){
			location.reload(); 
		}
		
        display.text(days+"d "+hours+"h "+minutes + "m " + seconds+"s are left");

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


<script>
/* 
var country = '<?php echo $mycontry ?>';
if(country == "IN") {
// Set the date we're counting down to
var countDownDate = new Date("Jan 30, 2018 13:30:00").getTime();
}

if(country == "CA") {
// Set the date we're counting down to
var countDownDate = new Date("Jan 30, 2018 03:00:00").getTime();
}

if(country == "US") {
// Set the date we're counting down to
var countDownDate = new Date("Jan 30, 2018 12:00:00").getTime();
}
// Update the count down every 1 second
var x = setInterval(function() {

    // Get todays date and time
    var now = new Date().getTime();
    
    // Find the distance between now an the count down date
    var distance = countDownDate - now;
    
    // Time calculations for days, hours, minutes and seconds
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
	if(hours<10){ var hours = "0"+hours; }
	
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    if(minutes<10){ var minutes = "0"+minutes; }
	var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    if(seconds<10){ var seconds = "0"+seconds; }
    // Output the result in an element with id="demo"
    document.getElementById("demo").innerHTML =  hours + " : "
    + minutes + " : " + seconds ;
    
    // If the count down is over, write some text 
    if (distance < 0) {
        clearInterval(x);
        document.getElementById("demo").innerHTML = "";
    }
}, 1000); */
</script>



  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> BTC <small>ICO </small> </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active"> ICO </li>
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
              <h4 class="widget-title"><b>ICO</b></h4>
            </div>
            
            <div class="widget-card-content p-b-5 p-t-0">
              <div class="panel-body">
                <div class="row">
				<?=$this->Flash->render();?>
                  <div class="col-md-6">
                    <div class="receive_cont">
                      <div class="receive_cont_head">
                        <h4>Buy HC </h4>
                      </div>
                      <div class="receive_cont_body">
                        <p id="time" style="font-size:40px;color:red;"></p>
						<div class="form-body form-body-info" >
						
						 
                          <?php //echo $this->Form->create('front/transactions/buy/BTC',array('method'=>'post'));?>
                          <form method="post" accept-charset="utf-8" action="<?php echo $this->Url->build(['prefix'=>'front','controller'=>'transactions' , 'action'=>'transaction','purchase']);  ?>">
						  <input type="hidden" name="type" value="no"/>
                            <!--<div class="form-group valid-form">
                              <div class="input-group"> <span class="input-group-addon">USD</span>
                                <input onKeyUp="getConvert('doller',this.value)" autocomplete="off" id="usd" placeholder="0.0 USD" class="form-control" name="usd_amount" required  type="text">
                              </div>
                            </div>
                            <div class="signArrow"><i class="fa fa-angle-double-left"></i> <i class="fa fa-angle-double-right"></i></div>-->
                            <div class="form-group valid-form">
                              <div class="input-group"> <span class="input-group-addon">HC</span>
                                <input onKeyUp="getConvert('coin',this.value)" autocomplete="off" id="coin_val" placeholder="0.0 HC" class="form-control" name="coin_amount" required  type="text">
                              </div>
                            </div>
                            <div class="signArrow"><i class="fa fa-angle-double-left"></i> <i class="fa fa-angle-double-right"></i></div>
                            <div class="form-group valid-form">
                              <div class="input-group"> <span class="input-group-addon">BTC</span>
                                <input onKeyUp="getConvert('btc',this.value)" autocomplete="off"  id="btc_val" placeholder="0.0 BTC" class="form-control" name="btc_amount" required  type="text">
                              </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group btn-group">
                              <input id="mySubmit" class="btn btn-primary" value="Purchase" type="submit">
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
				  
				  	
				<!--<div class="col-md-4">
				  <div class="receive_cont">
					<div class="receive_cont_head">
					  <h4>Buy Now (Bulk Buying) </h4>	
					</div>
					<div class="receive_cont_body">
					  <div class="form-body form-body-info" >
					  <p>We’d like to release 500,000 coins. users can buy coins anytime for 5$ each. </p>
						<?php //echo $this->Form->create('front/transactions/buy/BTC',array('method'=>'post'));?>
						<form method="post" accept-charset="utf-8" action="<?php //echo $this->Url->build(['prefix'=>'front','controller'=>'transactions' , 'action'=>'bulktransaction','purchase']);  ?>">
						<input type="hidden" name="type" value="no"/>
					  
						  <div class="form-group valid-form">
							<div class="input-group"> <span class="input-group-addon">HC</span>
							  <input  autocomplete="off" onKeyUp="getBulkConvert('coin',this.value)" id="bulk_coin_val" placeholder="0.0 HC"  class="form-control" name="bulk_coin_amount"  type="text">
							</div>
						  </div>
						  <div class="signArrow"><i class="fa fa-angle-double-left"></i> <i class="fa fa-angle-double-right"></i></div>
						  <div class="form-group valid-form">
							<div class="input-group"> <span class="input-group-addon">BTC</span> 
							  <input autocomplete="off" onKeyUp="getBulkConvert('btc',this.value)"  id="bulk_btc_val" placeholder="0.0 BTC" class="form-control" name="bulk_btc_amount" required  type="text">
							</div>
						  </div>
						  <div class="clearfix"></div>
						  <div class="form-group btn-group">
							<input id="mySubmit" class="btn btn-primary" value="Buy Now" type="submit">
						  </div>
						</form>
					  </div>
					</div>
				  </div>
				</div>-->
				  
                  <div class="col-md-6 col-xs-12"> <a href="#" class="widget btn-block widget-stats bg-blue inverse-mode">
                  <div class="widget-stats-left">
                    <div class="widget-stats-title">HC - Available Balance</div>
                  </div>
                  <div class="widget-stats-right" style="max-width:100%">
                    <div class="widget-stats-value f-s-25">  <?=number_format((float)$getUserTotalCoinCount,8);?>  <?php echo $coinNameStatic ?> </div>
                    <!--<div class="widget-desc">4th January 2018 02:14</div>-->
                  </div>
                  </a> </div>
				  
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
          <div class="panel panel-inverse endless_page_template">
            <div class="widget-card-content">
              <h4 class="widget-title"><b> HISTORY</b></h4>
            </div>
            <div class="panel-body">
              <p class="desc">Navigate through your ico history</p>
              <div class="table-responsive">
                <table class="table table-inverse m-b-0">
                  <thead>
                    <tr>
                      <th>S&nbsp;No.</th>
                      <th><?php echo $coinNameStatic ?> Tokens</th>
                      <th>Remark</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
					
										$count= 1;
											
										 foreach($listing->toArray() as $k=>$data){
											
											if($k%2==0) $class="odd";
											else $class="even";
										?>
                    <tr class="<?=$class?>">
                      <td><?=$count?></td>
                      <td><?=number_format((float)$data['coin'],8);?></td>
                      <td><?php echo str_replace("_"," ",ucfirst($data['type'])); ?></td>
                      <td><?=$data['created_at']->format('d M Y H:i:s');?></td>
                    </tr>
                    <?php $count++; } ?>
                    <?php  if(count($listing->toArray()) < 1) {
											echo "<tr class='even'><td colspan = '6'>No record found</td></tr>";
									   } ?>
                  </tbody>
				  
				  
                  <tbody>
                  </tbody>
                </table>
				
				  <?php $this->Paginator->options(array('url' => array('controller' => 'Transactions', 'action' => 'ico_search')));
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
	
	if(frm=="coin"){
		var doller = getval*dollerPerCoin;
		var btcVal = doller/dollerPerBtc;
		var n = btcVal.toFixed(8);
		$("#btc_val").val(n);
	}
	if(frm=="btc"){
		var doller = getval*dollerPerBtc;
		var coinVal = doller/dollerPerCoin;
		var n = coinVal.toFixed(8);
		$("#coin_val").val(n);
	}
	//$("#usd").attr("disabled",true);
	/* $("#coin_val").attr("disabled",true);
	$("#btc_val").attr("disabled",true);
	jQuery.ajax({ 
		url: '<?php echo $this->Url->build(['controller'=>'transactions' , 'action'=>'getconvert']);  ?>/'+frm+"/"+getval+"/"+dollerPerBtc+"/"+dollerPerCoin,
		success: function(data) {
			var data = $.parseJSON(data);
			if(frm=="coin"){
				$("#btc_val").val(data.btc);
			}
			if(frm=="btc"){
				$("#coin_val").val(data.coin);
			}
			$("#coin_val").attr("disabled",false);
			$("#btc_val").attr("disabled",false);
		}
	}); */
}		

function getBulkConvert(frm,getval,dollerPerBtc=<?php echo $buyUsd ?>,dollerPerCoin=<?php echo $bulkPerCoinRate ?>){
	
	
	if(frm=="coin"){
		var doller = getval*dollerPerCoin;
		var btcVal = doller/dollerPerBtc;
		var n = btcVal.toFixed(8);
		$("#bulk_btc_val").val(n);
	}
	if(frm=="btc"){
		var doller = getval*dollerPerBtc;
		var coinVal = doller/dollerPerCoin;
		var n = coinVal.toFixed(8);
		$("#bulk_coin_val").val(n);
	}

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

