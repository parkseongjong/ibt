<?php
    /* ============================================================================== */
    /* =   ����â ȣ�� �� ���� ������                                               = */
    /* = -------------------------------------------------------------------------- = */
    /* =   �ش� �������� �ݵ�� ������ ������ ���ε� �Ǿ�� �ϸ�                    = */ 
    /* =   ������ �������� ����Ͻñ� �ٶ��ϴ�.                                     = */
    /* ============================================================================== */

    /* ============================================================================== */
    /* =   ���̺귯�� ���� Include                                                  = */
    /* = -------------------------------------------------------------------------- = */
    $sbParam = ""; // 추가, YMJ
    include "../cfg/cert_conf.php";
    require "../lib/ct_cli_lib.php";

    /* = -------------------------------------------------------------------------- = */
    /* =   ���̺귯�� ���� Include END                                               = */
    /* ============================================================================== */
?>
<?php
    /* ============================================================================== */
    /* =   null ���� ó���ϴ� �޼ҵ�                                                = */
    /* = -------------------------------------------------------------------------- = */
    function f_get_parm_str( $val )
    {
        if ( $val == null ) $val = "";
        if ( $val == ""   ) $val = "";
        return  $val;
    }

    //!!�߿� �ش� �Լ��� year, month, day ������ null �� ��� 00 ���� ġȯ�մϴ�
    function f_get_parm_int( $val )
    {
        $ret_val = "";
        
        if ( $val == null ) $val = "00";
        if ( $val == ""   ) $val = "00";
      
        $ret_val = strlen($val) == 1? ("0" . $val) : $val;
      
        return  $ret_val;
    }
    /* ============================================================================== */
