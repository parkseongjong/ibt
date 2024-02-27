<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/coupons.css" />
<div class="container">

    <div class="custom_frame document">

        <?php echo $this->element('Front2/coupon_left'); ?>

        <div class="contents">
            <div class="coupon-content-wrap">
                <div class="coupon-content-container">
                    <div class="coupon-content-area">
                        <div class="coupon-usage-contents">
                            <h1><?= __('Coupon Usage Status'); ?></h1>

                            <div class="coupon-list-table-container">
                                <div class="coupon-list-table">
                                    <table>
                                        <thead>
                                        <tr>
                                            <th><?=__('Coupon Name')?></th>
                                            <th><?=__('Payment methods')?></th>
                                            <th><?=__('Payment amount')?></th>
                                            <th><?=__('Amount used')?></th>
                                            <th><?=__('Balance')?></th>
                                            <th><?=__('Usage period')?></th>
                                            <th><?=__('Status')?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>20,000 coupon</td>
                                            <td>CTC WALLET</td>
                                            <td>150,000 TP3</td>
                                            <td>30,000,000 KRW</td>
                                            <td> 1,500,000 KRW</td>
                                            <td>~20.03.12</td>
                                            <td>
                                                <div class="coupon-status"><?=__('Be in Use')?></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>20,000 coupon</td>
                                            <td>CTC WALLET</td>
                                            <td>150,000 TP3</td>
                                            <td>30,000,000 KRW</td>
                                            <td> 1,500,000 KRW</td>
                                            <td>~20.03.12</td>
                                            <td>
                                                <div class="coupon-status completed"><?=__('Completed Use')?></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>20,000 coupon</td>
                                            <td>CTC WALLET</td>
                                            <td>150,000 TP3</td>
                                            <td>30,000,000 KRW</td>
                                            <td> 1,500,000 KRW</td>
                                            <td>~20.03.12</td>
                                            <td>
                                                <div class="coupon-status use"><?=__('Use')?></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>20,000 coupon</td>
                                            <td>CTC WALLET</td>
                                            <td>150,000 TP3</td>
                                            <td>30,000,000 KRW</td>
                                            <td> 1,500,000 KRW</td>
                                            <td>~20.03.12</td>
                                            <td>
                                                <div class="coupon-status expire"><?=__('Expire')?></div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>