<?= $this->include('layout_home/header'); ?>

<!-- Include Validation Script -->
<script src="<?= base_url(); ?>/public/assets/otherJs/validation.js"></script>

<!-- Custom Styles -->
<style>
    .container_wrapper {
        display: flex;
        justify-content: space-between;
    }

    .content_container {
        padding: 30px;
        width: 100%;
        padding: 10px;
        background-color: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        margin: 20px;
    }


    .form-group {
        margin-bottom: 20px;
    }

    .form-control {
        height: 45px;
        border-radius: 5px;
        border: 1px solid #ddd;
        padding: 10px;
        font-size: 16px;
        color: #333;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
    }

    .form-group label {
        font-weight: bold;
        color: #555;
    }

    .text-danger {
        color: #dc3545;
    }

    .btn-secondary {
        background-color: #007bff;
        border-color: #007bff;
        color: #fff;
        height: 45px;
        border-radius: 5px;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    .btn-secondary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    .application_heading_container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .application_heading_container h5 {
        font-size: 20px;
        color: #666;
        font-weight: bold;
        margin-bottom: 30px;
    }

    .application_heading_container .apply-date {
        font-size: 14px;
        color: #666;
    }


    .application_heading_container span {
        font-size: 16px;
        color: #666;
        font-weight: normal;
    }

    .application_heading_container .application_no{
        color: #007bff;
    }

    .application_status_container {
        display: flex;
        align-items: center;
        margin-top: 20px;
        margin-bottom: 30px;
    }

    .application_status_container h6 {
        font-size: 16px;
        color: #333;
        margin-bottom: 10px;
    }

    .application_status_container .status {
        font-size: 14px;
        color: #007bff;
    }

    .attachment-container {
        padding-top: 20px;
    }

    .attachment-container a {
        color: #007bff;
        text-decoration: none;
        font-size: 16px;
    }

    .attachment-container a:hover {
        text-decoration: underline;
    }

    .btn {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border-radius: 5px;
        text-align: center;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        border: none;
        text-decoration: none;
    }

    .btn:hover{
        background-color: #0056b3;
    }
</style>

<div class="main_div_container">
    <div class="container_wrapper">
        <!-- CITIZEN GRIEVANCE SIDEBAR START HERE -->
        <div class="left_sidebar_container">
            <?php
            $citizen = true;
            if ($citizen) {
                echo $this->include('grievance/layout/sidebar');
            } else {
                echo '<h1>Main User Sidebar</h1>';
            }
            ?>
        </div>
        <!-- CITIZEN GRIEVANCE SIDEBAR END HERE -->

        <!-- CONTENT CONTAINER START HERE -->
        <div class="content_container">
            <!-- APPLICATION STATUS VIEW START HERE -->
            <div class="application_heading_container">
                <h5>Your Ticket No is<span class="application_no"> <?=$app["token_no"]?>, </span>  <span>You can use this for future reference</span></h5>
                <span class="apply-date">Apply Date: <?=date("d-m-Y",strtotime($app["created_on"]))?></span>
            </div>

            <div class="application_status_container">
                <h6>Application Status: <span class="status"><?=$app["app_status"]?></span></h6>

            </div>


            <div class="panel-body">
                
                <div class="row">
                    <label class="col-md-1" for="name">Citizen Name <span class="text-danger">*</span></label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= $app["name"]; ?>
                    </div>

                    <label class="col-md-1" for="mobile">Mobile No <span class="text-danger">*</span></label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= $app["mobile_no"]; ?>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-1" for="grievance_for">Grievance For <span class="text-danger">*</span></label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= $app["grievance_for"]; ?>
                    </div>

                    <label class="col-md-1" for="holding_no"><?=$app["app_type"];?> No <span class="text-danger">*</span></label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= $app["app_no"]; ?>
                    </div>

                    <label class="col-md-1" for="guardian_name">Ward No <span class="text-danger">*</span></label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= $app["ward_no"]; ?>
                    </div>
                </div>
                
                <div class="row">
                    <label class="col-md-1" for="guardian_name">Owner Name <span class="text-danger">*</span></label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= $app["owner_name"]; ?>
                    </div>  
                </div>
                <div class="row">
                    <label class="col-md-1" for="guardian_name">Guardian Name <span class="text-danger">*</span></label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= $app["guardian_name"]; ?>
                    </div> 

                    <label class="col-md-1">Address <span class="text-danger">*</span></label>
                    <div class="col-md-3 text-bold pad-btm">
                        <?= $app["address"]; ?>
                    </div>
                </div>

                <!-- Queries Input -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for="queries">Queries <span class="text-danger">*</span></label>
                        <textarea id="queries" name="queries" class="form-control" placeholder="Enter your queries here..." rows="4" readonly><?= ($app["queries"] ?? ""); ?></textarea>
                    </div>
                </div>

                <!-- Attachment View -->
                <div class="col-md-3 attachment-container">
                    <label class="control-label" for="attachment">Attachment:</label>
                    <?php
                        $path = $app['doc_path'];
                        $extention = strtolower(explode('.', $path)[1]);
                        if ($extention == "pdf") {
                            ?>
                                    <a href="<?= base_url(); ?>/getImageLink.php?path=<?= $path; ?>" target="_blank">
                                        <img id="imageresource" src="<?= base_url(); ?>/public/assets/img/pdf_logo.png" class='img-lg' />
                                    </a>
                                <?php
                        } else {
                        ?>
                            <a target="_blank" href="<?= base_url(); ?>/getImageLink.php?path=<?= $path; ?>">
                                <img src='<?= base_url(); ?>/getImageLink.php?path=<?= $path; ?>' class='img-lg' />
                            </a>
                        <?php
                        }
                    ?>
                </div>
            </div>

            <!-- BUUTON ROW -->
            <div class="text-center">
                <button class="w-full btn">PRINT</button>
            </div>
            <!-- APPLICATION STATUS VIEW END HERE -->
        </div>
        <!-- CONTENT CONTAINER END HERE -->
    </div>
</div>

<?= $this->include('layout_home/footer'); ?>
<!-- Include jQuery Validation Script -->
<script src="<?= base_url(); ?>/public/assets/js/jquery.validate.js"></script>