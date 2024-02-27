<table id="table-two-axis" class="two-axis table">
	<thead>
	<tr>
		<th>S No.</th>
		<th>Amount(ETH)</th>
		<th>Type</th>
		<th>Exchange Token</th>
		<th>Status</th>
		<th>Remark</th>
		<th>Date</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$count= $serial_num;
			$coinArr = [2=>'ETH',3=>'RAM',4=>'ADMC'];
	 foreach($listing->toArray() as $k=>$data){
		
		if($k%2==0) $class="odd";
		else $class="even";
		
		$showTxType = ($data['tx_type']=="purchase") ? "deposit" : $data['tx_type'];
		$showStatus = ($data['tx_type']=='withdrawal' && $data['withdrawal_send']=='N') ? "pending" :  $data['status'];
		
		
	?>
	<tr class="<?=$class?>">
		<td> <?=$count?></td>
		<td><?php echo number_format((float)$data['coin_amount'],8)?> </td>
		<td> <?php echo ucfirst(str_replace("_"," ",$showTxType)); ?></td>
		<td> 
			<?php if(!empty($data[$data['tx_type']]) && $data['remark']!='adminFees') {
					if($data['tx_type']=='sell_exchange' && $data['coin_amount']>0){  
						echo $data[$data['tx_type']]['total_sell_spend_amount']." ".$coinArr[$data[$data['tx_type']]['sell_spend_coin_id']];
					
					} 
					else if($data['tx_type']=='sell_exchange' && $data['coin_amount']<0){  
						echo $data[$data['tx_type']]['total_sell_get_amount']." ".$coinArr[$data[$data['tx_type']]['sell_get_coin_id']];
					
					} 
					else if($data['tx_type']=='buy_exchange' && $data['coin_amount']>0){ 
					
						echo $data[$data['tx_type']]['total_buy_spend_amount']." ".$coinArr[$data[$data['tx_type']]['buy_spend_coin_id']];
					
					} 
					else if($data['tx_type']=='buy_exchange' && $data['coin_amount']<0){ 
					
						echo $data[$data['tx_type']]['total_buy_get_amount']." ".$coinArr[$data[$data['tx_type']]['buy_get_coin_id']];
					
					} 
				}
				?>
			</td>
		<td><?php echo ucfirst($showStatus);?> </td>
		<td><?php echo ucfirst(str_replace("_"," ",$data['remark']));?> </td>
		<td><?=$data['created']->format('d M Y H:i:s');?> </td>
	</tr>
	<?php $count++; } ?>
	<?php  if(count($listing->toArray()) < 1) {
		echo "<tr class='even'><td colspan = '6'>No record found</td></tr>";
   } ?>	
	</tbody>
</table>
   <?php $this->Paginator->options(array('url' => array('controller' => 'Transactions', 'action' => 'translist_search',$userId)));
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