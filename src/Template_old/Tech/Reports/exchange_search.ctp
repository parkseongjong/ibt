<table id="table-two-axis" class="two-axis table">
                        <thead>
                        <tr> 
                            <th>#</th>
                            <th>Volume (BTC)</th>
                            <th>Price Per HC</th>
                            <th>Volume (HC)</th>
							<th>Buy Fees</th>
							<th>Sell Fees</th>
							<th>Buyer</th>
							<th>Seller</th>
                            <th class="column-title">Status </th>
							<th class="column-title no-link last"><span class="nobr">Date</span>                            
                            
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $count= $serial_num; 
                        foreach($getExchange->toArray() as $k=>$data){
						 if($k%2==0) $class="odd";
                        else $class="even";
                        ?>
                        <tr class="<?=$class?>" id="user_row_<?= $data['id']; ?>">
                            <td><?=$count?></td>
                            <td><?php echo number_format((float)$data['btc_amount'],8); ?></td>
                            <td><?php echo number_format((float)$data['price_per_hc'],8); ?></td>
                            <td><?php echo  number_format((float)$data['hc_amount'],8); ?></td>
                            <td><?php echo number_format((float)$data['buy_fees'],8); ?></td>                            
                            <td><?php echo number_format((float)$data['sell_fees'],8); ?> </td>
                            <td><?php echo (!empty($data['buyer'])) ? $data['buyer']['username']: '' ; ?> </td>
                            <td><?php echo (!empty($data['seller'])) ? $data['seller']['username']: '' ; ?> </td>
                            <td><?php echo $data['status']; ?> </td>
                            <td><?php echo date('d M Y H:i:s',strtotime($data['created_at'])); ?></td>
                            
                            
                        </tr>
                        <?php $count++;} ?>
                        </tbody>
                    </table>
                    <?php $this->Paginator->options(array('url' => array('controller' => 'Reports', 'action' => 'exchange_search')));
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
