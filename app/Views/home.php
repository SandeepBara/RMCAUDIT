
<?= $this->include('layout_home/header');?>

<style>
    #container_carousel {
        background: white;
        height:200px;
        padding-bottom: 0px;
    }
    #myCarousel img{
        height: 200px;
    }
    @media(max-width: 858px){
        #container_carousel {
            height:150px;
        }
        #myCarousel img{
            height: 150px;
        }
        #left_image {
            display: none;
        }
        #right_image {
            display: none;
        }
    }

</style>
<div id="page-content">
    <div class="panel">
        <div class="panel-body">
			<div class="row">
				<div class="col-md-2" style="height:185px;">
					<img src="<?=base_url();?>/public/assets/img/aatmnirbhar.jpg" style="width:100%;height:100%;" class="w3-round" alt="lgimg">
				</div>
				<div class="col-md-8">
					<div id="myCarousel" class="carousel slide" data-ride="carousel">
							<ol class="carousel-indicators">
								<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
								<li data-target="#myCarousel" data-slide-to="1"></li>
								<li data-target="#myCarousel" data-slide-to="2"></li>
							</ol>
							<div class="carousel-inner">
								<div class="item active" style="padding-top: 0px;">
									<img src="<?=base_url();?>/public/assets/img/IndependenceDay11.jpg" alt="Los Angeles" style="width:100%;">
									
								</div>
								
								<div class="item" style="padding-top: 0px;">
									<img src="<?=base_url();?>/public/assets/img/amrut.jpg" alt="Chicago" style="width:100%;">
									
								</div>
								
								<div class="item" style="padding-top: 0px;">
									<img src="<?=base_url();?>/public/assets/img/property_tax.jpg" alt="New york" style="width:100%;">
									
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
				<div class="col-md-2">
					<div id="myCarousel" class="carousel slide" data-ride="carousel">
						<div class="carousel-inner">
							<div class="item active" style="padding-top: 0px;">
								<img src="<?=base_url();?>/public/assets/img/hemat_soren.jpg" alt="CM" style="width:100%;">
								<div class="carousel-caption" style="right:0px; left:0px; padding-bottom:0px;">
									<p>Minister-in-Charge</p>
									<b>Shri Hemant Soren</b>
								</div>  
							</div>
							<div class="item" style="padding-top: 0px;">
								<img src="<?=base_url();?>/public/assets/img/secretary_arava_rajkamal.jpeg" alt="Secretary" style="width:100%;">
								<div class="carousel-caption" style="right:0px; left:0px; padding-bottom:0px;">
									<p>Secretary</p>
									<b>Arava Rajkamal</b>
								</div> 
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="page-content">
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12">
					<div class="panel">
						<div class="panel-heading">
							<h5 class="panel-title"> <b><u>Welcome to Urban Development & Housing Department , Jharkhand </u></b></h5>
						</div>
						<div class="panel-body">
							Urban Development & Housing Department , Jharkhand is always devoted for your service. This is your own city. To keep city clean, green & liveable please follow the rules & regulations of Urban Development & Housing Department , Jharkhand. Pay your tax/usercharges on time, registered the incident of birth and death in your family within the prescribed time period. Your comments and suggestions are always welcome for the growth and development of this corporation. Please come forward, take the responsibilities of your duties to be a good citizen and make Jharkhand city, an ideal city.
						</div>
					</div>
                </div>
            </div> 
			<div class="row">
                <div class="col-sm-4">
					<div class="panel-bordered panel-dark" id="property" style="height: 200px; width: 100%;"></div>
					<div class="hide_trial_version_label">Hello</div>
				</div>
				<div class="col-sm-4">
					<div class="panel-bordered panel-dark" id="trade" style="height: 200px; width: 100%;"></div>
                </div>
				<div class="col-sm-4">
					<div class="panel-bordered panel-dark" id="water" style="height: 200px; width: 100%;"></div>
                </div>
				
            </div> 
        </div>
    </div>
</div>


<br>
<?= $this->include('layout_home/footer');?>
<script src="https://cdn.anychart.com/releases/8.10.0/js/anychart-pie.min.js"></script>
<script src="https://cdn.anychart.com/releases/8.10.0/js/anychart-base.min.js"></script>
<script>
anychart.onDocumentReady(function () {

    // create data
    var data = [
      {x: "A", value: 637166},
      {x: "B", value: 721630},
      {x: "C", value: 148662},
      {x: "D", value: 78662},
      {x: "E", value: 90000}
    ];

    // create a 3d pie chart and set the data
    var chart = anychart.pie3d(data);

    /* set the inner radius
    / (to turn the pie chart into a doughnut chart)*/
    chart.innerRadius("30%");

    // set the chart title
	  chart.title("3D Doughnut Chart");

    // set the container id
    chart.container("property");

    // initiate drawing the chart
    chart.draw();
});
</script>