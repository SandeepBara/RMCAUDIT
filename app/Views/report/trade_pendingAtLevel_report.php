<?= $this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url();?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
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
					<li><a href="#">Trade</a></li>
					<li class="active">Pending At Level</li>
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
					                <h5 class="panel-title">Pending At Level</h5>
					            </div>
                                <div class="panel-body">
                                    
                                    <div class="row">
                                        <div class="table-responsive">
                                            <b>Ward :- <?=$ward_id; ?></b><br>
					                   <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Degination Name</th>
                                                <th>No. of Application</th>
                                             </tr>  
                                            <tr>
                                                <td>Dealing Assistant</td>
                                                <td>
                                                <a href="<?php echo base_url('TradeApplyLicenseReports/ward_wise_details/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("da"));?>">
                                                <?=$pendingAtda['count']?>
                                                </a>
                                                </td>
                                             </tr>
                                            <tr>
                                                <td>Tax Daroga </td>
                                                <td>
                                                <a href="<?php echo base_url('TradeApplyLicenseReports/ward_wise_details/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("td"));?>">
                                                 <?=$pendingAttaxdaroga['count']?>
                                               </a>
                                               </td>
                                             </tr>
                                            <tr>
                                                <td>Section Head</td>
                                                <td>
                                                <a href="<?php echo base_url('TradeApplyLicenseReports/ward_wise_details/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("sh"));?>">
                                                <?=$pendingAtsec['count']?>
                                                </a>
                                                </td>
                                             </tr>
                                            <tr>
                                                <td>Municipal Commissioner </td>
                                                <td>
                                                <a href="<?php echo base_url('TradeApplyLicenseReports/ward_wise_details/'.base64_encode($from_date).'/'.base64_encode($to_date).'/'.base64_encode($ward_id).'/'.base64_encode("eo"));?>">
                                                <?=$pendingAteo['count']?> 
                                               </a>
                                               </td>
                                             </tr>
                                             <tr>
                                             <th>Total </th>
                                                 <th><?=$pendingAtda['count']+$pendingAttaxdaroga['count']+$pendingAtsec['count']+$pendingAteo['count']?></th>
                                             </tr>
                                        </thead>
					                </table>  
                                    
                                    </div>
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
<script src="<?=base_url();?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>


