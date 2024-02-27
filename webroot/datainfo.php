<?php
$con=mysqli_connect('172.31.125.113',"smbit_ctc_exch","1234","exchange_db");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
    //트레이딩에 남아있는 코인을 먼저 체크한다

    //$sum_sql = "select user_id,cryptocoin_id,sum(coin_amount) as cnt from transactions where user_id = 4211 group by cryptocoin_id";
    $sum_sql = "select user_id,cryptocoin_id,sum(coin_amount) as cnt,wallet_address from transactions ";
    //$sum_sql .= " where  user_id = 4211";
    //$sum_sql .= " where user_id = 2710";
    $sum_sql .= " where  1";
    //$sum_sql .= " and (remark = 'transfer_to_main_account' or remark = 'transfer_from_main_account')";
    $sum_sql .= " and status = 'completed' ";
    $sum_sql .= " group by user_id,cryptocoin_id";

    $sum_result = mysqli_query($con,$sum_sql);

    //

    while ($sum_row = mysqli_fetch_array($sum_result)){
        /*
         * 수량이 0.001이라도 있으면 체크해서 아래 if문을 탄다 아니면 바로 종료
         */
        if($sum_row['cnt'] > 0){
            //수량 체크
            // wallet_address
            echo "아이디 =>".$sum_row['user_id']."||코인번호=>".$sum_row['cryptocoin_id']."||코인수량=>".$sum_row['cnt']."||".$sum_row['wallet_address']."<br>";

            $user_id = $sum_row['user_id']; // 유저 아이디
            $amount = $sum_row['cnt']; // 코인수량
            $coin_number = $sum_row['cryptocoin_id']; // 코인번호
            $address = $sum_row['wallet_address']; // 지갑주소
            $today = date("Y-m-d H:i:s");

            $sql1 = "insert into principal_wallet set 
                             user_id = '$user_id',
                             cryptocoin_id = '$coin_number',
                             amount = '$amount',
                             wallet_address = '$address',
                             type = 'transfer_from_trading_account',
                             fees = '0.00',
                             status = 'completed',
                             created_at = '$today'
                             ";
            //$query1 = mysqli_query($con,$sql1);
            //echo $sql1."<br>";

            //트레이닝 지갑쪽 insert 추가
            $sql2 = "insert into transactions set 
                         user_id = '$user_id',
                         coin_amount = '-$amount',
                         cryptocoin_id = '$coin_number',
                         wallet_address = '$address',
                         tx_type = 'withdrawal',
                         remark = 'transfer_to_main_account',
                         fees = '0.00',
                         status = 'completed',
                         created = '$today',
                         updated = '$today'
                             ";
            //$query2 = mysqli_query($con,$sql2);
            //echo $sql2."<br>";
            //거래 기록장소 insert

            //$log_sql = "";

            //로그

        }
    //

    }
echo "완료";
    exit;

    //현재 트레이딩에 있는 내 코인 내역을 뽑아온다
    $f_select = "select * from transactions where user_id = '4211' order by id desc";
    $f_result = mysqli_query($con,$f_select);
    //$row = mysqli_fetch_array($f_result);

    while ($row = mysqli_fetch_array($f_result)) {
        echo $row['cryptocoin_id'];
        echo '<br>';
    }