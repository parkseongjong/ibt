<table id="table-two-axis" class="two-axis table">
                        <thead>
                        <tr> 
                            <th>#</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Data</th>
                            <th>Status </th>
							<th>Created </th>
						</tr>
                        </thead>
                        <tbody>
                        <?php
                        $count= $serial_num; 
                        foreach($withdrawals->toArray() as $k=>$data){
						
						if($data['withdrawal_send']=='N' && !empty($data['withdrawal_id'])){
							$usedStatus = "Processing";
							$txId = '';
						}
						else if($data['withdrawal_send']=='Y') {
							$usedStatus = "Completed";
							$txId = !empty($data['withdrawal_id']) ? $data['withdrawal_tx_id'] : '';
						}
						else if($data['withdrawal_send']=='N') {
							$usedStatus = "<div style='cursor:pointer;color:blue;' id='status_id_".$data['id']."' onClick='change_withdrawal_status(".$data['id'].")'>Pending</div>";
							$txId = '';
						}
						else {
							$usedStatus = "Nil";
							$txId = '';
						}
						
						
                        if($k%2==0) $class="odd";
                        else $class="even";
                        ?>
                        <tr class="<?=$class?>" id="user_row_<?= $data['id']; ?>">
                            <td><?=$count?></td>
                            <td><?php echo $data['user']['username']; ?></td>
							<td><?php echo $data['user']['email']; ?></td>
							<td>
								<strong>Tx Id</strong> - <?php echo $txId; ?>
								<br/>
								<strong>Amount</strong> - <?php echo abs($data['coin_amount'])." ".$data['cryptocoin']['short_name']; ?>
								<?php if($data['cryptocoin']['id']==3) { ?>
									<br/>
									<strong>Amount In Usd</strong> - <?php echo abs($data['withdrawal_amount_in_usd']); ?>
									<br/>
									<strong>Coin Price</strong> - <?php echo $data['withdrawal_coin_price']; ?>
								<?php } ?>
								<br/>
								<strong>Wallet Address</strong> - <?php echo $data['wallet_address']; ?>
							</td>
							
							<td><?php echo $usedStatus; ?></td>
							<td><?php echo date('d M Y',strtotime($data['created'])); ?></td>
						</tr>
                        <?php $count++;} ?>
                        </tbody>
                    </table>
                    <?php $this->Paginator->options(array('url' => array('controller' => 'Reports', 'action' => 'admcWithdrawalSearch')));
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