<style>
    input[type=checkbox] {
        display: none;
    }

    .container img {
        margin: 10px;
        transition: transform 0.25s ease;
        cursor: zoom-in;
    }

    input[type=checkbox]:checked ~ label > img {
        transform: scale(6);
        cursor: zoom-out;
    }
</style>
<div class="container">

	<div class="custom_frame">

		<?php echo $this->element('Front2/customer_left'); ?>

		<div class="contents">
			<ul>
				<li class="title"><?= __('Inquiries Response') ?></li>
			</ul>
			<?= $this->Flash->render() ?>
			
			<?php
			
			echo $this->Form->create('Post', ['type'=>'file']);
			?>
			<table class="edit">
				<tbody>
					<tr>
						<th><?=__('Category')?></th>
						<td>
						<?php
							
							echo $this->Form->input('category', ['type'=>'text', 'value'=>$boardinfos['category'] ,'label'=>false, 'required'=>true, 'id'=>'subject','disabled'=>'disabled']); 
							
							?>

						</td>
					</tr>
					<tr>
						<th><?=__('Subject')?></th>
						<td>
							<?php echo $this->Form->input('subject', ['type'=>'text','value'=>$boardinfos['subject'] , 'label'=>false, 'required'=>true, 'id'=>'subject','disabled'=>'disabled']); ?>
						</td>
					</tr>
					<tr>
						<th><?=__('Contents')?></th>
						<td><?php echo $this->Form->input('contents', ['type'=>'textarea', 'value'=>$boardinfos['contents'], 'label'=>false, 'required'=>true,'id'=>'contents','disabled'=>'disabled']); ?>
						</td>
					</tr>
					<tr>
						<th><?=__('File Attachment')?></th>
						
						<td>
							<?php 
							if(!empty($boardinfos['file'])){
								?>
                                <input type="checkbox" id="zoomCheck">
                                <label for="zoomCheck">
                                    <img id="img"  src="/uploads/board/<?php echo $boardinfos['file'];?>" height="100px" width="100px"/>
                                </label>


								<?php
							}else{
							    ?>
                        <?php echo $this->Form->input('',['type'=>'file', 'label'=>$boardinfos['file']]); ?>
                        <?php
                            }
							?>
                        </td>
					</tr>

                    <?php if(!empty($boardinfos['reply'])){
                    ?>
                    <tr>
                        <th><?= __('Response Time & Date') ?></th>
                        <td><?php echo $this->Form->input('replyR', ['type'=>'text', 'label'=>false, 'required'=>true,'id'=>'replyR', 'disabled'=>'true', 'value' => $boardinfos['reply_time']]); ?></td>
                    </tr>
                    <?php } ?>
                    <tr>
						<th><?= __('Response') ?></th>
                        <?php if(!empty($boardinfos['reply'])){
                            ?>
                        <td><?php echo $this->Form->input('reply', ['type'=>'textarea', 'label'=>false, 'required'=>true,'id'=>'reply', 'disabled'=>'true', 'value' => $boardinfos['reply']]); ?></td>
                        <?php } else { ?>
						<td><?php echo $this->Form->input('reply', ['type'=>'textarea', 'label'=>false, 'required'=>true,'id'=>'reply']); ?>
						</td>
                        <?php }?>
					</tr>

				</tbody>
			</table>
            <?php if(!empty($boardinfos['reply'])){
            ?>
            <div class="button_block button_block_center">
                <a href="<?php echo $this->Url->Build(['controller'=>'customer','action'=>'qna',]) ?>" class="button"><?=__('List')?></a>
            </div>
            <?php } else { ?>
			<div class="button_block button_block_center">
				<input type="submit" class="button" name="" value="<?=__('Complete')?>" />
			</div>
                <?php } ?>
			<?php
			echo $this->Form->end();
			?>

		</div>
<div class="cls"></div>

</div>

