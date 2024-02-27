<div class="content-wrapper dashboardCon" style="z-index:1080">
<?php 
$curerntDate = time(); 

$launchDate = strtotime("2018-01-20 13:30:00");
$LchTime = strtotime("2018-03-05 13:30:00"); 

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

        display.text(hours+"h "+minutes + "m " + seconds+"s are left");

        if (--timer < 0) {
            timer = duration;
			$("#time").hide();
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
    <h1> My Orders </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active"> My Orders </li>
    </ol>
  </section>
  <!-- Main content -->
  <section id="content" class="table-layout">
    
 <div class="inner_content_w3_agile_info">
      <div class="clearfix"></div>
      <!-- /blank -->
	  <?php if($getDiff<0 && $otherUsers=="no"){ ?>
	
      <div class="blank_w3ls_agile">
        <div class="blank-page agile_info_shadow">
          <div class="panel panel-inverse endless_page_template">
            <div class="widget-card-content">
              <h4 class="widget-title"><b> My Buy/Sell Orders</b></h4>
            </div>
			<div class="container">
			<div class="row">
            <div class="panel-body col-md-10">
			<?=$this->Flash->render();?>
              <h4 class="desc">My Buy Order</h4>
              <div class="table-responsive" id="my_buy_table_list">
                <table class="table table-inverse m-b-0">
                  <thead>
                    <tr>
                      <th>Sr. No.</th>
                      <th>VOLUME(BTC)</th>
                      <th>RATE(BTC)</th>
                      <th>VOLUME(Hed)</th>
					  <th>Status</th>
					  <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
					
						$count= 1;
							
						 foreach($myBuyListing->toArray() as $k=>$data){
							
							if($k%2==0) $class="odd";
							else $class="even";
					?>
                    <tr class="<?=$class?>">
                      <td><?php echo $count; ?></td>
                      <td><?php echo number_format((float)$data['buy_btc_amount'],8);?></td>
                      <td><?php echo number_format((float)$data['price_per_hc'],8);?></td>
                      <td><?php echo number_format((float)$data['buy_hc_amount'],8);?></td>
					  <td><?php echo ucfirst(str_replace("_"," ",$data['status']));?></td>
					   <td>
						<?php if($data['status']=="pending") {  ?>
						<a href="javascript:void(0);" onClick="deletePopup(<?php echo $data['id']; ?>);"><i class="fa fa-trash"></i></a>
						<?php } else { ?>
						No Action
						<?php  } ?>
						</td>
                    </tr>
                    <?php $count++; } ?>
                    <?php  if(count($myBuyListing->toArray()) < 1) {
							echo "<tr class='even'><td colspan = '6'>No record found</td></tr>";
					   } ?>
                  </tbody>
				  
				  
                  <tbody>
                  </tbody>
                </table>
				
				  <?php $this->Paginator->options(array('url' => array('controller' => 'Transactions', 'action' => 'newmy_buy_exchange_search')));
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
			<br/>
			<br/>
			<div class="row">
			
			<div class="panel-body col-md-10">
              <h4 class="desc">My Sell Order</h4>
              <div class="table-responsive" id="my_sell_table_list">
                <table class="table table-inverse m-b-0">
                  <thead>
                    <tr>
                      <th>Sr. No.</th>
                      <th>VOLUME(BTC)</th>
                      <th>RATE(BTC)</th>
                      <th>VOLUME(Hed)</th>
                      <th>Status</th>
					  <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
					
						$count= 1;
							
						 foreach($mySellListing->toArray() as $k=>$data){
							
							if($k%2==0) $class="odd";
							else $class="even";
					?>
                    <tr class="<?=$class?>">
                      <td><?php echo $count; ?></td>
                      <td><?php echo number_format((float)$data['sell_btc_amount'],8);?></td>
                      <td><?php echo number_format((float)$data['price_per_hc'],8);?></td>
                      <td><?php echo number_format((float)$data['sell_hc_amount'],8);?></td>
                      <td><?php echo ucfirst(str_replace("_"," ",$data['status']));?></td>
					  <td>
						<?php if($data['status']=="pending") {  ?>
						<a class="btn btn-danger" href="javascript:void(0);" onClick="deletePopup(<?php echo $data['id']; ?>);"><i class="fa fa-trash"></i></a>
						<?php } else { ?>
						&nbsp;
						<?php  } ?>
						</td>
                    </tr>
                    <?php $count++; } ?>
                    <?php  if(count($mySellListing->toArray()) < 1) {
							echo "<tr class='even'><td colspan = '6'>No record found</td></tr>";
					   } ?>
                  </tbody>
				  
				  
                  <tbody>
                  </tbody>
                </table>
				
				  <?php $this->Paginator->options(array('url' => array('controller' => 'Transactions', 'action' => 'newmy_sell_exchange_search')));
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
        </div>
      </div>
	  <?php } ?>
	  
	  
	  
    </div>
	
<input type="hidden" name="delete_exchange_id" id="delete_exchange_id">
<!-- Modal -->
  <div class="modal fade" id="myDeleteModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Alert</h4>
        </div>
        <div class="modal-body">
          <p>Do you really Want to delete this ?</p>
        </div>
        <div class="modal-footer">
		<button type="button" class="btn btn-default" id="delete_yes" data-dismiss="modal">Yes</button>
          <button type="button" class="btn btn-default" id="delete_no" data-dismiss="modal">No</button>
        </div>
      </div>
      
    </div>
  </div>	
	
	
	
	
  </section>
</div>
<script>  

function deletePopup(id){
	$("#delete_exchange_id").val(id);
	$('#myDeleteModal').modal('show');
}

$('document').ready(function(){
	$("#delete_yes").click(function(){
		var id = $("#delete_exchange_id").val();
		var getUrl = window.location;
		var baseUrl = getUrl.protocol + "//" + getUrl.host + "/";
		var deleteUrl = baseUrl+"front/transactions/exchange-delete/"+id;
		//window.location.href = deleteUrl
	});
	$("#delete_no").click(function(){
		$("#delete_exchange_id").val('');
	});
});


function plus_sign(id){
		var row = $("#"+id).closest('tr');
		var btc = row.find('td:nth-child(2)').first().text();
		var price_per_hc = row.find('td:nth-child(3)').first().text();
		var hc = row.find('td:nth-child(4)').first().text();
		$("#hc_spend").val(hc).trigger('input');
		$("#price_per_hc_sell").val(price_per_hc).trigger('input');
		$("html, body").animate({scrollTop: 0}, 100);
		$(".sell").click();
}

function minus_sign(id){
	var row = $("#"+id).closest('tr');

	var btc = row.find('td:nth-child(2)').first().text();
	var price_per_hc = row.find('td:nth-child(3)').first().text();
	var hc = row.find('td:nth-child(4)').first().text();
	$("#btc_spend").val(btc).trigger('input');
	$("#price_per_hc").val(price_per_hc).trigger('input');
	$("html, body").animate({scrollTop: 0}, 100);
	$(".buy").click();
}

$(document).ready(function() {

	$("#buy_all").click(function(){
		var getAllBTc = $("#buy_all_btc").val();
		$("#btc_spend").val(getAllBTc).trigger('input');
	});
	
	$("#sell_all").click(function(){
		var getAllBTc = $("#sell_all_hc").val();
		$("#hc_spend").val(getAllBTc).trigger('input');
	});
	
	

	$('form[id="buy_form"]').submit(function() {
		$("#buy_order_img").show();
		$(".buy_order_submit").remove();
	});
	
	$('form[id="sell_form"]').submit(function() {
		$("#sell_order_img").show();
		$(".sell_order_submit").remove();
	});
	
	/* $(".buy_order_submit").click(function(){
		var form = document.getElementById("buy_form");
		form.submit();
		
		
	}); */
	
	/* $(".sell_order_submit").click(function(){
		$("#sell_form").submit();
		$("#sell_order_img").show();
		$(".sell_order_submit").remove();	
	}); */
	
	
	
	
	/* $(".plus_sign").click(function(){
		var row = $(this).closest('tr');

		var btc = row.find('td:nth-child(2)').first().text();
		var price_per_hc = row.find('td:nth-child(3)').first().text();
		var hc = row.find('td:nth-child(4)').first().text();
		$("#hc_spend").val(hc).trigger('input');
		$("#price_per_hc_sell").val(price_per_hc).trigger('input');
		$("html, body").animate({scrollTop: 0}, 100);
		$(".sell").click();
	});
	
	
	
	
	$(".minus_sign").click(function(){
		var row = $(this).closest('tr');

		var btc = row.find('td:nth-child(2)').first().text();
		var price_per_hc = row.find('td:nth-child(3)').first().text();
		var hc = row.find('td:nth-child(4)').first().text();
		$("#btc_spend").val(btc).trigger('input');
		$("#price_per_hc").val(price_per_hc).trigger('input');
		$("html, body").animate({scrollTop: 0}, 100);
		$(".buy").click();
	}); */

/* 	$('body').on('click', '[data-fill-sell]', function (e) {
		e.preventDefault()

		var row = $(this).closest('tr');

		var offer_quantity = row.find('td:nth-child(4)').first().text();
		var price = row.find('td:nth-child(3)').first().text();

		$('a.sell').click();
		$('#S-offer_quantity').val(offer_quantity)
		$('#S-price').val(price).trigger('input')

		$(document).scrollTop($('#create-an-order-panel').offset().top);
	}); */

});

$(window).load(function(){
                $('#onload').modal('show');
				
            });
	
	var buy_fee = 0.25000000;
    var sell_fee = 0.25000000;
	var btc_to_usd = 10103.00000000;	
	
	$('#btc_spend').on('input', function () {
		calculateBuyForm($(this).attr('id'), 'want_quantity')
	})

	$('#price_per_hc').on('input', function () {
		calculateBuyForm($(this).attr('id'), 'want_quantity')
	})

	$('#hc_receive').on('input', function () {
		calculateBuyForm($(this).attr('id'), 'offer_quantity')
	})
	
	
	function calculateBuyForm(thisId, field) {
		var btcSpendVal = $("#btc_spend").val();
		var btcSpendVal = parseFloat(btcSpendVal);
		var pricePerHc = $("#price_per_hc").val();
		var pricePerHc = parseFloat(pricePerHc);
		var hcReceive = $("#hc_receive").val();
		var hcReceive = parseFloat(hcReceive);
		
		if(thisId == "btc_spend" || thisId == "price_per_hc"){
			var btcSpendVal = btcSpendVal.toFixed(8);
			var adminFee = btcSpendVal*(buy_fee/100);
			var adminFee = parseFloat(adminFee); 
			var adminFee = adminFee.toFixed(8);
			var remainingBtcSpend = btcSpendVal-adminFee;
			var remainingBtcSpend = remainingBtcSpend.toFixed(8);
			var hcReceiveAmt = (remainingBtcSpend/pricePerHc).toFixed(8);
			$("#hc_receive").val(hcReceiveAmt);
			var adminFee = parseFloat(adminFee); 
			var adminFee = adminFee.toFixed(8);
			$("#buy_fee").val(adminFee);
		}
		
		if(thisId == "hc_receive"){
			var hcReceiveAmt = hcReceive.toFixed(8);
			var remainingBtcSpend = hcReceive*pricePerHc;
			var remainingBtcSpend = remainingBtcSpend.toFixed(8);
			//var btcSpendVal = (remainingBtcSpend*100)/(100-(buy_fee/100));
			var btcSpendVal =remainingBtcSpend/(1-0.0025);
			//var adminFee = btcSpendVal*(buy_fee/100);
			btcSpendVal = btcSpendVal.toFixed(8);
			$("#btc_spend").val(btcSpendVal);
			var adminFee = btcSpendVal*(buy_fee/100);
			adminFee = adminFee.toFixed(8);
			$("#buy_fee").val(adminFee);
		}
	}		


	$('#hc_spend').on('input', function () {
		calculateSellForm($(this).attr('id'), 'want_quantity')
	})

	$('#price_per_hc_sell').on('input', function () {
		calculateSellForm($(this).attr('id'), 'want_quantity')
	})

	$('#btc_receive').on('input', function () {
		calculateSellForm($(this).attr('id'), 'offer_quantity')
	})
	
	function calculateSellForm(thisId, field) {
		var hcSpendVal = $("#hc_spend").val();
		var hcSpendVal = parseFloat(hcSpendVal);;
		var pricePerHcSell = $("#price_per_hc_sell").val();
		var pricePerHcSell = parseFloat(pricePerHcSell);;
		var BtcReceive = $("#btc_receive").val();
		var BtcReceive = parseFloat(BtcReceive);;
		
		if(thisId == "hc_spend" || thisId == "price_per_hc_sell"){
			var btcReceive = hcSpendVal*pricePerHcSell;
			var btcReceive = btcReceive.toFixed(8);
			var adminFee = btcReceive*(buy_fee/100);
			var adminFee = parseFloat(adminFee); 
			var adminFee = adminFee.toFixed(8);
			var remainingBtcReceive = btcReceive-adminFee;
			var remainingBtcReceive = remainingBtcReceive.toFixed(8);
			$("#btc_receive").val(remainingBtcReceive);
			var adminFee = parseFloat(adminFee); 
			var adminFee = adminFee.toFixed(8);
			$("#sell_fee").val(adminFee);
		}
		
		if(thisId == "btc_receive"){
			
			var remainingBtcReceive = BtcReceive.toFixed(8);
			var adminFee = remainingBtcReceive*(buy_fee/100);
			var btcReceive = parseFloat(remainingBtcReceive)+parseFloat(adminFee);
			var btcReceive = parseFloat(btcReceive);
			var btcReceive = btcReceive.toFixed(8); 
			var pricePerHcSell = pricePerHcSell.toFixed(8);
			var hcSpendVal = btcReceive/pricePerHcSell;
			var hcSpendVal = hcSpendVal.toFixed(8);
			$("#hc_spend").val(hcSpendVal);
			var adminFee = parseFloat(adminFee); 
			var adminFee = adminFee.toFixed(8);
			$("#sell_fee").val(adminFee);
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
	/*jQuery('#buy_table_list').on('click','.pagination li a',function(event){
		event.preventDefault() ;
		
		var keyy = $('form').serialize();
		var urli = jQuery(this).attr('href');
		jQuery.ajax({ 
				url: urli,
				data: {key:keyy},
				type: 'POST',
				success: function(data) {
					if(data){
						
						jQuery('#buy_table_list').html(data);
						
					}
				}
		});
		
	});
	
	jQuery('#sell_table_list').on('click','.pagination li a',function(event){
		event.preventDefault() ;
		
		var keyy = $('form').serialize();
		var urli = jQuery(this).attr('href');
		jQuery.ajax({ 
				url: urli,
				data: {key:keyy},
				type: 'POST',
				success: function(data) {
					if(data){
						
						jQuery('#sell_table_list').html(data);
						
					}
				}
		});
		
	});	*/
	
	
	jQuery('#my_buy_table_list').on('click','.pagination li a',function(event){
		event.preventDefault() ;
		
		var keyy = $('form').serialize();
		var urli = jQuery(this).attr('href');
		jQuery.ajax({ 
				url: urli,
				data: {key:keyy},
				type: 'POST',
				success: function(data) {
					if(data){
						
						jQuery('#my_buy_table_list').html(data);
						
					}
				}
		});
		
	});
	
	jQuery('#my_sell_table_list').on('click','.pagination li a',function(event){
		event.preventDefault() ;
		
		var keyy = $('form').serialize();
		var urli = jQuery(this).attr('href');
		jQuery.ajax({ 
				url: urli,
				data: {key:keyy},
				type: 'POST',
				success: function(data) {
					if(data){
						
						jQuery('#my_sell_table_list').html(data);
						
					}
				}
		});
		
	});	
	
	
		
  </script>

