  <div class="clearfix"></div>
<footer class="main-footer"> <strong>Copyright &copy; 2020 <a href="/">SMBIT</a>.</strong> All rights
  reserved. </footer>
  <div id="generateTicketModel" class="modal fade" role="dialog">

    <div class="modal-dialog login_model">

        <!-- Modal content-->

        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Generate Ticket</h4>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div id="contact_message"></div>
                </div>
            </div>
            <div class="modal-body">

                <?php echo $this->Form->create(null,array('id'=>'','enctype'=>'multipart/form-data'));?>
                <div style="display:none">
                                <input type="hidden" name="ci_csrf_token" value="40cd64996329897a78066e445d8cb0c0" />
                            </div>
                            <fieldset>
                                <div class="form-group ">
                                    <label class="control-label col-md-2" for="subject">Subject<span class="required">*</span></label>
                                    <div class="col-md-10">
                                        <?php echo $this->Form->input('subject_id',['type'=>'select','label' =>false,'class'=>'form-control','options'=>$subjects]); ?>
                                        <span class='help-block'></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="title" class="control-label col-md-2">Title<span class="required">*</span></label> <div class='col-md-10'>
                                        <?php echo $this->Form->input('title',['type'=>'text','label' =>false,'class'=>'form-control']); ?>
                                        <span class='help-block'></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="message" class="control-label col-md-2">Your Message<span class="required">*</span></label> <div class='col-md-10'>
                                        <?php echo $this->Form->textarea('message',['id'=>'message','label' =>false,'class'=>'form-control']); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="image" class="control-label col-md-2">Image</label> <div class='col-md-10'>
                                        <?php echo $this->Form->input('media',['type'=>'file','label' =>false,'class'=>'form-control']); ?>
                                        <span class='help-block'></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class='col-md-10 col-md-offset-2'>
                                        <div class="controls mt5 captcha_container mb5" id="new_support_captcha_container"></div>
                                    </div>
                                </div>
                            </fieldset>

                    <button type="button" class="btn  btn-success btn-sm" id="confirm_btn" name="submit"><i class="fa fa-check-circle fa-lg "></i> Confirm generate new support request</button>
                    <button type="button" class="btn  btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times fa-lg"></i> Close</button>
                </form>
            </div>


        </div>

    </div>

  </div>
<!-- ./wrapper --> 

<!-- jQuery 3 --> 
<!-- jQuery UI 1.11.4 --> 
<script src="<?php echo $this->request->webroot?>css/Admin/bower_components/jquery-ui/jquery-ui.min.js"></script> 
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip --> 
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script> 
<!-- Bootstrap 3.3.7 --> 
<script src="<?php echo $this->request->webroot?>css/Admin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script> 
<script src="<?php echo $this->request->webroot;?>css/Admin/bower_components/moment/min/moment.min.js"></script> 
<!-- daterangepicker --> 
<script src="<?php echo $this->request->webroot;?>css/Admin/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script> 
<!-- datepicker --> 
<script src="<?php echo $this->request->webroot;?>css/Admin/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script> 

<!-- Bootstrap WYSIHTML5 --> 
<!-- AdminLTE App --> 
<script src="<?php echo $this->request->webroot;?>css/Admin/bower_components/dist/js/adminlte.min.js"></script> 
<!-- AdminLTE for demo purposes --> 
<script src="<?php echo $this->request->webroot;?>css/Admin/pnotify.js"></script>
<script src="<?php echo $this->request->webroot;?>assets/js/bootbox.min.js"></script>
<script src="<?php echo $this->request->webroot;?>assets/js/bootbox.min.js"></script>
<script src="<?php echo $this->request->webroot;?>assets/js/multi-select.js"></script>
<?php if($_SERVER['SERVER_NAME']=='galaxycoin.co') echo '<script src="https://cdn.tinymce.com/4/tinymce.min.js"></script>';
else '<script src="http://cdn.tinymce.com/4/tinymce.min.js"></script>';
?>
</body>
</html>
<script>
$('.datepicker').datepicker({
	format: 'yyyy-mm-dd',
	startDate: new Date()
});

<?php if(isset($authUser)){?>
function  getINRval(){
	/* jQuery.ajax({ 
		url: '<?php echo $this->Url->build(['controller'=>'transactions' , 'action'=>'getINR']);  ?>',
		success: function(data) {
			$("#inr").html(data);
			setTimeout(function(){getINRval();}, 30000);
			
			
		}
	}); */

}
$(function(){
	getINRval();
});

<?php }?>


<!---- tinymce editor  ----->

tinymce.init({
  selector: 'textarea.editor',
    menubar: false,
	
  height: 100,
  width:474,
  theme: 'modern',
  
  plugins: [
    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
    'searchreplace wordcount visualblocks visualchars code fullscreen',
    'insertdatetime media nonbreaking save table contextmenu directionality',
    'emoticons template paste textcolor colorpicker textpattern imagetools'
  ],
  
  toolbar1: ' Source code |  undo redo |styleselect | bold italic | alignleft aligncenter alignright alignjustify | forecolor backcolor |bullist numlist outdent indent|fontselect',
  content_css: [
    'http://www.tinymce.com/css/codepen.min.css'
  ]
 });
 
 <!---- /tinymce editor  ----->

</script>
