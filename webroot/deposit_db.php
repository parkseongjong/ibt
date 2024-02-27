<?php
$con=mysqli_connect('172.31.125.113',"smbit_ctc_exch","1234","exchange_db");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

//회원들중 180일 투자자들 정보를 가져온다
//180일인 회원들이 전부 누락되어있다.

$sql1 = "select * from deposit_application_list where service_period_month = 180 and status = 'A'";
$query = mysqli_query($con,$sql1);

while ($row = mysqli_fetch_array($query)){
    /*
     * 뽑아와야하는값
     * user_id 이름
     * cryptocoin_id
     * amount
     *
     */
    $user_id = $row['user_id'];
    $coin_number = $row['cryptocoin_id'];
    $amount = $row['quantity'];
    $withdrawal_send = $row['withdrawal_send'];
    $type = 'payback';
    $status = 'completed';
    $multisign = 'N';
    $created_at = $row['created_at'];
    $today = date("Y-m-d H:i:s");

    $sql2 = "insert into principal_wallet set 
                             user_id = '$user_id',
                             cryptocoin_id = '17',
                             amount = '$amount',
                             withdrawal_send = '$withdrawal_send',
                             type = 'payback',
                             fees = '0.00',
                             status = 'completed',
                             multisign = 'N',
                             created_at = '$today'
                             ";
    //$query2 = mysqli_query($con,$sql2);
}

echo "완료";
