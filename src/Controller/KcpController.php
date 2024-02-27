<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;  

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */

 
class KcpController extends AppController
{ 

	public $g_conf_home_dir     = "/var/www/html";

	public  $g_conf_site_cd      = "A95YT";
	
	public $g_conf_web_siteid   = "J20080705761";

	//public $g_conf_ENC_KEY      = "E66DCEB95BFBD45DF9DFAEEBCB092B5DC2EB3BF0";
	public $g_conf_ENC_KEY      = "75e8d7793e1dc665ef540e783a61844d3bbfcd06720dfffc01a6f7d0d76e42f7";

	public $g_conf_Ret_URL      = BASEURL."kcp/kcpcert-proc-res";
	
	/* ============================================================================== */
	/* =   02. 인증 테스트/상요 설정                                                = */
	/* = -------------------------------------------------------------------------- = */
	/* = * g_conf_gw_url 설정                                                       = */
	/* = 테스트 시 : src="https://testcert.kcp.co.kr/kcp_cert/cert_view.jsp"        = */
	/* = 실결제 시 : src="https://cert.kcp.co.kr/kcp_cert/cert_view.jsp"            = */
	/* = -------------------------------------------------------------------------- = */

	public  $g_conf_gw_url       = "https://cert.kcp.co.kr/kcp_cert/cert_view.jsp";


	public function beforeFilter(Event $event){
		parent::beforeFilter($event);
		 $this->loadComponent('Kcp');
			// Allow users to register and logout.
			// You should not add the "login" action to allow list. Doing so would
			// cause problems with normal functioning of AuthComponent.
			$this->Auth->allow(['index','kcpcertStart','kcpcertProcRes','kcpcertProcReq']);
	 }
	
	public function kcpcertStart(){
		
		$this->set('g_conf_home_dir',$this->g_conf_home_dir);
		$this->set('g_conf_site_cd',$this->g_conf_site_cd);
		$this->set('g_conf_web_siteid',$this->g_conf_web_siteid);
		$this->set('g_conf_ENC_KEY',$this->g_conf_ENC_KEY);
		$this->set('g_conf_Ret_URL',$this->g_conf_Ret_URL);
		$this->set('g_conf_gw_url',$this->g_conf_gw_url);
		
	}
	
	public function kcpcertProcReq(){

		$sbParam = "";
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

		$ct_cert = $this->Kcp;
		$ct_cert->mf_clear();

		// request �� �Ѿ�� ������ ó��
		foreach($_POST as $nmParam => $valParam)
		{
			 if ( $nmParam == "site_cd" )
			{
				$site_cd = $ct_cert->f_get_parm_str ( $valParam );
			}

			if ( $nmParam == "req_tx" )
			{
				$req_tx = $ct_cert->f_get_parm_str ( $valParam );
			}

			if ( $nmParam == "ordr_idxx" )
			{
				$ordr_idxx = $ct_cert->f_get_parm_str ( $valParam );
			}

			if ( $nmParam == "user_name" )
			{
				$user_name = $ct_cert->f_get_parm_str ( $valParam );
			}

			if ( $nmParam == "year" )
			{
				$year = $ct_cert->f_get_parm_int ( $valParam );
			}

			if ( $nmParam == "month" )
			{
				$month = $ct_cert->f_get_parm_int ( $valParam );
			}

			if ( $nmParam == "day" )
			{
				$day = $ct_cert->f_get_parm_int ( $valParam );
			}

			if ( $nmParam == "sex_code" )
			{
				$sex_code = $ct_cert->f_get_parm_str ( $valParam );
			}

			if ( $nmParam == "local_code" )
			{
				$local_code = $ct_cert->f_get_parm_str ( $valParam );
			}

			if ( $nmParam == "cert_able_yn" )
			{
				$cert_able_yn = $ct_cert->f_get_parm_str ( $valParam );
			}

			if ( $nmParam == "web_siteid_hashYN" )
			{
				$web_siteid_hashYN = $ct_cert->f_get_parm_str ( $valParam );
			}

			if ( $nmParam == "web_siteid" )
			{
				$web_siteid = $ct_cert->f_get_parm_str ( $valParam );
			}

			// ����â���� �ѱ�� form ������ ���� �ʵ�
			$sbParam .= "<input type='hidden' name='" . $nmParam . "' value='" . $ct_cert->f_get_parm_str( $valParam ) . "'/>";
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
							 $ct_cert->f_get_parm_int ( $year  ) .
							 $ct_cert->f_get_parm_int ( $month ) .
							 $ct_cert->f_get_parm_int ( $day   ) .
							 $sex_code                 .
							 $local_code; 
			}

			$up_hash = $ct_cert->make_hash_data( $this->g_conf_home_dir, $this->g_conf_ENC_KEY ,$this->hash_data );

			// ����â���� �ѱ�� form ������ ���� �ʵ� ( up_hash )
			$sbParam .= "<input type='hidden' name='up_hash' value='" . $up_hash . "'/>";
			// KCP ����Ȯ�� ���̺귯�� ���� ����
			$sbParam .= "<input type='hidden' name='kcp_cert_lib_ver' value='" . $ct_cert->get_kcp_lib_ver( $this->g_conf_home_dir ) . "'/>";
		}

