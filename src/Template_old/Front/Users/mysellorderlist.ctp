<style>
.table > thead > tr > th {
    padding: 14px 8px;
    color: #0b0b0b;
}
</style>



<section style="margin-left:0px;">
    <section class="main-content"> 
      <!-- Live Coin Price -->
      <div class="row">
        <div class="col-lg-12">
          <div class="panel panel-default">
            <div class="panel-collapse">
              <div class="panel-body"> 
                <!-- Table Starts Here -->
                <section class="main-content">
                  <h3>My Sell Orders</h3>
					<?php echo $this->Form->create('form', array('name' =>'myForm')); ?>
				  <div class="col-sm-8">
				    <div class="col-sm-6">
					 <select class="form-control" name="status">
					   <option value="">All</option>
					   <option value="deleted">Deleted</option>
					   <option value="completed">Completed</option>
					   <option value="pending">Pending</option>
					 </select>
					</div>
					<div class="col-sm-2">
				      <?php echo $this->Form->button('Submit', array('name' =>'submit', 'value' => 'Submit', 'class' 
					  => 'btn btn-success')); ?>
					</div>
				   </div>
				</form><br/>
				<br/>
				<br/>
				  
                  <div class="row">
                    
                    <div class="col-md-12">
                      <div class="panel panel-default">
                        <div class="tab-content2 tab-content">
                          <div id="home" class="tab-pane fade in active table-responsive">
                            
							  <?= $this->Flash->render() ?>
                             <table class="table table-striped">
								<thead>
									<tr>
									   <th>Sr No.</th>
									   <th>Price Per <?php echo $secondCoin; ?></th>
									  <th><?php echo $secondCoin; ?> Amount</th>
									  <th><?php echo $firstCoin; ?> Amount</th>
									  <th>Status</th>
									  <th>Date</th>
									  <th><i class="fa fa-times"></i></th>
									</tr>
								<thead>
								<tbody>
								<?php
								 $count= 1;
								foreach($getSellOrderList as $singleData) { 
								$action = "&nbsp";
								$showAmount = $singleData['total_sell_get_amount'];
								if($singleData['status']=='pending') {
									$action = "<a href='javascript:void(0)' id='sell_".$singleData['id']."' onClick='deleteOrder(this.id)'>Delete</a>";
									$showAmount = $singleData['sell_get_amount'];
								}
							
								?>
								
									<tr >
									<td><?php echo $count; ?></td>
									<td ><?php echo number_format($singleData['per_price'],8); ?></td>
									<td ><?php  echo $showAmount/$singleData['per_price']; ?></td>
									<td ><?php echo $showAmount; ?></td>
									<td ><?php echo  ucfirst($singleData['status']); ?></td>
									<td ><?php echo date('d M, Y',strtotime($singleData['created_at'])); ?></td>
									<td><?php echo $action; ?></td>
									</tr>
								<?php $count++; } ?>
								</tbody>
							</table>
							<?php $this->Paginator->options(array('url' => array('controller' => 'Users', 'action' => 'mysellorderlistSearch',$firstCoin,$secondCoin)));
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
                </section>
                <!-- Table Ends Here --> 
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </section>

  
<script>
$(document).ready(function() {
        
		
      

		
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
		
	});
	
	
	function deleteOrder(getId){
		if(confirm("Are you really want to delete this ?")){
		var splitId = getId.split("_");
		var tableType = splitId[0]; 
		var tableId = splitId[1];
		
		$.ajax({
				url : "<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'deleteMyOrder']); ?>/"+tableId+"/"+tableType,
				type : 'post',
				dataType : 'json',
				success : function(resp){
					$("#"+getId).closest('tr').remove();
				}
			});
		}
		
	}  
	  
	  
	  </script>	
</script>
  