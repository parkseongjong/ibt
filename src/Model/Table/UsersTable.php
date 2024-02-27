<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Rule\IsUnique;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

class UsersTable extends Table
{

    protected $_codeLength = 6;

    public $btcUrl = "http://13.125.120.31:8332/";
    public $btcPort = 8332;

    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');


        $this->hasMany('ethtransactions', [
            'className' => 'Transactions',
            'foreignKey' => 'user_id',
            'conditions' => ['ethtransactions.cryptocoin_id' => 2,
                'ethtransactions.status' => 'completed']
        ]);

        $this->belongsTo('level', [
            'className' => 'Levels',
            'foreignKey' => 'level_id'
        ]);


        // for eth reserve
        $this->hasMany('eth_reserve', [
            'className' => 'BuyExchange',
            'foreignKey' => 'buyer_user_id',
            'conditions' => ['eth_reserve.buy_spend_coin_id' => 2,
                'eth_reserve.status' => 'pending']
        ]);

        // for ram reservce
        $this->hasMany('ram_reserve', [
            'className' => 'SellExchange',
            'foreignKey' => 'seller_user_id',
            'conditions' => ['ram_reserve.sell_spend_coin_id' => 3,
                'ram_reserve.status' => 'pending']
        ]);

        // for admc reservce
        $this->hasMany('admc_reserve', [
            'className' => 'SellExchange',
            'foreignKey' => 'seller_user_id',
            'conditions' => ['admc_reserve.sell_spend_coin_id' => 4,
                'admc_reserve.status' => 'pending']
        ]);

        // for usd reservce
        $this->hasMany('usd_reserve', [
            'className' => 'SellExchange',
            'foreignKey' => 'seller_user_id',
            'conditions' => ['usd_reserve.sell_spend_coin_id' => 5,
                'usd_reserve.status' => 'pending']
        ]);

        $this->hasMany('ramtransactions', [
            'className' => 'Transactions',
            'foreignKey' => 'user_id',
            'conditions' => ['ramtransactions.cryptocoin_id' => 3]
        ]);

        $this->hasMany('admctransactions', [
            'className' => 'Transactions',
            'foreignKey' => 'user_id',
            'conditions' => ['admctransactions.cryptocoin_id' => 4]
        ]);

        $this->hasMany('usdtransactions', [
            'className' => 'Transactions',
            'foreignKey' => 'user_id',
            'conditions' => ['usdtransactions.cryptocoin_id' => 5]
        ]);

        $this->belongsTo('referral_user', [
            'className' => 'Users',
            'foreignKey' => 'referral_user_id',
            'conditions' => ['referral_user.enabled' => 'Y']
        ]);

        $this->hasMany('agctransactions', [
            'className' => 'Agctransactions',
            'foreignKey' => 'user_id',
            'conditions' => ['agctransactions.status' => 'completed']
        ]);

        $this->hasMany('cointransactions', [
            'className' => 'Cointransactions',
            'foreignKey' => 'user_id',
            'conditions' => ['cointransactions.status' => '1']
        ]);

        $this->hasMany('referusers', [
            'className' => 'Users',
            'foreignKey' => 'referral_user_id',
            'conditions' => ['referusers.enabled' => 'Y']
        ]);

        $this->hasMany('tocointransfer', [
            'className' => 'Cointransfer',
            'foreignKey' => 'to_user_id',
            //'conditions'=>['referusers.enabled'=>'Y']
        ]);


        $this->hasMany('buyvolume', [
            'className' => 'BuyExchange',
            'foreignKey' => 'buyer_user_id'
        ]);

