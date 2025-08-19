<?= $this->include('layout_vertical/header'); ?>
<script src="<?= base_url(); ?>/public/assets/otherJs/validation.js"></script>
<style>
    .error {
        color: red;
    }
</style>
<!--CONTENT CONTAINER-->
<!--===================================================-->
<div id="content-container">
    <div id="page-head">

        <!--Page Title-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <div id="page-title">
            <!--<h1 class="page-header text-overflow">Add/Update Designation</h1>//-->
        </div>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End page title-->


        <!--Breadcrumb-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Masters</a></li>
            <li class="active">Apartment List</li>
        </ol>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End breadcrumb-->

    </div>


    <!--Page content-->
    <!--===================================================-->
    <div id="page-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo $title; ?> Apartment</h3>

                    </div>

                    <!--Horizontal Form-->
                    <!--===================================================-->
                    <div class="panel-body">
                        <div class="pad-btm">
                            <a href="<?php echo base_url('Apartment/index') ?>"><button id="demo-foo-collapse" class="btn btn-purple"><i class="fa fa-arrow-left"></i> Back </button></a>
                        </div>
                        <form id="search_form" class="form-horizontal" method="post" action="<?php echo base_url('Apartment/create') ?><?= isset($apartment_data['id']) ? '/' . $apartment_data['id'] : '' ?>" enctype="multipart/form-data">
                            <input type="hidden" name="id" id="id" value="<?= (isset($apartment_data['id'])) ? $apartment_data['id'] : ""; ?>">

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="apt_name">Apartment Name<span style="color:red">*</span></label>
                                <div class="col-sm-4">
                                    <input maxlength="150" type="text" id="apt_name" name="apt_name" class="form-control" placeholder="Enter Apartment Name" value="<?= (isset($apartment_data['apartment_name'])) ? $apartment_data['apartment_name'] : ""; ?>" onkeypress="return isNumDot(event);" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="apt_code">Apartment Code<span style="color:red">*</span></label>
                                <div class="col-sm-4">
                                    <input maxlength="30" type="text" id="apt_code" name="apt_code" class="form-control" placeholder="Enter Apartment Code" value="<?= (isset($apartment_data['apt_code'])) ? $apartment_data['apt_code'] : ""; ?>" onkeypress="return isNumDot(event);" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="ward_mstr_id">Ward No.<span style="color:red">*</span></label>
                                <div class="col-sm-4">
                                    <select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
                                        <option value="">--select--</option>
                                        <?php if (isset($wardList)) {
                                            foreach ($wardList as $ward) {  ?>
                                                <option value="<?= $ward['id'] ?>" <?php if (isset($apartment_data['ward_mstr_id'])) {
                                                                                        if ($apartment_data['ward_mstr_id'] == $ward['id']) {
                                                                                            echo "selected";
                                                                                        }
                                                                                    }
                                                                                    ?>><?= $ward['ward_no']  ?></option>

                                        <?php }
                                        }  ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="road_type_mstr_id">Road Type<span style="color:red">*</span></label>
                                <div class="col-sm-4">
                                    <select id="road_type_mstr_id" name="road_type_mstr_id" class="form-control">
                                        <option value="">--select--</option>
                                        <?php if (isset($road_type)) {
                                            foreach ($road_type as $type) {  ?>
                                                <option <?php if (isset($apartment_data['road_type'])) {
                                                            if ($apartment_data['road_id'] == $type['id']) {
                                                                echo "selected";
                                                            }
                                                        }
                                                        ?> value="<?= $type['id'] ?>"><?= $type['road_type']  ?></option>

                                        <?php }
                                        }  ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="apt_address">Apartment Address<span style="color:red">*</span></label>
                                <div class="col-sm-4">
                                    <input maxlength="200" type="text" id="apt_address" name="apt_address" class="form-control" placeholder="Enter Address" value="<?= (isset($apartment_data['apartment_address'])) ? $apartment_data['apartment_address'] : ""; ?>" onkeypress="return isNumDot(event);" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="water_hrvesting">Water Harvesting<span style="color:red">*</span></label>
                                <div class="form-check d-inline">
                                    <input class="form-check-input" type="radio" name="water_hrvesting" value="yes" <?php if (isset($apartment_data['water_harvesting_status'])) {
                                                                                                                        if ($apartment_data['water_harvesting_status'] == 1) {
                                                                                                                            echo "checked";
                                                                                                                        }
                                                                                                                    }
                                                                                                                    ?>>
                                    <label class="form-check-label" for="flexRadioDefault1" style="margin-right: 30px;">
                                        Yes
                                    </label>
                                    <!-- </div> -->
                                    <!-- <div class="form-check d-inline"> -->
                                    <input class="form-check-input" type="radio" name="water_hrvesting" value="no" <?php if (isset($apartment_data['water_harvesting_status'])) {
                                                                                                                        if ($apartment_data['water_harvesting_status'] == 0) {
                                                                                                                            echo "checked";
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo "checked";
                                                                                                                    }
                                                                                                                    ?>>
                                    <label class="form-check-label" for="flexRadioDefault2">
                                        No
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="no_of_blocks">NO of Blocks<span style="color:red">*</span></label>
                                <div class="col-sm-4">
                                    <input maxlength="4" type="text" id="no_of_blocks" name="no_of_blocks" class="form-control" placeholder="Enter Apartment Code" value="<?= (isset($apartment_data['no_of_block'])) ? $apartment_data['no_of_block'] : "0"; ?>" onkeypress="return isNum(event)" />
                                </div>
                            </div>

                            <div class="form-group">
                                <?php if (isset($apartment_data['apt_image_file_name'])) {
                                ?>
                                    <a href="<?= base_url(); ?>/getImageLink.php?path=RANCHI/apartment/<?= $apartment_data['apt_image_file_name'] ?>"><img style="border:1px solid black" width="100" height="100" src="<?= base_url(); ?>/getImageLink.php?path=RANCHI/apartment/<?= $apartment_data['apt_image_file_name'] ?>"></a>
                                <?php } ?>
                                <input hidden name="apt_img_prev_path" type="text" value="<?= isset($apartment_data['apt_image_file_name']) ? $apartment_data['apt_image_file_name'] : ''; ?>">
                                <label class="col-sm-2 control-label" for="apt_img_file_path">Apartment Image
                                    <!-- <span style="color:red">*</span> -->
                                </label>
                                <div class="col-sm-4">
                                    <input accept="image/*" type="file" id="apt_img_file_path" name="apt_img_file_path" class="form-control" placeholder="Area" />
                                </div>
                            </div>
                            <div class="form-group" id="wtr_img_upload_hide_show">
                                <?php if (isset($apartment_data['wtr_hrvs_image_file_name'])) { ?>
                                    <a href="<?= base_url(); ?>/getImageLink.php?path=RANCHI/apartment/<?= $apartment_data['wtr_hrvs_image_file_name'] ?>"> <img style="border:1px solid black" width="100" height="100" src="<?= base_url(); ?>/getImageLink.php?path=RANCHI/apartment/<?= $apartment_data['wtr_hrvs_image_file_name'] ?>"></a>
                                <?php } ?>
                                <input hidden type="text" name="wtr_img_prev_path" value="<?= isset($apartment_data['wtr_hrvs_image_file_name']) ? $apartment_data['wtr_hrvs_image_file_name'] : ''; ?>">
                                <label class="col-sm-2 control-label" for="wtr_img_file_path">Water Harvesting Image
                                    <!-- <span style="color:red">*</span> -->
                                </label>
                                <div class="col-sm-4">
                                    <input accept="image/*" type="file" id="wtr_img_file_path" name="wtr_img_file_path" class="form-control" placeholder="Area" />
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="Document">&nbsp;</label>
                                <div class="col-sm-4">
                                    <button class="btn btn-success" id="btn_save" name="btn_save" type="submit"><?= (isset($apartment_data['id'])) ? "Edit Apartment" : "Add Apartment"; ?></button>
                                </div>
                            </div>

                        </form>
                    </div>

                    <!--===================================================-->
                    <!--End Horizontal Form-->

                </div>
            </div>
        </div>
    </div>
    <!--===================================================-->
    <!--End page content-->

</div>
<!--===================================================-->
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer'); ?>
<script src="<?= base_url(); ?>/public/assets/js/jquery.validate.min.js"></script>

<script type="text/javascript">
    $('document').ready(function() {

        if ($('input[name="water_hrvesting"]:checked').val() == 'yes') {
            $("#wtr_img_upload_hide_show").show();
            <?php if (isset($apartment_data['wtr_hrvs_image_file_name'])) {  ?>
                $("#wtr_img_file_path").prop('required', false);
            <?php  }else { ?>
                $("#wtr_img_file_path").prop('required', true);
                <?php } ?>
        } else if ($('input[name="water_hrvesting"]:checked').val() == 'no') {
            $("#wtr_img_upload_hide_show").hide();
        }

        $('input[type=radio][name=water_hrvesting]').trigger("change");

        $('input[type=radio][name=water_hrvesting]').change(function() {
            // console.log('working ', $this.value)
            // return;
            if (this.value == 'yes') {
                $("#wtr_img_upload_hide_show").show();
                <?php if (isset($apartment_data['wtr_hrvs_image_file_name'])) {  ?>
                    $("#wtr_img_file_path").prop('required', false);
                <?php  }else{ ?>
                    $("#wtr_img_file_path").prop('required', true);
                <?php } ?>
            } else if (this.value == 'no') {
                $("#wtr_img_upload_hide_show").hide();
            }
        });

        var validator = $("#search_form").validate({
            rules: {
                apt_name: {
                    required: true,
                    maxlength: 150
                },
                apt_code: {
                    required: true,
                    maxlength: 30
                },
                ward_mstr_id: {
                    required: true
                },
                road_type_mstr_id: {
                    required: true
                },
                apt_address: {
                    required: true,
                    maxlength: 200
                },
                no_of_blocks: {
                    required: true,
                    maxlength: 4
                }

            }

        });

        function isNum(e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        }
    })
</script>>