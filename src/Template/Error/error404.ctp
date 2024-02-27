<?php
use Cake\Core\Configure;
use Cake\Error\Debugger;

$this->layout = 'error';

if (Configure::read('debug')):
    $this->layout = 'dev_error';

    $this->assign('title', $message);
    $this->assign('templateName', 'error404.ctp');

    $this->start('file');
    ?>
    <?php if (!empty($error->queryString)) : ?>
    <p class="notice">
        <strong><?= __('SQL Query: ');?></strong>
        <?= h($error->queryString) ?>
    </p>
<?php endif; ?>
    <?php if (!empty($error->params)) : ?>
    <strong><?= __('SQL Query Params: ');?></strong>
    <?php Debugger::dump($error->params) ?>
<?php endif; ?>
    <?= $this->element('auto_table_warning') ?>
    <?php
    if (extension_loaded('xdebug')):
        xdebug_print_function_stack();
    endif;

    $this->end();
endif;
?>
<?php if (Configure::read('debug')){?>
    <h2><?= h($message) ?></h2>
    <p class="error">
        <strong><?= __d('cake', 'Error') ?>: </strong>
        <?= sprintf(
            __d('cake', 'The requested address %s was not found on this server.'),
            "<strong>'{$url}'</strong>"
        ) ?>
    </p>
<?php  } else{ ?>
<div class="main_container" style="height: 100vh">
    <div class="content-container">
        <div class="content-inner">
            <div class="p404-title">
                404
            </div>
            <div class="p404-subtitle">
                <?= __('요청하신 페이지를 찾을 수 없습니다.');?>
                <!-- EN: Sorry, we were unable to find that page -->
            </div>
            <!-- EN에선 p404-subtitle2 제거 -->
            <div class="p404-subtitle2">
                <div><?= __('요청하신 페이지는 제거되었거나 이름이 변경되어 사용할 수 없습니다.');?></div>
                <div><?= __('입력하신 페이지의 주소가 정확한지 다시 한번 확인해 주세요.');?></div>
            </div>
            <button class="back-button">
                <?= $this->Html->link(__('Back'),$this->request->referer());?>
            </button>

        </div>
    </div>
</div>

<?php } ?>

