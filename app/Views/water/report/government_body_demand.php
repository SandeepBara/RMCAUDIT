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
                    <li class="active"> Government Body Demand  </li>
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
                                    <h5 class="panel-title">Government Body Demand Report</h5>
                                </div>
                                <div class="panel-body">
                                    <div class ="row">
                                        <div class="col-md-12">
                                            <form class="form-horizontal" method="post" action="<?=base_url('water_report/government_body_demand')?>">
                                                <div class="form-group">
                                                    <!-- <div class="col-md-3">
														<label class="control-label" for="fin_year"><b>Fin Year</b> <span class="text-danger">*</span></label>
														<select id="fin_year" name="fin_year" class="form-control">
                                                           <?php
                                                           foreach($fin_year_list as $value)
                                                           {
                                                                ?>
                                                                <option value="<?=$value?>" <?=isset($_POST) && !empty($_POST) && set_value('fin_year')==$value ? 'selected':''?>><?=$value?></option>
                                                                <?php
                                                           }
                                                           ?>															
														</select>
													</div> -->
													<div class="col-md-2">
                                                        <label class="control-label" for="Catgory"><b>Catgory</b><span class="text-danger">*</span> </label>
                                                        <select name="category" id="category" class="form-control">
                                                            <option value="">All</option>
                                                            <option value="APL" <?=isset($_POST['category']) && $_POST['category']=='APL'?'selected':''?>>APL</option>
                                                            <option value="BPL" <?=isset($_POST['category']) && $_POST['category']=='BPL'? 'selected':''?>>BPL</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="control-label" for="Fin Year"><b>Connection Type</b><span class="text-danger">*</span> </label>
                                                        <select name="connection_type" id="connection_type" class="form-control">
                                                            <option value="">All</option>
                                                            <option value="Meter" <?=isset($_POST['connection_type']) && $_POST['connection_type']=='Meter'?'selected':''?>>Meter</option>
                                                            <option value="Non-Meter" <?=isset($_POST['connection_type']) && $_POST['connection_type']=='Non-Meter'?'selected':''?>>Non-Meter</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
														<label class="control-label" for="ward_id"><b>Ward</b><span class="text-danger">*</span> </label>
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
                                                    
													<div class="col-md-2 ">
														<label class="control-label" for="department_mstr_id">&nbsp;</label>
														<button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
													</div>
												</div>
                                            </form>
                                        </div>
                                    </div>
                               
                                    <div class="row">
                                    <div class="table-responsive" >
                                        <table id="demo_dt_basic" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>   
                                                <th>Consumer No.</th> 
                                                <th>Ward No.</th>  
                                                <th>Consumer Name</th>
                                                <th>Property Type</th>
                                                <th>Outstanding at the begining</th>
                                                <th>Current Demand</th>
                                                <th>Total Demand</th>
                                                <th>Old Due Collection</th>
                                                <th>Current Collection</th>
                                                <th>Old Due</th>
                                                <th>Current Due</th>
                                                <th>Outstanding Due</th> 
                                            </tr>
                                            
                                        </thead>
                                        <tbody>
                                        <?php
                                        
                                        if(!isset($consumer)||empty($consumer['result'])):
                                        ?>
                                                <tr>
                                                    <td colspan="16" style="text-align: center; color:red;">Data Not Available!!</td>
                                                </tr>
                                        <?php else:
                                                $i=$consumer['offset'];
                                                //$i=0;
                                                
                                                foreach ($consumer['result'] as $val):
                                                    
                                        ?>
                                                <tr>
                                                    <td><?=++$i;?></td>                                                    
                                                    <td><?=isset($val['consumer_no']) ? $val['consumer_no']:"N/A";?></td>
                                                    <td><?=isset($val['ward_no']) ? $val['ward_no']:"N/A";?></td>
                                                    <td><?=isset($val['applicant_name']) ? $val['applicant_name'] :"N/A";?></td>
                                                    <td><?=isset($val['property_type']) ? $val['property_type'] :"N/A";?></td>
                                                    <td><?=isset($val['outstanding_at_begin']) ? $val['outstanding_at_begin'] :"N/A";?></td>
                                                    <td><?=isset($val['current_demand']) ? $val['current_demand'] :"N/A";?></td>
                                                    <td><?=$val['outstanding_at_begin']+$val['current_demand'] ;?></td>
                                                    <td><?=isset($val['arrear_coll']) ? $val['arrear_coll'] :"N/A";?></td>
                                                    <td><?=isset($val['curr_coll']) ? $val['curr_coll'] :"N/A";?></td>
                                                    <td><?=isset($val['old_due']) ? $val['old_due'] :"N/A";?></td>
                                                    <td><?=isset($val['curr_due']) ? $val['curr_due'] :"N/A";?></td>
                                                    <td><?=isset($val['outstanding']) ? $val['outstanding'] :"N/A";?></td>                                                    
                                                    
                                                    
                                                    
                                                </tr>

                                            <?php endforeach;?>
                                        <?php endif;  ?>
                                        </tbody>
                                    </table>
                                    </div>
                                    <?=isset($consumer['count'])?pagination($consumer['count']):null;?>
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