		$ct_cert->mf_clear();

		$this->set('g_conf_home_dir',$this->g_conf_home_dir);
		$this->set('g_conf_site_cd',$this->g_conf_site_cd);
		$this->set('g_conf_web_siteid',$this->g_conf_web_siteid);
		$this->set('g_conf_ENC_KEY',$this->g_conf_ENC_KEY);
		$this->set('g_conf_Ret_URL',$this->g_conf_Ret_URL);
		$this->set('g_conf_gw_url',$this->g_conf_gw_url);
		$this->set('sbParam',$sbParam);
	}
	
	
	public function kcpcertProcRes(){

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
			$phone_no = "";
			$user_name = "";
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
			print_r($_POST);
			// request 로 넘어온 값 처리
			foreach($_POST as $nmParam => $valParam)
			{

				if ( $nmParam == "site_cd" )
				{
					$site_cd = $this->Kcp->f_get_parm_str ( $valParam );
				}

				if ( $nmParam == "ordr_idxx" )
				{
					$ordr_idxx = $this->Kcp->f_get_parm_str ( $valParam );
				}

				if ( $nmParam == "res_cd" )
				{
					$res_cd = $this->Kcp->f_get_parm_str ( $valParam );
				}

				if ( $nmParam == "cert_enc_use" )
				{
					$cert_enc_use = $this->Kcp->f_get_parm_str ( $valParam );
				}

				if ( $nmParam == "req_tx" )
				{
					$req_tx = $this->Kcp->f_get_parm_str ( $valParam );
				}

				if ( $nmParam == "cert_no" )
				{
					$cert_no = $this->Kcp->f_get_parm_str ( $valParam );
				}

				if ( $nmParam == "enc_cert_data2" )
				{
					$enc_cert_data2 = $this->Kcp->f_get_parm_str ( $valParam );
				}

				if ( $nmParam == "dn_hash" )
				{
					$dn_hash = $this->Kcp->f_get_parm_str ( $valParam );
				}

				// 추가(YMJ)
				if ( $nmParam == "param_opt_1" ) {
					$param_opt_1 = $this->Kcp->f_get_parm_str ( $valParam );
				}
				if ( $nmParam == "param_opt_2" ) {
					$param_opt_2 = $this->Kcp->f_get_parm_str ( $valParam );
				}
				if ( $nmParam == "param_opt_3" ) {
					$param_opt_3 = $this->Kcp->f_get_parm_str ( $valParam );
				}

				// 부모창으로 넘기는 form 데이터 생성 필드
				$sbParam .= "<input type='hidden' name='" . $nmParam . "' value='" . $this->Kcp->f_get_parm_str( $valParam ) . "'/>";
			}

			$ct_cert = $this->Kcp;
			$ct_cert->mf_clear();

			/*$next_url = 'https://coinibt.io/front2/Users/signup';*/
        $next_url = 'https://bitsomon.com/front2/Users/signup';

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

					echo "========================= 리턴 데이터 ======================="       ."<br>";
					echo "사이트 코드            :" . $site_cd                                 ."<br>";
					echo "인증 번호              :" . $cert_no                                 ."<br>";
					echo "암호된 인증정보        :" . $enc_cert_data2                          ."<br>";

					// 인증데이터 복호화 함수, Authentication data decryption function
					// 해당 함수는 암호화된 enc_cert_data2 를, Encrypted enc_cert_data2
					// site_cd 와 cert_no 를 가지고 복화화 하는 함수 입니다., Decryption function
					// 정상적으로 복호화 된경우에만 인증데이터를 가져올수 있습니다., Authentication data can be imported only when it is successfully decrypted.
					$opt = "0" ; // 복호화 인코딩 옵션 ( UTF - 8 사용시 "1" ) 
					$ct_cert->decrypt_enc_cert( $g_conf_home_dir , $g_conf_ENC_KEY , $site_cd , $cert_no , $enc_cert_data2 , $opt );
					echo "========================= 복호화 데이터 ====================="       ."<br>";
					echo "복호화 이동통신사 코드 :" . $ct_cert->mf_get_key_value("comm_id"    )."<br>"; // 이동통신사 코드   
					echo "복호화 전화번호        :" . $ct_cert->mf_get_key_value("phone_no"   )."<br>"; // 전화번호          
					echo "복호화 이름            :" . $ct_cert->mf_get_key_value("user_name"  )."<br>"; // 이름
					echo "복호화 생년월일        :" . $ct_cert->mf_get_key_value("birth_day"  )."<br>"; // 생년월일          
					echo "복호화 성별코드        :" . $ct_cert->mf_get_key_value("sex_code"   )."<br>"; // 성별코드          
					echo "복호화 내/외국인 정보  :" . $ct_cert->mf_get_key_value("local_code" )."<br>"; // 내/외국인 정보    
					echo "복호화 CI              :" . $ct_cert->mf_get_key_value("ci_url"     )."<br>"; // CI                
					echo "복호화 DI              :" . $ct_cert->mf_get_key_value("di_url"     )."<br>"; // DI 중복가입 확인값
					echo "복호화 WEB_SITEID      :" . $ct_cert->mf_get_key_value("web_siteid" )."<br>"; // WEB_SITEID
					echo "복호화 결과코드        :" . $ct_cert->mf_get_key_value("res_cd"     )."<br>"; // 암호화된 결과코드
					echo "복호화 결과메시지      :" . $ct_cert->mf_get_key_value("res_msg"    )."<br>"; // 암호화된 결과메시지

//			        $user_name =  $ct_cert->mf_get_key_value("user_name");
//			        $phone_no = $ct_cert->mf_get_key_value("phone_no");
					//code 
					// db ...
					//etc...
//                    $this->set('name', $user_name);
//                    $this->set('phone', $phone_no);
				}
				else/*if( res_cd.equals( "0000" ) != true )*/
				{
				   // 인증실패, failed
				}
			}
			else/*if( cert_enc_use.equals( "Y" ) != true )*/
			{
				// 암호화 인증 안함, No encryption authentication

			}

			$ct_cert->mf_clear();

		$this->set('g_conf_home_dir',$this->g_conf_home_dir);
		$this->set('g_conf_site_cd',$this->g_conf_site_cd);
		$this->set('g_conf_web_siteid',$this->g_conf_web_siteid);
		$this->set('g_conf_ENC_KEY',$this->g_conf_ENC_KEY);
		$this->set('g_conf_Ret_URL',$this->g_conf_Ret_URL);
		$this->set('g_conf_gw_url',$this->g_conf_gw_url);
		$this->set('sbParam',$sbParam);
	}
   
}
