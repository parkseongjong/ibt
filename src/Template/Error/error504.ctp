<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/errors.css" />
<?php
use Cake\Core\Configure;
use Cake\Error\Debugger;

$this->layout = 'error';

if (Configure::read('debug')):
    $this->layout = 'dev_error';

    $this->assign('title', $message);
    $this->assign('templateName', 'error504.ctp');

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
    <?php if ($error instanceof Error) : ?>
    <strong><?= __('Error in: ');?></strong>
    <?= sprintf('%s, line %s', str_replace(ROOT, 'ROOT', $error->getFile()), $error->getLine()) ?>
<?php endif; ?>
    <?php
    echo $this->element('auto_table_warning');

    if (extension_loaded('xdebug')):
        xdebug_print_function_stack();
    endif;

    $this->end();
endif;
?>
<?php if (Configure::read('debug')){?>
    <h2><?= __d('cake', 'Server Gateway Timeout') ?></h2>
    <p class="error">
        <strong><?= __d('cake', 'Server Gateway Timeout Error') ?>: </strong>
        <?= h($message) ?>
    </p>
<?php  } else{ ?>
<div class="main_container" style="height: 100vh">
    <div class="content-container">
        <div class="content-inner">
            <div class="p404-title">
                504
            </div>
            <div class="p404-subtitle">
                <?= __('Server Gateway Timeout Error');?>
            </div>
            <div class="p404-subtitle2">
                <div><?= __('Response not received.');?></div>
                <div><?= __('Please check your internet connection or refresh the page');?></div>
            </div>
            <button class="back-button">
                <?= $this->Html->link(__('Back'),$this->request->referer());?>
            </button>
        </div>
    </div>
</div>
<?php } ?>
