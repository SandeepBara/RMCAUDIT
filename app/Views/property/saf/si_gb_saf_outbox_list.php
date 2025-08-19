<?= $this->include('layout_vertical/header');?>
<div id="content-container">
    <div id="page-head">
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">GB SAF</a></li>
            <li class="active">GB SAF Outbox</li>
        </ol>
    </div>
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">GB SAF Outbox</h5>
            </div>
            <div class="panel-body">
                <div class ="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" method="post" action="<?php echo base_url('si_saf/gb_outbox');?>">
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label" for="ward_mstr_id No"><b>Ward No</b><span class="text-danger">*</span> </label>
                                    <select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
                                        <option value="">ALL</option> 
                                        <?php foreach($wardList as $value):?>
                                            <option value="<?=$value['ward_mstr_id']?>" <?=(isset($ward_mstr_id))?$ward_mstr_id==$value["ward_mstr_id"]?"SELECTED":"":"";?>><?=$value['ward_no'];?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label" for="search_param"><b>To Date</b> </label>
                                    <input type="text" id="search_param" name="search_param" class="form-control" placeholder="Enter keyword" value="<?=$search_param??"";?>" />
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label" for="btn_search">&nbsp;</label>
                                    <button class="btn btn-success btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Outbox</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="demo_dt_basic" class="table table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ward No.</th>
                                        <th>SAF No.</th>
                                        <th>Office Name</th>
                                        <th>Building Name</th>
                                        <th>Address</th>
                                        <th>Forward date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $i=0; foreach ($inboxList as $value) { ?>
                                    <tr>
                                        <td><?=++$i;?></td>
                                        <td><?=$value["ward_no"];?></td>
                                        <td><?=$value["application_no"];?></td>
                                        <td><?=$value["office_name"];?></td>
                                        <td><?=$value["building_colony_name"];?></td>
                                        <td><?=$value["building_colony_address"];?></td>
                                        <td><?=$value["forward_date"];?></td>
                                        <td><a class="btn btn-primary" href="<?php echo base_url('govsafDetailPayment/gov_saf_application_details/'.md5($value['id']));?>" role="button">View</a></td>
                                    </tr>
                                <?php } ?>
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

