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
                    <li><a href="#">Water</a></li>
                    <li><a href="#">Water Connection</a></li>
                    <li class="active"> Applied Connection </li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-bordered panel-dark">
                                <div class="panel-heading">
                                    <h5 class="panel-title">Connection apply List</h5>
                                </div>
                                <div class="panel-body">
                                    <div class ="row">
                                        <div class="col-md-12">
                                            <form class="form-horizontal" method="post" action="<?=base_url("WaterConnectionApply/report");?>">
                                                <div class="form-group">
                                                    <div class="col-md-2">
														<label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
														<input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
													</div>
													<div class="col-md-2">
														<label class="control-label" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
														<input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
													</div>
													<div class="col-md-2">
														<label class="control-label" for="Ward"><b>Ward No</b><span class="text-danger">*</span> </label>
														<select id="ward_id" name="ward_id" class="form-control">
														   <option value="">ALL</option>  
															<?php foreach($wardList as $value):?>
															<option value="<?=$value['id']?>" <?=(isset($ward_id))?$ward_id==$value["id"]?"SELECTED":"":"";?>><?=$value['ward_no'];?>
															</option>
															<?php endforeach;?>
														</select>
													</div>
                                                    <div class="col-md-4">
														<label class="control-label" for="Ward"><b>Pending No </b><span class="text-danger">*</span> </label>
														<!-- <select id="ward_id" name="ward_id" class="form-control">
														   <option value="">ALL</option>  
															<?php foreach($wardList as $value):?>
															<option value="<?=$value['id']?>" <?=(isset($ward_id))?$ward_id==$value["id"]?"SELECTED":"":"";?>><?=$value['ward_no'];?>
															</option>
															<?php endforeach;?>
														</select> -->
                                                        <select id="pending_on" name="pending_on" class="form-control">
														   <option value="All" <?=(isset($pending_on))?($pending_on=='All'?"SELECTED":""):"";?>>All</option> 
														   <option value="Payment Done But Document Upload Is Pending" <?=(isset($pending_on))?($pending_on=='Payment Done But Document Upload Is Pending'?"SELECTED":""):"";?>>Payment Done But Document Upload Is Pending</option>
                                                           <!-- <option value="Payment Done And Document Done" <?=(isset($pending_on))?($pending_on=='Payment Done And Document Done'?"SELECTED":""):"";?>>Payment Done And Document Done</option> -->
														   <option value="Payment Is Pending But Document Upload Done" <?=(isset($pending_on))?($pending_on=='Payment Is Pending But Document Upload Done'?"SELECTED":""):"";?> >Payment Is Pending But Document Upload Done  </option> 
														   <option value="Payment Pending And Document Upload Pending" <?=(isset($pending_on))?($pending_on=='Payment Pending And Document Upload Pending'?"SELECTED":""):"";?>>Payment Pending And Document Upload Pending</option>
                                                           <!-- <option value="2/0" <?=(isset($pending_on))?($pending_on=='2/0'?"SELECTED":""):"";?>>Payment is not clear And Document Upload Pending</option>
                                                           <option value="2/1" <?=(isset($pending_on))?($pending_on=='2/1'?"SELECTED":""):"";?>>Payment is not clear And Document Done</option> -->
														</select>
													</div>
													<div class="col-md-2">
														<label class="control-label" for="department_mstr_id">&nbsp;</label>
														<button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
													</div>
												</div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="row">
                                    <div class="" >
                                        <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Ward No</th>
                                                <th>Application No</th>
                                                <th>Consumer Name</th>
                                                <th>Mobile No</th>
                                                <th>Apply Date</th>
                                                <th>Apply By</th>
                                                <th>View</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        
                                        if(!isset($applyList)):
                                        ?>
                                                <tr>
                                                    <td colspan="8" style="text-align: center;">Data Not Available!!</td>
                                                </tr>
                                        <?php else:
                                                $i=$applyList['offset'];

                                                foreach ($applyList['result'] as $value):
                                        ?>
                                                <tr>
                                                    <td><?=++$i;?></td>
                                                    <td><?=$value['ward_no'];?></td>
                                                    <td><?=$value['application_no'];?></td>
                                                    <td><?=$value['applicant_name'];?></td>
                                                    <td><?=$value['mobile_no'];?></td>
                                                    <td><?=date('d-m-Y', strtotime($value['apply_date']));?></td>
                                                    <td><?=$value['emp_name'];?></td>
                                                    <td><a href="<?=base_url("WaterApplyNewConnection/water_connection_view/".md5($value['id'])); ?>" class="btn btn-primary">View</a></td>
                                                </tr>

                                            <?php endforeach;?>
                                        <?php endif;  ?>
                                        </tbody>
                                    </table>
                                    </div>
                                    <?=isset($applyList['count'])?pagination($applyList['count']):null;?>
                                    </div>
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
                exportOptions: { columns: [ 0,1,2,3,4,5,6,7,8,9] }
            }, {
                text: 'pdf',
                extend: "pdf",
                title: "Report",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0,1,2,3,4,5,6,7,8,9] }
            }]
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
    });

</script>