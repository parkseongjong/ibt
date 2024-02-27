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
						$count= $serial_num;
						$cudate = date("Y-m-d H:i:s");	
						$cudateStr = time();
						foreach($listing->toArray() as $k=>$data){
							
							if($k%2==0) $class="odd";
							else $class="even";
						
						$enddate = strtotime($data['created_at']. ' + '.$data['amount_reserve_days'].' days');
						
						$dateDiffInDays = ($enddate - $cudateStr)/(60*60*24);
						$dateDiffInDays = (int)abs($dateDiffInDays);
						//$dateDiffInDays = abs($dateDiffInDays);		
							
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