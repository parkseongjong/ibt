<style>
    .nav-tabs .nav-link.active{
        color: #495057;
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
        font-weight: bold;
    }
</style>
<?php
$active1 = '';
$active2 = '';
$active3 = '';
$active4 = '';
$active5 = '';

if(isset($this->request->params['prefix']) && $this->request->params['prefix'] == 'tech'){
    $controller_name = strtolower($this->request->params['controller']);
    $action_name = $this->request->params['action'];
    if (!empty($controller_name) && !empty($action_name)) {
        if ($controller_name == 'depositapplication' && $action_name == 'depositapplicationlist2') {
            $active1 = 'active';
        } else if ($controller_name == 'depositapplication' && $action_name == 'feecalculator2') {
            $active2 = 'active';
        } else if ($controller_name == 'depositapplication' && $action_name == 'settinglist2') {
            $active3 = 'active';
        } else if ($controller_name == 'depositapplication' && $action_name == 'loglist2') {
            $active4 = 'active';
        } else if ($controller_name == 'depositapplication' && $action_name == 'walletlist2') {
            $active5 = 'active';
        } else {
            $active1 = 'active';
        }
    } else {
        $active1 = 'active';
    }
} else {
    $active1 = 'active';
}
?>

<div class="tab-container">
    <ul class="nav nav-tabs">
        <li class="nav-item" role="tab">
            <a class="nav-link <?=$active1;?>" href="/tech/deposit-application/depositapplicationlist2" ><?=__("Investment List");?></a>
        </li>
        <li class="nav-item" role="tab">
            <a class="nav-link <?=$active2;?>" href="/tech/deposit-application/feecalculator2" ><?=__("Investment Profits Setting");?></a>
        </li>
        <li class="nav-item" role="tab">
            <a class="nav-link <?=$active3;?>" href="/tech/deposit-application/settinglist2" ><?=__("Investment Profits Setting List");?></a>
        </li>
        <li class="nav-item" role="tab">
            <a class="nav-link <?=$active4;?>" href="/tech/deposit-application/loglist2" ><?=__("Investment Profits Log List");?></a>
        </li>
        <li class="nav-item" role="tab">
            <a class="nav-link <?=$active5;?>" href="/tech/deposit-application/walletlist2"><?=__("Investment Profits Wallet List");?></a>
        </li>
    </ul>
</div>