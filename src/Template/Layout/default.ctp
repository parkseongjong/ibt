<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<!-- <title> Coinbt Exchange</title> -->
	<title>SMBIT Exchange</title>
	<?php
		
		echo $this->Html->meta('favicon.ico','images/favicon.ico',array('type' => 'icon'));
		echo $this->Html->css(array(
						'bootstrap.css',
						'font-awesome.min.css',
						'animate.css',
						'slick.css',
						'hover.css',
						'styles.css',
						'responsive.css',
						'webslidemenu.css',
						'bootstrap-dialog.min.css'
					));
		echo $this->Html->script(
				array(
					'modernizr.custom.26887.js',
					'Admin/jquery.min.js'
				));
				
				
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
		
	?>
	<script>
	/* 	$(document).ajaxStart(function(){
		    $("div#divLoading").addClass('show');
		});
		$(document).ajaxComplete(function(){
			 $("div#divLoading").removeClass('show');
		}); */

	</script>
  </head>
  
  <body>
   <div class="page clearfix">
	<div id = "divLoading"><i class="fa fa-spinner fa-spin fa-3x fa-fw loader"></i></div>
		<?php //echo $this->element('header'); ?> 
		<?php echo $this->fetch('content'); ?>
        <?php //echo $this->element('footer'); ?>
    </div>
  </body>
</html>
