
<section>
    <section class="main-content">
      <a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'mywallet']) ?>" class="btn btn-labeled btn-primary pull-right"> <span class="btn-label"><i class="fa fa-dollar"></i> </span>Goto Wallet</div></a>
      <h3>Dashboard </h3>
     <!-- Orders Book -->
      <div class="row">
       
        <!-- My Order History -->
        <div class="col-md-12">
          <div class="panel panel-default">
            <div class="panel-heading">Market History <a href="javascript:void(0);" data-perform="panel-collapse" data-toggle="tooltip" title="" class="pull-right" data-original-title="Collapse Panel"> <em class="fa fa-minus"></em> </a> </div>
            <div class="panel-wrapper collapse in" aria-expanded="true" style="">
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>pair</th>
                      
                    </tr>
                  </thead>
                  <tbody>
				  <?php foreach($getAllPair as $getdata){ ?>
                    <tr>
                      <td> <a href="<?php echo $this->Url->build(['controller'=>'exchange','action'=>'index',$getdata['cryptocoin_first']['short_name'],$getdata['cryptocoin_second']['short_name']]); ?>"><?php echo $getdata['cryptocoin_first']['short_name']."/".$getdata['cryptocoin_second']['short_name'] ?></a></td>
                    </tr>
				  <?php } ?>
                   
                  </tbody>
                </table>
              </div>
              
            </div>
          </div>
        </div>
    </section>
   
  </section>
   <!-- FOOTER -->
    <?php echo $this->element('Front/footer'); ?>
    <!-- end FOOTER --> 