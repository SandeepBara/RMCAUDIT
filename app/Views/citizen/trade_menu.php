<?= $this->include('layout_home/header');?>

<style type="text/css">
    .menu_panel_hover:hover {
        background-color: #e0e5ea;
        cursor: pointer;
    }
    #content-container{
        font-family:verdana;
    }
    #page-content{
        padding: 0px 40px 40px 40px;
    }

    .my-border{
		background-color: white;
    	padding: 20px 10px 20px 10px;
    	border-bottom: 6px solid #7a7ee7;
    	border-right: 6px solid #7a7ee7;
    	border-radius: 15px;
	}

    .card{
        border:2px solid black;
    }

    h3{
        color:#202020;
    }
    .card__container {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        justify-content: center;
        width: 100%;
        max-width: 90%;
        margin: 25px auto;
    }
    .card__bx {
        --bg-clr: #ffffff;
        --txt-clr: #ffffff;
        --btn-txt-clr: #333333;
        --transition: all 0.5s;
        font-family:verdana;
        height: 400px;
        width: 300px;
        overflow: hidden;
        border-radius: 20px;
        border-top-left-radius: 70px;
        position: relative;
        overflow: hidden;
        background: var(--clr);
        transition: var(--transition);
    }
    .card__1 {
        --clr: #7a7ee7;
    }
    .card__2 {
        --clr: #5c789f;
    }
    .card__3 {
        --clr: #e53030;
    }
    .card__bx .card__data {
        position: absolute;
        inset: 15px;
        border-radius: 10px;
        background: var(--bg-clr);
    }
    .card__bx .card__data .card__icon {
        position: absolute;
        top: 0;
        left: 0;
        border-bottom-right-radius: 50%;
        height: 140px;
        width: 140px;
        background: var(--clr);
    }
    .card__bx .card__data .card__icon::before {
        content: '';
        position: absolute;
        bottom: -30px;
        left: 0;
        height: 30px;
        width: 30px;
        border-top-left-radius: 30px;
        background: transparent;
        box-shadow: -5px -5px 0 5px var(--clr);
    }
    .card__bx .card__data .card__icon::after {
        content: '';
        position: absolute;
        right: -30px;
        top: 0;
        height: 30px;
        width: 30px;
        border-top-left-radius: 30px;
        background: transparent;
        box-shadow: -5px -5px 0 5px var(--clr);
    }
    .card__bx .card__data .card__icon .card__icon-bx {
        position: absolute;
        inset: 10px;
        border-radius: 10px;
        border-top-left-radius: 50%;
        border-bottom-right-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 60px;
        background: var(--bg-clr);
        color: var(--clr);
    }
    .card__bx:hover .card__data .card__icon .card__icon-bx {
        background: var(--clr);
        color: var(--txt-clr);
        transition: var(--transition);
    }
    .card__bx .card__data .card__content {
        position: absolute;
        top: 115px;
        padding: 10px;
        text-align: center;
        display: flex;
        justify-content: center;
        flex-direction: column;
        gap: 2px;
        color: var(--txt-clr);
    }
    .card__bx .card__data .card__content h3 {
        font-size: 20px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
    }
    .card__bx .card__data .card__content p {
        font-size: 14px;
        opacity: 0.75;
        color:black;
    }
    .card__bx .card__data .card__content a {
        display: inline-flex;
        align-self: center;
        padding: 10px 25px;
        text-decoration: none;
        text-transform: uppercase;
        font-size: 16px;
        font-weight: 600;
        border-radius: 30px;
        border: 2px solid var(--clr);
        color: var(--btn-txt-clr);
        background: var(--clr);
        transition: var(--transition);
    }
    .card__bx .card__data .card__content a:hover {
        background: transparent;
        color: var(--clr);
    }
   
