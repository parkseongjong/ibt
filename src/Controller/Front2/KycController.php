<?php
namespace App\Controller\Front2;
use App\Controller\AppController; // HAVE TO USE App\Controller\AppController
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Cake\Datasource\ConnectionManager;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Event\Event;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

class KycController extends AppController
{

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['authenticated','applicantCreated','applicantStatusChanged','applicantPersonInfoChanged']);
    }

    public function index() {
    }

    public function authenticated()
    {
        $this->loadModel('Users');
        $BASIS_API_SECRET2 = 's2-ILDtWPkSttscTEqXquUmfmEOknkwtRPZ';
        // write json data to file
        $rawData = file_get_contents("php://input");
        file_put_contents("KycDetails.txt",$rawData."\r\n");
        // write post data to file
        $rawPostData = $_REQUEST;
        file_put_contents("KycDetails_post.txt",json_encode($rawPostData)."\r\n");
        $obj = json_decode($rawData,true);

        if(!empty($obj)){
            $signature = '';
            if(!isset($obj['callback_type'])){
                $signature = hash('sha256', $BASIS_API_SECRET2 . $obj['user_id'] . $obj['user_hash'] . $obj['status'] . $obj['autocheck_bad_reasons']);
            } else {
                $signature = hash('sha256', $BASIS_API_SECRET2 . $obj['user_id'] . $obj['user_hash'] . $obj['status'] . $obj['reason'] . $obj['request'] . $obj['message']);
            }

            if ($signature == $obj['signature']) {
                $query = $this->Users->query();

                if($obj['status'] == "10"){
                    $status = "A";
                    $level = "3";
                } else if ($obj['status'] == "11"){
                    $status = "D";
                } else {
                    $status = "R";
                }

                if($status == "R"){
                    $query->update()->set(['id_document_status' => $status,'id_document_reject_reason' => $obj['reason'], 'scan_copy_status' => $status, 'scan_copy_reject_reason' => $obj['request'],
                        'review_message' => $obj['message']])->where(['user_hash'=>$obj['user_hash']])->execute();
                } else {
                    $query->update()->set(['id_document_status' => $status, 'scan_copy_status' => $status, 'id_document_reject_reason' => (isset($obj['autocheck_bad_reasons'])) ? $obj['autocheck_bad_reasons'] : '',
                        'scan_copy_reject_reason' => (isset($obj['request'])) ? $obj['request'] : '', 'review_message' => (isset($obj['message'])) ? $obj['message'] : '',
                        'user_level' => ($status == 'A' ? '3' : '2')])
                        ->where(['user_hash'=>$obj['user_hash']])->execute();
                }
                $this->getUserInfo($obj['user_hash'], $BASIS_API_SECRET2);

                header('HTTP/1.1 200 OK good');
            } else {
                echo 'WRONG SIGNATURE';
            }
        } else {
            echo 'Empty Object';
        }
        die;
    }

    public function getUserInfo($user_hash, $api_secret) {
        $this->loadModel('Users');
        $api_key = 'prod-PQyyrYZBZYJFPeerTTmhgVPewGblwgBg';
        $signature = hash('sha256', $user_hash . $api_secret);
        $query = $this->Users->query();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_PORT => "",
            CURLOPT_URL => "https://api.basisid.com/users/info/" . $user_hash . "/" . $api_key . "/" . $signature,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $decodeResp = json_decode($response,true);

        if(!empty($decodeResp) ){
            if ( $decodeResp['status'] == 'ok' ) {
                $query->update()->set(['id_number' => $this->Encrypt($decodeResp['profile']['document_number']), 'access_token' => $decodeResp['profile']['access_token'], 'id_document_front' => $decodeResp['profile']['passport'],
                    'id_document_back' => $decodeResp['profile']['passport2'], 'scan_copy' => $decodeResp['profile']['photo']])->where(['user_hash' => $user_hash])->execute();
            } else {
                echo "Error!";
            }
        }
    }

    public function applicantCreated(){
        $this->loadModel('Users');
        $SUMSUB_SECRET_KEY = 'wl028b0ad87o9eo5cfa5vnj6qtt';
        // write json data to file
        $rawData = file_get_contents("php://input");
        file_put_contents("applicantId.txt",$rawData."\r\n");
        // write post data to file
        $rawPostData = $_REQUEST;
        file_put_contents("applicantId_post.txt",json_encode($rawPostData)."\r\n");
        $obj = json_decode($rawData,true);
        //print_r($obj);
        if(!empty($obj)){
            $query = $this->Users->query();
            $query->update()->set(['applicant_id' => $obj['applicantId'], 'kyc_status' => $obj['reviewStatus']])->where(['external_user_id' => $obj['externalUserId']])->execute();
        } else {
            echo 'Empty Object';
        }
        die;
    }

    public function applicantStatusChanged(){
        $this->loadModel('Users');
        $SUMSUB_SECRET_KEY = 'dhbk15jxf4gqm8pewhbux8gm8pl';
        // write json data to file
        $rawData = file_get_contents("php://input");
        file_put_contents("applicantStatus.txt",$rawData."\r\n");
        // write post data to file
        $rawPostData = $_REQUEST;
        file_put_contents("applicantStatus_post.txt",json_encode($rawPostData)."\r\n");
        $obj = json_decode($rawData,true);
        //print_r($obj);
        if(!empty($obj)){
            $query = $this->Users->query();
            $query->update()->set(['kyc_status' => $obj['reviewStatus']])->where(['external_user_id' => $obj['externalUserId'], 'applicant_id' => $obj['applicantId']])->execute();
        } else {
            echo 'Empty Object';
        }
        die;
    }

    public function applicantPersonInfoChanged(){
        $this->loadModel('Users');
        $SUMSUB_SECRET_KEY = 'dhbk15jxf4gqm8pewhbux8gm8pl';
        // write json data to file
        $rawData = file_get_contents("php://input");
        file_put_contents("applicantInfo.txt",$rawData."\r\n");
        // write post data to file
        $rawPostData = $_REQUEST;
        file_put_contents("applicantInfo_post.txt",json_encode($rawPostData)."\r\n");
        $obj = json_decode($rawData,true);

        if(!empty($obj)){
            $query = $this->Users->query();
            $query->update()->set(['kyc_status' => $obj['reviewStatus'],'review_answer' => $obj['reviewResult']['reviewAnswer']])->where(['external_user_id'=>$obj['externalUserId'], 'applicant_id' => $obj['applicantId']])->execute();
        } else {
            echo 'Empty Object';
        }
        die;
    }


}

