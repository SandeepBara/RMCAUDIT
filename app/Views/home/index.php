<?=$this->include('layout_home/header');?>

<style>
	.hide_trial_version_label {
		/* background: white;
		width: 63px;
		height: 13px;
		position: absolute;
		bottom: 1px;
		z-index: 2; */
	}

	.card{
		border-bottom: 6px solid #7a7ee7;
		border-right: 6px solid #7a7ee7;
		border-radius: 35px;
		border-bottom-right-radius: 3px;
		/* padding: 30px; */
		/* padding-left: 5px !important; */
	} 

	.card-btn{
		background: #0f1151;
		border: 0;
		color: #ffffff;
		width: 90%;
		font-weight: bold;
		border-radius: 20px;
		height: 24px;
		transition: all 0.2s ease;
		text-align: center;
		padding: 2px 12px;
	}

	.card-btn:hover {
    	background: #ffffff;
		color:#0f1151;
	}

	.card-btn:focus {
        background: #0f1151;
        outline: 0;  
	}
	
	.card-img:hover > img {
        transform: scale(1.2);
	}

	.card-img img {
		padding: 10px;
		margin-top: 15px;
		margin-bottom: 10px;
		transition: 0.4s ease;
		cursor: pointer;
		/* border: 2px solid red;
    	border-radius: 135px; */
	}
 
</style>

<style>
	#container_carousel {
		background: white;
		height: 400px;
		padding-bottom: 0px;
	}

	#myCarousel img {
		height: 400px;
	}

	@media(max-width: 858px) {
		#container_carousel {
			height: 200px;
		}

		#myCarousel img {
			height: 200px;
		}

		#left_image {
			display: none;
		}

		#right_image {
			display: none;
		}
	}

	@media(max-width: 770px) {
		#aatambhart {
			display: none;
		}

		#honerperson {
			display: none;
		}
	}

	.new-border{
		border-bottom: 6px solid #7a7ee7 !important;
    	border-right: 6px solid #7a7ee7 !important;
    	border-radius: 15px !important;
	}
</style>

<style>
	@media only screen and (max-width: 600px) {
        .panel{
            padding:0px;
        }
        .cardnew{
            margin-right:0px;
            margin-top:20px;
        }
    }
    .cardnew {
        /* background: #fff; */
        border-radius: 4px;
        box-shadow: 10px 10px 0px rgba(34, 35, 58, 0.5);
        max-width: 400px;
        display: flex;
        flex-direction: row;
        border-radius: 25px;
        position: relative;
        margin-right: 15px;
    }                                   
    .cardnew h2 {
        margin: 0;
        padding: 0 1rem;
    }
    .cardnew .title {
        /* padding: 0.5rem; */
        text-align: center;
        color: #0f1151;
        font-weight: bolder;
        font-size: 17px;
        font-family: verdana;
    }
    .cardnew .desc {
        padding: 1rem 1rem;
        font-size: 12px;
        font-family:verdana;
        text-align: -webkit-match-parent;
    }

    .cardnew .desc a{
        color: #000000;
        font-size: 12px;
        font-family: cursive;
        font-weight: 900;
        &:hover{
            color: #d90000;
        }
    }
    .cardnew .actions {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        align-items: center;
        padding: 0.5rem 1rem;
    }
    .cardnew svg {
        width: 85px;
        height: 85px;
        margin: 0 auto;
    }

    .img-avatar {
        width: 80px;
        height: 80px;
        position: absolute;
        border-radius: 50%;
        /* border: 4px solid white; */
        /* background-image: linear-gradient(-60deg, #0f1151 0%, #ff0000 100%); */
        top: 30px;
        left: 15px;
        display:block;
    }

    .img-avatar img{
        vertical-align: middle;
        /* opacity: 100%; */
        width: 125px;
    }

    .cardnew-text {
        display: grid;
        grid-template-columns: 1fr 2fr;
    }

    .title-total {
        padding: 2em 1em 1em 1em;
    }

    path {
        fill: white;
    }

    .img-portada {
        width: 100%;
    }

    .portada {
        width: 120%;
        height: 100%;
        border-top-left-radius: 20px;
        border-bottom-left-radius: 20px;
        background-image: url("<?=base_url()?>/public/assets/img/icons/bg_victor.png");
        opacity: 25%;
        background-position: bottom center;
        background-size: cover;
    }

    button {
        border: none;
        background: none;
        font-size: 24px;
        color: #8bc34a;
        cursor: pointer;
        transition:.5s;
    }

	@media only screen and (max-width: 600px){
		.portada{
			width:85%;
		}
		.img-avatar img{
			width:85px;
		}
	}
