<table id="table-two-axis" class="two-axis table">
                        <thead>
                        <tr> 
                            <th>#</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Eth</th>
                            <th>Eth Reserve</th>
                            <th>Ram</th>
                            <th>Ram Reserve</th>
                            <th>Admc</th>
                            <th>Admc Reserve</th>
							<th>Usd</th>
                            <th>Usd Reserve</th> 
                          	                          
                            
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $count= $serial_num;
						

						
						
                        foreach($users->toArray() as $k=>$data){
						
						
                        if($k%2==0) $class="odd";
                        else $class="even";
                        
						
						$ethReserve = 0; 	
						$ramReserve = 0; 	
						$admcReserve = 0; 
						$usdReserve = 0;
						
						
						$ethTotal = 0;
						if(!empty($data['ethtransactions'])){
							foreach($data['ethtransactions'] as $ethTrans){
								if(!empty($ethTrans['coin_amount'])){
									$ethTotal = $ethTotal + $ethTrans['coin_amount'];
									/* if($ethTrans['remark']=='reserve for exchange'){
										$ethReserve = $ethReserve + $ethTrans['coin_amount'];
									} */
								}
							}
						}
						
						if(!empty($data['eth_reserve'])){
							foreach($data['eth_reserve'] as $ethSpend){
								if(!empty($ethSpend['total_buy_spend_amount'])){
									//$ethReserve = $ethReserve + $ethSpend['total_buy_spend_amount'];
									$ethReserve = $ethReserve + ($ethSpend['buy_get_amount']*$ethSpend['per_price']);
								}
							}
						}
						
						$ramTotal = 0;
						if(!empty($data['ramtransactions'])){
							foreach($data['ramtransactions'] as $ramTrans){
								if(!empty($ramTrans['coin_amount'])){
									$ramTotal = $ramTotal + $ramTrans['coin_amount'];
									/* if($ramTrans['remark']=='reserve for exchange'){
										$ramReserve = $ramReserve + $ramTrans['coin_amount'];
									} */
								}
							}
						}
						
						$usdTotal = 0;
						if(!empty($data['usdtransactions'])){
							foreach($data['usdtransactions'] as $usdTrans){
								if(!empty($usdTrans['coin_amount'])){
									$usdTotal = $usdTotal + $usdTrans['coin_amount'];
									/* if($ramTrans['remark']=='reserve for exchange'){
										$ramReserve = $ramReserve + $ramTrans['coin_amount'];
									} */
								}
							}
						}
						
						if(!empty($data['ram_reserve'])){
							foreach($data['ram_reserve'] as $ramSpend){
								if(!empty($ramSpend['total_sell_spend_amount'])){
									$ramReserve = $ramReserve + $ramSpend['total_sell_spend_amount'];
								}
							}
						}	
						
						$admcTotal = 0;
						if(!empty($data['admctransactions'])){
							foreach($data['admctransactions'] as $admcTrans){
								if(!empty($admcTrans['coin_amount'])){
									$admcTotal = $admcTotal + $admcTrans['coin_amount'];
									/* if($admcTrans['remark']=='reserve for exchange'){
										$admcReserve = $admcReserve + $admcTrans['coin_amount'];
									} */
								}
							}
						}
						
						
						if(!empty($data['admc_reserve'])){
							foreach($data['admc_reserve'] as $admcSpend){
								if(!empty($admcSpend['total_sell_spend_amount'])){
									$admcReserve = $admcReserve + $admcSpend['total_sell_spend_amount'];
								}
							}
						}
						
						if(!empty($data['usd_reserve'])){
							foreach($data['usd_reserve'] as $usdSpend){
								if(!empty($usdSpend['total_sell_spend_amount'])){
									$admcReserve = $admcReserve + $usdSpend['total_sell_spend_amount'];
								}
							}
						}
						
                        ?>
                        <tr class="<?=$class?>" id="user_row_<?= $data['id']; ?>">
                            <td><?=$count?></td>
                            <td><?php echo $data['username']; ?></td>
                            <td><?php echo $data['email']; ?></td>
                            <td><?php echo number_format((float)$ethTotal,8); ?></td>
							<td><?php echo number_format((float)abs($ethReserve),8); ?></td>
							<td><?php echo ($ramTotal<0) ? 0 : number_format((float)$ramTotal,8); ?></td>		
							<td><?php echo number_format((float)abs($ramReserve),8); ?></td>		
							<td><?php echo ($admcTotal<0) ? 0 : number_format((float)$admcTotal,8); ?></td>		
							<td><?php echo number_format((float)abs($admcReserve),8); ?></td>
							<td><?php echo ($usdTotal<0) ? 0 : number_format((float)$usdTotal,8); ?></td>		
							<td><?php echo number_format((float)abs($usdReserve),8); ?></td>								
                            
                        </tr>
                        <?php $count++;} ?>
                        </tbody>
                    </table>
                    <?php $this->Paginator->options(array('url' => array('controller' => 'Reports', 'action' => 'usertxnSearch')));
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