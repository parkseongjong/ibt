<table id="table-two-axis" class="two-axis table" >
	<thead>
	<tr >
        <th><?= __('Username'); ?></th>
        <th><?= __('Name'); ?></th>
        <th><?= __('Email'); ?></th>
        <th><?= __('User Level'); ?></th>
        <th><?= __('Phone Number'); ?></th>
        <th><?= __('ETH Address'); ?></th>
        <th><?= __('BTC Address'); ?></th>
        <th><?= __('Date of Registration'); ?></th>
        <th class="column-title no-link last"><span class="nobr"><?= __('Action'); ?></span>
	</tr>
	</thead>
	<tbody>
	<?php
	$count= 1; 
	foreach($users->toArray() as $k=>$data){
	?>
	<tr class="even" id="user_row_<?= $data['id']; ?>">
	    <td><?=$count?></td>
	    <td><?php echo $data['username']; ?></td>
	    <td><?php echo $data['name']; ?></td>
	    <td><?php echo $data['email']; ?></td>
	    <td><?php echo $data['user_level']; ?></td>
	    <td><?php echo $data['phone_number']; ?></td>
	     <td><?php echo $data['eth_address']; ?></td>
	     <td><?php echo $data['btc_address']; ?></td>
	    <td><?php echo $data['created']; ?></td>
	    <td>
		<a href="<?php echo $this->Url->build(['controller'=>'epay','action'=>'logs',$data['id']]); ?>"  class="btn btn-info btn-xs"> <?= __('Logs'); ?>  </a>
		</td>
	</tr>
	<?php $count++;} ?>
	</tbody>
    </table>
    <?php $this->Paginator->options(array('url' => array('controller' => 'Epay', 'action' => 'listsub')));
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