<?= $this->include('layout_vertical/header');?>

<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<!-- <link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet"> -->
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
                    <li><a href="#">Report</a></li>
                    <li class="active">Application Status</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    <div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h5 class="panel-title">Track Application Status</h5>
						</div>
						<div class="panel-body">
							<form id="FORMNAME1" name="FORMNAME1" action="<?=base_url('');?>/TradeapplicationReport/application_report" method="post">
								<table width="1318" border="0" class="table table-condensed">
									<tbody>
										<tr>
											<td width="206" height="24">    <strong>Application No <span style="color:red">*</span></strong></td>
											<td width="35"><strong>:</strong></td>
											<td width="378"><input type="text" class="require_dataos inputText" id="Application_no" name="Application_no" style="border-radius: 0px;  "></td>
										   <!-- <p style="text-align: center;color:red"><strong>OR</strong></p> -->
											<td width="101" align="center"><strong style="color:#FF0000">OR</strong></td>
											<td width="153"> <strong>Firm Name <span style="color:red">*</span></strong></td>
											<td width="11"><strong>:</strong></td>
											<td width="404"><input type="text" class="require_dataos inputText" id="firm_name" name="firm_name" style="border-radius: 0px;  "></td>
										</tr>
										<tr>
											<td colspan="7"><p style="text-align: center;color:red"><strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;OR</strong></p> </td> 
										</tr>
										<tr>
											<td>
											
												<label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
												<div class="input-group">
													<input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
												</div>
											
											</td>
											<td>
											
												<label class="control-label" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
												<div class="input-group">
													<input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
												</div>
											</td>
										</tr>
										<tr>
											<td height="63" align="center">
												<label class="control-label" for="natureofbusiness"><b>Nature Of Business</b><span class="text-danger">*</span> </label>
												<select id="natureofbusiness" name="natureofbusiness" class="form-control" style="box-shadow:1px 1px 1px;">
												   <option value="">ALL</option>  
													
												</select>
											</td>
											<td height="63" align="center">
												<label class="control-label" for="Ward"><b>Ward</b><span class="text-danger">*</span> </label>
												<select id="ward_mstr_id" name="ward_mstr_id" class="form-control" style="box-shadow:1px 1px 1px;">
												   <option value="">ALL</option>  
													
												</select>
											</td>
										</tr>
										<tr>
											<td height="39" colspan="2" align="right">
												<input type="submit" name="submit" value="Search" class="btn btn-success" style="width:100px">
											</td>
										</tr>
									</tbody>
								</table>
							</form>
						</div>
					</div>
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h5 class="panel-title">Application Status Search List</h5>
						</div>
						<div class="panel-body">
						
							<table class="table table-striped" width="100%">
								<tbody>
									<tr>
										<td width="6%"><strong>A. No</strong></td>
										<td width="16%"><strong>Firm Name</strong></td>
										<td width="16%"><strong>Mobile No</strong></td>
										<td width="16%"><strong>Applied Date </strong></td>
										<td width="16%"><strong>Application Type</strong></td>
										<td width="16%"><strong>Nature Of Business</strong></td>
										<td width="16%"><strong>Ward No.</strong></td>
										<td width="8%"><strong>VIew</strong></td>
									</tr>
									<tr>
										<td>158241080221124522</td>
										<td>UNIQUE SRINGAR GIFT STORE</td>
										<td>9835933918</td>
										<td>08-02-2021</td>
										<td>Renewal</td>		  
										<td><ol><li>OTHERS</li></ol>
										</td>	
										<td></td>		
										<td><a href="applyconnectionviewtadecaplicationstatus.php?nid=MTU4MjQx">View</a></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
    
				</div>
			</div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>

