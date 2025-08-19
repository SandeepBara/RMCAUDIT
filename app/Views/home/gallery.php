<?= $this->include('layout_home/header');?>

    <style scoped>
        .gallery{
            padding-bottom: 80px;
        }
        .gallery img{
            padding: 10px;
            height: 320px;

        }

    </style>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-body">


                <h4 style="padding-left: 18px">Promotional Campaigns</h4>
                <div class="row gallery">
                    <div class="col-md-4 text-center">
                        <img class="img-responsive" src="<?=base_url();?>/public/assets/img/campaign1.jpeg" alt="">
                    </div>
                    <div class="col-md-4 text-center">
                        <img class="img-responsive" src="<?=base_url();?>/public/assets/img/campaign2.jpeg" alt="">
                    </div>
                    <div class="col-md-4 text-center">
                        <img class="img-responsive" src="<?=base_url();?>/public/assets/img/campaign3.jpeg" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_home/footer');?>