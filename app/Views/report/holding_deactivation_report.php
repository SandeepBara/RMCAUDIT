<?= $this->include('layout_vertical/header');?>
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
                    <li><a href="#">Report</a></li>
                    <li class="active">Holding Deactivation List</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h5 class="panel-title">Holding Deactivation List</h5>
						</div>
						<div class="panel-body">
							<div class ="row">
								<div class="col-md-12">
									<form class="form-horizontal" method="post" action="<?=base_url('');?>/HoldingDeactivationReport/detail">
										<div class="form-group">
											<div class="col-md-3">
												<label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
												<div class="input-group">
													<input type="date" id="from_date" name="from_date" class="form-control" style="width:230px;" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
												</div>
											</div>
											<div class="col-md-3">
												<label class="control-label" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
												<div class="input-group">
													<input type="date" id="to_date" name="to_date" class="form-control" style="width:230px;" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
												</div>
											</div>
											<div class="col-md-3">
												<label class="control-label" for="Ward"><b>Ward</b><span class="text-danger">*</span> </label>
												<select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
												   <option value="">ALL</option>  
													<?php foreach($wardList as $value):?>
													<option value="<?=$value['id']?>" <?=(isset($ward_mstr_id))?$ward_mstr_id==$value["id"]?"SELECTED":"":"";?>><?=$value['ward_no'];?>
													</option>
													<?php endforeach;?>
												</select>
											</div>
											<div class="col-md-3">
												<label class="control-label" for="department_mstr_id">&nbsp;</label>
												<button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="row">
								<div class="table-responsive">
									<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th>#</th>
												<th>Deactivation Date</th>
												<th>Holding No</th>
												<th>Ward No</th>
												<th>Owner Name</th>
												<th>Relation</th>
												<th>Guardian Name</th>
												<th>Mobile No</th>
												<th>PAN No</th>
												<th>Aadhar No</th>
												<th>Document</th>
											</tr>
										</thead>
										<tbody>
										<?php
										if(!isset($holdingDeactivationList)):
										?>
											<tr>
												<td colspan="11" style="text-align: center;">Data Not Available!!</td>
											</tr>
										<?php else:
											$i=0;
											foreach ($holdingDeactivationList as $value):
										?>
											<tr>
												<td><?=++$i;?></td>
												<td><?=$value['deactivation_date']!=""?date('d-m-Y',strtotime($value['deactivation_date'])):"N/A";?></td>
												<td><?=$value['holding_no']!=""?$value['holding_no']:"N/A";?></td>
												<td>
													<?=$value['ward_no']!=""?$value['ward_no']:"N/A";?>
												</td>
												<td><?=$value['owner']!=""?$value['owner']:"N/A";?></td>
												<td><?=$value['ownerDetails']['relation_type']!=""?$value['ownerDetails']['relation_type']:"N/A";?></td>
												<td><?=$value['guardian']!=""?$value['guardian']:"N/A";?></td>
												<td><?=$value['ownerDetails']['mobile_no']!=""?$value['ownerDetails']['mobile_no']:"N/A";?></td>
												<td><?=$value['ownerDetails']['pan_no']!=""?$value['ownerDetails']['pan_no']:"N/A";?></td>
												<td><?=$value['ownerDetails']['aadhar_no']!=""?$value['ownerDetails']['aadhar_no']:"N/A";?></td>
												<td><a href="<?=base_url();?>/writable/uploads/RANCHI/holding_deactivate_doc/<?=$value['doc_path'];?>" target="_blank" ><img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a></td>
											</tr>
										<?php endforeach;?>
										<?php endif;  ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
                </div>
                <!--===================================================-->
                <!--End page content-->
            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<!--DataTables [ OPTIONAL ]-->
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
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
                title: "Report",
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3,4,5,6,7,8,9] }
            }, {
                text: 'pdf',
                extend: "pdf",
                title: "Report",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3,4,5,6,7,8,9] }
            }]
        });
    });
    $('#btn_search').click(function(){
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    if(from_date=="")
    {
        $("#from_date").css({"border-color":"red"});
        $("#from_date").focus();
        return false;
    }
    if(to_date=="")
    {
        $("#to_date").css({"border-color":"red"});
        $("#to_date").focus();
        return false;
    }
    if(to_date<from_date)
    {
        alert("To Date Should Be Greater Than Or Equals To From Date");
        $("#to_date").css({"border-color":"red"});
        $("#to_date").focus();
        return false;
    }
});
$("#from_date").change(function(){$(this).css('border-color','');});
$("#to_date").change(function(){$(this).css('border-color','');});
</script>