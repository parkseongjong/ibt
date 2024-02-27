		
		<div class="col-md-12">
          <div class="col-middle">
            <div class="text-center text-center">
              <h1 class="error-number">403</h1>
              <h2>Sorry but we couldnt find this page</h2>
              <p>You donot have permission to access this page.</a>
              </p>
              <div class="mid_center">
               <!--- <a href = "<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);?>"><h3>Go to home page</h3></a> --->
			   <a href = "<?php echo $this->request->session()->read('Config.referer'); ?>"><h3>Go to previous page</h3></a>
              </div>
            </div>
          </div>
        </div>