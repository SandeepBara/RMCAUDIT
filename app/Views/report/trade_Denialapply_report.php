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
                    <li class="active">Trade Denial  </li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h5 class="panel-title"> Trade Denial </h5>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" method="post" action="<?=base_url('');?>/TradeDenialApplyReports/report">
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
                <h5 class="panel-title">Trade Denial Details</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="demo_dt_basic" class="table">
                                <thead>
                                    <tr>
                                        <th>Ward No.   :- <b><?=$ward_id; ?></b></th>
                                        <th>From Date :-<b> <?=date("d-m-Y", strtotime($from_date))?></b></th> 
                                        <th>To Date :-<b> <?=date("d-m-Y", strtotime($to_date))?></b></th>
                                        <th> </th>

                                     </tr>
                                 </thead>
                                <tbody id="total_rocord">
								      <tr>
                                        <?php if($denialApply['count']==0){?>
                                        <th> Total Denial Apply :- <b><?=$denialApply['count']; ?></b></th>
                                        <?php }else{?>
                                        <th>Total Denial Apply :- <a href="<?php echo base_url('TradeDenialApplyReports/denial_details/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("all"));?>"><b><?=$denialApply['count']; ?></b></a></th>
                                        <?php }?>
                                        <?php if($approvedDenial['count']==0){?>
                                        <th>Total Approved Denial :- <b><?=$approvedDenial['count']; ?></b></th>
                                        <?php }else{?>
                                        <th>Total Approved Denial :- <a href="<?php echo base_url('TradeDenialApplyReports/denial_details/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("5"));?>"><b><?=$approvedDenial['count']; ?></b></a></th>
                                        <?php }?>
                                        <?php if($rejectedDenial['count']==0){?>
										<th>Total Rejected Denial :- <b><?=$rejectedDenial['count']; ?></b></th>
                                        <?php }else{?>
                                        <th>Total Rejected Denial :- <a href="<?php echo base_url('TradeDenialApplyReports/denial_details/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("4"));?>"><b><?=$rejectedDenial['count']; ?></b></a></th>
                                        <?php }?>
                                        <?php if($pendingAtEo['count']==0){?>
										<th>Pending At Executive Officer :- <b><?=$pendingAtEo['count']; ?></b></th>
                                        <?php }else{?>
                                        <th>Pending At Executive Officer :- <a href="<?php echo base_url('TradeDenialApplyReports/denial_details/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("1"));?>"><b><?=$pendingAtEo['count']; ?></b></a></th>
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
                title: "Trade Denial Details",
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3] }
            }, {
                text: 'pdf',
                extend: "pdf",
                title: "Trade Licence Denial Details",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3] }
            }
			]
        });
     });
</script>




