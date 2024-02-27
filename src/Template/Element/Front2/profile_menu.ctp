<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/profile2.css?id=<?php echo time(); ?>" />
<style>
.tab_menu li{
	width:20%;
}
.tab_menu li:nth-child(1) {
    width: 20%;
    border-left: solid 1px #b5b5b5;
}
</style>
		<!--<div class="page_title">
			<? //=__('Mypage') ?>
		</div>-->

		<ul class="tab_menu">
			<li class="mywallet"><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'mywallet']) ?>"><?=__('My Wallet') ?></a></li>
			<li class="mycoins"><a href="javascript:void(0)" onclick="go_asset_withdrawal('profile')"><?= __('Asset Withdrawal') ?></a></li>
			<li class="profile"><a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'profile']) ?>"><?=__('My Info') ?></a></li>
			<li class="security"><a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'security']) ?>"><?=__('Personal Info. Management') ?></a></li>

			<li class="id-verification"><a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'idVerification']) ?>"><?=__('Certification Stage') ?></a></li>

        </ul>

		<script>
		$(document).ready(function(){
			var currentUrl = window.location;
			
			var explodeUrl = currentUrl.toString().split("/");
			
			var lastParam = explodeUrl[explodeUrl.length-1];
			
		<?php //if (isset($kind)) { ?>
			$("."+lastParam).addClass('on');
		<?php //} ?>
		});

        function confirm_alert(node) {
            return confirm("<?= __('Service temporarily unavailable!')?>");
        }
		</script>
