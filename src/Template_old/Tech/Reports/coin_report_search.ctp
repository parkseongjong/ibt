 <table id="table-two-axis" class="two-axis table">
			<thead>
			<tr> 
				<th>#</th>
				<th>Date</th>
				<th>Eth Deposit</th>
				<th>Eth Withdrawal</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$count= $serial_num;
			

			
			
			foreach($getDateTrans->toArray() as $k=>$data){
			if($k%2==0) $class="odd";
			else $class="even";
			?>
			<tr class="<?=$class?>" id="user_row_<?= $data['id']; ?>">
				<td><?=$count?></td>
				<td><?php echo $data['showdate']; ?></td>
				<td><?php echo number_format((float)$data['totalpurchase'],8); ?></td>
				<td><?php echo number_format((float)abs($data['totalwithdrawal']),8); ?></td>
			</tr>
			<?php $count++;} ?>
			</tbody>
		</table>
		<?php $this->Paginator->options(array('url' => array('controller' => 'Reports', 'action' => 'ethReportSearch')));
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