<style type="text/css">
.category {
	font-size: 18px;
	font-weight: bold;
	font-stretch: normal;
	font-style: normal;
	line-height: 3.67;
	letter-spacing: normal;
	border-bottom: solid 1px #dddddd;
}
.category:nth-of-type(n+2) {
	margin-top: 40px;
}
dl {
	border-bottom: solid 1px #dddddd;
	margin: 0;
}
dt {
	font-size: 18px;
	font-weight: 400;
	font-stretch: normal;
	font-style: normal;
	line-height: 3.7;
	letter-spacing: normal;
	padding-left: 16px;
	margin: 0;
	background-image: url('/wb/imgs/arrow_down.png');
	background-repeat: no-repeat;
	background-position: 1050px center;
}
dd {
	font-size: 18px;
	font-weight: 300;
	font-stretch: normal;
	font-style: normal;
	line-height: normal;
	letter-spacing: normal;
	color: #7f7f81;
	background: #f9f9f9;
	padding: 28px 96px 28px 32px;
	margin: 0;
	display: none;
}


@media only screen and (max-width: 767px) {
dt {line-height: 2.7;    font-size: 15px;}
.category:nth-of-type(n+2) { margin-top: 10px;}
.category {    line-height: 3;    font-size: 16px;}
dd{    padding: 5px 5px 5px 18px;font-size: 15px}

}
</style>

<div class="container">

	<div class="custom_frame document">

		<?php echo $this->element('Front2/customer_left'); ?>

		<div class="contents">

			<li class="title"><?= __('FAQ')?></li>

			<?php
			
			$k_index = 1;
			$vararray=[];
			foreach($listing as $k=>$data){
				if(!in_array($data['category'],$vararray)){
					$vararray[]=$data['category'];
				?>
				<div class="category">
				<?=$data['category'];?>
				</div>
				<?php
			
					foreach($listing as $k2=>$data2){
						if($data['category'] ==$data2['category']){
						
							
						?><dl>
							<dt>Q. <?= h($data2['subject']);?></dt>
							<dd><?= h($data2['contents']);?></dd>
						</dl><?php
					}
				}
				}
				
			}
			?>

		</div>
   <div class="cls"></div>
	</div>

</div>

<script>
var old_dt = false;
var old_dd = false;
$(document).ready(function(){
	$('dt').click(function(){
		var par = $(this).parent();
		var dd = $(par).find('dd');
		if ($(dd).css('display') == 'none') {
			if (old_dd) {
				$(old_dd).slideUp('fast');
				$(old_dt).css('background-image', "url('/wb/imgs/arrow_down.png')");
			}
			$(dd).slideDown('fast');
			$(this).css('background-image', "url('/wb/imgs/arrow_up.png')");
			old_dd = dd;
			old_dt = $(this);
		} else {
			$(dd).slideUp('fast');
			$(this).css('background-image', "url('/wb/imgs/arrow_down.png')");
			old_dd = false;
			old_dt = false;
		}
	});
});
</script>
