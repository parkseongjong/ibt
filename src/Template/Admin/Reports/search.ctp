 <table id="table-two-axis" class="two-axis table">
                        <thead>
                          <tr class="headings">
                            <th>#</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone number</th>
                            <th>Wallet </th>
                            <th>Date Of Registration </th>
                            <!--<th>IP </th>-->
                            <th>HC Tokens</th>
                            <th>BTC Deposit</th>
							<th>BTC Balance</th>
							<th>Referrals</th>	                            
							<th class="column-title">Status </th>
							<th class="column-title no-link last"><span class="nobr">Action</span>          
                          
                          </tr>
                        </thead>

                        <tbody>
							<?php 
							$count = $serial_num;
							//pr($Reports);die;
							foreach($users->toArray() as $k=>$data){
							
							$coinTotal = 0;	
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
							}		
							
							if($k%2==0) $class="odd";
							else $class="even";
							?>
							<tr class="<?=$class?>">
								<td><?=$count?></td>
								<td><?php echo $data['username']; ?></td>
								<td><?php echo $data['name']; ?></td>
								<td><?php echo $data['email']; ?></td>
								<td><?php echo $data['phone_number']; ?></td>                            
								<td><?php echo $data['unique_id']; ?> </td>
								<td><?php echo date('d M Y',strtotime($data['created'])); ?></td>
								<!--<td><?php //echo $data['ip_address']; ?></td>         -->
								<td><?php echo $coinTotal; ?></td>
								<td><?php echo number_format((float)abs($btcDeposit),8); ?></td>
								<td><?php echo number_format((float)abs($btcBalance),8); ?></td>
								<td><?php echo count($data['referusers']); ?></td>
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
									<a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'profile',$data['id']]); ?>"  class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
									<a href="javascript:void(0)" onclick="delete_section(<?php echo $data['id'] ?>)" class="btn btn-danger btn-xs"><i class="fa fa-close"></i> Delete </a>
								</td>
							</tr>
							<?php $count++;
							} ?>
							
							<?php  if(count($users->toArray()) < 1) {
										echo "<tr><th colspan = '6'>No record found</th></tr>";
								   } ?>	

                         </tbody>
                      </table>
                       <?php $this->Paginator->options(array('url' => array('controller' => 'Reports', 'action' => 'search')));
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
              
