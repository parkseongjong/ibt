<?
    /* ============================================================================== */
    /* =   ���������� ���� �� ��ȣȭ ������                                         = */
    /* = -------------------------------------------------------------------------- = */
    /* =   �ش� �������� �ݵ�� ������ ������ ���ε� �Ǿ�� �ϸ�                    = */ 
    /* =   ������ �������� ����Ͻñ� �ٶ��ϴ�.                                     = */
    /* ============================================================================== */

    /* ============================================================================== */
    /* =   ���̺귯�� ���� Include                                                  = */
    /* = -------------------------------------------------------------------------- = */
    session_start();
    require_once '../../../../../config/cert_conf.php';
    ini_set("display_errors", 0);

    include "../../../../../config/cert_conf.php";
    require "../../KcpcertCoinibt/lib/ct_cli_lib.php";

    /* = -------------------------------------------------------------------------- = */
    /* =   ���̺귯�� ���� Include END                                               = */
    /* ============================================================================== */

    /* ============================================================================== */
    /* =   null ���� ó���ϴ� �޼ҵ�                                                = */
    /* = -------------------------------------------------------------------------- = */
    function f_get_parm_str( $val )
    {
        if ( $val == null ) $val = "";
        if ( $val == ""   ) $val = "";
        return  $val;
    }
    /* ============================================================================== */

    $site_cd       = "";
    $ordr_idxx     = "";
    
    $cert_no       = "";
    $cert_enc_use  = "";
    $enc_info      = "";
    $enc_data      = "";
    $req_tx        = "";
    
    $enc_cert_data2 = "";
    $cert_info     = "";

    $tran_cd       = "";
    $res_cd        = "";
    $res_msg       = "";

    $dn_hash       = "";
$param_opt_1 = "";
$param_opt_2 = "";
$param_opt_3 = "";

$auth_phone_no = '';
$auth_name = '';
$auth_dob_y = '';
$auth_dob_m = '';
$auth_dob_d = '';
$auth_gender = '';
$auth_local_code = '';
$t_id = ''; // 회원가입시 넘겨줄 temp_accounts.id
$auth_ci = '';
$auth_di = '';
$success_url = '';
	/*------------------------------------------------------------------------*/
    /*  :: ��ü �Ķ���� �����                                               */
    /*------------------------------------------------------------------------*/

    // request �� �Ѿ�� �� ó��
    foreach($_POST as $nmParam => $valParam)
    {

        if ( $nmParam == "site_cd" )
        {
            $site_cd = f_get_parm_str ( $valParam );
        }

        if ( $nmParam == "ordr_idxx" )
        {
            $ordr_idxx = f_get_parm_str ( $valParam );
        }

        if ( $nmParam == "res_cd" )
        {
            $res_cd = f_get_parm_str ( $valParam );
        }

        if ( $nmParam == "cert_enc_use" )
        {
            $cert_enc_use = f_get_parm_str ( $valParam );
        }

        if ( $nmParam == "req_tx" )
        {
            $req_tx = f_get_parm_str ( $valParam );
        }

        if ( $nmParam == "cert_no" )
        {
            $cert_no = f_get_parm_str ( $valParam );
        }

        if ( $nmParam == "enc_cert_data2" )
        {
            $enc_cert_data2 = f_get_parm_str ( $valParam );
        }

        if ( $nmParam == "dn_hash" )
        {
            $dn_hash = f_get_parm_str ( $valParam );
        }

        // 추가(YMJ)
        if ( $nmParam == "param_opt_1" ) {
            $param_opt_1 = f_get_parm_str ( $valParam );
        }
        if ( $nmParam == "param_opt_2" ) {
            $param_opt_2 = f_get_parm_str ( $valParam );
        }
        if ( $nmParam == "param_opt_3" ) {
            $param_opt_3 = f_get_parm_str ( $valParam );
        }

        // �θ�â���� �ѱ�� form ������ ���� �ʵ�
       // $sbParam .= "<input type='hidden' name='" . $nmParam . "' value='" . f_get_parm_str( $valParam ) . "'/>";
    }

    $ct_cert = new C_CT_CLI;
    $ct_cert->mf_clear();

    // ��� ó��
