<?= $this->include('layout_vertical/header');?>


            <div id="content-container">
                <div id="page-head">
                    <ol class="breadcrumb">
					<li><a href="#"><i class="demo-pli-home"></i></a></li>
					<li><a href="#">EO SAF</a></li>
					<li class="active">EO SAF List</li>
                    </ol>
                </div>
                <div id="page-content">
					<div class="row">
					    <div class="col-sm-12">
					        <div class="panel panel-bordered panel-dark">
					            <div class="panel-heading">
					                <h5 class="panel-title">EO SAF List</h5>
					            </div>
                                <div class="panel-body">
                                    <div class ="row">
                                        <div class="col-md-12">
                                            <form class="form-horizontal" method="post" action="<?php echo base_url('eo_saf/index');?>">
                                                <div class="form-group">
                                                    <div class="col-md-3">
                                                    <label class="control-label" for="from_date"><b>From Date</b> </label>
                                                    <div class="input-group date">
                                                        <input type="text" id="from_date" name="from_date" class="form-control mask_date" placeholder="From Date" value="<?=$from_date;?>" readonly>
                                                        <span class="input-group-addon"><i class="demo-pli-calendar-4"></i></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="control-label" for="to_date"><b>To Date</b> </label>
                                                    <div class="input-group date">
                                                        <input type="text" id="to_date" name="to_date" class="form-control mask_date" placeholder="To Date" value="<?=$to_date;?>" readonly>
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
                                                <th>SAF No.</th>
                                                <th>Owner Name</th>
                                                <th>Mobile No.</th>
                                                <th>Assessment Type</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php
                                        $i=0;
                                        foreach ($inboxList as $value)
                                        {
                                            ?>
                                            <tr>
                                                <td><?=++$i;?></td>
                                                <td><?=$value["ward_no"];?></td>
                                                <td><?=$value["saf_no"];?></td>
                                                <td><?=$value["owner_name"];?></td>
                                                <td><?=$value["mobile_no"];?></td>
                                                <td><?=$value["assessment_type"];?></td>
                                                <td>
                                                    <a class="btn btn-primary btn-sm" href="<?=base_url('EO_SAF/view2/'.md5($value['saf_dtl_id']));?>">View</a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
 					                    </tbody>
					                </table>
									    <?=pagination($pager);?>
                                        </div>
                                    </div>
                                </div>
					        </div>
					    </div>
					</div>
                </div>
            </div>
<?= $this->include('layout_vertical/footer');?>
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
		
	});
 </script>

