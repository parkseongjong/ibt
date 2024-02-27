<style>
    .modal-open {
        overflow: hidden
    }
    .modal {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 1050;
        display: none;
        overflow: hidden;
        -webkit-overflow-scrolling: touch;
        outline: 0
    }
    .modal.fade .modal-dialog {
        -webkit-transform: translate(0, -25%);
        -ms-transform: translate(0, -25%);
        -o-transform: translate(0, -25%);
        transform: translate(0, -25%);
        -webkit-transition: -webkit-transform .3s ease-out;
        -o-transition: -o-transform .3s ease-out;
        transition: -webkit-transform .3s ease-out;
        transition: transform .3s ease-out;
        transition: transform .3s ease-out, -webkit-transform .3s ease-out, -o-transform .3s ease-out
    }

    .modal.in .modal-dialog {
        -webkit-transform: translate(0, 0);
        -ms-transform: translate(0, 0);
        -o-transform: translate(0, 0);
        transform: translate(0, 0)
    }

    .modal-open .modal {
        overflow-x: hidden;
        overflow-y: auto
    }

    .modal-dialog {
        position: relative;
        width: auto;
        margin: 10px
    }

    .modal-content {
        position: relative;
        background-color: #fff;
        background-clip: padding-box;

        border: 1px solid rgba(0, 0, 0, .2);
        border-radius: 6px;
        -webkit-box-shadow: 0 3px 9px rgba(0, 0, 0, .5);
        box-shadow: 0 3px 9px rgba(0, 0, 0, .5);
        outline: 0
    }

    .modal-content .service_contents td div input {
        box-sizing: border-box;
        height: 100%;
        font-size: 20px;
        padding: 12px 20px;
        margin: 10px 20px;
        display: inline-block;
        border: 1px solid #ccc;

    }



    .modal-backdrop {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 1040;
        background-color: #000
    }

    .modal-backdrop.fade {
        filter: alpha(opacity=0);
        opacity: 0
    }

    .modal-backdrop.in {
        filter: alpha(opacity=50);
        opacity: .5
    }

    .modal-header {
        padding: 15px;
        border-bottom: 1px solid #e5e5e5
    }

    .modal-header .close {
        margin-top: 2px;
        float: right;
    }

    .modal-title {
        margin: 0;
        line-height: 1.42857143;
        font-size: large;
        font-weight: bold;
    }

    .modal-body {
        position: relative;
        padding: 15px;
        align-content: center;
        align-items: end;
        text-align: center;
    }

    .modal-footer {
        padding: 15px;
        text-align: center;
        border-top: 1px solid #e5e5e5
    }

    .modal-footer .myBtn button{
        width: 30%;
        height: 40px;
        font-size: 18px;
        font-weight: 600;
        line-height: 2.1;
        color: #6738ff;
        border-radius: 5px;
        border: solid 1px #6738ff;
        background-color: #ffffff;
        cursor: pointer;
        alignment: center;
    }

    .modal-footer .myBtn button:hover:enabled {
        background-color: #6738ff;
        color: white;
    }

    .modal-footer .myBtn button:active:enabled {
        background-color: white;
        color: black;
        border: 2px solid #6738ff
    }

    .application .myBtn button:disabled{
        background-color: #d3ccea;
        color: white;
        border: 0;
    }
	.application .myBtn #authBtn {
        background-color: #d3ccea;
        color: white;
        border: 0;
    }
    .modal-scrollbar-measure {
        position: absolute;
        top: -9999px;
        width: 50px;
        height: 50px;
        overflow: scroll
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
    .alert {
        position: relative;
        padding: .75rem 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: .25rem;
    }
    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }
    /*.modal-footer .btn-group .btn+.btn {*/
    /*    margin-left: -1px*/
    /*}*/

    /*.modal-footer .btn-block+.btn-block {*/
    /*    margin-left: 0*/
    /*}*/

    .modal-scrollbar-measure {
        position: absolute;
        top: -9999px;
        width: 50px;
        height: 50px;
        overflow: scroll
    }

    @media (min-width:768px) {
        .modal-dialog {
            width: 450px;
            margin: 30px auto
        }
		.modal-dialog-auth {
			width: 800px;
		}
        .modal-content {
            -webkit-box-shadow: 0 5px 15px rgba(0, 0, 0, .5);
            box-shadow: 0 5px 15px rgba(0, 0, 0, .5)
        }
        .modal-sm {
            width: 300px
        }
    }

    @media (min-width:992px) {
        .modal-lg {
            width: 900px
        }
    }
