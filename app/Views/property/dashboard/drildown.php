<?= $this->include('layout_vertical/header');?>
<style type="text/css">
    .error{
        color: red;
    }
</style>

<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Page Title-->
        <div id="page-title">
            <!--<h1 class="page-header text-overflow">Designation List</h1>//-->
        </div>
        <!--End page title-->
        <!--Breadcrumb-->
        <ol class="breadcrumb">
        <li><a href="<?php echo base_url('MiniDashboard/index/');?>"><i class="demo-pli-home"></i></a></li>
        <li><a href="#">Ward wise saf</a></li>
        <li class="active">Search Saf Application</li>
        </ol>
        <!--End breadcrumb-->
    </div>
    
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
				<div class="panel-control">                  
					<!--a href="<?php //echo base_url('levelwisependingform/exportlevelformdetail/'.$id);?>" class="btn btn-primary"><i class="fa fa-arrow-down" aria-hidden="true"></i> EXCEL EXPORT</a-->
				</div>
                <h5 class="panel-title">Search Result</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered text-sm" id="demo_dt_basic">
                                <thead>
                                    <tr>
                                        <th>Sl No.</th>
                                        <th>Ward No.</th>
                                        <th>SAF No.</th>
                                        <th>Owner Name</th>
                                        <th>Mobile No.</th>
                                        <th>Address</th>
                                        <th>Apply Date</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i=$offset??0;
                                if(isset($data))
                                    foreach($data as $list)
                                    {
                                        $i++;
                                        ?>
                                            <tr>
                                                <td><?=$i;?></td>
                                                <td><?=$list['ward_no']?></td>
                                                <td><?=$list['saf_no']?></td>
                                                <td><?=$list['owner_name']?></td>
                                                <td><?=$list['owner_mobile']?></td>
                                                <td><?=$list['prop_address']?></td>
                                                <td><?=date('d-m-Y', strtotime($list['apply_date']));?></td>
                                                <td><a href="<?=base_url();?>/safdtl/full/<?=md5($list['id']);?>" class="btn btn-primary"> View </a></td>
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
<?= $this->include('layout_vertical/footer'); ?>