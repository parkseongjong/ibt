<style>
.input-style{border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <?= __('Coin Management'); ?> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home'); ?></a></li>
            <li class="active"><?= __('Coin Management'); ?></li>
        </ol>
    </section>
    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="clearfix"></div>
             <div class="w3agile-validation w3ls-validation ">
				<div class="w3agile-validation w3ls-validation mt20">
                    <div class="agile-validation agile_info_shadow">
                        <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                            <div class="input-info">
                                <h3 class="w3_inner_tittle two"><?= __('List of Coins'); ?></h3>
								<a class="btn btn-info" href="<?php echo $this->Url->build(['controller'=>'Coin','action'=>'add']) ?>"><?= __('Add new coin'); ?></a>
                            </div>
							 <?= $this->Flash->render() ?>
                            <table id="table-two-axis " class="two-axis table dataTable">
							<thead>
								<tr>
									<th>#</th>
									<th><?= __('Coin Name'); ?></th>
									<th><?= __('Coin Short Name'); ?></th>
									<th><?= __('Icon'); ?></th>
									<th><?= __('Price in USD'); ?></th>
									<th><?= __('Total Coins'); ?></th>
									<th><?= __('Status'); ?></th>
									<th class="column-title no-link last"><span class="nobr"><?= __('Action'); ?></span>
								</tr>
							</thead>
							<tbody>
							<?php
							$count= 1;
								
							 foreach($listing->toArray() as $k=>$data){
								if($k%2==0) $class="odd";
								else $class="even";
								$statusArr = [0=>'Pending',1=>'Active'];
								$buttonText = ($data['status']==0) ? 'Activate' : 'Deactivate';
								
							?>
							<tr style="text-align:center;" class="<?=$class?>" id="user_row_<?= $data['id']; ?>">
								<td> <?php echo $data['serial_no']; ?> </td>
								<td class="total_token_<?= $data['id']; ?>" ><?php echo $data['name']; ?> </td>
								<td class="sold_token_<?= $data['id']; ?>" ><?php echo $data['short_name']; ?> </td>
								<td class="sold_token_<?= $data['id']; ?>" >
								<?php if(!empty($data['icon'])){ ?>
									<img width=40 src="<?php echo '/uploads/cryptoicon/'.$data['icon']; ?>" /> 
								<?php } ?>
								</td>
								<td class="token_value_<?= $data['id']; ?>"><?php echo $data['usd_price'];?> </td>
								<td class="token_value_<?= $data['id']; ?>"><?php echo $data['total_coin'];?> </td>
								<td class="btc_value_<?= $data['id']; ?>"><?php echo __($statusArr[$data['status']]); ?> </td>
								<td class=" last">
									<a href="<?php echo $this->Url->build(['controller'=>'Coin','action'=>'changestatus',$data['id']]) ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> <?php echo __($buttonText); ?> </a>
									<a href="<?php echo $this->Url->build(['controller'=>'Coin','action'=>'edit',$data['id']]) ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> <?= __('Edit'); ?> </a>
									<a href="javascript:void(0)" onclick="delete_section(<?php echo $data['id'] ?>)" class="btn btn-danger btn-xs"><i class="fa fa-close"></i> <?= __('Delete'); ?> </a>
								</td>				
							</tr>
							<?php $count++; } ?>
							<?php  if(count($listing->toArray()) < 1) {
								echo "<tr class='even'><td colspan = '8'>".__('No record found')."</td></tr>";
						   } ?>	
							</tbody>
						</table>
						   <?php $this->Paginator->options(array('url' => array('controller' => 'Coin', 'action' => 'index')));
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
							echo $paginator->numbers(array('modulus' => 9));

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
	function delete_section(id){
		bootbox.confirm("<?= __('Are you sure?'); ?>", function(result) {
			if(result == true){
				jQuery.ajax({ 
					//url: 'delete',
					url: '<?php echo $this->Url->build(['controller'=>'Coin','action'=>'deleteProgram']); ?>',
					beforeSend: function(xhr){
						xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
					},
					data: {'id':id},
					type: 'POST',
					success: function(data) {
						if(data == 1){
							jQuery("#user_row_"+id).remove();
							new PNotify({
								  title: '<?= __('Success!'); ?>',
								  text: '<?= __('Record deleted successfully!'); ?>',
								  type: 'success',
								  styling: 'bootstrap3',
								  delay:1200
							  });
							
						}if(data == 'forbidden'){
							
							new PNotify({
								  title: '<?= __('403 Error'); ?>',
								  text: '<?= __('You do not have permission to perform this action'); ?>',
								  type: 'error',
								  styling: 'bootstrap3',
								  delay:1200
							  });
							
						}
					},
					error: function (request) {
						new PNotify({
								  title: '<?= __('Error'); ?>',
								  text: '<?= __('This record is being referenced at other place. So, you cannot delete it.'); ?>',
								  type: 'error',
								  styling: 'bootstrap3',
								  delay:1200
							  });
					},
				});
			}
		});
	}
</script>