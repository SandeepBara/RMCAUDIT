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
                    <li class="active">Counter Report</li>
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
							<div style="width:375px; height:auto; padding:30px;  box-shadow:0px 0px 5px; margin:30px auto">
								<form id="FORMNAME1" name="FORMNAME1" action="<?=base_url('');?>/TradeapplicationReport/application_report" method="post">
									<table width="100%" border="0">
										<tbody>
											<tr>
												<td height="63" align="center">
													<label class="control-label" for="Application_no"><b>Application No</b><span class="text-danger">*</span> </label>
													<input type="text" class="form-control" id="Application_no" maxlength="100" name="Application_no" style="box-shadow:1px 0px 3px;">
													
												</td>
											</tr>
											<tr>
												<td height="78" align="center">
													<p style="text-align: center;color:red"><strong>OR</strong></p>
													<label class="control-label" for="date_wise"><b>Date Wise</b></label>
														<table width="100%">
															<tbody>
																<tr>
																	<td align="center">From Date</td>
																	<td></td>
																	<td align="center">To Date</td>
																</tr>
																<tr>
																	<td align="center">
																		<input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>"  style="box-shadow:1px 0px 3px;">
																	</td>
																	<td>&nbsp;&nbsp;</td>
																	<td align="center">
																		<input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>"  style="box-shadow:1px 0px 3px;">
																	</td>
																</tr>
														   </tbody>
													   </table>
												</td>
											</tr>
									  
											<tr>
												<td height="63" align="center">
													<p style="text-align: center;color:red"><strong>OR</strong></p>
													<label class="control-label" for="firm_name"><b>Firm Name</b><span class="text-danger">*</span> </label>
													<input type="text" class="form-control" id="firm_name" maxlength="100" name="firm_name"  style="box-shadow:1px 0px 3px;">
													
												</td>
											</tr>
											<tr>
												<td height="63" align="center">
													<label class="control-label" for="natureofbusiness"><b>Nature Of Business</b><span class="text-danger">*</span> </label>
													<select id="natureofbusiness" name="natureofbusiness" class="form-control" style="box-shadow:1px 1px 1px;">
													   <option value="">ALL</option>  
														
													</select>
												</td>
											</tr>
											 <tr>
												<td height="63" align="center">
													<label class="control-label" for="Ward"><b>Ward</b><span class="text-danger">*</span> </label>
													<select id="ward_mstr_id" name="ward_mstr_id" class="form-control" style="box-shadow:1px 1px 1px;">
													   <option value="">ALL</option>  
														
													</select>
												</td>
											</tr>
											
											<tr>
												<td height="63" align="center">
													<input type="submit" name="submit" value="Search" class="btn btn-success" style="width: 170px; font-weight:bold; text-transform:uppercase; height: 37px;">
												</td>
											</tr>
										</tbody>
									</table>

								</form>

							</div>
										
						</div>
					</div>	
					
				</div>
			</div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
