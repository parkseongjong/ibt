<?php
namespace App\Controller\Front2;

use App\Controller\AppController; // HAVE TO USE App\Controller\AppController

use Cake\Event\Event;

class DocumentController extends AppController
{
	public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Csrf');
    }
	public function beforeFilter(Event $event) {
        parent::beforeFilter($event);

        $this->Auth->allow([
            'privacy',
            'faq',
            'deallimit',
            'joininfo',
            'priceinfo',
            'cominfo',
            'usinginfo', 
            'reqdoc',
            'terms-staking',
            'terms-rental',
            'terms-annual',
            'terms-deal',
            'terms-deal-coupon',
        ]);
        /*
          $lang = isset($_COOKIE['Language']) ? $_COOKIE['Language'] : 'en_US';
          I18n::locale($lang);
         */
    }
    public function index() {
    }

    public function deallimit() {
        $this->set('kind', 'deallimit');
        $this->set('page_title', '회원레벨별 입/출금 한도 안내');
    }

    public function reqdoc($tabpos=7) {
        $this->set('tabpos', $tabpos);
        $this->set('kind', 'reqdoc');

        $this->viewBuilder()->template('reqdoc'.$tabpos);
    }
/*
    public function reqdoc1() {
        $this->set('tabpos', 1);
        $this->set('kind', 'reqdoc');
    }

    public function reqdoc3() {
        $this->set('tabpos', 3);
        $this->set('kind', 'reqdoc');
    }

    public function reqdoc5() {
        $this->set('tabpos', 5);
        $this->set('kind', 'reqdoc');
    }
*/
    public function cominfo() {
        $this->set('kind', 'cominfo');
    }

    public function usinginfo() {
        $this->set('kind', 'usinginfo');
    }

    public function privacy() {
        $this->set('kind', 'privacy');
    }

    public function termsStaking() {
        $this->set('kind', 'terms-staking');
    }
    public function termsRental() {
        $this->set('kind', 'terms-rental');
    }
    public function termsAnnual() {
        $this->set('kind', 'terms-annual');
    }
    public function termsDeal() {
        $this->set('kind', 'terms-deal');
    }
    public function termsDealCoupon() {
        $this->set('kind', 'terms-deal-coupon');
    }

    public function sitemap() {
        $this->set('kind', 'sitemap');
        $this->set('page_title', '사이트맵');
    }

    public function priceinfo() {
        $this->set('kind', 'priceinfo');
        $this->loadModel('Users');
		$this->loadModel('NumberSixSetting');
		$this->loadModel('PrincipalWallet');
		$query = $this->NumberSixSetting->find()->select(['id','cryptocoin_id','amount','krw','short_name'=>'coin.short_name','time_limit']);
		$settingList = $query->join(['coin' => ['table' => 'cryptocoin','type' => 'inner','conditions' => 'coin.id = cryptocoin_id']])->where(['NumberSixSetting.status'=>"ACTIVE"])->toArray();
		$masked = '';
		$name = '';
        $none = 1;
		$userId = 0;
        $annualMember = '';
       
		if ($this->Auth->user('id') ) {
		    $userId = $this->Auth->user('id');
			$name = $this->Auth->user('name');
			$phone = $this->Auth->user('phone_number');
			$masked =  substr ($phone,-4);
			$mainBalance = $this->Users->getUserPricipalBalance($userId,20);
            $annualMember = $this->Auth->user('annual_membership');
            $none = 0;
			
		}
		$this->set('settingList',$settingList);
        $this->set('none', $none);
		$this->set('userId', $userId);
        $this->set('mainBalance',isset($mainBalance) ? $mainBalance : 0);
        $this->set('name', isset($name) ? $name : '');
        $this->set('phone', isset($masked) ? $masked : '');
        $this->set('annualMember', isset($annualMember) ? $annualMember : '');
		if($this->request->is('ajax')){
			echo json_encode($settingList); die;
		}
    }
    public function priceinfo2() {
        $this->set('kind', 'priceinfo');
        $this->loadModel('Users');
        $this->loadModel('NumberSixSetting');
        $this->loadModel('PrincipalWallet');

        $query = $this->NumberSixSetting->find()->select(['id','cryptocoin_id','amount','krw','short_name'=>'coin.short_name','time_limit']);
        $settingList = $query->join(['coin' => ['table' => 'cryptocoin','type' => 'inner','conditions' => 'coin.id = cryptocoin_id']])->where(['NumberSixSetting.status'=>"ACTIVE"])->toArray();
        
        $masked = '';
        $name = '';
        $none = 1;
        $userId = 0;
        $annualMember = '';

        if ($this->Auth->user('id') ) {
            $userId = $this->Auth->user('id');
            $name = $this->Auth->user('name');
            $phone = $this->Auth->user('phone_number');
            $masked =  substr ($phone,-4);
            $mainBalance = $this->Users->getUserPricipalBalance($userId,20);
            $annualMember = $this->Auth->user('annual_membership');
            $none = 0;

        }
        $this->set('settingList',$settingList);
        $this->set('none', $none);
        $this->set('userId', $userId);
        $this->set('mainBalance',isset($mainBalance) ? $mainBalance : 0);
        $this->set('name', isset($name) ? $name : '');
        $this->set('phone', isset($masked) ? $masked : '');
        $this->set('annualMember', isset($annualMember) ? $annualMember : '');
        if($this->request->is('ajax')){
            echo json_encode($settingList); die;
        }
    }
    public function buycoupon(){
        $this->loadModel('PrincipalWallet');
        $this->loadModel('Transactions');
        $this->loadModel('NumberSixSetting');
        $this->loadModel('Users');
		$this->loadModel('Cryptocoin');
        if($this->request->is('ajax')){
            $userId = $this->Auth->user('id');
            $user = $this->Users->get($userId);
			$csId = 3263;  // **************** test서버와 실서버 다른 점 필수 확인!!!
            $cs = $this->Users->get($csId);
            $csWalletAddress = $cs->eth_address;
            $secret = $user->g_secret;
            $bankAuth = $user['bank_verify'];
            $annualMember = $user['annual_membership'];
            $mainBalance = $this->Users->getUserPricipalBalance($userId,20);
            //$googleAuthUrl = $this->Users->getQRCodeGoogleUrl('CoinIBT', $secret);
            if (!empty($this->request->data['coin_price']) && !empty($this->request->data['krw_price']) && $bankAuth == "Y" && $annualMember == "Y") {
                $coinPrice = $this->request->data['coin_price'];
                $krwPrice = $this->request->data['krw_price'];
                $type = $this->request->data['type'];
				
                $coin = 0;
				$mainBalanceCoin = 0;
				$cryptocoin = $this->Cryptocoin->find()->select('id')->where(['short_name'=>strtoupper($type)])->first();
				if(empty($cryptocoin)){
					$returnArr = ['success'=>"false","message"=>'거래 불가한 코인입니다'];
                    echo json_encode($returnArr); die;
				}
				$coin = $cryptocoin->id;
				$mainBalanceCoin = $this->Users->getUserPricipalBalance($userId,$coin);
                if ($krwPrice <= $mainBalance && $coinPrice <= $mainBalanceCoin) {

                    /*
					$thisWeek = $this->PrincipalWallet->find('all',['fields'=>['totalBought' => 'SUM(amount)'],'conditions' => [
                        'type' => 'bought_coupon_krw',
                        'user_id' => $userId,
                        'YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)']])->hydrate(false)->first();
                    if(!empty($thisWeek)){
                        $returnArr = ['success'=>"false","message"=>__('You have consumed your weekly limit of buying coupons')];
                        echo json_encode($returnArr); die;
                    }

                    $previouslyTransfer = $this->PrincipalWallet->find("all",["fields"=>["totalTransfer"=>"SUM(amount)"], "conditions"=>[
                        "type"=>"bought_coupon_krw",
                        "user_id"=>$userId,
                        "date_format(created_at, '%Y-%m') = date_format(NOW(), '%Y-%m')"]])->hydrate(false)->first();

                    $previouslyTransferAmt = !empty($previouslyTransfer['totalTransfer']) ? $previouslyTransfer['totalTransfer'] : 0;
                    $couponBought = -1 * $previouslyTransferAmt;

                    $couponLimit = $this->NumberSixSetting->find('all',['conditions'=>['cryptocoin_id'=>$coin,'status'=>'ACTIVE']])->hydrate(false)->first();
                    if($couponBought+$krwPrice > $couponLimit['coupon_limit']){
                        $returnArr = ['success'=>"false","message"=>__('You have consumed your monthly limit of buying coupons')];
                        echo json_encode($returnArr); die;
                    }
					*/

                    //Deduct KRW from user's main account
                    $insertArr = [];
                    $insertArr['user_id'] = $userId;
                    $insertArr['amount'] = -$krwPrice;
                    $insertArr['cryptocoin_id'] = 20;
                    $insertArr['wallet_address'] = $user->eth_address;
                    $insertArr['type'] = "bought_coupon_krw";
                    $insertArr['remark'] = "bought_coupon_krw";
                    $insertArr['status'] = "completed";
                    $withdraw = $this->PrincipalWallet->newEntity();
                    $withdraw = $this->PrincipalWallet->patchEntity($withdraw, $insertArr);
                    $saveWithdraw = $this->PrincipalWallet->save($withdraw);

                    //Insert coupon's coin amount in user's trading account but first deduct from the main account of that coin

                    $insertcArr = [];
                    $insertcArr['user_id'] = $userId;
                    $insertcArr['amount'] = -$coinPrice;
                    $insertcArr['cryptocoin_id'] = $coin;
                    $insertcArr['wallet_address'] = $user->eth_address;
                    $insertcArr['type'] = "coupon_transfer_to_trading";
                    $insertcArr['remark'] = "coupon_transfer_to_trading";
                    $insertcArr['status'] = "completed";
                    $transferc = $this->PrincipalWallet->newEntity();
                    $transferc = $this->PrincipalWallet->patchEntity($transferc, $insertcArr);
                    $saveTransferc = $this->PrincipalWallet->save($transferc);

                    $insertsArr = [];
                    $insertsArr['user_id'] = $userId;
                    $insertsArr['coin_amount'] = $coinPrice;
                    $insertsArr['cryptocoin_id'] = $coin;
                    $insertsArr['tx_type'] = "bought_coupon";
                    $insertsArr['remark'] = "bought_coupon";
                    $insertsArr['status'] = "completed";
                    $withdraws = $this->Transactions->newEntity();
                    $withdraws = $this->Transactions->patchEntity($withdraws, $insertsArr);
                    $saveWithdraws = $this->Transactions->save($withdraws);

                    //Insert KRW in CS1 Admin's main KRW account
                    $adminInsertArr = [];
                    $adminInsertArr['user_id'] = $csId;
                    $adminInsertArr['coupon_user_id'] = $userId;
                    $adminInsertArr['amount'] = $krwPrice;
                    $adminInsertArr['coin_amount'] = $coinPrice;
                    $adminInsertArr['cryptocoin_id'] = 20;
                    $adminInsertArr['coupon_cryptocoin_id'] = $coin;
                    $adminInsertArr['wallet_address'] = $csWalletAddress;
                    $adminInsertArr['type'] = "deducted_coupon_krw";
                    $adminInsertArr['remark'] = "deducted_coupon_krw";
                    $adminInsertArr['status'] = "completed";
                    $deposit = $this->PrincipalWallet->newEntity();
                    $deposit = $this->PrincipalWallet->patchEntity($deposit, $adminInsertArr);
                    $saveDeposit = $this->PrincipalWallet->save($deposit);

                    if($saveWithdraw == true && $saveTransferc == true && $saveWithdraws == true && $saveDeposit == true){
                        //$mainBalance = $this->Users->getUserPricipalBalance($userId,20);
                        //$mainBalanceCtc = $this->Users->getUserPricipalBalance($userId,21);
                        //$mainBalanceTp3 = $this->Users->getUserPricipalBalance($userId,17);
                        //$singleArr = ['mainBalance'=>$mainBalance,'mainBalanceTp3'=>$mainBalanceTp3,'mainBalanceCtc'=>$mainBalanceCtc];
                        //$returnArr = ['success'=>"true","message"=>__('Bought coupon successfully'),'data'=>['balanceList'=>$singleArr]];
						$returnArr = ['success'=>"true","message"=>__('Bought coupon successfully'),'data'=>''];
                    } else {
                        $returnArr = ['success'=>"false","message"=>__('Unable to buy coupon')];
                    }
                } else {
                    $this->Flash->error(__('You have insufficient balance.'));
                    $returnArr = ['success' => 'false', 'message' => __('You have insufficient balance.')];
                }
            } else {
                $returnArr = ['success'=>"false","message"=>__('Please get annual membership to be eligible to buy coupons')];
            }
            echo json_encode($returnArr);
            die;
        }
    }

    public function digitalinfo() {
        $this->set('kind', 'digitalinfo');
        $this->set('page_title', '디지털 자산 소개');
    }

    public function faq() {
        $this->set('kind', 'faq');
        $this->set('page_title', __('FAQ'));

		// FAQ 카테고리 = board_faq.category => 한글작업 필수/보이고 싶은 순서대로 배치
		// DB에서 불러와서(group by) 써도 좋고.
		//$list_category = array('Sign up', 'KRW D&W');

		$lang = !empty($_COOKIE['Language']) ? $_COOKIE['Language'] : 'en_US';		
		
		$this->loadModel('BoardFaq');
		
		$listing_array = [];
		
			$allBoardFaqList = $this->BoardFaq->find('all',[
					'conditions'=>['lang '=>$lang],
					'order'=>['category'=>'asc', 'id'=>'asc'],
				] )
				 ->hydrate(false)
				 ->toArray();
		
		
		$this->set('listing',$allBoardFaqList);	
		
    }

    public function joininfo() {
        $this->set('kind', 'joininfo');
        $this->set('page_title', __('Membership Registration'));
    }

    public function authinfo() {
        $this->set('kind', 'authinfo');
        $this->set('page_title', __('Authentication method guide'));
    }

    public function commission() {
        $this->set('kind', 'commission');
        $this->set('page_title', __('Commission Coupon'));
        $this->loadModel('Users');
        $user = $this->Users->get($this->Auth->user('id'));
        $totalTransactions = $this->Users->getTotalUserTransactions($user['id']);
        $name = $user['name'];
        $this->set('name', $name);
        $this->set('totalTransactions', $totalTransactions);
    }

    public function coupon() {
        $this->set('kind', 'coupon');
        $this->set('page_title', __('Coupon Usage Status'));
    }
}
