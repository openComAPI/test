<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="jscal/calendar.js"></script>
<script type="text/javascript" src="jscal/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscal/calendar-setup.js"></script>

<script type="text/javascript">    
    $(document).ready(function() {
        var pid = '{$phaseId}';
        $('select#phaseSelect').val(pid);
        toggle_supply_and_option();
    });
     
    $(document).ready(function(){
		$('.supply_select .reset_option_and_supply').each(function(i,v){
			if(i > 0)
				$(this).hide();
		});
        $('#isLaunchUnitPhase').change(function(){
            $('.launched').each(function(){
                if($('#isLaunchUnitPhase')[0].checked)$(this).removeAttr('readonly');
                else $(this).attr('readonly', 'true');
            });
        });
    });

    function updateURLParameter(url, param, paramVal) {
        var newAdditionalURL = "";
        var tempArray = url.split("?");
        var baseURL = tempArray[0];
        var additionalURL = tempArray[1];
        var temp = "";
        if (additionalURL) {
            tempArray = additionalURL.split("&");
            for (i = 0; i < tempArray.length; i++) {
                if (tempArray[i].split('=')[0] != param) {
                    newAdditionalURL += temp + tempArray[i];
                    temp = "&";
                }
            }
        }

        var rows_txt = temp + "" + param + "=" + paramVal;
        return baseURL + "?" + newAdditionalURL + rows_txt;
    }

    function change_phase() {
        var new_id = $('#phaseSelect').val();
        var newURL = updateURLParameter(window.location.href, 'phaseId', new_id);
        window.location.href = newURL;
    }

    function validate_phase() {
        var name_flag = true;
        var flat_bed = true;
        var villa_bed = true;
        var plot_supply = true;
        var office_supply = true;
        var shop_supply = true;

        {*var phasename = $('#phaseName').val();
        if (!phasename) {
            $('#err_phase_name').show();
            name_flag = false;
        }
        else {
            $('#err_phase_name').hide();
            name_flag = true;
        }*}

        $('li.flat_bed').each(function() {
            var intRegex = /^\d+$/;
            var input = $(this).find('input');
            if (!$(input).is(":disabled")) {
                var v = input.val();
                var err = $(this).find('span.err_flat_bed');
                if (!intRegex.test(v)) {
                    $(err).show();
                    villa_bed = false;
                }
                else {
                    $(err).hide();
                }
            }
        });

        $('li.villa_bed').each(function() {
            var intRegex = /^\d+$/;
            var input = $(this).find('input');
            if (!$(input).is(":disabled")) {
                var v = input.val();
                var err = $(this).find('span.err_villa_bed');
                if (!intRegex.test(v)) {
                    $(err).show();
                    flat_bed = false;
                }
                else {
                    $(err).hide();
                }
            }
        });

        if($('#supply').is(':visible')){
                 var intRegex = /^\d+$/;
                 var err = $('span.err_plotsupply');
                  if (!intRegex.test($('#supply').val())) {
                    $(err).show();
                    plot_supply = false;
                }
                else {
                    $(err).hide();
                }

        }
        
        if($('#supply_office').is(':visible')){
                 var intRegex = /^\d+$/;
                 var err = $('span.err_officesupply');
                  if (!intRegex.test($('#supply_office').val())) {
                    $(err).show();
                    office_supply = false;
                }
                else {
                    $(err).hide();
                }

        }
        
        if($('#supply_shop').is(':visible')){
                 var intRegex = /^\d+$/;
                 var err = $('span.err_shopsupply');
                  if (!intRegex.test($('#supply_shop').val())) { 
                    $(err).show();
                    shop_supply = false;
                }
                else {
                    $(err).hide();
                }

        }
       // alert(name_flag+"<==>"+flat_bed+"<==>"+villa_bed+"<==>"+plot_supply+"<==>"+office_supply+"----"+shop_supply);
        return name_flag && flat_bed && villa_bed && plot_supply && office_supply && shop_supply;
    }

    function deletePhase()
    {
            return confirm("All dependent data will be deleted. Proceed / cancel?");
    }

        function toggle_supply_and_option() {
            $(".reset_option_and_supply").click(function() {
                if ($(this).is(".supply_button")) {
                    $(".options_select").show();
                    $(".options_select  select").removeAttr("disabled");
                    $(".supply_select  input").attr("disabled", true);
                    $(".supply_select").hide();
                }
                else {
                    $(".supply_select").show();
                    $(".supply_select  input").removeAttr("disabled");
                    $(".options_select  select").attr("disabled", true);
                    $(".options_select").hide();
                }
                return false;
            });

            $(".select_all_options").change(function() {
                if ($(this).is(":checked")) {
                    $("#options > option").attr("selected", true);
                    $("#options > option[value=\"-1\"]").attr("selected", false);
                }
                else {
                    $("#options > option").attr("selected", false);
                }
                return false;
            });

            $("#options").change(function() {
                var all_select = $("#options").find("option:selected").not("option[value='-1']").length == $("#options").
                        find("option").not("option[value='-1']").length;
                if (all_select) {
                    $(".select_all_options").attr("checked", true);
                }
                else {
                    $(".select_all_options").attr("checked", false);
                }
                return false;
            });
        }
        
        function refrech_date(id){
			$('#'+id).val("");
		}

