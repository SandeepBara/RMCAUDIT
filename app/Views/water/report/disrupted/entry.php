<?php
@session_start();
if ($user_type == "") {
    echo $this->include('layout_home/header');

}
# 4	Team Leader	
# 5	Tax Collector
# 7	ULB Tax Collector
else if ($user_type == 5 || $user_type == 7) {
    echo $this->include('layout_mobi/header');
} else {
    echo $this->include('layout_vertical/header');
}


?>
<style type="text/css">
    .error {
        color: red;
    }
</style>
<!-- <script src="<?= base_url(); ?>/public/assets/otherJs/validation.js"></script>  -->

<?php $session = session(); ?>

<!--CONTENT CONTAINER-->
<div id="content-container">
    <?php
    if ($user_type != "" && $user_type != 5) {
        ?>
        <div id="page-head">
            <!--Breadcrumb-->
            <ol class="breadcrumb">
                <li><a href="#"><i class="demo-pli-home"></i></a></li>
                <li><a href="#">Water</a></li>
                <li class="active"><a href="#">Service Disrupted Area Issue</a></li>
            </ol>
            <!--End breadcrumb-->
        </div>
        <?php
    }
    ?>
    <!--Page content-->

    <div id="page-content">
        <?php
        // Error messages
        if (isset($_SESSION['msg'])) {
            ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php
                $message = $_SESSION['msg'];
                unset($_SESSION['msg']);

                // Check if it's a bulk import error message with multiple errors
                if (strpos($message, 'Import failed with errors:') !== false) {
                    // Extract the error part
                    $errorPart = str_replace('Import failed with errors: ', '', $message);

                    // Split errors by semicolon and display as list
                    $errors = explode('; ', $errorPart);

                    echo '<strong>Import failed with the following errors:</strong>';
                    echo '<ul class="mb-0 mt-2">';
                    foreach ($errors as $error) {
                        if (trim($error)) {
                            echo '<li>' . htmlspecialchars(trim($error)) . '</li>';
                        }
                    }
                    echo '</ul>';
                } else {
                    // Regular single error message
                    echo htmlspecialchars($message);
                }
                ?>
            </div>
            <?php
        } elseif (cHasCookie('msg')) {
            ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php
                $message = cGetCookie('msg');
                cDeleteCookie('msg');

                // Same logic for cookie messages
                if (strpos($message, 'Import failed with errors:') !== false) {
                    $errorPart = str_replace('Import failed with errors: ', '', $message);

                    $errors = explode('; ', $errorPart);

                    echo '<strong>Import failed with the following errors:</strong>';
                    echo '<ul class="mb-0 mt-2">';
                    foreach ($errors as $error) {
                        if (trim($error)) {
                            echo '<li>' . htmlspecialchars(trim($error)) . '</li>';
                        }
                    }
                    echo '</ul>';
                } else {
                    echo htmlspecialchars($message);
                }
                ?>
            </div>
            <?php
        }

        // Success messages
        if (isset($_SESSION['success_msg'])) {
            ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo htmlspecialchars($_SESSION['success_msg']);
                unset($_SESSION['success_msg']); ?>
            </div>
            <?php
        }
        ?>
        <form id="form" name="form" method="post">
            <?php if (isset($validation)) { ?>
                <?= $validation->listErrors(); ?>
            <?php } ?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Service Disrupted Entry
                        <?php
                        if (in_array($user_type, [1, 2, 11])) {
                            ?>
                            <a href="<?= base_url('public/sample/service_distrupted_bulk_entry_Sample.xlsx') ?>" type="download" class="btn btn-info text-white pull-right">Sample File Download</a>
                            <button type="submit" class="pull-right btn btn-mint text-primary"
                                data-target="#bulk_uploads_details-lg-modal" data-toggle="modal">
                                Bulk Upload
                            </button>
                            <?php
                        } elseif ($user_type == 5) {
                            ?>
                            <a class="pull-right btn btn-info" href="<?= base_url() ?>/WaterMobileIndex/index">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>Back
                            </a>
                            <?php
                        }
                        ?>
                    </h3>

                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="consumer_no">Consumer No <span class="text-danger">*</span></label>
                            <input type="text" name="consumer_no" id="consumer_no" class="form-control"
                                value="<?= isset($consumer_no) ? $consumer_no : '' ?>"
                                placeholder="Enter Consumer Number">
                        </div>
                        <div class="col-md-6 pad-btm">
                            <label for="created_by">Tax Collecter<span class="text-danger">*</span></label>
                            <?php
                            $selected_creator = set_value('created_by') ?: (isset($emp_details_id) ? $emp_details_id : '');
                            ?>
                            <select name="created_by" id="created_by" class="form-control" required <?= $user_type == 5 ? 'readonly disabled' : '' ?>>
                                <option value="">Select Tax Collector</option>
                                <?php foreach ($tc as $value):
                                    if ($value['lock_status'] == 1)
                                        continue;
                                    ?>
                                    <option value="<?= $value['id'] ?>" <?= $selected_creator == $value['id'] ? 'selected' : '' ?>>
                                      (<?=$value['id'] ?>)  <?= $value['full_emp_name'] ?> (<?= $value['employee_code'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($user_type == 5): ?>
                                <input type="hidden" name="created_by" value="<?= $emp_details_id ?>">
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="remarks">Remarks <span class="text-danger">*</span></label>
                            <textarea name="remarks" id="remarks" class="form-control" rows="4"
                                placeholder="Enter your remarks here..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-body text-center">
                        <button type="SUBMIT" id="btn_review" name="btn_review" class="btn btn-primary">SUBMIT</button>
                    </div>
                </div>

        </form>

    </div><!--End page content-->

    <!-- BULK UPLOAD MODAL START HERE -->
    <div id="bulk_uploads_details-lg-modal" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
                    <h4 class="modal-title" id="myLargeModalLabel">Bulk Upload</h4>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data"
                        action="<?= base_url('water_report/serviceDistrupedBulkEntry') ?>">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="bulk_file">Upload File <span class="text-danger">*</span></label>
                                <input type="file" name="bulk_file" id="bulk_file" class="form-control" required>
                                <small class="text-muted">Only CSV or Excel files are allowed.</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Upload</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
    <!-- BULK UPLOAD MODAL END HERE -->
</div><!--END CONTENT CONTAINER-->

<?php
if ($user_type == '') {
    echo $this->include('layout_home/footer');
} elseif ($user_type == 5 || $user_type == 7) {

    echo $this->include('layout_mobi/footer');
} else {
    echo $this->include('layout_vertical/footer');

}

?>

<script src="<?= base_url(); ?>/public/assets/js/jquery.validate.js"></script>

<script type="text/javascript">

    document.getElementById('bulk_file').addEventListener('change', function () {
        const allowed = ['csv', 'xls', 'xlsx'];
        const file = this.files[0];
        if (file) {
            const ext = file.name.split('.').pop().toLowerCase();
            if (!allowed.includes(ext)) {
                alert('Only CSV or Excel files are allowed!');
                this.value = '';
            }
        }
    });

</script>