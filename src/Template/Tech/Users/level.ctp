<style>
	.input-style{border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px}
    /* The Modal (background) */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }

    /* Modal Content/Box */
    .modal-content {
        background-color: #fefefe;
        margin: 5% auto; /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 50%; /* Could be more or less, depending on screen size */
    }
    /* The Close Button */
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }
    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
</style>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1><?=__("Level Management");?></h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i><?=__("Home");?></a></li>
			<li class="active"><?=__("Level Management");?></li>
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
								<h3 class="w3_inner_tittle two"></h3>
								<a class="btn btn-info" href="<?php echo $this->Url->build(['controller'=>'Users','action'=>'addlevel']) ?>"><?=__("Add");?></a>
							</div>
							<?= $this->Flash->render() ?>
							<table id="table-two-axis " class="two-axis table dataTable">
								<thead>
									<tr>
										<th><?=__("S No.");?></th>
										<th><?=__("Level");?></th>
										<th><?=__("Pages");?></th>
										<th><?=__("Status");?></th>
										<th><?=__("Created");?></th>
										<th class="column-title no-link last"><span class="nobr"><?=__("action");?></span>
									</tr>
								</thead>
								<tbody>
									<?php
										$count= 1;
										foreach($listing->toArray() as $k=>$data){
											if($k%2==0) $class="odd";
											else $class="even";
									?>
										<tr style="text-align:center;" class="<?=$class?>" id="user_row_<?= $data['id']; ?>">
											<td> <?php echo $count; ?> </td>
											<td> <?php echo $data['level_name']; ?> </td>
											<td class="total_token_<?= $data['id']; ?>" ><button type="button" class="btn btn-info btn-xs" onclick="show_pages(<?= $data['id']; ?>)" ><?=__("Show Pages");?></button>
											</td>
											<td><?php echo $data['status']; ?></td>
											<td><?php echo $data['created']->format('Y-m-d H:i:s'); ?></td>
											<td class=" last">
												<!--<a href="<?php //echo $this->Url->build(['controller'=>'Coin','action'=>'changestatus',$data['id']]) ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> <?php //echo $buttonText; ?> </a>-->
												<a href="<?php echo $this->Url->build(['controller'=>'Users','action'=>'editlevel',$data['id']]) ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i><?=__("Edit");?></a>
												<!--<a href="javascript:void(0)" onclick="delete_section(<?php //echo $data['id'] ?>)" class="btn btn-danger btn-xs"><i class="fa fa-close"></i> Delete </a>-->
											</td>
										</tr>
									<?php $count++; } ?>
									<?php  if(count($listing->toArray()) < 1) {
										echo "<tr class='even'><td colspan = '8'>No record found</td></tr>";
									} ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="myModal" class="modal">
			<!-- Modal content -->
			<div class="modal-content">
				<span class="close">&times;</span>
				<div id="text_area">

				</div>
			</div>
		</div>
	</section>
</div>
<script>
	function delete_section(id){
		bootbox.confirm("Are you sure?", function(result) {
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
								  title: 'Success',
								  text: 'Record Delete successfully!',
								  type: 'success',
								  styling: 'bootstrap3',
								  delay:1200
							  });

						}if(data == 'forbidden'){

							new PNotify({
								  title: '403 Error',
								  text: 'You donot have permission to access this action.',
								  type: 'error',
								  styling: 'bootstrap3',
								  delay:1200
							  });

						}
					},
					error: function (request) {
						new PNotify({
								  title: 'Error',
								  text: 'This record is being referenced in other place. You cannot delete it.',
								  type: 'error',
								  styling: 'bootstrap3',
								  delay:1200
							  });

					},
				});
			}
		});

	}
	var modal = document.getElementById('myModal');
    var span = document.getElementsByClassName("close")[0];
    span.onclick = function() {
        modal.style.display = "none";
    }
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
	function show_pages(id){
		$('#text_area').empty();
		$.ajax({
            type: 'post',
			dataType : 'json',
            url: '/tech/users/getlevelpages',
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
			},
            data: {
				"id" : id
            },
            success:function(resp) {
				var status = resp['status'];
				if(status == 'true'){
					var data = resp['data'];
					var html = '';
					$.each(data, function(idx, item){
						html += '<p>'+(idx+1) + ' : <a href="'+item.url+'" target="_blank">' + item.menu_name+'</a></p>';
					})
					$('#text_area').html(html);
					$('#myModal').css('display','block');
				}
            },
			error:function(request,status,error){
				console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
		    }

        });

	}

</script>
