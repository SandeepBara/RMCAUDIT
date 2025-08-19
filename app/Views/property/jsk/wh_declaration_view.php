<?= $this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url();?>/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">
                    <!--Page Title-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <div id="page-title">
                        <!--<h1 class="page-header text-overflow">Designation List</h1>//-->
                    </div>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End page title-->
                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
					<li><a href="#"><i class="demo-pli-home"></i></a></li>
					<li><a href="#">Property</a></li>
					<li class="active"> Declaration Search</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    <div class="row" >
                        <div class="col-md-12">
                            <center><b><h4 style="color:red;">
                                <?php
                                if(!empty($err_msg)){
                                    echo $err_msg;
                                }
                                ?>
                                </h4>
                                </b></center>
                        </div>
                    </div>
                                        <div class="panel panel-bordered panel-dark">
											<div class="panel-heading">
												<div class="panel-control">
													<a href="<?php echo base_url('wh_Declaration/search_list') ?>" class="btn btn-default">Back</a>
												</div>
												<h3 class="panel-title">Basic Details</h3>
											</div>
											<div class="panel-body">
												<div class="row">
													<div class="col-sm-2">
														<b>Ward No. :</b>
													</div>
													<div class="col-sm-3">
														<?php echo $basic_details['ward_no']; ?>
													</div>
													<div class="col-sm-1">
													</div>
													<div class="col-sm-3">
														<b>Holding No. :</b>
													</div>
													<div class="col-sm-3">
														<?php echo $basic_details['holding_no']; ?>
													</div>
												</div>
												<div class="row">
													<div class="col-md-2">
														<b>Property Type :</b>
													</div>
													<div class="col-md-3">
														<?php echo $basic_details['property_type']; ?>
													</div>
													<div class="col-md-1">
													</div>
													<div class="col-md-3">
														<b>Ownership Type :</b>
													</div>
													<div class="col-md-3">
														<?php echo $basic_details['ownership_type']; ?>
													</div>
												</div>
												<div class="row">
													<div class="col-md-2">
														<b>Address :</b>
													</div>
													<div class="col-md-10">
														<?php echo $basic_details['prop_address']; ?>
													</div>
												</div>
												<div class="row">
													<div class="col-md-2">
														<b>Area Of Plot :</b>
													</div>
													<div class="col-md-3">
														<?php echo $basic_details['area_of_plot']; ?>(In dismil)
													</div>
													<div class="col-md-1">
													</div>
													<div class="col-md-3">
														<b>Village :</b>
													</div>
													<div class="col-md-3">
														<?php echo $basic_details['village_mauja_name']; ?>
													</div>
												</div>
												<div class="row">
													<div class="col-md-2">
														<b> Khata :</b>
													</div>
													<div class="col-md-3">
														<?php echo $basic_details['khata_no']; ?>
													</div>
													<div class="col-md-1">
													</div>
													<div class="col-md-3">
														<b> Plot No. :</b>
													</div>
													<div class="col-md-3">
														<?php echo $basic_details['plot_no']; ?>
													</div>
												</div>
												<div class="row">
													<div class="col-md-2">
														<b>Mauja Name :</b>
													</div>
													<div class="col-md-3">
														<?php echo $basic_details['village_mauja_name']; ?>
													</div>
													<div class="col-md-1">
													</div>
													<div class="col-md-3">
														<b>Rainwater Harvesting Provision :</b>
													</div>
													<div class="col-md-3">
														<?php if($basic_details['is_water_harvesting']=='f')
                                                              {
                                                                echo "No";
                                                              }
                                                              else if($basic_details['is_water_harvesting']=='t')
                                                              {
                                                                echo "Yes";
                                                              }
                                                        ?>
													</div>
												</div>

											</div>

										</div>
                                        <div class="panel panel-bordered panel-dark">
											<div class="panel-heading">
												<h3 class="panel-title">Owner Details</h3>
											</div>
											<div class="table-responsive">
												<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
													<thead class="thead-light" style="background-color: blanchedalmond;">
														<tr>
														  <th scope="col">Owner Name</th>
														  <th scope="col">R/W Guardian</th>
														  <th scope="col">Guardian's Name</th>
														  <th scope="col">Mobile No</th>
														</tr>
													</thead>
													<tbody>
														<?php if($owner_details==""){ ?>
															<tr>
																<td style="text-align:center;"> Data Not Available...</td>
															</tr>
														<?php }else{ ?>
														<?php foreach($owner_details as $owner_details): ?>
															<tr>
															  <td><?php echo $owner_details['owner_name']; ?></td>
															  <td><?php echo $owner_details['relation_type']; ?></td>
															  <td><?php echo $owner_details['guardian_name']; ?></td>
															  <td><?php echo $owner_details['mobile_no']; ?></td>
															</tr>
														<?php endforeach; ?>
														<?php } ?>
													</tbody>
												</table>
											</div>
										</div>
										<div class="panel panel-bordered panel-dark">
											<div class="panel-heading">
												<h3 class="panel-title">Fill out all the details</h3>
											</div>
                                            <div class="panel-body">
                                                <div class ="row">
                                                    <div class="col-md-12">
                                                        <form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
                                                            <div class="form-group">
                                                                <div class="col-md-3">
                                                                    <label class="control-label" for="from_date"><b>Declaration date <span class="text-danger">*</span></b> </label>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="date" id="declaration_date" name="declaration_date" class="form-control" placeholder="Declaration Date" value="" >                                                        
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-md-3">
                                                                    <label class="control-label" for="from_date"><b>Upload Document <span class="text-danger">*</span></b> </label>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="file" id="declaration_doc_path" name="declaration_doc_path" class="form-control" value="" accept=".png,.jpg,.jpeg,.pdf" >                                                        
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-md-3">
                                                                    <label class="control-label" for="from_date"><b>Remarks <span class="text-danger">*</span></b> </label>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <textarea type="text" id="remarks" name="remarks" class="form-control" placeholder="Remarks" ></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-md-3">&nbsp;</div>
                                                                <div class="col-md-3">
                                                                    <button class="btn btn-success btn-block" id="btn_submit" name="btn_submit" type="submit">Submit</button>                                                      
                                                                </div>
                                                            </div>
                                                        </form>
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
<script type="text/javascript">
$(document).ready( function () {
    $("#btn_submit").click(function() {
        var process = true;
        var declaration_date = $("#declaration_date").val();
        var given_date='2017-03-31';

        if (declaration_date == '') {
            $("#declaration_date").css({"border-color":"red"});
            $("#declaration_date").focus();
            process = false;
          }
        if (declaration_date != '') {
            if(declaration_date<=given_date)
            {
                $("#declaration_date").css({"border-color":"red"});
                $("#declaration_date").focus();
                process = false;
            }
          }
        var declaration_doc_path = $("#declaration_doc_path").val();
        if (declaration_doc_path == '') {
            $("#declaration_doc_path").css({"border-color":"red"});
            $("#declaration_doc_path").focus();
            process = false;
          }
         var remarks = $("#remarks").val();
        if (remarks == '') {
            $("#remarks").css({"border-color":"red"});
            $("#remarks").focus();
            process = false;
          }
        return process;
    });

    $("#declaration_date").change(function(){$(this).css('border-color','');});
    $("#declaration_doc_path").change(function(){$(this).css('border-color','');});
    $("#remarks").change(function(){$(this).css('border-color','');});   
});
</script>