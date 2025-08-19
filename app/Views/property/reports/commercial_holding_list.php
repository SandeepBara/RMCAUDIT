<?= $this->include('layout_vertical/header');?>
<style type="text/css">
	.error{
		color: red;
	}
    .buttons-page-length{
        display:none !important;
    }
</style>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
    
<!--CONTENT CONTAINER-->
<!--===================================================-->
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
    <li class="active">Commercial Holding List</li>
</ol>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--End breadcrumb-->
</div>
<!--Page content-->
<!--===================================================-->
		<div id="page-content">
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h5 class="panel-title">Search Parameter</h5>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" id="myform" method="get" >
						<div class="form-group">
							<div class="col-md-1">
								<label class="control-label" for="ward_mstr_id"><b>Ward No.</b><span class="text-danger"></span> </label>
							</div>
							<div class="col-md-2">
								<select name="ward_mstr_id" id="ward_mstr_id" class="form-control">
									<option value="">Select</option>
									<?php
									if($wardList):
										foreach($wardList as $val):
                                        ?>
                                            <option value="<?php echo $val['id'];?>" <?php if(isset($ward_mstr_id) && $ward_mstr_id==$val['id']){ echo "selected"; }?>><?php echo $val['ward_no'];?></option>
                                        <?php
                                        endforeach;
									endif;
									?>
								</select>
							</div>
							<div class="col-md-2">
								<button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h5 class="panel-title">List</h5>
				</div>
				<div class="panel-body table-responsive">
                
					<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Ward No.</th>
                                <th>15 Digits Holding No.</th>
                                <th>Owner Name.</th>
                                <th>Mobile No</th>
                                <th>Khata No</th>
                                <th>Plot No</th>
                                <th>Address</th>
                            </tr>
                        </thead>
						<tbody>
							<?php
							 if(isset($results)){
                                $count = $offset??0;
                                foreach($results as $key => $value){
                                    ?>
                                    <tr>
                                        <td><?=++$count;?></td>
                                        <td><?=$value["ward_no"];?></td>
                                        <td><?=$value["new_holding_no"];?></td>
                                        <td><?=$value["owner_name"];?></td>
                                        <td><?=$value["mobile_no"];?></td>
                                        <td><?=$value["khata_no"];?></td>
                                        <td><?=$value["plot_no"];?></td>
                                        <td><?=$value["prop_address"];?></td>
                                    </tr>
                                    <?php
                                }
                            }else{
                                ?>
                                <tr>
                                    <td colspan="8">Data Not Available!!</td>
                                </tr>
                            <?php
                            }
							?>
						</tbody>                          
					</table> 
                    <?= pagination(isset($pager)?$pager:0); ?>                  						
				</div>
			</div>
				
		<!--===================================================-->
		<!--End page content-->
		</div>
		<!--===================================================-->
		<!--END CONTENT CONTAINER-->


<?= $this->include('layout_vertical/footer');?>
<!--DataTables [ OPTIONAL ]-->

<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>
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
        "bLengthChange" : false, //thought this line could hide the LengthMenu
        "bInfo":false,
        "bLengthChange": false,
        "bPaginate": false,
        lengthMenu: false,        
        buttons: [
            'pageLength',
            {
                text: 'Excel Export',
                className: 'btn btn-primary',
                action: function ( e, dt, node, config ) {
                    var search_param = $('#ward_mstr_id').val(); // get selected ward_mstr_id
                    var gerUrl = '';

                    if (search_param) {
                        
                        gerUrl = '<?=base_url();?>/prop_report/commercial_holding_export/' + search_param;
                    } else {
                        
                        gerUrl = '<?=base_url();?>/prop_report/commercial_holding_export';
                    }

                    console.log("Search Params", search_param); 
                    window.open(gerUrl).opener = null; 
                }
            }, 
            {
                text: 'pdf',
                extend: "pdf",
                title: "Report",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3, 4, 5,6] }
            }
        ]
    });
});
</script>