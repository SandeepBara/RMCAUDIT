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
    <li class="active">New Assesed Commercial Holding</li>
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
				 <form class="form-horizontal" id="myForm" method="get">
                    <div class="col-md-12">
					<div class="row">
						<label class="col-md-1 text-bold">From Date</label>
						<div class="col-md-3 has-success pad-btm">
                        <input type="date" id="from_date" name="from_date" class="form-control" 
                            value="<?= isset($from_date) ? $from_date : date('Y-m-d'); ?>" />
						</div>
						<label class="col-md-1 text-bold">Upto Date</label>
						<div class="col-md-3 has-success pad-btm">
                        <input type="date" id="upto_date" name="upto_date" class="form-control" 
                            value="<?= isset($upto_date) ? $upto_date : date('Y-m-d'); ?>" />
						</div>
                        <label class="col-md-1 text-bold">Ward No </label>
						<div class="col-md-3 has-success pad-btm">
                        <select id='ward_mstr_id' name="ward_mstr_id" class="form-control">
								<option value=''>ALL</option>
							<?php
							if (isset($wardList))
                            {
								foreach($wardList as $val):
                                    ?>
                                        <option value="<?php echo $val['id'];?>" <?php if(isset($ward_mstr_id) && $ward_mstr_id==$val['id']){ echo "selected"; }?>><?php echo $val['ward_no'];?></option>
                                    <?php
                                    endforeach;
							}
							?>
							</select>
						</div>
					</div>
					
					<div class="row">
						
						<div class="col-md-5 text-right">
							<input type="submit" name="search" id="btn_search" class="btn btn-primary" value="SEARCH" />                                
						</div>
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
                                <th>SAF No</th>
                                <th>Owner Name.</th>
                                <th>Mobile No</th>
                                <th>Khata No</th>
                                <th>Plot No</th>
                                <th>Address</th>
                                <th>Apply Date</th>
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
                                        <td><?=$value["saf_no"];?></td>
                                        <td><?=$value["owner_name"];?></td>
                                        <td><?=$value["mobile_no"];?></td>
                                        <td><?=$value["khata_no"];?></td>
                                        <td><?=$value["plot_no"];?></td>
                                        <td><?=$value["prop_address"];?></td>
                                        <td><?=$value["saf_apply_date"];?></td>
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

                    var gerUrl = '<?=base_url();?>/prop_report/new_commercial_assesment?export=true&';
                    var formData = $("#myForm").serializeArray();
                        $.each(formData, function(i, field) {
                            gerUrl += (field.name+'='+field.value)+"&";
                        });
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