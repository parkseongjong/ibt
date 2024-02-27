
<table id="table-two-axis" class="two-axis table">
    <thead>
    <tr >
        <th style="color:#fff"><?= __('#')?></th>
        <th style="color:#fff"><?= __('ID')?></th>
        <th style="color:#fff"><?= __('Name')?></th>
        <th style="color:#fff"><?= __('Phone Number')?></th>
        <th style="color:#fff"><?= __('Spent Coin')?></th>
        <th style="color:#fff"><?= __('Spent Amount')?></th>
        <th style="color:#fff"><?= __('Sell Get Coin')?></th>
        <th style="color:#fff"><?= __('Sell Amount')?></th>
        <th style="color:#fff"><?= __('Description')?></th>
        <th style="color:#fff"><?= __('Per Price')?></th>
        <th style="color:#fff"><?= __('Fees')?></th>
        <th style="color:#fff"><?= __('Status')?></th>
        <th style="color:#fff"><?= __('Created')?></th>
        <th style="color:#fff"><?= __('Updated')?></th>
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
            <td> <?php echo $data['seller_user_id']; ?></td>
            <td> <?php echo $data['user']['name']; ?></td>
            <td> <?php echo $data['user']['phone_number']; ?></td>
            <td> <?php echo $data['spendcryptocoin']['short_name']; ?></td>
            <td><?php echo number_format((float)$data['sell_spend_amount'],2);?> </td>
            <td> <?php echo $data['getcryptocoin']['short_name']; ?></td>
            <td><?php echo number_format((float)$data['sell_get_amount'],2);?> </td>
            <td> <?php echo $data['sell_description'];?> </td>
            <td> <?php echo number_format((float)$data['per_price'],2);?> </td>
            <td> <?php echo number_format((float)$data['sell_fees'],2);?> </td>
            <td> <?php echo $data['status'];?> </td>
            <td><?=$data['created_at']->format('d M Y H:i:s');?> </td>
            <td><?=$data['update_at']->format('d M Y H:i:s');?> </td>

        </tr>
        </tr>
        <?php $count++; } ?>
    <?php  if(count($listing->toArray()) < 1) {
        echo "<tr class='even'><td colspan = '10'>No record found</td></tr>";
    } ?>
    </tbody>
</table>
<?php $this->Paginator->options(array('url' => array('controller' => 'transactions', 'action' => 'sellfeespagination')));
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
