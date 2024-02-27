<?php 
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class KcpComponent extends Component
{

	  // 변수 선언 부분
    var $m_dec_data;


	public function f_get_parm_str( $val ){
		if ( $val == null ) $val = "";
		if ( $val == ""   ) $val = "";
		return  $val;
	}
	 
	 public function f_get_parm_int( $val ) {
        $ret_val = "";
        
        if ( $val == null ) $val = "00";
        if ( $val == ""   ) $val = "00";
      
        $ret_val = strlen($val) == 1? ("0" . $val) : $val;
      
        return  $ret_val;
    }
	

    // 변수 초기화 영역
    public function mf_clear()
    {
        $this->m_dec_data="";        
    }

    // hash 처리 영역
    public function make_hash_data( $home_dir , $key , $str )
    {
        $hash_data = $this -> mf_exec( $home_dir . "/bin/ct_cli" ,
                                       "lf_CT_CLI__make_hash_data",
		                               $key,
                                       $str
                                     );

        if ( $hash_data == "" ) { $hash_data = "HS01"; }
        
        return $hash_data;
    }

    // dn_hash 체크 함수
    public function check_valid_hash ($home_dir , $key , $hash_data , $str )
    {
        $ret_val = $this -> mf_exec( $home_dir . "/bin/ct_cli" ,
                                     "lf_CT_CLI__check_valid_hash" ,
		                             $key,
                                     $hash_data ,
                                     $str
                                    );

        if ( $ret_val == "" ) { $ret_val = "HS02"; }

        return $ret_val;
    }

    // 암호화 인증데이터 복호화
    public function decrypt_enc_cert ( $home_dir, $key , $site_cd , $cert_no , $enc_cert_data , $opt)
    {
        $dec_data = $this -> mf_exec( $home_dir . "/bin/ct_cli" ,
                                     "lf_CT_CLI__decrypt_enc_cert" ,
		                             $key,		
                                     $site_cd ,
                                     $cert_no ,
                                     $enc_cert_data ,
                                     $opt
                                    );
        if ( $dec_data == "" ) { $dec_data = "HS03"; }


        parse_str( str_replace( chr( 31 ), "&", $dec_data ), $this->m_dec_data );
		
		return $dec_data;
    }

    public function get_kcp_lib_ver( $home_dir )
    {
        $ver_data = $this -> mf_exec( $home_dir . "/bin/ct_cli" , 
                                       "lf_CT_CLI__get_kcp_lib_ver"
                                     );

        if ( $ver_data == "" ) { $ver_data = "HS04"; }
        
        return $ver_data;
    }

    // 인증데이터 get data
    public function mf_get_key_value( $name )
    {
        return  $this->m_dec_data[ $name ];
    }

    public function  mf_exec()
    {
      $arg = func_get_args();

      if ( is_array( $arg[0] ) )  $arg = $arg[0];

      $exec_cmd = array_shift( $arg );

      while ( list(,$i) = each($arg) )
      {
        $exec_cmd .= " " . escapeshellarg( $i );
      }

      $rt = exec( $exec_cmd );

      return  $rt;
    }
}