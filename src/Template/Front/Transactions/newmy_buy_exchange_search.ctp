<table class="table table-inverse m-b-0">
                  <thead>
                    <tr>
                      <th>Sr. NO.</th>
                      <th>VOLUME(BTC)</th>
                      <th>RATE(BTC)</th>
                      <th>VOLUME(Hed)</th>
					  <th>Status</th>
					  <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
					
						$count= $serial_num;
							
						 foreach($listing->toArray() as $k=>$data){
							
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
						<a class="btn btn-danger" href="javascript:void(0);" onClick="deletePopup(<?php echo $data['id']; ?>);"><i class="fa fa-trash"></i></a>
						<?php } else { ?>
						&nbsp;
						<?php  } ?>
						</td>
                    </tr>
                    <?php $count++; } ?>
                    <?php  if(count($listing->toArray()) < 1) {
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