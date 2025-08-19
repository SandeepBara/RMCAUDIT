
<?= $this->include('layout_vertical/header');?>
<div id="content-container">
    <div id="page-head">
        <ol class="breadcrumb">
		<li><a href="#"><i class="demo-pli-home"></i></a></li>
		<li><a href="#">GBSAF</a></li>
		<li class="active">Search Application</li>
        </ol>
    </div>
	<div id="page-content">
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">Search Application </h3>
			</div>
			<div class="panel-body">
				<form method="post" >				
                    <div class="row">
                        <label for="keyword" class="col-md-2">Enter GBSAF No. </label>
                        <div class="col-md-3">
                            <input type="text" id="keyword" name="gbsafNo" class="form-control" placeholder="Enter GBSAF No" value="<?php echo $gbsafNo ?? null; ?>" />
                            
                        </div>
                        <div class="col-md-2 pad-btm">
                            <button type="submit" id="search" name="search" class="btn btn-primary">SEARCH</button>
                        </div>
                        <!-- <div class="col-md-2 pad-btm">
                            <button type="button" class="btn btn-info" data-target="#importModal" data-toggle="modal">Import</button>
                        </div>
                        <div class="col-md-2 pad-btm">
                            <button type="button" name="search" class="btn btn-primary" data-target="#listModal" data-toggle="modal">Report</button>
                        </div> -->
                    </div>
				</form>
            </div>
        </div>                    
			<?php
			if(isset($gbSaf))
			{
				?>
                    <div class="panel panel-bordered panel-dark">
                        <div class="panel-heading">
                            <h3 class="panel-title">Basic Detail </h3>
                        </div>
                        <div class="panel-body">
                            <form method="post" enctype="multipart/form-data" >
                                <input type="hidden" id="id" name="id" value="<?=$gbSaf['id'];?>" />
                                <div class="row pad-btm">
                                    <div class="col-md-3">
                                        <label>Ward No:</label>
                                        <strong><?=$gbSaf["ward_no"];?></strong>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Holding No : </label>
                                        <strong><?=$gbSaf["holding_no"];?></strong>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Address : </label>
                                        <strong><?=$gbSaf["building_colony_address"];?></strong>
                                    </div>
                                </div>
                                <div class="row pad-btm">
                                    <div class="col-md-6">
                                        <label>Owner Name:</label>
                                        <strong><?=$gbSaf["owner_name"];?></strong>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Contact No</label>
                                        <strong><?=$gbSaf["mobile_no"];?></strong>
                                    </div>
                                </div>
                                <div class="row pad-btm">
                                    <div class="col-md-12">
                                        <label>Office Name:</label>
                                        <strong><?=$gbSaf["office_name"];?></strong>
                                    </div>
                                </div>
                                <div class="row pad-btm">
                                    <label for="contact_person" class="col-md-2">Contact Person Name</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="contact_person" name="contact_person" value="<?=$gbSaf["contact_person"]??'';?>" />
                                    </div>
                                    <label for="contact_no" class="col-md-2">Contact No</label>
                                    <div class="col-md-4">
                                        <input type="tel" class="form-control" id="contact_no" name="contact_no" value="<?=$gbSaf["contact_no"]??'';?>" />
                                    </div>
                                </div>
                                <div class="row pad-btm">
                                    <label for="is_demand_served" class="col-md-2">Is Demand Served</label>
                                    <div class="col-md-4">
                                        <select class="form-control" id="is_demand_served" name="is_demand_served" >
                                            <option value="">Select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <label for="last_demand_served_date" class="col-md-2">Last Demand Serve Date</label>
                                    <div class="col-md-4">
                                        <input type="date" max="<?=date('Y-m-d');?>" class="form-control" id="last_demand_served_date" name="last_demand_served_date" />
                                    </div>
                                </div>
                                <div class="row pad-btm">
                                    <label for="is_demand_notice_served" class="col-md-2">Is Demand Notice Served</label>
                                    <div class="col-md-4">
                                        <select class="form-control" id="is_demand_notice_served" name="is_demand_notice_served" >
                                            <option value="">Select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <label for="last_demand_notice_served_date" class="col-md-2">Last Demand Notice Serve Date</label>
                                    <div class="col-md-4">
                                        <input type="date" max="<?=date('Y-m-d');?>" class="form-control" id="last_demand_notice_served_date" name="last_demand_notice_served_date" />
                                    </div>
                                </div>
                                <div class="row pad-btm">
                                    <label for="notice" class="col-md-2">Upload The Notice</label>
                                    <div class="col-md-4">
                                        <input type="file" accept=".jpg,.jpeg,.png,.gif,.pdf" class="form-control" id="notice" name="notice" />
                                    </div>
                                </div>
                                <div class="row pad-btm">
                                    <label for="is_payment_received" class="col-md-2">Payment Received end of the reporting Period</label>
                                    <div class="col-md-4">
                                        <select class="form-control" id="is_payment_received" name="is_payment_received" >
                                            <option value="">Select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <label for="upload_receipt_info" class="col-md-2">Upload the Receiving of Demand Letter and Demand Notice</label>
                                    <div class="col-md-4">
                                        <input type="file" accept=".jpg,.jpeg,.png,.gif,.pdf" class="form-control" id="upload_receipt_info" name="upload_receipt_info" />
                                    </div>
                                </div>
                                <div class="row pad-btm pull-right">
                                    <div class="col-md-12">
                                        <button type="submit" name="submit" id="submit" class="btn btn-sm btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
				<?php 
			}
			?>
	</div>
</div>

<?= $this->include('layout_vertical/footer');?>
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">

<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script>

</script>
