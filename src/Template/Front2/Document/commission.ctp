<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/coupons.css" />
<div class="container">

    <div class="custom_frame document">

        <?php echo $this->element('Front2/coupon_left'); ?>
        <ul class="tab_menu">
            <li id="index" ><a href="<?php echo $this->Url->build(['controller'=>'document','action'=>'priceinfo']) ?>"><?=__('Fee Information') ?></a></li>
            <li id="history" class="on"><a href="<?php echo $this->Url->build(['controller'=>'document','action'=>'commission']) ?>"><?=__('Commission Coupon') ?></a></li>
        </ul>
        <div class="contents">

            <h2 style="font-weight: bold;"><?= __('Commission Coupon'); ?></h2>
            <div class="coupon-banner">
                <img src="<?php echo $this->request->webroot ?>images/coupon-gold.png" alt="coupon-gold" class="banner-image" />
                <div class="banner-text">
                    <div class="banner-text-title">
                       <?= __('Commission banner1'); ?>
                    </div>
                    <div class="banner-text-content">
                        <?= __('Commission Text10'); ?>
                    </div>
                </div>
            </div>
            <div class="coupon-description">
                <ul style="list-style-type: disc;padding-inline-start: 20px;font-size: 16px;margin: 5px 0;">
                    <li style="list-style-type: disc;padding-inline-start: 20px;margin: 5px 0;"><?= __('Commission Text1'); ?></li>
                    <li style="list-style-type: disc;padding-inline-start: 20px;"><?= __('Commission Text2'); ?></li>
                </ul>
            </div>
            <div class="coupon-volume">
                <div class="volume-top">
                    <span class="user-name"><?= $name;?></span>
                    <span class="name-desc"><?= __('Commission Text3'); ?></span>
                    <span class="volume-krw">
                  <strong><?php if(empty($totalTransactions)) {echo 0;} else echo number_format($totalTransactions,2); ?></strong> <?= __('Commission Counpon4'); ?>
                </span>
                </div>
                <div class="volume-desc">
                    ※ <?= __('Commission Text4'); ?>
                </div>
            </div>
            <div class="coupon-list-wrap">
                <ul class="coupon-list">
                    <li class="list-item">
                        <div class="list-item-coupon-area">
                            <img src="<?php echo $this->request->webroot ?>images/coupon-list-item-background.png" alt="coupon-list-item-background">
                            <div class="list-item-title">
                                <?= __('Commission Counpon_20000'); ?>
                            </div>
                            <div class="list-item-body">
                                <div class="body-amount">
                        <span class="number">
                          20,000
                        </span>
                                    <span class="currency">
                          <?= __('Commission Counpon4'); ?>
                        </span>
                                </div>
                                <div class="body-commission">
                        <span class="commission-text">
                          <?= __('Commission Counpon1'); ?>
                        </span>
                                    <span class="commission-percent">0.2%</span>
                                </div>
                            </div>
                            <div class="coupon-list-item-discount-circle-wrap">
                                <div class="coupon-list-item-discount-circle">
                                    <img src="<?php echo $this->request->webroot ?>images/coupon-list-item-circle.png" alt="coupon-list-item-circle">
                                    <div class="discount-percent">
                                        20%<br /> <?= __('Commission Counpon3'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="list-item-btn-area">
                            <div class="list-item-btn">
                                <?= __('Commission Counpon2'); ?>
                            </div>
                        </div>
                    </li>


                    <li class="list-item">
                        <div class="list-item-coupon-area">
                            <img src="<?php echo $this->request->webroot ?>images/coupon-list-item-background.png" alt="coupon-list-item-background">
                            <div class="list-item-title">
                                <?= __('Commission Counpon_40000'); ?>
                            </div>
                            <div class="list-item-body">
                                <div class="body-amount">
                        <span class="number">
                          40,000
                        </span>
                                    <span class="currency">
                          <?= __('Commission Counpon4'); ?>
                        </span>
                                </div>
                                <div class="body-commission">
                        <span class="commission-text">
                          <?= __('Commission Counpon1'); ?>
                        </span>
                                    <span class="commission-percent">0.16%</span>
                                </div>
                            </div>
                            <div class="coupon-list-item-discount-circle-wrap">
                                <div class="coupon-list-item-discount-circle">
                                    <img src="<?php echo $this->request->webroot ?>images/coupon-list-item-circle.png" alt="coupon-list-item-circle">
                                    <div class="discount-percent">
                                        36%<br /> <?= __('Commission Counpon3'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="list-item-btn-area">
                            <div class="list-item-btn">
                                <?= __('Commission Counpon2'); ?>
                            </div>
                        </div>
                    </li>


                    <li class="list-item">
                        <div class="list-item-coupon-area">
                            <img src="<?php echo $this->request->webroot ?>images/coupon-list-item-background.png" alt="coupon-list-item-background">
                            <div class="list-item-title">
                                <?= __('Commission Counpon_60000'); ?>
                            </div>
                            <div class="list-item-body">
                                <div class="body-amount">
                        <span class="number">
                          60,000
                        </span>
                                    <span class="currency">
                          <?= __('Commission Counpon4'); ?>
                        </span>
                                </div>
                                <div class="body-commission">
                        <span class="commission-text">
                          <?= __('Commission Counpon1'); ?>
                        </span>
                                    <span class="commission-percent">0.12%</span>
                                </div>
                            </div>
                            <div class="coupon-list-item-discount-circle-wrap">
                                <div class="coupon-list-item-discount-circle">
                                    <img src="<?php echo $this->request->webroot ?>images/coupon-list-item-circle.png" alt="coupon-list-item-circle">
                                    <div class="discount-percent">
                                        52%<br /> <?= __('Commission Counpon3'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="list-item-btn-area">
                            <div class="list-item-btn">
                                <?= __('Commission Counpon2'); ?>
                            </div>
                        </div>
                    </li>


                    <li class="list-item">
                        <div class="list-item-coupon-area">
                            <img src="<?php echo $this->request->webroot ?>images/coupon-list-item-background.png" alt="coupon-list-item-background">
                            <div class="list-item-title">
                                <?= __('Commission Counpon_80000'); ?>
                            </div>
                            <div class="list-item-body">
                                <div class="body-amount">
                        <span class="number">
                          80,000
                        </span>
                                    <span class="currency">
                          <?= __('Commission Counpon4'); ?>
                        </span>
                                </div>
                                <div class="body-commission">
                        <span class="commission-text">
                          <?= __('Commission Counpon1'); ?>
                        </span>
                                    <span class="commission-percent">0.08%</span>
                                </div>
                            </div>
                            <div class="coupon-list-item-discount-circle-wrap">
                                <div class="coupon-list-item-discount-circle">
                                    <img src="<?php echo $this->request->webroot ?>images/coupon-list-item-circle.png" alt="coupon-list-item-circle">
                                    <div class="discount-percent">
                                        68%<br /> <?= __('Commission Counpon3'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="list-item-btn-area">
                            <div class="list-item-btn">
                                <?= __('Commission Counpon2'); ?>
                            </div>
                        </div>
                    </li>


                    <li class="list-item">
                        <div class="list-item-coupon-area">
                            <img src="<?php echo $this->request->webroot ?>images/coupon-list-item-background.png" alt="coupon-list-item-background">
                            <div class="list-item-title">
                                <?= __('Commission Counpon_325000'); ?>
                            </div>
                            <div class="list-item-body">
                                <div class="body-amount">
                        <span class="number">
                          325,000
                        </span>
                                    <span class="currency">
                          <?= __('Commission Counpon4'); ?>
                        </span>
                                </div>
                                <div class="body-commission">
                        <span class="commission-text">
                          <?= __('Commission Counpon1'); ?>
                        </span>
                                    <span class="commission-percent">0.065%</span>
                                </div>
                            </div>
                            <div class="coupon-list-item-discount-circle-wrap">
                                <div class="coupon-list-item-discount-circle">
                                    <img src="<?php echo $this->request->webroot ?>images/coupon-list-item-circle.png" alt="coupon-list-item-circle">
                                    <div class="discount-percent">
                                        74%<br /> <?= __('Commission Counpon3'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="list-item-btn-area">
                            <div class="list-item-btn">
                                <?= __('Commission Counpon2'); ?>
                            </div>
                        </div>
                    </li>


                    <li class="list-item">
                        <div class="list-item-coupon-area">
                            <img src="<?php echo $this->request->webroot ?>images/coupon-list-item-background.png" alt="coupon-list-item-background">
                            <div class="list-item-title">
                                <?= __('Commission Counpon_500000'); ?>
                            </div>
                            <div class="list-item-body">
                                <div class="body-amount">
                        <span class="number">
                          500,000
                        </span>
                                    <span class="currency">
                          <?= __('Commission Counpon4'); ?>
                        </span>
                                </div>
                                <div class="body-commission">
                        <span class="commission-text">
                          <?= __('Commission Counpon1'); ?>
                        </span>
                                    <span class="commission-percent">0.05%</span>
                                </div>
                            </div>
                            <div class="coupon-list-item-discount-circle-wrap">
                                <div class="coupon-list-item-discount-circle">
                                    <img src="<?php echo $this->request->webroot ?>images/coupon-list-item-circle.png" alt="coupon-list-item-circle">
                                    <div class="discount-percent">
                                        80%<br /> <?= __('Commission Counpon3'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="list-item-btn-area">
                            <div class="list-item-btn">
                                <?= __('Commission Counpon2'); ?>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="coupon-footer">
                <ul class="coupon-precautions-list">
                    <li class="coupon-precautions-list-item">
                        - <?= __('Commission Text5'); ?>
                    </li>
                    <li class="coupon-precautions-list-item">
                        - <?= __('Commission Text6'); ?>
                    </li>
                    <li class="coupon-precautions-list-item">
                        - <?= __('Commission Text7'); ?>
                    </li>
                    <li class="coupon-precautions-list-item">
                        - <?= __('Commission Text8'); ?>
                    </li>
                    <li class="coupon-precautions-list-item">
                        - <?= __('Commission Text9'); ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

        </div>

    </div>

</div>

<script>
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
    }
</script>