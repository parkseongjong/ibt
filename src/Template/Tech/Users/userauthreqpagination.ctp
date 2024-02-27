
<table id="table-two-axis" class="two-axis table">
    <thead>
    <tr >
        <th style="color:#fff"><?= __('#')?></th>
        <th style="color:#fff"><?= __('User ID')?></th>
        <th style="color:#fff"><?= __('Name')?></th>
        <th style="color:#fff"><?= __('Phone Number')?></th>
        <th style="color:#fff"><?= __('Email')?></th>
        <th style="color:#fff"><?= __('Bank Name')?></th>
        <th style="color:#fff"><?= __('Bank Account Number')?></th>
        <th style="color:#fff"><?= __('Request')?></th>
        <th style="color:#fff"><?= __('Action')?></th>
        <th style="color:#fff"><?= __('Status')?></th>
        <th style="color:#fff"><?= __('Date & Time')?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $count = 1;

    foreach($listing->toArray() as $k=>$data){

        if($k%2==0) $class="odd";
        else $class="even";

        ?>
        <tr class="<?=$class?>" id="user_row_<?= $data['user_id']; ?>">
        <tr class="<?=$class?>" id="user_row_<?= $data['user_id']; ?>">
            <td> <?= $data['id']; ?></td>
            <td> <?= $data['user_id']; ?></td>
            <td> <?= $data['user_name']; ?></td>
            <td> <?= $data['user_phone_number']; ?></td>
            <td> <?= $data['user_email']; ?></td>
            <td> <?= $data['user_bank_name']; ?></td>
            <td> <?= $data['user_account_number']; ?></td>
            <td> <?php if ($data['request'] == "emailAuth_change"){
                    echo "Email Change Request";
                }
                if ($data['request'] == "bankAuth_change"){
                    echo "Bank Information Change Request";
                }
                if ($data['request'] == "otpAuth_change"){
                    echo "OTP Change Request";
                }
                ?></td>
            <td class=" ">
                <?php
                if($data['request'] == "emailAuth_change" && $data['status'] == "Pending"){
                    echo '<a href="javascript:void(0)" onclick="removeEmail(this,'.$data['user_id'].','.$data['id'].')" class="btn btn-xs"><i class="fa fa-close"></i> Remove Email Auth</a>';
                } else if ($data['request'] == "bankAuth_change" && $data['status'] == "Pending"){
                    echo '<a href="javascript:void(0)" onclick="removeBank(this,'.$data['user_id'].','.$data['id'].')" class="btn btn-xs"><i class="fa fa-close"></i> Remove Bank Auth</a>';
                } else if($data['request'] == "otpAuth_change" && $data['status'] == "Pending"){
                    echo '<a href="javascript:void(0)" onclick="removeOTP(this,'.$data['user_id'].','.$data['id'].')" class="btn btn-xs"><i class="fa fa-close"></i> Remove OTP Auth</a>';
                }else {
                    echo 'Change Request Completed';
                }
                ?>
            </td>
            <td> <?php if ($data['status'] == "Pending"){
                    echo "Pending";
                }
                if ($data['status'] == "Completed"){
                    echo "Completed";
                }
                ?></td>
            <td> <?= $data['created']->format('d M Y H:i:s');?> </td>
        </tr>
        </tr>
        <?php $count++; } ?>
    <?php  if(count($listing->toArray()) < 1) {
        echo "<tr class='even'><td colspan = '10'>No record found</td></tr>";
    } ?>
    </tbody>
</table>
<?php $this->Paginator->options(array('url' => array('controller' => 'transactions', 'action' => 'withdrawallistpagination')));
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
