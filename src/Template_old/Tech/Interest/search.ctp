<table id="table-two-axis " class="two-axis table dataTable">
							<thead>
							<tr>
								<th>S No.</th>
								<th>date</th>
								<th>Percent</th>
								<th class="column-title no-link last"><span class="nobr">Action</span>
							</tr>
							</thead>
							<tbody>
							<?php
							$count= $serial_num;
								
							 foreach($listing->toArray() as $k=>$data){
								if($k%2==0) $class="odd";
								else $class="even";
							?>
							<tr  style="text-align:center" class="<?=$class?>" id="user_row_<?= $data['id']; ?>">
								<td> <?=$count?></td>
								<td class="from_date_<?= $data['id']; ?>" data-hiddendate='<?php echo date('Y-m-d',strtotime($data['add_date'])); ?>'><?php echo date('d M Y',strtotime($data['add_date']))?> </td>
								<td class="to_date_<?= $data['id']; ?>" data-hiddendate="<?php echo $data['percent']; ?>"><?php echo $data['percent']; ?> </td>
								<td class=" last">
									<a href="javascript:void(0)" onclick="edit_section(<?php echo $data['id'] ?>)" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
									
									<a href="javascript:void(0)" onclick="delete_section(<?php echo $data['id'] ?>)" class="btn btn-danger btn-xs"><i class="fa fa-close"></i> Delete </a>
								
								</td>				
								
							</tr>
							<?php $count++; } ?>
							<?php  if(count($listing->toArray()) < 1) {
								echo "<tr class='even'><td colspan = '6'>No record found</td></tr>";
						   } ?>	
							</tbody>
						</table>
						   <?php $this->Paginator->options(array('url' => array('controller' => 'Interest', 'action' => 'search')));
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