?>
<?php
    $req_tx        = "";

    $site_cd       = "";
    $ordr_idxx     = "";

    $year          = "";
    $month         = "";
    $day           = "";
    $user_name     = "";
    $sex_code      = "";
    $local_code    = "";

    $cert_able_yn  = "";
    $web_siteid    = "";
    $web_siteid_hashYN    = "";
    
    $up_hash       = "";
	/*------------------------------------------------------------------------*/
    /*  :: ��ü �Ķ���� �����                                               */
    /*------------------------------------------------------------------------*/

    $ct_cert = new C_CT_CLI;
    $ct_cert->mf_clear();
	
    // request �� �Ѿ�� ������ ó��
    foreach($_POST as $nmParam => $valParam)
    {
         if ( $nmParam == "site_cd" )
        {
            $site_cd = f_get_parm_str ( $valParam );
        }

        if ( $nmParam == "req_tx" )
        {
            $req_tx = f_get_parm_str ( $valParam );
        }

        if ( $nmParam == "ordr_idxx" )
        {
            $ordr_idxx = f_get_parm_str ( $valParam );
        }

        if ( $nmParam == "user_name" )
        {
            $user_name = f_get_parm_str ( $valParam );
        }

        if ( $nmParam == "year" )
        {
            $year = f_get_parm_int ( $valParam );
        }

        if ( $nmParam == "month" )
        {
            $month = f_get_parm_int ( $valParam );
        }

        if ( $nmParam == "day" )
        {
            $day = f_get_parm_int ( $valParam );
        }

        if ( $nmParam == "sex_code" )
        {
            $sex_code = f_get_parm_str ( $valParam );
        }

        if ( $nmParam == "local_code" )
        {
            $local_code = f_get_parm_str ( $valParam );
        }

        if ( $nmParam == "cert_able_yn" )
        {
            $cert_able_yn = f_get_parm_str ( $valParam );
        }

        if ( $nmParam == "web_siteid_hashYN" )
        {
            $web_siteid_hashYN = f_get_parm_str ( $valParam );
        }

        if ( $nmParam == "web_siteid" )
        {
            $web_siteid = f_get_parm_str ( $valParam );
        }

        // ����â���� �ѱ�� form ������ ���� �ʵ�
        $sbParam .= "<input type='hidden' name='" . $nmParam . "' value='" . f_get_parm_str( $valParam ) . "'/>";
    }


    if ( $req_tx == "cert" )
    {

        if ( $web_siteid_hashYN !="Y")
        {
            // web_siteid ������ ���ҽ� �ش� ���� ""(null) �� ����
            $web_siteid = "";
        }

        if ( $cert_able_yn == "Y" )
        {
            // input �ڽ� Ȱ��ȭ�� up_hash ���� ������
            $hash_data = $site_cd                  .
                         $ordr_idxx                .
                         $web_siteid               .
                         ""                        .
                         "00"                      .
                         "00"                      .
                         "00"                      .
                         ""                        .
                         ""; 
        }
        else 
        {
            // !!up_hash ������ ������ ���� ����
            // year , month , day �� ��� �ִ� ��� "00" , "00" , "00" ���� ������ �˴ϴ�
            // �׿��� ���� ���� ��� ""(null) �� �����Ͻø� �˴ϴ�.
            // up_hash ������ ������ site_cd �� ordr_idxx �� �ʼ� ���Դϴ�.
            $hash_data = $site_cd                  .
                         $ordr_idxx                .
                         $web_siteid               .
                         $user_name                .
                         f_get_parm_int ( $year  ) .
                         f_get_parm_int ( $month ) .
                         f_get_parm_int ( $day   ) .
                         $sex_code                 .
                         $local_code; 
        }

        $up_hash = $ct_cert->make_hash_data( $g_conf_home_dir, $g_conf_ENC_KEY ,$hash_data );

        // ����â���� �ѱ�� form ������ ���� �ʵ� ( up_hash )
        $sbParam .= "<input type='hidden' name='up_hash' value='" . $up_hash . "'/>";
        // KCP ����Ȯ�� ���̺귯�� ���� ����
        $sbParam .= "<input type='hidden' name='kcp_cert_lib_ver' value='" . $ct_cert->get_kcp_lib_ver( $g_conf_home_dir ) . "'/>";
    }

    $ct_cert->mf_clear();
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>*** KCP Online Payment System [PHP Version] ***</title>
        <script type="text/javascript">
            window.onload=function()
            {
                cert_page();
            }

			// ���� ��û �� ȣ�� �Լ�
            function cert_page()
            {
                var frm = document.form_auth;

				if ( ( frm.req_tx.value == "auth" || frm.req_tx.value == "otp_auth" ) )
                {
                    frm.action="./smartcert_proc_res.php";
                    
                   // MOBILE
                    if( ( navigator.userAgent.indexOf("Android") > - 1 || navigator.userAgent.indexOf("iPhone") > - 1 || navigator.userAgent.indexOf("iPad") > - 1 ) )
                    {
                        self.name="kcp_cert";
                    }
                    // PC
					else
					{
					    frm.target="kcp_cert";
					}
                    
                    frm.submit();
                    
                    window.close();
                }
				
				else if ( frm.req_tx.value == "cert" )
                {

                    if( ( navigator.userAgent.indexOf("Android") > - 1 || navigator.userAgent.indexOf("iPhone") > - 1 || navigator.userAgent.indexOf("iPad") > - 1 ) ) // ����Ʈ���� ���
                    {
                        parent.document.form_auth.veri_up_hash.value = frm.up_hash.value; // up_hash ������ ������ ���� �ʵ�
						self.name="auth_popup";
                    }
					else // ����Ʈ�� �ƴҶ�
					{
	                    opener.document.form_auth.veri_up_hash.value = frm.up_hash.value; // up_hash ������ ������ ���� �ʵ�
					}
                    frm.action="<?= $g_conf_gw_url ?>";
                    frm.submit();
                }
			}
        </script>
    </head>
    <body oncontextmenu="return false;" ondragstart="return false;" onselectstart="return false;">
        <form name="form_auth" method="post" id="form_auth">
            <?= $sbParam ?>
        </form>
    </body>
</html>
