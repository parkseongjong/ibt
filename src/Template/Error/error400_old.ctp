<?php
use Cake\Core\Configure;
use Cake\Error\Debugger;
$this->layout = 'error';
if (Configure::read('debug')):
    $this->layout = 'dev_error';
    $this->assign('title', $message);
    $this->assign('templateName', 'error400.ctp');
    $this->start('file');
?>
<?php if (!empty($error->queryString)) : ?>
    <p class="notice">
        <strong>SQL Query: </strong>
        <?= h($error->queryString) ?>
    </p>
<?php endif; ?>
<?php if (!empty($error->params)) : ?>
        <strong>SQL Query Params: </strong>
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
		<div class="col-md-12">
          <div class="col-middle">
            <div class="text-center text-center">
              <h1 class="error-number">404</h1>
              <h2>Sorry but we couldnt find this page</h2>
              <p>This page you are looking for does not exist.</p>
              <div class="mid_center">
                <a href = "<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);?>"><h3>Go to home page</h3></a>
              </div>
            </div>
          </div>
        </div>
<?php } ?>