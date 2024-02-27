 <table class="table table-striped jambo_table bulk_action two-axis table">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S.No. </th>
                            <th class="column-title">Person Name. </th>
                            <th class="column-title">Email </th>
                            <th class="column-title">Phone Number </th>
                            <th class="column-title">Query</th>
                            <th class="column-title">Is replied</th>
                             <th class="column-title">Sent At</th>
                            <th class="column-title no-link last"><span class="nobr">Reply</span>
                            </th>
                          
                          </tr>
                        </thead>

                        <tbody>
							<?php 
							$count = $serial_num;
							//pr($Reports);die;
							foreach($ContactUs->toArray() as $data){
								if($data['status']==0){
									$status="No";$button="danger";$text="Reply";
								}else{
									$status="Yes";$button="success";$text="View Reply";
								}
								
							 ?>
							<tr id ="user_row_<?= $data['id']; ?>">
								<td><?= $count?>.</td>
								<td><?= $data['name']?>.</td>
								<td><?= $data['email']?>.</td>
								<td><?= $data['phone']?>.</td>
								
								
								<td class=" "><?= substr($data['message'],0,50).'...'; ?> </td>
								<td class=" "><span class='btn btn-<?=$button?> btn-xs'><?=$status?></span></td>
								<td class=" "><?=date('j M Y g:i A',strtotime($data['created']->format('Y-m-d H:i:s'))); ?> </td>
								
								<td class=" last">
									<a href="<?php echo $this->Url->build(['controller'=>'Feedbacks','action'=>'detail',$data['id']]); ?>" class="btn btn-<?=$button?> btn-xs"><i class="fa fa-eye"></i> <?=$text?></a>
								</td>
							</tr>
							
							<?php  $count++;
							} ?> 
							
							<?php  if(count($ContactUs->toArray()) < 1) {
										echo "<tr><th colspan = '6'>No record found</th></tr>";
								   } ?>	

                         </tbody>
                      </table>
                      <?php 
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
              