</style>

<!-- tihs section is for slider -->
<div id="page-content" style="padding: 0px !important;">
	<div class="panel">
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
					<div id="myCarousel" class="carousel slide" data-ride="carousel">
						<ol class="carousel-indicators">
							<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
							<li data-target="#myCarousel" data-slide-to="1"></li>
							<li data-target="#myCarousel" data-slide-to="2"></li>
							<li data-target="#myCarousel" data-slide-to="3"></li>
							<li data-target="#myCarousel" data-slide-to="4"></li>
							<li data-target="#myCarousel" data-slide-to="5"></li>
						</ol>
						<div class="carousel-inner">
							<div class="item active" style="padding-top: 0px;">
								<img src="<?= base_url(); ?>/public/assets/img/sliders/n2.jpg" alt="Los Angeles" style="width:100%;">
							</div>
							<div class="item" style="padding-top: 0px;">
								<img src="<?= base_url(); ?>/public/assets/img/sliders/n4.jpg" alt="New york" style="width:100%;">
							</div>
							<div class="item" style="padding-top: 0px;">
								<img src="<?= base_url(); ?>/public/assets/img/sliders/n1.jpg" alt="Los Angeles" style="width:100%;">
							</div>
							<div class="item " style="padding-top: 0px;">
								<img src="<?= base_url(); ?>/public/assets/img/sliders/n3.jpg" alt="Los Angeles" style="width:100%;">
							</div>
							<div class="item" style="padding-top: 0px;">
								<img src="<?= base_url(); ?>/public/assets/img/IndependenceDay11.jpg" alt="Los Angeles" style="width:100%;">
							</div>
							<div class="item" style="padding-top: 0px;">
								<img src="<?= base_url(); ?>/public/assets/img/amrut.jpg" alt="Chicago" style="width:100%;">
							</div>
						</div>

						<a class="left carousel-control" href="#myCarousel" data-slide="prev" style="padding-left: 20px;">
							<span class="glyphicon glyphicon-chevron-left"></span>
							<span class="sr-only">Previous</span>
						</a>
						<a class="right carousel-control" href="#myCarousel" data-slide="next" style="padding-right: 20px;">
							<span class="glyphicon glyphicon-chevron-right"></span>
							<span class="sr-only">Next</span>
						</a>
					</div>		
				</div>
			</div>
		</div>
	</div>
</div>
<!-- slider ends here -->


<!-- middile part starts here -->
<div id="page-contento" style="padding: 0px 0px 30px 0px !important;background-color: #0f1151;">
    <div class="text-light">
        <div class="col-sm-12">
            <marquee width="100%" behavior="slide" style="padding: 6px">
                All Citizen are requested to pay tax on time to avoid penalty. |
                For any queries and grievance please Visit here -> <a href="https://pgms.dmajharkhand.in/index.aspx">PGMS Portal</a>

            </marquee>
        </div>
    </div>
</div>
<br>

