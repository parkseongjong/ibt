 <table id="table-two-axis" class="two-axis table">
                        <thead>
                          <tr class="headings">
                            <th>#</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Wallet </th>
                            <th>HC Tokens</th>
							<th>HC Transferred</th>
                            <th class="column-title no-link last"><span class="nobr">Action</span>          
                          
                          </tr>
                        </thead>

                        <tbody>
							<?php 
							$count = $serial_num;
							//pr($Reports);die;
							foreach($users->toArray() as $k=>$data){
							
							$coinTotal = 0;	
							$totalTransferCoin = 0;
							
							
							if(!empty($data['cointransactions'])){
								foreach($data['cointransactions'] as $singleTrans){
									//$agcTotal = $agcTotal + $trans['agc_coins'];
									$coinTotal = $coinTotal + $singleTrans['coin'];
								}
							}
							if($coinTotal<0){ continue; }
							if(!empty($data['tocointransfer'])){
								foreach($data['tocointransfer'] as $tocointransfer_singleTrans){
									//$agcTotal = $agcTotal + $trans['agc_coins'];
									$totalTransferCoin = $totalTransferCoin + $tocointransfer_singleTrans['coin_amount'];
								}
							}	
							
							if($k%2==0) $class="odd";
							else $class="even";
							?>
							<tr class="<?=$class?>">
								<td><?=$count?></td>
								<td><?php echo $data['username']; ?></td>
								<td><?php echo $data['name']; ?></td>
								<td><?php echo $data['email']; ?></td>
								<td><?php echo $data['token_wallet_address']; ?> </td>
								<td><?php echo $coinTotal; ?></td>
								<td><?php echo $totalTransferCoin; ?></td>
								
								<td class=" last">
									<a href="<?php echo $this->Url->build(['controller'=>'reports','action'=>'cointransfer',$data['id']]); ?>"  class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Transfer Coin </a>
								</td>
							</tr>
							<?php $count++;
							} ?>
							
							<?php  if(count($users->toArray()) < 1) {
										echo "<tr><th colspan = '6'>No record found</th></tr>";
								   } ?>	

                         </tbody>
                      </table>
                       <?php $this->Paginator->options(array('url' => array('controller' => 'Reports', 'action' => 'users-coin-search')));
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
              
