
                    <table id="table-two-axis" class="two-axis table">
                        <thead>
                        <tr> 
                        <th>#</th>
                           
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone number</th>

                            <th>TP3 Main Balance</th>
                            <th>TP3 Trading Balance</th>
                            <th>CTC Main Balance</th>
                            <th>CTC Trading Balance</th>
                            <th>ETH Main Balance</th>
                            <th>ETH Trading Balance</th>
                            <th>BTC Main Balance</th>
                            <th>BTC Trading Balance</th>
                            <th>USDT Main Balance</th>
                            <th>USDT Trading Balance</th>
                            <th>MC Main Balance</th>
                            <th>MC Trading Balance</th>
                            <th>XRP Main Balance</th>
                            <th>XRP Trading Balance</th>
                            <th>BNB Main Balance</th>
                            <th>BNB Trading Balance</th>
                            <th>KRW Main Balance</th>
                            <th>KRW Trading Balance</th>
							
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $count = $serial_num;
                        foreach($users->toArray() as $k=>$data){
						$kyArr = [''=>'Not Uploaded','P'=>'Pending','Y'=>'Completed','N'=>'Rejected'];
						
                        if($k%2==0) $class="odd";
                        else $class="even";
                        ?>
                       <tr class="<?=$class?>" id="user_row_<?= $data['id']; ?>">
                            <td><?=$count?></td>
                            
                            <td><?php echo $data['name']; ?></td>
                            <td><?php echo $data['email']; ?></td>
                            <td><?php echo $data['phone_number']; ?></td>                            
							<td> <?php
							 $getUserTransactions = $this->Custom->getBalance("TP3",$data['id']);
							 echo $getUserTransactions['principalBalance'];

							 ?></td>         
							 <td> <?php
							 
							 echo $getUserTransactions['withdrawBalance'];

							 ?></td>       
							
                            <td> <?php
							 $getUserTransactions1 = $this->Custom->getBalance("CTC",$data['id']);
							 echo $getUserTransactions1['principalBalance'];

							 ?></td>         
							 <td> <?php
							 
							 echo $getUserTransactions1['withdrawBalance'];

							 ?></td>   
							 <td> <?php
							 $getUserTransactions2 = $this->Custom->getBalance("ETH",$data['id']);
							 echo $getUserTransactions2['principalBalance'];

							 ?></td>         
							 <td> <?php
							 
							 echo $getUserTransactions2['withdrawBalance'];

							 ?></td>         
							 <td> <?php
							 $getUserTransactions3 = $this->Custom->getBalance("BTC",$data['id']);
							 echo $getUserTransactions3['principalBalance'];

							 ?></td>         
							 <td> <?php
							 
							 echo $getUserTransactions3['withdrawBalance'];

							 ?></td> 

						<td> <?php
							 $getUserTransactions4 = $this->Custom->getBalance("USDT",$data['id']);
							 echo $getUserTransactions4['principalBalance'];

							 ?></td>         
							 <td> <?php
							 
							 echo $getUserTransactions4['withdrawBalance'];

							 ?></td> 

						<td> <?php
							 $getUserTransactions5 = $this->Custom->getBalance("MC",$data['id']);
							 echo $getUserTransactions5['principalBalance'];

							 ?></td>         
							 <td> <?php
							 
							 echo $getUserTransactions5['withdrawBalance'];

							 ?></td>

<td> <?php
							 $getUserTransactions6= $this->Custom->getBalance("XRP",$data['id']);
							 echo $getUserTransactions6['principalBalance'];

							 ?></td>         
							 <td> <?php
							 
							 echo $getUserTransactions6['withdrawBalance'];

							 ?></td>

							<td> <?php
							 $getUserTransactions7= $this->Custom->getBalance("BNB",$data['id']);
							 echo $getUserTransactions7['principalBalance'];

							 ?></td>         
							 <td> <?php
							 
							 echo $getUserTransactions7['withdrawBalance'];

							 ?></td>

<td> <?php
							 $getUserTransactions8= $this->Custom->getBalance("KRW",$data['id']);
							 echo $getUserTransactions8['principalBalance'];

							 ?></td>         
							 <td> <?php
							 
							 echo $getUserTransactions8['withdrawBalance'];

							 ?></td>
                        </tr>
                        <?php $count++;} ?>
                        </tbody>
                    </table>
                    <?php $this->Paginator->options(array('url' => array('controller' => 'Reports', 'action' => 'userbalnacesearch')));
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
                   