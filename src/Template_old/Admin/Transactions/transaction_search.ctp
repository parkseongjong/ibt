<table id="table-two-axis" class="two-axis table">
	<thead>
	<tr>
		<th>S No.</th>
		<th>Username</th>
		<th>Wallet Address</th>
		<th>Transaction Type</th>
		<th>Remark</th>
		<th>Btc Received</th>
		<th>Date</th>
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
		<td><?php echo $data['wallet_address'];?> </td>
		<td><?php echo $data['trans_type'];?> </td>
		<td><?php echo $data['coin_type'];?> </td>
		<td><?php echo number_format((float)abs($data['btc_coins']),8)?> </td>
		<td><?=$data['created_at']->format('d M Y H:i:s');?> </td>
		
		
	</tr>
	<?php $count++; } ?>
	<?php  if(count($listing->toArray()) < 1) {
		echo "<tr class='even'><td colspan = '6'>No record found</td></tr>";
   } ?>	
	</tbody>
</table>
<?php $this->Paginator->options(array('url' => array('controller' => 'Transactions', 'action' => 'transaction_search')));
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
