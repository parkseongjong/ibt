
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                             <th class="column-title">S.No. </th>
                            <th class="column-title">Name </th>
                            <th class="column-title">Email </th>
                            <th class="column-title">Phone Number </th>
							<th class="column-title">Status </th>
                            <th class="column-title no-link last"><span class="nobr">Action</span>
                            </th>
                          
                          </tr>
                        </thead>

                        <tbody>
							<?php 
							$count = 1;
							foreach($Users->toArray() as $data){ ?>
							<tr id ="user_row_<?= $data['id']; ?>">
								<td><?= $count?>.</td>
								<td class=" "><?php  if($data['gender'] == 'male' ){
									echo "Mr. "; 
									}elseif($data['gender'] == 'female'){
										 echo "Ms."; }; 
								echo ucwords($data['first_name'].' '.$data['last_name']) ?></td>
								<td class=" "><?= $data['email']; ?> </td>
								<td class=" "><?= $data['phone_number']; ?></td>
							
								<td class=" ">
									<input type="hidden" id="user_status_<?= $data['id'] ?>" value ="<?= $data['enabled']; ?>" />
									<a href="javascript:void(0)" id="status_id_<?= $data['id']; ?>" onclick="change_user_status(<?php echo $data['id'] ?>)">
									<?php  if($data['enabled'] == 'Y'){
										echo '<button type="button" class="btn btn-success btn-xs">Active</button>'; 
									}else{
										echo '<button type="button" class="btn btn-danger btn-xs">Deactive</button>';
									} ?></a>
								</td>
								<td class=" last">
									<a href="edit/<?= $data['id']; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
									<a href="#" onclick="delete_user(<?= $data['id'] ?>)" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>
								</td>
							</tr>
							
							<?php  $count++;
							} ?> 
							
							<?php  if(count($Users->toArray()) < 1) {
										echo "<tr><th colspan = '6'>No record found</th></tr>";
								   } ?>	

                         </tbody>
                      </table>
                      <?php //$this->Paginator->options(array('url' => array('controller' => 'Users', 'action' => 'adminsearch')));
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
                 