$browser_infos = '';
if (isset($_SERVER['HTTP_USER_AGENT'])) {
    $browser_infos = $_SERVER['HTTP_USER_AGENT'];
}
$page_url = '';
if (isset($_SERVER['REQUEST_URI'])) {
    $page_url = $_SERVER['REQUEST_URI'];
} else if (isset($_SERVER['SCRIPT_NAME'])) {
    $page_url = $_SERVER['SCRIPT_NAME'];
} else if (isset($_SERVER['PHP_SELF'])) {
    $page_url = $_SERVER['PHP_SELF'];
}
$user_ip = '';
if(!empty($_SERVER['HTTP_CLIENT_IP'])){
    //ip from share internet
    $user_ip = $_SERVER['HTTP_CLIENT_IP'];
}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
    //ip pass from proxy
    $user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}else{
    $user_ip = $_SERVER['REMOTE_ADDR'];
}

$next_url = 'https://coinibt.io/front2/Users/signup';

if ( !isset($_SESSION['auth_re_r']) || $_SESSION['auth_re_r'] != 'fin') {
    if( $cert_enc_use == "Y" )
    {
        if( $res_cd == "0000" )
        {
            // dn_hash ����
            // KCP �� ������ �帮�� dn_hash �� ����Ʈ �ڵ�, ��û��ȣ , ������ȣ�� �����Ͽ�
            // �ش� �������� �������� �����մϴ�
             $veri_str = $site_cd.$ordr_idxx.$cert_no; // ����Ʈ �ڵ� + ��û��ȣ + �����ŷ���ȣ

            if ( $ct_cert->check_valid_hash ( $g_conf_home_dir , $g_conf_ENC_KEY , $dn_hash , $veri_str ) != "1" )
            {
                // ���� ���н� ó�� ����

                $_SESSION['failure'] = $langArr['auth_failed'];
                $_SESSION['auth_re_r'] = 'fin';
                fn_logSave('Personal Identification Error : '.$langArr['auth_failed']);

                $data_auth_err = [];
                $data_auth_err['type'] = 'auth';
                $data_auth_err['cause'] = 'decrypt';
                $data_auth_err['message'] = 'dn_hash 변조 위험있음';
                $data_auth_err['browser_infos'] = $browser_infos;
                $data_auth_err['page_url'] = $page_url;
                $data_auth_err['user_ip'] = $user_ip;
                $data_auth_err['created_at'] = date("Y-m-d H:i:s");
                $db_err_insert = getDbInstance();
                $db_err_insert->insert('auth_error', $data_auth_err);

                echo "<script>if( ( navigator.userAgent.indexOf('Android') > - 1 || navigator.userAgent.indexOf('iPhone') > - 1 || 
                navigator.userAgent.indexOf('android-web-view') > - 1 ) ){ 
                    parent.location.replace('".$next_url."'); 
                    } else { 
                    opener.location.replace('".$next_url."');
                    window.close(); 
                    }
                    </script>";
                // ���� ó�� ( dn_hash ���� ��������)
                exit();
            }

            // ������ DB ó�� ������ ����

            echo "========================= ���� ������ ======================="       ."<br>";
            echo "����Ʈ �ڵ�            :" . $site_cd                                 ."<br>";
            echo "���� ��ȣ              :" . $cert_no                                 ."<br>";
            echo "��ȣ�� ��������        :" . $enc_cert_data2                          ."<br>";
            
            // ���������� ��ȣȭ �Լ�
            // �ش� �Լ��� ��ȣȭ�� enc_cert_data2 ��
            // site_cd �� cert_no �� ������ ��ȭȭ �ϴ� �Լ� �Դϴ�.
            // ���������� ��ȣȭ �Ȱ�쿡�� ���������͸� �����ü� �ֽ��ϴ�.
            try{
            $opt = "1" ; // ��ȣȭ ���ڵ� �ɼ� ( UTF - 8 ���� "1" )
            $ct_cert->decrypt_enc_cert( $g_conf_home_dir , $g_conf_ENC_KEY , $site_cd , $cert_no , $enc_cert_data2 , $opt );
            
            echo "========================= ��ȣȭ ������ ====================="       ."<br>";
            echo "��ȣȭ �̵���Ż� �ڵ� :" . $ct_cert->mf_get_key_value("comm_id"    )."<br>"; // �̵���Ż� �ڵ�
            echo "��ȣȭ ��ȭ��ȣ        :" . $ct_cert->mf_get_key_value("phone_no"   )."<br>"; // ��ȭ��ȣ
            echo "��ȣȭ �̸�            :" . $ct_cert->mf_get_key_value("user_name"  )."<br>"; // �̸�
            echo "��ȣȭ �������        :" . $ct_cert->mf_get_key_value("birth_day"  )."<br>"; // �������
            echo "��ȣȭ �����ڵ�        :" . $ct_cert->mf_get_key_value("sex_code"   )."<br>"; // �����ڵ�
            echo "��ȣȭ ��/�ܱ��� ����  :" . $ct_cert->mf_get_key_value("local_code" )."<br>"; // ��/�ܱ��� ����
            echo "��ȣȭ CI              :" . $ct_cert->mf_get_key_value("ci_url"     )."<br>"; // CI
            echo "��ȣȭ DI              :" . $ct_cert->mf_get_key_value("di_url"     )."<br>"; // DI �ߺ����� Ȯ�ΰ�
            echo "��ȣȭ WEB_SITEID      :" . $ct_cert->mf_get_key_value("web_siteid" )."<br>"; // WEB_SITEID
            echo "��ȣȭ ����ڵ�        :" . $ct_cert->mf_get_key_value("res_cd"     )."<br>"; // ��ȣȭ�� ����ڵ�
            echo "��ȣȭ ����޽���      :" . $ct_cert->mf_get_key_value("res_msg"    )."<br>"; // ��ȣȭ�� ����޽���
                if ( empty($ct_cert->mf_get_key_value("phone_no")) ) {
                    $ct_cert->decrypt_enc_cert( $g_conf_home_dir , $g_conf_ENC_KEY , $site_cd , $cert_no , $enc_cert_data2 , $opt );
                }
//        else/*if( res_cd.equals( "0000" ) != true )*/
//        {
//           // ��������
//        }

                if ( !empty($ct_cert->mf_get_key_value("phone_no") )) {

					$auth_phone_no = $ct_cert->mf_get_key_value("phone_no");
					if ( $ct_cert->mf_get_key_value("local_code") == '01') {
						$auth_local_code = 'Kor';
					} else if ( $ct_cert->mf_get_key_value("local_code") == '02') {
						$auth_local_code = 'For';
					}

					$phone_result = '';
					if (substr($auth_phone_no, 0, 1) == '0'){
						$phone_result = '+82'.substr($auth_phone_no, 1); // +8210....
					} else {
						$phone_result = '+82'.$auth_phone_no; // +82...
					}

					$db = getDbInstance();
					$db->orWhere("auth_phone", $auth_phone_no);
					$db->orWhere("phone", $phone_result);
					$row = $db->get('admin_accounts');
					if ($db->count == 0) {

						$_val_u = [];
						$auth_name = $ct_cert->mf_get_key_value("user_name");
						$auth_dob_y = substr($ct_cert->mf_get_key_value("birth_day"), 0, 4);
						$auth_dob_m = substr($ct_cert->mf_get_key_value("birth_day"), 4, 2);
						$auth_dob_d = substr($ct_cert->mf_get_key_value("birth_day"), 6, 2);
						$auth_ci = $ct_cert->mf_get_key_value("ci_url");
						$auth_di = $ct_cert->mf_get_key_value("di_url");

						if ( $ct_cert->mf_get_key_value("sex_code") == '01') {
							$auth_gender = 'male';
						} else if ( $ct_cert->mf_get_key_value("sex_code") == '02') {
							$auth_gender = 'female';
						}

						$_val_u['phone'] = $auth_phone_no;
						$_val_u['name'] = $auth_name;

						if ($auth_gender) {
							$_val_u['gender'] = $auth_gender;
						}
						if ($auth_dob_y && $auth_dob_m && $auth_dob_d) {
							$_val_u['dob'] = $auth_dob_y.'-'.$auth_dob_m.'-'.$auth_dob_d;
						}
						if ($auth_local_code) {
							$_val_u['local_code'] = $auth_local_code;
						}
						$_val_u['auth_ci'] = $auth_ci;
						$_val_u['auth_di'] = $auth_di;

						$_val_u['id_auth_at'] = date("Y-m-d H:i:s");

						$_val_u['user_ip'] = $user_ip;

						$db2 = getDbInstance();
						$t_id = $db2->insert ('temp_accounts', $_val_u);

						$success_url = 'register_au.php?tid='.$t_id;
						$_SESSION['auth_re_r'] = 'fin';
						echo "<script>if( ( navigator.userAgent.indexOf('Android') > - 1 || navigator.userAgent.indexOf('iPhone') > - 1 || navigator.userAgent.indexOf('android-web-view') > - 1 ) ){ parent.location.replace('".$success_url."'); } else { opener.location.replace('".$success_url."');window.close(); }</script>";

						} else { // 이미 인증완료한 번호
						$_SESSION['failure'] = $langArr['phone_already_rg'];
						$_SESSION['auth_re_r'] = 'fin';
						//fn_logSave('Personal Identification Error : '.$langArr['phone_already_rg']);

						$data_auth_err = [];
						$data_auth_err['type'] = 'auth';
						$data_auth_err['cause'] = 'decrypt';
						$data_auth_err['message'] = $langArr['phone_already_rg'];
						$data_auth_err['browser_infos'] = $browser_infos;
						$data_auth_err['page_url'] = $page_url;
						$data_auth_err['user_ip'] = $user_ip;
						$data_auth_err['created_at'] = date("Y-m-d H:i:s");
						$db_err_insert = getDbInstance();
						$db_err_insert->insert('auth_error', $data_auth_err);

						echo "<script>if( ( navigator.userAgent.indexOf('Android') > - 1 || navigator.userAgent.indexOf('iPhone') > - 1 || navigator.userAgent.indexOf('android-web-view') > - 1 ) ){ parent.location.replace('".$next_url."'); } else { opener.location.replace('".$next_url."');window.close(); }</script>";
					}

				} else {
					// 인증실패
					$_SESSION['failure'] = $langArr['auth_err_try_again'];
					$_SESSION['auth_re_r'] = 'fin';
					fn_logSave('Personal Identification Error : '.$langArr['auth_err_try_again']);

					$data_auth_err = [];
					$data_auth_err['type'] = 'auth';
					$data_auth_err['cause'] = 'decrypt';
					$data_auth_err['message'] = $langArr['auth_err_try_again'];
					$data_auth_err['browser_infos'] = $browser_infos;
					$data_auth_err['page_url'] = $page_url;
					$data_auth_err['user_ip'] = $user_ip;
					$data_auth_err['created_at'] = date("Y-m-d H:i:s");
					$db_err_insert = getDbInstance();
					$db_err_insert->insert('auth_error', $data_auth_err);

					echo "<script>if( ( navigator.userAgent.indexOf('Android') > - 1 || navigator.userAgent.indexOf('iPhone') > - 1 || navigator.userAgent.indexOf('android-web-view') > - 1 ) ){ parent.location.replace('".$next_url."'); } else { opener.location.replace('".$next_url."');window.close(); }</script>";
				} // if


			} catch (Exception $e) {
				$_SESSION['failure'] = $langArr['auth_failed'];
				$_SESSION['auth_re_r'] = 'fin';
				fn_logSave('Personal Identification Error : '.$langArr['auth_failed']);

				$data_auth_err = [];
				$data_auth_err['type'] = 'auth';
				$data_auth_err['cause'] = 'decrypt';
				$data_auth_err['message'] = $e->getMessage();
				$data_auth_err['browser_infos'] = $browser_infos;
				$data_auth_err['page_url'] = $page_url;
				$data_auth_err['user_ip'] = $user_ip;
				$data_auth_err['created_at'] = date("Y-m-d H:i:s");
				$db_err_insert = getDbInstance();
				$db_err_insert->insert('auth_error', $data_auth_err);

				echo "<script>if( ( navigator.userAgent.indexOf('Android') > - 1 || navigator.userAgent.indexOf('iPhone') > - 1 || navigator.userAgent.indexOf('android-web-view') > - 1 ) ){ parent.location.replace('".$next_url."'); } else { opener.location.replace('".$next_url."');window.close(); }</script>";
			}

		}
		else { //if( res_cd.equals( "0000" ) != true )
			// 인증실패
			$_SESSION['failure'] = $langArr['auth_failed'];
			$_SESSION['auth_re_r'] = 'fin';
			fn_logSave('Personal Identification Error : '.$langArr['auth_failed']);

			$data_auth_err = [];
			$data_auth_err['type'] = 'auth';
			$data_auth_err['cause'] = 'failed';
			$data_auth_err['message'] = $langArr['auth_failed'];
			$data_auth_err['browser_infos'] = $browser_infos;
			$data_auth_err['page_url'] = $page_url;
			$data_auth_err['user_ip'] = $user_ip;
			$data_auth_err['created_at'] = date("Y-m-d H:i:s");
			$db_err_insert = getDbInstance();
			$db_err_insert->insert('auth_error', $data_auth_err);

			echo "<script>if( ( navigator.userAgent.indexOf('Android') > - 1 || navigator.userAgent.indexOf('iPhone') > - 1 || navigator.userAgent.indexOf('android-web-view') > - 1 ) ){ parent.location.replace('".$next_url."'); } else { opener.location.replace('".$next_url."');window.close(); }</script>";
		}
	}
	else { //if( cert_enc_use.equals( "Y" ) != true )
		// 암호화 인증 안함
		$_SESSION['failure'] = $langArr['auth_not_encryption'];
		$_SESSION['auth_re_r'] = 'fin';
		fn_logSave('Personal Identification Error : '.$langArr['auth_not_encryption']);

		$data_auth_err = [];
		$data_auth_err['type'] = 'auth';
		$data_auth_err['cause'] = 'none';
		$data_auth_err['message'] = $langArr['auth_not_encryption'];
		$data_auth_err['browser_infos'] = $browser_infos;
		$data_auth_err['page_url'] = $page_url;
		$data_auth_err['user_ip'] = $user_ip;
		$data_auth_err['created_at'] = date("Y-m-d H:i:s");
		$db_err_insert = getDbInstance();
		$db_err_insert->insert('auth_error', $data_auth_err);

		echo "<script>if( ( navigator.userAgent.indexOf('Android') > - 1 || navigator.userAgent.indexOf('iPhone') > - 1 || navigator.userAgent.indexOf('android-web-view') > - 1 ) ){ parent.location.replace('".$next_url."'); } else { opener.location.replace('".$next_url."');window.close(); }</script>";
	}
} else {
	unset($_SESSION['success']);
	unset($_SESSION['failure']);
	echo "<script>if( ( navigator.userAgent.indexOf('Android') > - 1 || navigator.userAgent.indexOf('iPhone') > - 1 || navigator.userAgent.indexOf('android-web-view') > - 1 ) ){ parent.location.replace('".$next_url."'); } else { opener.location.replace('".$next_url."');window.close(); }</script>";
}

    $ct_cert->mf_clear();
