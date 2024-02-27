 <table id="table-two-axis" class="two-axis table">
                        <thead>
                        <tr>
							<th><input type="checkbox" id="select_all" /></th>
                            <th>S&nbsp;No.</th>
							<th>Btc Transaction Id</th>
							<th>Username</th>
							<th>Total Btc Amount</th>
							<th>Btc Amount</th>
							<th>Wallet Address</th>
							<th>Status</th>
							<th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
						
                        $count= $serial_num;

                        foreach($listing->toArray() as $k=>$data){
                            if($k%2==0) $class="odd";
                            else $class="even";
							
							$userBTC = $this->Conversion->getTotalBtc($data['user']['id']);
							
							$showStatus = "Completed";
							if($data['admin_withdrawl_transfer']=="no"){
								$showStatus = "Pending";
							}
							
                            ?>
                            <tr class="<?=$class?>">
								<td>
									<?php if($data['admin_withdrawl_transfer']=="no"){ ?>
										<input type="checkbox" name="agc_ids[]" value="<?php echo $data['id']; ?>" class="checkbox" />
									<?php } else { ?>
									&nbsp;
									<?php } ?>
								</td>
                                <td><?=$count?></td>
								<td><?php echo $data['id']; ?></td>
								<td><?php echo $data['user']['username']; ?></td>
								<td><?php echo number_format((float)abs($userBTC),8); ?></td>
								<td><?=number_format((float)abs($data['btc_coins']),8);?></td>
								<td><?php echo $data['wallet_address']; ?></td>
								<td><?php echo $showStatus; ?></td>
								<td><?=$data['created_at']->format('d M Y H:i:s');?></td>
                            </tr>
                            <?php $count++; } ?>
                        <?php  if(count($listing->toArray()) < 1) {
                            echo "<tr class='even'><td colspan = '6'>No record found</td></tr>";
                        }  ?>
                        </tbody>
                    </table>
                    <?php  $this->Paginator->options(array('url' => array('controller' => 'Transactions', 'action' => 'btcSendSearch')));
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
					<script>
						//select all checkboxes
		$("#select_all").change(function(){  //"select all" change
			var status = this.checked; // "select all" checked status
			$('.checkbox').each(function(){ //iterate all listed checkbox items
				this.checked = status; //change ".checkbox" checked status
			});
		});

		$('.checkbox').change(function(){ //".checkbox" change
			//uncheck "select all", if one of the listed checkbox item is unchecked
			if(this.checked == false){ //if this item is unchecked
				$("#select_all")[0].checked = false; //change "select all" checked status to false
			}
		   
			//check "select all" if all checkbox items are checked
			if ($('.checkbox:checked').length == $('.checkbox').length ){
				$("#select_all")[0].checked = true; //change "select all" checked status to true
			}
		});
		
					</script>