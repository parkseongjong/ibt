<table id="table-two-axis" class="two-axis table">
                        <thead>
                        <tr> 
                            <th>#</th>
							<th>From User</th>
                            <th>To User</th>
                            <th>Amount</th>
							<th>Transaction Id</th>
                            <th>Date</th>
                            
                            
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $count= $serial_num; 
                        foreach($listing->toArray() as $k=>$data){
							
							$statusArr = [0=>'Pending',1=>'Completed'];
						
                        if($k%2==0) $class="odd";
                        else $class="even";
                        ?>
                        <tr class="<?=$class?>" id="user_row_<?= $data['id']; ?>">
                            <td><?=$count?></td>
							
                            <td><?php echo $data['from_user']['username']; ?></td>
                            <td><?php echo $data['to_user']['username']; ?></td>
                            <td><?php echo $data['coin_amount']; ?></td>
							<td><a target="_blank" href="https://etherscan.io/tx/<?php echo $data['tx_id']; ?>"><?php echo $data['tx_id']; ?></a></td>
                            <td><?php echo date('Y-m-d H:i:s',strtotime($data['created_at'])); ?></td>
                        </tr>
                        <?php $count++;} ?>
                        </tbody>
                    </table>
                    <?php $this->Paginator->options(array('url' => array('controller' => 'Reports', 'action' => 'coin-transfer-history-search')));
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