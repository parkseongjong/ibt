<table id="table-two-axis" class="two-axis table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th><?= __('Username'); ?></th>
                            <th><?= __('Email'); ?></th>
                            <th><?= __('Buy Volume'); ?></th>
                            <th><?= __('Sell Volume'); ?></th>
                            <th><?= __('Total Volume'); ?></th>
                            <th><?= __('Start Date'); ?></th>
                            <th><?= __('End Date'); ?></th>
                            
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $count= $serial_num; 
                        foreach($users->toArray() as $k=>$data){
						
						$sellvolume = 0;
						if(!empty($data['sellvolume'])){
							foreach($data['sellvolume'] as $sellSingle){
								$sellvolume = $sellvolume + $sellSingle['total_sell_get_amount'];
							}
						}
						
						$buyvolume = 0;
						if(!empty($data['buyvolume'])){
							foreach($data['buyvolume'] as $buySingle){
								$buyvolume = $buyvolume + $buySingle['total_buy_spend_amount'];
							}
						}
						
                        if($k%2==0) $class="odd";
                        else $class="even";
                        ?>
                        <tr class="<?=$class?>" id="user_row_<?= $data['id']; ?>">
                            <td><?=$count?></td>
                            <td><?php echo $data['username']; ?></td>
                            <td><?php echo $data['email']; ?></td>
                            <td><?php echo $buyvolume; ?></td>
                            <td><?php echo $sellvolume; ?></td>
							<td><?php echo $buyvolume + $sellvolume; ?></td>
							<td><?php echo $startDate; ?></td>
							<td><?php echo $endDate; ?></td>
							
                            
                        </tr>
                        <?php $count++;} ?>
                        </tbody>
                    </table>
                    <?php $this->Paginator->options(array('url' => array('controller' => 'Exchange', 'action' => 'volumeSearch',$coinId)));
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