function fn_logSave($log){ //로그내용 인자
	$logPathDir = "/var/www/html/src/Template/Front2/_log";  //로그위치 지정

	$filePath = $logPathDir."/".date("Y")."/".date("n");
	$folderName1 = date("Y"); //폴더 1 년도 생성
	$folderName2 = date("n"); //폴더 2 월 생성

	if(!is_dir($logPathDir."/".$folderName1)){
		mkdir($logPathDir."/".$folderName1, 0777);
	}

	if(!is_dir($logPathDir."/".$folderName1."/".$folderName2)){
		mkdir(($logPathDir."/".$folderName1."/".$folderName2), 0777);
	}

		$log_file = fopen($logPathDir."/".$folderName1."/".$folderName2."/".date("Ymd").".txt", "a");
		fwrite($log_file, date("Y-m-d H:i:s ").$log."\r\n");
		fclose($log_file);
}

?>




<!--<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">-->
<!--<html xmlns="http://www.w3.org/1999/xhtml" >-->
<!--    <head>-->
<!--<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">-->
<!--        <title>*** KCP Online Payment System [PHP Version] ***</title>-->
<!--        <script type="text/javascript">-->
<!--            window.onload=function()-->
<!--            {-->
<!--                try-->
<!--                {-->
<!--                    opener.auth_data( document.form_auth ); // �θ�â���� �� ����-->
<!---->
<!--                    window.close();// �˾� �ݱ�-->
<!--                }-->
<!--                catch(e)-->
<!--                {-->
<!--                    alert(e); // �������� �θ�â�� iframe �� ��ã�� �����-->
<!--                }-->
<!--            }-->
<!--        </script>-->
<!--    </head>-->
<!--    <body oncontextmenu="return false;" ondragstart="return false;" onselectstart="return false;">-->
<!--        <form name="form_auth" method="post">-->
<!--            --><?//= $sbParam ?>
<!--        </form>-->
<!--    </body>-->
<!--</html>-->
