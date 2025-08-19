<?php 
    if(isset($user_type_id) && !in_array($user_type_id,[5,7]))
    {
        echo $this->include("layout_vertical/header");
    }
    else
    {
        echo $this->include("layout_mobi/header");
    }
?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <?php
                    if(isset($user_type_id) && in_array($user_type_id,[5,7]))
                    {
                        ?>
                        <div class="panel-control">
                            <a href="<?php echo base_url('visiting_dtl/visit_details/');?>" type="button" id="addvisiting" class="btn btn-mint" style="color:white;">Add Visiting Reports</a>
                        </div>
                        <?php
                    }
                    ?>
                <h3 class="panel-title"><b>Search visiting reports</b></h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form method="post" action="<?php echo base_url('visiting_dtl/getvisitinglist') ?>">
							<div class="col-sm-12">
								<label class="col-sm-2" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
								<div class="col-sm-2 pad-btm">
									<input type="date" id="from_date" name="from_date" class="form-control"  value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>">
								</div>
							
								<label class="col-sm-2" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
								<div class="col-sm-2 pad-btm">
									<input type="date" id="to_date" name="to_date" class="form-control" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>">
								</div>
                                <?php
                                    if(isset($user_type_id) && !in_array($user_type_id,[5,7]) && isset($tcList))
                                    {
                                        ?>
                                            <label class="col-sm-2" for="emp_id"><b>Tc List</b><span class="text-danger">*</span> </label>
                                            <div class="col-sm-2 pad-btm">
                                                <select  id="emp_id" name="emp_id" class="form-control">
                                                    <option value="">Select Tc</option>
                                                    <?php
                                                        foreach($tcList as $val)
                                                        {
                                                            ?>
                                                                <option value="<?=$val["id"];?>" <?=isset($emp_id) && $emp_id==$val["id"] ? "selected" : "" ;?>><?=$val["name"];?></option>
                                                            <?php
                                                        }
                                                    ?>
                                                </select>
                                            </div>

                                            <label class="col-sm-2" for="moduleId"><b>Modul List</b><span class="text-danger">*</span> </label>
                                            <div class="col-sm-2 pad-btm">
                                            <select name="moduleId" id="moduleId" class="form-control" onchange="getRemarks()" >
                                                <option value="">Select Module</option>
                                                <option value="1" <?=isset($moduleId) && $moduleId =="1"?"selected":""?>>SAF</option>
                                                <option value="2" <?=isset($moduleId) && $moduleId =="2"?"selected":""?>>PROPERTY</option>
                                                <option value="3" <?=isset($moduleId) && $moduleId =="3"?"selected":""?>>WATER CONSUMER</option>
                                                <option value="4" <?=isset($moduleId) && $moduleId =="5"?"selected":""?>>TRADE LICENSE</option>
                                            </select>
                                            </div>

                                            <label class="col-sm-2 control-label" for="remarks_id">Remarks<span class="text-danger">*</span></label>
                                            <div class="col-sm-4">
                                                <select class="form-control" name="remarks_id" id="remarks_id" >
                                                    <option value="">Please Select</option>
                                                    <?php
                                                        if(isset($remarks) && $remarks)
                                                        {                                                    
                                                            foreach($remarks as $val)
                                                            {                                                          
                                                                ?>
                                                                <option value="<?=$val['id'];?>" <?= ((isset($remarks_id) || isset($_POST["remarks_id"])) && (($remarks_id??$_POST["remarks_id"])==$val["id"]) )? "selected":"";?> > <?=$val["remarks"];?> </option>
                                                                <?php
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        <?php
                                    }
                                ?>
								<div class="col-sm-4 pad-btm">
                                    <label class="col-sm-2 control-label" > </label>
									<input class="col-sm-4 btn btn-primary form-control" id="search" name="search" type="submit" value="Search">
								</div>
							</div>
                            
						</form>
                    </div>
                </div>
            </div>
        </div>
		
		<div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title"><b>Visiting reports list</b></h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
						<div class="table-responsive">
							<table id="demo_dt_basic" class="table table-striped table-hover table-bordered" cellspacing="0" width="100%">
								<thead style="background-color: #fab810;">
									<tr>
										<th>#</th>
                                        <?=isset($user_type_id) && !in_array($user_type_id,[5,7]) ? "<th>Tc Name</th>":"";?>
										<th>Reference Number</th>
										<th>Responce</th>
										<th>Address</th>
										<th>Latitude</th>
										<th>Longitude</th>
										<th>IP Address</th>
										<th>Date Time</th>
                                        <th>View on google map</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if(!isset($visiting_list)):
									?>
										<tr>
											<td colspan="8" style="text-align: center;">Data Not Available!!</td>
										</tr>
									<?php else:
										$i=0;
										foreach ($visiting_list as $value):
									?>
										<tr>
											<td><?=++$i;?></td>
                                            <?=isset($user_type_id) && !in_array($user_type_id,[5,7]) ? ("<td>".($value['full_name']!=""?$value['full_name']:"N/A")."</td>"):"";?>
											<td><?=$value['ref_no']!=""?$value['ref_no']:"N/A";?></td>
											<td><?=$value['remarks']!=""?$value['remarks']:"N/A";?></td>
											<td><?=$value['address']!=""?$value['address']:"N/A";?></td>
											<td><?=$value['latitude']!=""?$value['latitude']:"N/A";?></td>
											<td><?=$value['longitude']!=""?$value['longitude']:"N/A";?></td>
											<td><?=$value['ip_address']!=""?$value['ip_address']:"N/A";?></td>
											<td><?=$value['created_on']!=""?$value['created_on']:"N/A";?></td>
                                            <td><button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-sm btn-danger" onclick="PopupMap('<?=$value['latitude'];?>', '<?=$value['longitude'];?>');"> View on google map </button></td>
										</tr>
										<?php endforeach;?>
									<?php endif;  ?>
								</tbody>
							</table>
						</div>
                    </div>
                </div>
            </div>
        </div>
		
    </div>
    <!--End page content-->


    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Geo tagged image on map</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <div id="map" style="background: pink; height: 400px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
        </div>
</div>


<!--END CONTENT CONTAINER-->
<?php 

    if(isset($user_type_id) && !in_array($user_type_id,[5,7]))
    {
        echo $this->include("layout_vertical/footer");
    }
    else
    {
        echo $this->include("layout_mobi/footer");
    }
?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDJ0oDYz1cVvaM-fcAmYVsz9uVwnDNwD5g&callback=initMap" async defer></script>
<script type="text/javascript">
    function PopupMap(latitude, longitude)
    {
        console.log(latitude);
        console.log(longitude);
        initialize(latitude, longitude);
    }

    var map;
    var geocoder;
    var centerChangedLast;
    var reverseGeocodedLast;
    var currentReverseGeocodeResponse;
    function initialize(latitude, longitude) {
        //alert(latitude);		
        var latlng = new google.maps.LatLng(latitude,longitude);
        
        var myOptions = {
            zoom: 15,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById("map"), myOptions);
        geocoder = new google.maps.Geocoder();

        var marker = new google.maps.Marker({
            position: latlng,
            map: map,
            title: "Aadrika Enterprises"
        });
        console.log(marker);

    }
    $(document).ready( function () {
        $("#search").click(function() {
            var process = true;
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            if (from_date > to_date) {
                $("#from_date").css({"border-color":"red"});
                $("#from_date").focus();
                $("#to_date").css({"border-color":"red"});
                $("#to_date").focus();
                alert('"To date" must be greater than "From date"');
                process = false;
            }
            
            return process;
        });
        $("#from_date").change(function(){$(this).css('border-color','');});
        $("#to_date").keyup(function(){$(this).css('border-color','');}); 

        getRemarks();              
        
    });

    function getRemarks()
    {
        var moduleId = $("#moduleId").val();
        if(moduleId)
        {
            $.ajax({
                "type": "POST",
                "url": "<?=base_url()."/visiting_dtl/getRemarks"?>",                       
                "dataType": "json" ,
                "data": {
                        moduleId : $('#moduleId').val()
                    }, 
                beforeSend: function() {
                        $("#btndesign").html("LOADING ...");
                        $("#btndesign").attr("type","button");
                        $("#loadingDiv").show();
                    },            
                complete: function(){
                    $("#loadingDiv").hide();   
                    $("#btndesign").html("Submit"); 
                    $("#btndesign").attr("type","submit");            
                },
                'success': function(response) {
                    // Here the response   
                    if(response.status)
                    {
                        $("#remarks_id").html(response.remarks);
                        $("#loadingDiv").hide();
                        <?php
                            if(isset($remarks_id))
                            {
                                ?>
                                    var x = document.getElementById('remarks_id');
                                    for (var i = 0; i < x.length; i++) {
                                        if(x[i].value=="<?=$remarks_id?>")
                                        {
                                            document.getElementById("remarks_id").selectedIndex = i;
                                        }
                                    }
                                <?php
                            }
                        ?>  
                    }
                },
                error: function(xhr, status, error) {
                        // Handle the error
                        console.error(error);
                        $("#loadingDiv").hide();
                    }
            });
        }
    }
</script>