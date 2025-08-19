<?= $this->include('layout_vertical/header');?>
<div id="content-container">
    <div id="page-head">
        <ol class="breadcrumb">
        <li><a href="#"><i class="demo-pli-home"></i></a></li>
        <li><a href="#">SAF</a></li>
        <li class="active">Applied Application</li>
        </ol>
    </div>
    <div id="page-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h5 class="panel-title">SAF Search</h5>
                    </div>
                    <div class="panel-body">
                        <div class ="row">
                            <div class="col-md-12">
                                <form class="form-horizontal" method="get" action="<?php echo base_url('bo_saf/index');?>">
                                    <div class="form-group">
                                        <div class="col-md-2">
                                            <label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
                                            <input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>" >
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label" for="to_date"><b>To Date</b> <span class="text-danger">*</span></label>
                                            <input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" >
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label" for="ward No"><b>Ward No</b><span class="text-danger">*</span> </label>
                                            <select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
                                                <option value="All">ALL</option> 
                                                <?php 
                                                foreach($wardList as $value)
                                                {
                                                    ?>
                                                    <option value="<?=$value['ward_mstr_id']?>" <?=(isset($ward_mstr_id, $ward_mstr_id) && ($ward_mstr_id==$value["ward_mstr_id"])) ? "selected" : NULL;?>>
                                                    <?=$value['ward_no'];?>
                                                    </option>
                                                    <?php 
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label" for="pending_on"><b>Pending On</b><span class="text-danger">*</span> </label>
                                            <select id="pending_on" name="pending_on" class="form-control">
                                                <option value="All">All</option> 
                                                <option value="Payment Done But Document Upload Is Pending" <?=(isset($pending_on) && ($pending_on=="Payment Done But Document Upload Is Pending")) ? "selected" : NULL;?>>Payment Done But Document Upload Is Pending</option>
                                                <option value="Document Upload Done But Payment Is Pending" <?=(isset($pending_on) && ($pending_on=="Document Upload Done But Payment Is Pending")) ? "selected" : NULL;?>>Document Upload Done But Payment Is Pending</option> 
                                                <option value="Payment Pending And Document Upload Pending" <?=(isset($pending_on) && ($pending_on=="Payment Pending And Document Upload Pending")) ? "selected" : NULL;?>>Payment Pending And Document Upload Pending</option>
                                                <option value="Payment Done" <?=(isset($pending_on) && ($pending_on=="Payment Done")) ? "selected" : NULL;?>>Payment Done</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label" for="property_type"><b>Property type</b><span class="text-danger">*</span> </label>
                                            <select id="assessment_type" name="property_type" class="form-control">
                                                <option value="All">All</option> 
                                                <option value="1" <?=(isset($property_type) && ($property_type=="1")) ? "selected" : NULL;?>>SUPER STRUCTURE</option>
                                                <option value="2" <?=(isset($property_type) && ($property_type=="2")) ? "selected" : NULL;?>>INDEPENDENT BUILDING</option> 
                                                <option value="3" <?=(isset($property_type) && ($property_type=="3")) ? "selected" : NULL;?>>FLATS / UNIT IN MULTI STORIED BUILDING</option>
                                                <option value="4" <?=(isset($property_type) && ($property_type=="4")) ? "selected" : NULL;?>>VACANT LAND</option>
                                                <option value="5" <?=(isset($property_type) && ($property_type=="5")) ? "selected" : NULL;?>>OCCUPIED PROPERTY</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label" for="assessment_type"><b>Assessment type</b><span class="text-danger">*</span> </label>
                                            <select id="assessment_type" name="assessment_type" class="form-control">
                                                <option value="All">All</option> 
                                                <option value="New Assessment" <?=(isset($assessment_type) && ($assessment_type=="New Assessment")) ? "selected" : NULL;?>>New Assessment</option>
                                                <option value="Re-Assessment" <?=(isset($assessment_type) && ($assessment_type=="Re-Assessment")) ? "selected" : NULL;?>>Re-Assessment</option> 
                                                <option value="Mutation" <?=(isset($assessment_type) && ($assessment_type=="Mutation")) ? "selected" : NULL;?>>Mutation</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label" for="to_date"><b>Saf No.</b> <span class="text-danger">*</span></label>
                                            <input type="text" id="saf_no" name="saf_no" class="form-control" placeholder="Saf No." value="<?=(isset($saf_no))?$saf_no:"";?>" >
                                        </div>
                                        <div class="col-md-2"></div>
                                        <div class="col-md-2">
                                            <label class="control-label" for="btn_search">&nbsp;</label>
                                            <button class="btn btn-primary btn-block" id="btn_search" type="submit">Search</button>
                                        </div>
                                        <div class="col-md-4"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="page-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <div class="panel-control">
                            <?php if (isset($from_date) && isset($to_date) && isset($ward_mstr_id) && isset($pending_on) && isset($property_type) && isset($assessment_type) && isset($saf_no)) {?>
                                <a target="_blank" href="<?=base_url();?>/BO_SAF/appliedApplicationExport?from_date=<?=$from_date;?>&to_date=<?=$to_date;?>&ward_mstr_id=<?=$ward_mstr_id;?>&pending_on=<?=$pending_on;?>&property_type=<?=$property_type;?>&assessment_type=<?=$assessment_type;?>&saf_no=<?=$saf_no;?>" class="btn btn-mint">Excel Export</a>
                                <a target="_blank" href="<?=base_url();?>/BO_SAF/appliedApplicationExportFull?from_date=<?=$from_date;?>&to_date=<?=$to_date;?>&ward_mstr_id=<?=$ward_mstr_id;?>&pending_on=<?=$pending_on;?>&property_type=<?=$property_type;?>&assessment_type=<?=$assessment_type;?>&saf_no=<?=$saf_no;?>" class="btn btn-mint">Export Full</a>
                            <?php }?>
                        </div>
                        <h5 class="panel-title">SAF List</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="table-responsive">
                                <table id="" class="table table-striped table-bordered text-sm" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Ward No.</th>
                                            <th>SAF No.</th>
                                            <th>Owner Name</th>
                                            <th>Mobile No.</th>
                                            <th>Property Type</th>
                                            <th>Assessment Type</th>
                                            <th>Apply Date</th>
                                            <th>Apply By</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                            //print_r($owner);
                                    if(isset($posts['result'], $posts['result']))
                                    {
                                        foreach ($posts['result'] as $value)
                                        {
                                            ?>
                                            <tr>
                                                <td><?=++$posts['offset'];?></td>
                                                <td><?=$value["ward_no"];?></td>
                                                <td><?=$value["saf_no"];?></td>
                                                <td><?=$value["owner_name"];?></td>
                                                <td><?=$value["mobile_no"];?></td>
                                                <td><?=$value["property_type"];?></td>
                                                <td><?=$value["assessment_type"];?></td>
                                                <td><?=$value["apply_date"];?> </td>
                                                <td><?=$value["emp_name"];?></td>
                                                <td>
                                                    <a class="btn btn-primary" href="<?=base_url('safdtl/full/'.$value['id']);?>" role="button">View</a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <?=isset($posts['count'])?pagination($posts['count']):null;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->include('layout_vertical/footer');?>


<script type="text/javascript">
    
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

