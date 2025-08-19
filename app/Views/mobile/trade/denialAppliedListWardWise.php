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
<ol class="breadcrumb">
<li><a href="#"><i class="demo-pli-home"></i></a></li>
<li><a href="#">Trade</a></li>
<li class="active">Report</li>
<li class="active"><?=$denial?></li>
</ol>
</div>
                <div id="page-content">
					<div class="row">
					    <div class="col-sm-12">
							<div class="panel panel-bordered panel-dark">
								<div class="panel-heading">
                                    <div class="panel-control">
										<span class = "pull-right btn btn-info" onclick="history.back();"><i class='fa fa-arrow-left' aria-hidden='true'></i>Back</span>
									</div>
									<h3 class="panel-title"><?=$denial?></h3>
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
                                                <th>Total</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
										<?php
 										if(isset($denialDetails)):
											if(empty($denialDetails)):
										?>
                                            <tr>
                                                <td colspan="7" style="text-align: center;">Data Not Available!!</td>
                                            </tr>
										<?php else:
                                             $i=0;
                                            foreach ($denialDetails as $value):
										?>
                                            <tr>
                                                <td><?=++$i;?></td>
                                                <td><?=$value["ward_no"];?></td>
                                                <td><?=$value["count"];?></td> 
                                                <td>
												<?php if($value["count"]==0){?>
												<?php } else {?>
                                                 <a class="btn btn-primary" href="<?php echo base_url('MobiTradeReport/viewDetailsByWard/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($value['id']).'/'.base64_encode($status));?>" role="button">View</a>
                                                <?php } ?>
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
				title: "<?=$denial?>    <?=$from_date?>  -  <?=$to_date?>",
				footer: { text: '' },
				exportOptions: { columns: [ 0, 1,2] }
			}, {
				text: 'pdf',
				extend: "pdf",
				title: "<?=$denial?>       <?=$from_date?>  -  <?=$to_date?>",
				download: 'open',
				footer: { text: '' },
				exportOptions: { columns: [ 0, 1,2] }
			}]
		});
	});
 </script>

