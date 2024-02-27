
<table id="table-two-axis" class="two-axis table">
    <thead>
    <tr >
        <th style="color:#fff"><?= __('#')?></th>
        <th style="color:#fff"><?= __('User ID')?></th>
        <th style="color:#fff"><?= __('Name')?></th>
        <th style="color:#fff"><?= __('Phone Number')?></th>
        <th style="color:#fff"><?= __('Annual Member')?></th>
        <th style="color:#fff"><?= __('Bank Name')?></th>
        <th style="color:#fff"><?= __('Bank Account Number')?></th>
        <th style="color:#fff"><?= __('Currency')?></th>
        <th style="color:#fff"><?= __('Amount')?></th>
        <th style="color:#fff"><?= __('Transaction Type')?></th>
        <th style="color:#fff"><?= __('Date & Time')?></th>tr>
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
            <td> <?php if($data['user']['annual_membership'] == 'Y'){
                    echo "✔";
                } else {
                    echo "✗";
                } ?></td>
            <td> <?php echo $data['user']['bank']; ?></td>
            <td> <?php echo $data['user']['account_number']; ?></td>
            <td> <?php echo $data['cryptocoin']['short_name']; ?></td>
            <td><?php echo number_format((float)$data['coin_amount'],2);?> </td>
            <td> <?php if ($data['tx_type'] == "bought_coupon") {
                    echo __('Bought Coupon');
                } ?></td>
            <td><?=$data['created']->format('d M Y H:i:s');?> </td>

        </tr>
        </tr>
        <?php $count++; } ?>
    <?php  if(count($listing->toArray()) < 1) {
        echo "<tr class='even'><td colspan = '10'>No record found</td></tr>";
    } ?>
    </tbody>
</table>
<?php $this->Paginator->options(array('url' => array('controller' => 'transactions', 'action' => 'couponslistpagination')));
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
