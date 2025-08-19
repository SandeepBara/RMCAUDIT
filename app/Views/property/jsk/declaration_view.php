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
                                        <div class="panel-group">
                    <div class="panel panel-bordered panel-dark">

                                            <div class="panel-heading">
                                                <h3 class="panel-title">Tax Details</h3>
											</div>
                                             <div class="panel-body">
											<div class="table-responsive">												
												<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
													<thead class="thead-light" style="background-color: blanchedalmond;">
															<th scope="col">ARV</th>
															<th scope="col">Effected From</th>
															<th scope="col">Holding Tax</th>
															<th scope="col">Water Tax</th>
															<th scope="col">Conservancy/Latrine Tax</th>
															<th scope="col">Education Cess</th>
															<th scope="col">Health Cess</th>
															<th scope="col">Quarterly Tax</th>
													</thead>
													<tbody>
														<tr>
															<?php if($tax_list):
																$qtr_tax=0; ?>
															<?php foreach($tax_list as $tax_list): 
																$qtr_tax=$tax_list['holding_tax']+$tax_list['water_tax']+$tax_list['latrine_tax']+$tax_list['education_cess']+$tax_list['health_cess'];
															?>
														<tr>
															<td><?php echo $tax_list['arv']; ?></td>
															<td>Quarter : <?php echo $tax_list['qtr']; ?> / Year : <?php echo $tax_list['fy']; ?></td>
															<td><?php echo $tax_list['holding_tax']; ?></td>
															<td><?php echo $tax_list['water_tax']; ?></td>
															<td><?php echo $tax_list['latrine_tax']; ?></td>
															<td><?php echo $tax_list['education_cess']; ?></td>
															<td><?php echo $tax_list['health_cess']; ?></td>
															<td><?php echo $qtr_tax; ?></td>     
														</tr>
														<?php endforeach; ?>
														<?php else: ?>
														<tr>
															<td colspan="7" style="text-align:center;"> Data Not Available...</td>
														</tr>
														<?php endif; ?>
													</tbody>
												</table>
											</div>
                                                </div>

										</div>
                    </div>

										<div class="panel panel-bordered panel-dark">
											<div class="panel-heading">
												<h3 class="panel-title">Declaration Details</h3>
											</div>
                                            <div class="panel-body">
                                                <div class ="row">
                                                    <div class="col-md-12">
                                                        <form class="form-horizontal">
                                                            <div class="form-group">
                                                                <div class="col-md-3">
                                                                    <b>Declaration date</b>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <?=$declaration_dtl['declaration_date']?>                                         
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-md-3">
                                                                    <b> Document </b>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <?php
                                                                    $exp_tr_doc=explode('.',$declaration_dtl['declaration_doc_path']);
                                                                    $exp_tr_doc_ext=$exp_tr_doc[1];

                                                                    if($exp_tr_doc_ext=='pdf') { 
                                                                    ?>
                                                                    <a href="<?=base_url();?>/writable/uploads/<?=$declaration_dtl['declaration_doc_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
                                                                    <?php } else { ?>
                                                                    <a href="#" class="pop"><img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$declaration_dtl['declaration_doc_path'];?>" style="width: 40px; height: 40px;"></a>
                                                                    <?php } ?>                                                      
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-md-3">
                                                                    <b>Remarks  </b>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <?=$declaration_dtl['remarks']?>
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
<!-- modal start -->
<!-- Creates the bootstrap modal where the image will appear -->
<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel">Image preview</h4>
        </div>
        <div class="modal-body">
            <img src="" id="imagepreview" style="width: 400px; height: 264px;" >
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
</div>
<!-- modal end -->
<?= $this->include('layout_vertical/footer');?>
<script>
    $(function() {
        $('.pop').on('click', function() {
            //alert($(this).find('img').attr('src'));
            $('#imagepreview').attr('src', $(this).find('img').attr('src'));
            $('#imagemodal').modal('show');   
        });
    });
</script>