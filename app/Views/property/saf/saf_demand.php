<?= $this->include('layout_vertical/header');?>
<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
  <div id="page-head">
      <!--Breadcrumb-->
      <ol class="breadcrumb">
          <li><a href="#"><i class="demo-pli-home"></i></a></li>
          <li><a href="#"> Self Assessment Form Demand</a></li>
      </ol><!--End breadcrumb-->
  </div>
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">Demand Details</h3>
			</div>
			<div class="panel-body">
				<form method="post" action="<?=base_url('safDemand/saf_demand_details');?>">
					<div class="row">
            <div class="table-responsive">
  						<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <?php
                if(isset($saf_demand)):
                foreach ($saf_demand as $value):
                ?>
              	<thead>
  								<tr>
                    <input type="hidden" id="id" name="id" value="<?=$value["id"];?>">
  									<th class="col-sm-6">Acknowledgement No. : <?=$value["application_no"];?><input type="hidden" id="ac" name="ac" value="<?=$value["application_no"];?>"></th>
  									<th class="col-sm-6">Application Type : <?=$value["application_type"];?><input type="hidden" name="application_type" value="<?=$value["application_type"];?>"></th>
  								</tr>
                  <tr>
                    <th class="col-sm-6">Ownership Type :     <?=($value["ownership_type"]!="")?$value["ownership_type"]:"N/A";?><input type="hidden" name="ownership_type_mstr_id" value="<?=$value["ownership_type"];?>"></th>
                    <th class="col-sm-6">Building Address : <?=$value["building_colony_address"];?><input type="hidden" name="building_colony_address" value="<?=$value["building_colony_address"];?>"></th>
                  </tr>
                  <input type="hidden" id="id" name="ward_no" value="<?=$value["ward_no"];?>">

                  <tr>
                    <th class="col-sm-6">Designation :
                       <?php if(isset($designation)):
                           foreach ($designation as $designations):?>
                         <?=($designations["designation"]!="")?$designations["designation"]:"N/A";?>
                          <?php endforeach; endif;  ?>
                   </th>

                    <th class="col-sm-6">Address : <?=($value["address"]!="")?$value["address"]:"N/A";?><input type="hidden" name="address" value="<?=$value["address"];?>"></th>
                  </tr>
                  <tr>
                    <th>Govt_Building Usage Type : <?=($value["building_type"]!="")?$value["building_type"]:"N/A";?> <input type="hidden" name="govt_building" value="<?=$value["building_type"];?>"></th>
                    <th>Property Usage Type : <?=$value["prop_usage_type"]?$value["prop_usage_type"]:"N/A";?><input type="hidden" name="property" value="<?=$value["prop_usage_type"];?>"></th>
                  </tr>
                  <input type="hidden" id="prop_usage_type_mstr_id" value="<?=$value["prop_usage_type_mstr_id"];?>">
                  <input id="created_on" type="hidden" value="<?=$value["apply_date"];?>">
                  <input type="hidden" id="holding_no" name="holding_no" value="<?=$value["holding_no"];?>">
                  <input type="hidden" id="building_name" name="building_name" value="<?=$value["building_colony_name"];?>">

              	</thead>
              <?php endforeach;?>
               <?php endif;  ?>
  						</table>
  					</div>
					</div>

			</div>
		</div>

		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">Demand Details
      </h3>
			</div>
			<div class="panel-body">
        <div class="row">
          <label class="col-md-2"> Payment Upto Year :</label>
          <?php
        if (date('m') < 4) {//Upto June 2014-2015
        $financial_year = (date('Y')-1) . '-' . date('Y');
        } else {//After June 2015-2016
        $financial_year = date('Y') . '-' . (date('Y') + 1);
        }
        ?>
          <div class="col-md-3 pad-btm">
              <select id="fy" name="fy" class="form-control">
                   <?php
                  if(isset($fy)){
                      foreach ($fy as $fyear) {
                  ?>
                  <?php if($fyear['fy']==$financial_year){?>
                    <option value="<?= $fyear['id'];?>" selected><?=$fyear['fy'];?></option>
                  <?php }else{?>
                  <option value="<?= $fyear['id'];?>"><?=$fyear['fy'];?></option>
                  <?php
                }  }
                  }
                  ?>
              </select>
              <input type="hidden" name="upto_year" id="upto_year">

          </div>
          <label class="col-md-2"> Payment Upto Quarter :</label>
          <div class="col-md-3 pad-btm">
              <select id="quarter" name="quarter" class="form-control demand_get">
                </select>
           </div>
        </div>
				<div class="row">
					<div class="table-responsive">
						<table id="dem_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>SI No.</th>
									<th>Demand From</th>
									<th>Demand Upto</th>
									<th>Quarterly Tax(In Rs.)</th>
									<th>Demand(In Rs.)</th>
 									<th>Already Paid(In Rs.)</th>
									<th>Total(In Rs.)</th>
 								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
        <div class="row">
          <label class="col-md-12" style="color:red;"> Late Assessment Penalty :  <a id="laf"></a><input type="hidden" id="lafinp" name="laf"></label>

          <label class="col-md-12" style="color:green;"> Total Payable :  <a id="ttl"> </a><input type="hidden" id="ttlnme" name="ttl" value=""></label>

        </div>
        <div class="row">


          <div class="col-md-12 text-center">
        <button type="submit" class="btn btn-primary" name="print_review" >Print Demand</button>
          </div>


        </div>
        </form>

			</div>
		</div>
    </div>
