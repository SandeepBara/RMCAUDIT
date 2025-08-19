<?=$this->include("layout_mobi/header");?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url();?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">

<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
            <div id="page-head">

<!--Page Title-->
<div id="page-title">
</div>
<?php 
if(isset($_SESSION['emp_details']['user_type_mstr_id']) && $_SESSION['emp_details']['user_type_mstr_id']!=5)
{
?>
    <ol class="breadcrumb">
    <li><a href="#"><i class="demo-pli-home"></i></a></li>
    <li><a href="#">Trade</a></li>
    <li class="active">Report</li>
    <li class="active"><?=$wcon?></li>
<?php
}
?>
</ol>
</div>
                <div id="page-content">
					<div class="row">
					    <div class="col-sm-12">
							<div class="panel panel-bordered panel-dark">
								<div class="panel-heading">
                                    <div class="panel-control">
                                        <a class="panel-control btn-info btn-sm pull-right" href ="#" onclick="history.back();"><i class="fa fa-arrow-left" aria-hidden="true"></i>Back</a>
                                    </div>
									<h3 class="panel-title"><?=$wcon?></h3>
								</div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                   <b> From Date:-  <?=$from_date?>  - To Date:- <?=$to_date?>&nbsp;&nbsp;&nbsp;</b>
                                    </div>
                                </div>
								<div class="table-responsive" style="padding:5px;">
                                <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Ward</th>
                                                <th>Application No</th>
                                                <th>Owner Name</th>
                                                <th>Property Type</th>
                                                <th>Connection Type</th>
                                                <th>Apply Date </th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
										<?php
 										if(isset($waterConnectionDetailsByWard)):
											if(empty($waterConnectionDetailsByWard)):
										?>
                                            <tr>
                                                <td colspan="7" style="text-align: center;">Data Not Available!!</td>
                                            </tr>
										<?php else:
                                             $i=0;
                                            foreach ($waterConnectionDetailsByWard as $value):
										?>
                                            <tr>
                                                 <td><?=++$i;?></td>
                                                <td><?=$value["ward_no"];?></td>
                                                <td><?=$value["application_no"];?></td>
                                                <td><?=$value["applicant_name"];?></td>
                                                <td><?=$value["property_type"];?></td> 
                                                <td><?=$value["connection_type"];?></td>
                                                <td><?=$value["apply_date"];?></td>      
                                                <td>
                                                    <!-- <a class="btn btn-primary" href="<?php echo base_url('WaterApplyNewConnection/water_connectionView/'.md5($value['id']));?>" role="button">View</a> -->
                                                    <a class="btn btn-primary" href="<?php echo base_url('WaterApplyNewConnectionMobi/water_connection_view/'.md5($value['id']));?>" role="button">View</a>
                                                </td>
                                             </tr>
                                        <?php endforeach;?>
                                        <?php endif;  ?>
										<?php endif;  ?>
 					                    </tbody>
					                </table>
								</div>
							</div>
							<?php if($paid??null){?>	<h2>Total Amount Collection :- <?=number_format((float)($sum??0), 2, '.', '') ?> </h2><?php } ?>				
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
				title: "<?=$denial??null?>    <?=$from_date??null?>  -  <?=$to_date??null?>",
				footer: { text: '' },
				exportOptions: { columns: [ 0, 1,2,3] }
			}, {
				text: 'pdf',
				extend: "pdf",
				title: "<?=$denial??null?>       <?=$from_date??null?>  -  <?=$to_date??null?>",
				download: 'open',
				footer: { text: '' },
				exportOptions: { columns: [ 0, 1,2,3] }
			}]
		});
	});
 </script>

