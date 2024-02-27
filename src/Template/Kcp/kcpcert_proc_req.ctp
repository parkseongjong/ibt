
        <script type="text/javascript">

			window.onload=function()
            {
                var frm = document.form_auth;

                // 인증 요청 시 호출 함수
                if ( frm.req_tx.value == "cert" )
                {
                    //thisOpener.document.form_auth.veri_up_hash.value = frm.up_hash.value; // up_hash 데이터 검증을 위한 필드
					opener.document.form_auth.veri_up_hash.value = frm.up_hash.value; // up_hash 데이터 검증을 위한 필드
                                
                    frm.action="<?= $g_conf_gw_url ?>";
                    frm.submit();
                }

                // 인증 결과 데이터 리턴 페이지 호출 함수
                else if ( ( frm.req_tx.value == "auth" || frm.req_tx.value == "otp_auth" ) )
                {
                    frm.action="<?php echo $this->Url->build(['controller'=>'Kcp','action'=>'kcpcertProcRes']); ?>";
                    frm.submit();
                }
                else
                {
                    //alert ("req_tx 값을 확인해 주세요");
                }
            }

        </script>

    <body oncontextmenu="return false;" ondragstart="return false;" onselectstart="return false;">
        <form name="form_auth" method="post" id="form_auth">
            <?= $sbParam ?>
        </form>
    </body>

 