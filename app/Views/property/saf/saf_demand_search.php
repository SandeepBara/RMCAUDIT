<?= $this->include('layout_vertical/header');?>
<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
  <div id="page-head">
      <!--Breadcrumb-->
      <ol class="breadcrumb">
          <li><a href="#"><i class="demo-pli-home"></i></a></li>
          <li><a href="#"> Self Assessment Form Demand</a></li>
      </ol><!--End breadcrumb-->
  </div>
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">SEARCH APPLICATION NO.</h3>
			</div>
			<div class="panel-body">
				<form method="post" action="<?=base_url('safDemand/search_application_no');?>">
					<div class="row">
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pad-btm">
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm">
						   Enter Application No.
						   <input required type="text" name="application_no" id="application_no" value="" class="form-control">
						</div>

						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pad-btm text-center">
							<br>
							<button type="submit" id="search" name="search" class="col-lg-6 col-md-6 col-sm-12 col-xs-12 btn btn-primary" value="search">SEARCH</button>
						</div>

					</div>
				</form>
			</div>
		</div>


    </div>
</div>
    <!--End page content-->
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
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
