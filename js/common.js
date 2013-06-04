function project_scn1(edittype)
{
	
	var flag = 'no';
	if($("#txtProjectName").val() == '')
	{
		$("#err_project_name").show();
		flag = 'yes';
	}else
	{
		$("#err_project_name").hide();
	}
	
	if($(".builderId").val() == '')
	{
		$("#err_builder_id").show();
		flag = 'yes';
	}else
	{
		$("#err_builder_id").hide();
	}

	if($(".cityId").val() == '')
	{
		$("#err_city_id").show();
		flag = 'yes';
	}else
	{
		$("#err_city_id").hide();
	}

	if($(".suburbId").val() == '')
	{
		$("#err_suburb_id").show();
		flag = 'yes';
	}else
	{
		$("#err_suburb_id").hide();
	}

	if($(".localityId").val() == '')
	{
		$("#err_locality_id").show();
		flag = 'yes';
	}else
	{
		$("#err_locality_id").hide();
	}

	if($("#txtProjectDescription").val() == '')
	{
		$("#err_project_desc").show();
		flag = 'yes';
	}else
	{
		$("#err_project_desc").hide();
	}

	if($("#txtProjectAddress").val() == '')
	{
		$("#err_project_address").show();
		flag = 'yes';
	}else
	{
		$("#err_project_address").hide();
	}

	if($("#txtProjectDesc").val() == '')
	{
		$("#err_project_bhk").show();
		flag = 'yes';
	}else
	{
		$("#err_project_bhk").hide();
	}

	if($("#txtProjectSource").val() == '')
	{
		$("#err_project_source").show();
		flag = 'yes';
	}else
	{
		$("#err_project_source").hide();
	}

	if($(".project_type").val() == '')
	{
		$("#err_project_type").show();
		flag = 'yes';
	}else
	{
		$("#err_project_type").hide();
	}

	if($("#txtProjectLocation").val() == '')
	{
		$("#err_project_loc_desc").show();
		flag = 'yes';
	}else
	{
		$("#err_project_loc_desc").hide();
	}

	if($("#txtProjectLattitude").val() == '')
	{
		$("#err_project_latt").show();
		flag = 'yes';
	}else
	{
		$("#err_project_latt").hide();
	}

	if($("#txtProjectLongitude").val() == '')
	{
		$("#err_project_long").show();
		flag = 'yes';
	}else
	{
		$("#err_project_long").hide();
	}

	if($(".fieldState").val() == '')
	{
		$("#err_project_status").show();
		flag = 'yes';
	}else
	{
		$("#err_project_status").hide();
	}

	if($("#txtProjectURL").val() == '')
	{
		$("#err_project_url").show();
		flag = 'yes';
	}else
	{
		$("#err_project_url").hide();
	}

	if($("#project_size").val() >=500)
	{
		$("#err_project_size").show();
		flag = 'yes';
	}else
	{
		$("#err_project_size").hide();
	}


	if($("#Completion").val() == '')
	{
		$("#err_project_completion").show();
		flag = 'yes';
	}else
	{
		$("#err_project_completion").hide();
	}

	if($("#power_backup_capacity").val() >= 10)
	{
		$("#err_power_bkpKba_10").show();
		flag = 'yes';
	}else
	{
		$("#err_power_bkpKba_10").hide();
	}
	if(edittype != 547 && edittype != 525 && edittype != 588)
	{
		if($("#project_type_hidden").val() != '' && $("#project_type_hidden").val() != 0)
		{
			if($(".project_type").val() != $("#project_type_hidden").val())
			{
				$("#err_project_typeChk").show();
				flag = 'yes';
			}
			else
			{
				$("#err_project_typeChk").hide();
			}
		}
	}
	/*******code for new launch and completion date diff*******/
	var launchDt   = $("#f_date_c_to").val();
	var promisedDt = $("#f_date_c_prom").val();
	var preLaunchDt = $("#pre_f_date_c_to").val();

	if(launchDt == '0000-00-00 00:00:00')
		launchDt = '';
	if(preLaunchDt == '0000-00-00 00:00:00')
		preLaunchDt = '';
	if(promisedDt == '0000-00-00 00:00:00')
		promisedDt = '';
	
	if(launchDt != '' && promisedDt !='' )
	{
		var retdt  = dateBetween(launchDt,promisedDt);
		if(retdt == 0)
		{
			alert("Completion date to be always greater than launch date");
			$("#f_date_c_prom").focus();
			return false;

		}
	}
	if(preLaunchDt != '' && launchDt !='' )
	{
		var retdt  = dateBetween(preLaunchDt,launchDt);
		if(retdt == 0)
		{
			alert("Launch date to be always greater than Pre Launch date");
			$("#pre_f_date_c_to").focus();
			return false;

		}
	}
	
	if(preLaunchDt != '' && promisedDt !='' )
	{
		var retdt  = dateBetween(preLaunchDt,promisedDt);
		if(retdt == 0)
		{
			alert("Completion date to be always greater than Pre Launch date");
			$("#pre_f_date_c_to").focus();
			return false;

		}
	}
	/*******code for new launch and completion date diff*******/

	if(flag == 'yes')
		return false;
	else
		return true;	
}

