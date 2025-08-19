<?=$this->include("layout_mobi/header");?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">       
        <div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">SEARCH APPLICATION</h3>
			</div>
			<div class="panel-body">
				<form method="post" action="">
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm">
						   Date From:
						   <input type="date" name="from_date" id="from_date" value="<?php echo $from_date;?>" class="form-control">
						</div>
					
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm">
						   Date To:
						   <input type="date" name="upto_date" id="upto_date" value="<?php echo $upto_date;?>" class="form-control">
						</div>
					
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm">
						  Keyword:
						   <input type="text" name="keyword" id="keyword" value="<?php echo $keyword;?>" class="form-control">
						</div>
					</div>
					<div class="row">
						<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pad-btm text-center">
							<button type="submit" id="search" name="search" class="col-lg-6 col-md-6 col-sm-12 col-xs-12 btn btn-primary" value="search">SEARCH</button>
						</div>
					</div>
				</form>
			</div>
		</div>
 
        <?php
        if($consumer_dtls)
        {
           foreach($consumer_dtls as $val)
           {


        ?>
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">APPLICATION DETAILS</h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-2">
						<label for="exampleInputEmail1">Application No.</label>
					</div>
					<div class="col-sm-2">
						<label for="exampleInputEmail1"><strong><?php echo $val['application_no']; ?></strong></label>
					</div>
					<div class="col-sm-2">
						<label for="exampleInputEmail1">Ward No.</label>
					</div>
					<div class="col-sm-2">
						<label for="exampleInputEmail1"><strong><?php echo $val['ward_no']; ?></strong></label>
					</div>
					<div class="col-sm-2">
						<label for="exampleInputEmail1">Pipeline Type</label>
					</div>
					<div class="col-sm-2">
						<label for="exampleInputEmail1"><strong><?php echo $val['pipeline_type']; ?></strong></label>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-2">
						<label for="exampleInputEmail1">Connection Type</label>
					</div>
					<div class="col-sm-2">
						<label for="exampleInputEmail1"><strong><?php echo $val['connection_type']; ?></strong></label>
					</div>
					<div class="col-sm-2">
						<label for="exampleInputEmail1">Connection Through</label>
					</div>
					<div class="col-sm-2">
						<label for="exampleInputEmail1"><strong><?php echo $val['connection_through']; ?></strong></label>
					</div>
					<div class="col-sm-2">
						<label for="exampleInputEmail1">Category</label>
					</div>
					<div class="col-sm-2">
						<label for="exampleInputEmail1"><strong><?php echo $val['category']; ?></strong></label>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-2">
						<label for="exampleInputEmail1">Property Type</label>
					</div>
					<div class="col-sm-2">
						<label for="exampleInputEmail1"><strong><?php echo $val['property_type']; ?></strong></label>
					</div>
				</div>
                <div class="row">
					<center><a href="<?php echo base_url('WaterFieldVerification/field_verification/'.md5($val['id']));?>" class="btn btn-success">Go to Survey</a></center>
				</div>  
                 
            </div>
        </div>
       <?php
            }
        }
        ?>
    </div>
</div>
    <!--End page content-->
<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>
<script type="text/javascript">
	
function modelInfo(msg){
    $.niftyNoty({
        type: 'info',
        icon : 'pli-exclamation icon-2x',
        message : msg,
        container : 'floating',
        timer : 5000
    });
}
<?php 
    if($error=flashToast('error'))
    {
        echo "modelInfo('".$error."');";
    }
  ?>


</script>