
    <table id="table-two-axis" class="two-axis table" >
	<thead>
	<tr >
        <th>#</th>
        <th><?= __('User Info'); ?></th>
        <th><?= __('Amount'); ?></th>
        <th><?= __('Coin'); ?></th>
        <th><?= __('Category'); ?></th>
        <th><?= __('Target'); ?></th>
        <th><?= __('Reward List ID'); ?></th>
        <th><?= __('Date'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php
	$count= 1; 
	foreach($users->toArray() as $k=>$data){
	?>
	<tr class="even" id="user_row_<?= $data['id']; ?>">
	    <td><?php echo $data['id']; ?></td>
	    <td><?php echo $data['user']['name']; ?> (<?php echo $data['user']['phone_number']; ?>)</td>
	    <td><?php echo $data['amount']; ?></td>
	    <td><?php echo $data['epay']['short_name']; ?></td>
	    <td><?php echo $data['type']; ?></td>
	    <td><?php echo $data['target']; ?></td>
        <td><?php echo $data['principal_wallet_id']; ?></td>
	    <td><?php echo $data['created']->format("Y-m-d H:i"); ?></td>
	</tr>
	<?php $count++;} ?>
	</tbody>
    </table>
    <?php $this->Paginator->options(array('url' => array('controller' => 'Epay', 'action' => 'logsub', $userId)));
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



