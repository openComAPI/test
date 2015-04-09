function project_scn1(){    var flag = 'no';    if ($("#txtProjectName").val() == '')    {        $("#err_project_name").show();        flag = 'yes';    } else    {        $("#err_project_name").hide();    }    if ($(".builderId").val() == '')    {        $("#err_builder_id").show();        flag = 'yes';    } else    {        $("#err_builder_id").hide();    }    if ($(".cityId").val() == '')    {        $("#err_city_id").show();        flag = 'yes';    } else    {        $("#err_city_id").hide();    }    if ($(".suburbId").val() == '')    {        $("#err_suburb_id").show();        flag = 'yes';    } else    {        $("#err_suburb_id").hide();    }    if ($(".localityId").val() == '')    {        $("#err_locality_id").show();        flag = 'yes';    } else    {        $("#err_locality_id").hide();    }    if ($("#txtProjectDescription").val() == '')    {        $("#err_project_desc").show();        flag = 'yes';    } else    {        $("#err_project_desc").hide();    }    if ($("#txtProjectAddress").val() == '')    {        $("#err_project_address").show();        flag = 'yes';    } else    {        $("#err_project_address").hide();    }    if ($("#txtProjectDesc").val() == '')    {        $("#err_project_bhk").show();        flag = 'yes';    } else    {        $("#err_project_bhk").hide();    }    if ($("#txtProjectSource").val() == '')    {        $("#err_project_source").show();        flag = 'yes';    } else    {        $("#err_project_source").hide();    }    if ($(".project_type").val() == '')    {        $("#err_project_type").show();        flag = 'yes';    } else    {        $("#err_project_type").hide();    }    if ($("#txtProjectLocation").val() == '')    {        $("#err_project_loc_desc").show();        flag = 'yes';    } else    {        $("#err_project_loc_desc").hide();    }    if ($("#txtProjectLattitude").val() == '')    {        $("#err_project_latt").show();        flag = 'yes';    } else    {        $("#err_project_latt").hide();    }    if ($("#txtProjectLongitude").val() == '')    {        $("#err_project_long").show();        flag = 'yes';    } else    {        $("#err_project_long").hide();    }    if ($(".fieldState").val() == '')    {        $("#err_project_status").show();        flag = 'yes';    } else    {        $("#err_project_status").hide();    }    if ($("#project_size").val() >= 500)    {        $("#err_project_size").show();        flag = 'yes';    } else    {        $("#err_project_size").hide();    }    if ($("#Completion").val() == '')    {        $("#err_project_completion").show();        flag = 'yes';    } else    {        $("#err_project_completion").hide();    }    if ($("#power_backup_capacity").val() >= 10)    {        $("#err_power_bkpKba_10").show();        flag = 'yes';    } else    {        $("#err_power_bkpKba_10").hide();    }    if (($('#existingStatus').val() == 'Inactive' && $('#Active').val() == 'Inactive') || $('#existingStatus').length == 0) {    }    else if ($('#Active').val() == 'Inactive') {        if ($('input[name=reason]:checked').val() == undefined) {            $("#err_project_active").html("Please select reason to Inactive");            $("#err_project_active").show();            flag = 'yes';        }        else if ($('input[name=reason]:checked').val() == 'duplicate') {            if ($('input[name=duplicate_pid]').val() == undefined || $('input[name=duplicate_pid]').val() == '') {                $("#err_project_active").html("Please Enter Duplicate PID");                $("#err_project_active").show();                flag = 'yes';            } else {                if ($('input[name=duplicate_pid]').val() == $('input[name=projectId]').val())                {                    flag = 'yes';                    $("#err_project_active").html("Please enter different Project ID");                    $("#err_project_active").show();                } else {                    //checking if PID exist or not                    $.ajax({                        type: "POST",                        url: '/pidValidation.php',                        async: false,                        data: {pid: $('input[name=duplicate_pid]').val()},                        success: function (msg) {                            if (msg == 0)                                $("#err_project_active").html("NE");                            if (msg == 'Inactive')                                $("#err_project_active").html("NA");                        }                    });                    if ($("#err_project_active").html() == 'NE') {                        flag = 'yes';                        $("#err_project_active").html("Duplicate PID does not exist.");                        $("#err_project_active").show();                    }                    if ($("#err_project_active").html() == 'NA') {                        flag = 'yes';                        $("#err_project_active").html("Duplicate PID is Inactive.");                        $("#err_project_active").show();                    }                }            }        } else if ($('input[name=reason]:checked').val() == 'other_reason') {            if ($('textarea[name=other_reason_txt]').val() == undefined || $('textarea[name=other_reason_txt]').val() == '') {                $("#err_project_active").html("Please enter reason to Inactive");                $("#err_project_active").show();                flag = 'yes';            }        }        else            $("#err_project_active").html("");    } else {        $("#err_project_active").hide();    }    if ($("#offer_heading").val() != '' || $("#offer_desc").val() != '') {        var offerHeading = $("#offer_heading").val().trim();        var lnth = parseInt(offerHeading.split(" ").length - 1);        if (lnth < 1 || lnth > 2) {            $("#offerHeading").html("Minimum two words and maximum three words allow!");            flag = 'yes';        }        else if (offerHeading.length < 8) {            $("#offerHeading").html("Minimum 8 characters allow!");            flag = 'yes';        }        else {            $("#offerHeading").html("");        }        var offerDesc = $("#offer_desc").val().trim();        var lnthDesc = offerDesc.split(" ").length - 1;        if (parseInt(lnthDesc) < 3) {            $("#offerDesc").html("Minimum four words allow!");            flag = 'yes';        }        else if (offerDesc.length < 13) {            $("#offerDesc").html("Minimum 13 characters allow!");            flag = 'yes';        }        else {            $("#offerDesc").html("");        }    }    if (flag == 'yes')        return false;    else        return true;}/*********function scn 2*************/function project_scn2(){    var flag = 'no';    if ($(".BedId").val() == '')    {        $("#err_bed_name").show();        flag = 'yes';    } else    {        $("#err_bed_name").hide();    }    if ($(".FlatId").val() == '')    {        $("#err_flat_name").show();        flag = 'yes';    }    else    {        $("#err_flat_name").hide();    }    if (isNaN($(".FlatId").val()))    {        $("#err_flat_number").show();        flag = 'yes';    }    else    {        $("#err_flat_number").hide();    }    if (isNaN($(".AvilFlatId").val()))    {        $("#err_avail_number_flt").show();        flag = 'yes';    }    else    {        $("#err_avail_number_flt").hide();    }    if (parseInt($(".FlatId").val()) < parseInt($(".AvilFlatId").val()))    {        $("#err_avail_number_grtr").show();        flag = 'yes';    }    else    {        $("#err_avail_number_grtr").hide();    }    if ($(".soi").val() == '')    {        $("#err_soi").show();        flag = 'yes';    }    else    {        $("#err_soi").hide();    }    if ($(".flats").val() == '')    {        $("#err_flats").show();        flag = 'yes';    }    else    {        $("#err_flats").hide();    }    if (flag == 'yes')        return false;    else        return true;}/*********function scn 2*************/function project_scn3(){    var flag = 'no';    if ($("#TowerId").val() == '')    {        $("#err_tower_name").show();        flag = 'yes';    }    else    {        $("#err_tower_name").hide();    }    if ($("#FloorId").val() == '')    {        $("#err_floor_name").show();        flag = 'yes';    }    else    {        if (isNaN($(".FloorId").val()))        {            $("#err_floor_number").show();            flag = 'yes';        }        else        {            $("#err_floor_number").hide();        }        $("#err_floor_name").hide();    }    if (isNaN($(".AvilFlatTowerId").val()))    {        $("#err_Avail_tower").show();        flag = 'yes';    }    else    {        $("#err_Avail_tower").hide();    }    if ($(".stilt").val() == '')    {        $("#err_stilt").show();        flag = 'yes';    }    else    {        $("#err_stilt").hide();    }    if (flag == 'yes')        return false;    else        return true;}function tower_status(){    var flag = 'no';    if ($(".tower_name_select").val() == '')    {        $("#err_tower_status").show();        flag = 'yes';    }    else    {        $("#err_tower_status").hide();    }    if ($(".completed_floors").val() == '')    {        $("#err_floor_name").show();        flag = 'yes';    }    else    {        $("#err_floor_name").hide();    }    if (isNaN($(".completed_floors").val()))    {        $("#err_floor_number_").show();        flag = 'yes';    }    else    {        $("#err_floor_number_").hide();    }    if ($("#remark").val() == '')    {        $("#err_edit_reson").show();        flag = 'yes';    }    else    {        $("#err_edit_reson").hide();    }    if ($("#f_date_c_to").val() == '')    {        $("#err_date").show();        flag = 'yes';    }    else    {        $("#err_date").hide();    }    if ($("#f_date_c_to1").val() == '')    {        $("#err_date1").show();        flag = 'yes';    }    else    {        $("#err_date1").hide();    }    if (flag == 'yes')        return false;    else        return true;}function construction_status(){    var flag = 'no';    if ($("#remark").val() == '')    {        $("#err_edit_reson").show();        flag = 'yes';    }    else    {        $("#err_edit_reson").hide();    }    if ($("#f_date_c_to").val() == '')    {        $("#err_date").show();        flag = 'yes';    }    else    {        $("#err_date").hide();    }    if (flag == 'yes')        return false;    else        return true;}/*******function for two date diff*********/function dateBetween(date1, date2){    var zeroReplaceDate1 = date1.replace(" 00:00:00", "");    var zeroReplaceDate2 = date2.replace(" 00:00:00", "");    var converDt1 = zeroReplaceDate1.split('-').join('/');    var converDt2 = zeroReplaceDate2.split('-').join('/');    var date3 = new Date(converDt1);    var date4 = new Date(converDt2);    var timeDiff = date4.getTime() - date3.getTime();    if (timeDiff > 0)        return 1    else        return 0;}/** check gramatical errors **/function find_errors(url, textToTest) {        $.ajax({        type: "POST",        async: false,        url: url,        data: {text: textToTest},        beforeSend: function(){                    console.log('in ajax beforeSend');                    $("body").addClass("loading");                  },        success: function (msg) {            //console.log(msg.data);            $("body").removeClass("loading");            spellErrors = '';            $.each(msg.data, function(i, v){                $.each(v, function(j, k){                    spellErrors += k + "\n";                    console.log(k);                });                           });            res = confirm(spellErrors);                       if(res == true){                $('<input>').attr('type','hidden').attr('name', 'btnSave').appendTo('form#frmcity');                $('#frmcity').submit();            }            else                return false;        }    });}