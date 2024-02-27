<?php
$con = mysqli_connect('172.31.125.113', "smbit_ctc_exch", "1234", "exchange_db");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
// 한글 깨짐 관련
mysqli_query($con, "set session character_set_connection=utf8;");
mysqli_query($con, "set session character_set_results=utf8;");
mysqli_query($con, "set session character_set_client=utf8;");


$sql1 = "select * from input_deposit where status !='y' ";
$query = mysqli_query($con, $sql1);


$today = date('Y-m-d H:i:s');
//실제 회원정보를 가져 온다
while ($row = mysqli_fetch_array($query)){


    //이 회원의 실제 회원 ID값을 가져오자
    $number = substr($row['phone_number'],0,7);
    $username = $row['name'];
    //echo $number."@@@@";
    //$user_sql = "select * from users where name = '".$row['name']."' and phone_number like '%$number%'";
    $user_sql = "select * from users where phone_number like '%$number%' and name = '$username'  ";
    $user_query = mysqli_query($con,$user_sql);
    while ($user_row = mysqli_fetch_array($user_query)){
        //데이터 출력 후 입력 시켜주자
        //이사람의 정보를 먼저 추출
        $krw_amount = ($row['amount']/250000)*50000;
        //echo "이름".$row['name']."<br>";
        //echo "아이디".$user_row['id']."<br>";
        //echo "코인명".$row['coin_type']."<br>";
        //echo "코인갯수".$row['amount']."<br>";
        //echo "빠져나갈KRW".$krw_amount."<br>";
      //  echo "<br><br><br><br>";



        $coin = $row['coin_type']; // 코인명
        $name = $row['name']; // 회원명
        $user_id = $user_row['id']; // 회원번호
        $quantity = $row['amount'];

        if($coin == "TP3"){
            $coin_number = 17;
        }else{
            $coin_number = 19;
        }

        //회원 메인 밸러싱 값도 구해주자
        $main_sql = "select sum(amount) as cnt from principal_wallet where user_id = '$user_id' and cryptocoin_id = '$coin_number'";
        //echo $main_sql;
        $main_query = mysqli_query($con,$main_sql);
        $row3 = mysqli_fetch_row($main_query);
        $balance = $row3[0];
        //echo "2424";

        //echo "<br><br><br><br>";
//현재 관련된자들 정보를 가져온다

//예치에 데이터 삽입
$sql2 = "insert into deposit_application_list set 
                             user_id = '$user_id',
                             quantity = '$quantity',
                             unit = '$coin',
                             service_period_month = '360',
                             previous_balance = '$balance',
                             status = 'A',
                             investment_number = '4',
                             created = '$today',
                             approval_date = '$today'
                             ";
//mysqli_query($con,$sql2);

//KRW 추가
$sql3 = "insert into principal_wallet set
                             user_id = '$user_id',
                             cryptocoin_id = '$coin_number',
                             amount = '$quantity',
                             type = 'event_coin',
                             remark = 'event_coin',
                             status = 'completed',
                             created_at = '$today'
                             ";
//echo $sql3;
//echo $sql3;

        
 /*       if(mysqli_query($con,$sql3)){
            echo "완료".$name."||||".$user_id."<br>";
        }else{
            echo "미완료".$name."||||".$user_id."<br>";
        }*/

    }
}
echo "<br><br><br><br><br>";
echo "전체 완료";