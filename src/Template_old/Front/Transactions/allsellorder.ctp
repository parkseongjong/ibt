<div class="content-wrapper dashboardCon" style="z-index:1080">


<style>
#buy_trade_rate{ font-weight:bold; }
#sell_trade_rate{ font-weight:bold; }
</style>



  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> All Sell Orders </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active"> All Sell Orders </li>
    </ol>
  </section>
  <!-- Main content -->
  <section id="content" class="table-layout">
    
  
    <div class="inner_content_w3_agile_info">
      <div class="clearfix"></div>
      <!-- /blank -->
	  
	  
      <div class="blank_w3ls_agile">
        <div class="blank-page agile_info_shadow">
          <div class="panel panel-inverse endless_page_template">
            <div class="widget-card-content">
              <h4 class="widget-title"><b> All Sell Orders</b></h4>
            </div>
			<div class="containers">
			<div class="row">
			<div class="col-md-12">
            
              <div class="table-responsive" id="sell_table_list">
                <table class="table table-inverse m-b-0">
                  <thead>
                    <tr>
                      <th>Sr No.</th>
                      <th>VOLUME(BTC)</th>
                      <th>RATE(BTC)</th>
                      <th>VOLUME(Hed)</th>
                    </tr>
                  </thead>
                  <tbody id="sell_ajax_data">
                    <?php
					
						$count= 1;
							
						 foreach($sellListing->toArray() as $k=>$data){
							
							if($k%2==0) $class="odd";
							else $class="even";
					?>
                    <tr class="<?php echo $class?>">
                      <td><?php echo $count; ?></td>
                      <td><?php echo number_format((float)$data['sell_btc_amount'],8);?></td>
                      <td><?php echo number_format((float)$data['price_per_hc'],8);?></td>
                      <td><?php echo number_format((float)$data['sell_hc_amount'],8);?></td>
                    </tr>
                    <?php $count++; } ?>
                    <?php  if(count($sellListing->toArray()) < 1) {
							echo "<tr class='even'><td colspan = '6'>No record found</td></tr>";
					   } ?>
                  </tbody>
				  
				  
                  <tbody>
                  </tbody>
                </table>
				
				  <?php $this->Paginator->options(array('url' => array('controller' => 'Transactions', 'action' => 'newsell_exchange_search')));
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
    <br />
    <br />
	
	</section>
</div>

<script>
	$(document).ready(function() {
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
			
		});	
	
	});
	
	
		
  </script>