<div id="page-content" style="padding: 0px !important;">
	<div class="panel">
		<div class="panel-body" style="padding:20px 40px 40px 40px;">
			<div class="row new-border">

				<div class="col-sm-2" id="honerperson">
					<div id="myCarousel" class="carousel slide" data-ride="carousel">
						<div class="carousel-inner">
							<div class="item active" style="padding-top: 0px;">
								<img src="<?= base_url(); ?>/public/assets/img/hemat_soren.jpg" alt="CM" style="width:100%;height:165px;">
								<div class="carousel-caption" style="right:0px; left:0px; padding-bottom:0px; bottom: 0px !important;">
									<p style="margin:0px !important;">Minister-in-Charge</p>
									<b style="margin:0px !important;">Shri Hemant Soren</b>
								</div>
							</div>
							<div class="item" style="padding-top: 0px; ">
								<img src="<?= base_url(); ?>/public/assets/img/Secretary_Vinay_kr.jpg" alt="Secretary" style="width:100%;height:165px;">
								<div class="carousel-caption" style="right:0px; left:0px; padding-bottom:0px;bottom: 0px !important;">
									<p style="margin:0px !important;">Secretary</p>
									<b style="margin:0px !important;">Shri Vinay Kumar Choubey</b>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-8">
					<div class="panel">
						<div class="panel-heading">
							<h5 class="panel-title" style="text-align: center;font-family: revert;"> 
								<b><u>Welcome to Urban Development & Housing Department , Jharkhand </u></b>
							</h5>
						</div>
						<div class="panel-body" style="opacity: 0.8;text-align: justify;">
							Urban Development & Housing Department , Jharkhand is always devoted for your service. This is your own city. To keep city clean, green & liveable please follow the rules & regulations of Urban Development & Housing Department , Jharkhand. Pay your tax/usercharges on time, registered the incident of birth and death in your family within the prescribed time period. Your comments and suggestions are always welcome for the growth and development of this corporation. Please come forward, take the responsibilities of your duties to be a good citizen and make Jharkhand city, an ideal city.
						</div>
					</div>
				</div>

				<div class="col-md-2" style="height:165px;" id="aatambhart">
					<img src="<?= base_url(); ?>/public/assets/img/aatmnirbhar.jpg" style="width:100%;height:100%;" class="w3-round" alt="lgimg">
				</div>
			</div>
		</div>
	</div>
</div>
<!-- middile part ends here -->
<input type="hidden" name="fy_mstr_id" id="fy_mstr_id" value="2021-2022">



