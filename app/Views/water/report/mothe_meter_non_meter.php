
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
                    <li class="active">Meter Non-Meter Connection</li>
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
                                    <h5 class="panel-title">Month Wise Meter Non-Meter Connection</h5>
                                </div>
                                <div class="panel-body">
                                    <div class ="row">
                                        <div class="col-md-12">
                                            <form class="form-horizontal" method="post" action="<?=base_url("Water_report/mothe_meter_non_meter")?>" >
                                                <div class="form-group">                                                    
                                                    <div class="col-md-4 ">
														<label class="control-label" for="fy_year"><b>Fy Year</b></label>
														<select id="fy_year" name="fy_year" class="form-control" >                                                            
                                                            <?php
                                                            if(isset($fy_list))
                                                            {
                                                                foreach($fy_list as $val)
                                                                {
                                                                    ?>
                                                                        <option value="<?=$val?>" <?=isset($_POST['fy_year']) && $_POST['fy_year']==$val?"selected":''?>><?=$val?></option>
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
                            <?php //echo(md5('clr'));
                                if(!empty($reports))
                                { //print_var($records_application);
                                    ?>
                                    <div class="panel panel-bordered panel-dark">
                                        <div class="panel-heading">
                                            <h5 class="panel-title">Meter Report:Water</h5>                                           
                                        </div>
                                        <div class="panel-body " id="printable">
                                            <div class ="row panel panel-bordered panel-dark">                                               
                                                <div class="col-md-12 table-responsive"> 
                                                                                                   
                                                    <!-- <button class="btn btn-info pull-right" type="button" onclick="export_to_ex()">Export</button> -->
                                                    <div >
                                                    <button class="bg-success" onclick="ExportToExcel('xlsx')" style="margin-left: 20px;">Export Excel</button>
                                                        <table class="table table-striped table-responsive table-bordered" id='empTable'>
                                                            <thead>
                                                                <tr>
                                                                    <th rowspan="2">#</th>  
                                                                    <th colspan="2">Jan</th>
                                                                    <th colspan="2">Feb</th>
                                                                    <th colspan="2">Mar</th>
                                                                    <th colspan="2">Apr</th>
                                                                    <th colspan="2">May</th>
                                                                    <th colspan="2">Jun</th>
                                                                    <th colspan="2">Jul</th>
                                                                    <th colspan="2">Aug</th>
                                                                    <th colspan="2">Sep</th>
                                                                    <th colspan="2">Oct</th>
                                                                    <th colspan="2">Nov</th>
                                                                    <th colspan="2">Dec</th>                                                                                                         
                                                                </tr>
                                                                <tr>
                                                                    <td>Meter</td>
                                                                    <td>Non-Meter</td>
                                                                    <td>Meter</td>
                                                                    <td>Non-Meter</td>
                                                                    <td>Meter</td>
                                                                    <td>Non-Meter</td>
                                                                    <td>Meter</td>
                                                                    <td>Non-Meter</td>
                                                                    <td>Meter</td>
                                                                    <td>Non-Meter</td>
                                                                    <td>Meter</td>
                                                                    <td>Non-Meter</td>
                                                                    <td>Meter</td>
                                                                    <td>Non-Meter</td>
                                                                    <td>Meter</td>
                                                                    <td>Non-Meter</td>
                                                                    <td>Meter</td>
                                                                    <td>Non-Meter</td>
                                                                    <td>Meter</td>
                                                                    <td>Non-Meter</td>
                                                                    <td>Meter</td>
                                                                    <td>Non-Meter</td>
                                                                    <td>Meter</td>
                                                                    <td>Non-Meter</td>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php 
                                                                    if(!$reports)
                                                                    {
                                                                        ?>
                                                                            <tr>
                                                                                <td colspan="25" class="text-center text-danger">! NO DATA</td>
                                                                            </tr>
                                                                        <?php
                                                                    }
                                                                    else
                                                                    {
                                                                        ?>
                                                                        <tr>
                                                                            <td>1</td>
                                                                            <td><?=$jan && $jan[0] ? $jan[0]['total']: 0 ;?></td>
                                                                            <td><?=$jan && $jan[1] ? $jan[1]['total']: 0 ;?></td>
                                                                            <td><?=!empty($feb) && !empty($feb[0]) ? $feb[0]['total']: 0 ;?></td>
                                                                            <td><?=!empty($feb) && !empty($feb[1]) ? $feb[1]['total']: 0 ;?></td>
                                                                            <td><?=!empty($mar) && !empty($mar[0]) ? $mar[0]['total']: 0 ;?></td>
                                                                            <td><?=!empty($mar) && !empty($mar[1]) ? $mar[1]['total']: 0 ;?></td>
                                                                            <td><?=$apr && $apr[0] ? $apr[0]['total']: 0 ;?></td>
                                                                            <td><?=$apr && $apr[1] ? $apr[1]['total']: 0 ;?></td>
                                                                            <td><?=$may && $may[0] ? $may[0]['total']: 0 ;?></td>
                                                                            <td><?=$may && $may[1] ? $may[1]['total']: 0 ;?></td>
                                                                            <td><?=$jun && $jun[0] ? $jun[0]['total']: 0 ;?></td>
                                                                            <td><?=$jun && $jun[1] ? $jun[1]['total']: 0 ;?></td>
                                                                            <td><?=$jul && $jul[0] ? $jul[0]['total']: 0 ;?></td>
                                                                            <td><?=$jul && $jul[1] ? $jul[1]['total']: 0 ;?></td>
                                                                            <td><?=$aug && $aug[0] ? $aug[0]['total']: 0 ;?></td>
                                                                            <td><?=$aug && $aug[1] ? $aug[1]['total']: 0 ;?></td>
                                                                            <td><?=$sep && $sep[0] ? $sep[0]['total']: 0 ;?></td>
                                                                            <td><?=$sep && $sep[1] ? $sep[1]['total']: 0 ;?></td>
                                                                            <td><?=$oct && $oct[0] ? $oct[0]['total']: 0 ;?></td>
                                                                            <td><?=$oct && $oct[1] ? $oct[1]['total']: 0 ;?></td>
                                                                            <td><?=$nov && $nov[0] ? $nov[0]['total']: 0 ;?></td>
                                                                            <td><?=$nov && $nov[1] ? $nov[1]['total']: 0 ;?></td>
                                                                            <td><?=$dec && $dec[0] ? $dec[0]['total']: 0 ;?></td>
                                                                            <td><?=$dec && $dec[1] ? $jan[1]['total']: 0 ;?></td>

                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                ?>
                                                                
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <?=''//pagination($count,$offset)?>
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
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<!--<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script> -->
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

    function ExportToExcel(type, fn, dl) 
    {
        var elt = document.getElementById('empTable');
        var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
        return dl ?
        XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
        XLSX.writeFile(wb, fn || ('AllAttendanceList.' + (type || 'xlsx')));
    }

    $(document).ready(function(){
        //$.fn.dataTable.ext.errMode = 'throw';
        var dataTable = $('#empTable').DataTable({
            'responsive': true,
            'processing': true,
            'search': false,
            
            "deferLoading": 0, // default ajax call prevent
            // 'serverSide': true,
            dom: 'Bfrtip',
            // lengthMenu: [
            //     [10, 25, 50, 5000],
            //     ['10 rows', '25 rows', '50 rows', '5000 rows']
            // ],
            buttons: [
                // {
                //     extend:    'copyHtml5',
                //     text:      '<i class="fa fa-files-o"></i>',
                //     titleAttr: 'Copy'
                // },
                // {
                //     extend:    'excelHtml5',
                //     // text:      '<i class="fa fa-file-excel-o"></i>',
                //     titleAttr: 'Excel'
                // },
                // {
                //     extend:    'csvHtml5',
                //     text:      '<i class="fa fa-file-text-o"></i>',
                //     titleAttr: 'CSV'
                // },
                // {
                //     extend:    'pdfHtml5',
                //     text:      '<i class="fa fa-file-pdf-o"></i>',
                //     titleAttr: 'PDF'
                // }
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