        $this->hasMany('sellvolume', [
            'className' => 'SellExchange',
            'foreignKey' => 'seller_user_id'
        ]);

    }


    public function validationDefault(Validator $validator)
    {
        $validator
            ->notEmpty('name', 'Please enter Name')
            ->notEmpty('username', 'Please enter username')
            ->notEmpty('email', 'Please enter Email')
            ->notEmpty('phone_number', 'Please enter Phone Number')
            ->notEmpty('password', 'Please enter Password')
            ->notEmpty('confirm_password', 'Please enter Confirm Password')
            ->notEmpty('old_password', 'Please enter Old Password')
            ->notEmpty('new_password', 'Please enter New Password');
        $validator->add('first_name', 'minLength', [
            'rule' => ['minLength', 3],
            'message' => 'First name should be minimum 3 characters.'
        ])
            ->add('first_name', ['maxLength' => [
                'rule' => ['maxLength', 15],
                'message' => 'First name should be maximum 15 characters.',
            ]
            ]);
        $validator->add('last_name', 'minLength', [
            'rule' => ['minLength', 3],
            'message' => 'Last name should be minimum 3 characters.'
        ])
            ->add('last_name', ['maxLength' => [
                'rule' => ['maxLength', 15],
                'message' => 'Last name should be maximum 15 characters.',
            ]
            ]);

        $validator->add('first_name', 'custom', [
            'rule' => function ($value, $context) {
                $error = 0;
                if (preg_match("/[a-z]/i", $value)) return true;
                else return false;
            },
            'message' => 'First name is not valid'
        ]);
        $validator->add('last_name', 'custom', [
            'rule' => function ($value, $context) {
                $error = 0;
                if (preg_match("/[a-z]/i", $value)) return true;
                else return false;
            },
            'message' => 'Last name is not valid'
        ]);
        /*  $validator
            ->add('username','custom',[
                'rule'=>  function($value, $context){

                    if(preg_match("/^[a-zA-Z\d]+$/", $value)) return true;
                    return false;
                },
                'message'=>'Username contain characters and digits only.',
            ]);	 */

//		$validator->add('phone_number', 'validFormat', [
//			'rule' => 'numeric',
//			'message' => 'Phone Number should be numeric'
//		])
        $validator->add('phone_number', [
            'length' => [
                'rule' => ['minLength', 6],
                'message' => 'Minimum 6 digits required',
            ]
        ]);
        /* $validator->add('email', 'validFormat', [
            'rule' => 'email',
            'message' => 'E-mail must be a valid email'
        ]); */
        $validator->add('username', 'unique', [
            'rule' => 'validateUnique',
            'provider' => 'table',
            'message' => 'username already exist'
        ]);

        $validator->add('confirm_password', [
            'equalToPassword' => [
                'rule' => function ($value, $context) {
                    return $value === $context['data']['password'];
                },
                'message' => __("Your confirm password must match with your password.")
            ]
        ]);

        $validator->add('email', 'unique', [
            'rule' => 'validateUnique',
            'provider' => 'table',
            'message' => 'Email already exist'
        ]);
        $validator->add('phone_number', 'unique', [
            'rule' => 'validateUnique',
            'provider' => 'table',
            'message' => 'Phone Number already exist'
        ])->add('phone_number', [
            'length' => [
                'rule' => ['minLength', 6],
                'message' => 'Minimum 6 digits required',
            ]
        ]);
        $validator->add('password', 'minLength', [
            'rule' => ['minLength', 8],
            'message' => 'Password should be minimum 8 characters.'
        ])
            ->add('password', ['maxLength' => [
                'rule' => ['maxLength', 20],
                'message' => 'Password should be maximum 20 characters.',
            ]
            ]);


        return $validator;
    }


    public function validationPassword(Validator $validator)
    {
        $validator
            ->notEmpty('old_password', 'Please enter old password')
            ->notEmpty('new_password', 'Please enter new password')
            ->notEmpty('confirm_password', 'Please enter confirm password');

        $validator
            ->add('old_password', 'custom', [
                'rule' => function ($value, $context) {
                    //pr($context);die;
                    $user = $this->get($context['data']['id']);
                    if ($user) {

                        if ((new DefaultPasswordHasher)->check($value, $user->password)) {
                            return true;
                        }
                    }
                    return false;
                },
                'message' => 'The old password is not correct!',
            ])
            ->notEmpty('old_password');

        $validator
            ->add('new_password', [
                'length' => [
                    'rule' => ['minLength', 8],
                    'message' => 'New password length should be min 8',
                ]
            ])
            ->add('new_password', [
                'match' => [
                    'rule' => ['compareWith', 'confirm_password'],
                    'message' => "fields don't match",
                ]
            ])
            ->notEmpty('new_password');
        $validator
            ->add('confirm_password', [
                'length' => [
                    'rule' => ['minLength', 8],
                    'message' => 'Confirm password length should be min 8',
                ]
            ])
            ->add('confirm_password', [
                'match' => [
                    'rule' => ['compareWith', 'new_password'],
                    'message' => "fields don't match",
                ]
            ])
            ->notEmpty('confirm_password');

        return $validator;
    }


    public function coinConvert($coinAmount, $convertInto)
    {
        $returnType = "";
        $this->Token = TableRegistry::get('Token');
        $totalAMXCoin = $this->Token->find('all')->hydrate(false)->first();
        $btcValInOneAgc = $totalAMXCoin['btc_value'];
        if ($btcValInOneAgc != 0) {
            if ($convertInto == "amaxgold") {
                $returnType = $coinAmount / $btcValInOneAgc;
            } else {
                $returnType = $coinAmount * $btcValInOneAgc;
            }
        }
        return $returnType;
    }


    public function getUserTotalBtc($userId)
    {

        $getUserTotalCoinSum = 0;
        $this->Agctransactions = TableRegistry::get("Agctransactions");
        $getUserTotalCoin = $this->Agctransactions->find();
        $getUserTotalCoinCnt = $getUserTotalCoin
            ->select(['sum' => $getUserTotalCoin->func()->sum('btc_coins')])
            ->where(array('user_id' => $userId, 'status' => 'completed'))
            ->toArray();

        if (!empty($getUserTotalCoinCnt)) {
            $getUserTotalCoinSum = $getUserTotalCoinCnt[0]['sum'];
        }
        return $getUserTotalCoinSum;
    }

    public function getUserTotalCoin($userId)
    {

        $getUserTotalCoinSum = 0;
        $this->Cointransactions = TableRegistry::get("Cointransactions");
        $getUserTotalCoin = $this->Cointransactions->find();
        $getUserTotalCoinCnt = $getUserTotalCoin
            ->select(['sum' => $getUserTotalCoin->func()->sum('coin')])
            ->where(array('user_id' => $userId, 'status' => 1))
            ->toArray();

        if (!empty($getUserTotalCoinCnt)) {
            $getUserTotalCoinSum = $getUserTotalCoinCnt[0]['sum'];
        }
        return $getUserTotalCoinSum;
    }

    /*
    Assign Coin At User Verification
    */
    public function assginCoinOnVerifition($userId)
    {
        $cudate = date("Y-m-d H:i:s");
        $newInsertArr = [];
        $newInsertArr['user_id'] = $userId;
        $newInsertArr['btc'] = "";
        $newInsertArr['coin'] = 5;
        $newInsertArr['dollar'] = "";
        $newInsertArr['doller_per_hc'] = "";
        $newInsertArr['type'] = "registration";
        $newInsertArr['updated_at'] = $cudate;

        // insert data
        $this->Cointransactions = TableRegistry::get("Cointransactions");
        $purchaseCoinTransactions = $this->Cointransactions->newEntity();
        $purchaseCoinTransactions = $this->Cointransactions->patchEntity($purchaseCoinTransactions, $newInsertArr);
        $saveData = $this->Cointransactions->save($purchaseCoinTransactions);
        $cointransactionsId = $saveData->id;
    }

    /*
    Assign Coin if Not assign at verifation
    */

    public function assginCoinOnLogin($userId)
    {

        $this->Cointransactions = TableRegistry::get("Cointransactions");
        $isAssignedAtVerification = $this->Cointransactions->find('all', ['conditions' => ['user_id' => $userId, 'type' => 'registration']])->hydrate(false)->first();

        if (empty($isAssignedAtVerification)) {
            $cudate = date("Y-m-d H:i:s");
            $newInsertArr = [];
            $newInsertArr['user_id'] = $userId;
            $newInsertArr['btc'] = "";
            $newInsertArr['coin'] = 10;
            $newInsertArr['dollar'] = "";
            $newInsertArr['doller_per_hc'] = "";
            $newInsertArr['type'] = "registration";
            $newInsertArr['updated_at'] = $cudate;

            $purchaseCoinTransactions = $this->Cointransactions->newEntity();
            $purchaseCoinTransactions = $this->Cointransactions->patchEntity($purchaseCoinTransactions, $newInsertArr);
            $saveData = $this->Cointransactions->save($purchaseCoinTransactions);
            $cointransactionsId = $saveData->id;
        }
    }


    public function getCoinPrice()
    {
        $this->Token = TableRegistry::get("Token");
        $totalAMXCoin = $this->Token->find('all', ['conditions' => ['id' => 5]])->hydrate(false)->first();
        return $coinPrice = $totalAMXCoin['price'];
    }

    public function getUserTotalInvestment($userId)
    {

        $getUserTotalCoinSum = 0;
        $this->Investment = TableRegistry::get("Investment");
        $getUserTotalCoin = $this->Investment->find();
        $getUserTotalCoinCnt = $getUserTotalCoin
            ->select(['sum' => $getUserTotalCoin->func()->sum('amount')])
            ->where(array('user_id' => $userId, 'status' => 'completed', 'type' => 'investment'))
            ->toArray();

        if (!empty($getUserTotalCoinCnt)) {
            $getUserTotalCoinSum = $getUserTotalCoinCnt[0]['sum'];
        }
        return $getUserTotalCoinSum;
    }


    public function updateReserveDays($userId)
    {

        $getUserInvestmentAmount = $this->getUserTotalInvestment($userId);
        $this->LandingProgram = TableRegistry::get("LandingProgram");
        $getData = $this->LandingProgram->find("all", ["conditions" => ["start_range <=" => $getUserInvestmentAmount, "end_range >=" => $getUserInvestmentAmount]])->hydrate(false)->first();
        $getReserveDays = $getData['reserve_days'];


        $this->Users = TableRegistry::get("Users");
        $users = $this->Users->get($userId);
        $users = $this->Users->patchEntity($users, [
            'user_reserve_days' => $getReserveDays
        ]);
        $this->Users->save($users);

    }

    public function getBtcPricePerHcBuy()
    {
        $getBitJsonData = file_get_contents("https://blockchain.info/ticker");
        $getDecode = json_decode($getBitJsonData, true);
        /* $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://blockchain.info/ticker');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $contents = curl_exec($ch);
        $getDecode = json_decode($contents,true); */

        $buyUsd = $getDecode['USD']['buy'];
        $btcPerUsd = 1 / $buyUsd;
        $btcPerHc = $btcPerUsd * 6;
        return number_format((float)$btcPerHc, 8);
    }

    public function getBtcPricePerHcSell()
    {
        $getBitJsonData = file_get_contents("https://blockchain.info/ticker");
        $getDecode = json_decode($getBitJsonData, true);
        /* $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://blockchain.info/ticker');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $contents = curl_exec($ch);
        $getDecode = json_decode($contents,true); */

        $buyUsd = $getDecode['USD']['buy'];
        $btcPerUsd = 1 / $buyUsd;
        $btcPerHc = $btcPerUsd * 5.5;
        return number_format((float)$btcPerHc, 8);
    }


    public function getUserTotalHc($userId)
    {

        $getUserTotalCoinSum = 0;
        $this->Cointransactions = TableRegistry::get("Cointransactions");
        $getUserTotalCoin = $this->Cointransactions->find();
        $getUserTotalCoinCnt = $getUserTotalCoin
            ->select(['sum' => $getUserTotalCoin->func()->sum('coin')])
            ->where(array('user_id' => $userId, 'status' => 1))
            ->toArray();

        if (!empty($getUserTotalCoinCnt)) {
            $getUserTotalCoinSum = $getUserTotalCoinCnt[0]['sum'];
        }
        return $getUserTotalCoinSum;
    }


    public function getCoinLastExhangePrice()
    {
        $this->Exchange = TableRegistry::get("Exchange");
        $CoinPrice = $this->Exchange->find('all', ['conditions' => ['status' => 'completed'], 'limit' => 1, 'order' => ['id' => 'desc']])->hydrate(false)->first();
        if (!empty($CoinPrice)) {
            return $coinPrice = $CoinPrice['price_per_hc'];
        } else {
            return "6";
        }
    }

    public function getUserSellExchangeCoin($userId)
    {

        $getUserTotalCoinSum = 0;
        $this->Exchange = TableRegistry::get("Exchange");
        $getUserTotalCoin = $this->Exchange->find();
        $getUserTotalCoinCnt = $getUserTotalCoin
            ->select(['sum' => $getUserTotalCoin->func()->sum('sell_hc_amount')])
            ->where(array('seller_user_id' => $userId, 'status' => 'pending'))
            ->toArray();

        if (!empty($getUserTotalCoinCnt)) {
            $getUserTotalCoinSum = $getUserTotalCoinCnt[0]['sum'];
        }
        return $getUserTotalCoinSum;
    }

    /*
    used for create address at Token Server
    */

    public function createTokenWalletAddress($pass)
    {

        //extract data from the post
        //set POST variables
        $url = 'http://139.162.50.231/examples/create_account.php';
        $fields = array(
            'password' => urlencode($pass)
        );
        $fields_string = '';
        //url-ify the data for the POST
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        rtrim($fields_string, '&');

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        return $result = curl_exec($ch);
    }


    public function getCoinBalance($address)
    {

        //extract data from the post
        //set POST variables
        $url = 'http://139.162.50.231/examples/get_balance.php';
        $fields = array(
            'address' => urlencode($address),
        );
        $fields_string = '';
        //url-ify the data for the POST
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        rtrim($fields_string, '&');

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        return $result = curl_exec($ch);
    }


    public function getUserTransferredHc($userId)
    {

        $getUserTransferredCoinSum = 0;
        $this->Cointransfer = TableRegistry::get("Cointransfer");
        $getUserTransferredCoin = $this->Cointransfer->find();
        $getUserTransferredCoinCnt = $getUserTransferredCoin
            ->select(['sum' => $getUserTransferredCoin->func()->sum('coin_amount')])
            ->where(array('to_user_id' => $userId))
            ->toArray();

        if (!empty($getUserTransferredCoinCnt)) {
            $getUserTransferredCoinSum = $getUserTransferredCoinCnt[0]['sum'];
        }
        return $getUserTransferredCoinSum;
    }

    public function addlog($insertData = [])
    {
        $this->ExchangeLog = TableRegistry::get("ExchangeLog");
        $ExchangeLogEntry = $this->ExchangeLog->newEntity();
        $ExchangeLogEntry = $this->ExchangeLog->patchEntity($ExchangeLogEntry, $insertData);
        $this->ExchangeLog->save($ExchangeLogEntry);
    }

    public function withdrawBtcAmount($toAddress = [], $toAmount = [], $securePin = null)
    {
        $toAddressCount = count($toAddress);
        $toAmountCount = count($toAmount);

        $toAddress = implode(",", $toAddress);
        $toAmount = implode(",", $toAmount);

        $url = "https://block.io/api/v2/withdraw/?api_key=5bb4-ec3a-8548-de45&to_addresses=" . $toAddress . "&amounts=" . $toAmount . "&pin=" . $securePin;
        //$url  ="https://block.io/api/v2/withdraw/?api_key=0fb9-6643-b12d-e7d0&to_addresses=".$toAddress."&amounts=".$toAmount."&pin=".$securePin;


        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        //curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);
        curl_close($curl);


        return $jsonDecode = json_decode($result, true);
    }

    public function withdrawSingleBtcAmount($toAddress, $toAmount, $securePin = "")
    {
        $toAmount = number_format(abs($toAmount), 8);
        $url = "https://block.io/api/v2/withdraw/?api_key=5bb4-ec3a-8548-de45&to_addresses=" . $toAddress . "&amounts=" . $toAmount . "&pin=klose118";
        //$url  ="https://block.io/api/v2/withdraw/?api_key=0fb9-6643-b12d-e7d0&to_addresses=".$toAddress."&amounts=".$toAmount."&pin=klose118";


        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        //curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);
        curl_close($curl);


        return $jsonDecode = json_decode($result, true);
    }


    public function coinpayments_api_call($cmd, $req = array())
    {
        // Fill these in from your API Keys page

        $public_key = 'f85bdb8106b2cd05e99aa79408a866c1e04a12e035f81b5835f461e6efded87a';
        $private_key = 'bC84727a34c0C9B79a8c34d3dca28fE994501e758CB3e33C204F2499a09a77cb';

        // Set the API command and required fields
        $req['version'] = 1;
        $req['cmd'] = $cmd;
        $req['key'] = $public_key;
        $req['format'] = 'json'; //supported values are json and xml


        // Generate the query string
        $post_data = http_build_query($req, '', '&');

        // Calculate the HMAC signature on the POST data
        $hmac = hash_hmac('sha512', $post_data, $private_key);

        // Create cURL handle and initialize (if needed)
        static $ch = NULL;
        if ($ch === NULL) {
            $ch = curl_init('https://www.coinpayments.net/api.php');
            curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('HMAC: ' . $hmac));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

        // Execute the call and close cURL handle
        $data = curl_exec($ch);
        // Parse and return data if successful.
        if ($data !== FALSE) {
            if (PHP_INT_SIZE < 8 && version_compare(PHP_VERSION, '5.4.0') >= 0) {
                // We are on 32-bit PHP, so use the bigint as string option. If you are using any API calls with Satoshis it is highly NOT recommended to use 32-bit PHP
                $dec = json_decode($data, TRUE, 512, JSON_BIGINT_AS_STRING);
            } else {
                $dec = json_decode($data, TRUE);
            }
            if ($dec !== NULL && count($dec)) {
                return $dec;
            } else {
                // If you are using PHP 5.5.0 or higher you can use json_last_error_msg() for a better error message
                return array('error' => 'Unable to parse JSON result (' . json_last_error() . ')');
            }
        } else {
            return array('error' => 'cURL error: ' . curl_error($ch));
        }
    }


    /**
     * Creates a withdrawal from your account to a specified address.<br />
     * @param amount The amount of the transaction (floating point to 8 decimals).
     * @param currency The cryptocurrency to withdraw.
     * @param address The address to send the coins to.
     * @param auto_confirm If auto_confirm is TRUE, then the withdrawal will be performed without an email confirmation.
     * @param ipn_url Optionally set an IPN handler to receive notices about this transaction. If ipn_url is empty then it will use the default IPN URL in your account.
     */
    public function createWithdrawal($amount, $currency, $address, $auto_confirm = FALSE, $ipn_url = '')
    {
        $req = array(
            'amount' => $amount,
            'currency' => $currency,
            'address' => $address,
            'auto_confirm' => $auto_confirm ? 1 : 0,
            'ipn_url' => $ipn_url,
        );
        return $this->coinpayments_api_call('create_withdrawal', $req);

        /* 	$cmd = "create_withdrawal";
            $public_key = '9badf0ce66fe54682a8c5ccb924e4b32844dfc8d859a916b515371dc06ff184f';
            $private_key = '52Bd80da1535664F319Ce6cf1e8a39E33a2844eb5b1f9940F10a0d3Cabc0D8c8';

            // Set the API command and required fields
            $req['version'] = 1;
            $req['cmd'] = $cmd;
            $req['key'] = $public_key;
            $req['format'] = 'json'; //supported values are json and xml



            // Generate the query string
            $post_data = http_build_query($req, '', '&');

            // Calculate the HMAC signature on the POST data
            $hmac = hash_hmac('sha512', $post_data, $private_key);

            // Create cURL handle and initialize (if needed)
            static $ch = NULL;
            if ($ch === NULL) {
                $ch = curl_init('https://www.coinpayments.net/api.php');
                curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('HMAC: '.$hmac));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

            // Execute the call and close cURL handle
            $data = curl_exec($ch);
            // Parse and return data if successful.
            if ($data !== FALSE) {
                if (PHP_INT_SIZE < 8 && version_compare(PHP_VERSION, '5.4.0') >= 0) {
                    // We are on 32-bit PHP, so use the bigint as string option. If you are using any API calls with Satoshis it is highly NOT recommended to use 32-bit PHP
                    $dec = json_decode($data, TRUE, 512, JSON_BIGINT_AS_STRING);
                } else {
                    $dec = json_decode($data, TRUE);
                }
                if ($dec !== NULL && count($dec)) {
                    return $dec;
                } else {
                    // If you are using PHP 5.5.0 or higher you can use json_last_error_msg() for a better error message
                    return array('error' => 'Unable to parse JSON result ('.json_last_error().')');
                }
            } else {
                return array('error' => 'cURL error: '.curl_error($ch));
            }  */

    }

    public function getUserTotalWithdrawn($userId)
    {
        $getUserTotalWithdrawnSum = 0;
        $this->PrincipalWallet = TableRegistry::get("PrincipalWallet");
        $getUserTotalWithdrawn = $this->PrincipalWallet->find();
        $getUserTotalWithdrawnCnt = $getUserTotalWithdrawn
            ->select(['sum' => $getUserTotalWithdrawn->func()->sum('amount')])
            ->where(array('user_id' => $userId, 'type' => 'bank_initial_withdraw', 'status' => 'completed'))
            ->toArray();
        if (!empty($getUserTotalWithdrawnCnt)) {
            $getUserTotalWithdrawnSum = $getUserTotalWithdrawnCnt[0]['sum'];
        }
        return $getUserTotalWithdrawnSum;
    }

    public function getUserTotalDeposit($userId)
    {

        $getUserTotalDepositSum = 0;
        $this->PrincipalWallet = TableRegistry::get("PrincipalWallet");
        $getUserTotalDeposit = $this->PrincipalWallet->find();
        $getUserTotalDepositCnt = $getUserTotalDeposit
            ->select(['sum' => $getUserTotalDeposit->func()->sum('amount')])
            ->where(array('user_id' => $userId, 'type' => 'bank_initial_deposit', 'status' => 'completed'))
            ->toArray();

        if (!empty($getUserTotalDepositCnt)) {
            $getUserTotalDepositSum = $getUserTotalDepositCnt[0]['sum'];
        }
        return $getUserTotalDepositSum;
    }

    /* 한달 총 입금액 - 이충현 210820 */
    public function getUserTotalMonthDeposit($userId)
    {

        $getUserTotalDepositSum = 0;
        $this->PrincipalWallet = TableRegistry::get("PrincipalWallet");
        $getUserTotalDeposit = $this->PrincipalWallet->find();
        $getUserTotalDepositCnt = $getUserTotalDeposit
            ->select(['sum' => $getUserTotalDeposit->func()->sum('amount')])
            ->where(array(
                'user_id' => $userId,
                'status' => 'completed',
                'type' => 'bank_initial_deposit',
                'TIMESTAMPDIFF(DAY,created_at,NOW()) <= ' => 30))
            ->toArray();

        if (!empty($getUserTotalDepositCnt)) {
            $getUserTotalDepositSum = $getUserTotalDepositCnt[0]['sum'];
        }
        return $getUserTotalDepositSum;
    }

    public function getUserTotalWithdrawnWithoutFees($userId)
    {
        $getUserTotalWithdrawnSum = 0;
        $this->PrincipalWallet = TableRegistry::get("PrincipalWallet");
        $getUserTotalWithdrawn = $this->PrincipalWallet->find();
        $getUserTotalWithdrawnCnt = $getUserTotalWithdrawn
            ->select(['sum' => $getUserTotalWithdrawn->func()->sum('coin_amount')])
            ->where(array('user_id' => $userId, 'status' => 'completed', 'type' => 'bank_initial_withdraw'))
            ->toArray();
        if (!empty($getUserTotalWithdrawnCnt)) {
            $getUserTotalWithdrawnSum = $getUserTotalWithdrawnCnt[0]['sum'];
        }
        return $getUserTotalWithdrawnSum;
    }

    public function getUserTotalOldDeposit($userId)
    {

        $getUserTotalDepositSum = 0;
        $this->Transactions = TableRegistry::get("Transactions");
        $getUserTotalDeposit = $this->Transactions->find();
        $getUserTotalDepositCnt = $getUserTotalDeposit
            ->select(['sum' => $getUserTotalDeposit->func()->sum('coin_amount')])
            ->where(array('user_id' => $userId, 'tx_type' => 'bank_initial_deposit', 'status' => 'completed'))
            ->toArray();

        if (!empty($getUserTotalDepositCnt)) {
            $getUserTotalDepositSum = $getUserTotalDepositCnt[0]['sum'];
        }
        return $getUserTotalDepositSum;
    }

    public function getCoinCoupon($userId, $coinId)
    {
        $this->NumberSixSetting = TableRegistry::get("NumberSixSetting");
        $coupon = $this->NumberSixSetting->find()
            ->select('amount')
            ->where(['user_id' => $userId, 'cryptocoin_id' => $coinId, 'status' => "ACTIVE"])
            ->order(['id' => 'DESC'])
            ->hydrate(false)
            ->first();
        if (!empty($coupon)) {
            return $coupon;
        }
    }

    public function getAllSellReserveBalance($cryptoCoinId)
    {

        $this->Users = TableRegistry::get("Users");
        $adminList = $this->Users->find('list', ['keyField' => 'id',
            'valueField' => 'id',
            'conditions' => ['user_type' => 'A']
        ])->toArray();


        $getUserTransferredCoinSum = 0;
        $this->Transactions = TableRegistry::get("Transactions");
        $getUserTransferredCoin = $this->Transactions->find();
        $getUserTransferredCoinCnt = $getUserTransferredCoin
            ->select(['sum' => $getUserTransferredCoin->func()->sum('coin_amount')])
            ->where(['Transactions.user_id NOT IN ' => $adminList,
                'Transactions.cryptocoin_id' => $cryptoCoinId,
                'Transactions.status' => 'completed',
                'Transactions.remark' => 'reserve for exchange',
                //'sellReserve.status !='=>'deleted',
                'sellReserve.status in ' => ['pending', 'processing']
            ])
            ->contain(['sellReserve'])
            ->toArray();

        if (!empty($getUserTransferredCoinCnt)) {
            $getUserTransferredCoinSum = $getUserTransferredCoinCnt[0]['sum'];
        }
        return $getUserTransferredCoinSum;
    }

    public function getAllBuyReserveBalance($cryptoCoinId)
    {

        $this->Users = TableRegistry::get("Users");
        $adminList = $this->Users->find('list', ['keyField' => 'id',
            'valueField' => 'id',
            'conditions' => ['user_type' => 'A']
        ])->toArray();


        $getUserTransferredCoinSum = 0;
        $this->Transactions = TableRegistry::get("Transactions");
        $getUserTransferredCoin = $this->Transactions->find();
        $getUserTransferredCoinCnt = $getUserTransferredCoin
            ->select(['sum' => $getUserTransferredCoin->func()->sum('coin_amount')])
            ->where(['Transactions.user_id NOT IN ' => $adminList,
                'Transactions.cryptocoin_id' => $cryptoCoinId,
                'Transactions.status' => 'completed',
                'Transactions.remark' => 'reserve for exchange',
                //'buyReserve.status !='=>'deleted'
                'buyReserve.status in ' => ['pending', 'processing']
            ])
            ->contain(['buyReserve'])
            ->toArray();

        if (!empty($getUserTransferredCoinCnt)) {
            $getUserTransferredCoinSum = $getUserTransferredCoinCnt[0]['sum'];
        }
        return $getUserTransferredCoinSum;
    }

    public function getUserTotalReceivedReward($userId)
    {

        $getUserTotalRewardSum = 0;
        $this->PrincipalWallet = TableRegistry::get("PrincipalWallet");
        $getUserTotalReward = $this->PrincipalWallet->find();
        $getUserTotalRewardCnt = $getUserTotalReward
            ->select(['sum' => $getUserTotalReward->func()->sum('amount')])
            ->where(array(
                'user_id' => $userId,
                'status' => 'completed',
                'type' => 'purchase',
                'remark' => 'airdrop reward',
                'cryptocoin_id' => 20))
            ->toArray();

        if (!empty($getUserTotalRewardCnt)) {
            $getUserTotalRewardSum = $getUserTotalRewardCnt[0]['sum'];
        }
        return $getUserTotalRewardSum;
    }

    public function getUserTotalBuy($userId)
    {

        $getUserTotalBuyCoinSum = 0;
        $this->BuyExchange = TableRegistry::get("BuyExchange");
        $getUserTotalBuyCoin = $this->BuyExchange->find();
        $getUserTotalBuyCoinCnt = $getUserTotalBuyCoin
            ->select(['sum' => $getUserTotalBuyCoin->func()->sum('buy_spend_amount')])
            ->where(array('buyer_user_id' => $userId, 'status' => 'completed'))
            ->toArray();

        if (!empty($getUserTotalBuyCoinCnt)) {
            $getUserTotalBuyCoinSum = $getUserTotalBuyCoinCnt[0]['sum'];
        }
        return $getUserTotalBuyCoinSum;
    }

    /* 한달 총 구매 금액 - 이충현 210820 */
    public function getUserTotalMonthBuy($userId)
    {

        $getUserTotalBuyCoinSum = 0;
        $this->BuyExchange = TableRegistry::get("BuyExchange");
        $getUserTotalBuyCoin = $this->BuyExchange->find();
        $getUserTotalBuyCoinCnt = $getUserTotalBuyCoin
            ->select(['sum' => $getUserTotalBuyCoin->func()->sum('buy_spend_amount')])
            ->where(array(
                'buyer_user_id' => $userId,
                'status' => 'completed',
                'TIMESTAMPDIFF(DAY,created_at,NOW()) <= ' => 30))
            ->toArray();

        if (!empty($getUserTotalBuyCoinCnt)) {
            $getUserTotalBuyCoinSum = $getUserTotalBuyCoinCnt[0]['sum'];
        }
        return $getUserTotalBuyCoinSum;
    }

    public function getUserTotalSell($userId)
    {

        $getUserTotalSellCoinSum = 0;
        $this->SellExchange = TableRegistry::get("SellExchange");
        $getUserTotalSellCoin = $this->SellExchange->find();
        $getUserTotalSellCoinCnt = $getUserTotalSellCoin
            ->select(['sum' => $getUserTotalSellCoin->func()->sum('sell_get_amount')])
            ->where(array('seller_user_id' => $userId, 'status' => 'completed'))
            ->toArray();

        if (!empty($getUserTotalSellCoinCnt)) {
            $getUserTotalSellCoinSum = $getUserTotalSellCoinCnt[0]['sum'];
        }
        return $getUserTotalSellCoinSum;
    }

    /* 한달 총 판매 금액 - 이충현 210820 */
    public function getUserTotalMonthSell($userId)
    {

        $getUserTotalSellCoinSum = 0;
        $this->SellExchange = TableRegistry::get("SellExchange");
        $getUserTotalSellCoin = $this->SellExchange->find();
        $getUserTotalSellCoinCnt = $getUserTotalSellCoin
            ->select(['sum' => $getUserTotalSellCoin->func()->sum('sell_get_amount')])
            ->where(array(
                'seller_user_id' => $userId,
                'status' => 'completed',
                'TIMESTAMPDIFF(DAY,created_at,NOW()) <= ' => 30))
            ->toArray();

        if (!empty($getUserTotalSellCoinCnt)) {
            $getUserTotalSellCoinSum = $getUserTotalSellCoinCnt[0]['sum'];
        }
        return $getUserTotalSellCoinSum;
    }

    public function getUserTotalBuyCoins($userId, $coinId)
    {

        $getUserTotalBuyCoinSum = 0;
        $this->BuyExchange = TableRegistry::get("BuyExchange");
        $getUserTotalBuyCoin = $this->BuyExchange->find();
        $getUserTotalBuyCoinCnt = $getUserTotalBuyCoin
            ->select(['sum' => $getUserTotalBuyCoin->func()->sum('total_buy_spend_amount')])
            ->where(array('buyer_user_id' => $userId, 'buy_get_coin_id' => $coinId, 'status' => 'completed'))
            ->toArray();

        if (!empty($getUserTotalBuyCoinCnt)) {
            $getUserTotalBuyCoinSum = $getUserTotalBuyCoinCnt[0]['sum'];
        }
        return $getUserTotalBuyCoinSum;
    }

    public function getUserTotalSellCoins($userId, $coinId)
    {

        $getUserTotalSellCoinSum = 0;
        $this->SellExchange = TableRegistry::get("SellExchange");
        $getUserTotalSellCoin = $this->SellExchange->find();
        $getUserTotalSellCoinCnt = $getUserTotalSellCoin
            ->select(['sum' => $getUserTotalSellCoin->func()->sum('sell_get_amount')])
            ->where(array('seller_user_id' => $userId, 'sell_spend_coin_id' => $coinId, 'status' => 'completed'))
            ->toArray();

        if (!empty($getUserTotalSellCoinCnt)) {
            $getUserTotalSellCoinSum = $getUserTotalSellCoinCnt[0]['sum'];
        }
        return $getUserTotalSellCoinSum;
    }

    public function getTotalUserTransactions($userId)
    {
        $getUserTransactionsCoinSum = 0;
        $this->Transactions = TableRegistry::get("Transactions");
        $getUserTransactionCoin = $this->Transactions->find();
        $getUserTransactionCoinCnt = $getUserTransactionCoin
            ->select(['sum' => $getUserTransactionCoin->func()->sum('coin_amount')])
            ->where(['user_id' => $userId,
                'tx_type' => 'buy_exchange',
                'OR' => [
                    'tx_type' => 'sell_exchange'
                ],])
            ->toArray();

        if (!empty($getUserTransactionCoinCnt)) {
            $getUserTransactionsCoinSum = $getUserTransactionCoinCnt[0]['sum'];
        }
        return $getUserTransactionsCoinSum;
    }

    public function getLocalUserBalance($userId, $cryptoCoinId)
    {
        $getUserTransferredCoinSum = 0;
        $this->Transactions = TableRegistry::get("Transactions");
        $getUserTransferredCoin = $this->Transactions->find();
        $getUserTransferredCoinCnt = $getUserTransferredCoin
            ->select(['sum' => $getUserTransferredCoin->func()->sum('coin_amount')])
            ->where([
                'user_id' => $userId,
                'status' => 'completed',
                'cryptocoin_id' => $cryptoCoinId,
                'tx_type !=' => 'bank_initial_deposit',
                'tx_type !=' => 'save'
            ])
            ->toArray();
        if (!empty($getUserTransferredCoinCnt)) {
            $getUserTransferredCoinSum = $getUserTransferredCoinCnt[0]['sum'];
        }

        return $getUserTransferredCoinSum;
    }

    public function getLocalUsersave($userId, $cryptoCoinId)
    {

        $getUserTransferredCoinSum = 0;
        $this->Transactions = TableRegistry::get("Transactions");
        $getUserTransferredCoin = $this->Transactions->find();
        $getUserTransferredCoinCnt = $getUserTransferredCoin
            ->select(['sum' => $getUserTransferredCoin->func()->sum('coin_amount')])
            ->where(['user_id' => $userId,
                'cryptocoin_id' => $cryptoCoinId, 'tx_type !=' => 'bank_initial_deposit', 'tx_type =' => 'save',
                'status' => 'completed'])
            ->toArray();

        if (!empty($getUserTransferredCoinCnt)) {
            $getUserTransferredCoinSum = $getUserTransferredCoinCnt[0]['sum'];
        }
        return $getUserTransferredCoinSum;
    }

    public function getLocalAllUserBalance($cryptoCoinId)
    {
        $this->Users = TableRegistry::get("Users");
        $adminList = $this->Users->find('list', ['keyField' => 'id',
            'valueField' => 'id',
            'conditions' => ['user_type' => 'A']
        ])->toArray();

        $getUserTransferredCoinSum = 0;
        $this->Transactions = TableRegistry::get("Transactions");
        $getUserTransferredCoin = $this->Transactions->find();
        $getUserTransferredCoinCnt = $getUserTransferredCoin
            ->select(['sum' => $getUserTransferredCoin->func()->sum('coin_amount')])
            ->where(['cryptocoin_id' => $cryptoCoinId, 'tx_type !=' => 'bank_initial_deposit',
                'user_id NOT IN ' => $adminList,
                'status' => 'completed'])
            ->toArray();

        if (!empty($getUserTransferredCoinCnt)) {
            $getUserTransferredCoinSum = $getUserTransferredCoinCnt[0]['sum'];
        }
        return $getUserTransferredCoinSum;
    }

    public function getUserPendingBalance($userId, $cryptoCoinId)
    {

        $getUserTransferredCoinSum = 0;
        $this->Transactions = TableRegistry::get("Transactions");
        $getUserTransferredCoin = $this->Transactions->find();
        $getUserTransferredCoinCnt = $getUserTransferredCoin
            ->select(['sum' => $getUserTransferredCoin->func()->sum('coin_amount')])
            ->where(['user_id' => $userId,
                'cryptocoin_id' => $cryptoCoinId,
                'status' => 'pending',
                'OR' => [['tx_type !=' => 'withdrawal'], ['tx_type !=' => 'bank_initial_deposit']]])
            ->toArray();

        if (!empty($getUserTransferredCoinCnt)) {
            $getUserTransferredCoinSum = $getUserTransferredCoinCnt[0]['sum'];
        }
        return $getUserTransferredCoinSum;
    }

    public function getUserPricipalBalance($userId, $cryptoCoinId)
    {

        $getUserTransferredCoinSum = 0;
        $this->PrincipalWallet = TableRegistry::get("PrincipalWallet");
        $getUserTransferredCoin = $this->PrincipalWallet->find();
        $getUserTransferredCoinCnt = $getUserTransferredCoin
            ->select(['sum' => $getUserTransferredCoin->func()->sum('amount')])
            ->where([
                'user_id' => $userId,
                'status' => 'completed',
                'cryptocoin_id' => $cryptoCoinId
            ])
            ->toArray();

        if (!empty($getUserTransferredCoinCnt)) {
            $getUserTransferredCoinSum = $getUserTransferredCoinCnt[0]['sum'];
        }
        if ($cryptoCoinId == 20 ) {
            $getUserTransferredCoinSums = 0;
            $getUserTransferredCoins = $this->PrincipalWallet->find();
            $getUserTransferredCoinCnts = $getUserTransferredCoins
                ->select(['sum' => $getUserTransferredCoins->func()->sum('amount')])
                ->where(['user_id' => $userId,'status' => 'pending',
                     'type ' => 'bank_initial_withdraw','cryptocoin_id' => $cryptoCoinId
                    ])
                ->toArray();

            if (!empty($getUserTransferredCoinCnts)) {
                $getUserTransferredCoinSums = $getUserTransferredCoinCnts[0]['sum'];
            }
            return $getUserTransferredCoinSum + $getUserTransferredCoinSums;
        } else {
            return $getUserTransferredCoinSum;
        }
    }

    public function getUserPrincipalERC20Balance($userId, $cryptoCoinId)
    {

        $getUserTransferredCoinSum = 0;
        $this->PrincipalWallet = TableRegistry::get("PrincipalWallet");
        $getUserTransferredCoin = $this->PrincipalWallet->find();
        $getUserTransferredCoinCnt = $getUserTransferredCoin
            ->select(['sum' => $getUserTransferredCoin->func()->sum('amount')])
            ->where(['user_id' => $userId,
                'cryptocoin_id' => $cryptoCoinId,
                'remark' => 'erc20_purchase',
                'status' => 'completed'])
            ->toArray();

        if (!empty($getUserTransferredCoinCnt)) {
            $getUserTransferredCoinSum = $getUserTransferredCoinCnt[0]['sum'];
        }

        return $getUserTransferredCoinSum;

    }

    public function getUserPrincipalETokenBalance($userId, $cryptoCoinId)
    {

        $getUserTransferredCoinSum = 0;
        $this->PrincipalWallet = TableRegistry::get("PrincipalWallet");
        $getUserTransferredCoin = $this->PrincipalWallet->find();
        $getUserTransferredCoinCnt = $getUserTransferredCoin
            ->select(['sum' => $getUserTransferredCoin->func()->sum('amount')])
            ->where(['user_id' => $userId,
                'cryptocoin_id' => $cryptoCoinId,
                'remark !=' => 'erc20_purchase',
                'status' => 'completed'])
            ->toArray();

        if (!empty($getUserTransferredCoinCnt)) {
            $getUserTransferredCoinSum = $getUserTransferredCoinCnt[0]['sum'];
        }
        if ($cryptoCoinId == 20) {
            $getUserTransferredCoinSums = 0;
            $getUserTransferredCoins = $this->PrincipalWallet->find();
            $getUserTransferredCoinCnts = $getUserTransferredCoins
                ->select(['sum' => $getUserTransferredCoins->func()->sum('amount')])
                ->where(['user_id' => $userId,
                    'cryptocoin_id' => $cryptoCoinId, 'type ' => 'bank_initial_withdraw',
                    'status' => 'pending'])
                ->toArray();

            if (!empty($getUserTransferredCoinCnts)) {
                $getUserTransferredCoinSums = $getUserTransferredCoinCnts[0]['sum'];
            }
            return $getUserTransferredCoinSum + $getUserTransferredCoinSums;
        } else {
            return $getUserTransferredCoinSum;
        }
    }

    public function getAllUserPricipalBalance($cryptoCoinId)
    {

        $this->Users = TableRegistry::get("Users");
        $adminList = $this->Users->find('list', ['keyField' => 'id',
            'valueField' => 'id',
            'conditions' => ['user_type' => 'A']
        ])->toArray();

        $getUserTransferredCoinSum = 0;
        $this->PrincipalWallet = TableRegistry::get("PrincipalWallet");
        $getUserTransferredCoin = $this->PrincipalWallet->find();
        $getUserTransferredCoinCnt = $getUserTransferredCoin
            ->select(['sum' => $getUserTransferredCoin->func()->sum('amount')])
            ->where(['cryptocoin_id' => $cryptoCoinId,
                'status' => 'completed',
                'user_id NOT IN ' => $adminList,
            ])
            ->toArray();

        if (!empty($getUserTransferredCoinCnt)) {
            $getUserTransferredCoinSum = $getUserTransferredCoinCnt[0]['sum'];
        }
        return $getUserTransferredCoinSum;
    }

    public function getAllTransferHistory($userId)
    {
        $this->PrincipalWallet = TableRegistry::get("PrincipalWallet");
        $getUserTransferredAmount = $this->PrincipalWallet->find();
        return $getUserTransferredAmount
            ->select('*')
            ->where(['user_id' => $userId, 'type' => 'transfer_from_trading_account'])
            ->where(['user_id' => $userId, 'type' => 'transfer_to_trading_account'])
            ->toArray();
    }

    public function getAllTransferHistoryPerDate($userId, $dateFrom, $dateTo)
    {
        $this->PrincipalWallet = TableRegistry::get("PrincipalWallet");
        $getUserTransferredAmount = $this->PrincipalWallet->find();
        return $getUserTransferredAmount
            ->select('all')
            ->where(['user_id' => $userId, 'type' => 'transfer_from_trading_account', 'created_at <= ' => $dateTo, 'created_at >= ' => $dateFrom])
            ->where(['user_id' => $userId, 'type' => 'transfer_to_trading_account', 'created_at <= ' => $dateTo, 'created_at >= ' => $dateFrom])
            ->toArray();
    }

    public function getTransferHistoryPerCoin($userId, $cryptoCoinId, $dateFrom, $dateTo)
    {

        $this->PrincipalWallet = TableRegistry::get("PrincipalWallet");
        $getUserTransferredAmount = $this->PrincipalWallet->find();
        return $getUserTransferredAmount
            ->select('all')
            ->where(['user_id' => $userId, 'cryptocoin_id' => $cryptoCoinId, 'type' => 'transfer_from_trading_account', 'created_at <= ' => $dateTo, 'created_at >= ' => $dateFrom])
            ->where(['user_id' => $userId, 'cryptocoin_id' => $cryptoCoinId, 'type' => 'transfer_to_trading_account', 'created_at <= ' => $dateTo, 'created_at >= ' => $dateFrom])
            ->toArray();
    }

    public function getAllDepositHistory($userId)
    {
        $this->PrincipalWallet = TableRegistry::get("PrincipalWallet");
        $getUserTransferredAmount = $this->PrincipalWallet->find();
        return $getUserTransferredAmount
            ->select('all')
            ->where(['user_id' => $userId, 'type' => 'withdrawal'])
            ->hydrate(false)
            ->toArray();
    }

    public function getAllDepositHistoryPerDate($userId, $dateFrom, $dateTo)
    {
        $this->PrincipalWallet = TableRegistry::get("PrincipalWallet");
        $getUserTransferredAmount = $this->PrincipalWallet->find();
        return $getUserTransferredAmount
            ->select('all')
            ->where(['user_id' => $userId, 'type' => 'withdrawal', 'created_at <= ' => $dateTo, 'created_at >= ' => $dateFrom])
            ->hydrate(false)
            ->toArray();
    }

    public function getDepositHistoryPerCoin($userId, $cryptoCoinId, $dateFrom, $dateTo)
    {

        $this->PrincipalWallet = TableRegistry::get("PrincipalWallet");
        $getUserTransferredAmount = $this->PrincipalWallet->find();
        return $getUserTransferredAmount
            ->select('all')
            ->where(['user_id' => $userId, 'cryptocoin_id' => $cryptoCoinId, 'type' => 'withdrawal', 'created_at <= ' => $dateTo, 'created_at >= ' => $dateFrom])
            ->toArray();
    }

    /* public function getUserReserveBalance($userId,$cryptoCoinId){

        $getUserTransferredCoinSum = 0;
        $this->Transactions = TableRegistry::get("Transactions");
        $getUserTransferredCoin = $this->Transactions->find();
        $getUserTransferredCoinCnt = $getUserTransferredCoin
                                    ->select(['sum' => $getUserTransferredCoin->func()->sum('coin_amount')])
                                    ->where(['user_id'=>$userId,
                                             'cryptocoin_id'=>$cryptoCoinId,
                                             'status'=>'completed',
                                             'remark'=>'reserve for exchange'])
                                    ->toArray();

        if(!empty($getUserTransferredCoinCnt)){
            $getUserTransferredCoinSum = $getUserTransferredCoinCnt[0]['sum'];
        }
        return $getUserTransferredCoinSum;
    } */


    public function getUserSellReserveBalance($userId, $cryptoCoinId)
    {

        $getUserTransferredCoinSum = 0;
        $this->Transactions = TableRegistry::get("Transactions");
        $getUserTransferredCoin = $this->Transactions->find();
        $getUserTransferredCoinCnt = $getUserTransferredCoin
            ->select(['sum' => $getUserTransferredCoin->func()->sum('coin_amount')])
            ->where(['Transactions.user_id' => $userId,
                'Transactions.status' => 'completed',
                'Transactions.cryptocoin_id' => $cryptoCoinId,
                'Transactions.remark' => 'reserve for exchange',
                //'sellReserve.status !='=>'deleted',
                'sellReserve.status in ' => ['pending', 'processing']
            ])
            ->contain(['sellReserve'])
            ->toArray();

        if (!empty($getUserTransferredCoinCnt)) {
            $getUserTransferredCoinSum = $getUserTransferredCoinCnt[0]['sum'];
        }
        return $getUserTransferredCoinSum;
    }

    public function getUserBuyReserveBalance($userId, $cryptoCoinId)
    {

        $getUserTransferredCoinSum = 0;
        $this->Transactions = TableRegistry::get("Transactions");
        $getUserTransferredCoin = $this->Transactions->find();
        $getUserTransferredCoinCnt = $getUserTransferredCoin
            ->select(['sum' => $getUserTransferredCoin->func()->sum('coin_amount')])
            ->where(['Transactions.user_id' => $userId,
                'Transactions.status' => 'completed',
                'Transactions.cryptocoin_id' => $cryptoCoinId,
                'Transactions.remark' => 'reserve for exchange',
                //'buyReserve.status !='=>'deleted'
                'buyReserve.status in ' => ['pending', 'processing']
            ])
            ->contain(['buyReserve'])
            ->toArray();

        if (!empty($getUserTransferredCoinCnt)) {
            $getUserTransferredCoinSum = $getUserTransferredCoinCnt[0]['sum'];
        }
        return $getUserTransferredCoinSum;
    }


    public function getUserTotalWithPendingBalance($userId, $cryptoCoinId)
    {

        $getUserTransferredCoinSum = 0;
        $this->Transactions = TableRegistry::get("Transactions");
        $getUserTransferredCoin = $this->Transactions->find();
        $getUserTransferredCoinCnt = $getUserTransferredCoin
            ->select(['sum' => $getUserTransferredCoin->func()->sum('coin_amount')])
            ->where(['user_id' => $userId,
                'cryptocoin_id' => $cryptoCoinId, 'Transactions.tx_type !=' => 'bank_initial_deposit',])
            ->toArray();

        if (!empty($getUserTransferredCoinCnt)) {
            $getUserTransferredCoinSum = $getUserTransferredCoinCnt[0]['sum'];
        }
        return $getUserTransferredCoinSum;
    }


    public function adminFees($amount, $transactionId, $userId, $cryptocoinId, $exchangeId, $exchangeHistroyId, $tx_type)
    {


        $cudate = date('Y-m-d H:i:s');
        $this->Transactions = TableRegistry::get("Transactions");

        $newTransArr = [];
        $newTransArr['user_id'] = $userId;
        $newTransArr['coin_amount'] = $amount;
        $newTransArr['cryptocoin_id'] = $cryptocoinId;
        $newTransArr['transaction_id'] = $transactionId;
        $newTransArr['exchange_id'] = $exchangeId;
        $newTransArr['exchange_history_id'] = $exchangeHistroyId;
        $newTransArr['tx_type'] = $tx_type;
        $newTransArr['remark'] = 'adminFees';
        $newTransArr['status'] = 'completed';
        $newTransArr['created_at'] = $cudate;
        $newTransArr['updated_at'] = $cudate;

        $addCoinToSellerAccount = $this->Transactions->newEntity();
        $addCoinToSellerAccount = $this->Transactions->patchEntity($addCoinToSellerAccount, $newTransArr);
        $addCoinToSellerAccount = $this->Transactions->save($addCoinToSellerAccount);
    }


    /*
    used for tranfer coin
    */

    public function transferCoinToAddress($password, $fromWalletAddress, $toWalletAddress, $coinAmount, $transferType)
    {


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://206.189.87.153/web3/mywork/" . $transferType . ".php",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"toWalletAddress\"\r\n\r\n" . $toWalletAddress . "\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"coinAmount\"\r\n\r\n" . $coinAmount . "\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
                "postman-token: df7a062e-5e98-b234-cc17-26a359fbec8d"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }

        //extract data from the post
        //set POST variables
        $url = 'http://206.189.87.153/web3/mywork/' . $transferType . '.php';

        $fields = array(
            'password' => urlencode($password),
            'fromWalletAddress' => urlencode($fromWalletAddress),
            'toWalletAddress' => urlencode($toWalletAddress),
            'coinAmount' => urlencode($coinAmount)
        );


        $curl = curl_init();

        $fields_string = '';
        //url-ify the data for the POST
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        rtrim($fields_string, '&');

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $fields_string,
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
                "postman-token: 0d46cad5-0611-7b13-3b70-c6e9e8faab10"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
        die;
    }


    public function generate_string($strength = 12)
    {
        $input = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $input_length = strlen($input);
        $random_string = '';
        for ($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }

        return $random_string;
    }

    public function generateReferralCode()
    {
        $getId = $this->generate_string();
        $this->Users = TableRegistry::get("Users");
        $getUserData = $this->Users->find();
        $getUserTransferredCoinCnt = $getUserData
            ->select(['id'])
            ->where(['referral_code' => $getId])
            ->first();
        if (!empty($getUserTransferredCoinCnt)) {
            return $this->generateReferralCode();
        }
        return $getId;
    }

    public function getUniqueId($userId)
    {
        $getId = uniqid($userId . sha1(md5(time())));
        $this->Transactions = TableRegistry::get("Transactions");
        $getUserTransferredCoin = $this->Transactions->find();
        $getUserTransferredCoinCnt = $getUserTransferredCoin
            ->select(['id'])
            ->where(['withdrawal_tx_id' => $getId, 'tx_type' => 'withdrawal'])
            ->toArray();
        if (!empty($getUserTransferredCoinCnt)) {
            return $this->getUniqueId($userId);
        }
        return $getId;
    }

    public function getUniqueTxId()
    {
        $getId = "0x" . uniqid(sha1(md5(time() . time())));
        $this->Transactions = TableRegistry::get("Transactions");
        $getUserTransferredCoin = $this->Transactions->find();
        $getUserTransferredCoinCnt = $getUserTransferredCoin
            ->select(['id'])
            ->where(['tx_id' => $getId])
            ->toArray();
        if (!empty($getUserTransferredCoinCnt)) {
            return $this->getUniqueId();
        }
        return $getId;
    }

    public function callRamTrexApi($hashdata = null)
    {

        $urltest = "https://www.ramtrex.com/Request/Withdrowal.aspx?hashcode=" . $hashdata;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urltest);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public function getAdmcTxDetail($txId = null)
    {
        if (empty($txId)) {
            return '';
        }
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_PORT => "44155",
            CURLOPT_URL => "http://139.162.23.51:44155/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"jsonrpc\":\"1.0\",\"method\":\"gettransaction\",\"params\":[\"" . $txId . "\"]}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic aW50VXNlQ29kZXI6V3JVeUhuNkdyZkdiNzg=",
                "cache-control: no-cache",
                "content-type: application/json",
                "postman-token: b177ddf8-7e00-7918-a082-08abe77d101b"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        return $response;
    }


    public function admcWithdrawal($toAddress, $amt)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_PORT => "20155",
            CURLOPT_URL => "http://178.128.223.236:20155/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"jsonrpc\":\"1.0\",\"method\":\"sendtoaddress\",\"params\":[\"" . $toAddress . "\"," . $amt . "]}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic cnBjcmFtdXNyYW1iOmExMkVFRW9wM1RyZWQzNDN3UWU0NTZiMXo3OGV2YjQ0NA==",
                "cache-control: no-cache",
                "content-type: application/json",
                "postman-token: 2eff3f37-e680-dd4b-48a6-490428d215f5"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        return $response;
        /* if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          echo $response;
        } */
    }


    public function admcCheckAddressValid($address)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_PORT => "20155",
            CURLOPT_URL => "http://178.128.223.236:20155/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"jsonrpc\":\"1.0\",\"method\":\"validateaddress\",\"params\":[\"" . $address . "\"]}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic cnBjcmFtdXNyYW1iOmExMkVFRW9wM1RyZWQzNDN3UWU0NTZiMXo3OGV2YjQ0NA==",
                "cache-control: no-cache",
                "content-type: application/json",
                "postman-token: cc68a538-85fc-6a6c-0d06-87ba5b94e78d"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 0;
        } else {
            $decodeResult = json_decode($response, true);
            if ($decodeResult['result']['isvalid'] == true) {
                return 1;;
            } else {
                return 0;
            }
        }
        return 0;
    }


    public function getramcurrentprice()
    {

        $this->Cryptocoin = TableRegistry::get("Cryptocoin");
        $this->ExchangeHistory = TableRegistry::get("ExchangeHistory");
        $cudate = date('Y-m-d');
        $firstCoinId = 2;
        $secondCoinId = 3;
        $getPrice = 0;


        $getFirstCoinDetail = $this->Cryptocoin->find('all', ['conditions' => ['id' => $firstCoinId]])->hydrate(false)->first();
        $baseCoinPriceInUsd = $getFirstCoinDetail['usd_price'];


        $currentPrice = $this->ExchangeHistory->find('all', ['conditions' => ['OR' => [
            ['get_cryptocoin_id' => $secondCoinId,
                'spend_cryptocoin_id' => $firstCoinId],
            ['spend_cryptocoin_id' => $secondCoinId,
                'get_cryptocoin_id' => $firstCoinId]
        ]
        ], 'limit' => 1,
            'order' => ['id' => 'desc']
        ])
            ->hydrate(false)
            ->first();

        if (!empty($currentPrice)) {
            $getPrice = $currentPrice['get_per_price'];
        }
        $returnArr = [];
        $returnArr['currentprice_eth'] = $getPrice;
        $returnArr['currentprice_usd'] = $getPrice * $baseCoinPriceInUsd;
        return $returnArr;
    }


    public function checkpair($coin_pair)
    {
        $this->Coinpair = TableRegistry::get('Coinpair');
        $this->Cryptocoin = TableRegistry::get('Cryptocoin');
        $this->ExchangeHistory = TableRegistry::get('ExchangeHistory');
        $this->BuyExchange = TableRegistry::get('BuyExchange');
        $this->SellExchange = TableRegistry::get('SellExchange');

        $returnArr = [];
        $explodeCoinPair = explode("_", $coin_pair);
        $firstCoinName = strtoupper($explodeCoinPair[0]);
        $secondCoinName = strtoupper($explodeCoinPair[1]);

        // check for first coin details
        $getFirstCoinDetail = $this->Cryptocoin->find('all', ['conditions' => ['short_name' => $firstCoinName]])->hydrate(false)->first();
        if (empty($getFirstCoinDetail)) {
            $returnArr['success'] = false;
            $returnArr['error'] = true;
            $returnArr['message'] = "Invalid Coin";
            $returnArr['data'] = "";
            echo json_encode($returnArr);
            die;
        }

        // check for second coin details
        $getSecondCoinDetail = $this->Cryptocoin->find('all', ['conditions' => ['short_name' => $secondCoinName]])->hydrate(false)->first();
        if (empty($getSecondCoinDetail)) {
            $returnArr['success'] = false;
            $returnArr['error'] = true;
            $returnArr['message'] = "Invalid Coin";
            $returnArr['data'] = "";
            echo json_encode($returnArr);
            die;
        }

        $returnArr['firstCoinId'] = $getFirstCoinDetail['id'];
        $returnArr['secondCoinId'] = $getSecondCoinDetail['id'];

        return $returnArr;


    }


    public function getIntrAddress($getEmail)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://139.162.23.51/api_list.php",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"secret_code\"\r\n\r\nMD(58UB@gsaa@@!=@@!!!9&@ds54g4e\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"email\"\r\n\r\n" . $getEmail . "\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"api_name\"\r\n\r\nget_new_address\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
                "postman-token: 233238aa-463f-e178-519d-2135d7688bff"
            ),
        ));

        $response = curl_exec($curl);

        $err = curl_error($curl);

        curl_close($curl);
        $decodeJson = json_decode($response, true);

        return $decodeJson['data'];
        /* if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          echo $response;
        } */
    }

    public function getIntrAddressByAccount($getEmail)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://139.162.23.51/api_list.php",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"secret_code\"\r\n\r\nMD(58UB@gsaa@@!=@@!!!9&@ds54g4e\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"email\"\r\n\r\n" . $getEmail . "\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"api_name\"\r\n\r\nget_address_by_account\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
                "postman-token: 233238aa-463f-e178-519d-2135d7688bff"
            ),
        ));

        $response = curl_exec($curl);

        $err = curl_error($curl);

        curl_close($curl);
        $decodeJson = json_decode($response, true);

        return $decodeJson['data'];
        /* if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          echo $response;
        } */
    }


    public function sso_action($postData = array(), $actionName = "")
    {
        //$postData = json_encode($postData);
        $postvars = '';
        foreach ($postData as $key => $value) {
            $postvars .= $key . "=" . $value . "&";
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sosmed.mbx.co.id/sso/" . $actionName . ".php",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postvars,
            /* CURLOPT_HTTPHEADER => array(
              "cache-control: no-cache",
              "content-type: multipart/form-data",
              "postman-token: 0704dcdf-0d01-e5d2-dcff-c8f409e7d056"
            ), */
        ));

        $response = curl_exec($curl);
        //$err = curl_error($curl);

        curl_close($curl);
        return $response;
        /* if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          echo $response;
        } */
    }


    /*
    Google Authenticator start

    */


    /**
     * Create new secret.
     * 16 characters, randomly chosen from the allowed base32 characters.
     *
     * @param int $secretLength
     *
     * @return string
     */
    public function createSecret($secretLength = 16)
    {
        $validChars = $this->_getBase32LookupTable();

        // Valid secret lengths are 80 to 640 bits
        if ($secretLength < 16 || $secretLength > 128) {
            throw new Exception('Bad secret length');
        }
        $secret = '';
        $rnd = false;
        if (function_exists('random_bytes')) {
            $rnd = random_bytes($secretLength);
        } elseif (function_exists('mcrypt_create_iv')) {
            $rnd = mcrypt_create_iv($secretLength, MCRYPT_DEV_URANDOM);
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $rnd = openssl_random_pseudo_bytes($secretLength, $cryptoStrong);
            if (!$cryptoStrong) {
                $rnd = false;
            }
        }
        if ($rnd !== false) {
            for ($i = 0; $i < $secretLength; ++$i) {
                $secret .= $validChars[ord($rnd[$i]) & 31];
            }
        } else {
            throw new Exception('No source of secure random');
        }

        return $secret;
    }

    /**
     * Calculate the code, with given secret and point in time.
     *
     * @param string $secret
     * @param int|null $timeSlice
     *
     * @return string
     */
    public function getCode($secret, $timeSlice = null)
    {
        if ($timeSlice === null) {
            $timeSlice = floor(time() / 30);
        }

        $secretkey = $this->_base32Decode($secret);

        // Pack time into binary string
        $time = chr(0) . chr(0) . chr(0) . chr(0) . pack('N*', $timeSlice);
        // Hash it with users secret key
        $hm = hash_hmac('SHA1', $time, $secretkey, true);
        // Use last nipple of result as index/offset
        $offset = ord(substr($hm, -1)) & 0x0F;
        // grab 4 bytes of the result
        $hashpart = substr($hm, $offset, 4);

        // Unpak binary value
        $value = unpack('N', $hashpart);
        $value = $value[1];
        // Only 32 bits
        $value = $value & 0x7FFFFFFF;

        $modulo = pow(10, $this->_codeLength);

        return str_pad($value % $modulo, $this->_codeLength, '0', STR_PAD_LEFT);
    }

    /**
     * Get QR-Code URL for image, from google charts.
     *
     * @param string $name
     * @param string $secret
     * @param string $title
     * @param array $params
     *
     * @return string
     */
    public function getQRCodeGoogleUrl($name, $secret, $title = null, $params = array())
    {
        $width = !empty($params['width']) && (int)$params['width'] > 0 ? (int)$params['width'] : 200;
        $height = !empty($params['height']) && (int)$params['height'] > 0 ? (int)$params['height'] : 200;
        $level = !empty($params['level']) && array_search($params['level'], array('L', 'M', 'Q', 'H')) !== false ? $params['level'] : 'M';

        $urlencoded = urlencode('otpauth://totp/' . $name . '?secret=' . $secret . '');
        if (isset($title)) {
            $urlencoded .= urlencode('&issuer=' . urlencode($title));
        }

        return "https://api.qrserver.com/v1/create-qr-code/?data=$urlencoded&size=${width}x${height}&ecc=$level";
    }

    /**
     * Check if the code is correct. This will accept codes starting from $discrepancy*30sec ago to $discrepancy*30sec from now.
     *
     * @param string $secret
     * @param string $code
     * @param int $discrepancy This is the allowed time drift in 30 second units (8 means 4 minutes before or after)
     * @param int|null $currentTimeSlice time slice if we want use other that time()
     *
     * @return bool
     */
    public function verifyCode($secret, $code, $discrepancy = 1, $currentTimeSlice = null)
    {
        if ($currentTimeSlice === null) {
            $currentTimeSlice = floor(time() / 30);
        }

        if (strlen($code) != 6) {
            return false;
        }

        for ($i = -$discrepancy; $i <= $discrepancy; ++$i) {
            $calculatedCode = $this->getCode($secret, $currentTimeSlice + $i);
            if ($this->timingSafeEquals($calculatedCode, $code)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Set the code length, should be >=6.
     *
     * @param int $length
     *
     * @return PHPGangsta_GoogleAuthenticator
     */
    public function setCodeLength($length)
    {
        $this->_codeLength = $length;

        return $this;
    }

    /**
     * Helper class to decode base32.
     *
     * @param $secret
     *
     * @return bool|string
     */
    protected function _base32Decode($secret)
    {
        if (empty($secret)) {
            return '';
        }

        $base32chars = $this->_getBase32LookupTable();
        $base32charsFlipped = array_flip($base32chars);

        $paddingCharCount = substr_count($secret, $base32chars[32]);
        $allowedValues = array(6, 4, 3, 1, 0);
        if (!in_array($paddingCharCount, $allowedValues)) {
            return false;
        }
        for ($i = 0; $i < 4; ++$i) {
            if ($paddingCharCount == $allowedValues[$i] &&
                substr($secret, -($allowedValues[$i])) != str_repeat($base32chars[32], $allowedValues[$i])) {
                return false;
            }
        }
        $secret = str_replace('=', '', $secret);
        $secret = str_split($secret);
        $binaryString = '';
        for ($i = 0; $i < count($secret); $i = $i + 8) {
            $x = '';
            if (!in_array($secret[$i], $base32chars)) {
                return false;
            }
            for ($j = 0; $j < 8; ++$j) {
                $x .= str_pad(base_convert(@$base32charsFlipped[@$secret[$i + $j]], 10, 2), 5, '0', STR_PAD_LEFT);
            }
            $eightBits = str_split($x, 8);
            for ($z = 0; $z < count($eightBits); ++$z) {
                $binaryString .= (($y = chr(base_convert($eightBits[$z], 2, 10))) || ord($y) == 48) ? $y : '';
            }
        }

        return $binaryString;
    }

    /**
     * Get array with all 32 characters for decoding from/encoding to base32.
     *
     * @return array
     */
    protected function _getBase32LookupTable()
    {
        return array(
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', //  7
            'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', // 15
            'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', // 23
            'Y', 'Z', '2', '3', '4', '5', '6', '7', // 31
            '=',  // padding char
        );
    }

    /**
     * A timing safe equals comparison
     * more info here: http://blog.ircmaxell.com/2014/11/its-all-about-time.html.
     *
     * @param string $safeString The internal (safe) value to be checked
     * @param string $userString The user submitted (unsafe) value
     *
     * @return bool True if the two strings are identical
     */
    private function timingSafeEquals($safeString, $userString)
    {
        if (function_exists('hash_equals')) {
            return hash_equals($safeString, $userString);
        }
        $safeLen = strlen($safeString);
        $userLen = strlen($userString);

        if ($userLen != $safeLen) {
            return false;
        }

        $result = 0;

        for ($i = 0; $i < $userLen; ++$i) {
            $result |= (ord($safeString[$i]) ^ ord($userString[$i]));
        }

        // They are only identical strings if $result is exactly 0...
        return $result === 0;
    }

    /*
    Google Authenticator end

    */


    public function ntrCheckAddressValid($address)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_PORT => "44155",
            CURLOPT_URL => "http://139.162.23.51:44155/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"jsonrpc\":\"1.0\",\"method\":\"validateaddress\",\"params\":[\"" . $address . "\"]}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic aW50VXNlQ29kZXI6V3JVeUhuNkdyZkdiNzg=",
                "cache-control: no-cache",
                "content-type: application/json",
                "postman-token: cc68a538-85fc-6a6c-0d06-87ba5b94e78d"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 0;
        } else {
            $decodeResult = json_decode($response, true);
            if ($decodeResult['result']['isvalid'] == true) {
                return 1;;
            } else {
                return 0;
            }
        }
        return 0;
    }


    public function ntrWithdrawal($fromAccount, $toAddress, $amt)
    {
        $curl = curl_init();
        if (empty($fromAccount)) {
            curl_setopt_array($curl, array(
                CURLOPT_PORT => "44155",
                CURLOPT_URL => "http://139.162.23.51:44155/",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "{\"jsonrpc\": \"1.0\", \"id\":\"curltest\", \"method\": \"sendfrom\", \"params\": [\"\",\"" . $toAddress . "\"," . $amt . "] }",
                CURLOPT_HTTPHEADER => array(
                    "authorization: Basic aW50VXNlQ29kZXI6V3JVeUhuNkdyZkdiNzg=",
                    "cache-control: no-cache",
                    "content-type: application/json",
                    "postman-token: 2eff3f37-e680-dd4b-48a6-490428d215f5"
                ),
            ));

        } else {
            curl_setopt_array($curl, array(
                CURLOPT_PORT => "44155",
                CURLOPT_URL => "http://139.162.23.51:44155/",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                // CURLOPT_POSTFIELDS => "{\"jsonrpc\":\"1.0\",\"method\":\"sendfrom\",\"params\":[\"".$toAddress."\",".$amt."]}",
                "{\"jsonrpc\": \"1.0\", \"id\":\"curltest\", \"method\": \"sendfrom\", \"params\": [\"" . $fromAccount . "\",\"" . $toAddress . "\"," . $amt . "] }",
                CURLOPT_HTTPHEADER => array(
                    "authorization: Basic aW50VXNlQ29kZXI6V3JVeUhuNkdyZkdiNzg=",
                    "cache-control: no-cache",
                    "content-type: application/json",
                    "postman-token: 2eff3f37-e680-dd4b-48a6-490428d215f5"
                ),
            ));
        }

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);


        if ($err) {
            return $err;
        } else {
            return $response;
        }
    }

    public function getNtrChainBalance($email)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_PORT => "44155",
            CURLOPT_URL => "http://139.162.23.51:44155/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"jsonrpc\":\"1.0\",\"method\":\"getbalance\",\"params\":[\"" . $email . "\"]}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic aW50VXNlQ29kZXI6V3JVeUhuNkdyZkdiNzg=",
                "cache-control: no-cache",
                "content-type: application/json",
                "postman-token: 2eff3f37-e680-dd4b-48a6-490428d215f5"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        return $response;
        /* if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          echo $response;
        } */
    }

    public function moveNtrToAdminBalance($fromAccount, $amount)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_PORT => "44155",
            CURLOPT_URL => "http://139.162.23.51:44155/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            //CURLOPT_POSTFIELDS => "{\"jsonrpc\":\"1.0\",\"method\":\"getbalance\",\"params\":[\"".$email."\"]}",
            CURLOPT_POSTFIELDS => "{\"jsonrpc\": \"1.0\", \"id\":\"curltest\", \"method\": \"move\", \"params\": [\"" . $fromAccount . "\",\"\"," . $amount . "] }",
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic aW50VXNlQ29kZXI6V3JVeUhuNkdyZkdiNzg=",
                "cache-control: no-cache",
                "content-type: application/json",
                "postman-token: 2eff3f37-e680-dd4b-48a6-490428d215f5"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        return $response;
        /* if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          echo $response;
        } */
    }


    public function getBtcTxDetailFronNode($txId = null)
    {
        if (empty($txId)) {
            return '';
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_PORT => $this->btcPort,
            CURLOPT_URL => $this->btcUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"jsonrpc\":\"1.0\",\"id\":\"curltest\",\"method\":\"gettransaction\",\"params\":[\"" . $txId . "\"]}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic Y3N0bWNid2lldGJla29ydGVjaGJpcGFqOiFBWHpLdzhmZkBocyRoTHQ5QFpUSWImZ0JEWVpHISUqcVZtOXRAMnVXaU8=",
                "cache-control: no-cache",
                "content-type: application/json",
                "postman-token: b177ddf8-7e00-7918-a082-08abe77d101b"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return '';
        } else {
            return $response;
        }


    }


    public function createBtcAddress($email = null)
    {
        if (empty($email)) {
            return '';
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_PORT => $this->btcPort,
            CURLOPT_URL => $this->btcUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"jsonrpc\":\"1.0\",\"id\":\"curltest\",\"method\":\"getnewaddress\",\"params\":[\"" . $email . "\"]}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic Y3N0bWNid2lldGJla29ydGVjaGJpcGFqOiFBWHpLdzhmZkBocyRoTHQ5QFpUSWImZ0JEWVpHISUqcVZtOXRAMnVXaU8=",
                "cache-control: no-cache",
                "content-type: application/json",
                "postman-token: b177ddf8-7e00-7918-a082-08abe77d101b"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return '';
        } else {
            return $response;
        }


    }


    public function validateBtcAddress($addr = null)
    {
        if (empty($addr)) {
            return '';
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_PORT => $this->btcPort,
            CURLOPT_URL => $this->btcUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"jsonrpc\":\"1.0\",\"id\":\"curltest\",\"method\":\"validateaddress\",\"params\":[\"" . $addr . "\"]}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic Y3N0bWNid2lldGJla29ydGVjaGJpcGFqOiFBWHpLdzhmZkBocyRoTHQ5QFpUSWImZ0JEWVpHISUqcVZtOXRAMnVXaU8=",
                "cache-control: no-cache",
                "content-type: application/json",
                "postman-token: b177ddf8-7e00-7918-a082-08abe77d101b"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return '';
        } else {
            return $response;
        }


    }

    public function createEthAddress()
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "15.164.225.146:3000/get_address",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;


    }




    // IP 확인
    // return : IP Address
    function new_getUserIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    // ipinfo.io
    // return : 국가코드(KR, DE, ...)
    function new_ipinfo_ip_chk($key)
    { // 수량 체크 테스트용. whois 대신 사용 가능한지 check (2020.05.14, YMJ)
        $access_token = $this->getIpinfoToken();
        $ip_address = $this->new_getUserIpAddr();
        $country = '';
        $url = "https://ipinfo.io/{$ip_address}/country?token=" . $access_token;
        $country = @file_get_contents($url);

        return $country; // 국내 : KR
    }

    /* ipinfo token */
    public function getIpinfoToken()
    {
        return '52d3bade00ca48';  // 2021-07-13 이충현 1dc844ce917bf3 => 2f11973e5039d5 으로 수정  // 2021-07-22 이충현 2f11973e5039d5 => 4f91a06d11ac51 으로 수정, 52d3bade00ca48
        // 21-09-13 이충현 1dc844ce917bf3 -> 52d3bade00ca48 으로 수정
    }

    public function sendSmsText($to, $sendText)
    {

        $sendArr = ["to" => $to, "messages" => [["channel" => "sms", "sender" => "CoinIBT", "text" => $sendText]]];
        $encodeData = json_encode($sendArr);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.messente.com/v1/omnimessage",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            /* CURLOPT_POSTFIELDS =>"{\r\n    \"to\": \"+919782632174\",\r\n    \"messages\": [\r\n      {\r\n        \"channel\": \"sms\",\r\n        \"sender\": \"CyberTChain\",\r\n        \"text\": \"hello sms\"\r\n      }\r\n    ]\r\n  }", */
            CURLOPT_POSTFIELDS => $encodeData,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic MThiODFlMDdkMTg0MjUyMTBkYjc5MjVmMzliM2ViN2M6MzFhMDZmYjk2MTk4ODQzNDIyNjM1NzE2YjExNGEzMmE=",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }

    public function sendSms($to, $code)
    {

        $sendText = "Coin IBT authentication code is : " . $code;
        $sendArr = ["to" => $to, "messages" => [["channel" => "sms", "sender" => "CoinIBT", "text" => $sendText]]];
        $encodeData = json_encode($sendArr);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.messente.com/v1/omnimessage",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            /* CURLOPT_POSTFIELDS =>"{\r\n    \"to\": \"+919782632174\",\r\n    \"messages\": [\r\n      {\r\n        \"channel\": \"sms\",\r\n        \"sender\": \"CyberTChain\",\r\n        \"text\": \"hello sms\"\r\n      }\r\n    ]\r\n  }", */
            CURLOPT_POSTFIELDS => $encodeData,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic MThiODFlMDdkMTg0MjUyMTBkYjc5MjVmMzliM2ViN2M6MzFhMDZmYjk2MTk4ODQzNDIyNjM1NzE2YjExNGEzMmE=",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }

    public function getCurrentPrice($firstCoinId, $secondCoinId)
    {
        $this->ExchangeHistory = TableRegistry::get("ExchangeHistory");
        $this->Cryptocoin = TableRegistry::get("Cryptocoin");
        $this->Coinpair = TableRegistry::get("Coinpair");
        $getCoinPairSingle = $this->Coinpair->find("all", ["conditions" => ['OR' => [
            ['coin_first_id' => $secondCoinId,
                'coin_second_id' => $firstCoinId,
                'binance_price' => 'Y',
            ],
            ['coin_second_id' => $secondCoinId,
                'coin_first_id' => $firstCoinId,
                'binance_price' => 'Y',
            ]
        ]
        ]
        ])
            ->hydrate(false)
            ->first();
        if ($getCoinPairSingle['binance_price'] == "Y") {
            return $price = $getCoinPairSingle['pair_price'];
        } else {
            $currentPrice = $this->ExchangeHistory->find('all', ['conditions' => ['OR' => [
                ['get_cryptocoin_id' => $secondCoinId,
                    'spend_cryptocoin_id' => $firstCoinId],
                ['spend_cryptocoin_id' => $secondCoinId,
                    'get_cryptocoin_id' => $firstCoinId]
            ]
            ],
                'limit' => 1,
                'order' => ['id' => 'desc']
            ])
                ->hydrate(false)
                ->first();

            if (empty($currentPrice)) {
                $currentPrice = $this->Cryptocoin->find('all', ['conditions' => ['id' => $firstCoinId], 'fields' => ['get_per_price' => 'usd_price']])->hydrate(false)->first();
                return $currentPrice['get_per_price'];
            } else {
                return $currentPrice['get_per_price'];
            }

        }


    }


    public function getAdninFee($feeType = "buy_sell_fee")
    {

        $this->Settings = TableRegistry::get("Settings");

        $getFee = $this->Settings->find('all', ['conditions' => ['module_name' => $feeType],
            'limit' => 1,
            'order' => ['id' => 'desc']
        ])
            ->hydrate(false)
            ->first();

        if (!empty($getFee)) {
            return $getFee['value'];
        } else {
            return 0.8;
        }


    }


    public function getBuySellFee($coinPairId)
    {

        $this->Settings = TableRegistry::get("Settings");
        $this->UserBuySellFee = TableRegistry::get("UserBuySellFee");
        $getFee = $this->UserBuySellFee->find('all', ['conditions' => ['coinpair_id' => $coinPairId],
            'limit' => 1,
            'order' => ['id' => 'desc']
        ])
            ->hydrate(false)
            ->first();
        if (!empty($getFee)) {
            return $getFee['buy_sell_fee'];
        } else {
            $getFee = $this->Settings->find('all', ['conditions' => ['module_name' => "buy_sell_fee"],
                'limit' => 1,
                'order' => ['id' => 'desc']
            ])
                ->hydrate(false)
                ->first();

            if (!empty($getFee)) {
                return $getFee['value'];
            } else {
                return 0.5;
            }
        }


    }

    public function getUserWithdrawalFee($coinId, $userId)
    {
        $this->Settings = TableRegistry::get("Settings");
        $this->NumberThreeSetting = TableRegistry::get("NumberThreeSetting");
        $this->NumberFourSetting = TableRegistry::get("NumberFourSetting");
        $adminWithdrawalFee = $this->Settings->find("all", ["conditions" => ["id" => 17]])->hydrate(false)->first();
        $withdrawalFeePercent = $adminWithdrawalFee["value"];

        $getDataNew = $this->NumberFourSetting->find("all", ["conditions" => [
            "user_id" => 0,
            "cryptocoin_id" => $coinId
        ],
            "order" => ["id" => "DESC"]
        ])
            ->hydrate(false)
            ->first();
        if (!empty($getDataNew) && !empty($getDataNew["withdrawal_fee"])) {

            $withdrawalFeePercent = $getDataNew["withdrawal_fee"];
        }


        $getData = $this->NumberThreeSetting->find("all", ["conditions" => [
            "user_id" => $userId,
            "cryptocoin_id" => $coinId
        ],
            "order" => ["id" => "DESC"]
        ])
            ->hydrate(false)
            ->first();
        if (!empty($getData)) {

            $days = $getData["days"];
            $created = strtotime(date("Y-m-d H:i:s", strtotime($getData["created"])) . '+ ' . $days . ' days');

            $timediff = $created - time();
            if ($timediff > 0) {
                $withdrawalFeePercent = $getData["user_fee"];
            }
        }
        return $withdrawalFeePercent;
    }


    public function getUserBuySellFee($coinpairId, $userId)
    {
        $this->Settings = TableRegistry::get("Settings");
        $this->NumberThreeSetting = TableRegistry::get("NumberThreeSetting");
        $this->UserBuySellFee = TableRegistry::get("UserBuySellFee");
        $adminWithdrawalFee = $this->Settings->find("all", ["conditions" => ["id" => 20]])->hydrate(false)->first();
        $buySellFeePercent = $adminWithdrawalFee["value"];

        $getDataNew = $this->UserBuySellFee->find("all", ["conditions" => [
            "user_id" => $userId,
            "coinpair_id" => $coinpairId
        ],
            "order" => ["id" => "DESC"]
        ])
            ->hydrate(false)
            ->first();
        if (!empty($getDataNew) && !empty($getDataNew["buy_sell_fee"])) {

            $buySellFeePercent = $getDataNew["buy_sell_fee"];
        }


        /* $getData =  $this->NumberThreeSetting->find("all",["conditions"=>[
                                                                        "user_id"=>$userId,
                                                                        "cryptocoin_id"=>$coinId
                                                                     ],
                                                        "order"=>["id"=>"DESC"]
                                                       ])
                                                       ->hydrate(false)
                                                       ->first();
        if(!empty($getData)){

            $days = $getData["days"];
            $created = strtotime(date("Y-m-d H:i:s",strtotime($getData["created"])).'+ '.$days.' days');

            $timediff = $created-time();
            if($timediff>0){
                $buySellFeePercent = $getData["user_fee"];
            }
        } */
        return $buySellFeePercent;
    }

    public function getTotalWithdrawAmount($userId, $cryptoCoinId)
    {

        $getUserTransferredCoinSum = 0;
        $this->PrincipalWallet = TableRegistry::get("PrincipalWallet");
        $getUserTransferredCoin = $this->PrincipalWallet->find();
        $getUserTransferredCoinCnt = $getUserTransferredCoin
            ->select(['sum' => $getUserTransferredCoin->func()->sum('amount')])
            ->where(['user_id' => $userId,
                'cryptocoin_id' => $cryptoCoinId, 'type ' => 'bank_initial_withdraw',
                'OR' => [['status' => 'completed'], ['status' => 'pending']]])
            ->toArray();

        if (!empty($getUserTransferredCoinCnt)) {
            $getUserTransferredCoinSum = $getUserTransferredCoinCnt[0]['sum'];
        }
        return $getUserTransferredCoinSum;
    }

    public function getUserInvestmentAmount($userId)
    { // investment application amount
        $this->DepositApplicationList = TableRegistry::get("DepositApplicationList");
        $query = $this->DepositApplicationList->find()->select(['total' => 'sum(quantity)']);
        $total_amount = $query->where(['user_id' => $userId, 'status != ' => 'C'])->first();
        if (!empty($total_amount)) {
            return $total_amount->total;
        }
        return 0;
    }

    public function getUserInvestmentWalletAmount($userId)
    { // investment profits wallet
        $this->DepositApplicationWallet = TableRegistry::get("DepositApplicationWallet");
        $query = $this->DepositApplicationWallet->find()->select(['amount']);
        $total_amount = $query->where(['user_id' => $userId])->first();
        if (!empty($total_amount)) {
            return $total_amount->amount;
        }
        return 0;
    }

    public function getAllUserInvestmentAmount()
    {
        $this->DepositApplicationList = TableRegistry::get("DepositApplicationList");
        $query = $this->DepositApplicationList->find()->select(['total' => 'sum(quantity)']);
        $total_amount = $query->where(['status != ' => 'C'])->first();
        if (!empty($total_amount)) {
            return $total_amount->total;
        }
        return 0;
    }

    public function getAllUserInvestmentWalletAmount()
    {
        $this->DepositApplicationWallet = TableRegistry::get("DepositApplicationWallet");
        $query = $this->DepositApplicationWallet->find()->select(['amount']);
        $total_amount = $query->first();
        if (!empty($total_amount)) {
            return $total_amount->amount;
        }
        return 0;
    }

    /* Hassam Multisig - 210820 */
    public function getUserTotalWithdrawnTrading($userId)
    { //To get the total withdrawal amount from the trading account
        $getUserTotalWithdrawnSum = 0;
        $this->Transactions = TableRegistry::get("Transactions");
        $getUserTotalWithdrawn = $this->Transactions->find();
        $getUserTotalWithdrawnCnt = $getUserTotalWithdrawn
            ->select(['sum' => $getUserTotalWithdrawn->func()->sum('coin_amount')])
            ->where(array('user_id' => $userId, 'OR' => [['tx_type' => 'bank_initial_withdraw'], ['tx_type' => 'withdrawal']], 'status' => 'completed', 'created_at >=' =>
                date('Y-m-d H:i:s', strtotime('2021-07-15 12:00:00'))))
            ->toArray();
        if (!empty($getUserTotalWithdrawnCnt)) {
            $getUserTotalWithdrawnSum = $getUserTotalWithdrawnCnt[0]['sum'];
        }
        return $getUserTotalWithdrawnSum;
    }

    /* Hassam Multisig - 210820 */
    public function getUserTotalWithdrawnKRWTrading($userId)
    { //to get the total KRW withdrawal amount from the trading account
        $getUserTotalWithdrawnSum = 0;
        $this->Transactions = TableRegistry::get("Transactions");
        $getUserTotalWithdrawn = $this->Transactions->find();
        $getUserTotalWithdrawnCnt = $getUserTotalWithdrawn
            ->select(['sum' => $getUserTotalWithdrawn->func()->sum('coin_amount')])
            ->where(array('user_id' => $userId, 'tx_type' => 'bank_initial_withdraw', 'status' => 'completed', 'created_at >=' => date('Y-m-d H:i:s',
                strtotime('2021-07-15 12:00:00'))))
            ->toArray();
        if (!empty($getUserTotalWithdrawnCnt)) {
            $getUserTotalWithdrawnSum = $getUserTotalWithdrawnCnt[0]['sum'];
        }
        return $getUserTotalWithdrawnSum;
    }

    /* Hassam Multisig - 210820 */
    public function getUserTotalWithdrawnTradingWithoutFees($userId)
    { //to calculate the total amount of the withdrawals without the fess. This is the amount that the user had requested for withdrawal
        $getUserTotalWithdrawnSum = 0;
        $this->Transactions = TableRegistry::get("Transactions");
        $getUserTotalWithdrawn = $this->Transactions->find();
        $getUserTotalWithdrawnCnt = $getUserTotalWithdrawn
            ->select(['sum' => $getUserTotalWithdrawn->func()->sum('amount')])
            ->where(array('user_id' => $userId, 'OR' => [['tx_type' => 'bank_initial_withdraw'], ['tx_type' => 'withdrawal']], 'status' => 'completed'))
            ->toArray();
        if (!empty($getUserTotalWithdrawnCnt)) {
            $getUserTotalWithdrawnSum = $getUserTotalWithdrawnCnt[0]['sum'];
        }
        return $getUserTotalWithdrawnSum;
    }

    /* Hassam Multisig - 210820 */
    public function getUserTotalWithdrawnTradingKRWWithoutFees($userId)
    {
        $getUserTotalWithdrawnSum = 0;
        $this->Transactions = TableRegistry::get("Transactions");
        $getUserTotalWithdrawn = $this->Transactions->find();
        $getUserTotalWithdrawnCnt = $getUserTotalWithdrawn
            ->select(['sum' => $getUserTotalWithdrawn->func()->sum('amount')])
            ->where(array('user_id' => $userId, 'tx_type' => 'bank_initial_withdraw', 'status' => 'completed'))
            ->toArray();
        if (!empty($getUserTotalWithdrawnCnt)) {
            $getUserTotalWithdrawnSum = $getUserTotalWithdrawnCnt[0]['sum'];
        }
        return $getUserTotalWithdrawnSum;
    }

    /* Hassam Multisig - 210820 */
    public function getTotalWithdrawTradingAmount($userId, $cryptoCoinId)
    {
        $getUserTransferredCoinSum = 0;
        $this->Transactions = TableRegistry::get("Transactions");
        $getUserTransferredCoin = $this->Transactions->find();
        $getUserTransferredCoinCnt = $getUserTransferredCoin
            ->select(['sum' => $getUserTransferredCoin->func()->sum('coin_amount')])
            ->where(['user_id' => $userId,
                'cryptocoin_id' => $cryptoCoinId, 'tx_type ' => 'bank_initial_withdraw',
                'OR' => [['status' => 'completed'], ['status' => 'pending']]])
            ->toArray();

        if (!empty($getUserTransferredCoinCnt)) {
            $getUserTransferredCoinSum = $getUserTransferredCoinCnt[0]['sum'];
        }
        return $getUserTransferredCoinSum;
    }

    public function getUserCryptocoins($authUserId, $coinId = null)
    {
        $where = '';
        if (empty($coinId) === false) {
            $where = " AND ct.id = '{$coinId}' ";
        }

        //TODO 20220628 SOJO 날쿼리 추가
        $connection = new \Cake\Datasource\ConnectionManager;
        $connection = $connection::get('default');
        $query = "
            SELECT 
                ct.*
                ,(SELECT SUM(amount) FROM principal_wallet WHERE user_id = {$authUserId} AND type != 'event_coin' AND status = 'completed' AND cryptocoin_id = ct.id) AS wallet_amount
                ,(SELECT SUM(amount) FROM principal_wallet WHERE user_id = {$authUserId} AND status = 'pending' ANd type = 'bank_initial_withdraw' AND cryptocoin_id = ct.id) AS initial_withdraw
                ,(SELECT SUM(coin_amount) FROM `transactions` WHERE user_id = {$authUserId} AND status = 'completed' AND cryptocoin_id = ct.id AND tx_type <> 'bank_initial_deposit' AND tx_type <> 'save') AS trade_amount
                ,(SELECT (SUM(coin_amount)) AS `sum` FROM transactions as t LEFT JOIN buy_exchange as b  ON (t.tx_type = 'buy_exchange' AND t.remark = 'reserve for exchange' AND b.id = (t.exchange_id)) WHERE (t.user_id = {$authUserId} AND t.status = 'completed' AND t.cryptocoin_id = ct.id AND t.remark = 'reserve for exchange' AND b.status IN ('pending','processing'))) AS buy_amount
                ,(SELECT (SUM(coin_amount)) AS `sum` FROM transactions as t LEFT JOIN sell_exchange as s ON (t.tx_type = 'sell_exchange' AND t.remark = 'reserve for exchange' AND s.id = (t.exchange_id)) WHERE (t.user_id = {$authUserId} AND t.status = 'completed' AND t.cryptocoin_id = ct.id AND t.remark = 'reserve for exchange' AND s.status IN ('pending','processing'))) AS sell_amount
            FROM
                cryptocoin AS ct
            WHERE
                ct.status = 1
                {$where}
            ORDER BY
                ct.serial_no ASC
        ";

        if (empty($coinId) === false) {
            return $connection->execute($query)->fetch('assoc');
        }

        return $connection->execute($query)->fetchAll('assoc');
    }

}

?>