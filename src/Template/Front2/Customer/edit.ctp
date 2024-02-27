<style>
    .custom_frame > .contents button { width: 167px; height: 54px; border-radius: 10px; background-color: #240978; font-size: 20px; font-weight: 500; border: 0; outline: none; color: #ffffff; }
</style>

<div class="container">

	<div class="custom_frame">

		<?php echo $this->element('Front2/customer_left'); ?>

		<div class="contents">
			<ul>
				<li class="title"><?=__($board_title[$kind]) ?></li>
			</ul>
			<?= $this->Flash->render() ?>
			
			<?php
				
			echo $this->Form->create('Post', ['type'=>'file','id'=>'frm']);
			echo $this->Form->input('kind', ['type'=>'hidden','label'=>false,'value'=>$kind]);
			?>
			<table class="edit">
				<tbody>
					<tr>
						<th><?=__('Category')?></th>
						<td>
						<?php
							if ( $kind == 'notice' ) {
								echo $this->Form->input('category', ['type'=>'select','options'=> array(
									'notice'=>__('Boardcategory notice'),
									'general'=>__('Boardcategory general')
									),'label'=>false]);
							} else if($kind == 'qna'){
                                echo $this->Form->input('category', ['type'=>'select','options'=> array(
									'deposit'=>__('Boardcategory deposit'),
									'general2'=>__('Boardcategory general2')
									),'label'=>false]);
                            }

							else {
								echo $this->Form->input('category', ['type'=>'select','options'=> array(
									'deposit'=>__('Boardcategory deposit'),
									'general2'=>__('Boardcategory general2')
									),'label'=>false]);
							}
						?>
						</td>
					</tr>
					<tr>
						<th><?=__('Subject')?></th>
						<td>
							<?php echo $this->Form->input('subject', ['type'=>'text', 'label'=>false, 'required'=>true, 'id'=>'subject']); ?>
							<p class="text-pink" id="subject_error" style="display:none;">제목을 입력해주세요</p>
						</td>
					</tr>
					<tr>
						<th><?=__('Contents')?></th>
						<td><?php echo $this->Form->input('contents', ['type'=>'textarea', 'label'=>false, 'required'=>true,'id'=>'contents']); ?>
						<p class="text-pink" id="contents_error" style="display:none;">내용을 10자 이상 입력해주세요</p>
						</td>
					</tr>
					<tr>
						<th><?=__('File Attachment')?></th>
						<td><?php echo $this->Form->input('attfile', ['type'=>'file', 'label'=>false]); ?></td>
					</tr>
				</tbody>
			</table>
			
			<div class="button_block button_block_center">
                <button class="button" type="button" id="submit_btn" onclick="submitCheck()" ><?=__('Complete')?></button>
			</div>

			<?php
			echo $this->Form->end();
			?>

		</div>
<div class="cls"></div>
	</div>

</div>

<script type="text/JavaScript">
	function submitCheck(){
		if($('#subject').val() == ''){
			$('#subject').focus();
			$('#subject_error').show();
			return;
		}
		$('#subject_error').hide();
		if($('#contents').val() == '' || $('#contents').val().length < 10){
			$('#contents').focus();
			$('#contents_error').show();
			return;
		}
		$('#contents_error').hide();
		$('#submit_btn').hide();
		$('#frm').submit();
	}
</script>