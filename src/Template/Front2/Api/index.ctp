<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/api-info.css">
<style>
	.coinibt-api-container .coinibt-api .contents-wrap .contents .txt02{color: #333;line-height: 24px;margin-bottom:5px;}
</style>
<div class="body-container">
	<div class="coinibt-api-container">
		<div class="coinibt-api">
			<?php echo $this->element('Front2/api_info_left');?>
			<article class="contents-wrap">
				<div class="contents">
					<h2 class="title">
						<span><?=__("API Introduction");?></span>
						<!--<span class="bg-skyblue">Public API</span>
						<span class="bg-pink">Private API</span>-->
				    </h2>
					<p class="txt01"><?=__("SMBIT REST API is various features of SMBIT opened to public to encourage external Programmers and Users to develop services and applications.");?></p>
					<p class="txt02"><?=__("Public API provides following SMBIT exchange market information.");?></p>
					<p class="txt01">
						- <?=__("Current Price Info");?> <br>
						- <?=__("Bid and Ask Info");?> <br>
						- <?=__("Transaction History");?><br>
					</p>
				</div>
			</article>
		</div>
	</div>
</div>
