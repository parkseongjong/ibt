<style>
	.prev-privacy-area ul > li{ padding:2px; }
	.prev-privacy-area ul > li > a { background-color:#e0e0e0; }
	.new_privacy_policy { border: solid 1px #d2d2d2; padding: 20px 4px 24px 20px; }
	.text-area { height: 500px; overflow-x:hidden; overflow-y:auto; font-size: 15px; font-weight: 300; line-height: 2.14; color: #3a3a3a; padding-right:30px; }
</style>
<div class="container">
	<div class="custom_frame document">
		<?php echo $this->element('Front2/terms_left'); ?>
		<div class="contents">
			<ul>
				<li class="title"><?= __('terms-annual') ?></li>
			</ul>
			<div class="new_privacy_policy">
				<div id="text_area" class="text-area">
					<!-- <zero-md src="/dev/default.md" class="text_md_file" >
						<template>
							<style>
								.markdown-body { font-family: "Noto Sans KR", "Montserrat", sans-serif !important; }
							</style>
						</template>
					</zero-md> -->
				</div>
			</div>
			<ul class="m-t-10">
				<li class="title"><?= __("show prev terms") ?></li>
			</ul>
			<hr>
			<div class="prev-privacy-area">
				<ul>
					<li><?= __('terms-annual') ?> : 2021.03.01 <a href="javascript:void(0)" onclick="getTerms('2021-03-01')"><?=__('Show2');?></a></li>
				</ul>
			</div>
		</div>
		<div class="cls"></div>
	</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@webcomponents/webcomponentsjs@2/webcomponents-loader.min.js"></script>
<script type="module" src="https://cdn.jsdelivr.net/gh/zerodevx/zero-md@1/src/zero-md.min.js"></script>
<script>
	function getTerms(file_name='2021-03-01'){
		const md_file = '<zero-md src="/dev/terms_annual/'+file_name+'.md" class="text_md_file" ><template><style>.markdown-body { font-family: "Noto Sans KR", "Montserrat", sans-serif !important; }</style></template></zero-md>';
		$('#text_area').html('');
		$('#text_area').html(md_file);
		$('#text_area').focus();
	}
	getTerms();
</script>