<div id="page-content" style="padding: 0px !important;">
    <div class="panel" style="padding: 25px 10px 15px 5px; background-color:white;">
        <div class="row">
            <div class="col-md-4 cardnew ">
                <div class="img-avatar">
                    <img src="<?=base_url()?>/public/assets/img/icons/rcm-1.png" alt="Municipal Corporations" srcset="" >
                </div>
                <div class="cardnew-text">
                    <div class="portada"></div>
                    <div class="title-total">
                        <div class="title">PROPERTY TAX</div>
                        <!-- <h2>PROPERTY TAX</h2> -->
                        <div class="desc">
                            Enroll your property or properties and quickly check the outstanding amount and pay it with ease online.
                        </div>
                        <div class="actions">
                            <!-- <button> -->
                            <a class="btn btn-primary btn-block btn-rounded mar-top" href="<?=base_url();?>/CitizenProperty/home">
                                <i class="fa fa-hand-o-right"></i> PAY PROPERTY TAX
                            </a>
                            <!-- </button> -->
                        </div>
                        <div class="actions">
                            <button>
                                <a class="btn btn-primary btn-block btn-rounded mar-top" href="<?=base_url();?>/CitizenSaf2/manuall">
                                    <i class="fa fa-hand-o-right"></i> ASSESSMENT
                                </a>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 cardnew ">
                <div class="img-avatar">
                    <img src="<?=base_url()?>/public/assets/img/icons/rcm-2.png" alt="Municipal Corporations" srcset="" >
                </div>
                <div class="cardnew-text">
                    <div class="portada"></div>
                    <div class="title-total">
                        <div class="title">WATER USER CHARGES</div>
                        <!-- <h2>WATER USER CHARGES</h2> -->
                        <div class="desc">
                            Enroll your Water Meter connection and quickly check the outstanding amount and pay it with ease online.
                        </div>

                        <div class="actions">
                            <button>
                                <a class="btn btn-primary btn-block btn-rounded mar-top" href="<?=base_url();?>/WaterApplyNewConnectionCitizen/searchList/<?=md5(2)?>">
                                    <i class="fa fa-hand-o-right"></i> PAY WATER CHARGE
                                </a>
                            </button>
                        </div>
                        <div class="actions">
                            <button>
                                <a class="btn btn-primary btn-block btn-rounded mar-top" href="<?=base_url();?>/WaterApplyNewConnectionCitizen/index">
                                    <i class="fa fa-hand-o-right"></i> APPLY CONNECTION
                                </a>
                            </button>
                        </div>
                    </div>
                </div>
            </div>



            <div class="col-md-4 cardnew h-auto">
                <div class="img-avatar">
                    <!--                    <img src="--><?php //=base_url()?><!--/public/assets/img/icons/rcm-2.png" alt="Municipal Corporations" srcset="">-->
                </div>
                <div class="cardnew-text">
                    <div class="portada"></div>
                    <div class="title-total">
                        <div class="title">PUBLIC GRIEVANCE</div>
                        <div class="desc">
                            For any queries and grievance please Visit.
                            <br /><br /><br /><br /><br/><br/>
                        </div>
                        <div class="actions">
                            <button>
                                <a class="btn btn-primary btn-block btn-rounded mar-top" href="https://pgms.dmajharkhand.in/index.aspx" target="_blank">
                                    <i class="fa fa-hand-o-right"></i> VISIT WEBSITE
                                </a>
                            </button>
                        </div>
                    </div>
                </div>
            </div>




        </div>

        <div class="row" style="margin-top:30px;">
            <div class="col-md-4 cardnew ">
                <div class="img-avatar">

                    <img src="<?=base_url()?>/public/assets/img/icons/rcm-5.png" alt="Municipal Corporations" srcset="" >
                </div>
                <div class="cardnew-text">
                    <div class="portada"></div>
                    <div class="title-total">
                        <div class="title">PROPERTY DOCUMENT</div>
                        <!-- <h2>PROPERTY TAX</h2> -->
                        <div class="desc">
                            <a href="<?=base_url();?>/public/download_doc/property/rain_water_harvesting_structure.pdf" target="_blank" >
                                <i class="fa fa-hand-o-right text-danger" aria-hidden="true"></i>&nbsp;Rain Water Harvesting Structure Information Booklet
                            </a><br />
                            <a href="<?=base_url();?>/public/download_doc/property/assessment_form.pdf" target="_blank" >
                                <i class="fa fa-hand-o-right text-danger" aria-hidden="true"></i>&nbsp;Download-Self Assessment Form
                            </a><br />
                            <a href="<?=base_url();?>/public/download_doc/property/booklet.pdf" target="_blank" >
                                <i class="fa fa-hand-o-right text-danger" aria-hidden="true"></i>&nbsp;Download-Information Booklet
                            </a><br />
                            <a href="<?=base_url();?>/public/download_doc/property/supplementary_booklet.pdf" target="_blank" >
                                <i class="fa fa-hand-o-right text-danger" aria-hidden="true"></i>&nbsp;Download Supplementary Information Booklet
                            </a><br />
                            <a href="<?=base_url();?>/public/download_doc/property/owner_annexure.pdf" target="_blank" >
                                <i class="fa fa-hand-o-right text-danger" aria-hidden="true"></i>&nbsp;Download From For-More than one owner
                            </a><br />
                            <a href="<?=base_url();?>/public/download_doc/property/unassessed_properties.pdf" target="_blank" >
                                <i class="fa fa-hand-o-right text-danger" aria-hidden="true"></i>&nbsp;List of Unassessed Properties
                            </a>
                        </div>


                    </div>
                </div>
            </div>

            <div class="col-md-4 cardnew ">
                <div class="img-avatar">
                    <img src="<?=base_url()?>/public/assets/img/icons/rcm-6.png" alt="Municipal Corporations" srcset="" >

                </div>
                <div class="cardnew-text">
                    <div class="portada"></div>
                    <div class="title-total">
                        <div class="title">WATER DOCUMENT</div>
                        <!-- <h2>WATER USER CHARGES</h2> -->
                        <div class="desc">
                            <a href="<?=base_url();?>/public/download_doc/water/New Water Connection Form.pdf" target="_blank" >
                                <i class="fa fa-hand-o-right text-danger" aria-hidden="true"></i>&nbsp;WCF -1 (For New Connection)
                            </a><br />
                            <a href="<?=base_url();?>/public/download_doc/water/water meter installation confirmation form.pdf" target="_blank" >
                                <i class="fa fa-hand-o-right text-danger" aria-hidden="true"></i>&nbsp;Meter Installation Form
                            </a>
                            <br />
                            <br /><br /><br /><br /><br /><br /><br /><br />


                        </div>


                    </div>
                </div>
            </div>

            <div class="col-md-4 cardnew ">
                <div class="img-avatar">
                    <img src="<?=base_url()?>/public/assets/img/icons/rcm-4.png" alt="Municipal Corporations" srcset="" >
                </div>
                <div class="cardnew-text">
                    <div class="portada"></div>
                    <div class="title-total">
                        <div class="title">
                            TAX COLLECTOR
                        </div>
                        <!-- <h2>WATER USER CHARGES</h2> -->
                        <div class="desc">
                            Know your tax collector.
                            <br /><br /><br /><br /><br /><br />
                        </div>

                        <div class="actions">
                            <button>
                                <a class="btn btn-primary btn-block btn-rounded mar-top" href="<?=base_url();?>/CitizenProperty/list_tax_collector">
                                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>&nbsp;TAX COLLECTOR LIST
                                </a>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 30px;">
            <div class="col-md-4 cardnew ">
                <div class="img-avatar">
                    <img src="<?=base_url()?>/public/assets/img/icons/rcm-3.png" alt="Municipal Corporations" srcset="" >

                </div>
                <div class="cardnew-text">
                    <div class="portada"></div>
                    <div class="title-total">
                        <div class="title">MUNCIPAL LICENCE</div>
                        <!-- <h2>WATER USER CHARGES</h2> -->
                        <div class="desc">
                            Enroll your municipal license and quickly check the outstanding amount and pay it with ease online.
                        </div>
                        <br /><br /><br />
                        <div class="actions">
                            <button>
                                <a class="btn btn-primary btn-block btn-rounded mar-top" href="<?=base_url();?>/Home/tradeList">
                                    <i class="fa fa-hand-o-right"></i> APPLY NOW
                                </a>
                            </button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

