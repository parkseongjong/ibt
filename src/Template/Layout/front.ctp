<!DOCTYPE html>
<!-- saved from url=(0057)https://xbootstrap.com/demo/crypto/cryptoapp/index-6.html -->
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">
<head>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
<meta name="description" content="Coin IBT is a fast and secure platform that makes it easy to buy, sell, and store cryptocurrency like Bitcoin, Ethereum, and more.">
<meta name="keywords" content="crypto, Bootstrap, bitcoins, ethereum, dogecoin, iota, ripple, siacoin, exchange, trading platform, crypto trading">
<meta name="author" content="">
<link rel="icon" href="<?php echo $this->request->webroot?>assets/images/favicon.ico" type="image/x-icon">
<title>SMBIT Exchange</title>
<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/bootstrap.css">
<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/font-awesome.css">
<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/animate+animo.css">
<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/csspinner.min.css">
<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/app.css">
<script src="<?php echo $this->request->webroot ?>assets/html/js/jquery.js"></script> 
<script src="<?php echo $this->request->webroot ?>assets/html/js/bootstrap.js"></script> 
<script src="<?php echo $this->request->webroot ?>assets/html/js/modernizr.js"></script>
<script src="<?php echo $this->request->webroot ?>assets/html/js/fastclick.js"></script>

<style type="text/css">
.jqstooltip {
	position: absolute;
	left: 0px;
	top: 0px;
	visibility: hidden;
	background: rgb(0, 0, 0) transparent;
	background-color: rgba(0,0,0,0.6);
filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);
	-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";
	color: white;
	font: 10px arial, san serif;
	text-align: left;
	white-space: nowrap;
	padding: 5px;
	border: 1px solid white;
	z-index: 10000;
}
.jqsfield {
	color: white;
	font: 10px arial, san serif;
	text-align: left;
}
</style>
</head>
<body class="animate-border divide">
<div id="overlayLoader" class="" style="transform: translateY(-100%);">
  <div id="preloader" class="" style="opacity: 0.1; transform: translateY(-80px);"> <span></span> <span></span> </div>
</div>
<section class="wrapper">
  
  <?php echo $this->element('Front/nav'); ?> 
  
  <?php echo $this->fetch('content'); ?>

</section>

 <script type="text/javascript">
  window._mfq = window._mfq || [];
  (function() {
    var mf = document.createElement("script");
    mf.type = "text/javascript"; mf.async = true;
    mf.src = "//cdn.mouseflow.com/projects/560dedc2-b6f7-4508-ab97-71b7d3b16a00.js";
    document.getElementsByTagName("head")[0].appendChild(mf);
  })();
</script>
<script src="<?php echo $this->request->webroot ?>assets/html/js/tradify.js"></script> 
<script>
         $(document).ready(function() {
            // Candlestick
            $.getJSON('tradify/data.json', function (data) {

                // create the chart
                Highcharts.stockChart('candlestickChart', {

                  chart: {
                },


                    rangeSelector: {
                        selected: 1
                    },

                    series: [{
                        type: 'candlestick',
                        name: 'SC-BTC',
                        data: data,
                        dataGrouping: {
                            units: [
                                [
                                    'week', // unit name
                                    [1] // allowed multiples
                                ], [
                                    'month',
                                    [1, 2, 3, 4, 6]
                                ]
                            ]
                        }
                    }]
                });
            });
            });
      </script>
</body>
</html>