<?php
session_start();	
echo  $this->include('layout_home/header');
	
?>
<!--CONTENT CONTAINER-->
<div id="content-container">
   
    <div id="page-content">
        <form method="post" action="<?=base_url('TradeCitizen/amendment');?>">
            
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">                   
                    <h3 class="panel-title">Apply For Renewal Licence</h3>
                </div>
                <div class="panel-body">
					<label class="col-md-1">Apply For</label>
					<div class="col-md-2">
						<input type="text" name="application_type" id="application_type" value="AMENDMENT" class="form-control" readonly>
					</div>
					<label class="col-md-2">Licence No.<span class="text-danger">*</span></label>
					<div class="col-md-3 ">
					   <input type="text" name="licenceno" id="licenceno" class="form-control" value="<?php echo $licence_no; ?>">
					</div>
					<div class="col-md-3 pad-btm">
					    <button type="SUBMIT" id="search" name="search" class="btn btn-primary">SUBMIT</button>
					</div>
					
					<div class="row">
						<div class="col-md-12 has-success pad-btm">
							<b style="color:red;"><?php echo $msg; ?></b>
						</div>
					</div>
                </div>
            </div>
		</form>
		
		
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->


<?= $this->include('layout_home/footer');?>		


<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>
