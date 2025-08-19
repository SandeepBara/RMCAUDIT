<?=$this->include("layout_mobi/header");?>

<style>
		.buttonA {
  border: none;
  color: white;
  padding: 10px 25px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  transition-duration: 0.4s;
  cursor: pointer;
}
	.buttonx{
width:150px;
height:45px;
border:none;
outline:none;
box-shadow:-4px 4px 5px 0 #46403a;
color:#fff;
font-size:14px;
text-shadow:0 1px rgba(0,0,0,0.4);
background-color:#25476a;
border-radius:3px;
font-weight:700
}
.buttonx:hover{
background-color:#FF8000;
color:#fff;
cursor:pointer
}
.buttonx:active{
margin-left:-4px;
margin-bottom:-4px;
padding-top:2px;
box-shadow:none
}
.hght {
    height: 108px;
}
@media only screen and (max-width: 600px) {
  .hght {
    height: 108px;
  }
}
</style>
<!--CONTENT CONTAINER-->
<div id="content-container">
<div id="page-head">
    <!--Page Title-->
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <div id="page-title">
    <!-- <h1 class="page-header text-overflow">Department List</h1>//-->
    </div>
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <!--End page title-->

    <!--Breadcrumb-->
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <ol class="breadcrumb">
    <li><a href="#"><i class="demo-pli-home"></i></a></li>
    <li><a href="#">Trade</a></li>
    <li class="active">Reports </li>
    </ol>
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <!--End breadcrumb-->
</div>
    <!--Page content-->
    <div id="page-content">
     
                <div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h5 class="panel-title">
								Search  Trade License Report
								<a class = "pull-right btn btn-info btn_wait_load" href="<?=base_url()?>/Mobi/mobileMenu/trade"><i class='fa fa-arrow-left' aria-hidden='true'></i>Back</a>
							</h5>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" method="GET" action="<?=base_url('');?>/MobiTradeReport/applyLicenceReport">
								<div class="col-md-3">
									<label class="control-label" for="from_date"><b>From Date</b><span class="text-danger">*</span> </label>
									<input required type="date" id="from_date" name="from_date" class="form-control frmtodate" placeholder="From Date" value="<?=(isset($to_date))?$from_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
								</div>
								<div class="col-md-3">
									<label class="control-label" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
									<input required type="date" id="to_date" name="to_date" class="form-control frmtodate" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
								</div>
								<div class="col-md-3">
									<label class="control-label" for="to_date"><b>Ward No.</b><span class="text-danger">*</span></label>
									<select name="ward_id" id="ward_id" class="form-control">
										<option value="All">All</option>
										<?php
										if($ward_list):

										foreach($ward_list as $val):
										?>
										<option value="<?php echo $val['ward_mstr_id'];?>" <?php if($ward_id??null==$val['ward_mstr_id']){ echo "selected"; }?>><?php echo $val['ward_no'];?></option>
										<?php
										endforeach;
										endif;
										?>
									</select>
								</div>
								<div class="col-md-2">
									<label class="control-label" for="department_mstr_id">&nbsp;</label>
									<button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit" >Search</button>
								</div>
							</form>
						</div>
					</div>
					<?php if($newapplyLicense??null)
				    {
					?>
				
              <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h5 class="panel-title"> Trade License Report</h5>
                </div>
                <div class="panel-body">
				<div class="row" style="padding: 15px;">
                <?php if($newapplyLicense['count']==0 ) {?>
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-info panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;" class="ndf"><?=$newapplyLicense['count']?$newapplyLicense['count']:"0"?></p> 
								<p><b>Apply New Licence</b></p>
							</div>
						</div>
					</div>
                    <?php } else {?>
                        <a href="<?php echo base_url('MobiTradeReport/viewLicenceDetails/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("1"));?>">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-info panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;"><?=$newapplyLicense['count']?$newapplyLicense['count']:"0"?></p> 
								<p><b>Apply New Licence</b></p>
							</div>
						</div>
					</div>
                    </a>
                    <?php } ?>
					 

                    <?php if($newapplyLicense['count']==0 ) {?>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-mint panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;" class="ndf"><?=$renewapplyLicense['count']?$renewapplyLicense['count']:"0"?></p> 
								<p><b>Renewal Licence</b></p>
							</div>
						</div>
					</div>
                    <?php } else {?>
                        <a href="<?php echo base_url('MobiTradeReport/viewLicenceDetails/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("2"));?>">
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-mint panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;"><?=$renewapplyLicense['count']?$renewapplyLicense['count']:"0"?></p> 
								<p><b>Renewal Licence</b></p>
							</div>
						</div>
					</div>
					</a>
                    <?php } ?>
				
                    <?php if($newapplyLicense['count']==0 ) {?>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-success panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;" class="ndf"><?=$amendapplyLicense['count']?$amendapplyLicense['count']:"0"?></p> 
								<p><b>Amendment</b></p>
							</div>
						</div>
					</div>
                    <?php } else {?>
                        <a href="<?php echo base_url('MobiTradeReport/viewLicenceDetails/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("3"));?>">
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-success panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;"><?=$amendapplyLicense['count']?$amendapplyLicense['count']:"0"?></p> 
								<p><b>Amendment</b></p>
							</div>
						</div>
					</div>
					</a>
                    <?php } ?>

                    <?php if($newapplyLicense['count']==0 ) {?>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-mint panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;" class="ndf"><?=$surrendapplyLicense['count']?$surrendapplyLicense['count']:"0"?></p> 
								<p><b>Surrender</b></p>
							</div>
						</div>
					</div>
                    <?php } else {?>
                        <a href="<?php echo base_url('MobiTradeReport/viewLicenceDetails/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("4"));?>">
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-mint panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;"><?=$surrendapplyLicense['count']?$surrendapplyLicense['count']:"0"?></p> 
								<p><b>Surrender</b></p>
							</div>
						</div>
					</div>
					</a>
                    <?php } ?>

                       
                </div>
                </div>
                </div>
              
                <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h5 class="panel-title"> Trade Collection Report</h5>
                </div>
                <div class="panel-body">
				<div class="row" style="padding: 15px;">
				<?php if($newlicencecollection['sum']==0 ) {?>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-info panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;" class="ndf"><?=$newlicencecollection['sum']?$newlicencecollection['sum']:"0"?></p> 
								<p><b>New Licence Collection </b></p>
							</div>
						</div>
					</div>
                    <?php } else {?>
                        <a href="<?php echo base_url('MobiTradeReport/viewLicenceDetails/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("newCollectn"));?>">
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-info panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;"><?=$newlicencecollection['sum']?$newlicencecollection['sum']:"0"?></p> 
								<p><b>New Licence Collection</b></p>
							</div>
						</div>
					</div>
					</a>
					<?php } ?>

					<?php if($renewlicencecollection['sum']==0 ) {?>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-mint panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;" class="ndf"><?=$renewlicencecollection['sum']?$renewlicencecollection['sum']:"0"?></p> 
								<p><b>Renewal Licence Collection </b></p>
							</div>
						</div>
					</div>
                    <?php } else {?>
                        <a href="<?php echo base_url('MobiTradeReport/viewLicenceDetails/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("renewCollectn"));?>">
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-mint panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;"><?=$renewlicencecollection['sum']?$renewlicencecollection['sum']:"0"?></p> 
								<p><b>Renewal Licence Collection</b></p>
							</div>
						</div>
					</div>
					</a>
					<?php } ?>
				
					<?php if($amendmentcollection['sum']==0 ) {?>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-success panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;" class="ndf"><?=$amendmentcollection['sum']?$amendmentcollection['sum']:"0"?></p> 
								<p><b>Amendment Collection</b></p>
							</div>
						</div>
					</div>
                    <?php } else {?>
                        <a href="<?php echo base_url('MobiTradeReport/viewLicenceDetails/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("amedCollectn"));?>">
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-success panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;"><?=$amendmentcollection['sum']?></p> 
								<p><b>Amendment Collection</b></p>
							</div>
						</div>
					</div>
					</a>
					<?php } ?>

					<?php if($surrendercollection['sum']==0 ) {?>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-mint panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;" class="ndf"><?=$surrendercollection['sum']?$surrendercollection['sum']:"0"?></p> 
								<p><b>Surrender Collection </b></p>
							</div>
						</div>
					</div>
                    <?php } else {?>
                        <a href="<?php echo base_url('MobiTradeReport/viewLicenceDetails/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("surrenCollectn"));?>">
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-mint panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;"><?=$surrendercollection['sum']?></p> 
								<p><b>Surrender Collection </b></p>
							</div>
						</div>
					</div>
					</a>
					<?php } ?>
                </div>
                </div>
                </div>

                <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h5 class="panel-title"> Trade Denial Report</h5>
                </div>
                <div class="panel-body">
				<div class="row" style="padding: 15px;">
				<?php if($denialApply['count']==0 ) {?>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-info panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;" class="ndf"><?=$denialApply['count']?$denialApply['count']:"0"?></p> 
							<p><b> Total Applied Denial</b></p>
							</div>
						</div>
					</div>
                    <?php } else {?>
                        <a href="<?php echo base_url('MobiTradeReport/denial_details/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("all"));?>">
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-info panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;"><?=$denialApply['count']?$denialApply['count']:"0"?></p> 
								<p><b> Total Applied Denial</b></p>
							</div>
						</div>
					</div>
					</a>
					<?php } ?>

					<?php if($pendingAtEo['count']==0 ) {?>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-dark panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;" class="ndf"><?=$pendingAtEo['count']?$pendingAtEo['count']:"0"?></p> 
							<p><b>Pending At Executive Officer</b></p>
							</div>
						</div>
					</div>
                    <?php } else {?>
                        <a href="<?php echo base_url('MobiTradeReport/denial_details/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("1"));?>">
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-dark panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;"><?=$pendingAtEo['count']?$pendingAtEo['count']:"0"?></p> 
								<p><b>Pending At Executive Officer</b></p>
							</div>
						</div>
					</div>
					</a>
					<?php } ?>
				
					<?php if($approvedDenial['count']==0 ) {?>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-success panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;" class="ndf"><?=$approvedDenial['count']?$approvedDenial['count']:"0"?></p> 
								<p><b>Approved Denial </b></p>
							</div>
						</div>
					</div>
                    <?php } else {?>
                        <a href="<?php echo base_url('MobiTradeReport/denial_details/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("5"));?>">
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-success panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;"><?=$approvedDenial['count']?$approvedDenial['count']:"0"?></p> 
								<p><b>Approved Denial</b></p>
							</div>
						</div>
					</div>
					</a>
					<?php } ?>

					<?php if($rejectedDenial['count']==0 ) {?>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-danger panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;" class="ndf"><?=$rejectedDenial['count']?$rejectedDenial['count']:"0"?></p> 
								<p><b>Rejected Denial </b></p>
							</div>
						</div>
					</div>
                    <?php } else {?>
                        <a href="<?php echo base_url('MobiTradeReport/denial_details/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("4"));?>">
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-danger panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;"><?=$rejectedDenial['count']?$rejectedDenial['count']:"0"?></p> 
								<p><b>Rejected Denial </b></p>
							</div>
						</div>
					</div>
					</a>
					<?php } ?>
                </div>
                </div>
                </div>
				<?php }?>
                
    <!--End page content-->
	</div>
 </div>
<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>




<script>
$(".frmtodate").change(function(){ 
var from_date = $("#from_date").val();
var to_date = $("#to_date").val();
if(from_date > to_date)
{
    alert("From Date should not be greater then To Date");
    $("#to_date").val("");
}

});

$('#btn_search').click(function(){
	if($('#from_date').val()==null && $('#to_date').val()){
		return false;
	}else{
		$('#btn_search').html('Please Wait...');
		return true;
	}
})
</script>

 
 