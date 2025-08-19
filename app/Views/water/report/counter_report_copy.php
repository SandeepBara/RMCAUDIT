<?php
$app=array();
$consumer=array();
if(isset($collection))
{
    $app = array_filter($collection['result'],function($val){
            if(in_array($val['transaction_type'], ['New Connection', 'Site Inspection']))
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
                                    <h5 class="panel-title">Counter Report</h5>
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
                                                                if($value['lock_status']==1)
                                                                    continue;
                                                                ?>
                                                                <option value="<?=$value['id']?>" <?=isset($_POST) && !empty($_POST) && set_value('tc_id')==$value['id'] ? 'selected':''?>><?=$value['emp_name']?> (<?=$value['employee_code']?>)</option>
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
                                    <div class="panel-heading" >
                                        <span class="panel-title text-sm text-muted mx-auto col-md-3" >Total No.: <i id='a_total'></i></span>
                                        <!-- <span class="panel-title text-sm text-muted mx-auto col-md-3" >Bounced Collection : <i id='a_bounce'></i> </span> -->
                                        <span class="panel-title text-sm text-muted mx-auto col-md-3" >Total Collection : <i id='a_actual'></i></span>
                                    </div>
                                    <div class="panel-body table-responsive">
                                        <table id="a_demo_dt_basic" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
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
                                        $i=0;$a_total=0;$a_bounce =0;$a_actul=0;
                                        if(count($app)==0)
                                        {
                                        ?>
                                                <tr>
                                                    <td colspan="14" style="text-align: center; color:red;">Data Not Available!!</td>
                                                </tr>
                                        <?php 
                                        }
                                        else
                                        {
                                                //$i=$collection['offset'];
                                                $i=0;$a_total=0;$a_bounce =0;$a_actul=0;
                                                foreach ($app as $value)
                                                {
                                                    $a_total = ++$i;
                                                    // $a_actul=$a_total;
                                                    // if($value['status']==3)
                                                    {
                                                        // $a_bounce += $value['paid_amount'];
                                                        $a_actul+=$value['paid_amount'];
                                                    }
                                        ?>
                                                <tr>
                                                    <td><?=$i;?></td>
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
                                                    <td><?=!empty($value['emp_name']) ? ($value['emp_name']." (".$value['employee_code'].")" ): 'N/A'?></td>
                                                    
                                                </tr>

                                            <?php }?>
                                             
                                        <?php }  ?>
                                        </tbody>
                                    </table>
                                             <input type="hidden" value="<?=$a_total?>" id = 'h_a_total'>
                                             <!-- <input type="hidden" value="<?=$a_bounce?>" id = 'h_a_bounce'> -->
                                             <input type="hidden" value="<?=$a_actul?>" id = 'h_a_actual'>
                                    </div>
                                </div>
                                
                                <div class="panel panel-bordered panel-dark">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Water User Charges against Demand</h3>
                                        
                                        
                                    </div>
                                    <div class="panel-heading" >
                                        <span class="panel-title text-sm text-muted mx-auto col-md-3" >Total No.(Demand): <i id='c_total'></i></span>
                                        <!-- <span class="panel-title text-sm text-muted mx-auto col-md-3" >Bounced Collection (Demand): <i id='c_bounce'></i> </span> -->
                                        <span class="panel-title text-sm text-muted mx-auto col-md-3" >Total Collection (Demand): <i id='c_actual'></i></span>
                                    </div>
                                    <div class="panel-body table-responsive">
                                        <table id="c_demo_dt_basic" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
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
                                        $i=0; $c_total=0;$c_bounce =0;$c_actul=0;
                                        if(count($consumer)==0)
                                        {
                                        ?>
                                                <tr>
                                                    <td colspan="16" style="text-align: center;color:red;">Data Not Available!!</td>
                                                </tr>
                                        <?php 
                                        }
                                        else
                                        {
                                                //$i=$collection['offset'];
                                                $i=0; $c_total=0;$c_bounce =0;$c_actul=0;
                                                foreach ($consumer as $value)
                                                {   
                                                    $c_total = ++$i;
                                                    //$c_actul=$c_total;
                                                    //if($value['status']==3)
                                                    {
                                                        //$c_bounce += $value['paid_amount'];
                                                        $c_actul+=$value['paid_amount'];
                                                    }
                                                    //$c_actul=$c_total-$c_bounce;
                                        ?>
                                                <tr>
                                                    <td><?=$i?></td>
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
                                                    <td><?=$value['emp_name'] ?($value['emp_name']." (".$value['employee_code'].")" ) : 'N/A'?></td>
                                                    
                                                </tr>

                                            <?php }?>
                                        
                                            
                                             <?php
                                             }
                                         ?>
                                        </tbody>
                                    </table>
                                             <input type="hidden" value="<?=$c_total?>" id = 'h_c_total'>
                                             <!-- <input type="hidden" value="<?=$c_bounce?>" id = 'h_c_bounce'> -->
                                             <input type="hidden" value="<?=$c_actul?>" id = 'h_c_actual'>
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
    $(document).ready(function(){
        $('#a_demo_dt_basic').DataTable({
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
                exportOptions: { columns: [ 0,1,2,3,4,5,6,7,8,9,10,11,12,13] }
            }, {
                text: 'pdf',
                extend: "pdfHtml5",
                title: "Report",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0,1,2,3,4,5,6,7,8,9,10,11,12,13] }
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


    $(document).ready(function(){
        $('#c_demo_dt_basic').DataTable({
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
                exportOptions: { columns: [ 0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15] }
            }, {
                text: 'pdf',
                extend: "pdf",
                title: "Report",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15] }
            }]
        });
        // $('#btn_search').click(function(){
        //     var from_date = $('#from_date').val();
        //     var to_date = $('#to_date').val();
        //     if(from_date=="")
        //     {
        //         $("#from_date").css({"border-color":"red"});
        //         $("#from_date").focus();
        //         return false;
        //     }
        //     if(to_date=="")
        //     {
        //         $("#to_date").css({"border-color":"red"});
        //         $("#to_date").focus();
        //         return false;
        //     }
        //     if(to_date<from_date)
        //     {
        //         alert("To Date Should Be Greater Than Or Equals To From Date");
        //         $("#to_date").css({"border-color":"red"});
        //         $("#to_date").focus();
        //         return false;
        //     }
        // });
        // $("#from_date").change(function(){$(this).css('border-color','');});
        // $("#to_date").change(function(){$(this).css('border-color','');});
    });

</script>
<script type="text/javascript">
    function myPopup(myURL, title, myWidth, myHeight)
    {
        var left = (screen.width - myWidth) / 2;
        var top = (screen.height - myHeight) / 4;
        var myWindow = window.open(myURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + myWidth + ', height=' + myHeight + ', top=' + top + ', left=' + left);
    }
</script>

<script>
    $(document).ready(function(){
        var a_total = $('#h_a_total').val();
        //var a_bounce = $('#h_a_bounce').val();
        var a_actual = $('#h_a_actual').val();

        var c_total = $('#h_c_total').val();
        //var c_bounce = $('#h_c_bounce').val();
        var c_actual = $('#h_c_actual').val();
        
        document.getElementById('a_total').innerHTML="["+a_total+"]";
        //document.getElementById('a_bounce').innerHTML="["+a_bounce+"]";
        document.getElementById('a_actual').innerHTML="["+a_actual+"]";

        document.getElementById('c_total').innerHTML="["+c_total+"]";
        //document.getElementById('c_bounce').innerHTML="["+c_bounce+"]";
        document.getElementById('c_actual').innerHTML="["+c_actual+"]";
        
    });
    
</script>