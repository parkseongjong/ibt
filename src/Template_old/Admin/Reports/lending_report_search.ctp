<table id="table-two-axis" class="two-axis table">
                        <thead>
                        <tr> 
                            <th>#</th>
                            <th>Username</th>
                            <th>Lending Amount</th>
                            <th>Debit HC</th>
                            <th>Date</th>
                                                    
                            
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $count = $serial_num; 
                        foreach($reports->toArray() as $k=>$data){
							
						/*$coinTotal = 0;	
						$btcBalance = 0;	
						$btcDeposit = 0;	
						//	print_r($data['agctransactions']); die;
						if(!empty($data['agctransactions'])){
							foreach($data['agctransactions'] as $trans){
								//$agcTotal = $agcTotal + $trans['agc_coins'];
								if($trans['trans_type']=="credit") {
									$btcDeposit = $btcDeposit + $trans['btc_coins'];
								}
								$btcBalance = $btcBalance + $trans['btc_coins'];
							}
						}
						
						if(!empty($data['cointransactions'])){
							foreach($data['cointransactions'] as $singleTrans){
								//$agcTotal = $agcTotal + $trans['agc_coins'];
								$coinTotal = $coinTotal + $singleTrans['coin'];
							}
						}	*/
						
                        if($k%2==0) $class="odd";
                        else $class="even";
                        ?>
                        <tr class="<?=$class?>" id="user_row_<?= $data['id']; ?>">
                            <td><?=$count?></td>
                            <td><?php echo $data['user']['username']; ?></td>
                            <td><?php echo $data['amount']; ?></td>
                            <td><?php echo number_format((float)abs($data['cointransaction']['coin']),8); ?></td>
                            <td><?php echo date('d M Y H:i:s',strtotime($data['created_at'])); ?></td>
                            <!--<td><?php //echo $data['ip_address']; ?></td>    -->
                            
                            
                        </tr>
                        <?php $count++;} ?>
                        </tbody>
                    </table>
                    <?php $this->Paginator->options(array('url' => array('controller' => 'Reports', 'action' => 'lending_report_search')));
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