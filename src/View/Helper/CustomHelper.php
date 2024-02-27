<?php
namespace App\View\Helper;
 
use Cake\View\Helper;
use Cake\ORM\Table;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
 
class CustomHelper extends Helper {
 
 
     
	  public function getAllUserBalance($coinId){       
		

		
		$this->Users = TableRegistry::get("Users");
		$coinId1 = TableRegistry::get("cryptocoin")->find('all', array(
			'fields'=>['id'],
            'conditions' => array(
            'short_name' => $coinId
            )
		))->first();

          $principalBalance = $this->Users->getAllUserPricipalBalance($coinId1['id']);
          $withdrawBalance = $this->Users->getLocalAllUserBalance($coinId1['id']);
          $getAllSellReserveBalance = $this->Users->getAllSellReserveBalance($coinId1['id']);
          $getAllBuyReserveBalance = $this->Users->getAllBuyReserveBalance($coinId1['id']);
          $getTotalBuyAndSell = $getAllSellReserveBalance+$getAllBuyReserveBalance;

          $returnArr = [];
          $returnArr['principalBalance'] = isset($principalBalance) ? $principalBalance : 0;
          $returnArr['withdrawBalance'] = isset($withdrawBalance) ? $withdrawBalance : 0;
          $returnArr['getTotalBuyAndSell'] = isset($getTotalBuyAndSell) ? $getTotalBuyAndSell : 0;
		

		
		return $returnArr;
        //your function code here
    }
	 
    public function getBalance($coinId,$userId){       
		

		
		$this->Users = TableRegistry::get("Users");
		$coinId1 = TableRegistry::get("cryptocoin")->find('all', array(
            'conditions' => array(
            'short_name' => $coinId
            )
		))->first();
		
		$principalBalance = $this->Users->getUserPricipalBalance($userId,$coinId1['id']);
		$withdrawBalance = $this->Users->getLocalUserBalance($userId,$coinId1['id']);
		$pendingBalance = $this->Users->getUserPendingBalance($userId,$coinId1['id']);
		$reserveBuyBalance = $this->Users->getUserBuyReserveBalance($userId,$coinId1['id']);
		$reserveSellBalance = $this->Users->getUserSellReserveBalance($userId,$coinId1['id']);
		$reserveBalance = $reserveBuyBalance + $reserveSellBalance;
		$totalWithPendingBalance = $this->Users->getUserTotalWithPendingBalance($userId,$coinId1['id']);
		
		$returnArr = [];
		$returnArr['principalBalance'] = isset($principalBalance) ? $principalBalance : 0;
		$returnArr['withdrawBalance'] = isset($withdrawBalance) ? $withdrawBalance : 0;
		$returnArr['pendingBalance'] = isset($pendingBalance) ? $pendingBalance : 0;
		$returnArr['reserveBalance'] = isset($reserveBalance) ? $reserveBalance : 0;
		$returnArr['totalWithPendingBalance'] = isset($totalWithPendingBalance) ? $totalWithPendingBalance : 0 ;
		
		return $returnArr;
        //your function code here
    }
	
    public function getBalanceForAdmin($coinId,$userId){       
		

		
		$this->Users = TableRegistry::get("Users");
		$coinId1 = TableRegistry::get("cryptocoin")->find('all', array(
            'conditions' => array(
            'short_name' => $coinId
            )
		))->first();

        $principalBalance = $this->Users->getUserPricipalBalance($userId,$coinId1['id']);
        $withdrawBalance = $this->Users->getLocalUserBalance($userId,$coinId1['id']);
        $reserveBuyBalance = $this->Users->getUserBuyReserveBalance($userId,$coinId1['id']);
        $reserveSellBalance = $this->Users->getUserSellReserveBalance($userId,$coinId1['id']);
        $reserveBalance = $reserveBuyBalance + $reserveSellBalance;

        $returnArr = [];
        $returnArr['principalBalance'] = isset($principalBalance) ? $principalBalance : 0;
        $returnArr['withdrawBalance'] = isset($withdrawBalance) ? $withdrawBalance : 0;
        $returnArr['reserveBalance'] = isset($reserveBalance) ? $reserveBalance : 0;
	
		return $returnArr;
        //your function code here
    }	

    public function getUsers(){
        $getUsers = [];
        $getUsers = TableRegistry::get("Users")->find('all')->hydrate(false)->first();
        return $getUsers;

    }

	public function getAllUserInvestmentAmount(){
		$this->Users = TableRegistry::get("Users");
		$total = $this->Users->getAllUserInvestmentAmount();
		//$returnArr = [];
		//$returnArr['total_investment_amount'] = isset($total) ? $total : 0;
		return isset($total) ? $total : 0;
	}

	public function getAllUserInvestmentWalletAmount(){
		$this->Users = TableRegistry::get("Users");
		$total = $this->Users->getAllUserInvestmentWalletAmount();
		//$returnArr = [];
		//$returnArr['total_investment_wallet_amount'] = isset($total) ? $total : 0;
		return isset($total) ? $total : 0;
	}

	public function getLastCouponTime($coinId, $userId, $limitTime){
		$returnTime = -1;
		$this->PrincipalWallet = TableRegistry::get("PrincipalWallet");
		$query = $this->PrincipalWallet->find()->select(['last_time'=>'TIMESTAMPDIFF(MINUTE,created_at,NOW())']);//MINUTE
		$query = $query->where(['cryptocoin_id'=>$coinId,'status'=>'completed','type'=>'coupon_transfer_to_trading','user_id'=>$userId,'TIMESTAMPDIFF(HOUR,created_at,NOW()) <='=>$limitTime]);
		$remainTime = $query->order(['id'=>'desc'])->first();

		if(!empty($remainTime)){
			$returnTime = $remainTime->last_time;
		}
		return $returnTime;
	}
}
?>