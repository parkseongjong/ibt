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
<style>
.modal { z-index:2000; } 
</style>



  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> My Sell Orders </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active"> My Sell Orders </li>
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
              <h4 class="widget-title"><b> My Sell Orders</b></h4>
            </div>
			<div class="container" style="width:auto;">
			
			<br/>
			<br/>
			<div class="row">
			<?=$this->Flash->render();?>
			<div class="panel-body col-md-12">
			
             
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
		var deleteUrl = baseUrl+"front/transactions/newsellexchange-delete/"+id;
		//alert(deleteUrl);
		window.location.href = deleteUrl
	});
	$("#delete_no").click(function(){
		$("#delete_exchange_id").val('');
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
	});	
	
		
  </script>

