
		 <table class="table table-striped">
			<thead>
				<tr>
				<th>Sr No.</th>
				  <th>Price Per <?php echo $secondCoin; ?></th>
				  <th><?php echo $secondCoin; ?> Amount</th>
				  <th><?php echo $firstCoin; ?> Amount</th>
				  <th>Status</th>
				  <th>Date</th>
				  <th><i class="fa fa-times"></i></th>
				</tr>
			<thead>
			<tbody>
			<?php
			 $count= $serial_num;
			foreach($getOrderList as $singleData) { 
			$action = "&nbsp";
			$showAmount = $singleData['total_buy_get_amount'];
			if($singleData['status']=='pending') {
				$action = "<a href='javascript:void(0)' id='buy_".$singleData['id']."' onClick='deleteOrder(this.id)'>Delete</a>";
				$showAmount = $singleData['buy_get_amount'];
			}
		
			?>
				<tr >
				<td><?php echo $count; ?></td>
				<td ><?php echo number_format($singleData['per_price'],8); ?></td>
				<td ><?php  echo $showAmount; ?></td>
				<td ><?php echo $showAmount*$singleData['per_price']; ?></td>
				<td ><?php echo  ucfirst($singleData['status']); ?></td>
				<td ><?php echo date('d M, Y',strtotime($singleData['created_at'])); ?></td>
				<td><?php echo $action; ?></td>
				</tr>
			<?php $count++; } ?>
			</tbody>
		</table>
		<?php $this->Paginator->options(array('url' => array('controller' => 'Users', 'action' => 'mybuyorderlistSearch',$firstCoin,$secondCoin)));
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
