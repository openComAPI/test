<script type="text/javascript" src="fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

<script language="javascript">
function chkConfirm() 
{
    return confirm("Are you sure! you want to delete this record.");
}
function selectCity(value){
    document.getElementById('frmcity').submit();
	window.location.href="{$dirname}/loc_sub_priority.php?page=1&sort=all&citydd="+value;
}
function openLocSubPriorityAdd()
{
    $.fancybox({
        'width'                :720,
        'height'               :200,
        'scrolling'            : 'yes',
        'href'                 : '/setLocSubPriority.php?cityId={$cityId}',
        'type'                : 'iframe'
    })
}

function openLocSubPriorityEdit(id,type)
{
    $.fancybox({
        'width'                :720,
        'height'               :200,
        'scrolling'            : 'yes',
        'href'                 : '/setLocSubPriority.php?cityId={$cityId}&type='+type+'&id='+id+'&mode=edit',
        'type'                : 'iframe'
    })
}

function openLocSubPriorityDelete(id,type)
{
    var r=confirm("Are you sure you want to delete");
    if (r==true)
    {
        $.ajax({
          type: "POST",
          url: '/deletePriority.php',
          data: { id : id,type:type },
          success:function(msg){
            if(msg == 1){
                 alert("Priority Successfully deleted");
                 window.location.reload(true); 
             }
          }
      })
    }
    else
    {
        alert("OK");
    } 
    
}
function show_loc_inst(){
    window.open("images/instruction_locality.png","_blank",'width=300,height=500');
}
</script>
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
          <TD vAlign=center align=middle width=10 bgColor=#f7f7f7>&nbsp;</TD>
          <TD vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
              <TR>
                <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Area Priority</TD>
                      
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
                  <table width="93%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>
                        <table width="70%" border="0" cellpadding="0" cellspacing="0" align="center">
                          <tr>
                            <td width="40%" height="25" align="center" valign="top">
                                <form name="frmcity" id="frmcity" method="post">
                                    <select id="citydd" name="citydd" onchange="selectCity(this.value)">
                                       <option>select</option>
                                       {foreach from=$cityArray key=k item=v}
                                           <option value="{$v.CITY_ID}" {if $cityId=={$v.CITY_ID}}  selected="selected" {/if}>{$v.LABEL}</option>
                                       {/foreach}
                                    </select>
                               </form>
                            </td>
                            <td width="25%" height="25" align="left" valign="top"><input type="button" name="add" value="Add Locality/Suburb Priority" onclick="return openLocSubPriorityAdd();" /></td>
                            <td width="15%" height="25" align="left" valign="top"><img src="images/1376493783_help.png" onclick="show_loc_inst();" border="0" style="cursor: pointer;"/></td>
                            
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                    <TABLE cellSpacing=1 cellPadding=4 width="50%" align=center border=0>
                    <form name="form1" method="post" action="">
                      <TBODY>
                      <TR class = "headingrowcolor">
                        <TD class=whiteTxt width=2% align="center">S.No.</TD>
                        <TD class=whiteTxt width=5% align="center">Name</TD>
                        <TD class=whiteTxt width=2% align="center">Id</TD>
                        <TD class=whiteTxt width=2% align="center">&nbsp;</TD>
                        <TD class=whiteTxt width=5% align="center">Priority
                        {if $smarty.post.desc_x!='' || (!isset($smarty.post))}
                            <span style="clear:both;margin-left:10px"><input type="image" name="asc" value="asc" src="images/arrow-up.png" width="16"></span>
                        {else}
                            <span style="clear:both;margin-left:10px"><input type="image" name="desc" value="desc" src="images/arrow-down.png"></span>
                        {/if}
                        </TD> 
                        <TD class=whiteTxt width=5% align="center">&nbsp;</TD>
                      </TR>
                      <TR><TD colspan=12 class=td-border>&nbsp;</TD></TR>
                      {$i=0}
                      {foreach from=$arraySubLoc key=k item=v}
                          {$i=$i+1}
                          {if $i%2 == 0}
                            {$color = "bgcolor = '#F7F7F7'"}
                          {else}                       			
                            {$color = "bgcolor = '#FCFCFC'"}
                          {/if}
                          
                          {if isset($v.LOCALITY_ID)}
                              {$type = 'LOCALITY'}
                          {else}
                              {$type = 'SUBURB'}
                          {/if}
                            <TR {$color}>
                              <TD align=center class=td-border>{$i}</TD>
                              <TD align=center class=td-border>{$v.LABEL}</TD>
                              <TD align=center class=td-border>{$v.ID}</TD>
                              <TD align=center class=td-border>{$type}</TD>
                              <TD align=center class=td-border>{$v.PRIORITY}</TD>
                              <TD align=center class=td-border><a href="javascript:void(0);" onclick="return openLocSubPriorityEdit('{$v.ID}','{$type}');">Edit</a> | <a href="javascript:void(0);" onclick="return openLocSubPriorityDelete('{$v.ID}','{$type}');">Reset</a></TD>
                            </TR>
                      {/foreach}
                      <TR><TD colspan="9" class="td-border" align="right">&nbsp;</TD></TR>
                     
                      </TBODY>
                    </FORM>
                    </TABLE>
 
	      </TD>
            </TR>
          </TBODY></TABLE>
        </TD>
      </TR>
    </TBODY></TABLE>
  </TD>
</TR>
<TR>
 
</TR>