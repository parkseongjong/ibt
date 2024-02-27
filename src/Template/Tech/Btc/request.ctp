<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> BTC <small><?= __('Requests'); ?></small> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home'); ?></a></li>
            <li class="active">BTC <?= __('Requests'); ?></li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="content" class="table-layout">

        <div class="inner_content_w3_agile_info">

            <div class="clearfix"></div>
            <div class="row agile-tables">
                <div class="w3l-table-info agile_info_shadow table-responsive">
                    <h3 class="w3_inner_tittle two"><?= __('Transactions'); ?></h3>
                    <table id="table-two-axis" class="two-axis table">
                        <thead>
                        <tr>
                            <th><?= __('#'); ?></th>
                            <th><?= __('Username'); ?></th>
                            <th><?= __('Coins'); ?></th>
                            <th><?= __('Transaction ID'); ?></th>
                            <th><?= __('Status'); ?></th>
                            <th><?= __('Date'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $count= 1;
                        $arr = array('P'=>'Pending','A'=>'Accepted','R'=>'Rejected');
                        foreach($listing->toArray() as $k=>$data){
                       // pr($data);die;
                            if($k%2==0) $class="odd";
                            else $class="even";
                            ?>
                            <tr class="<?=$class?>">
                                <td> <?=$count?></td>
                                <td><?php echo $data['user']['name']; ?></td>
                                <td><?=number_format((float)$data['amount'],8)?></td>
                                <td><?=$data['transaction_id']?></td>
                                <td id="row_<?php echo $data['id'] ?>"><?php
                                    if($data['status'] == 'P')
                                    {
                                        echo '<button class="btn btn-xs btn-success" onclick="request_check(1,'.$data["id"].')">'.__('Yes').'</button>'.' '.'<button class="btn btn-xs btn-danger"  onclick="request_check(0,'.$data["id"].')">'.__('No').'</button>';
                                    }
                                    else if($data['status'] == 'A')
                                    {
                                        echo 'Accepted';
                                    }
                                    else if($data['status'] == 'R')
                                    {
                                        echo 'Rejected';
                                    }
                                    //echo $arr[$data['status']]?> </td>
                                <td><?=$data['created']->format('d M Y');?> </td>
                            </tr>
                            <?php $count++; } ?>
                        <?php  if(count($listing->toArray()) < 1) {
                            echo "<tr class='even'><td colspan = '6'>".__('No pending requests')."</td></tr>";
                        } ?>
                        </tbody>
                    </table>
                    <?php $this->Paginator->options(array('url' => array('controller' => 'Btc', 'action' => 'request')));
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
                </div>
            </div>
        </div>
    </section>
</div>
<script>
function request_check(val,id) {
	if(confirm("<?= __('Are you sure?');?>"))
	{
		$.ajax({
			type : 'POST',
			url:'<?php echo $this->Url->build(['controller'=>'Btc','action'=>'request_update']); ?>',
			data:{'val':val,'id':id},
			dataType : 'JSON',
			success : function (res) {
				if(res=='')
				{
					new PNotify({
						title: '<?= __('Success!');?>',
						text: '<?= __('Request changed successfully!');?>',
						type: 'success',
						styling: 'bootstrap3',
						delay:1200
					});
					if(val == 0)
					{
						$('#row_'+id).text('<?= __('Rejected'); ?>');
					}
					else if(val == 1){
						$('#row_'+id).text('<?= __('Accepted'); ?>');
					}


				}
				else {

						new PNotify({
							title: '<?= __('403 Error'); ?>',
							text: '<?= __('Request not updated!'); ?>',
							type: 'error',
							styling: 'bootstrap3',
							delay:1200
						});
				}
			}
		});
	}
}
</script>
