<?=$this->include("layout_mobi/header");?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url();?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
            <div id="page-head">

<!--Page Title-->
<div id="page-title">
</div>
<ol class="breadcrumb">
<li><a href="#"><i class="demo-pli-home"></i></a></li>
<li><a href="#">Trade</a></li>
<li class="active">Report</li>
<li class="active"><?=$application_type?></li>
</ol>
</div>
                <div id="page-content">
					<div class="row">
					    <div class="col-sm-12">
							<div class="panel panel-bordered panel-dark">
								<div class="panel-heading">
                                    <div class="panel-control">
										<a href="<?=base_url();?>/MobiTradeReport/applyLicenceReport" class="btn btn-default btn_wait_load">
										<i class="fa fa-arrow-left" aria-hidden="true"></i> Back
									   </a>
								    </div>
									<h3 class="panel-title">
									<div class="row">
										<div class="col-sm-4">
											<?=$application_type?>
										</div> 
										<div class="col-sm-4">
 									  </div>
                                    </div>
								   </h3>
								  </div>
                                <br>
								<div class="row" style="font-size: 15px;font-weight: 600;">
										<div class="col-sm-4">
 										</div> 
										<div class="col-sm-4">
											&nbsp;&nbsp;&nbsp;From Date:-<?=$from_date?>  - To Date:-<?=$to_date?>
									  </div>
								   </h3>
								</div>
								<div class="table-responsive" style="padding:5px;">
									<table id="demo_dt_basic" class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
										<thead class="bg-trans-dark text-dark">
											<tr>
												<th>#</th>
												<th>Ward No.</th>
												<th>Application No.</th>
												<th>License No.</th>
												<th>Firm Name</th>
 												<th>Application Type</th>
												 <?php if($paid??null){?>
												<th>Paid Amount </th>
												<?php } ?>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
										<?php
										if(isset($view_licence)):
										if(empty($view_licence)):
										?>
											<tr>
												<td colspan="7" style="text-align: center;">No Result Found!</td>
											</tr>
										<?php else:
											$i=0;
											$sum = 0;
											foreach ($view_licence as $value):
										?>
											<tr>
												<td><?=++$i;?></td>
												<td><?=$value["ward"];?></td>
												<td><?=$value["application_no"];?></td>
												<td><?=$value["license_no"]?$value["license_no"]:"N/A";?></td>
												<td><?=$value["firm_name"];?></td>
 												<td><?=$value["applicationType"];?></td>
												 <?php if($paid??null){?>
												<td><?=$value["paid_amount"];?></td>
												<?php } ?>
												<td>
													<a class="btn btn-primary btn_wait_load" href="<?php echo base_url('mobitradeapplylicence/trade_licence_view/'.md5($value['id']));?>" role="button">View</a>
												</td>
												<?php $sum += $value["paid_amount"]??0?>
											</tr>
										<?php endforeach;?>
 										<?php endif;  ?>
										<?php endif;  ?>
										</tbody>
									</table>
								</div>
							</div>
							<?php if($paid??null){?>	<h2>Total Amount Collection :- <?=number_format((float)$sum, 2, '.', '') ?> </h2><?php } ?>				
					    </div>
					</div>
                </div>
                <!--===================================================-->
                <!--End page content-->
            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>
<script src="<?=base_url();?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>

<script type="text/javascript">
     
     
	$(document).ready(function() {
		$('#demo_dt_basic').DataTable({
			responsive: true,
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
				title: "<?=$application_type?>    <?=$from_date?>  -  <?=$to_date?>",
				footer: { text: '' },
				exportOptions: { columns: [ 0, 1,2,3,4,5] }
			}, {
				text: 'pdf',
				extend: "pdf",
				title: "<?=$application_type?>       <?=$from_date?>  -  <?=$to_date?>",
				download: 'open',
				footer: { text: '' },
				exportOptions: { columns: [ 0, 1,2,3,4,5] }
			}]
		});
	});
 </script>
 
 
