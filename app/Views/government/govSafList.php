
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
				<form method="get" >
				<div class="row">
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <div class="radio">
                            <input type="radio" id="by_app_gbsaf" class="magic-radio" name="by_app" value="GBSAF" <?= isset($by_app) ? (strtolower($by_app) == "gbsaf") ? "checked" : "" : "checked"; ?> >
                            <label for="by_app_gbsaf">By GBSAF</label>

                            <input type="radio" id="by_app_csaf" class="magic-radio" name="by_app" value="CSAF" <?= (isset($by_app) && strtolower($by_app) == "csaf") ? "checked" : ""; ?> >
                            <label for="by_app_csaf">By CSAF</label>
                        </div>
                    </div>
                </div>
				<div class="row">
				
					<div class="col-md-1">
						<label for="ward_mstr_id">Ward No.</label>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<select id="ward_mstr_id" name="ward_mstr_id" class="form-control m-t-xxs" >
								<option value="">Select</option>
								<?php if($ward): ?>
								<?php foreach($ward as $post): ?>
								<option value="<?=$post['id']?>" <?=(isset($ward_mstr_id))?$ward_mstr_id==$post["id"]?"SELECTED":"":"";?>><?=$post['ward_no'];?></option>
								<?php endforeach; ?>
								<?php endif; ?>
							</select>
						</div>
					</div>

                    <div class="col-md-1">
						<label for="government_type">Government Type</label>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<select id="government_type" name="government_type" class="form-control m-t-xxs" >
								<option value="">Select</option>
                                <option value="CENTRAL GOVERNMENT" <?=(isset($government_type))?$government_type=="CENTRAL GOVERNMENT"?"SELECTED":"":"";?> >CENTRAL GOVERNMENT</option>
                                <option value="STATE GOVERNMENT" <?=(isset($government_type))?$government_type=="STATE GOVERNMENT"?"SELECTED":"":"";?> >STATE GOVERNMENT</option>								
							</select>
						</div>
					</div>
				
					<div class="col-md-2">
						<label for="keyword">Enter Keyword </label>
						<i class="fa fa-info-circle" data-placement="bottom" data-toggle="tooltip" title="" data-original-title="Enter Application No. Or Colony"></i>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<input type="text" id="keyword" name="keyword" class="form-control" style="height:38px;" placeholder="Enter Keyword" value="<?php echo $keyword ?? null; ?>" />
						</div>
					</div>
					<div class="col-md-3 pad-btm">
						<button type="submit" id="search" name="search" class="btn btn-primary">SEARCH</button>
					</div>
				</div>
				</form>
            </div>
        </div>                    
			<?php
			if(isset($govSaf_details))
			{
				?>
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title">Search Result</h3>
					</div>
					<div class="panel-body">
						<div id="saf_distributed_dtl_hide_show">
							<div class="row">
								<div class="col-md-12">
									<div class="table-responsive">
										<table id="demo_dt_basic" class="table table-striped table-bordered"  width="100%" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
											<thead class="bg-trans-dark text-dark">
												
													<th>Sl No. </th>
													<th>Ward No </th>
                                                    <th>New Ward No </th>
													<th>Application No </th>
													<th>Office Name </th>
													<th>Address </th>
													<th>Assessment Type </th>
													<th>Building Colony Name</th>
													<th>Application Type </th>
                                                    <th>Building Type </th>
                                                    <th>Gov Type </th>
                                                    <th>Balance </th>
													<th>Action </th>
											</thead>
											<tbody>
												<?php if($govSaf_details):
												$i=0;  
                                                ?>
												<?php foreach($govSaf_details as $post): ?>
													
												<tr>
													<td><?php echo ++$i; ?></td>
													<td><?=$post['ward_no']?$post['ward_no']:"N/A"; ?></td>
                                                    <td><?=$post['new_ward_no']?$post['new_ward_no']:"N/A"; ?></td>
													<td><?=$post['application_no']?$post['application_no']:"N/A"; ?></td>
													<td><?=$post['office_name']?$post['office_name']:"N/A"; ?></td>
													<td><?=$post['building_colony_address']?$post['building_colony_address']:"N/A"; ?></td>
													<td><?=$post['assessment_type']?$post['assessment_type']:"N/A"; ?></td>
													<td><?=$post['building_colony_name']?$post['building_colony_name']:"N/A"; ?></td>
													<td><?=$post['application_type']?$post['application_type']:"N/A"; ?></td>
                                                    <td><?=$post['building_type']?$post['building_type']:"N/A"; ?></td>
                                                    <td><?=$post['gov_type']?$post['gov_type']:"N/A"; ?></td>
                                                    <td><?=$post['balance']?$post['balance']:"0"; ?></td>
													<td>
														<a href="<?php echo base_url('govsafDetailPayment/gov_saf_application_details/'.md5($post['id']));?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">View</a>
                                                    </td>
												</tr>
												<?php endforeach; ?>
												<?php else: ?>
												<tr>
													<td colspan="8" style="text-align:center;color:red;"> Data Are Not Available!!</td>
												</tr>

												<?php endif; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
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
    $(document).ready(function(){
        $('#demo_dt_basic').DataTable({
            responsive: false,
            dom: 'Bfrtip',
            lengthMenu: [
                [ 10, 25, 50, -1 ],
                [ '10 rows', '25 rows', '50 rows', 'Show all' ]
            ],
            buttons: [
                'pageLength',
                {
                text: 'excel',
                extend: "excel",
                
                title: "Report",
                footer: { text: '' },
                exportOptions: { columns: [ 0,1,2,3,4,5,6,7,8,9,10,11] }
            }, {
                text: 'pdf',
                extend: "pdfHtml5",
                title: "Report",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0,1,2,3,4,5,6,7,8,9,10,11] }
            }]
        });
    });
</script>
