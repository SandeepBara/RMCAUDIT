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
                    <li><a href="#">Tread</a></li>
                    <li class="active">Application Against Denial </li>
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
                                    <h5 class="panel-title">Entry Summary</h5>
                                </div>
                                <div class="panel-body">
                                    <div class ="row">
                                        <div class="col-md-12">
                                            <form class="form-horizontal" method="post" action="<?=base_url("Trade_report/entry_detail_report?page=clr")?>" >
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
														<label class="control-label" for="ward_id"><b>Ward No.</b><span class="text-danger"></span> </label>
														<select id="ward_id" name="ward_id" class="form-control">
														   <option value="">ALL</option> 
                                                           <?php
                                                           foreach($ward as $value)
                                                           {
                                                                ?>
                                                                <option value="<?=$value['id']?>" <?=isset($ward_id)  && $ward_id==$value['id'] ? 'selected':''?>><?=$value['ward_no']?></option>
                                                                <?php
                                                           }
                                                           ?>														
														</select> 
													</div>
                                                    <div class="col-md-2 ">
														<label class="control-label" for="department_mstr_id"><b>Entry Type</b><span class="text-danger">*</span></label>
														<select id="entry_type" name="entry_type" class="form-control" required>
                                                           <option value="">Select</option>
                                                           <option value="1" <?=isset($entry_type)  && $entry_type=='1' ? 'selected':''?>>NEW</option>
                                                           <option value="2" <?=isset($entry_type)  && $entry_type=='2' ? 'selected':''?>>EXISTING</option>
                                                        </select>
													</div>
                                                    <div class="col-md-2 ">
														<label class="control-label" for="department_mstr_id"><b>Operator</b></label>
                                                        <select id="oprator_id" name="oprator_id" class="form-control">
														   <option value="">ALL</option> 
                                                           <?php
                                                           if(isset($oprator))
                                                           {
                                                                foreach($oprator as $value)
                                                                {
                                                                        ?>
                                                                        <option value="<?=$value['id']?>" <?=isset($oprator_id)  && $oprator_id==$value['id'] ? 'selected':''?>><?=$value['emp_name']?><i> (<?=$value['user_type']?>)</i></option>
                                                                        <?php
                                                                }
                                                           }   
                                                           ?>														
														</select>
														
													</div>

													<div class="col-md-2 ">
														<label class="control-label" for="department_mstr_id">&nbsp;</label>
														<button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
													</div>
												</div>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <?php 
                                if(!empty($report))
                                {
                                    ?>
                                    <div class="panel panel-bordered panel-dark">
                                        <div class="panel-heading text-center">
                                            <h5 class="panel-title"><?=$ulb_dtl['ulb_name']?></h5>                                           
                                        </div>
                                        <div class="panel-body">
                                            <div class ="row">
                                                <div class="col-md-12">
                                                    <div class="text-center">
                                                        <p>Operator Wise Application Entry Report of Municipal License</p>
                                                        <p>From <?=$from_date?> To <?=$to_date?></p>
                                                    </div>
                                                    <button class="btn btn-info pull-right" type="button" onclick="export_to_ex()">Export</button>
                                                    <div id="printable" class="table-responsive">
                                                        <table class="table table-striped table-responsive table-bordered" id='empTable'>
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Operator Name</th>
                                                                    <th>Date</th>
                                                                    <th>Application Type</th>
                                                                    <th>Application No</th>
                                                                    <th>Firm Name</th>
                                                                    <th>Ward No.</th>
                                                                    <th>Address</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php 
                                                                    if(empty($report))
                                                                    {
                                                                        ?>
                                                                            <tr>
                                                                                <td class="text-center text-danger">! NO DATA</td>
                                                                            </tr>
                                                                        <?php
                                                                    }
                                                                    else
                                                                    {
                                                                        $i=$offset;
                                                                        foreach($report as $val)
                                                                        {
                                                                            ?>
                                                                                <tr>
                                                                                    <td><?=++$i?></td>
                                                                                    <td><?=!empty($val['emp_name'])?$val['emp_name']:'N/A'?></td>
                                                                                    <td><?=!empty($val['apply_date'])?$val['apply_date']:'N/A'?></td>
                                                                                    <td><?=!empty($val['application_type'])?$val['application_type']:'N/A'?></td>
                                                                                    <td><?=!empty($val['application_no'])?$val['application_no']:'N/A'?></td>
                                                                                    <td><?=!empty($val['firm_name'])?$val['firm_name']:'N/A'?></td>
                                                                                    <td><?=!empty($val['ward_no'])?$val['ward_no']:'N/A'?></td>
                                                                                    <td><?=!empty($val['address'])?$val['address']:'N/A'?></td>
                                                                                </tr>
                                                                            <?php
                                                                         }
                                                                    }
                                                                ?>
                                                                
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <?=pagination($count,$offset)?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            ?>
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
   

</script>
<script type="text/javascript">
    function myPopup(myURL, title, myWidth, myHeight)
    {
        var left = (screen.width - myWidth) / 2;
        var top = (screen.height - myHeight) / 4;
        var myWindow = window.open(myURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + myWidth + ', height=' + myHeight + ', top=' + top + ', left=' + left);
    }

    function export_to_ex()
    {   
        //alert(data);
        debugger;
        var form_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var ward_id = $("#ward_id").val();
        var entry_type = $("#entry_type").val();
        var oprator_id = $("#oprator_id").val();
        // alert(form_date);
        // alert(to_date);
        // alert(ward_id);
        // alert(entry_type);
         alert(oprator_id);

        $.ajax({
                type:"POST",
                url: '<?php echo base_url("Trade_report/entry_detail_reportAjax");?>',
                dataType: "json",
                data: {
                        "form_date":form_date,
                        "to_date":to_date,
                        "ward_id":ward_id,
                        "entry_type":entry_type,
                        "oprator_id":oprator_id,
                 },               
                success:function(data){
                //console.log(data);
                    alert();
                   if (data.response==true) 
                   {
                        
                   }  
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert(JSON.stringify(jqXHR));
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
              });
    }

    $(document).ready(function(){
        //$.fn.dataTable.ext.errMode = 'throw';
        var dataTable = $('#empTable').DataTable({
            'responsive': true,
            'processing': true,
            
            "deferLoading": 0, // default ajax call prevent
            // 'serverSide': true,
            dom: 'Bfrtip',
            lengthMenu: [
                [10, 25, 50, 5000],
                ['10 rows', '25 rows', '50 rows', '5000 rows']
            ],
            buttons: [
                {
                    extend:    'copyHtml5',
                    text:      '<i class="fa fa-files-o"></i>',
                    titleAttr: 'Copy'
                },
                {
                    extend:    'excelHtml5',
                    text:      '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'Excel'
                },
                {
                    extend:    'csvHtml5',
                    text:      '<i class="fa fa-file-text-o"></i>',
                    titleAttr: 'CSV'
                },
                {
                    extend:    'pdfHtml5',
                    text:      '<i class="fa fa-file-pdf-o"></i>',
                    titleAttr: 'PDF'
                }
            ],
            // 'ajax': {
            //     "type": "POST",
            //     'url':'<?=base_url('prop_report/SAFcollectionReportAjax');?>',
            //     dataSrc: function ( data ) {
            //         total_collection = data.total_collection;
            //         recordsTotal  = data.recordsTotal;
            //         return data.data;
            //     },
            //     "deferRender": true,
            //     "dataType": "json",
            //     'data': function(data){
            //         console.log($('#from_date').val());
            //         // Append to data
            //         data.search_from_date = $('#from_date').val();
            //         data.search_upto_date = $('#upto_date').val();
            //         data.search_ward_mstr_id = $('#ward_mstr_id').val();
            //         data.search_collector_id = $('#collector_id').val();
            //         data.search_tran_mode_mstr_id = $('#tran_mode_mstr_id').val();
                    
            //     },
            //     beforeSend: function () {
            //         $("#btn_search").val("LOADING ...");
            //         $("#loadingDiv").show();
            //     },
            //     complete: function () {
            //     $("#btn_search").val("SEARCH");
            //     $("#loadingDiv").hide();
            //     },
            // },
            
            // 'columns': [
            //     { 'data': 's_no' },
            //     { 'data': 'ward_no' },
            //     { 'data': 'saf_no' },
            //     { 'data': 'holding_no' },
            //     { 'data': 'owner_name' },
            //     { 'data': 'mobile_no' },
            //     { 'data': 'from_upto_fy_qtr' },
            //     { 'data': 'tran_date' },
            //     { 'data': 'transaction_mode' },
            //     { 'data': 'payable_amt' },
            //     { 'data': 'emp_name' },
            //     { 'data': 'tran_no' },
            //     { 'data': 'cheque_no' },
            //     { 'data': 'bank_name' },
            //     { 'data': 'branch_name' },
            // ],
            // drawCallback: function( settings )
            // {
            //     try
            //     {
            //         $("#footerResult").html(" (Total Holding - "+recordsTotal+", Total Collection - "+total_collection+")");
            //         var api = this.api();
            //         $(api.column(2).footer() ).html(recordsTotal);
            //         $(api.column(9).footer() ).html(total_collection);
            //     }
            //     catch(err)
            //     {
            //         console.log(err.message);
            //     }
            // }
        });
        // $('#btn_search').click(function(){
        //     dataTable.draw();
        // });
        //dataTable.draw();
    });

</script>