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
	.input-area {
		margin:30px auto;
	}
	.input-area input{
		width:45%;
		margin-left: 8px;
	}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <a href="<?php echo $this->Url->build(['controller'=>'Employees','action'=>'editEmployee',$id]);  ?>">임직원 수정</a></h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i><?=__("Home");?></a></li>
            <li class="active"> <a href="<?php echo $this->Url->build(['controller'=>'Employees','action'=>'editEmployee',$id]);  ?>">임직원 수정</a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-tables">
                <div class="w3l-table-info agile_info_shadow">
                    <div class="clearfix"></div>
					<?php echo $this->Form->create(null,array('method'=>'post','id'=>'frm')); ?>
						<div id="" class="mt20 table-responsive" >
							<table class="two-axis table" id="historyData">
								<thead style="background: #d3ccea; font-size: 16px;">
									<tr>
										<th style="color:#fff"><?=__("Name");?></th>
										<th style="color:#fff"><?=__("Phone Number");?></th>
										<th style="color:#fff"><?=__("Created");?></th>
										<th style="color:#fff"><?=__("Updated");?></th>
										<th style="color:#fff"><?=__("Last Worker");?></th>
									</tr>
								</thead>
								<tbody id="transferHistoryList">
									<tr>
										<td><input type="text" id="name" name="name" value="<?=$employee->name;?>" placeholder="<?=__("Name")?>" class="form-control"></td>
										<td><input type="text" id="phone_number" name="phone_number" value="<?=$employee->phone_number;?>" placeholder="<?=__("Phone Number")?>" class="form-control"></td>
										<td><input type="text" value="<?=$employee->created->format('Y-m-d H:i:s');?>" readonly class="form-control"></td>
										<td><input type="text" value="<?=$employee->updated->format('Y-m-d H:i:s');?>" readonly class="form-control"></td>
										<td><input type="text" value="<?=$employee->admin_name;?>" readonly class="form-control"></td>
									</tr>
								</tbody>
							</table>
							<div style="text-align: center;">
								<?= $this->Flash->render() ?>
								<button type="button" class="btn btn-info" onclick="submit_chk()"><?=__("Edit");?></button>
							</div>
						</div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
	function submit_chk(){
		if($('#name').val() == ''){
			alert('이름을 입력해주세요');
			return;
		}
		if($('#phone_number').val() == ''){
			alert('전화번호를 입력해주세요');
			$('#days').focus();
			return;
		}
		$('#frm').submit();
	}
</script>