/*********function scn 2*************/
function project_scn2()
{
	var flag = 'no';
	if($(".BedId").val() == '')
	{
		$("#err_bed_name").show();
		flag = 'yes';
	}else
	{
		$("#err_bed_name").hide();
	}

    if($(".FlatId").val()== '')
    {
		$("#err_flat_name").show();
	    flag='yes';
    }
	else
	{	
		$("#err_flat_name").hide();
		
	}

	if(isNaN($(".FlatId").val()))
	{
		$("#err_flat_number").show();
		flag='yes';
		
	}
	else
	{
		$("#err_flat_number").hide();
	}   

    if(isNaN($(".AvilFlatId").val()))
	{
		$("#err_avail_number_flt").show();
		flag='yes';
	}
	else
	{
		$("#err_avail_number_flt").hide();
	}

	if(parseInt($(".FlatId").val()) < parseInt($(".AvilFlatId").val()))
	{
		$("#err_avail_number_grtr").show();
		flag='yes';
	}
	else
	{
		$("#err_avail_number_grtr").hide();
	}


    if($(".soi").val()== '')
    {
		$("#err_soi").show();
	    flag='yes';
    }
	else
	{
		 $("#err_soi").hide();
	}    
    if($(".flats").val()=='')
	{
		$("#err_flats").show();
		flag='yes';
    }
	else 
	{
		$("#err_flats").hide();
	}

	if(flag == 'yes')
		return false;
	else
		return true;	
}


/*********function scn 2*************/
function project_scn3()
{
	var flag = 'no';
	
	if($("#TowerId").val() == '')
	{
		$("#err_tower_name").show();
		flag = 'yes';
	}
	else
	{
		$("#err_tower_name").hide();
	}

	if($("#FloorId").val()=='')
	{
		$("#err_floor_name").show();
		flag='yes';
    }
	else 
	{
		if(isNaN($(".FloorId").val()))
		{
			$("#err_floor_number").show();
			flag='yes';	
		}
		else
		{
			$("#err_floor_number").hide();
		}
		$("#err_floor_name").hide();
	}


	

	if(isNaN($(".AvilFlatTowerId").val()))
	{
		$("#err_Avail_tower").show();
		flag='yes';	
	}
	else
	{
		$("#err_Avail_tower").hide();
	}

	if($(".stilt").val()=='')
	{
		$("#err_stilt").show();
		flag='yes';
    }
	else 
	{
		$("#err_stilt").hide();
	}
    
	if(flag == 'yes')
		return false;
	else
		return true;	
}

function tower_status()
{
   var flag='no';

	if($(".tower_name_select").val()=='')
	{
		$("#err_tower_status").show();
		flag='yes';
	}
	else
	{
		$("#err_tower_status").hide();
	}

  
    if($(".completed_floors").val()=='')
	{

		$("#err_floor_name").show();
		flag='yes';
    }
	else 
	{
		$("#err_floor_name").hide();
	}

    if(isNaN($(".completed_floors").val()))
	{
			$("#err_floor_number_").show();
			flag='yes';
			
	}
	else
	{
			$("#err_floor_number_").hide();
	}

	if($("#remark").val()=='')
	{

		$("#err_edit_reson").show();
        flag='yes';
	 
	}
	else 
	{
        $("#err_edit_reson").hide();
	}
	
	if($("#f_date_c_to").val()=='')
	{

		$("#err_date").show();
		flag='yes';
    }
	else 
	{
		$("#err_date").hide();
	}

	if($("#f_date_c_to1").val()=='')
	{

		$("#err_date1").show();
		flag='yes';
    }
	else 
	{
		$("#err_date1").hide();
	}

	if(flag == 'yes')
		return false;
	else
		return true;
}

function construction_status()
{
	 var flag='no';

	if($("#remark").val()=='')
	{
		$("#err_edit_reson").show();
        flag='yes';	 
	}
	else 
	{
        $("#err_edit_reson").hide();
	}
	
	if($("#f_date_c_to").val()=='')
	{

		$("#err_date").show();
		flag='yes';
    }
	else 
	{
		$("#err_date").hide();
	}

	if(flag == 'yes')
		return false;
	else
		return true;
}

/*******function for two date diff*********/
function dateBetween( date1, date2 ) 
  {
  		var zeroReplaceDate1 = date1.replace(" 00:00:00","");
  		var zeroReplaceDate2 = date2.replace(" 00:00:00","");

  		var converDt1 = zeroReplaceDate1.split('-').join('/');
  		var converDt2 = zeroReplaceDate2.split('-').join('/');

	    var date3 = new Date(converDt1);
		var date4 = new Date(converDt2);

		var timeDiff = date4.getTime() - date3.getTime();
		if(timeDiff >0)
			return 1
		else
			return 0;
	}
