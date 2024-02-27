<?php
namespace App\Controller\Front2;

use App\Controller\AppController; // HAVE TO USE App\Controller\AppController

use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Email;
use Cake\Auth\DefaultPasswordHasher;
use Google_Client;
use Google_Service_Plus;
use Google_Service_Oauth2;

class ApiController extends AppController{
    public function beforeFilter(Event $event)
    {
        $this->Auth->allow(['index','apiinfo','codeinfo','ticker','orderbook','transactionHistory','assets']);
        $this->loadModel('Cryptocoin');
        $this->loadModel('BuyExchange');
        $this->loadModel('SellExchange');
        $this->loadModel('Transactions');
        $this->loadModel('ExchangeHistory');
        $this->loadModel('Coinpair');
        $this->loadModel('Messages');
        $this->loadModel('Users');
    }

    public function index(){

    }
    /*
    public function index(){
        $returnArr = [];
        // get all pairs / 20 - KRW
        $getCoinPairList = $this->Coinpair->find('all',['fields'=>['pair'=>'CONCAT(cryptocoin_first.short_name,"_",cryptocoin_second.short_name)',
                                                                  'firstCoinId'=>'cryptocoin_first.id',
                                                                  'secondCoinId'=>'cryptocoin_second.id'],
                                                        'conditions'=>['Coinpair.status'=>1,
                                                                        'Coinpair.coin_second_id'=>20],
                                                        'contain'=>['cryptocoin_first','cryptocoin_second'],
                                                        'order'=>['Coinpair.order_no'=>'asc'],
                                                        ])
                                                        ->hydrate(false)
                                                        ->toArray();
        foreach($getCoinPairList as $getCoinPairSingle){
            $pairName = $getCoinPairSingle['pair'];
            $firstCoinId = $getCoinPairSingle['firstCoinId'];
            $secondCoinId = $getCoinPairSingle['secondCoinId'];
            // get last api start
            $currentPrice = $this->ExchangeHistory->find('all',['conditions'=>['OR'=>[
                                                                                                      ['get_cryptocoin_id'=>$secondCoinId,
                                                                                                       'spend_cryptocoin_id'=>$firstCoinId],
                                                                                                      ['spend_cryptocoin_id'=>$secondCoinId,
                                                                                                       'get_cryptocoin_id'=>$firstCoinId]
                                                                                                      ]
                                                                                                ],
                                                                                'limit' => 2,
                                                                                'order' => ['id'=>'desc']
                                                                                ])
                                                                              ->hydrate(false)
                                                                              ->toArray();
            // if getting price from external api
            $findCoinPair = $this->Coinpair->find("all",["conditions"=>['OR'=>[
                                                                                      ['coin_first_id'=>$secondCoinId,
                                                                                       'coin_second_id'=>$firstCoinId,
                                                                                      // 'binance_price'=>'Y',
                                                                                       ],
                                                                                      ['coin_second_id'=>$secondCoinId,
                                                                                       'coin_first_id'=>$firstCoinId,
                                                                                       //'binance_price'=>'Y',
                                                                                       ]
                                                                                    ]
                                                                            ]
                                                            ])
                                                            ->hydrate(false)
                                                            ->first();

            if(!empty($findCoinPair) && $findCoinPair['binance_price']=="Y" && !empty($findCoinPair['pair_price'])){
                $currentPrice[0]['get_per_price'] = $findCoinPair['pair_price'];
            }
            if(empty($currentPrice)){
                $currentPrice = $this->Cryptocoin->find('all',['conditions'=>['id'=>$secondCoinId],'fields'=>['get_per_price'=>'usd_price']])->hydrate(false)->toArray();
                $sendArr['real_current_price'] = $currentPrice[0]['get_per_price'];
            }
            $lastPrice = $currentPrice[0]['get_per_price'];
            // get last api end


            // get last 24 hours data

            $getRecentMaxMinPrice = $this->ExchangeHistory->find('all',['conditions'=>['OR'=>[
                                                                                              ['get_cryptocoin_id'=>$secondCoinId,
                                                                                               'spend_cryptocoin_id'=>$firstCoinId,
                                                                                             'TIMESTAMPDIFF(HOUR,created_at,NOW()) <= '=>24
                                                                                               ],
                                                                                              ['spend_cryptocoin_id'=>$secondCoinId,
                                                                                               'get_cryptocoin_id'=>$firstCoinId,
                                                                                              'TIMESTAMPDIFF(HOUR,created_at,NOW()) <= '=>24
                                                                                               ]
                                                                                            ]
                                                                                    ],
                                                                        'fields'=>['maxprice'=>'max(get_per_price)','minprice'=>'min(get_per_price)'],
                                                                        'limit' => 1,
                                                                        //'group'=>['DATE_FORMAT(created_at,"%Y-%m-%d")'],
                                                                        'order' => ['id'=>'desc']
                                                                        ])
                                                                      ->hydrate(false)
                                                                      ->first();

            if(!empty($getRecentMaxMinPrice)){
                $maxPrice = !empty($getRecentMaxMinPrice['maxprice']) ? $getRecentMaxMinPrice['maxprice'] : $lastPrice;
                $minPrice = !empty($getRecentMaxMinPrice['minprice']) ? $getRecentMaxMinPrice['minprice'] : $lastPrice;
            }

            $changeInOneDay = (($findCoinPair['current_pair_price']-$findCoinPair['mid_night_price'])/$findCoinPair['mid_night_price'])*100;

            // get volume start
            $getKrwVolume = $this->ExchangeHistory->find('all',['conditions'=>['OR'=>[
                                                                                          ['get_cryptocoin_id'=>$secondCoinId,
                                                                                           'spend_cryptocoin_id'=>$firstCoinId,
                                                                                          'TIMESTAMPDIFF(HOUR,created_at,NOW()) <= '=>24
                                                                                           ],
                                                                                          ['spend_cryptocoin_id'=>$secondCoinId,
                                                                                           'get_cryptocoin_id'=>$firstCoinId,
                                                                                          'TIMESTAMPDIFF(HOUR,created_at,NOW()) <= '=>24
                                                                                           ]
                                                                                        ]
                                                                                ],
                                                                    'fields'=>['totalsum'=>'sum(get_amount*get_per_price)'],
                                                                    'limit' => 1,
                                                                    'order' => ['id'=>'desc']
                                                                    ])
                                                                  ->hydrate(false)
                                                                  ->first();
            $getKrwVolumeSum = (!empty($getKrwVolume) && !empty($getKrwVolume['totalsum'])) ?$getKrwVolume['totalsum'] : 0;


            $getOtherVolume = $this->ExchangeHistory->find('all',['conditions'=>['OR'=>[
                                                                                          ['get_cryptocoin_id'=>$secondCoinId,
                                                                                           'spend_cryptocoin_id'=>$firstCoinId,
                                                                                          'TIMESTAMPDIFF(HOUR,created_at,NOW()) <= '=>24
                                                                                           ],
                                                                                          ['spend_cryptocoin_id'=>$secondCoinId,
                                                                                           'get_cryptocoin_id'=>$firstCoinId,
                                                                                          'TIMESTAMPDIFF(HOUR,created_at,NOW()) <= '=>24
                                                                                           ]
                                                                                        ]
                                                                                ],
                                                                    'fields'=>['totalsum'=>'sum(get_amount)'],
                                                                    'limit' => 1,
                                                                    'order' => ['id'=>'desc']
                                                                    ])
                                                                  ->hydrate(false)
                                                                  ->first();
            $getOtherVolumeSum = (!empty($getOtherVolume) && !empty($getOtherVolume['totalsum'])) ?$getOtherVolume['totalsum'] : 0;
            // get volume end



            $returnArr[] =["trading_pairs"=>$pairName,
                           "last_price"=>$lastPrice,
                           "highest_price_24h"=>$maxPrice,
                           "lowest_price_24h"=>$minPrice,
                           "price_change_percent_24h"=>$changeInOneDay,
                           "base_volume"=>$getKrwVolumeSum,
                           "quote_volume"=>$getOtherVolumeSum,

                           ];
        }
        echo json_encode($returnArr); die;
    }
    */

