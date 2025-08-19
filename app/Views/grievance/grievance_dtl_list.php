<?= $this->include('layout_vertical/header');?>
<style>
	.row{line-height: 25px;}
	.wardClass{font-size: medium; font-weight: bold;}
	#tdId{font-size: medium; font-weight: bold; text-align: right;}
	#tdRight{font-size: medium; font-weight: bold; text-align: right;color: #090f44;}
	#leftTd{font-size: medium; font-weight: bold; text-align: left;color: #090f44;}
	#left{font-size: medium; font-weight: bold; text-align: left;}

</style>
<!-- <style type="text/css" media="print">
.dontprint{ display:none}
</style> -->
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<script src="<?=base_url();?>/public/assets/otherJs/ExcelExport.js"></script>
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
                    <li><a href="#">Grievance</a></li>
                    <li class="active">Grievance List</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    <div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h5 class="panel-title">Grievance List</h5>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" method="post" action="<?=base_url('');?>/grievance_responce/grievance_list">
								<div class="row">
									<div class="col-md-10">
										<div class="col-md-4">
											<div class="col-md-5">
												<label class="control-label" for="from_date">From Date<span class="text-danger">*</span></label>
											</div>
											<div class="col-md-7">
												<input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?=(isset($from))?$from:date('Y-m-d');?>">
											</div>
										</div>
										<div class="col-md-4">
											<div class="col-md-5">
												<label class="control-label" for="to_date">To Date<span class="text-danger">*</span> </label>
											</div>
											<div class="col-md-7">
												<input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?=(isset($to))?$to:date('Y-m-d');?>">
											</div>
										</div>
										<div class="col-md-4">
											<div class="col-md-6">
												<label class="control-label" for="to_date">Grievance Type<span class="text-danger">*</span> </label>
											</div>
											<div class="col-md-6">
												<select name="grievance_type" id="grievance_type" class="form-control">
													<option value=""><?=(isset($type))?$type:"SELECT";?></option>
													<option value="Query">Query</option>
													<option value="Complain">Complain</option>
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<button type="submit" class="btn btn-primary btn-labeled" id="btn_grievance_list" name="btn_grievance_list">View List</button>
									</div>
								</div>
								
							</form>
						</div>
					</div>	
					<?php if($type=='Complain'){
					$i=1;?>
					<div class="panel panel-bordered panel-dark" id="printableArea">
						<div class="panel-heading">
							<h3 class="panel-title">Complain List</h3>
						</div><br/><br/>
						
						<div class="table-responsive">
							 <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>#</th>
										<th>Unique No.</th>
										<th>Module</th>
										<th>Ward No.</th>
										<th>Grievance Type</th>
										<th>Mobile No.</th>
										<th>Query</th>
										<th>Token No.</th>
										<th>Submit Document</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									
									<?php foreach($grievance_list as $value): ?>
									<tr>
										<td><?php echo $i++; ?></td>
										<td><?=$value['unique_no']?$value['unique_no']:"N/A"; ?></td>
										<td><?=$value['module']?$value['module']:"N/A"; ?></td>
										<td><?=$value['ward_no']?$value['ward_no']:"N/A"; ?></td>
										<td><?=$value['grievance']?$value['grievance']:"N/A"; ?></td>
										<td><?=$value['mobile_no']?$value['mobile_no']:"N/A"; ?></td>
										<td><?=$value['query']?$value['query']:"N/A"; ?></td>
										<td><?=$value['token_no']?$value['token_no']:"N/A"; ?></td>
										<td><a href="<?=base_url();?>/writable/uploads/<?=$value['doc_path'];?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a></td>
										<td>
											<?php if($value['status']==1){ ?>
												<b style="color:red;">Pending</b>
											<?php } else if($value['status']==2){ ?>
												<b style="color:#ec7a03;">Forward To Next Level</b>
											<?php } else if($value['status']==3){ ?>
												<b style="color:green;">Process Done</b>
											<?php } ?>
										</td>
										<td>
										<?php if($value['status']==1){ ?>
											<button type="button" class="btn btn-info" data-toggle="modal" title="Reply" data-target="#complain"><i class="fa fa-reply" style="font-size: 14px;color:green;"></i></button>
											<a href="<?php echo base_url('grievance_responce/grievance_forwardTl/'.$value['token_no']);?>" type="button" title="Forward to TL" class="btn btn-purple btn-labeled"><i class="fa fa-fast-forward"></i></a>
										<?php } else { ?>
											<b>---</b>
										<?php } ?>
										</td>
									</tr>
									<?php endforeach; ?>
									
								</tbody>
							</table>
						</div>
					</div>
					<?php } else if($type=='Query'){
					$i=1;?>
					<div class="panel panel-bordered panel-dark" id="printableArea">
						<div class="panel-heading">
							<h3 class="panel-title">Query List</h3>
						</div><br/><br/>
						
						<div class="table-responsive">
							 <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>#</th>
										<th>Query</th>
										<th>Mobile No.</th>
										<th>Token No.</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									
									<?php foreach($grievance_list as $value): ?>
									<tr>
										<td><?php echo $i++; ?></td>
										<td><?php echo $value['query']; ?></td>
										<td><?php echo $value['mobile_no']; ?></td>
										<td><?php echo $value['token_no']; ?></td>
										<td>
											<?php if($value['status']==1){ ?>
												<b style="color:red;">Pending</b>
											<?php } else if($value['status']==2){ ?>
												<b style="color:#ec7a03;">Forward To Next Level</b>
											<?php } else if($value['status']==3){ ?>
												<b style="color:green;">Process Done</b>
											<?php } ?>
										</td>
										<td>
										<?php if($value['status']==1){ ?>
											<button type="button" class="btn btn-info" data-toggle="modal" data-target="#query"><i class="fa fa-reply" title="Reply" style="font-size: 14px;color:green;"></i></button>
											<a href="<?php echo base_url('grievance_responce/grievance_forwardTl/'.$value['token_no']);?>" type="button" title="Forward to TL" class="btn btn-purple btn-labeled"><i class="fa fa-fast-forward"></i></a>
										<?php } else { ?>
											<b>---</b>
										<?php } ?>
										</td>
									</tr>
									<?php endforeach; ?>
									
								</tbody>
							</table>
						</div>
					</div>
					<?php } else { ?>
						<div class="col-md-12">				
							<span style="color:red;"> Data Are Not Available!!</span>
						</div>
					<?php } ?>
				</div>
			</div>
			
			<div class="modal fade" id="complain" role="dialog">
				<div class="modal-dialog">
				
				  <!-- Modal content-->
				  <div class="modal-content">
					<div class="modal-header">
					  <button type="button" class="close" data-dismiss="modal">&times;</button>
					  <h4 class="modal-title">Responce Panel</h4>
					</div>
					<div class="modal-body">
						<form class="form-horizontal" method="post" action="<?=base_url('');?>/grievance_responce/grievance_replay">
							<div class="row">
								<div class="col-md-3">
									<label class="control-label" for="from_date">Token No.<span class="text-danger">*</span></label>
								</div>
								<div class="col-md-9">
									<input type="text" id="token_no" name="token_no" class="form-control" value="<?php echo $value['token_no']; ?>">
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<label class="control-label" for="from_date">Grievance Type<span class="text-danger">*</span></label>
								</div>
								<div class="col-md-9">
									<input type="text" id="grievance_type" name="grievance_type" class="form-control" value="<?php echo $value['grievance']; ?>">
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<label class="control-label" for="from_date">Query<span class="text-danger">*</span></label>
								</div>
								<div class="col-md-9">
									<input type="text" id="query" name="query" class="form-control" value="<?php echo $value['query']; ?>">
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<label class="control-label" for="reply">Reply<span class="text-danger">*</span></label>
								</div>
								<div class="col-md-9">
									<textarea id="reply" name="reply" class="form-control" placeholder="Type Here"></textarea>
								</div>
							</div>
							<hr>
							<div class="row" style="text-align:center;">
								<button type="submit" class="btn btn-primary" id="grv_replay" name="grv_replay">Reply</button>
							</div>
						</form>
					</div>
					
				  </div>
				  
				</div>
			 </div>
			 
			 <div class="modal fade" id="query" role="dialog">
				<div class="modal-dialog">
				
				  <!-- Modal content-->
				  <div class="modal-content">
					<div class="modal-header">
					  <button type="button" class="close" data-dismiss="modal">&times;</button>
					  <h4 class="modal-title">Responce Panel</h4>
					</div>
					<div class="modal-body">
						
					</div>
					<div class="modal-footer">
					  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				  </div>
				  
				</div>
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
                exportOptions: { columns: [ 0, 1,2,3,4,5,6,7] }
            }, {
                text: 'pdf',
                extend: "pdf",
                title: "Report",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3,4,5,6,7] }
            }]
        });
    });
    $('#btn_harvest_report').click(function(){
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
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