<?= $this->include('layout_home/header'); ?>
<!--CONTENT CONTAINER-->
<div id="content-container" style="padding-top: 10px;">
    <!--Page content-->

    <div id="page-content">
        <div class="panel panel-bordered ">
            <div class="panel-body">

                <div class="col-sm-10">
                    <div class="panel panel-bordered panel-dark">
                        <div class="panel-heading">
                            <h3 class="panel-title">Self Assessment Form
                        </div>
                        <div class="panel-body">

                            <div class="panel panel-bordered panel-dark" style="padding: 10px;">
                                <span class="text text-center" style="font-weight: bold; font-size: 23px; text-align: center; color: black; font-family: font-family Verdana, Arial, Helvetica, sans-serif Verdana, Arial, Helvetica, sans-serif;">
                                    Your applied application no. is
                                    <span style="color: #ff6a00"><?= $saf_no; ?></span>.
                                    You can use this application no. for future reference.
                                </span>
                                <br>
                                <br>
                                <div style="font-weight: bold; font-size: 20px; text-align:center; color:#0033CC">
                                    Current Status : <span style="color:#009900"> <?= $application_status; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-sm-2">
                    <?= $this->include('citizen/SAF/SafCommonPage/saf_left_side'); ?>
                </div>
            </div>
        </div>
    </div>




    <!--END CONTENT CONTAINER-->
    <?= $this->include('layout_home/footer'); ?>
    <script>
        $(document).ready(function() {
            $("#sidebarmenu a").each(function() {
                //console.log(decodeURIComponent($(this).attr("href")));
                if (decodeURIComponent($(this).attr("href")).replace(/\\/gi, "/") == decodeURIComponent(window.location.href)) {
                    $(this).addClass('active');
                }
            });
        });
    </script>