    public function apiinfo(){

    }

    public function codeinfo(){

    }

    public function ticker(){

    }

    public function orderbook(){

    }

    public function transactionHistory(){

    }


    public function assets(){
        $returnArr = [];
        // get all pairs / 20 - KRW
        $getCoinList = $this->Cryptocoin->find('all',['fields'=>['name',
            'short_name',
            'unified_crptoasset_id',
            'can_deposit',
            'can_withdrawal',
            'min_withdraw',
            'max_withdraw',
            'maker_fee',
            'taker_fee'
        ],
            'conditions'=>['status'=>1],
            'order'=>['id'=>'asc'],
        ])
            ->hydrate(false)
            ->toArray();
        foreach($getCoinList as $getCoinSingle){
            $returnArr[$getCoinSingle["short_name"]] = ["name"=>$getCoinSingle["name"],
                "unified_crptoasset_id"=>$getCoinSingle["name"],
                "can_deposit"=> ($getCoinSingle["can_deposit"]=="Y") ? true : false,
                "can_withdrawal"=>($getCoinSingle["can_withdrawal"]=="Y") ? true : false,
                "min_withdraw"=>$getCoinSingle["min_withdraw"],
                "max_withdraw"=>$getCoinSingle["max_withdraw"],
                "maker_fee"=>$getCoinSingle["maker_fee"],
                "taker_fee"=>$getCoinSingle["taker_fee"],
            ];
        }

        echo json_encode($returnArr); die;
    }

    //블랙리스트 json

    function blacklist_update(){
        //json 파일 업데이트 DB에 쌓자
    }
}