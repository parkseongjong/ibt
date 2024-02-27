<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

<style>
    .jconfirm .jconfirm-scrollpane{
        width: auto !important;
        margin: auto !important;
    }
    .replied-btn {border: 0 !important; border-radius: 0px !important; background-color: #240978 !important; color: #fff !important; font-size: 11px !important; width: auto !important; height: auto !important; font-weight: 0 !important;}
</style>
<div class="container">

	<div class="custom_frame">

		<?php
		echo $this->element('Front2/customer_left');

		$keyword = !empty($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '';
		//$keyword = !empty($this->request->data['keyword']) ? $this->request->data['keyword'] : '';
		
		$page = !empty($_REQUEST['page']) ? $_REQUEST['page'] : 1;
		//$page = !empty($this->request->data['page']) ? $this->request->data['page'] : 1;

		?>
		<?= $this->Flash->render() ?>
		<div class="contents">
			<ul class="search_box">
				<li class="title"><?=__($board_title[$kind]) ?></li>
				<li class="search">
					<div>
						<?php
						echo $this->Form->create('Post');
						?>
						<input type="text" name="keyword" class="" placeholder="<?=__('Search in bulletin board') ?>" />
						<?php
						echo $this->Form->submit('/wb/imgs/search.png' );
						echo $this->Form->end();
						?>
					</div>
				</li>
			</ul>
         <div class="table_scrool table_scrool222">
			<table class="list">
				<thead>
					<tr>
						<?php if ( $kind == 'notice' ) { ?>
							<th style="width:60px"><?=__('No.') ?></th>
						<?php } else if ( $kind == 'qna' ) { ?>
							<th style="width:60px"><?=__('Progress') ?></th>
						<?php } ?>
						
						<th style="text-align:left"><?=__('Title') ?></th>
                        <?php
                        if(!empty($_SESSION['Auth']['User']['user_type']) && $kind!='notice'){
                            if($_SESSION['Auth']['User']['user_type']=='A'){
                                ?>
                                <th><?=__("User's Name")?> </th>
                                <?php
                            }
                        }
                        ?>
						<?php
						
						$vardata=explode("/",$_SERVER[ 'REQUEST_URI' ]);

							if($kind!='notice'){


								if(!empty($_SESSION['Auth']['User']['user_type'])){
							if($_SESSION['Auth']['User']['user_type']=='A'){
								?>
						        <?php } }?>
						        <th><?=__('Date & Time') ?></th>
						<?php
							
						}
						?>
						<?php
						if(!empty($_SESSION['Auth']['User']['user_type']) && $kind!='notice'){
							if($_SESSION['Auth']['User']['user_type']=='A'){
								?>
								<th><?=__('Status')?> </th>
								<?php
							}
						}
						?>
					</tr>
				</thead>
				<tbody>
					<?php 
					
					if ( $kind == 'notice' ) {
						//$i = $listingcount - ( $limit * ($page-1) );
						$i = (($page - 1) * $limit) + 1;
						if ( !empty($listing) ) {
							foreach($listing as $k=>$data){
								?><tr>
									<td><?php echo $i; ?> </td>
									<td><a href="<?php echo $this->Url->Build(['controller'=>'customer','action'=>'view',$kind,$data['id'],$page,$keyword]) ?>"><?php echo !empty($data['category']) ? '['.__('Boardcategory '.$data['category']).']' : ''; ?> <?php echo h($data['subject']); ?></a></td>
									
									
								</tr><?php
								$i = $i + 1;
							}
						}
					} else if ( $kind == 'qna' ) {
						if ( !empty($listing) ) {
							foreach($listing as $k=>$data){
								$status = $data['status'];
								?><tr>
									<td><?php echo __('BoardQnaStatus_'.$status); ?></td>
                                <?php if(!empty($_SESSION['Auth']['User']['user_type'])){
                                    if($_SESSION['Auth']['User']['user_type']=='A'){
                                        ?>
                                        <td><a  href="<?= $this->Url->Build(['controller'=>'customer','action'=>'updatereply', $data['id']])." id=replay_data1_".$data['id']." data-id=".$data['id']?>" ><?php echo !empty($data['category']) ? '['.__('Boardcategory '.$data['category']).']' : ''; ?> <?php echo h($data['subject']); ?></a></td>
                                    <?php } else{ ?>
                                        <td><a href="<?php echo $this->Url->Build(['controller'=>'customer','action'=>'view',$kind,$data['id'],$page,$keyword]) ?>"><?php echo !empty($data['category']) ? '['.__('Boardcategory '.$data['category']).']' : ''; ?> <?php echo h($data['subject']); ?></a></td>
                                    <?php } }?>
                            <?php if(!empty($_SESSION['Auth']['User']['user_type'])){
                                if($_SESSION['Auth']['User']['user_type']=='A'){
                                ?>
                                <td><?php echo  $data['users_name']?></td>
                                    <?php } } ?>

									<td><?php echo $data['created']->format('Y-m-d H:i:s'); ?></td>
									<?php
						if(!empty($_SESSION['Auth']['User']['user_type'])){
							if($_SESSION['Auth']['User']['user_type']=='A'){
								?>
							<td><?php 
								if(empty($data['reply'])){
                                    echo "<a  href=". $this->Url->Build(['controller'=>'customer','action'=>'updatereply', $data['id']])." id=replay_data1_".$data['id']." class='button_click btn btn-success' data-id=".$data['id']  .">". __("Reply") ." </a>";
								}else{

                                    echo "<button disabled='disabled' class='btn btn-success replied-btn'>". __("Replied") ."</button>";
								}
							 ?>
							</td>
							<?php
					}
						}
						?>
								</tr>
								<?php
							}
						}
					}
					?>
				</tbody>
			</table>
			</div>

			<?php
				
			$paginator = $this->Paginator;
			$paginator->options(array('url'=>array_merge(array('keyword'=>$keyword, 'controller'=>'customer', 'action'=>$kind)) ));
			echo "<ul class='page_nav'>";
			echo $paginator->first("<<");
			if($paginator->hasPrev()){
				echo $paginator->prev("<");
			}
			echo $paginator->numbers(array('modulus' => 5));
			if($paginator->hasNext()){
				echo $paginator->next(">");
			}
			echo $paginator->last(">>");
			echo "</ul>";
			?>
			
			<?php if ( $kind != 'notice' && $_SESSION['Auth']['User']['user_type'] != 'A' ) { ?>
				<div class="button_block">
                    <a href="<?php echo $this->Url->Build(['controller'=>'customer','action'=>'edit', $kind]) ?>" class="button"><?=__('Writing')?></a>
				</div>
			<?php } else{

                        if(!empty($_SESSION['Auth']['User']['user_type'])){
                            if($_SESSION['Auth']['User']['user_type']=='A' && $kind != 'qna'){
			?><div class="button_block">
			<a href="<?php echo $this->Url->Build(['controller'=>'customer','action'=>'edit', $kind]) ?>" class="button"><?=__('Create Notice')?></a>
</div><?php
}
}
}
?>
		</div>
		<div class="cls"></div>

	</div>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script> 

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>  

<script>
toastr.options = {
  "closeButton": false,
  "debug": false,
  "newestOnTop": false,
  "progressBar": false,
  "positionClass": "toast-top-right",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}
// $(".button_click").click(function(){

// 			var id=$(this).data("id");
			
// 		$.confirm({
//     title: 'Reply!',
//     content: '' +
//     '<form action="" class="formName">' +
//     '<div class="form-group">' +
// 	'<input type="text" placeholder="Reply" class="reply form-control" required />' +
//     '</div>' +
//     '</form>',
//     buttons: {
//         formSubmit: {
//             text: 'Submit',
//             btnClass: 'btn-blue',
//             action: function () {
//                 var name = this.$content.find('.reply').val();
//                 if(!name){
//                     $.alert('Please Enter Reply');
//                     return false;
//                 }
//                 $.ajax({
//             url:'<?php echo $this->Url->build(["controller"=>"Customer","action"=>"updatereply"]) ?>//',
//            type:'post',
//            data:{reply:this.$content.find('.reply').val(),id:id},
//            dataType:'JSON',
//            success:function(resp){
            
// 				toastr.success("Sucess")
// 				window.location.reload();

//            }
//        });
       
//            }
//        },
//        cancel: function () {
//            //close
//        },
//    },
//    onContentReady: function () {
//        // bind to events
//        var jc = this;
//        this.$content.find('form').on('submit', function (e) {
//            // if the user submits the form by pressing enter in the field.
           
// 		});
//    }
// });

// 		});
</script>