</style>
<!--CONTENT CONTAINER-->
<div id="content-container" style="padding-top: 10px;">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">

            <section class="card__container">
                <!--==================== SERVICE CARD ====================-->
                <div class="card__bx card__1">
                    <div class="card__data">
                        <div class="card__icon">
                            <div class="card__icon-bx">
                                <img src="<?=base_url()?>/public/assets/img/icons/new_licence.png" alt="" srcset="" style="width: 80px;">
                            </div>
                        </div>
                        <div class="card__content">
                            <h3>Apply For New Licence</h3>
                            <p>Ready to level up? Apply for your new license today!</p>
                            <?php $aplyNew = md5(1); ?>
                            <!-- code by xxx -->
                            <!-- applynewlicence to applyLicenceNew -->
                            <a href="<?=base_url();?>/Citizen/SelectMunicipal/<?=hashEncrypt('TradeCitizen/applynewlicence/'.$aplyNew);?>">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>&nbsp;Click Here
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card__bx card__2">
                    <div class="card__data">
                        <div class="card__icon">
                            <div class="card__icon-bx">
                                <img src="<?=base_url()?>/public/assets/img/icons/renewlicence-01.png" alt="" srcset="" style="width: 80px;">
                            </div>
                        </div>
                        <div class="card__content">
                            <h3>Renew Licence</h3>
                            <p>License about to expire? Renew and inspire confidence.</p>
                            <a href="<?=base_url();?>/Citizen/SelectMunicipal/<?=hashEncrypt('TradeCitizen/searchLicense/'.md5(2));?>">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>&nbsp;Click Here
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card__bx card__3">
                    <div class="card__data">
                        <div class="card__icon">
                            <div class="card__icon-bx">
                                <img src="<?=base_url()?>/public/assets/img/icons/surrender-01.png" alt="" srcset="" style="width: 80px;">
                            </div>
                        </div>
                        <div class="card__content">
                            <h3>Surrender Licence</h3>
                            <p>Hand over the reins, surrender your license.</p>
                            <a href="<?=base_url();?>/Citizen/SelectMunicipal/<?=hashEncrypt('TradeCitizen/searchLicense/'.md5(4));?>">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>&nbsp;Click Here
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="card__container">
                <!--==================== SERVICE CARD ====================-->
                <div class="card__bx card__3">
                    <div class="card__data">
                        <div class="card__icon">
                            <div class="card__icon-bx">
                                <img src="<?=base_url()?>/public/assets/img/icons/ammendment-01.png" alt="" srcset="" style="width: 80px;">
                            </div>
                        </div>
                        <div class="card__content">
                            <h3>Amendment Licence</h3>
                            <p>Empower your rights with the Amendment Licence.</p>
                            <a href="<?=base_url();?>/Citizen/SelectMunicipal/<?=hashEncrypt('TradeCitizen/searchLicense/'.md5(3));?>">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>&nbsp;Click Here
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card__bx card__1">
                    <div class="card__data">
                        <div class="card__icon">
                            <div class="card__icon-bx">
                                <img src="<?=base_url()?>/public/assets/img/icons/tabacoLicence.png" alt="" srcset="" style="width: 80px;">
                            </div>
                        </div>
                        <div class="card__content">
                            <h3>Apply For New Tobacco Licence</h3>
                            <p>Ignite new opportunities with a fresh tobacco license application.</p>
                            <a href="<?=base_url();?>/Citizen/SelectMunicipal/<?=hashEncrypt('TradeCitizen/tobaccoapplynewlicence/'.$aplyNew);?>">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>&nbsp;Click Here
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card__bx card__2">
                    <div class="card__data">
                        <div class="card__icon">
                            <div class="card__icon-bx">
                                <img src="<?=base_url()?>/public/assets/img/icons/searchlicence-01.png" alt="" srcset="" style="width: 80px;">
                            </div>
                        </div>
                        <div class="card__content">
                            <h3>Search Licence</h3>
                            <p>Your Guide to a Hunting License Application.</p>
                            <a href="<?=base_url();?>/Citizen/SelectMunicipal/<?=hashEncrypt('TradeCitizen/index');?>">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>&nbsp;Click Here
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <?php /*
            
            <div class="panel-heading">
                <h3 class="panel-title">Trade Menu List</h3>
            </div>
            <div class="panel-body bg-gray-light pad-ver">
                <div class="row">
                    <div class="col-md-3">
					<?php $aplyNew = md5(1); ?>
                        <a href="<?=base_url();?>/Citizen/SelectMunicipal/<?=hashEncrypt('TradeCitizen/applynewlicence/'.$aplyNew);?>">
                            <div class="panel media middle panel-bordered-purple menu_panel_hover">
                                <div class="media-left pad-all">
                                    <img src="<?=base_url();?>/public/assets/img/list/prop_assessment.png" width="48" height="48" />
                                </div>
                                <div class="media-body pad-btm">
                                    <h4>Apply For New Licence</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?=base_url();?>/Citizen/SelectMunicipal/<?=hashEncrypt('TradeCitizen/searchLicense/'.md5(2));?>">
                            <div class="panel media middle panel-bordered-purple menu_panel_hover">
                                <div class="media-left pad-all">
                                    <img src="<?=base_url();?>/public/assets/img/list/renew_licence.png" width="48" height="48" />
                                </div>
                                <div class="media-body pad-btm">
                                    <h4>Renew Licence</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?=base_url();?>/Citizen/SelectMunicipal/<?=hashEncrypt('TradeCitizen/searchLicense/'.md5(4));?>">
                            <div class="panel media middle panel-bordered-purple menu_panel_hover">
                                <div class="media-left pad-all">
                                    <img src="<?=base_url();?>/public/assets/img/list/surrender.png" width="48" height="48" />
                                </div>
                                <div class="media-body pad-btm">
                                    <h4>Surrender Licence</h4>
                                </div>
                            </div>
                        </a>
                    </div>
					<div class="col-md-3">
                        <a href="<?=base_url();?>/Citizen/SelectMunicipal/<?=hashEncrypt('TradeCitizen/searchLicense/'.md5(3));?>">
                            <div class="panel media middle panel-bordered-purple menu_panel_hover">
                                <div class="media-left pad-all">
                                    <img src="<?=base_url();?>/public/assets/img/list/prop_assessment.png" width="48" height="48" />
                                </div>
                                <div class="media-body pad-btm">
                                    <h4>Amendment Licence</h4>
                                </div>
                            </div>
                        </a>
                    </div>
					<div class="col-md-3">
                        <a href="<?=base_url();?>/Citizen/SelectMunicipal/<?=hashEncrypt('TradeCitizen/tobaccoapplynewlicence/'.$aplyNew);?>">
                            <div class="panel media middle panel-bordered-purple menu_panel_hover">
                                <div class="media-left pad-all">
                                    <img src="<?=base_url();?>/public/assets/img/list/search_licence.png" width="48" height="48" />
                                </div>
                                <div class="media-body pad-btm">
                                    <h4>Apply For New Tobacco Licence</h4>
                                </div>
                            </div>
                        </a>
                    </div>
					<div class="col-md-3">
                        <a href="<?=base_url();?>/Citizen/SelectMunicipal/<?=hashEncrypt('TradeCitizen/index');?>">
                            <div class="panel media middle panel-bordered-purple menu_panel_hover">
                                <div class="media-left pad-all">
                                    <img src="<?=base_url();?>/public/assets/img/list/search_licence.png" width="48" height="48" />
                                </div>
                                <div class="media-body pad-btm">
                                    <h4>Search Licence</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            */ ?>

        </div>                
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_home/footer');?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>
<script>
    $(document).ready(function(){
        $("#formValidate").validate({
            rules:{
                ulb_mstr_id:{
                    required:true
                }
            },
            messages:{
                ulb_mstr_id:{
                    required:"Please select ULB."
                }
            }
        });
    });
</script>