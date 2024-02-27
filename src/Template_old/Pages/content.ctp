<!-- Container Start -->
<div class="container">
	<!-- Breadcrump Start -->
	<div class="breadcrump box-shadow border-radius clearfix hidden-xs">
		<ul>
			<li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'home']);  ?>">Home</a></li>
			<li><?php echo $content['title']; ?></li>
		</ul>
	</div>
	<!-- Breadcrump End -->

	<section class="clearfix padding15 box-shadow border-radius termswrap">
		<?php 
		echo $content['description']; ?>
	</section>
</div>
