<?= $this->include('layout_vertical/header');?>

<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
       
        <div class="panel panel-dark">
            <div class="panel-heading">
				<div class="panel-control">
					<a href="<?php echo base_url('TradeApplyLicenseReports/report');?>" class="btn btn-default">Back</a>
				</div>
                <h5 class="panel-title">New Licence List</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="empTable" class="table table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>                                        
                                        <th>Ward No.</th>
                                        <th>Application No.</th>
										<th>Licence No.</th>
                                        <th>Firm Name</th>
                                        <th>Area In Sqft</th>
                                        <th>Address</th>
                                        <th>Apply Date</th>
                                        <th>View</th>     
                                    </tr>
                                </thead>
                                <tbody>
								<?php $i=1; ?>
								<?php foreach($newapplyLicense as $newapplyLicense): ?>
									<tr>
										<td><?=$i++; ?></td> 
                                        <td><?=$newapplyLicense['ward_mstr_id']; ?></td>
                                        <td><?=$newapplyLicense['application_no']; ?></td>
										<td><?=$newapplyLicense['provisional_license_no']; ?></td>
                                        <td><?=$newapplyLicense['firm_name']; ?></td>
                                        <td><?=$newapplyLicense['area_in_sqft']; ?></td>
                                        <td><?=$newapplyLicense['address']; ?></td>
                                        <td><?=$newapplyLicense['apply_date']; ?></td>
                                        <td><a href="<?php echo base_url('tradeapplylicence/trade_licence_view/'.md5($newapplyLicense['id']));?>" type="button" class="btn btn-primary btn-labeled">View Details</a></td>
                                    </tr>
								<?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<!--DataTables [ OPTIONAL ]-->
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
