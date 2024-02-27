<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <?php echo $title; ?> <small><?= __('Logs'); ?></small> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home'); ?></a></li>
            <li class="active"><?php echo $title; ?></li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="content" class="table-layout">

        <div class="inner_content_w3_agile_info">
                <div class="agile-tables">
                <div class="w3l-table-info agile_info_shadow">
			<div class="clearfix"></div>

			 <form method="post">
				<?php echo $this->Form->input('id',array('type'=>'hidden', 'value'=>$userId)); ?>
			</form>
       
                    <h3 class="w3_inner_tittle two"><?= __('Information'); ?></h3>

                    <div class="table-responsive">
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
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>



<script>
jQuery('.table-responsive').on('click','.pagination li a',function(event){
	event.preventDefault() ;
	var keyy = $('form').serialize();
	var urli = jQuery(this).attr('href');
	jQuery.ajax({ 
		url: urli,
		data: {key:keyy},
		type: 'POST',
		success: function(data) {
			if(data){
				jQuery('.table-responsive').html(data);
			}
		}
	});
});
</script>