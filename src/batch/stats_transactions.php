<?php
@set_time_limit(600);
date_default_timezone_set('Asia/Seoul');
include __DIR__ . '/DB.php';
include __DIR__ . '/helper.php';

if (isset($_SERVER['argv'][1])) {
    $from_date = $_SERVER['argv'][1];
} else {
    #$from_date = (new DateTime('-1 hour'))->format('Y-m-d');
    $from_date = date('Y-m-d');
}

$db = DB::instance();
$query = "
    SELECT 
        IFNULL(SUM(coin_amount), 0) AS coin_amount,
        user_id,
        status,
        cryptocoin_id,
        tx_type,
        remark
    FROM (
        SELECT 
            coin_amount,
            user_id,
            status,
            cryptocoin_id,
            tx_type,
            remark
        FROM 
            transactions
        WHERE
            DATE_FORMAT(created,'%Y-%m-%d')  = '{$from_date}'
    ) AS t
    GROUP BY
        user_id,
        status,
        cryptocoin_id,
        tx_type,
        remark
";

echo "$from_date => " . $from_date . PHP_EOL;
$data = $db->query($query)->fetchAll();

$query = "
        REPLACE INTO `tb_stats_transactions` set 
            `coin_amount` = :coin_amount,
            `create_date` = :create_date,
            `user_id` = :user_id,
            `status` = :status,
            `cryptocoin_id` = :cryptocoin_id,
            `tx_type` = :tx_type,
            remark = :remark,
            `reg_dt` = now()
    ";

$stmt = $db->prepare($query);

foreach ($data as $key => $row) {

    if (empty($row['user_id']) === true) {
        continue;
    }
    try{

        $stmt->bindParam(':coin_amount', $row['coin_amount']);
        $stmt->bindParam(':create_date', $from_date);
        $stmt->bindParam(':user_id', $row['user_id']);
        $stmt->bindParam(':status', $row['status']);
        $stmt->bindParam(':cryptocoin_id', $row['cryptocoin_id']);
        $stmt->bindParam(':tx_type', $row['tx_type']);
        $stmt->bindParam(':remark', $row['remark']);
        $stmt->execute();
    } catch (Exception $e) {
        echo $e->getMessage() . PHP_EOL;
    }

    echo "rowCount => " . $stmt->rowCount() . " lastInsertId => " . $db->lastInsertId() . PHP_EOL;

}

unset($from, $to, $result, $hour, $index, $code, $val, $date, $type, $where, $row, $articles, $values, $query);