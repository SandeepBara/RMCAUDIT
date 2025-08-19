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
                    <li class="active"> Ward Wise Team Summary </li>
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
                                    <h5 class="panel-title">Ward Wise Team Summary</h5>
                                </div>
                                <div class="panel-body">
                                    <div class ="row">
                                        <div class="col-md-12">
                                            <form class="form-horizontal" method="post" action="<?=base_url('water_report/ward_team_summary_report')?>">
                                                <div class="form-group">
                                                    <div class="col-md-3">
														<label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
														<input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
													</div>
													<div class="col-md-3">
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
													<div class="col-md-2 col-md-offset-5">
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
                                    <div class="row">
                                    <div class="" >
                                        <table id="demo_dt_basic" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Tax Collector</th>
                                                <th>Ward No.</th>
                                                <th>Total Consumers</th>
                                                <th>No. of Transactions</th>
                                                <th>Total Collections</th>
                                                 <th>View</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        
                                        if(!isset($collection) || empty($collection['result'])):
                                        ?>
                                                <tr>
                                                    <td colspan="10" style="text-align: center;">Data Not Available!!</td>
                                                </tr>
                                        <?php else:
                                                $i=$collection['offset'];
                                                //$i=0;
                                                foreach ($collection['result'] as $value):
                                        ?>
                                                <tr>
                                                    <td><?=++$i;?></td>
                                                    <td>
                                                            <span style="font-weight: bold; "><?=$value['emp_name'];?></span> (<?=$value['employee_code']?>)
                                                    </td>
                                                    <td><?=$value['ward_no'];?></td>
                                                    <td><?=$value['c_count'];?></td>
                                                    <td><?=$value['t_count'];?></td>
                                                    <td><?=$value['amount'];?></td>
                                                    <td>
                                                        <a onClick="myPopup('<?=base_url('water_report/ward_team_summary_report_dtl/'.md5($value['emp_id']).'/'.md5($value['ward_id']).'/'.$from.'/'.$to);?>','xtf','900','700');" class='btn btn-primary'>
                                                            view
                                                        </a>
                                                    </td>
                                                    
                                                </tr>

                                            <?php endforeach;?>
                                        <?php endif;  ?>
                                        </tbody>
                                    </table>
                                    </div>
                                    <?=isset($collection['count'])?pagination($collection['count']):null;?>
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