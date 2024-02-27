 <table class="table table-striped jambo_table bulk_action two-axis table">
                        <thead>
                          <tr class="headings">
                            <th class="column-title"># </th>
                            <th class="column-title"><?= __('Username'); ?></th>
                            <th class="column-title"><?= __('Email'); ?></th>
                            <th class="column-title"><?= __('Issue Type'); ?></th>
                             <th class="column-title"><?= __('Issue'); ?></th>
                             <th class="column-title"><?= __('File Attachment'); ?></th>
                            <th class="column-title"><?= __('Replied'); ?></th>
                             <th class="column-title"><?= __('Sent at'); ?></th>
                            <th class="column-title no-link last"><span class="nobr"><?= __('Reply'); ?></span>
                            </th>
                          
                          </tr>
                        </thead>

                        <tbody>
							<?php 
							$count = $serial_num;
							//pr($Reports);die;
							foreach($ContactUs->toArray() as $data){
								$issueFile = "&nbsp;";
								if(!empty($data['issue_file'])){
									$issueFile = "<img src='".$this->request->webroot."uploads/issue_file/".$data['issue_file']."' width=50 />";
								}
								if($data['status']=='pending'){
									$status="No";$button="danger";$text="Reply";
								}else{
									$status="Yes";$button="success";$text="View Reply";
								}
							 ?>
							<tr id ="user_row_<?= $data['id']; ?>">
								<td><?= $data['id'];?></td>
								<td><?= $data['user']['username']?></td>
								<td><?php  echo empty($data['user']['email']) ? $data['email'] : $data['user']['email'];?></td>
								<td><?=  str_replace("_"," ",$data['issue_type'])?></td>
								<td class=" "><textarea><?= $data['issue']; ?></textarea> 
									<?php if(!empty($data['tx_id'])) { ?> <div style="cursor:pointer;color:blue;" onClick="showClick(this);"  class="show_tx"><?= __('Show TX ID'); ?></div>
									<div style="display:none" ><?php echo $data['tx_id']; ?></div>		
								<?php } ?>
								
								
								</td>
								<td class=" "><?= $issueFile ?> </td>
								<td class=" "><span class='btn btn-<?=$button?> btn-xs'><?=$status?></span></td>
								<td class=" "><?=date('j M Y g:i A',strtotime($data['created_at']->format('Y-m-d H:i:s'))); ?> </td>
								
								<td class=" last"> 
									<a target="_blank" href="<?php echo $this->Url->build(['controller'=>'ContactUs','action'=>'detail',$data['id']]); ?>" class="btn btn-<?=$button?> btn-xs"><i class="fa fa-eye"></i> <?=$text?></a>
									
									
								</td>
							</tr>
							
							<?php  $count++;
							} ?> 
							
							<?php  if(count($ContactUs->toArray()) < 1) {
										echo "<tr><th colspan = '6'>".__('No record found')."</th></tr>";
								   } ?>	

                         </tbody>
                      </table>
                      <?php $this->Paginator->options(array('url' => array('controller' => 'ContactUs', 'action' => 'search')));
					echo "<div class='pagination' style = 'float:right'>";
 
					// the 'first' page button
					$paginator = $this->Paginator;
					echo $paginator->first(__('First'));

					// 'prev' page button, 
					// we can check using the paginator hasPrev() method if there's a previous page
					// save with the 'next' page button
					if($paginator->hasPrev()){
					echo $paginator->prev(__('Prev'));
					}

					// the 'number' page buttons
					echo $paginator->numbers(array('modulus' => 2));

					// for the 'next' button
					if($paginator->hasNext()){
					echo $paginator->next(__('Next'));
					}

					// the 'last' page button
					echo $paginator->last(__('Last'));

					echo "</div>";
							
					?>