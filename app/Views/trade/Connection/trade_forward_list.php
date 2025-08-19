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
					<li class="active">Forward and Backward List</li>
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
					                <h5 class="panel-title">Forward and Backward List</h5>
					            </div>
                                <div class="panel-body">
                                    <div class ="row">
                                        <div class="col-md-12">
                                            <form class="form-horizontal" method="post" action="<?php echo base_url('trade_da/forward_list');?>">
                                                <div class="form-group">
                                                    <div class="col-md-3">
                                                    <label class="control-label" for="from_date"><b>From Date</b> </label>
                                                    <div class="input-group date">
                                                        <input type="readonly" id="from_date" name="from_date" class="form-control mask_date" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>">
                                                        <span class="input-group-addon"><i class="demo-pli-calendar-4"></i></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="control-label" for="to_date"><b>To Date</b> </label>
                                                    <div class="input-group date">
                                                        <input type="readonly" id="to_date" name="to_date" class="form-control mask_date" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>">
                                                        <span class="input-group-addon"><i class="demo-pli-calendar-4"></i></span>
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                    <label class="control-label" for="ward No"><b>Ward No</b><span class="text-danger">*</span> </label>
                                                    <select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
                                                       <option value="">ALL</option> 
                                                        <?php foreach($wardList as $value):?>
                                                        <option value="<?=$value['ward_mstr_id']?>" <?=(isset($ward_mstr_id))?$ward_mstr_id==$value["ward_mstr_id"]?"SELECTED":"":"";?>><?=$value['ward_no'];?>
                                                        </option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="control-label" for="department_mstr_id">&nbsp;</label>
                                                    <button class="btn btn-success btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
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
                                                <th>Ward No.</th>
                                                <th>Application No.</th>
                                                <th>Owner Name</th>
                                                <th>Mobile No.</th>
                                                <th>Forwarded To</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php
                                            //print_r($owner);
                                    if(isset($posts)):
                                          if(empty($posts)):
                                    ?>
                                            <tr>
                                                <td colspan="7" style="text-align: center;">Data Not Available!!</td>
                                            </tr>
                                    <?php else:
                                            $i=0;
                                            foreach ($posts as $value):
                                    ?>
                                            <tr>
                                                <td><?=++$i;?></td>
                                                <td><?=$value["ward_no"];?></td>
                                                <td><?=$value["application_no"];?></td>
                                                <td>
                                                    <?php
                                                    if(isset($value["owner_name"])):
                                                         if(empty($value["owner_name"])):
                                                    ?>
                                                            <span class="text-danger">N/A</span>
                                                    <?php else:
                                                            $catArray = [];
                                                    foreach($value["owner_name"] as $owner_name) { 
                                                        $catArray[] = $owner_name; 
                                                    }
                                                    echo implode(', ', $catArray);
                                                    endif;  
                                                 endif;  ?>

                                                    </td>

                                                <td>
                                                    <?php
                                                    if(isset($value["mobile_no"])):
                                                         if(empty($value["mobile_no"])):
                                                    ?>
                                                            <span class="text-danger">N/A</span>
                                                    <?php else:

                                                             $catArray = [];
                                                    foreach($value["mobile_no"] as $mobile_no) { 
                                                        $catArray[] = $mobile_no; 
                                                    }
                                                    echo implode(', ', $catArray);
                                                        endif;  
                                                 endif;  ?>

                                                    </td>
                                                <td><?=$value["user_type"];?></td>
                                                <td>
                                                    <a class="btn btn-primary" href="<?php echo base_url('trade_da/forward_view/'.md5($value['id']));?>" role="button">View</a>

                                                </td>
                                            </tr>
                                        <?php endforeach;?>
                                         <?php endif;  ?>
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
<script src="<?=base_url();?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script type="text/javascript">
    $('#from_date').datepicker({ 
    	format: "yyyy-mm-dd",
    	weekStart: 0,
    	autoclose:true,
    	todayHighlight:true,
    	todayBtn: "linked",
    	clearBtn:true,
    	daysOfWeekHighlighted:[0]
    });
    $('#to_date').datepicker({ 
    	format: "yyyy-mm-dd",
    	weekStart: 0,
    	autoclose:true,
    	todayHighlight:true,
    	todayBtn: "linked",
    	clearBtn:true,
    	daysOfWeekHighlighted:[0]
    });
	$(document).ready(function() {
        $("#from_date").change(function ()
        {
            var from_date= $("#from_date").val();
            var to_date= $("#to_date").val();

            var startDay = new Date(from_date);
            var endDay = new Date(to_date);

            if((startDay.getTime())>(endDay.getTime()))
            {
                alert("Please select valid To Date!!");
                $("#from_date").val('');
            }
        });
        $("#to_date").change(function ()
        {
            var from_date= $("#from_date").val();
            var to_date= $("#to_date").val();

            var startDay = new Date(from_date);
            var endDay = new Date(to_date);

            if((startDay.getTime())>(endDay.getTime()))
            {
                alert("Please select valid To Date!!");
                $("#to_date").val('');
            }
        });

        $("#btn_search").click(function(){
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            if(from_date=="")
			{
				alert("Please Select From Date");
				$('#from_date').focus();
				return false;
			}

			if(to_date=="")
			{
				alert("Please Select To date");
				$('#to_date').focus();
				return false;
			}
        });
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
				exportOptions: { columns: [ 0, 1,2,3,4,5] }
			}, {
				text: 'pdf',
				extend: "pdf",
				title: "Report",
				download: 'open',
				footer: { text: '' },
				exportOptions: { columns: [ 0, 1,2,3,4,5] }
			}]
		});
	});
 </script>

