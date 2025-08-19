<?= $this->include('layout_home/header');?>
<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<style>
    @media screen and (min-width:990px)
    {
        #content-container{
            padding-bottom:520px;
        }
        #footer{
            position: absolute;
        }
    }
    </style>
<div id="content-container" style="padding-top: 10px;">
<!--Page content-->
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <div id="page-content">
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Search Application</h3>
                </div>
                <div class="panel-body" style="padding: 40px;">
                    <form method="POST" action="<?=base_url();?>/CitizenSaf/searchApplication">
                        <div class="row">
                            <label class="col-md-5 text-bold" style="font-size: 16px;">
                                Acknowledgement No. :
                            </label>
                            <div  class="col-md-7 text-bold has-warning">
                                <input onkeypress="remove_error()" type="text" id="saf_no" name="saf_no" class="form-control" value="<?=(isset($saf_no))?$saf_no:"";?>" />
                                <span id="error_msg" class="text-danger"><?=isset($saf_no_err)?$saf_no_err:"";?></span>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-5 text-bold mar-top">
                                Validation code
                            </label>
                            <div  class="col-md-5 text-bold mar-top has-warning">
                                <img src='<?=loginCaptchaCitizen();?>' />
                                <br />Enter the code above here :
                                <input type="text" id="captcha_code" name="captcha_code" class="form-control" maxlength="4" />
                                <span class="text-danger"><?=isset($captcha_err)?$captcha_err:"";?></span>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-8 text-bold mar-top">
                                <a href="<?=base_url();?>/CitizenSaf/safmanual" class="text-info">Haven't applied? Apply Now?</a>
                            </label>
                            <div class="col-md-4 mar-top text-right">
                                <button type="submit" class="btn btn-primary btn-block">Continue</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div><!--End page content-->
    </div>
    <div class="col-md-3"></div>
</div><!--END CONTENT CONTAINER-->
<?= $this->include('layout_home/footer');?>

<script>

    function remove_error(){
        $('#error_msg').hide();
    }

</script>