</script>
<input type = "hidden" name = "projectId" id = "projectId" value="{$projectId}">
<tr>
    <td class="white-bg paddingright10" vAlign=top align=middle bgColor=#ffffff>
        <table cellSpacing=0 cellPadding=0 width="100%" border=0>
            <tr>
                <td width=224 height=25>&nbsp;</td>
                <td width=10>&nbsp;</td>
                <td width=866>&nbsp;</td>
            </tr>
            <tr>
                <td class=paddingltrt10 vAlign=top align=middle bgColor=#ffffff>
                    {include file="{$PROJECT_ADD_TEMPLATE_PATH}left.tpl"}
                </td>
                <td vAlign=center align=middle width=10 bgColor=#f7f7f7>&nbsp;</td>
                <td vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
                    <table cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><tbody>
                            <tr>
                                <td class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                                    <table cellSpacing=0 cellPadding=0 width="99%" border=0>
                                        <tbody>
                                            <tr>
                                                <td class="h1" width="67%"><img height="18" hspace="5" src="../images/arrow.gif" width="18">Edit Phase ({$ProjectDetail[0]['BUILDER_NAME']} {$ProjectDetail[0]['PROJECT_NAME']})</td>
                                                <td width="33%" align ="right"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr></tr>
                        <td vAlign="top" align="middle" class="backgorund-rt" height="350"><BR>

                            <table cellSpacing="1" cellPadding="4" width="67%" align="center" border="0">
                                <form method="post" id="phase_form">
                                    <input type='hidden' name='project_type_id' value="{$ProjectDetail[0]['PROJECT_TYPE_ID']}">
                                    {if $error_msg}
                                        <tr>
                                            <td colspan="3"><font color ="red">Error :: {$error_msg}</font></td>
                                        </tr>
                                    {/if}
                                    <tr>
                                        <td width="20%" align="right"><b>Phase :</b> </td>
                                        <td width="30%" align="left">
                                            <select id="phaseSelect" name="phaseSelect" onchange="change_phase();">
                                                <option value="-1">Select Phase</option>
                                                {foreach $phases as $p}
                                                    <option value="{$p.id}">{$p.name}</option>
                                                {/foreach}
                                            </select>
                                        </td>
                                        <td width="50%" align="left">
                                           {if $phaseId != -1} <input class="pt_click" type="button" title="Update Completion Date" value="Update Completion Date">{/if}
                                        </td>
                                    </tr>


                                    {if isset($phaseId) and $phaseId != -1}
                                            <tr>
                                                <td width="20%" align="right"><font color ="red">*</font><b>Phase Name :</b> </td>
                                                <td width="30%" align="left">
                                                    <input id="phaseName" name="phaseName" value="{$phasename}" {if $phaseObject.PHASE_TYPE == 'Logical'} readonly {/if} />
                                                </td>
                                                <td width="50%" align="left">
                                                    <font color="red"><span id="err_phase_name" style = "display:none;">Enter Phase Name</span></font>
                                                </td>
                                            </tr>
                                             <tr>
                                                <td width="20%" align="right"><font color ="red">*</font><b>Construction Status :</b> </td>
                                                <td width="30%" align="left">                                                   
                                                    <select name="construction_status" id="construction_status" class="fieldState">
														<option value="">Select</option>
														{foreach from = $projectStatus key = key item = value}
															<option value="{$key}" {if $key == $construction_status} selected {/if}>{$value} </option>
														{/foreach}
													 </select>
                                                </td>
                                                <td width="50%" align="left">
                                                    <font color="red"><span id="err_construction_status" style = "display:none;">Select Construction Status</span></font>
                                                </td>
                                            </tr> 
                                            <tr>
                                                <td width="20%" align="right" valign="top"><b>Completion Date  :</b> </td>
                                                <td width="30%" align="left">
                                                    {$completion_date}
                                                <td width="50%" align="left">
                                                    &nbsp;
                                                </td>
                                            </tr>
                                            <tr>
											   <td width="20%" align="right" valign="top"><b>Pre - Launch Date :</b> </td>
											   <td width="30%" align="left">
												   <input name="phase_pre_launch_date" value="{$phase_pre_launch_date}" type="text" class="formstyle2" id="pre_f_date_c_to" size="10" readonly="1"/>  <img src="images/cal_1.jpg" id="pre_f_trigger_c_to" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
												   &nbsp;&nbsp;<img width="15" height="15" id="ref-prelaunch-date" onclick="refrech_date('pre_f_date_c_to')" title="Refresh Launch Date"  src="../images/refresh.png">
											   </td>
											   <td width="50%" align="left"><font color="red">{if count($ErrorMsg["phase_pre_launch_date"])>0}{$ErrorMsg["phase_pre_launch_date"]}{/if}
												{if count($ErrorMsg["phase_preLaunchDateAvailabilities"])>0}
													{$ErrorMsg["phase_preLaunchDateAvailabilities"]}
												{/if}
												{if count($ErrorMsg["phase_preLaunchDatePrices"])>0}
													{$ErrorMsg["phase_preLaunchDatePrices"]}
												{/if}
											   </font></td>
											</tr>
                                            <tr>
                                                <td width="20%" align="right" valign="top"><b>Launch Date  :</b> </td>
                                                <td width="30%" align="left">
                                                    <input name="launch_date" value="{$launch_date}" type="text" class="formstyle2" id="launch_date" readonly="1" size="10" />  <img src="../images/cal_1.jpg" id="launch_date_trigger" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
                                                     &nbsp;&nbsp;<img width="15" height="15" id="ref-launch-date" onclick="refrech_date('launch_date')" title="Refresh Launch Date"  src="../images/refresh.png">
                                                </td>
                                                <td width="50%" align="left">
                                                    <font color="red"><span id = "err_launch_date" style = "display:none;">Enter Launch Date</span></font>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="20%" align="right" valign="top"><b>Sold Out Date  :</b> </td>
                                                <td width="30%" align="left">
                                                    <input name="sold_out_date" value="{$sold_out_date}" type="text" class="formstyle2" id="sold_out_date" readonly="1" size="10" />  <img src="../images/cal_1.jpg" id="sold_out_date_trigger" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
                                                     &nbsp;&nbsp;<img width="15" height="15" id="ref-sold-date" onclick="refrech_date('sold_out_date')" title="Refresh Sold Date" src="../images/refresh.png">
                                                </td>
                                                <td width="50%" align="left">
                                                    <font color="red"><span id = "err_launch_date" style = "display:none;">Enter Launch Date</span></font>
                                                </td>
                                            </tr>
                                        <input type = "hidden" name = "pre_launch_date" value="{$pre_launch_date}">
                                    

                                        <tr>
                                            <td width="20%" align="right" valign="top"><b><b><b>Phase Launched :</b> </td>
                                                        <td width="30%" align="left">
                                                            <input name = "isLaunchUnitPhase" id="isLaunchUnitPhase" type = "checkbox" value = "1" {if $isLaunchUnitPhase >= 1} checked {/if}>
                                                        </td>
                                                        <td width="50%" align="left"></td>
                                                        </tr>
                                                                                
                                                        <tr class="options_select" style="display: none">
                                                            <td width="20%" align="right" valign="top"><b><b><b>Select Options :</b> </td>
                                                                        <td width="30%" align="left">
                                                                            <select name="options[]" id="options" multiple="multiple" style="width: 236px; height: 210px;" disabled>
                                                                                <option value="-1" {if count($phase_options) <= 0}selected="selected"{/if}>Select Option</option>
                                                                                {foreach $options as $option}
                                                                                    <option {if in_array($option->options_id, $option_ids) && count($phase_options) > 0}selected="selected"{/if} value="{$option->options_id}">{$option->option_name} - {$option->size} sqft - {$option->option_type}</option>
                                                                                {/foreach}
                                                                            </select>
                                                                        </td>
                                                                        <td width="50%" align="left">
                                                                            <button class="reset_option_and_supply option_button">Change to supply</button>
                                                                            <br><br><strong>Select all options:</strong> <input type="checkbox" class="select_all_options">
                                                                        </td>
                                                                        </tr>
                                                                        {if $ProjectDetail[0]['PROJECT_TYPE_ID'] == $APARTMENTS || $ProjectDetail[0]['PROJECT_TYPE_ID']== $VILLA_APARTMENTS || $ProjectDetail[0]['PROJECT_TYPE_ID']== $PLOT_APARTMENTS}
                                                                            <tr class="supply_select">
                                                                                {if count($phase_quantity) == 0 || count($bedrooms_hash['Apartment'])>0}
                                                                                <td width="20%" align="right" valign="top"><b><b><b>Supply of Flats :{count($bedrooms_hash['Apartment'])}</b> </td>{/if}
                                                                                            <td width="50%" align="left">
                                                                                                <ul id="flats_config">
                                                                                                    {foreach $bedrooms_hash['Apartment'] as $num}
                                                                                                        <li class="flat_bed">
                                                                                                            <font color="red"><span class = "err_flat_bed" style = "display:none;">Integer expected</span>
                                                                                                            <br/></font>
                                                                                                            <label for="flat_bed_{$num}">{$num} Bedroom(s)</label>
                                                                                                            <input id="flat_bed_{$num}" name="flat_bed_{$num}[supply]" style="width: 50px;" value="{$FlatsQuantity[$num]['supply']}" />
                                                                                                                <label>Launched</label>
                                                                                                            <input id="flat_bed_{$num}" {if !$isLaunchUnitPhase}readonly="true"{/if} name="flat_bed_{$num}[launched]" class="launched" style="width: 50px;" value="{$FlatsQuantity[$num]['launched']}" />
                                                                                                            <select multiple="multiple" style="width: 150px; height: 110px;" disabled>
                                                                                                                {foreach $OptionsDetails as $option}
                                                                                                                    {if $option.BEDROOMS == $num and $option.OPTION_TYPE == 'Apartment' and in_array($option.OPTIONS_ID, $option_ids)}
                                                                                                                        <option value="{$option.OPTION_NAME}">{$option.OPTION_NAME}</option>
                                                                                                                    {/if}
                                                                                                                {/foreach}
                                                                                                            </select>
                                                                                                        </li>
                                                                                                    {/foreach}
                                                                                                </ul>
                                                                                            </td>
                                                                                            {if $phaseObject['PHASE_TYPE'] != 'Logical'}
                                                                                                <td width="50%" align="left">
                                                                                                    <button class="reset_option_and_supply supply_button">Change to options</button>
                                                                                                </td>
                                                                                            {/if}
                                                                                            </tr>
                                                                                               <!-- <tr {if $phaseObject['PHASE_TYPE'] == 'Logical'} style="display: none;" {/if}>
                                                                                                    <td width="20%" align="right" valign="top"><b><b><b>Select Towers :</b> </td>
                                                                                                        <td width="30%" align="left">
                                                                                                            <select name="towers[]" id="towers" multiple="multiple" style="width: 150px; height: 110px;">
                                                                                                                <option value="-1">Select Towers</option>
                                                                                                                {foreach $TowerDetails as $tower}
                                                                                                                    <option value="{$tower.TOWER_ID}" {if $tower.PHASE_ID eq $phaseId}selected{/if}>{$tower.TOWER_NAME}</option>
                                                                                                                {/foreach}
                                                                                                            </select>
                                                                                                        </td>
                                                                                                        <td width="50%" align="left"></td>
                                                                                                </tr> -->
                                                                                        {/if}

                                                                                                        {if $ProjectDetail[0]['PROJECT_TYPE_ID']==2 || $ProjectDetail[0]['PROJECT_TYPE_ID']==3 || $ProjectDetail[0]['PROJECT_TYPE_ID']==5}
                                                                                                            <tr class="supply_select">
                                                                                                               {if count($phase_quantity) == 0 || count($bedrooms_hash['Villa'])>0}
                                                                                                                <td width="20%" align="right" valign="top"><b><b><b>Supply of Villas :</b> </td>{/if}
                                                                                                                            <td width="30%" align="left">
                                                                                                                                <ul id="villa_config">
                                                                                                                                    {foreach $bedrooms_hash['Villa'] as $num}
                                                                                                                                        <li class="villa_bed">
                                                                                                                                            <font color="red"><span class = "err_villa_bed" style = "display:none;">Integer expected</span>
                                                                                                                                            <br/></font>
                                                                                                                                            <label for="villa_bed_{$num}">{$num} Bedroom(s)</label>
                                                                                                                                            <input id="villa_bed_{$num}" name="villa_bed_{$num}[supply]" style="width: 50px;" value="{$VillasQuantity[$num]['supply']}" />
                                                                                                                                            <label>Launched</label>
                                                                                                                                            <input id="villa_bed_{$num}" {if !$isLaunchUnitPhase}readonly="true"{/if} name="villa_bed_{$num}[launched]" class="launched" style="width: 50px;" value="{$VillasQuantity[$num]['launched']}" />
                                                                                                                                            <select multiple="multiple" style="width: 150px; height: 110px;" disabled>
                                                                                                                                                {foreach $OptionsDetails as $option}
                                                                                                                                                    {if $option.BEDROOMS == $num and $option.OPTION_TYPE == 'Villa' and in_array($option.OPTIONS_ID, $option_ids)}
                                                                                                                                                        <option value="{$option.OPTION_NAME}">{$option.OPTION_NAME}</option>
                                                                                                                                                    {/if}
                                                                                                                                                {/foreach}
                                                                                                                                            </select>
                                                                                                                                        </li>
                                                                                                                                    {/foreach}
                                                                                                                                </ul>
                                                                                                                            </td>
                                                                                                                            
                                                                                                                            {if $phaseObject['PHASE_TYPE'] != 'Logical'}
                                                                                                                            <td width="50%" align="left">
                                                                                                                                <button class="reset_option_and_supply supply_button">Change to options</button>
                                                                                                                            {/if}
                                                                                                                            </td>
                                                                                                                            </tr>
                                                                                                                        {/if}
                                                                                                                        {if $ProjectDetail[0]['PROJECT_TYPE_ID']==4 || $ProjectDetail[0]['PROJECT_TYPE_ID']==5 || $ProjectDetail[0]['PROJECT_TYPE_ID']==6}
                                                                                                                           {if count($bedrooms_hash['Plot'])>0}
                                                                                                                           
                                                                                                                               <tr class="supply_select">
                                                                                                                                <td width="20%" align="right" valign="top"><b>Supply of Plot :</b> </td>
                                                                                                                                <td width="30%" align="left" nowrap>
                                                                                                                                    <font color="red">
                                                                                                                                    <span class = "err_plotsupply" style = "display:none;">Integer expected<br/></span>
                                                                                                                                    <span id = "err_supply" style = "display:none;">Enter the supply for Plot<br/></span></font>
                                                                                                                                    <input type='text' name='supply' id='supply' value="{$PlotQuantity[0]['supply']}">
                                                                                                                                    <label>Launched</label>
                                                                                                                                    <input id="supply" {if !$isLaunchUnitPhase}readonly="true"{/if} name="launched" class="launched" style="width: 50px;" value="{$PlotQuantity[0]['launched']}" />
                                                                                                                                </td>
                                                                                                                                <td width="50%" align="left">
																																	
                                                                                                                                    
                                                                                                                                    {if $ProjectDetail[0]['PROJECT_TYPE_ID'] == 4 && $phaseObject['PHASE_TYPE'] != 'Logical'}
                                                                                                                                     <button class="reset_option_and_supply supply_button">Change to options</button>
                                                                                                                                     {/if}
                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                            
                                                                                                                            <input type='hidden' name='plotvilla' id='plotvilla' value='Plot'>
                                                                                                                            {/if}
                                                                                                                        {/if}  
                                                                                                                        
                                                                                                                        <!--code for shop office others-->
                                                                                                                        
                                                                                                                        {if $ProjectDetail[0]['PROJECT_TYPE_ID']==$SHOP}
                                                                                                                           {if count($bedrooms_hash['Shop'])>0}
                                                                                                                               {$showHide = ""}
                                                                                                                           {else}
                                                                                                                               {$showHide = "style = 'display:none;'"}
                                                                                                                           {/if}
                                                                                                                               <tr {$showHide} class="supply_select">
                                                                                                                                <td width="20%" align="right" valign="top"><b>Supply of Shop  :</b> </td>
                                                                                                                                <td width="30%" align="left" nowrap>
                                                                                                                                    <font color="red">
                                                                                                                                    <span class = "err_shopsupply" style = "display:none;">Integer expected<br/></span>
                                                                                                                                    <span id = "err_supply" style = "display:none;">Enter the supply for Shop<br/></span></font>
                                                                                                                                    <input type='text' name='supply_shop' id='supply_shop' value="{$ShopQuantity[0]['supply']}">
                                                                                                                                    <label>Launched</label>
                                                                                                                                    <input id="supply_shop" {if !$isLaunchUnitPhase}readonly="true"{/if} name="launched_shop" class="launched" style="width: 50px;" value="{$ShopQuantity[0]['launched']}" />
                                                                                                                                </td>
                                                                                                                                <td width="50%" align="left">
																																	
                                                                                                                                    
                                                                                                                                    {if $ProjectDetail[0]['PROJECT_TYPE_ID'] == $SHOP && $phaseObject['PHASE_TYPE'] != 'Logical'}
                                                                                                                                     <button class="reset_option_and_supply supply_button">Change to options</button>
                                                                                                                                     {/if}
                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                            
                                                                                                                            <input type='hidden' name='Shop' id='Shop' value='Shop'>
                                                                                                                        {/if}
                                                                                                                        <!--end code for shop office and other-->
                                                                                                                        
                                                                                                                        <!--code for shop office others-->
                                                                                                                        {if $ProjectDetail[0]['PROJECT_TYPE_ID']==$OFFICE}
                                                                                                                           {if count($bedrooms_hash['Office'])>0}
                                                                                                                               {$showHide = ""}
                                                                                                                           {else}
                                                                                                                               {$showHide = "style = 'display:none;'"}
                                                                                                                           {/if}
                                                                                                                               <tr {$showHide} class="supply_select">
                                                                                                                                <td width="20%" align="right" valign="top"><b>Supply of Office  :</b> </td>
                                                                                                                                <td width="30%" align="left" nowrap>
                                                                                                                                    <font color="red">
                                                                                                                                    <span class = "err_officesupply" style = "display:none;">Integer expected<br/></span>
                                                                                                                                    <span id = "err_supply" style = "display:none;">Enter the supply for Office<br/></span></font>
                                                                                                                                    <input type='text' name='supply_office' id='supply_office' value="{$OfficeQuantity[0]['supply']}">
                                                                                                                                    <label>Launched</label>
                                                                                                                                    <input id="supply_office" {if !$isLaunchUnitPhase}readonly="true"{/if} name="launched_office" class="launched" style="width: 50px;" value="{$OfficeQuantity[0]['launched']}" />
                                                                                                                                </td>
                                                                                                                                <td width="50%" align="left">
																																	
                                                                                                                                    
                                                                                                                                    {if $ProjectDetail[0]['PROJECT_TYPE_ID'] == $OFFICE && $phaseObject['PHASE_TYPE'] != 'Logical'}
                                                                                                                                     <button class="reset_option_and_supply supply_button">Change to options</button>
                                                                                                                                     {/if}
                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                            
                                                                                                                            <input type='hidden' name='Office' id='Office' value='Office'>
                                                                                                                        {/if}
                                                                                                                        <!--end code for shop office and other-->
                                                                                                                        
                                                                                                                        <!--code for shop office others-->
                                                                                                                        {if $ProjectDetail[0]['PROJECT_TYPE_ID']==$SHOP_OFFICE}
                                                                                                                           {if count($bedrooms_hash['Shop'])>0}
                                                                                                                               {$showHide = ""}
                                                                                                                           {else}
                                                                                                                               {$showHide = "style = 'display:none;'"}
                                                                                                                           {/if}
                                                                                                                               <tr {$showHide} class="supply_select">
                                                                                                                                <td width="20%" align="right" valign="top"><b>Supply of Shop  :</b> </td>
                                                                                                                                <td width="30%" align="left" nowrap>
                                                                                                                                    <font color="red">
                                                                                                                                    <span class = "err_shopsupply" style = "display:none;">Integer expected<br/></span>
                                                                                                                                    <span id = "err_supply" style = "display:none;">Enter the supply for Shop<br/></span></font>
                                                                                                                                    <input type='text' name='supply_shop' id='supply_shop' value="{$ShopQuantity[0]['supply']}">
                                                                                                                                    <label>Launched</label>
                                                                                                                                    <input id="supply_shop" {if !$isLaunchUnitPhase}readonly="true"{/if} name="launched_shop" class="launched" style="width: 50px;" value="{$ShopQuantity[0]['launched']}" />
                                                                                                                                </td>
                                                                                                                                <td width="50%" align="left">
																																	
                                                                                                                                    
                                                                                                                                    {if $ProjectDetail[0]['PROJECT_TYPE_ID'] == $SHOP_OFFICE && $phaseObject['PHASE_TYPE'] != 'Logical'}
                                                                                                                                     <button class="reset_option_and_supply supply_button">Change to options </button>
                                                                                                                                     {/if}
                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                            
                                                                                                                            <input type='hidden' name='Shop' id='Office' value='Shop'>
                                                                                                                            
                                                                                                                            {if count($bedrooms_hash['Office'])>0}
                                                                                                                               {$showHide = ""}
                                                                                                                           {else}
                                                                                                                               {$showHide = "style = 'display:none;'"}
                                                                                                                           {/if}
                                                                                                                               <tr {$showHide} class="supply_select">
                                                                                                                                <td width="20%" align="right" valign="top"><b>Supply of Office  :</b> </td>
                                                                                                                                <td width="30%" align="left" nowrap>
                                                                                                                                    <font color="red">
                                                                                                                                    <span class = "err_officesupply" style = "display:none;">Integer expected<br/></span>
                                                                                                                                    <span id = "err_supply" style = "display:none;">Enter the supply for Office<br/></span></font>
                                                                                                                                    <input type='text' name='supply_office' id='supply_office' value="{$OfficeQuantity[0]['supply']}">
                                                                                                                                    <label>Launched</label>
                                                                                                                                    <input id="supply_office" {if !$isLaunchUnitPhase}readonly="true"{/if} name="launched_office" class="launched" style="width: 50px;" value="{$OfficeQuantity[0]['launched']}" />
                                                                                                                                </td>
                                                                                                                                <td width="50%" align="left">
																																	
                                                                                                                                    
                                                                                                                                    {if $ProjectDetail[0]['PROJECT_TYPE_ID'] == $SHOP_OFFICE && $phaseObject['PHASE_TYPE'] != 'Logical'}
                                                                                                                                     <button class="reset_option_and_supply supply_button">Change to options</button>
                                                                                                                                     {/if}
                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                            
                                                                                                                            <input type='hidden' name='Office' id='Office' value='Office'>
                                                                                                                        {/if}
                                                                                                                        <!--end code for shop office and other-->
                                                                                                                        
                                                                                                                        <!--code for shop office others-->
                                                                                                                        {if $ProjectDetail[0]['PROJECT_TYPE_ID']==$OTHER}
                                                                                                                           {if count($bedrooms_hash['Other'])>0}
                                                                                                                               {$showHide = ""}
                                                                                                                           {else}
                                                                                                                               {$showHide = "style = 'display:none;'"}
                                                                                                                           {/if}
                                                                                                                               <tr {$showHide} class="supply_select">
                                                                                                                                <td width="20%" align="right" valign="top"><b>Supply of Other  :</b> </td>
                                                                                                                                <td width="30%" align="left" nowrap>
                                                                                                                                <font color="red">
                                                                                                                                        <span class = "err_plotsupply" style = "display:none;">Integer expected<br/></span>
                                                                                                                                        <span id = "err_supply" style = "display:none;">Enter the supply for Other<br/></span></font>
                                                                                                                                    <input type='text' name='supply' id='supply' value="{$OtherQuantity[0]['supply']}">
                                                                                                                                    <label>Launched</label>
                                                                                                                                    <input id="supply" {if !$isLaunchUnitPhase}readonly="true"{/if} name="launched" class="launched" style="width: 50px;" value="{$OtherQuantity[0]['launched']}" />
                                                                                                                                </td>
                                                                                                                                <td width="50%" align="left">
																																	
                                                                                                                                    
                                                                                                                                    {if $ProjectDetail[0]['PROJECT_TYPE_ID'] == $OTHER && $phaseObject['PHASE_TYPE'] != 'Logical'}
                                                                                                                                     <button class="reset_option_and_supply supply_button">Change to options</button>
                                                                                                                                     {/if}
                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                            
                                                                                                                            <input type='hidden' name='Other' id='Other' value='Other'>
                                                                                                                        {/if}
                                                                                                                        <!--end code for shop office and other-->
                                                                                                                        {if count($phase_quantity) == 0 || count($bedrooms_hash['Apartment'])>0}
																															<tr {if $phaseObject['PHASE_TYPE'] == 'Logical'} style="display: none;" {/if}>
																																<td width="20%" align="right" valign="top"><b><b><b>Select Towers :</b> </td>
																																	<td width="30%" align="left">
																																		<select name="towers[]" id="towers" multiple="multiple" style="width: 150px; height: 110px;">
																																			<option value="-1">Select Towers</option>
																																			{foreach $TowerDetails as $tower}
																																				<option value="{$tower.TOWER_ID}" {if $tower.PHASE_ID eq $phaseId}selected{/if}>{$tower.TOWER_NAME}</option>
																																			{/foreach}
																																		</select>
																																	</td>
																																	<td width="50%" align="left"></td>
																															</tr>
																													{/if}
                                                                                                                        {if $phaseId != '0'}
                                                                                                                        <tr>
                                                                                                                            <td width="20%" align="right" valign="top"><b><b><b>Remarks :</b> </td>
                                                                                                                                        <td width="30%" align="left">
                                                                                                                                            <textarea name="remark" rows="10" cols="30" id="remark">{$remark}</textarea>
                                                                                                                                        </td>
                                                                                                                                        <td width="50%" align="left"></td>
                                                                                                                                        </tr>
                                                                                                                                        {/if}

                                                                                                                                        <tr>
                                                                                                                                            <td>&nbsp;</td>

                                                                                                                                            <td align="left" style="padding-left:0px;">
                                                                                                                                                <input type = "hidden" name = "completion_date" value="{$completion_date}">
                                                                                                                                                <input type="submit" name="btnSave" id="btnSave" value="Submit" onclick="return validate_phase();" />
                                                                                                                                                
                                                                                                                                                {if $specialAccess == 1 && $phaseObject.PHASE_TYPE != 'Logical'}
                                                                                                                                                    &nbsp;&nbsp;<input type="submit" name="delete" value="Delete" onclick = "return deletePhase();" />
                                                                                                                                                {/if}
                                                                                                                                                &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit" />
                                                                                                                                            </td>
                                                                                                                                        </tr>
                                                                                                                                    {else}
                                                                                                                                        <tr>
                                                                                                                                            <td>&nbsp;</td>
                                                                                                                                            <td align="left" style="padding-left:0px;">
                                                                                                                                                &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit" />
                                                                                                                                            </td>
                                                                                                                                        </tr>
                                                                                                                                    {/if}
                                                                                                                                    </form>
                                                                                                                                    </table>
                                                                                                                                    </td>
                                                                                                                                    </tr>

                                                                                                                                    </table>

