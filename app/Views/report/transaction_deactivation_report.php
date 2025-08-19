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
                    <li><a href="#">Report</a></li>
                    <li class="active">Transaction Deactivation Report</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h5 class="panel-title">Transaction Deactivation Report</h5>
                                </div>
                                <div class="panel-body">
                                    <div class ="row">
                                        <div class="col-md-12">
                                            <form class="form-horizontal" method="post" >
                                            <div class="form-group">
                                                <div class="col-md-3">
                                                     <label class="control-label" for="ward No"><b>Date From</b><span class="text-danger">*</span> </label>
                                                     <input type="date" name="date_from" id="date_from" value="<?php echo $date_from;?>" class="form-control">
                                                </div>
                                                 <div class="col-md-3">
                                                     <label class="control-label" for="ward No"><b>Date Upto</b><span class="text-danger">*</span> </label>
                                                     <input type="date" name="date_upto" id="date_upto" value="<?php echo $date_upto;?>" class="form-control">
                                                </div>
                                                  <input type="hidden" name="curr_date" id="curr_date" value="<?php echo date('Y-m-d'); ?>">
                                                  <div class="col-md-2">
                                                    <label class="control-label" for="department_mstr_id">&nbsp;</label>
                                                    <button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="row">
                                    <div class="table-responsive">
                                     <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                             
                                                <th>Transaction Date</th>
                                                <th>Transaction No.</th>
                                                <th>Transaction Type</th>
                                                <th>Holding No.</th>
                                                <th>Owner Name</th>
                                                <th>Mobile No.</th>
                                                <th>From Year</th>
                                                <th>From Quarter</th>
                                                <th>Upto Year</th>
                                                <th>Upto Quarter</th>
                                                <th>Payable Amount</th>
                                                <th>Deactivated By</th>
                                                <th>Deactivated Date</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                  <?php
                                  if($report)
                                  {
                                    foreach($report as $val)
                                    {
                                  ?>
                                <tr>  
                                     <td><?php echo date('d-m-Y',strtotime($val['tran_date']));?></td>
                                     <td><?php echo $val['tran_no'];?></td>
                                     <td><?php echo $val['tran_type'];?></td>
                                     <td><?php echo $val['h_no'];?></td>
                                     <td><?php echo $val['owner_name'];?></td>
                                     <td><?php echo $val['mobile_no'];?></td>
                                     <td><?php echo $val['from_fy'];?></td>
                                     <td><?php echo $val['from_qtr'];?></td>
                                     <td><?php echo $val['upto_fy'];?></td>
                                     <td><?php echo $val['upto_qtr'];?></td>
                                     <td><?php echo $val['payable_amt'];?></td>
                                     <td><?php echo $val['emp_name'];?></td>
                                     <td><?php echo date('d-m-Y',strtotime($val['deactive_date']));?></td>

                                    </td>     
                                </tr>
                                 <?php
                                    }
                                   }
                                  ?>
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

  <script type="text/javascript">

  document.getElementById('date_from').onchange = function() {
    
        var date_from=document.getElementById('date_from').value;
        var date_upto=document.getElementById('date_upto').value;

        var curr_date=$("#curr_date").val();
        //alert(curr_date);
        //alert(date_from);

        if(date_from>curr_date)
        {
            alert('Date From Should not be greater than Current Date');
            $("#date_from").val(curr_date);
        }
        else if(date_upto<date_from)
        {
             alert('Date From Should not be less than Date Upto');
            $("#date_from").val(curr_date);
        }

    }
    document.getElementById('date_upto').onchange = function() {
    
        var date_upto=document.getElementById('date_upto').value;
        var date_from=document.getElementById('date_from').value;
        var curr_date=$("#curr_date").val();

        //alert(date_upto);
        //alert(curr_date);

        if(date_upto>curr_date)
        {
            alert('Date Upto Should not be greater than Current Date');
            $("#date_upto").val(curr_date);
        }
        else if(date_upto<date_from)
        {
            alert('Date Upto Should be greater than Date From');
            $("#date_upto").val(curr_date);
        }

    }
  </script>
<?= $this->include('layout_vertical/footer');?>
<!--DataTables [ OPTIONAL ]-->
