<div class="block_in_wrapper">
{if $add_enabled}
<h2>{#addproject#}</h2>

	<form class="main" method="post" action="admin.php?action=addpro" {literal}onsubmit="return validateCompleteForm(this);"{/literal} >
	<fieldset>
		<div class="row"><label for="name">{#name#}:</label><input type="text" class="text" name="name" id="name" required="1" realname="{#name#}" /></div>
		<div class="row"><label for="desc">{#description#}:</label><div class="editor"><textarea name="desc" id="desc"  rows="3" cols="1" ></textarea></div></div>

	    <div class="clear_both_b"></div>
        
        <div class="row">
        <label for="start">{#startdate#}:</label>
        <input type="text" class="text" name="start"  id="start"  realname="{#startdate#}" required="1" regexp="{literal}\d{2}.\d{2}.\d{4}{/literal}" />
        </div>
	    
	    <div class="datepick">
            <div id = "add_project_start" class="picker" style = "display:none;"></div>
        </div>
        	
		<div class="row">
		<label for="end">{#finishdate#}:</label>
		<input type="text" class="text" name="end"  id="end"  realname="{#finishdate#}" required="1" regexp="{literal}\d{2}.\d{2}.\d{4}{/literal}" />
		<br /><br />
		</div>

		<div class="datepick">
			<div id = "add_project_end" class="picker" style = "display:none;"></div>
		</div>

		<script type="text/javascript">
		  	theCal = new calendar({$theM},{$theY});
			theCal.dayNames = ["{#monday#}","{#tuesday#}","{#wednesday#}","{#thursday#}","{#friday#}","{#saturday#}","{#sunday#}"];
			theCal.monthNames = ["{#january#}","{#february#}","{#march#}","{#april#}","{#may#}","{#june#}","{#july#}","{#august#}","{#september#}","{#october#}","{#november#}","{#december#}"];
			theCal.relateTo = "end";
			theCal.dateFormat = "{$settings.dateformat}";
			theCal.getDatepicker("add_project_end");

	        theCal2 = new calendar({$theM},{$theY});
            theCal2.dayNames = ["{#monday#}","{#tuesday#}","{#wednesday#}","{#thursday#}","{#friday#}","{#saturday#}","{#sunday#}"];
            theCal2.monthNames = ["{#january#}","{#february#}","{#march#}","{#april#}","{#may#}","{#june#}","{#july#}","{#august#}","{#september#}","{#october#}","{#november#}","{#december#}"];
            theCal2.relateTo = "start";
            theCal2.dateFormat = "{$settings.dateformat}";
            theCal2.getDatepicker("add_project_start");
		</script>

		<div class = "row">
		<label for = "budget">{#planeffort#}:</label>
		<input type = "text" class="text" name = "budget" id = "budget" />
		</div>

        <div class = "row">
        <label for = "accesskey">{#accesskey#}:</label>
        <input type = "text" class="text" name = "accesskey" id = "accesskey" value="{$accesskey}" readonly="readonly" style="background-color: #ddd;" />
        </div>

		<div class="row"><label>{#members#}:</label>
		<div style="float:left;">
        {section name=user loop=$users}
	        <div class="row">
	        <input type="checkbox" class="checkbox" value="{$users[user].ID}" name="assignto[]" id="{$users[user].ID}"  {if $users[user].ID == $userid} checked="checked"{/if} /><label for="{$users[user].ID}">{$users[user].name}</label><br />
	      	</div>
	    {/section}
	    </div>
		</div>

		<input type="hidden" name="assignme" value="1" />

	    <div class="clear_both_b"></div>

		<div class="row-butn-bottom">
		<label>&nbsp;</label>
		<button type="submit" onfocus="this.blur();">{#addbutton#}</button>
		{if $myprojects == "1"}
		<button onclick="blindtoggle('form_addmyproject');toggleClass('add_myprojects','add-active','add');toggleClass('add_butn_myprojects','butn_link_active','butn_link');toggleClass('sm_myprojects','smooth','nosmooth');return false;" onfocus="this.blur();">{#cancel#}</button>
		{else}
		<button onclick="blindtoggle('form_{$myprojects[project].ID}');return false;">{#cancel#}</button>
		{/if}
		</div>


	</fieldset>
	</form>
{else}
You are out of limit for adding new projects.
{/if}
</div> {*block_in_wrapper end*}