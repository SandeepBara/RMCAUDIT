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
@media only screen and (max-width: 600px) {
	.rprt {
    height:126px;
  }
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
<div id="page-title">
</div>
<ol class="breadcrumb">
<li><a href="#"><i class="demo-pli-home"></i></a></li>
<li><a href="#">Trade</a></li>
 <li class="active">Ward Wise Denial Report</li>
</ol>
</div>
    <!--Page content-->
    <div id="page-content">
    <div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
						<span class = " panel-control pull-right btn btn-info btn-sm btn_wait_load " onclick="history.back();"><i class='fa fa-arrow-left' aria-hidden='true'></i>Back</span>
							<h5 class="panel-title">
								Search Ward Wise Denial Report
							</h5>
								
							
						</div>
						<div class="panel-body">
							<form class="form-horizontal" method="get" action="<?=base_url('');?>/MobiTradeReport/wardWiseDenialReport">
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
					<?php if($denialApply??null){?>
				<div class="row">

				<?php if($denialApply['count']==0) {?>
 					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-info panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;"><?=$denialApply['count']?$denialApply['count']:0;?></p>
								<p><b>Total Denial</b></p>
							</div>
						</div>
					</div>
 					<?php } else { ?>
						<a href="<?php echo base_url('MobiTradeReport/wardWiseDenialDetails/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("ttl"));?>">
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-info panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;"><?=$denialApply['count']?$denialApply['count']:0;?></p>
								<p><b>Total Denial</b></p>
							</div>
						</div>
					</div>
					</a>
					<?php } ?>

			
					<?php if($approvedDenial['count']==0) {?>
 					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-success panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;"><?=$approvedDenial['count']?$approvedDenial['count']:0;?></p>
								<p><b>Total Approved Denial</b></p>
							</div>
						</div>
					</div>
 					<?php } else { ?>
						<a href="<?php echo base_url('MobiTradeReport/wardWiseDenialDetails/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("5"));?>">
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-success panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;"><?=$approvedDenial['count']?$approvedDenial['count']:0;?></p>
								<p><b>Total Approved Denial</b></p>
							</div>
						</div>
					</div>
					</a>
					<?php } ?>
				
					<?php if($rejectedDenial['count']==0) {?>
 					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-danger panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;"><?=$rejectedDenial['count']?$rejectedDenial['count']:0;?></p>
								<p><b>Total Rejected Denial</b></p>
							</div>
						</div>
					</div>
 					<?php } else { ?>
						<a href="<?php echo base_url('MobiTradeReport/wardWiseDenialDetails/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("4"));?>">
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-danger panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;"><?=$rejectedDenial['count']?$rejectedDenial['count']:0;?></p>
								<p><b>Total Rejected Denial</b></p>
							</div>
						</div>
					</div>
					</a>
					<?php } ?>
                 
					<?php if($applyByNotice['count']==0) {?>
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-mint panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;"><?=$applyByNotice['count']?$applyByNotice['count']:0;?></p>
 								<p><b>Total Apply New Licence By Notice</b></p>
							</div>
						</div>
					</div>
					<?php } else { ?>
						<a href="<?php echo base_url('MobiTradeReport/wardWiseDenialDetails/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("2"));?>">
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					    <div class="panel panel-mint panel-colorful">
							<div class="pad-all text-center hght">
                            <p style="font-size:23px;"><?=$applyByNotice['count']?$applyByNotice['count']:0;?></p>
 								<p><b>Total Apply New Licence By Notice</b></p>
							</div>
						</div>
					</div>
					</a>
					<?php } ?>

                 </div>
				 <?php }?>

                    
    <!--End page content-->
	</div>
 </div>
<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>

<script>
	$('#btn_search').click(function(){

	if($('#from_date').val()==null && $('#to_date').val()){
		return false;
	}else{
		$('#btn_search').html('Please Wait...');
		return true;
	}
})
</script>