<script type="text/javascript">                                                                                                                                    {if isset($phaseId) and !in_array($phaseId, array('-1', '0'))}
                                                                                                                             
        var cals_dict = {
            "launch_date_trigger": "launch_date",
            "completion_date_trigger": "completion_date",
            "pre_f_trigger_c_to" : "pre_f_date_c_to",
            "sold_out_date_trigger": "sold_out_date",
        };

        $.each(cals_dict, function(k, v) {
            if ($('#' + k).length > 0) {
                Calendar.setup({
                    inputField: v, // id of the input field
                    //    ifFormat       :    "%Y/%m/%d %l:%M %P",         // format of the input field
                    ifFormat: "%Y-%m-%d", // format of the input field
                    button: k, // trigger for the calendar (button ID)
                    align: "Tl", // alignment (defaults to "Bl")
                    singleClick: true,
                    showsTime: true
                });
            }
        });
      {/if}
    jQuery(document).ready(function(){
            jQuery(".pt_click").live('click',function(){
                var projectId = $('#projectId').val();
                var phaseId = $('#phaseSelect').val();
                var title =  jQuery(this).attr('title');
                if(title=='Update Completion Date'){
                    jQuery(this).attr('href','javascript:void(0)');
                    window.open('add_project_construction.php?projectId='+projectId+"&phaseId="+phaseId,'AddProjectConstruction',
                    'height=400,width=1000,scrollbars=yes,toolbar=no,left=150,right=150,resizable=1,top=50');
                }
            });
    });
 </script>
