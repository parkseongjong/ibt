<style>
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
    .upload_img:hover{cursor: pointer;}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><a href="<?php echo $this->Url->build(['controller'=>'assetWaiver','action'=>'assetlist']);  ?>">자산포기각서 리스트</a> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home');?></a></li>
            <li class="active"><a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'assetlist']);  ?>">자산포기각서 리스트</a></li>
        </ol>
    </section>
	<?php echo $this->Form->create('',array('method'=>'post'));?>
	<?php echo $this->Form->end();?>
    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-tables">
            <div class="w3l-table-info agile_info_shadow">
				<div class="clearfix"></div>
				<div class="clearfix"></div>
                    <div class="table-responsive">
                    <table id="table-two-axis" class="two-axis table" >
                        <thead>
							<tr style="text-align: center;vertical-align: middle">
								<th>No</th>
								<th>회원명(회원번호)</th>
								<th>남은 자산</th>
								<th>미리보기</th>
								<th>다운로드</th>
								<th>신청일</th>
								<th>승인일</th>
								<th>승인</th>
							</tr>
                        </thead>
                        <tbody>
                        <?php foreach($asset_waiver_list as $l){ 
							$btn_name = '대기';
							if($l->is_leaving == 'N') $btn_name = '반려';
							if($l->is_leaving == 'Y') $btn_name = '탈퇴완료';
						?>
							<tr>
								<td><?= $l->id; ?></td>
								<td><?= $l->name. '('.$l->user_id.')'; ?></td>
								<td><?= number_format($l->total_remain,2); ?></td>
								<td><img src="/<?= $l->path .'/'.$l->save_file_name; ?>" width="50" alt="<?=$l->save_file_name;?>" class="upload_img"></td>
								<td><a href="/tech/assetWaiver/filedownload/<?=$l->save_file_name;?>" class="btn btn-xs btn-info">다운로드</a></td>
								<td><?= $l->created->format('Y-m-d H:i:s'); ?></td>
								<td><?= $l->approve_date != null ? $l->approve_date->format('Y-m-d H:i:s') : ''; ?></td>
								<td id="td_<?= $l->id; ?>">
									<?php 
										if($l->is_leaving != 'P'){
									?>
										<button type="button" class="btn btn-xs btn-success" disabled ><?=$btn_name;?></button>
									<?php	
										} else {
									?>
										<div class="dropdown" id="dropdown_<?= $l->id; ?>">
											<button class="btn btn-primary dropdown-toggle btn-xs" type="button" data-toggle="dropdown" ><?= __('action');?><span class="caret"></span></button>
											<ul class="dropdown-menu">
												<li><a href="javascript:void(0)" onclick="leaving(<?=$l->id;?>,'Y')" class="btn btn-info btn-xs text-white"><i class="fa fa-pencil"></i> <?= __('승인');?> </a></li>
												<li><a href="javascript:void(0)" onclick="leaving(<?=$l->id;?>,'N')" class="btn btn-danger btn-xs text-white"><i class="fa fa-close"></i> <?= __('반려');?> </a></li>
											</ul>
										</div>
									<?php } ?>
								</td>
							</tr>
						<?php }?>
                        </tbody>
                    </table>
					 <?php 
						$searchArr = [];
						foreach($this->request->query as $singleKey=>$singleVal){
							$searchArr[$singleKey] = $singleVal;
						}
						$this->Paginator->options(array('url' => array('controller' => 'AssetWaiver', 'action' => 'assetlist')+$searchArr));
						echo "<div class='pagination' style = 'float:right'>";
						$paginator = $this->Paginator;
						echo $paginator->first(__("First"));
						if($paginator->hasPrev()){
							//echo $paginator->prev(__("Prev"));
						}
						echo $paginator->numbers(array('modulus' => 9));
						if($paginator->hasNext()){
							//echo $paginator->next(__("Next"));
						}
						echo $paginator->last(__("Last"));
						echo "</div>";
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
	<div id="myModal" class="modal">
		<!-- Modal content -->
		<div class="modal-content">
			<span class="close">&times;</span>
			<div id="img_area">
				<img id="modal_img" src="" width=90%;>
			</div>
		</div>
	</div>
</div>
<script>
	function leaving(id,status){
		let msg = '반려';
		if (status == 'Y'){ msg = '승인'; }
		msg += '하시겠습니까?'
		if(confirm(msg)){
			$.ajax({
				type: 'post',
				url: '/tech/asset-waiver/realLeaving',
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
					$('#dropdown_'+id).hide();
					$('#td_'+id).html('<img src="/ajax-loader.gif"/>');
				},
				data: {
					"id" : id,
					"status" : status,
				},
				success:function(resp) {
					location.reload();
				}, 
				error:function(request,status,error){
					console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
				}
			});
		}
		return;
	}
	/* img blow up */
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

    $(function(){
        $('.upload_img').on('click',function(){
            var src = $(this).attr('src');
            $('#modal_img').attr('src',src);
            $('#myModal').css('display','block');
        });
    })
</script>