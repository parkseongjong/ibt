<table border=1 id="table-two-axis" class="two-axis table">
	<thead>
	<tr>
        <th>#</th>
        <th><?= __('Username'); ?></th>
        <th><?= __('Total Amount Spent'); ?></th>
        <th><?= __('Amount Spent'); ?></th>
        <th><?= __('Coins Spent'); ?></th>
        <th><?= __('Total Amount Received'); ?></th>
        <th><?= __('Amount Received'); ?></th>
        <th><?= __('Coins Received'); ?></th>
        <th><?= __('Rate'); ?></th>
        <th><?= __('Admin Fees'); ?></th>
        <th><?= __('Status'); ?></th>
        <th><?= __('Date & Time'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php
	$count= $serial_num;
		
	 foreach($listing->toArray() as $k=>$data){
		
		if($k%2==0) $class="odd";
		else $class="even";
	?>
	<tr class="<?=$class?>">
		<td> <?=$count?></td>
		<td> <?php echo $data['user']['username']; ?></td>
		<td><?php echo number_format((float)$data['total_buy_spend_amount'],4)?> </td>
		<td><?php echo number_format((float)$data['buy_spend_amount'],4)?> </td>
		<td><?php echo $data['spendcryptocoin']['short_name']?> </td>
		<td><?php echo number_format((float)$data['total_buy_get_amount'],4)?> </td>
		<td><?php echo number_format((float)$data['buy_get_amount'],4)?> </td>
		<td><?php echo $data['getcryptocoin']['short_name']?> </td>
		<td> <?php echo number_format((float)$data['per_price'],4); ?></td>
		<td> <?php echo number_format((float)$data['buy_fees'],4); ?></td>
		<td><?php echo __(ucfirst($data['status']));?> </td>
		<td><?=$data['created_at']->format('d M Y H:i:s');?> </td>
	</tr>
	<?php $count++; } ?>
	<?php  if(count($listing->toArray()) < 1) {
		echo "<tr class='even'><td colspan = '10'>".__('No record found')."</td></tr>";
   } ?>	
	</tbody>
</table>
   <?php $this->Paginator->options(array('url' => array('controller' => 'Exchange', 'action' => 'buyListSearch')));
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