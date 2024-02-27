<table class="table" style=" width:1500px">
						<tr>
							<th>Sr. No.</th>
						
							<th>Subject</th>
							<th width="150px">Contents</th>
							
              <th>Action</th> 
						</tr>
            <?php 
            
            
			$count = $serial_num; 
			
			foreach($BoardFaq->toArray() as $ticket){
						$issueFile = "&nbsp;";
						if(!empty($ticket['file'])){
							$issueFile = "<img src='".$this->request->webroot."uploads/board/".$ticket['file']."' width=50 />";
						}
						?><tr>
							<td><?php echo $serial_num; ?></td>
							
						
							<td><?php echo $ticket['subject']; ?></td>
						
								
							
							</td>
							<th><?php echo $ticket['contents']; ?></td>

							
					
						
              <td style="width: 110px;"><a href="<?php echo $this->Url->build(['controller'=>'Settings','action'=>'editfaqs',$ticket['id']]) ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a></td>
              
							</tr>
						<?php $serial_num++; }  ?>
					  </table>
            <?php $this->Paginator->options(array('url' => array('controller' => 'Settings', 'action' => 'faqspagination')));
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