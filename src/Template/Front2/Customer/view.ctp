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
        transform: scale(3);
        cursor: zoom-out;
    }
</style>

<div class="container">

	<div class="custom_frame">

		<?php echo $this->element('Front2/customer_left'); ?>

		<div class="contents">
			<ul>
				<li class="title"><?=__($board_title[$kind]) ?></li>
			</ul>
			<?php
			if ( !empty($boardinfos) ) { ?>
				<table class="view">
					<tbody>
						<tr>
							<th><?=__('Subject')?></th>
							<td>
								<?php
									echo !empty($boardinfos['category']) ? '['.__('Boardcategory '.$boardinfos['category']).'] ' : '';
									echo h($boardinfos['subject']);
								?>
							</td>
						</tr>
						<tr>
							<th><?=__('Date Created')?></th>
							<td><?=$boardinfos['created']->format("Y-m-d H:i:s")?></td>
						</tr>
						<tr>
<!--							<td colspan="2">-->
								<th><?=__('Contents')?></th>
								<td>
									<p style="white-space: pre-line; text-align: justify; margin: 0;display: block;">
										<?= $boardinfos['contents'];?></p>
									</td>
                        </tr>

                                <?php
                                if(!empty($boardinfos['file'])){
                                    ?>
                                        <tr>
                                    <th><?=__('File')?></th>
                                            <div class="picture_box">
                                            <td>
                                                <input type="checkbox" id="zoomCheck">
                                                <label for="zoomCheck">
                                                    <img id="img1" width=311 src="/uploads/board/<?php echo $boardinfos['file'];?>"/>
                                                </label>
                                            </td>
                                            </div>
                                        </tr>
                                    <?php
                                }
                                ?>
                                <?php
                                if(!empty($boardinfos['reply'])){
                                    ?>
                                    <tr>
                                        <th><?=__('Response Time & Date')?></th>
                                        <td><?php if(!empty($boardinfos['reply_time'])){
                                            echo $boardinfos['reply_time']->format("Y-m-d H:i:s");
                                        }?></td>

                                    </tr>
                                    <tr>
                                        <th><?= __('Response') ?></th>
                                    <td><?= h($boardinfos['reply']);?></td>
                                    </tr>
                                <?php } ?>

                        </tr>

					</tbody>
				</table>
			<?php
			}
			if ( $kind == 'qna' && !empty($boardinfos2)) { ?>
				<table class="view view_top">
					<tbody>
						<tr>
							<th><?=__('Subject')?></th>
							<td>
								<?php
									echo !empty($boardinfos2['category']) ? '['.$boardinfos2['category'].'] ' : '';
									echo h($boardinfos2['subject']);
								?>
							</td>
						</tr>
						<tr>
							<th><?=__('Date Created')?></th>
							<td><?=$boardinfos2['created']->format("Y-m-d H:i:s")?></td>
						</tr>
						<tr>
							<td colspan="2">
								<?= h(nl2br($boardinfos2['contents']));?>
                                <br><br><br><br>
                                <?php
                                if(!empty($boardinfos2['file'])){
                                    ?>
                                    <img src="/uploads/board/<?= $boardinfos2['file'];?>" width=311/>
                                    <?php
                                } ?>

							</td>
                        </tr>

					</tbody>
				</table>

			<?php } ?>

			<div class="button_block">
				<div class="left">
					<?php if ( !empty($before_id) ) { ?>
						<a href="<?php echo $this->Url->Build(['controller'=>'customer','action'=>'view', $kind, $before_id, $page, $keyword]) ?>" class="button_white"><?=__('Previous')?></a>
					<?php }
					if ( !empty($after_id) ) { ?>
						<a href="<?php echo $this->Url->Build(['controller'=>'customer','action'=>'view', $kind, $after_id, $page, $keyword]) ?>" class="button_white"><?=__('Next')?></a>
					<?php } ?>
				</div>
				<a href="<?php echo $this->Url->Build(['controller'=>'customer','action'=>$kind, 'page'=>$page, 'keyword'=>$keyword]) ?>" class="button"><?=__('List')?></a>
				<?php if ( $kind == 'qna' && !empty($boardinfos) && $user['user_type'] == 'A') {  ?>
					<a href="<?php echo $this->Url->Build(['controller'=>'customer','action'=>'edit', $kind]) ?>" class="button"><?=__('Answer')?></a>
				<?php } ?>

			</div>


		</div>
		<div class="cls"></div>

	</div>

</div>
