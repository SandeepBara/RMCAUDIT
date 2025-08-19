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
                    <li class="active">Consumer Deactivation List</li>
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
                                    <h5 class="panel-title">Consumer Deactivation List</h5>
                                </div>
                                <div class="panel-body">
                                    <div class ="row">
                                        <div class="col-md-12">
                                            <form class="form-horizontal" method="post" action="<?=base_url('');?>/ConsumerDeactivationReport/detail">
                                                <div class="form-group">
                                                    <div class="col-md-3">
														<label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
														<input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
													</div>
													<div class="col-md-3">
														<label class="control-label" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
														<input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
													</div>
													<div class="col-md-3">
														<label class="control-label" for="Ward"><b>Ward</b><span class="text-danger">*</span> </label>
														<select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
														   <option value="">ALL</option>  
															<?php foreach($wardList as $value):?>
															<option value="<?=$value['id']?>" <?=(isset($ward_mstr_id))?$ward_mstr_id==$value["id"]?"SELECTED":"":"";?>><?=$value['ward_no'];?>
															</option>
															<?php endforeach;?>
														</select>
													</div>
													<div class="col-md-3">
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
                                                <th>#</th>
                                                <th>Ward No</th>
                                                <th>Deactivation Date</th>
                                                <th>Consumer No</th>
                                                <th>Consumer Name</th>
                                                <th>Father Name</th>
                                                <th>Mobile No</th>
                                                <th>Category</th>
                                                <th>Area</th>
                                                <th>Document</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php
                                    if(!isset($consumerDeactivationList)):
                                    ?>
                                        <tr>
                                            <td colspan="11" style="text-align: center;">Data Not Available!!</td>
                                        </tr>
                                    <?php else:
                                            $i=0;   $path_m='';
                                            $ulb_m=session()->get('ulb_dtl')['ulb_mstr_id'];
                                            if($ulb_m==1)
                                                $path_m='RANCHI';
                                            elseif($ulb_m==2)
                                                $path_m='DHANBAD';
                                        foreach ($consumerDeactivationList as $value):
                                    ?>
                                        <tr>
                                            <td><?=++$i;?></td>
                                            <td><?=$value['ward_no']!=""?$value['ward_no']:"N/A";?></td>
                                            <td><?=$value['deactivation_date']!=""?date('d-m-Y',strtotime($value['deactivation_date'])):"N/A";?></td>
                                            <td><?=$value['consumer_no']!=""?$value['consumer_no']:"N/A";?></td>
                                            <td><?=$value['applicant_name']!=""?$value['applicant_name']:"N/A";?></td>
                                            <td><?=$value['father_name']!=""?$value['father_name']:"N/A";?></td>
                                            <td><?=$value['mobile_no']!=""?$value['mobile_no']:"N/A";?></td>
                                            <td><?=$value['category']!=""?$value['category']:"N/A";?></td>
                                            <td><?=$value['area_sqft']!=""?$value['area_sqft']:"N/A";?></td>
                                            <td>
                                                <a onclick="myPopup('<?=base_url();?>/getImageLink.php?path=<?=$value['doc_path'];?>','xtf','900','700');">
                                                <?php
                                                $extention = strtolower(explode('.',  $value['doc_path'])[1]??"");
                                                    if ($extention!="pdf")
                                                    {
                                                        ?>
                                                    <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;">  
                                                    <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;">
                                                        <?php
                                                    }
                                                    ?>

                                                </a>                                                
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    <?php endif;  ?>
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
    $(document).ready(function(){
        $('#demo_dt_basic').DataTable({
            responsive: true,
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
                exportOptions: { columns: [ 0, 1,2,3,4,5,6,7,8] }
            }, {
                text: 'pdf',
                extend: "pdf",
                title: "Report",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3,4,5,6,7,8] }
            }]
        });
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
function myPopup(myURL, title, myWidth, myHeight)
{
    var left = (screen.width - myWidth) / 2;
    var top = (screen.height - myHeight) / 4;
    var myWindow = window.open(myURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + myWidth + ', height=' + myHeight + ', top=' + top + ', left=' + left);
}
</script>