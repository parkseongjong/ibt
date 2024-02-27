<table border=1 id="table-two-axis" class="two-axis table">
	<thead>
	<tr>
        <th>#</th>
        <th><?= __('Phone Number'); ?></th>
        <th><?= __('Username'); ?></th>
        <th><?= __('Coin Amount'); ?></th>
        <th><?= __('Coin'); ?></th>
        <th><?= __('Status'); ?></th>
        <th><?= __('Date'); ?></th>
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
		<tr class="<?=$class?>">
			<td> <?=$count?></td>
			<td> <?php echo $data['user']['username']; ?></td>
             <td> <?php echo $data['user']['name']; ?></td>
			<td><?php echo number_format((float)$data['amount'],4)?> </td>
			<td> <?php echo $data['cryptocoin']['short_name']; ?></td>
			<td><?php echo ucfirst($data['status']);?> </td>
			<td><?=$data['created_at']->format('d M Y H:i:s');?> </td>
		</tr>
	</tr>
	<?php $count++; } ?>
	<?php  if(count($listing->toArray()) < 1) {
		echo "<tr class='even'><td colspan = '10'>".__('No record found')."</td></tr>";
   } ?>	
	</tbody>
</table>
   <?php $this->Paginator->options(array('url' => array('controller' => 'Coin', 'action' => 'rewardlist_search')));
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