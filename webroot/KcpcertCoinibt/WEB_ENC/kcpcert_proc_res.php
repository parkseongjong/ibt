<?php
/* ============================================================================== */
/* =   인증데이터 수신 및 복호화 페이지          = */
/* = -------------------------------------------------------------------------- = */
/* =   해당 페이지는 반드시 가맹점 서버에 업로드 되어야 하며                    = */ 
/* =   가급적 수정없이 사용하시기 바랍니다.                                     = */
/* ============================================================================== */

/* ============================================================================== */
 /* =   라이브러리 파일 Include                                                  = */
/* = -------------------------------------------------------------------------- = */
//session_start();

//ini_set("display_errors", 0);

include "../cfg/cert_conf.php";
require "../lib/ct_cli_lib.php";

/* = -------------------------------------------------------------------------- = */
/* =   라이브러리 파일 Include END                                               = */
/* ============================================================================== */

/* ============================================================================== */
/* =   null 값을 처리하는 메소드                                                = */
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
//$auth_phone_no = "";
//$user_name = "";
/*

$auth_dob_y = '';
$auth_dob_m = '';
$auth_dob_d = '';
$auth_gender = '';
$auth_local_code = '';
$t_id = ''; // 회원가입시 넘겨줄 temp_accounts.id
$auth_ci = '';
$auth_di = '';
$success_url = '';
*/
$sbParam = '';


/*------------------------------------------------------------------------*/
/*  :: 전체 파라미터 남기기                                               */
/*------------------------------------------------------------------------*/

// request 로 넘어온 값 처리
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

	// 부모창으로 넘기는 form 데이터 생성 필드
	$sbParam .= "<input type='hidden' name='" . $nmParam . "' value='" . f_get_parm_str( $valParam ) . "'/>";
}

$ct_cert = new C_CT_CLI;
$ct_cert->mf_clear();

//$next_url = 'https://coinibt.io/front2/Users/signup';

// 결과 처리

if( $cert_enc_use == "Y" )
{
	if( $res_cd == "0000" )
	{
		// dn_hash 검증
		// KCP 가 리턴해 드리는 dn_hash 와 사이트 코드, 요청번호 , 인증번호를 검증하여, By verifying the dn_hash returned by KCP, the site code, request number, and authentication number,
		// 해당 데이터의 위변조를 방지합니다, Prevents forgery of the data
		 $veri_str = $site_cd.$ordr_idxx.$cert_no; // 사이트 코드 + 요청번호 + 인증거래번호

		if ( $ct_cert->check_valid_hash ( $g_conf_home_dir , $g_conf_ENC_KEY , $dn_hash , $veri_str ) != "1" )
		{
			// 검증 실패시 처리 영역, Processing area when verification fails

			echo "dn_hash 변조 위험있음";
			// 오류 처리 ( dn_hash 변조 위험있음), Risk of dn_hash tampering
		}

		// 가맹점 DB 처리 페이지 영역, DB processing page area

//		echo "========================= 리턴 데이터 ======================="       ."<br>";
//		echo "사이트 코드            :" . $site_cd                                 ."<br>";
//		echo "인증 번호              :" . $cert_no                                 ."<br>";
//		echo "암호된 인증정보        :" . $enc_cert_data2                          ."<br>";

		// 인증데이터 복호화 함수, Authentication data decryption function
		// 해당 함수는 암호화된 enc_cert_data2 를, Encrypted enc_cert_data2
		// site_cd 와 cert_no 를 가지고 복화화 하는 함수 입니다., Decryption function
		// 정상적으로 복호화 된경우에만 인증데이터를 가져올수 있습니다., Authentication data can be imported only when it is successfully decrypted.
		$opt = "1" ; // 복호화 인코딩 옵션 ( UTF - 8 사용시 "1" ) 
		$ct_cert->decrypt_enc_cert( $g_conf_home_dir , $g_conf_ENC_KEY , $site_cd , $cert_no , $enc_cert_data2 , $opt );
//		echo "========================= 복호화 데이터 ====================="       ."<br>";
//		echo "복호화 이동통신사 코드 :" . $ct_cert->mf_get_key_value("comm_id"    )."<br>"; // 이동통신사 코드
//		echo "복호화 전화번호        :" . $ct_cert->mf_get_key_value("phone_no"   )."<br>"; // 전화번호
//		echo "복호화 이름            :" . $ct_cert->mf_get_key_value("user_name"  )."<br>"; // 이름
//		echo "복호화 생년월일        :" . $ct_cert->mf_get_key_value("birth_day"  )."<br>"; // 생년월일
//		echo "복호화 성별코드        :" . $ct_cert->mf_get_key_value("sex_code"   )."<br>"; // 성별코드
//		echo "복호화 내/외국인 정보  :" . $ct_cert->mf_get_key_value("local_code" )."<br>"; // 내/외국인 정보
//		echo "복호화 CI              :" . $ct_cert->mf_get_key_value("ci_url"     )."<br>"; // CI
//		echo "복호화 DI              :" . $ct_cert->mf_get_key_value("di_url"     )."<br>"; // DI 중복가입 확인값
//		echo "복호화 WEB_SITEID      :" . $ct_cert->mf_get_key_value("web_siteid" )."<br>"; // WEB_SITEID
//		echo "복호화 결과코드        :" . $ct_cert->mf_get_key_value("res_cd"     )."<br>"; // 암호화된 결과코드
//		echo "복호화 결과메시지      :" . $ct_cert->mf_get_key_value("res_msg"    )."<br>"; // 암호화된 결과메시지

        //$comm_id =  $ct_cert->mf_get_key_value("comm_id");
        $phone_no = $ct_cert->mf_get_key_value("phone_no");
        $user_name = $ct_cert->mf_get_key_value("user_name");

		$sbParam .= "<input type='hidden' name='phone_no' id='phone_no' value='" . f_get_parm_str( $phone_no ) . "'/>";
		$sbParam .= "<input type='hidden' name='user_name' id='user_name' value='" . f_get_parm_str( $user_name ) . "'/>";

		// CI, DI values should not be used in the following ways: Session, Cookie, Input-Text, Input-Hidden
		// Recommended way : Submit(action=signup) -> Decrypt from the "signup" page
		
	}
	elseif( res_cd.equals( "0000" ) != true )
	{
	   // 인증실패, failed
        echo('Verification Failed!');
	}
}
else/*if( cert_enc_use.equals( "Y" ) != true )*/
{
	// 암호화 인증 안함, No encryption authentication

}

$ct_cert->mf_clear();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>KCP Online Verification</title>
		<script src="/assets/html/js/jquery.js"></script> 
        <script type="text/javascript">
		$(document).ready(function(){
			try
			{
				//$(opener.document.find('#phone').val($('#phone_no').val());
				opener.document.signup.phone.value = $('#phone_no').val();
				opener.document.signup.user_name.value = $('#user_name').val();
				window.close();


                /*var thisOpener = window.opener;
				document.form_auth.action = "/front2/Users/signup";
                //document.form_auth.target = thisOpener;
				document.form_auth.submit();
				//thisOpener.close();// popup close
				*/

			}
			catch (e)
			{
				 alert(e);
			}
		});

        </script>
    </head>
    <body oncontextmenu="return false;" ondragstart="return false;" onselectstart="return false;">
        <form name="form_auth" method="post" id="form_auth">
            <?= $sbParam ?>
        </form>
    </body>
</html>

