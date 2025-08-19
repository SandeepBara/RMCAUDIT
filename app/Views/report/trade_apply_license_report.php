<?= $this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->
 <link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
   <style>
   .dataTables_filter, .dataTables_info { display: none; }
   tbody th{
	   font-weight:100!important;
	   line-height:32px!important;
 	 }
	 
   tbody tr{
	   height:50px;
	 }

 thead th{
	   font-weight:100!important;
	   line-height:30 px;
	 }

 thead tr {
		  height:50px;
	 }

 .vl {
  border-left: 2px solid #8ca1b7;
  height: 5px;
}
</style> 
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
                    <li class="active">Trade License </li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h5 class="panel-title"> Trade License </h5>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" method="post" action="<?=base_url('');?>/TradeApplyLicenseReports/report">
								<div class="col-md-3">
									<label class="control-label" for="from_date"><b>From Date</b><span class="text-danger">*</span> </label>
									<input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?=(isset($to_date))?$from_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
								</div>
								<div class="col-md-3">
									<label class="control-label" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
									<input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
								</div>
								<div class="col-md-3">
									<label class="control-label" for="to_date"><b>Ward No.</b><span class="text-danger">*</span></label>
									<select name="ward_id" id="ward_id" class="form-control">
										<option value="all">All</option>
										<?php
										if($ward_list):
										foreach($ward_list as $val):
										?>
										<option value="<?php echo $val['id'];?>" <?php if($ward_id==$val['id']){ echo "selected"; }?>><?php echo $val['ward_no'];?></option>
										<?php
										endforeach;
										endif;
										?>
									</select>
								</div>
								<div class="col-md-2">
									<label class="control-label" for="department_mstr_id">&nbsp;</label>
									<button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit" >Search</button>
								</div>
							</form>
						</div>
					</div>
					 
			<div class="panel panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Municipal Licence Application Details</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="demo_dt_basic" class="table">
                                <thead>
                                    <tr>
                                        <th>Ward No.   :- <b><?=$ward_id_name; ?></b></th>
                                        <th>From Date :-<b> <?=date("d-m-Y", strtotime($from_date))?></b></th> 
                                        <th>To Date :-<b> <?=date("d-m-Y", strtotime($to_date))?></b></th>
                                     </tr>
                                 </thead>
                                <tbody id="total_rocord">
								      <tr>
                                        <?php if($newapplyLicense['count']==0){?>
                                        <th>New License Request :- <b><?=$newapplyLicense['count']; ?></b></th>
                                        <?php }else{?>
                                        <th>New License Request :- <a href="<?php echo base_url('TradeApplyLicenseReports/ward_wise_details/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("1"));?>"><b><?=$newapplyLicense['count']; ?></b></a></th>
                                        <?php }?>
                                        <?php if($renewapplyLicense['count']==0){?>
                                        <th>Renewal License Request :- <b><?=$renewapplyLicense['count']; ?></b></th>
                                        <?php }else{?>
                                        <th>Renewal License Request :- <a href="<?php echo base_url('TradeApplyLicenseReports/ward_wise_details/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("2"));?>"><b><?=$renewapplyLicense['count']; ?></b></a></th>
                                        <?php }?>
                                        <?php if($amendapplyLicense['count']==0){?>
										<th>Amendment License Request :- <b><?=$amendapplyLicense['count']; ?></b></th>
                                        <?php }else{?>
                                        <th>Amendment License Request :- <a href="<?php echo base_url('TradeApplyLicenseReports/ward_wise_details/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("3"));?>"><b><?=$amendapplyLicense['count']; ?></b></a></th>
                                        <?php }?>
                                     </tr>
                                    <tr>
									
                                        <?php if($surrendapplyLicense['count']==0){?>
                                        <th>Surrender License Request :- <b><?=$surrendapplyLicense['count']; ?></b></th>
										<?php }else{?>
                                        <th>Surrender License Request :- <a href="<?php echo base_url('TradeApplyLicenseReports/ward_wise_details/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("4"));?>"><b><?=$surrendapplyLicense['count']; ?></b></a></th>
                                        <?php }?>
                                        <?php if($totalapplyLicense==0){?>
                                        <th>Total Application Request :- <b><?=$totalapplyLicense; ?></b></th>
										<?php }else{?>
                                        <th>Total Application Request :- <a><b><?=$totalapplyLicense; ?></b></a></th>
                                        <?php }?>
                                          <?php if($total_rejected_form['count']==0){?>
                                        <th>Total Rejected Form :- <b><?=$total_rejected_form['count']; ?></b></th>
										<?php }else{?>
                                        <th>Total Rejected Form :- <a href="<?php echo base_url('TradeApplyLicenseReports/ward_wise_details/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("rej"));?>"><b><?=$total_rejected_form['count']; ?></b></a></th>
                                        <?php }?>
                                     </tr>
									 <tr>
                                        <?php if($pendingapplyLicense['count']==0){?>
                                        <th>Pending At Level :- <b><?=$pendingapplyLicense['count']; ?></b></th>		
								        <?php }else{?>
                                        <th>Pending At Level :- <a href="<?php echo base_url('TradeApplyLicenseReports/pendingAtLevelcount/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id));?>"><b><?=$pendingapplyLicense['count']; ?></b></a></th>
                                        <?php }?>
                                        <?php if($backapplyLicense['count']==0){?>
                                        <th>Back To Citizen :- <b><?=$backapplyLicense['count']; ?></b></th>
										<?php }else{?>
                                        <th>Back To Citizen :- <a href="<?php echo base_url('TradeApplyLicenseReports/ward_wise_details/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("bo"));?>"><b><?=$backapplyLicense['count']; ?></b></a></th>
                                        <?php }?>
                                        <?php if($pndjskapplyLicense['count']==0){?>
                                        <th>Pending At JSK :- <b><?=$pndjskapplyLicense['count']; ?></b></th>
										<?php }else{?>
                                        <th>Pending At JSK :- <a href="<?php echo base_url('TradeApplyLicenseReports/ward_wise_details/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("jsk"));?>"><b><?=$pndjskapplyLicense['count']; ?></b></a></th>
                                        <?php }?>
                                      </tr>
									 <tr>
                                     <?php if($newapplyLicense['count']==0){?>
                                        <th>Provisional Licence :- <b><b><?=$totalprovisional['count']; ?></b></b></th>
                                        <?php }else{?>
                                        <th>  Provisional Licence :- <a href="<?php echo base_url('TradeApplyLicenseReports/ward_wise_details/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("prov"));?>"><b><b><?=$totalprovisional['count']; ?></b></b></a></th>
                                        <?php }?>
                                        <?php if($finalapplyLicense['count']==0){?>
                                        <th>Final License :- <b><?=$finalapplyLicense['count']; ?></b></th>
										<?php }else{?>
                                        <th>Final License :- <a href="<?php echo base_url('TradeApplyLicenseReports/ward_wise_details/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("5"));?>"><b><?=$finalapplyLicense['count']; ?></b></a></th>
                                        <?php }?>
                                        <?php if($pndbocapplyLicense['count']==0){?>
                                        <th>Pending At Back Office :- <b><?=$pndbocapplyLicense['count']; ?></b></th>
										<?php }else{?>
                                        <th>Pending At Back Office :- <a href="<?php echo base_url('TradeApplyLicenseReports/ward_wise_details/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("bco"));?>"><b><?=$pndbocapplyLicense['count']; ?></b></a></th>
                                        <?php }?>
										 <th></th>
                                     </tr>
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Municipal Licence Collection Details</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="collection" class="table">
                                <thead>
                                    <tr>
                                        <th>Ward No.   :- <b><?=$ward_id_name; ?></b></th>
                                        <th>From Date :-<b> <?=date("d-m-Y", strtotime($from_date))?></b> &nbsp;&nbsp;           To Date :-<b> <?=date("d-m-Y", strtotime($to_date))?></b></th> 
                                        
                                     </tr>
                                 </thead>
                                <tbody id="total_rocord">
								      <tr>
                                        <?php if($newapplyLicense['count']==0){?>
                                        <th>New License Request :- <b><?=$newapplyLicense['count']; ?></b></th>
                                        <?php }else{?>
                                        <th>New License Request :- <a href="<?php echo base_url('TradeApplyLicenseReports/ward_wise_collection_details/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("1"));?>"><b><?=$newapplyLicense['count']; ?></b></a> 
                                        &nbsp;<a class="vl"></a> &nbsp;Total Amount Collection :- <a href="<?php echo base_url('TradeApplyLicenseReports/ward_wise_collection_details/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("1"));?>"><b><?=$newlicencecollection['sum']?$newlicencecollection['sum']:0?></b></a></th>
                                        <?php }?>
                                        <?php if($renewapplyLicense['count']==0){?>
                                        <th>Renewal License Request :- <b><?=$renewapplyLicense['count']; ?></b></th>
                                        <?php }else{?>
                                        <th>Renewal License Request :- <a href="<?php echo base_url('TradeApplyLicenseReports/ward_wise_collection_details/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("2"));?>"><b><?=$renewapplyLicense['count']; ?></b></a>
                                        &nbsp;<a class="vl"></a> &nbsp;Total Amount Collection :- <a href="<?php echo base_url('TradeApplyLicenseReports/ward_wise_collection_details/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("2"));?>"><b><?=$renewlicencecollection['sum']?$renewlicencecollection['sum']:0?></b></a></th>
                                        <?php }?>
                                     </tr>
                                    <tr>
                                        <?php if($amendapplyLicense['count']==0){?>
										<th>Amendment License Request :- <b><?=$amendapplyLicense['count']; ?></b></th>
                                        <?php }else{?>
                                        <th>Amendment License Request :- <a href="<?php echo base_url('TradeApplyLicenseReports/ward_wise_collection_details/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("3"));?>"><b><?=$amendapplyLicense['count']; ?></b></a>
                                        &nbsp;<a class="vl"></a> &nbsp;Total Amount Collection :- <a href="<?php echo base_url('TradeApplyLicenseReports/ward_wise_collection_details/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("3"));?>"><b><?=$amendmentcollection['sum']?$amendmentcollection['sum']:0?></b></a> </th>
                                        <?php }?>
                                        <?php if($surrendapplyLicense['count']==0){?>
                                        <th>Surrender License Request :- <b><?=$surrendapplyLicense['count']; ?></b></th>
										<?php }else{?>
                                        <th>Surrender License Request :- <a href="<?php echo base_url('TradeApplyLicenseReports/licence_request/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("4"));?>"><b><?=$surrendapplyLicense['count']; ?></b></a>
                                        &nbsp;<a class="vl"></a> &nbsp;Total Amount Collection :- 0</th>
                                        <?php }?>

                                     </tr>
									 
									 
                                </tbody>
                            </table>
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
 <script type="text/javascript">
    $(document).ready(function(){
        $('#demo_dt_basic').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            "bPaginate": false,
			"bSort" : false ,
            buttons: [            
              {
                text: 'excel',
                extend: "excel", 
                title: "Municipal Licence Application Details",
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2] }
            }, {
                text: 'pdf',
                extend: "pdf",
                title: "Municipal Licence Application Details",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2] }
            }
			]
        });
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
     });
</script>


<script type="text/javascript">
    $(document).ready(function(){
        $('#collection').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            "bPaginate": false,
			"bSort" : false ,
            buttons: [            
              {
                text: 'excel',
                extend: "excel", 
                title: "Municipal Licence Collection Details",
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1] }
            }, {
                text: 'pdf',
                extend: "pdf",
                title: "Municipal Licence Collection Details",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1] }
            }
			]
        });
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
     });
</script>


