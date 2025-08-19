<?= $this->include('layout_mobi/header');?>
<div id="content-container">
    <div id="page-head">
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li class="active">Water Harvesting Declaration Form</li>
        </ol>
    </div>
    <div id="page-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h5 class="panel-title">Water Harvesting Declaration Form</h5>
                    </div>
                    <div class="panel-body">
                        <div class ="row">
                            <div class="col-md-12">
                                <form id="form_id" method="get" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="from_date">From Date <span class="text-danger">*</span></label>
                                        <input type="date" id="from_date" name="from_date" class="form-control" value="<?=isset($from_date)?$from_date:""?>" />
                                    </div>
                                    <div class="form-group">
                                        <label for="upto_date">Upto Date <span class="text-danger">*</span></label>
                                        <input type="date" id="upto_date" name="upto_date" class="form-control" value="<?=isset($upto_date)?$upto_date:""?>" />
                                    </div>
                                    <div class="form-group">
                                        <label for="ward_mstr_id">Ward No</label>
                                        <select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
                                            <option value="">ALL</option>
                                            <?php foreach ($wardList as $value) : ?>
                                                <option value="<?= $value['ward_mstr_id'] ?>" <?= (isset($ward_mstr_id)) ? $ward_mstr_id == $value["ward_mstr_id"] ? "SELECTED" : "" : ""; ?>><?= $value['ward_no']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="upto_date">Search Keyword</label>
                                        <input type="text" id="search_param" name="search_param" class="form-control" value="<?=isset($search_param)?$search_param:""?>" />
                                    </div>
                                    <div class="form-group">
                                    <button type="submit" class="btn btn-success btn-block" id="btn_submit">SEARCH</button>
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
                        <h5 class="panel-title">List</h5>
                    </div>
                    <div class="panel-body">
                        <div class ="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered text-sm">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Ward No.</th>
                                                <th>Application No</th>
                                                <th>Owner Name</th>
                                                <th>Guardian name</th>
                                                <th>Mobile No.</th>
                                                <th>Address</th>
                                                <th>Receive Datetime</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if (isset($result)) {
                                            $count = isset($offset)?$offset:0;
                                            foreach ($result as $key => $value) {
                                        ?>
                                            <tr>
                                                <td><?=++$count;?></td>
                                                <td><?=$value["ward_no"];?></td>
                                                <td><?=$value["water_hrvting_application_no"];?></td>
                                                <td><?=$value["owner_name"];?></td>
                                                <td><?=$value["guardian_name"];?></td>
                                                <td><?=$value["mobile_no"];?></td>
                                                <td><?=$value["prop_address"];?></td>
                                                <td><?=$value["created_on"];?></td>
                                                <?php if ($value["receiver_user_type_mstr_id"]=="5") { ?>
                                                    <td><a href="<?=base_url();?>/WaterHarvestingTC/atc_fieldverify/<?=$value["id"];?>" class="btn btn-primary">VIEW</a></td>
                                                <?php } elseif ($value["receiver_user_type_mstr_id"]=="7") { ?>
                                                    <td><a href="<?=base_url();?>/WaterHarvestingTC/utc_fieldverify/<?=$value["id"];?>" class="btn btn-primary">VIEW</a></td>
                                                <?php } ?>
                                            </tr>
                                        <?php
                                            }
                                        } else {
                                        ?>
                                            <tr>
                                                <td colspan="8">Data Not Available!!</td>
                                            </tr>
                                        <?php
                                        }

                                        ?>
                                        </tbody>
                                    </table>
                                    <?= pagination(isset($pager)?$pager:0); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->include('layout_mobi/footer');?>