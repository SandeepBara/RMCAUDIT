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
<li><a href="#">Water</a></li>
<li class="active">Search Consumer</li>
</ol>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--End breadcrumb-->
</div>
<!--Page content-->
<!--===================================================-->
		<div id="page-content">
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h5 class="panel-title">Search Consumer</h5>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" id="myform" method="get" >
						<div class="form-group">
							<div class="col-md-1">
								<label class="control-label" for="ward_id"><b>Ward No.</b><span class="text-danger">*</span> </label>
							</div>
							<div class="col-md-2">
								<select name="ward_id" id="ward_id" class="form-control">
									<option value="">Select</option>
									<?php
									if($ward_list):
										foreach($ward_list as $val):
									?>
									<option value="<?php echo $val['id'];?>" <?php if(isset($ward_id) && $ward_id==$val['id']){ echo "selected"; }?>><?php echo $val['ward_no'];?></option>
									<?php
									endforeach;
									endif;
									?>
								</select>
							</div>
							<div class="col-md-2">
								 <label class="control-label" for="keyword">
									 <b>Enter Keywords</b>
									 <i class="fa fa-info-circle" data-placement="bottom" data-toggle="tooltip" title="" data-original-title="Enter Owner Name or Mobile No. or Consumer No. or Holding No."></i>
									 <!-- <span class="text-danger">*</span> -->
								 </label>								 
							</div>                            
							<div class="col-md-3">								 
								 <input type="text" name="keyword" id="keyword" class="form-control" value="<?php echo $keyword ?? null; ?>" style="text-transform:uppercase" >
							</div>
                            <div class="col-md-2">
                                <input   style="margin-left:20px;" checked class="form-check-input valid" type="radio" name="connection_type" id="connection_type1" value="1" <?=$connection_type==1?"checked":''?>/>
                                <label class="form-check-label" for="inlineRadio1">Meter</label>&nbsp;
                                <input   class="form-check-input valid" type="radio" name="connection_type" id="connection_type2" value="3" <?=$connection_type==3?"checked":''?> />
                                <label class="form-check-label" for="inlineRadio2">Fixed</label>
                            </div>
							<div class="col-md-2">
								<!-- <label class="control-label" for="department_mstr_id">&nbsp;</label> -->
								<button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h5 class="panel-title">Consumer List</h5>
				</div>
				<div class="panel-body table-responsive">
                
					<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead class="bg-trans-dark text-dark">
							<tr>
								<th>S. No.</th>   
								<th>Ward No.</th>   
								<th>Consumer No.</th>
								<th>Category</th>
								<th>Applicant Name</th>
								<th>Mobile No.</th>
								<th>Address</th>
                                <th>Connection Type</th>
                                <th>Meter/Fixed Connection Date</th>
								<th>View</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if(isset($consumer_details['result']))
							{
								$i=$consumer_details['offset']??0;
								foreach($consumer_details['result'] as $val)
								{
									?>
									<tr>  
										<td><?=++$i; ?></td>
										<td><?= $val['ward_no']??'N/A';?></td>
										<td><?=$val['consumer_no']??'N/A';?></td>
										<td><?=$val['category']??'N/A';?></td>
										<td><?= $val['applicant_name']??'N/A';?></td>
										<td><?= $val['mobile_no']??'N/A';?></td>
										<td><?php echo(isset($val['address']) && !empty($val['address']) ? $val['address']:'N/A');?></td>
										<td><?= $val['connection_type']??'N/A';?></td>
                                        <td><?= $val['meter_connection_date']??'N/A';?></td>
                                        <td><a href="<?php echo base_url($view.md5($val['id']));?>" class="btn btn-primary btn-sm" target="blank">View</a></td>
									</tr>
									<?php
								}
								
							}
							?>
						</tbody>                          
					</table> 
                    <?=isset($consumer_details['count'])?pagination($consumer_details['count']):null;?>                   						
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
$(document).ready(function () 
{
    // $('#myform').validate({ // initialize the plugin
    //     rules: {
    //         ward_id: {
    //             required: "#keyword:blank",
    //         },
    //         keyword: {
    //             required: "#ward_id:blank",
    //         }
    //     }
    // });
});
</script>
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
                    var connection_type = $('input[name="connection_type"]:checked').val();
                    var keyword = $('#keyword').val();  
                    var ward_id =  $('#ward_id').val();
                    if (keyword=="") {
                        keyword = "@@@";
                    }
                    if (ward_id=="") {
                        ward_id = "All";
                    }
                    var gerUrl = ward_id+'/'+keyword+'/'+connection_type;
                    window.open('<?=base_url();?>/water_report/meter_non_meter_consumer_listExcel/'+gerUrl).opener = null;
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