
<table id="table-two-axis" class="two-axis table">
    <thead>
    <tr >
        <th style="color:#fff"><?= __('#')?></th>
        <th style="color:#fff"><?= __('User ID')?></th>
        <th style="color:#fff"><?= __('Name')?></th>
        <th style="color:#fff"><?= __('Phone Number')?></th>
        <th style="color:#fff"><?= __('Wallet Address')?></th>
        <th style="color:#fff"><?= __('Coin')?></th>
        <th style="color:#fff"><?= __('Amount')?></th>
        <th style="color:#fff"><?= __('Coin Amount')?></th>
        <th style="color:#fff"><?= __('Fees')?></th>
        <th style="color:#fff"><?= __('Date & Time')?></th>
        <th style="color:#fff"><?= __('Status')?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $count = 1;

    foreach($listing->toArray() as $k=>$data){

        if($k%2==0) $class="odd";
        else $class="even";

        ?>
        <tr class="<?=$class?>">
        <tr class="<?=$class?>">
            <td> <?php echo $data['id']; ?></td>
            <td> <?php echo $data['user_id']; ?></td>
            <td> <?php echo $data['user']['name']; ?></td>
            <td> <?php echo $data['user']['phone_number']; ?></td>
            <td> <?php echo $data['wallet_address']; ?></td>
            <td> <?php echo $data['cryptocoin']['short_name']; ?></td>
            <td> <?php echo number_format((float)$data['coin_amount'],2); ?></td>
            <td><?php echo number_format((float)$data['amount'],2);?> </td>
            <td><?php echo number_format((float)$data['fees'],4);?> </td>
            <td><?=$data['created_at']->format('d M Y H:i:s');?> </td>
            <td><?=$data['status'];?></td>
        </tr>
        </tr>
        <?php $count++; } ?>
    <?php  if(count($listing->toArray()) < 1) {
        echo "<tr class='even'><td colspan = '10'>No record found</td></tr>";
    } ?>
    </tbody>
</table>
<?php $this->Paginator->options(array('url' => array('controller' => 'transactions', 'action' => 'coinswithdrawallistpagination')));
echo "<div class='pagination' style = 'float:right'>";

// the 'first' page button
$paginator = $this->Paginator;
echo $paginator->first("First");

// 'prev' page button,
// we can check using the paginator hasPrev() method if there's a previous page
// save with the 'next' page button
if($paginator->hasPrev()){
    echo $paginator->prev("Prev");
}

// the 'number' page buttons
echo $paginator->numbers(array('modulus' => 2));

// for the 'next' button
if($paginator->hasNext()){
    echo $paginator->next("Next");
}

// the 'last' page button
echo $paginator->last("Last");

echo "</div>";

?>
