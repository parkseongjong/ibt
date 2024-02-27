 <table id="table-two-axis" class="two-axis table">
                        <thead>
                          <tr class="headings">
                            <th>#</th>
                            <th>User Name</th>                        
                            <th>Ip Address</th>
                            <th>Date Time</th>
                          
                          </tr>
                        </thead>

                        <tbody>
							<?php 
							$count = $serial_num;
							//pr($Reports);die;
							 foreach($logs->toArray() as $k=>$data){
							
							if($k%2==0) $class="odd";
							else $class="even";
							?>
							<tr class="<?=$class?>">
								  <td><?=$count?></td>
									<td><?php echo $data['user']['name']; ?></td>
									<td><?php echo $data['ip_address']; ?></td>
									<td><?php echo date('d M Y g:i A',strtotime($data['created'])); ?></td>
							</tr>
							<?php $count++;
							} ?>
							
							<?php  if(count($logs->toArray()) < 1) {
										echo "<tr><th colspan = '6'>No record found</th></tr>";
								   } ?>	

                         </tbody>
                      </table>
                       <?php $this->Paginator->options(array('url' => array('controller' => 'LoginLogs', 'action' => 'log_search')));
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
              
