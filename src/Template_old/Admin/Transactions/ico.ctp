<div class="content-wrapper dashboardCon">
<?php 
$curerntDate = time(); 
$launchDate = strtotime("2018-01-20 13:30:00");
if($launchDate > $curerntDate) { 
?>
<script>
$('document').ready(function(){
 $('#usd').bind("paste",function(e) {
     e.preventDefault();
 });
 
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
<?php } ?>
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
                 
                  <div class="col-md-4 col-xs-12"> 
					  <a href="#" class="widget btn-block widget-stats bg-blue inverse-mode">
					  <div class="widget-stats-left">
						<div class="widget-stats-title">HC - Purchase Balance</div>
					  </div>
					  <div class="widget-stats-right" style="max-width:100%">
						<div class="widget-stats-value f-s-25">  <?php echo (int)$getUserTotalCoinCount;?>  <?php echo $coinNameStatic ?> </div>
						<!--<div class="widget-desc">4th January 2018 02:14</div>-->
					  </div>
					  </a>
				  </div>
				  
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
					  <th>Username</th>
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
					  <td><?php echo $data['user']['username'] ?></td>
                      <td><?php echo number_format((float)abs($data['coin']),8);?></td>
                      <td><?php echo $data['type']?></td>
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
	$("#usd").attr("disabled",true);
	$("#coin_val").attr("disabled",true);
	$("#btc_val").attr("disabled",true);
	jQuery.ajax({ 
		url: '<?php echo $this->Url->build(['controller'=>'transactions' , 'action'=>'getconvert']);  ?>/'+frm+"/"+getval+"/"+dollerPerBtc+"/"+dollerPerCoin,
		success: function(data) {
			var data = $.parseJSON(data);
			$("#usd").val(data.doller);
			$("#coin_val").val(data.coin);
			$("#btc_val").val(data.btc);
			$("#usd").attr("disabled",false);
			$("#coin_val").attr("disabled",false);
			$("#btc_val").attr("disabled",false);
		}
	});
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