</style>
<div class="container">
	<div class="profile_box">
		<?php echo $this->element('Front2/investment_menu'); ?>
		<div class="service_info">
			<div class="img_box img_box1 img_box_a">
				<img src="/wb/imgs/investment_index1-2.png" alt="investment" class="main_img" />
				<div class="">
					<p>
						<?=__('Staking Service') ?>
						<!-- <button>
							<span>
								<?=__('Details') ?>
							</span>
							<img src="/wb/imgs/investment_arrow.png" />
						</button> -->
					</p>
					
					<p><?=__('Staking Service for Revenue') ?></p>
					<!-- <hr align="left" /> -->
					<button class="details" onclick="openDetailDialog()">
						<span><?=__('Details') ?></span>
						<img src="/wb/imgs/investment_arrow-right-white.svg" />
					</button>
					<p><?=__('Deposit Service Profit') ?></p>
					<p><?=__('Deposit Service increase and Profit') ?></p>
				</div>

				<a class="check-terms-area" href="/front2/document/terms-staking">
					<span><?=__('Go to Terms') ?></span>
					<img src="/wb/imgs/investment_arrow-right-black.svg" />
				</a>
			</div>
			<ul class="ul_ex">
				<li>
					<p><?=__('Usage Fee') ?></p>
					<p><?=__('Free') ?></p>
				</li>
				<li class="bar"></li>
				<li>
					<p><?=__('Maximum Limit') ?></p>
					<p><?=__('Fifty thousand coins') ?>~<?=__('Two million coins') ?></p>
				</li>
				<li class="bar"></li>
				<li>
					<p><?=__('Period') ?></p>
					<p><?=__('Deposit Service Period') ?></p>
				</li>
			</ul>

			<div class="btn_box">
				<a class="btn_left" href="<?php echo $this->Url->Build(['controller'=>'investment','action'=>'history']) ?>" >
					<?=__('Usage History') ?>
				</a>
				<?php 
					if($stage == 0){ 
				?>
						<!--<a class="btn_right" href="javascript:void(0)" onclick="openDialog()" >-->
                        <a class="btn_right" href="<?php echo $this->Url->Build(['controller'=>'investment','action'=>'application']) ?>" >
                            <?=__('Apply for Staking Service') ?>
						</a>
                        <a class="btn_right" href="<?php echo $this->Url->Build(['controller'=>'investment','action'=>'applicationdev']) ?>" >
                            스테이킹 개발용페이지
                        </a>
				<?php
					} else {
				?>
						<a class="btn_right" href="<?php echo $this->Url->Build(['controller'=>'investment','action'=>'application']) ?>" >
                            <?=__('Apply for Staking Service') ?>
						</a>
                        <a class="btn_right" href="<?php echo $this->Url->Build(['controller'=>'investment','action'=>'applicationdev']) ?>" >
                            스테이킹 개발용페이지
                        </a>
				<?php } ?>
			</div>

			<div class="text_box2">
				<!-- <?=__('Deposit Service Notice') ?> -->
				<ul>
					<li>
						<?=__('staking text 001') ?>
					</li>
					<li>
						<?=__('staking text 002') ?>
					</li>
					<li>
						<?=__('staking text 003') ?>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<div id="myModalDeposit" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <span><?=__('Service Check Information')?></span>
                <button type="button" class="close" data-dismiss="modal" >&times;</button>
            </div>
            <div class="modal-body">
                <p><?=__('Service Check Information Text')?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close2')?></button>
            </div>
        </div>

    </div>
</div>
<!-- detail-dialog -->
<div class="detail-dialog modal" id="detail-dialog" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<h1 class="modal-title">
				<?= __('Staking Service')?>
			</h1>
			<h2 class="modal-subtitle">
				<?= __('staking modal 001')?>
			</h2>

			<ul class="desc-list">
				<li class="desc-list-item">
					<?= __('staking modal 002')?>
				</li>
				<li class="desc-list-item">
					<?= __('staking modal 003')?>
				</li>
				<li class="desc-list-item">
					<?= __('staking modal 004')?>
				</li>
				<li class="desc-list-item">
					<?= __('staking modal 005')?>
				</li>
			</ul>

			<div class="modal-confirm-button" onclick="closeDetailDialog()">
				<?= __('Confirm') ?>
			</div>
		</div>
	</div>
</div>
<!-- /detail-dialog -->

<script>
	function openDialog(){
        $("#myModalDeposit").modal('show');
    }

    function openDetailDialog() {
		$('#detail-dialog').modal('show');
	}
	function closeDetailDialog() {
		$('#detail-dialog').modal('hide');
	}
</script>