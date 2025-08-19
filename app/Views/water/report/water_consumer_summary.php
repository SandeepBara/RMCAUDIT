<?= $this->include('layout_vertical/header');?>
<style>
.row{line-height:25px;}
#tdId{font-size: medium; font-weight: bold; text-align: right;}
#leftTd{font-size: medium; font-weight: bold; text-align: left;color: #090f44;}
#tdRight{font-size: medium; font-weight: bold; text-align: right;color: #090f44;}
#left{font-size: medium; font-weight: bold; text-align: left;}
.wardClass{font-size: medium; font-weight: bold;}
</style>
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
                    <li><a href="#">Report</a></li>
                    <li class="active">Collection Report</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                  <!--  <div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h5 class="panel-title">Collection Report</h5>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" method="post">
								<div class="row">
									<div class="col-md-12">
										<div class="col-md-6">
											<div class="col-md-3">
												<label class="control-label wardClass" for="Ward"><b>Ward No</b><span class="text-danger">*</span> </label>
											</div>
											<div class="col-md-6">
												<select id="ward_id" name="ward_id" class="form-control">
												   <option value="">ALL</option>  
													<?php foreach($ward_list as $value):?>
													<option value="<?=$value['id']?>" <?=(isset($ward_id))?$ward_id==$value["id"]?"SELECTED":"":"";?>><?=$value['ward_no'];?>
													</option>
													<?php endforeach;?>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-2"></div>
								<div class="row">
									<div class="col-md-12">
										<div class="col-md-6">
											<div class="col-md-3">
												<label class="control-label wardClass" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
											</div>
											<div class="col-md-6">
												<input type="date" id="date_from" name="date_from" class="form-control" placeholder="From Date" value="<?php echo $date_from; ?>" max="<?=date('Y-m-d');?>">
											</div>
										</div>
										<div class="col-md-6">
											<div class="col-md-3">
												<label class="control-label wardClass" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
											</div>
											<div class="col-md-6">
												<input type="date" id="date_upto" name="date_upto" class="form-control" placeholder="To Date" value="<?php echo $date_upto; ?>" max="<?=date('Y-m-d');?>">
											</div>
										</div>
									</div>
								</div>
								<div class="panel">
									<div class="panel-body text-center">
										<button type="submit" class="btn btn-primary btn-labeled" id="btn_property" name="btn_property">View Collection</button>&nbsp;&nbsp;&nbsp;&nbsp;
										<a  href="#" download="Collection Report.xls"  onClick="return ExcellentExport.excel(this, 'printableArea', 'Water Collection');" class="btn btn-primary">Export to Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="button" onClick="printDiv('printableArea')" style="width: 100px" class="btn btn-primary" value="Print">
									</div>
								</div>
							</form>
						</div>
					</div>	
					-->
					
					<div class="panel panel-bordered panel-dark" >
						<div class="panel-heading">
							<div class="panel-control">
								<a href="#" download="Consumer Summary.xls"  onClick="return ExcellentExport.excel(this, 'printableArea', 'Consumer Summary');" class="btn btn-primary text-center">Export to Excel</a>
							</div>
							<h3 class="panel-title">Consumer Summary</h3>
						</div>
						<div class="panel-body" id="printableArea">
							<div class="col-md-12">
								
								<div class="col-md-6">
									<div class="panel panel-bordered panel-dark" id="printableArea">
										<div class="panel-heading" style="background-color: #298da0;">
											<h3 class="panel-title">Consumer Summary Description</h3>
										</div>
										<div class="panel-body">
											
											<table class="table table-bordered table-responsive">
												<tr>
													<th id="leftTd">Type</th>
													<th id="leftTd" style="text-align: right;">No. of Consumer</th>
													
												</tr>
												<tr>
													<td>Metered</td>
													<td style="text-align: right;"><?=isset($consumer_summary['metered'])?$consumer_summary['metered']:0;?>&nbsp;&nbsp;</td>
													
												</tr>
												<tr>
													<td>Non-Metered</td>
													<td style="text-align: right;"><?=isset($consumer_summary['non_metered'])?$consumer_summary['non_metered']:0;?>&nbsp;&nbsp;</td>
												
												</tr>
												
											</table>

											<table class="table table-bordered table-responsive">
												<tr>
													<th id="leftTd">Property Type</th>
													<th id="leftTd" style="text-align: right;">No. of Consumer</th>
													
												</tr>
												
													<tr>
														<td >Residential</td>
														<td style="text-align: right;"><?php echo isset($consumer_summary['residential_consumer'])?$consumer_summary['residential_consumer']:0;?></td>
													</tr>
													<tr>
														<td >Commercial</td>
														<td style="text-align: right;" ><?php echo isset($consumer_summary['commercial_consumer'])?$consumer_summary['commercial_consumer']:0;?></td>
													</tr>
													<tr>
														<td >Apartment</td>
														<td style="text-align: right;"><?php echo isset($consumer_summary['appartment_consumer'])?$consumer_summary['appartment_consumer']:0;?></td>
													</tr>
													<tr>
														<td >Goverment & PSU</td>
														<td style="text-align: right;"><?php echo isset($consumer_summary['gov_consumer'])?$consumer_summary['gov_consumer']:0;?></td>
													</tr>
													<tr>
														<td >Institutional</td>
														<td style="text-align: right;"><?php echo isset($consumer_summary['institutional_consumer'])?$consumer_summary['institutional_consumer']:0;?></td>
													</tr>
													<tr>
														<td >SSI Unit</td>
														<td style="text-align: right;"><?php echo isset($consumer_summary['ssi_unit_consumer'])?$consumer_summary['ssi_unit_consumer']:0;?></td>
													</tr>
													<tr>
														<td >Trust & NGO</td>
														<td style="text-align: right;"><?php echo isset($consumer_summary['trust_and_ngo_consumer'])?$consumer_summary['trust_and_ngo_consumer']:0;?></td>
													</tr>
													<tr>
														<td >Industrial</td>
														<td style="text-align: right;"><?php echo isset($consumer_summary['industrial_consumer'])?$consumer_summary['industrial_consumer']:0;?></td>
													</tr>
													

											</table>

										</div>
									</div>
								</div>


								<div class="col-md-6">
									<div class="panel panel-bordered panel-dark" id="printableArea">
										<div class="panel-heading" style="background-color: #298da0;">
											<h3 class="panel-title">Holding Wise Consumer Description</h3>
										</div>
										<div class="panel-body">
											<table class="table table-bordered">
											
											<tr>
												<th id="leftTd">Description</th>
												<th id="leftTd" style="text-align: right;">No.of Consumer</th>
												<!-- <td style="font-size: medium; font-weight: bold; text-align: right;color: #090f44">No.of Consumer</td> -->
											</tr>
											<tr>
												<td>Holding	</td>
												<td style="text-align: right;"><?php echo isset($consumer_summary['holding_consumer'])?$consumer_summary['holding_consumer']:0;?></td>
											</tr>
											<tr>
												<td>Non-Holding	</td>
												<td style="text-align: right;"><?php echo isset($consumer_summary['non_holding_consumer'])?$consumer_summary['non_holding_consumer']:0;?></td>
											</tr>

											<tr>
												<td colspan="2" align="center">Holding	</td>
												
											</tr>

											<tr>
												<td>Type of Connection</td>
												<td style="text-align: right;">No. of Consumer</td>
											</tr>

											<tr>
												<td>Metered</td>
												<td style="text-align: right;"><?php echo isset($consumer_summary['holding_metered_consumer'])?$consumer_summary['holding_metered_consumer']:0;?></td>
											</tr>

											<tr>
												<td>Non-Metered	</td>
												<td style="text-align: right;"><?php echo isset($consumer_summary['holding_non_metered_consumer'])?$consumer_summary['holding_non_metered_consumer']:0;?></td>
											</tr>

											<tr>
												<td colspan="2" align="center">Non-Holding	</td>
												
											</tr>

											<tr>
												<td>Type of Connection</td>
												<td style="text-align: right;">No. of Consumer</td>
											</tr>

											<tr>
												<td>Metered</td>
												<td style="text-align: right;"><?php echo isset($consumer_summary['non_holding_metered_consumer'])?$consumer_summary['non_holding_metered_consumer']:0;?></td>
											</tr>

											<tr>
												<td>Non-Metered	</td>
												<td style="text-align: right;"><?php echo isset($consumer_summary['non_holding_non_metered_consumer'])?$consumer_summary['non_holding_non_metered_consumer']:0;?></td>
											</tr>

											
										</table>
										</div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="panel panel-bordered panel-dark" id="printableArea">
											<div class="panel-heading" style="background-color: #298da0;">
												<h3 class="panel-title">Consumer Type Description</h3>
											</div>
											<div class="panel-body">
												<table class="table table-bordered">
													<tr>
														<th id="leftTd">Description</th>
														<th id="leftTd" style="text-align: right;">No.of Consumer</th>
														<th id="leftTd" style="text-align: right;">Meter</th>
														<th id="leftTd" style="text-align: right;">Non Meter</th>
													</tr>
													<?php
															if(isset($consumer_type))
															{
																foreach($consumer_type as $val)
																{
																	?>
																	<tr>
																		<td><?=$val['consumer_type'];?></td>
																		<td style="text-align: right;"><?=$val['count'];?></td>
																		<td style="text-align: right;"><?=$val['metered'];?></td>
																		<td style="text-align: right;"><?=$val['non_metered'];?></td>
																	</tr>
																	<?php
																}
															}
														?>
												</table>
											</div>
									</div>
								</div>

							</div>
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
    $('#btn_property').click(function(){
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        if(to_date==""){
            $("#to_date").css({"border-color":"red"});
            $("#to_date").focus();
            return false;
        }
        if(to_date<from_date){
            alert("To Date Should Be Greater Than Or Equals To From Date");
            $("#to_date").css({"border-color":"red"});
            $("#to_date").focus();
            return false;
        }
    });
    $("#from_date").change(function(){$(this).css('border-color','');});
    $("#to_date").change(function(){$(this).css('border-color','');});
    function printDiv(divName) { //alert('asfasdf'); return false;
	var printData = document.getElementById(divName).innerHTML;
	var data = document.body.innerHTML;
	
	document.body.innerHTML = printData;
	window.print();
	window.location.reload();
	document.body.innerHTML = data;
	}
</script>