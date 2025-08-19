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
            <li><a href="#">Water Holding Mapping</a></li>
        </ol>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <!--===================================================-->
    <div id="page-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-dark">
                    <div class="panel-heading">
                        <h5 class="panel-title">Map Water Consumer With Holding</h5>
                    </div>
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-horizontal" method="post" action="">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label class="control-label" for="from_date"><b>Holding No.</b> <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   id="holding_no"
                                                   name="holding_no"
                                                   value="<?= $result[0]['new_holding_no']??"";?>"
                                                   class="form-control">
                                        </div>
                                        <?php if(isset($result) && $result[0]['new_holding_no']){ ?>
                                            <div class="col-md-3">
                                                <label class="control-label" for="from_date"><b>Consumer No.</b> <span class="text-danger">*</span></label>
                                                <input type="text"
                                                       id="consumer_no"
                                                       name="consumer_no"
                                                       value="<?= $consumer[0]['consumer_no']??"";?>"
                                                       class="form-control">
                                            </div>
                                       <?php    }
                                        ?>
                                        <div class="col-md-3">
                                            <label class="control-label" for="">&nbsp;</label>
                                            <button class="btn btn-primary btn-block" id="btn_search_<?php echo isset($result) ? 'consumer' : 'holding'; ?>" name="btn_search_<?php echo isset($result) ? 'consumer' : 'holding'; ?>" type="submit">
                                                <?php echo isset($result) ? 'Search Consumer' : 'Search Holding'; ?>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>


                        <?php if(isset($result)){?>
                            <div class="row">
                                <div class="text-center text-danger text-bold">Holding No: <?= $result[0]['new_holding_no'] ?? ""; ?>
                                </div>
                                <div class="panel panel-dark panel-bordered">
                                    <div class="panel-heading">
                                        <h5 class="panel-title">Details Of Holding</h5>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Owner Name</th>
                                                    <th>Mapping Status</th>
                                                    <th>Address</th>
                                                </tr>
                                                </thead>
                                                <form class="form-horizontal" method="post" action="">
                                                    <tbody>
                                                    <?php foreach ($result as $item): ?>
                                                        <tr>
                                                            <td><?= $item['id'] ?></td>
                                                            <td><?= $item['owner_name'] ?></td>
                                                            <td><?= $item['water_conn_no'] ? 'Yes' : 'No' ?></td>
                                                            <td><?= $item['prop_address'] ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    </tbody>

                                                </form>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <!--Consumer Data-->

                        <?php if(isset($consumer)){?>
                            <div class="row">
                                <div class="text-center text-danger text-bold">Consumer No: <?= $consumer[0]['consumer_no'] ?? ""; ?>


                                </div>
                                <div class="panel panel-dark panel-bordered">
                                    <div class="panel-heading">
                                        <h5 class="panel-title">Details Of Water Consumer</h5>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Owner Name</th>
                                                    <th>Mapping Status</th>
                                                    <th>Address</th>
                                                </tr>
                                                </thead>
                                                <form class="form-horizontal" method="post" action="">
                                                    <tbody>
                                                    <?php foreach ($consumer as $item): ?>
                                                        <tr>
                                                            <td><?= $item['consumer_id'] ?></td>
                                                            <td><?= $item['applicant_name'] ?></td>
                                                            <td><?= $item['holding_no'] ? 'Yes' : 'No' ?></td>
                                                            <td><?= $item['address'] ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    </tbody>

                                                </form>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                    <!--FORM TO UPDATE-->

                        <?php if(isset($consumer)){?>
                            <form class="form-horizontal" method="post" action="">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <input type="hidden" id="prop_dtl_id" name="prop_dtl_id" value='<?=$result[0]['id'] ?? 'NA';?>' class="form-control" >
                                        <input type="hidden" id="holding_no" name="holding_no" value="<?=$result[0]['new_holding_no']?? 'NA'; ?> " class="form-control" >

                                        <input type="hidden" id="water_consumer_no" name="water_consumer_no" value='<?=$consumer[0]['consumer_no'];?>' class="form-control" >
                                        <input type="hidden" id="created_on" name="water_connection_data" value="<?=$consumer[0]['created_on']?? 'NA'; ?> " class="form-control" >

                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label" align="center" for="" style="color:#f00; font-weight:bold; text-align:center; font-size:20px;">* Please verify all the details before click on the button</label>
                                        <button class="btn btn-primary btn-block" id="btn_verify" name="btn_verify" type="submit" >Verify & Map</button>
                                    </div>
                                </div>
                            </form>
                        <?php } ?>
                        <!--FORM UPDATE END HERE-->
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
<script>
    function block_button(button)
    {
        button.disabled = true;
    }
</script>
<!--DataTables [ OPTIONAL ]-->