</div>
    <!--End page content-->
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<script type="text/javascript">
$('#fy').on('change', function() {
  var fyid = this.value;
  var ackn = $("#ac").val();
  var slct = "slct";
   try{
      $.ajax({
          type:"POST",
          url: "<?=base_url('safDemand/get_quarter');?>",
         dataType: "json",
          data: {
              "fyid":fyid,
              "ackn":ackn,
              "slct":slct
          },
          beforeSend: function() {
              $("#loadingDiv").show();
          },
          success:function(data){
            //alert(data)
            if (data.response==true) {
              $('#quarter').empty();
              $('#quarter').append(`<option value="">Select</option>`);
              $('#quarter').html(data.data);
            }
              $("#loadingDiv").hide();
          },
          error: function(jqXHR, textStatus, errorThrown) {
              $("#loadingDiv").hide();
              alert(JSON.stringify(jqXHR));
              console.log(JSON.stringify(jqXHR));
              console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
          }
      });
  }catch (err) {
      alert(err.message);
  }
});
</script>
<script>
$('.demand_get').on('change', function() {
   var fy = $("#fy").val();
   var fy_text = $("#fy option:selected").text();
   $("#upto_year").val(fy_text);
    var qtr = $("#quarter").val();

   var id = $("#id").val();
   var demandfrm = $("#dem_table").find("td").eq(2).html();
   var ttl = 0;
  try{
     $.ajax({
         type:"POST",
         url: "<?=base_url('safDemand/get_demand');?>",
         dataType: "json",
         data: {
             "fy":fy,
             "qtr":qtr,
             "id":id
         },
         beforeSend: function() {
             $("#loadingDiv").show();
         },
         success:function(data){
           if (data) {

             $('#dem_table tbody').empty();
             var sn = 0;
             for (var i=0; i<data.length; i++) {
               sn++;
               var fy_upto = "";
               var qtr_upto = "";
               if(data.length==i+1) {
                 fy_upto = fy_text;
                 qtr_upto = qtr;
               } else {
                 if (data[i+1]['qtr']==1) {
                   qtr_upto = 4;
                   var fy_upto_arr = data[i+1]['fy'];
                   fy_upto_arr = fy_upto_arr.split("-");
                   fy_upto = (fy_upto_arr[0]-1)+"-"+(fy_upto_arr[1]-1);
                 } else {
                   fy_upto = data[i+1]['fy'];
                   qtr_upto = data[i+1]['qtr']-1;
                 }
               }
               var demand = parseFloat(data[i]['sum']);
               var demand = demand.toFixed(2);

               $('#dem_table tbody').append('<tr><td>'+sn+'</td><td><input id="demfrom_'+i+'" name="demfrom[]" type="hidden" value="'+data[i]['fy']+ '/' +data[i]['qtr']+'">'+data[i]['financial_year']+ '/' +data[i]['qtrs']+'</td>'+
              ' <td><input name="dem_upto[]" type="hidden" value="'+fy_upto+ '/' +qtr_upto+'">'+fy_upto+ '/' +qtr_upto+'</td><td><input name="quar[]" type="hidden" value="'+data[i]['quarter']+'">'+data[i]['quarter']+'</td><td><input name="sum[]" type="hidden" value="'+demand+'">  '+demand+'</td>'+
              '<td>0</td><td><input name="total[]" type="hidden" value="'+demand+'">'+demand+'</td>'+
               '</tr>');

                ttl += parseFloat(data[i]['sum']);


             }
            }


            var prop_id = $("#prop_usage_type_mstr_id").val();
            var applied_date = $("#created_on").val();
            // calculation of no. of days between two date

      // To set two dates to two variables
      var applied_date = new Date(applied_date);
     var today_date = new Date();

  // To calculate the time difference of two dates
   var Difference_In_Time = today_date.getTime() - applied_date.getTime();

  // To calculate the no. of days between two dates
  var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);
  var Difference_In_Days = Difference_In_Days.toFixed(0);

const oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
 const secondDate = new Date();
 var d = new Date();

 var m = new Date(d.setMonth(d.getMonth() - 3));
 const diffDays = Math.round(Math.abs((m - secondDate) / oneDay));

     if(Difference_In_Days>diffDays)
    {
      if(prop_id==1)
      {
      var total = parseInt(ttl)+5000;
      $("#ttl").text(total.toFixed(2));
      $("#ttlnme").val(total.toFixed(2));
      $("#laf").text("5000.00");
      $("#lafinp").val("5000.00");
      }
      else
       {
      var total = parseInt(ttl)+2000;
      $("#ttl").text(total.toFixed(2));
      $("#ttlnme").val(total.toFixed(2));
      $("#laf").text("2000.00");
      $("#lafinp").val("2000.00");
      }
    }
    else {
      $("#ttl").text(ttl.toFixed(2));
      $("#ttlnme").val(ttl.toFixed(2));
      $("#laf").text("0");
      $("#lafinp").val("0");
    }

             $("#loadingDiv").hide();
         },
         error: function(jqXHR, textStatus, errorThrown) {
             $("#loadingDiv").hide();
             alert(JSON.stringify(jqXHR));
             console.log(JSON.stringify(jqXHR));
             console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
         }
     });
 }catch (err) {
     alert(err.message);
 }

});
</script>
<script>
$( document ).ready(function() {
   var fyid = $("#fy").val();
  var ackn = $("#ac").val();
   try{
      $.ajax({
          type:"POST",
          url: "<?=base_url('safDemand/get_quarter');?>",
         dataType: "json",
          data: {
              "fyid":fyid,
              "ackn":ackn
          },
          beforeSend: function() {
              $("#loadingDiv").show();
          },
          success:function(data){
            //alert(data)
            if (data.response==true) {
                $('#quarter').html(data.data);
            }
              $("#loadingDiv").hide();
              getdemand();
          },
          error: function(jqXHR, textStatus, errorThrown) {
              $("#loadingDiv").hide();
              alert(JSON.stringify(jqXHR));
              console.log(JSON.stringify(jqXHR));
              console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
          }
      });
  }catch (err) {
      alert(err.message);
  }
});
</script>
<script>
function getdemand() {
  var fy = $("#fy").val();
   var fy_text = $("#fy option:selected").text();
   $("#upto_year").val(fy_text);
  var qtr = $("#quarter").val();
   var id = $("#id").val();

   var demandfrm = $("#dem_table").find("td").eq(2).html();
  var ttl = 0;
 try{
    $.ajax({
        type:"POST",
        url: "<?=base_url('safDemand/get_demand');?>",
        dataType: "json",
        data: {
            "fy":fy,
            "qtr":qtr,
            "id":id
        },
        beforeSend: function() {
            $("#loadingDiv").show();
        },
        success:function(data){
          if (data) {
            $('#dem_table tbody').empty();
            var sn = 0;
            for (var i=0; i<data.length; i++) {
              sn++;
              var fy_upto = "";
              var qtr_upto = "";
              if(data.length==i+1) {
                fy_upto = fy_text;
                qtr_upto = qtr;
              } else {
                if (data[i+1]['qtr']==1) {
                  qtr_upto = 4;
                  var fy_upto_arr = data[i+1]['fy'];
                  //alert(fy_upto_arr);
                  fy_upto_arr = fy_upto_arr.split("-");
                  fy_upto = (fy_upto_arr[0]-1)+"-"+(fy_upto_arr[1]-1);
                } else {
                  fy_upto = data[i+1]['fy'];
                  qtr_upto = data[i+1]['qtr']-1;
                }
              }
              var demand = parseFloat(data[i]['sum']);
              var demand = demand.toFixed(2);


              $('#dem_table tbody').append('<tr><td>'+sn+'</td><td><input id="demfrom_'+i+'" name="demfrom[]" type="hidden" value="'+data[i]['financial_year']+ '/' +data[i]['qtrs']+'">'+data[i]['financial_year']+ '/' +data[i]['qtrs']+'</td>'+
             ' <td><input name="dem_upto[]" type="hidden" value="'+fy_upto+ '/' +qtr_upto+'">'+fy_upto+ '/' +qtr_upto+'</td><td><input name="quar[]" type="hidden" value="'+data[i]['quarter']+'">'+data[i]['quarter']+'</td><td><input name="sum[]" type="hidden" value="'+demand+'">  '+demand+'</td>'+
             '<td>0</td><td><input name="total[]" type="hidden" value="'+demand+'">'+demand+'</td>'+
              '</tr>');

               ttl += parseFloat(data[i]['sum']);

            }
           }

           var prop_id = $("#prop_usage_type_mstr_id").val();
           var created_on = $("#created_on").val();
           // calculation of no. of days between two date

     // To set two dates to two variables
     var date1 = new Date(created_on);
    var date2 = new Date();

 // To calculate the time difference of two dates
 var Difference_In_Time = date2.getTime() - date1.getTime();

 // To calculate the no. of days between two dates
 var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);
 var Difference_In_Days = Difference_In_Days.toFixed(0);
   if(Difference_In_Days>90)
   {
     if(prop_id==1)
     {
     var total = parseInt(ttl)+5000;
     $("#ttl").text(total.toFixed(2));
     $("#ttlnme").val(total.toFixed(2));
     $("#laf").text("5000.00");
     $("#lafinp").val("5000.00");
     }
     else
      {
     var total = parseInt(ttl)+2000;
     $("#ttl").text(total.toFixed(2));
     $("#ttlnme").val(total.toFixed(2));
     $("#laf").text("2000.00");
     $("#lafinp").val("2000.00");
     }
   }
   else {
     $("#ttl").text(ttl.toFixed(2));
     $("#ttlnme").val(ttl.toFixed(2));
     $("#laf").text("0");
     $("#lafinp").val("0");
   }
            $("#loadingDiv").hide();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            $("#loadingDiv").hide();
            alert(JSON.stringify(jqXHR));
            console.log(JSON.stringify(jqXHR));
            console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
        }
    });
}catch (err) {
    alert(err.message);
}
}
</script>
