<div class="menu_left">
  <h1><?=__('Terms of Use') ?></h1>
  <p class="item">
    <a href="/front2/document/usinginfo" class="usinginfo"><?=__('Terms of Use') ?></a>
  </p>
  <p class="item">
    <a href="/front2/document/privacy" class="privacy"><?=__('Personal Information Processing Policy') ?></a>
  </p>
  <p class="item">
    <a href="/front2/document/terms-staking" class="terms-staking">
      <?= __('terms-staking') ?>
    </a>
  </p>
  <p class="item">
    <a href="/front2/document/terms-rental" class="terms-rental">
      <?= __('terms-rental') ?>
    </a>
  </p>
  <p class="item">
    <a href="/front2/document/terms-annual" class="terms-annual">
      <?= __('terms-annual') ?>
    </a>
  </p>
  <p class="item">
    <a href="/front2/document/terms-deal" class="terms-deal">
      <?= __('terms-deal') ?>
    </a>
  </p>
  <p class="item">
    <a href="/front2/document/terms-deal-coupon" class="terms-deal-coupon">
      <?= __('terms-deal-coupon') ?>
    </a>
  </p>
</div>

<script>
$(document).ready(function(){
<?php if (isset($kind)) { ?>
  $(".<?=$kind ?>").addClass('on');
<?php } ?>
});
</script>