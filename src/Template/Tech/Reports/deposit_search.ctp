<table id="table-two-axis" class="two-axis table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th><?= __('Username'); ?></th>
                            <th><?= __('Type'); ?></th>
                            <th><?= __('Email'); ?></th>
                            <th><?= __('Data'); ?></th>
                            <th><?= __('Status'); ?></th>
                            <th><?= __('Created'); ?></th>
                            <th><?= __('Action'); ?></th>
						</tr>
                        </thead>
                        <tbody>
                        <?php
                        $count= $serial_num; 
                        foreach($getData->toArray() as $k=>$data){
						
						
						
						
                        if($k%2==0) $class="odd";
                        else $class="even";
                        ?>
                        <tr class="<?=$class?>" id="user_row_<?= $data['id']; ?>">
                            <td><?=$count?></td>
                            <td><?php echo $data['user']['username']; ?></td>
							<td><?php echo $data['cryptocoin']['short_name']; ?></td>
							<td><?php echo $data['user']['email']; ?></td>
							<td>
								<strong><?= __('Transaction ID'); ?></strong> - <?php echo $data['tx_id']; ?>
								<br/>
								<strong><?= __('Amount'); ?></strong> - <?php echo abs($data['coin_amount'])." ".$data['cryptocoin']['short_name']; ?>
							
								<br/>
								<strong><?= __('Wallet Address'); ?></strong> - <?php echo $data['wallet_address']; ?>
								<br/>
								<?php if(!empty($data['user_file'])) { ?><a target="_blank" href="<?php echo "/uploads/flat/".$data['user_file'] ?>"><?= __('Show Attachment'); ?></a> <?php } ?>
							</td>
							
							<td><?php echo ucfirst($data['status']); ?></td>
							<td><?php echo date('d M Y',strtotime($data['created'])); ?></td>
							<td><?php if($data['status']=="pending"){
								?>
								
								<div style='cursor:pointer;color:blue;' data-curr-id="<?php echo $data['id']; ?>" onClick='change_deposit_status("<?php echo $data['id']; ?>")'><?= __('Approve'); ?></div>
								<?php
								
							} else { echo __("Approved"); } ?></td>
						</tr>
                        <?php $count++;} ?>
                        </tbody>
                    </table>
                    <?php $this->Paginator->options(array('url' => array('controller' => 'Reports', 'action' => 'depositSearch')));
                        echo "<div class='pagination' style = 'float:right'>";

                        // the 'first' page button
                        $paginator = $this->Paginator;
                        echo $paginator->first(__('First'));

                        // 'prev' page button,
                        // we can check using the paginator hasPrev() method if there's a previous page
                        // save with the 'next' page button
                        if($paginator->hasPrev()){
                            echo $paginator->prev(__('Prev'));
                        }

                        // the 'number' page buttons
                        echo $paginator->numbers(array('modulus' => 2));

                        // for the 'next' button
                        if($paginator->hasNext()){
                            echo $paginator->next(__('Next'));
                        }

                        // the 'last' page button
                        echo $paginator->last(__('Last'));

                        echo "</div>";

                    ?>