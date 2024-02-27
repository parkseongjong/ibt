<table id="table-two-axis" class="two-axis table">
<thead>
<tr>
	<th><?= __('#');?></th>
	<th><?= __('To');?></th>
	<th><?= __('From');?></th>
	<th><?= __('Coins');?></th>
	<th><?= __('Date');?></th>
</tr>
</thead>
<tbody>
<?php
$count= $serial_num;
	
 foreach($listing->toArray() as $k=>$data){
	// pr($data);die;
	if($k%2==0) $class="odd";
	else $class="even";
?>
<tr class="<?=$class?>">
	<td> <?=$count?></td>
	<td><?=$data['user']['name']?> </td>
    <td><?=$data['from_user']['name']?> </td>
	<td><?=$data['amount']?> </td>											
	<td><?=$data['created']->format('d M Y');?> </td>
</tr>
<?php $count++; } ?>
<?php  if(count($listing->toArray()) < 1) {
	echo "<tr class='even'><td colspan = '6'>". __('No record found')."</td></tr>";
} ?>	
</tbody>
</table>
<?php $this->Paginator->options(array('url' => array('controller' => 'Btc', 'action' => 'reference_search')));
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