</div>
<section class="hidden-treasure carousel-wrapper aos-init aos-animate" style="padding-bottom: 125px;">
    <div class="container">
        <h2 class="text-center redfo">
            Jewels of Jharkhand
        </h2>
        <div id="flexCarousel4" class="flexslider teasure-gallery">
            <div class="flex-viewport" style="overflow: hidden; position: relative;"><ul class="slides" style="width: 600%; transition-duration: 0s; transform: translate3d(0px, 0px, 0px);">
                    <li class="treasure-gallery-card after-shape darkMode" style="width: 363.333px; margin-right: 25px; display: block;">
                        <img src="<?=base_url();?>/public/assets/img/tribal.jpg" width="363" height="200" alt="Tribal Culture" typeof="Image" draggable="false">
                        <div class="trasure-gallery-content">
                            <h4>Tribal Culture</h4>
                            <p class="card-text">The tribes of Jharkhand consist of 32 tribes inhabiting the Jharkhand state in India.The tribes in Jharkhand were originally classified on the basis of their cultural types... </p>
                        </div>
                    </li>
                    <li class="treasure-gallery-card after-shape darkMode" style="width: 363.333px; margin-right: 25px;  display: block;">
                        <img src="<?=base_url();?>/public/assets/img/mineral.png" width="363" height="200" alt="Mines and Minerals" typeof="Image" draggable="false">

                        <div class="trasure-gallery-content">
                            <h4>Mines and Minerals</h4>
                            <p>
                                Jharkhand is endowed with vast resources of a variety of minerals and occupies a prominent place in the countrys as a mineral rich State...
                            </p>
                        </div>
                    </li>
                    <li class="treasure-gallery-card after-shape darkMode" style="width: 363.333px; margin-right: 25px;  padding-bottom:12px;display: block;">
                        <img src="<?=base_url();?>/public/assets/img/treasure3_0.jpg" width="363" height="200" alt="Tourism" typeof="Image" draggable="false">

                        <div class="trasure-gallery-content">
                            <h4>Tourism</h4>
                            <p>The lush green forests, rivers and waterfalls of this primeval land are home to many kinds of spectacular flora and fauna. Age-old tribes are the main inhabit of this wonderful …</p>
                            <a href="https://tourism.jharkhand.gov.in/" title="Know More" target="_blank" style="color:blue"> Know more...</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<div class="modal fade in" id="myModalMunc">
	<div class="modal-dialog" role="document" style="border: 5px solid #ff0000; border-radius: 3px;">
		<div class="modal-content">
			<div class="modal-header" style="background-color: #ff0000; color: white; font-weight: bold;">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color: white;">×</span></button>
				<h4 class="modal-title" id="myModalLabel"><b style="color: white;">Municipal Corporations</b></h4>
			</div>
			<div class="modal-body">
				<table class="table table-hover table-bordered">
					<tbody>
						<tr>
							<td>1</td>
							<td>
								<a id="Munc" href="<?= base_url("Citizen/index/"); ?>/<?= hashEncrypt(md5(1)); ?>">
									Ranchi Municipal Corporation
								</a>
							</td>
						</tr>
						<tr>
							<td>2</td>
							<td><!-- <a href="<?= base_url("Citizen/index/"); ?>/<?= hashEncrypt(md5(2)); ?>"> -->
									Dhanbad Municipal Corporation
								<!-- </a> -->
							</td>
						</tr>
						<tr>
							<td>3</td>
							<td>
								Hazaribag Nagar Nigam
							</td>
						</tr>

						<tr>
							<td>4</td>
							<td>
								Deoghar Nagar Nigam
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade in" id="myModalNagarPar">
	<div class="modal-dialog" role="document" style="border: 5px solid #ff0000; border-radius: 3px;">
		<div class="modal-content">
			<div class="modal-header" style="background-color: #ff0000; color: white; font-weight: bold;">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color: white;">×</span></button>
				<h4 class="modal-title" id="myModalLabel"><b style="color: white;">Nagar Parishads</b></h4>
			</div>
			<div class="modal-body">
				<table class="table table-hover table-bordered">
					<tbody>
						<tr>
							<td>1</td>
							<td>Dumka Nagar Parishad</td>
						</tr>
						<tr>
							<td>2</td>
							<td>Pakur Nagar Parishad</td>
						</tr>
						<tr>
							<td>3</td>
							<td>Chaibasa Nagar Parishad</td>
						</tr>
						<tr>
							<td>4</td>
							<td>Chatra Nagar Parishad</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade in" id="myModalNagarPanc">
	<div class="modal-dialog" role="document" style="border: 5px solid #ff0000; border-radius: 3px;">
		<div class="modal-content">
			<div class="modal-header" style="background-color: #ff0000; color: white; font-weight: bold;">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="color: white;">×</span></button>
				<h4 class="modal-title" id="myModalLabel"><b style="color: white;">Nagar Panchayat</b></h4>
			</div>
			<div class="modal-body">
				<table class="table table-hover table-bordered">
					<tbody>
						<tr>
							<td>1</td>
							<td>Latehar Nagar Panchayat</td>
						</tr>
						<tr>
							<td>2</td>
							<td>Bundu Nagar Panchayat</td>
						</tr>
						<tr>
							<td>3</td>
							<td>Koderma Nagar Panchayat</td>
						</tr>
						<tr>
							<td>4</td>
							<td>Basukinath Nagar Panchayat</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<?= $this->include('layout_home/footer'); ?>
<script type="text/javascript">
	window.onload = function() {
		$("#muncipal_corporation").val("");
	}
</script>