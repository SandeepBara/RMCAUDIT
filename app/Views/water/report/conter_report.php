<?php
$app=array();
$consumer=array();
if(isset($collection))
{
    $app = array_filter($collection['result'],function($val){
            if($val['transaction_type']=='New Connection')
                return  $val;
    });

    $consumer = array_filter($collection['result'],function($val){
        if($val['transaction_type']=='Demand Collection')
            return  $val;
    });
}
?>
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
                    <li class="active"> Counter Report </li>
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
                                    <h5 class="panel-title">Team Summary</h5>
                                </div>
                                <div class="panel-body">
                                    <div class ="row">
                                        <div class="col-md-12">
                                        <form class="form-horizontal" method="post" action="<?=base_url('water_report/counter_report')?>">
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
														<label class="control-label" for="ward_id"><b>Ward No.</b><span class="text-danger">*</span> </label>
														<select id="ward_id" name="ward_id" class="form-control">
														   <option value="">ALL</option> 
                                                           <?php
                                                           foreach($ward as $value)
                                                           {
                                                                ?>
                                                                <option value="<?=$value['id']?>" <?=isset($_POST) && !empty($_POST) && set_value('ward_id')==$value['id'] ? 'selected':''?>><?=$value['ward_no']?></option>
                                                                <?php
                                                           }
                                                           ?>														
														</select> 
													</div>
                                                    <div class="col-md-4">
														<label class="control-label" for="tc_id"><b>Tax Collecter</b><span class="text-danger">*</span> </label>
														<select id="tc_id" name="tc_id" class="form-control">
														   <option value="">ALL</option> 
                                                           <?php
                                                           foreach($tc as $value)
                                                           {
                                                                ?>
                                                                <option value="<?=$value['id']?>" <?=isset($_POST) && !empty($_POST) && set_value('tc_id')==$value['id'] ? 'selected':''?>><?=$value['emp_name']?> (<?=$value['user_type']?>)</option>
                                                                <?php
                                                           }
                                                           ?>															
														</select>
													</div>
                                                    <!-- <div class="col-md-4">
														<label class="control-label" for="Ward"><b>Pending No </b><span class="text-danger">*</span> </label>
														
                                                        <select id="pending_on" name="pending_on" class="form-control">
														   <option value="All" <?=(isset($pending_on))?($pending_on=='All'?"SELECTED":""):"";?>>All</option> 
														   
														</select>
													</div> -->
													<div class="col-md-2 ">
														<label class="control-label" for="department_mstr_id">&nbsp;</label>
														<button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
													</div>
												</div>
                                            </form>
                                        </div>
                                    </div>
                                <!-- </div>
                                <hr>
                                <div id="page-content">     -->
                                <div class="panel panel-bordered panel-dark">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Counter Report against New Connection/Regularization</h3>
                                    </div>
                                    <div class="panel-body table-responsive">
                                        <table id="demo_dt_basic" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>App. No</th>
                                                <th>Applicant Name</th>
                                                <th>Mobile No.</th>
                                                <th>Guardian Name</th>
                                                <th>Ward No.</th>
                                                <th>Tran. Date</th>
                                                <th>Tran. No</th>
                                                <th>Mode</th>
                                                <th>Check/DD No</th>
                                                <th>Bank</th>
                                                <th>Branch</th>
                                                <th>Amount</th>
                                                <th>Tax Collector</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        
                                        if(count($app)==0)
                                        {
                                        ?>
                                                <tr>
                                                    <td colspan="10" style="text-align: center;">Data Not Available!!</td>
                                                </tr>
                                        <?php 
                                        }
                                        else
                                        {
                                                //$i=$collection['offset'];
                                                $i=0;
                                                foreach ($app as $value)
                                                {
                                        ?>
                                                <tr>
                                                    <td><?=++$i;?></td>
                                                    <td><?=!empty($value['app_application_no'])? $value['app_application_no'] :'N/A'?></td>
                                                    <td><?=!empty($value['app_applicant_name']) ? $value['app_applicant_name'] : 'N/A'?></td>
                                                    <td><?=!empty($value['app_mobile_no']) ? $value['app_mobile_no'] : 'N/A'?></td>
                                                    <td><?=!empty($value['app_father_name']) ? $value['app_father_name'] : 'N/A'?></td>
                                                    <td><?=!empty($value['app_ward_no']) ? $value['app_ward_no'] : 'N/A'?></td>
                                                    <td><?=!empty($value['date']) ? $value['date'] : 'N/A'?></td>
                                                    <td><?=!empty($value['transaction_no']) ? $value['transaction_no'] : 'N/A'?></td>
                                                    <td><?=!empty($value['payment_mode']) ? $value['payment_mode'] : 'N/A'?></td>
                                                    <td><?=!empty($value['cheque_no']) ? $value['cheque_no'] : 'N/A'?></td>
                                                    <td><?=!empty($value['bank_name']) ? $value['bank_name'] : 'N/A'?></td>
                                                    <td><?=!empty($value['branch_name']) ? $value['branch_name'] : 'N/A'?></td>
                                                    <td><?=!empty($value['paid_amount']) ? $value['paid_amount'] : 'N/A'?></td>
                                                    <td><?=!empty($value['emp_name']) ? $value['emp_name'] : 'N/A'?></td>
                                                    
                                                </tr>

                                            <?php }?>
                                        <?php }  ?>
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                                
                                <div class="panel panel-bordered panel-dark">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Water User Charges against Demand</h3>
                                    </div>
                                    <div class="panel-body table-responsive">
                                        <table id="demo_dt_basic" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Consumer No</th>
                                                <th>Connection Type</th>
                                                <th>Consumer Name</th>
                                                <th>Mobile No.</th>
                                                <th>Usage Type</th>
                                                <th>Holding No</th>
                                                <th>Ward No.</th>
                                                <th>Tran. Date</th>
                                                <th>Tran. No</th>
                                                <th>Mode</th>
                                                <th>Check/DD No</th>
                                                <th>Bank</th>
                                                <th>Branch</th>
                                                <th>Amount</th>
                                                <th>Tax Collector</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        
                                        if(count($consumer)==0)
                                        {
                                        ?>
                                                <tr>
                                                    <td colspan="10" style="text-align: center;">Data Not Available!!</td>
                                                </tr>
                                        <?php 
                                        }
                                        else
                                        {
                                                //$i=$collection['offset'];
                                                $i=0;
                                                foreach ($consumer as $value)
                                                {
                                        ?>
                                                <tr>
                                                    <td><?=++$i;?></td>
                                                    <td><?=$value['c_consumer_no'] ? : 'N/A'?></td>
                                                    <td><?=$value['meter'] ? : 'N/A'?></td>
                                                    <td><?=$value['c_worner_name'] ? : 'N/A'?></td>
                                                    <td><?=$value['c_mobile_no'] ? : 'N/A'?></td>
                                                    <td><?=$value['c_property_type'] ? : 'N/A'?></td>
                                                    <td><?=$value['c_holding_no'] ? : 'N/A'?></td>
                                                    <td><?=$value['c_ward_no'] ? : 'N/A'?></td>
                                                    <td><?=$value['date'] ? : 'N/A'?></td>
                                                    <td><?=$value['transaction_no'] ? : 'N/A'?></td>
                                                    <td><?=$value['payment_mode'] ? : 'N/A'?></td>
                                                    <td><?=$value['cheque_no'] ? : 'N/A'?></td>
                                                    <td><?=$value['bank_name'] ? : 'N/A'?></td>
                                                    <td><?=$value['branch_name'] ? : 'N/A'?></td>
                                                    <td><?=$value['paid_amount'] ? : 'N/A'?></td>
                                                    <td><?=$value['emp_name'] ? : 'N/A'?></td>
                                                    
                                                </tr>

                                            <?php }?>
                                        <?php }  ?>
                                        </tbody>
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
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script type="text/javascript">
    // $(document).ready(function(){
    //     $('#demo_dt_basic').DataTable({
    //         responsive: false,
    //         dom: 'Bfrtip',
    //         lengthMenu: [
    //             [ 10, 25, 50, -1 ],
    //             [ '10 rows', '25 rows', '50 rows', 'Show all' ]
    //         ],
    //         buttons: [
    //             'pageLength',
    //           {
    //             text: 'excel',
    //             extend: "excel",
    //             title: "Report",
    //             footer: { text: '' },
    //             exportOptions: { columns: [ 0,1,2,3,4,5,6,7,8,9] }
    //         }, {
    //             text: 'pdf',
    //             extend: "pdf",
    //             title: "Report",
    //             download: 'open',
    //             footer: { text: '' },
    //             exportOptions: { columns: [ 0,1,2,3,4,5,6,7,8,9] }
    //         }]
    //     });
    //     $('#btn_search').click(function(){
    //         var from_date = $('#from_date').val();
    //         var to_date = $('#to_date').val();
    //         if(from_date=="")
    //         {
    //             $("#from_date").css({"border-color":"red"});
    //             $("#from_date").focus();
    //             return false;
    //         }
    //         if(to_date=="")
    //         {
    //             $("#to_date").css({"border-color":"red"});
    //             $("#to_date").focus();
    //             return false;
    //         }
    //         if(to_date<from_date)
    //         {
    //             alert("To Date Should Be Greater Than Or Equals To From Date");
    //             $("#to_date").css({"border-color":"red"});
    //             $("#to_date").focus();
    //             return false;
    //         }
    //     });
    //     $("#from_date").change(function(){$(this).css('border-color','');});
    //     $("#to_date").change(function(){$(this).css('border-color','');});
    // });

</script>
<script type="text/javascript">
    function myPopup(myURL, title, myWidth, myHeight)
    {
        var left = (screen.width - myWidth) / 2;
        var top = (screen.height - myHeight) / 4;
        var myWindow = window.open(myURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + myWidth + ', height=' + myHeight + ', top=' + top + ', left=' + left);
    }
</script>