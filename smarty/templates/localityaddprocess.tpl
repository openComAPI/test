<script type="text/javascript" src="js/jquery.js"></script>
</TD>
  </TR>
  <TR>
    <TD class="white-bg paddingright10" vAlign=top align=middle bgColor=#ffffff>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0><TBODY>
        <TR>
          <TD width=224 height=25>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=866>&nbsp;</TD>
	</TR>
        <TR>
          <TD class=paddingltrt10 vAlign=top align=middle bgColor=#ffffff>
	   		{include file="{$PROJECT_ADD_TEMPLATE_PATH}left.tpl"}
	  </TD>
          <TD class=border-all vAlign=center align=middle width=10 bgColor=#f7f7f7>&nbsp;</TD>
          <TD class=border-rt vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
              <TR>
                <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>Edit Locality</TD>
                      <TD align=right ></TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
                {if $accessLocality == ''}
			  <TABLE cellSpacing=2 cellPadding=4 width="93%" align=center border=0>
			    <form method="post" enctype="multipart/form-data" id="frmcity" name="frmcity">
			      <div>
				<tr>
				  <td width="20%" align="right" >*Locality Name : </td>
				  <td width="30%" align="left"><input type=text name=txtCityName id=txtCityName value="{$txtCityName}" style="width:250px;"></td> {if $ErrorMsg["txtCityName"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtCityName"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
				</tr>				
				<tr>
				  <td width="20%" align="right" >* Meta Title : </td>
				  <td width="30%" align="left" ><input type=text name=txtMetaTitle id=txtMetaTitle value="{$txtMetaTitle}" style="width:250px;"></td>				   {if $ErrorMsg["txtMetaTitle"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtMetaTitle"]}</font></td>{else} <td width="50%" align="left" id="errmsgmetatitle"></td>{/if}
				</tr>				<tr>
				  <td width="20%" align="right" valign="top">*Meta Keywords :</td>
				  <td width="30%" align="left" >
				  <textarea name="txtMetaKeywords" rows="10" cols="35" id="txtMetaKeywords" style="width:250px;">{$txtMetaKeywords}</textarea>
                  </td>{if $ErrorMsg["txtMetaKeywords"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtMetaKeywords"]}</font></td>{else} <td width="50%" align="left" id="errmsgmetakey"></td>{/if}
				</tr>				<tr>
				  <td width="20%" align="right" valign="top">*Meta Description :</td>
				  <td width="30%" align="left" >
				  <textarea name="txtMetaDescription" rows="10" cols="35" id="txtMetaDescription" style="width:250px;">{$txtMetaDescription}</textarea>
                  </td>{if $ErrorMsg["txtMetaDescription"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtMetaDescription"]}</font></td>{else} <td width="50%" align="left" id="errmsgmetades"></td>{/if}
				</tr>									
				<tr>
				  <td width="20%" align="right" valign = top >*Description  : </td>
				  <td width="30%" align="left" ><textarea name = 'desc' id = 'desc' cols = "35" rows = "10" style="width:250px;">{$desc}</textarea></td>
				</tr>
				<tr>
				  <td width="20%" align="right">*Status  : </td>
				  <td width="30%" align="left" >
				    <select name = "status" id="status" style="width:150px;"> 
					  <option {if $status == '0'} value = "0" selected = 'selected' {else} value = "0" {/if}>Inactive</option>					<option {if $status == '1'} value = "1" selected = 'selected' {else} value = "1" {/if}>Active</option>		
					 </select>
				 </td>				   
				 <td width="50%" align="left"></td>
				</tr>
                                
				<tr>
				  <td width="20%" align="right">Visible In CMS :</td>
				  <td width="30%" align="left" >
                                  {if $specialAccess == 1}
				    <select name = "visibleInCms" id="visibleInCms" style="width:150px;"> 
                                        <option value = "0" {if $visibleInCms == '0'}  selected {/if}>Not Visible</option>
                                        <option value = "1" {if $visibleInCms == '1'}  selected {/if}>Visible</option>		
                                    </select>
                                  {else}
                                      <input type="hidden" name = "visibleInCms" value="{$visibleInCms}">
                                      {if $visibleInCms == '0'}  Not Visible {/if}
                                      {if $visibleInCms == '1'}  Visible {/if}
                                  {/if}
				 </td>				   
				 <td width="50%" align="left"></td>
				</tr>
                                {if $localityCleanedAccess == 1}
                                <tr>
				  <td width="20%" align="right">Locality Cleaned  : </td>
				  <td width="30%" align="left" >
                                      <input type = "button" name ="localityCleaned" onclick = "cleanedLocality({$localityid});" value="Click To Save">
				 </td>				   
				 <td width="50%" align="left">&nbsp;</td>
				</tr>
                                <tr class="latLong">
                                  <td width="20%" align="right">Max Latitude  : </td>
                                  <td width="30%" align="left" >
                                      {$maxLatitude}
                                      <input type = "hidden" name ="maxLatitude" value="{$maxLatitude}">
                                 </td>				   
                                 <td width="50%" align="left">&nbsp;</td>
                                </tr>

                                <tr class="latLong">
                                  <td width="20%" align="right">Min Latitude  : </td>
                                  <td width="30%" align="left" >
                                      {$minLatitude}
                                      <input type = "hidden" name ="minLatitude" value="{$minLatitude}">
                                 </td>				   
                                 <td width="50%" align="left">&nbsp;</td>
                                </tr>

                                <tr class="latLong">
                                  <td width="20%" align="right">Max Longitude  : </td>
                                  <td width="30%" align="left" >
                                      {$maxLongitude}
                                      <input type = "hidden" name ="maxLongitude" value="{$maxLongitude}">
                                 </td>				   
                                 <td width="50%" align="left">&nbsp;</td>
                                </tr>

                                 <tr class="latLong">
                                  <td width="20%" align="right">Min Longitude  : </td>
                                  <td width="30%" align="left" >
                                      {$minLongitude}
                                      <input type = "hidden" name ="minLongitude" value="{$minLongitude}">
                                 </td>				   
                                 <td width="50%" align="left">&nbsp;</td>
                                </tr>
                                {/if}
                                <tr class="save_row">
				  <td >&nbsp;</td>
				  <td align="left" style="padding-left:50px;" >
				  <input type="submit" name="btnSave" id="btnSave" value="Save" style="cursor:pointer">
				  &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit" style="cursor:pointer">
				  </td>
				</tr>
			    </form>
			    </TABLE>
<!--			</fieldset>-->
	            </td>
		  </tr>
		</TABLE>
	      </TD>
              {else}
                    <font color="red">No Access</font>
                {/if}
            </TR>
          </TBODY></TABLE>
        </TD>
      </TR>
    </TBODY></TABLE>
  </TD>
</TR>
<script type="text/javascript">

jQuery(document).ready(function(){

	jQuery("#btnSave").click(function(){
	
		var cityname = jQuery("#txtCityName").val();
		var CityUrl = jQuery("#txtCityUrl").val();
		var txtMetaTitle = jQuery("#txtMetaTitle").val();
		var MetaKeywords = jQuery("#txtMetaKeywords").val();
		var MetaDescription = jQuery("#txtMetaDescription").val();
		var status = jQuery("#status").val();
		var desc = jQuery("#desc").val();
		
		if(cityname==''){
		
			jQuery('#errmsgname').html('<font color="red">Please enter Suburb name</font>');
			jQuery("#txtCityName").focus();
			return false;
		}else		{
			jQuery('#errmsgname').html('');
		}
		
		if(CityUrl==''){
		
			jQuery('#errmsgurl').html('<font color="red">Please enter Suburb url</font>');
			jQuery("#txtCityUrl").focus();
			return false;
		}else{
			jQuery('#errmsgurl').html('');
		
		}
		
		if(txtMetaTitle==''){
		
			jQuery('#errmsgmetatitle').html('<font color="red">Please enter meta title</font>');
			jQuery("#txtMetaTitle").focus();
			return false;
		}else{
			jQuery('#errmsgmetatitle').html('');
		}
		
		if(MetaKeywords==''){
		
			jQuery('#errmsgmetakey').html('<font color="red">Please enter meta keywords</font>');
			jQuery("#txtMetaKeywords").focus();
			return false;
		}else{
		
			jQuery('#errmsgmetakey').html('');
		}
		
		if(MetaDescription==''){
		
			jQuery('#errmsgmetades').html('<font color="red">Please enter meta description</font>');
			jQuery("#txtMetaDescription").focus();
			return false;
		}else{
			jQuery('#errmsgmetades').html('');
		}

		
	});

});

function cleanedLocality(localityId) {

$.ajax({
         type: "POST",
         url: 'ajax/cleanedLocality.php',
         data: { localityId:localityId },
         success:function(msg){
           if(msg){
             if(msg.length > 823) {
                alert("New locality boundaries have been saved");
             }
             else{
                alert("No valid record in project table");
             }
              $(".latLong").remove();
              $(msg).insertBefore($('.save_row'));
            }
         }
     })
}

</script>