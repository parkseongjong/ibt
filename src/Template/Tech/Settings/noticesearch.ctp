<table class="table" style=" width:1500px">
						<tr>
							<th>Sr. No.</th>
							<th>Username</th>
               <th>Email</th>
							<th>Subject</th>
							<th width="150px">Contents</th>
							<th>File</th>
							<th>Date</th> 
              <th>Action</th> 
						</tr>
            <?php 
            
			$count = $serial_num;
             foreach($BoardNotice->toArray() as $ticket){
						$issueFile = "&nbsp;";
						if(!empty($ticket['file'])){
							$issueFile = "<img src='".$this->request->webroot."uploads/board/".$ticket['file']."' width=50 />";
						}
						?><tr>
							<td><?php echo $count; ?></td>
								<td><?= $ticket['user']['username']?></td>
							<td><?php  echo empty($ticket['user']['email']) ? $ticket['email'] : $ticket['user']['email'];?></td>
							<td><?php echo $ticket['subject']; ?></td>
							<td><?php echo $ticket['contents']; ?>
								
							
							</td>
							<th><?php echo $issueFile; ?></td>

							
					
							<td style="width: 110px;"><?php echo $ticket['created']; ?></td>
              <td style="width: 110px;"><a href="<?php echo $this->Url->build(['controller'=>'Settings','action'=>'editnotice',$ticket['id']]) ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a></td>
              
							</tr>
						<?php  $count++; }  ?>
					  </table>
            <?php $this->Paginator->options(array('url' => array('controller' => 'Settings', 'action' => 'noticesearch')));
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