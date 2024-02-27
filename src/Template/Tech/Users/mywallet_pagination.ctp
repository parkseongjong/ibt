<table id="table-two-axis" class="two-axis table">
    <thead>
    <tr >
        <th style="color:#fff"><?= __('User ID')?></th>
        <th style="color:#fff"><?= __('Name')?></th>
        <th style="color:#fff"><?= __('Phone Number')?></th>
        <th style="color:#fff"><?= __('Annual Member')?></th>
        <th style="color:#fff"><?= __('Coin')?></th>
        <th style="color:#fff"><?= __('Amount')?></th>
        <th style="color:#fff"><?= __('Transferred from/to')?></th>
        <th style="color:#fff"><?= __('Date & Time')?></th>

    </tr>
    </thead>
    <tbody>
    <?php
    $count= 1;

    foreach($listing->toArray() as $k=>$data){

        if($k%2==0) $class="odd";
        else $class="even";

        ?>
        <tr class="<?=$class?>">
        <tr class="<?=$class?>">
            <td> <?php echo $data['user_id']; ?></td>
            <td> <?php echo $data['user']['name']; ?></td>
            <td> <?php echo $data['user']['phone_number']; ?></td>
            <td> <?php if($data['user']['annual_membership'] == 'Y'){
                    echo "✔";
                } else {
                    echo "✗";
                } ?></td>
            <td> <?php echo $data['cryptocoin']['short_name']; ?></td>
            <td><?php if($data['amount'] < 0){
                    $amount = $data['amount'];
                    $amount = $amount * -1;
                    echo number_format((float)$amount,2);
                } else {
                    echo number_format((float)$data['amount'],2); }?> </td>

            <td> <?php
                $transType = $data['type'];
                $transTypes = str_replace('_',' ',$transType);
                echo $transTypes; ?></td>
            <td><?=$data['created_at']->format('d M Y H:i:s');?> </td>
        </tr>
        </tr>
        <?php $count++; } ?>
    <?php  if(count($listing->toArray()) < 1) {
        echo "<tr class='even'><td colspan = '10'>No record found</td></tr>";
    } ?>
    </tbody>
</table>
<?php $this->Paginator->options(array('url' => array('controller' => 'users', 'action' => 'mywallet_pagination')));
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
