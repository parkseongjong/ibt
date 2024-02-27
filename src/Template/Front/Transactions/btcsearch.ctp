<table class="table table-inverse m-b-0">
	  <thead>
		<tr>
		  <th>ID</th>
		  <th>Transaction Id</th>
		  <th>Amount</th>
		  <th>Transaction Type</th>
		  <th>STATUS</th>
		  <th>DATE</th>
		</tr>
	  </thead>
	  <?php if(!empty($btcTrans)) {
		  
		  $i = $serial_num;
		foreach($btcTrans->toArray() as $single) {	
		?>
	  <tbody>
		<tr>
		  <td><?php echo $i; ?></td>
		  <td><?php echo $single['trans_id']; ?></td>
		  <td><?php echo number_format((float)abs($single['btc_coins']),8) ?> BTC</td>
		  <td><?php echo ucfirst($single['trans_type']); ?></td>
		  <td><?php echo ucfirst($single['status']); ?></td>
		  <td><?php echo date("M d, Y h:i A",strtotime($single['created_at'])); ?></td>
		</tr>
	  </tbody>
		<?php $i++; }
		}
		?>
	</table>
	
	<?php $this->Paginator->options(array('url' => array('controller' => 'Transactions', 'action' => 'btcsearch')));
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