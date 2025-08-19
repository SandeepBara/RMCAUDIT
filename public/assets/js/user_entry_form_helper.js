$("#tableOwnership").on('click', '.removeOwnershipRow', function(e) {
    $(this).closest("tr").remove();    
	//$('#ownershipCount').val(parseInt($('#ownershipCount').val())-1);
});
function tableAddQualification(j){
	alert();
	var qualificationCount = parseInt($('#qualificationCount').val());
	j = qualificationCount+j;
	var newDivQualification = "";

    newDivQualification	+=	'<tr>';
    newDivQualification	+=      '<td>'
    newDivQualification	+=          '<select id="passing_year'+j+'" name="passing_year[]" class="form-control">';
	newDivQualification	+=		    	'<option value="" style="display:none;">Select Passing Year</option>';
	newDivQualification	+=              '<?php for($i=(date("Y")-1); $i>(date("Y")-50); $i--){?>';
	newDivQualification	+=				'<option value="<?=$i;?>"><?=$i;?></option>';
	newDivQualification	+=			    '<?php }?>'
	newDivQualification	+=		    '</select>';
	newDivQualification	+=      '</td>';
    newDivQualification	+=		'<td>';
    newDivQualification	+=			'<select id="Qualification'+j+'" name="Qualification[]" class="form-control">';
	newDivQualification	+=	            '<option value="" style="display:none;">Select Qualification</option>';
	newDivQualification	+=	            '@foreach ($doctor_qualifications as $doctor_qualification)';
	newDivQualification	+=		        '<option value="{{ $doctor_qualification }}">{{ $doctor_qualification }}</option>';
	newDivQualification	+=			   	'@endforeach';
	newDivQualification	+=          '</select>';
    newDivQualification	+=		'</td>';
    newDivQualification	+=		'<td>';
    newDivQualification	+=			'<input type="text" id="collage_name'+j+'" name="collage_name[]" class="form-control" placeholder="Enter Collage Name" value="" onkeypress="return IsAlpha(event);">';
    newDivQualification	+=		'</td>';
    newDivQualification	+=		'<td>';
    newDivQualification	+=			'<input type="text" id="percentage'+j+'" name="percentage[]" class="form-control" placeholder="Enter Percentage" value="" onkeypress="return IsNum(event);">';
    newDivQualification	+=		'</td>';
    newDivQualification	+=		'<td>';
    newDivQualification	+=			'<input type="text" id="description'+j+'" name="description[]" class="form-control" placeholder="Enter Description" value="" onkeypress="return IsAlpha(event);">';
    newDivQualification	+=		'</td>';
    newDivQualification	+=		'<td>';
	newDivQualification	+=			'<span onclick="tableAddQualification('+j+')">ADD</span>';	
    newDivQualification	+=		'</td>';
    newDivQualification	+=	'</tr>';
    $('#rowAddQualification').append(newDivQualification);
	$('#qualificationCount').val(j);
}