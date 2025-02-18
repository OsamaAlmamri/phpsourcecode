{if $showhtml != "no"}
{include file="header.tpl" jsload="ajax" jsload1="tinymce"}

{include file="tabsmenue-project.tpl" milestab = "active"}
<div id="content-left">
<div id="content-left-in">
<div class="miles">


<div class="breadcrumb">
<a href="manageproject.php?action=showproject&amp;id={$project.ID}"><img src="./templates/standard/images/symbols/projects.png" alt="" />{$projectname|truncate:40:"...":true}</a>
<a href="managemilestone.php?action=showproject&amp;id={$project.ID}"><img src="./templates/standard/images/symbols/miles.png" alt="" />{#milestones#}</a>
</div>

<h1>{#editmilestone#}</h1>

{/if}

			<div class="block_in_wrapper">

				{if $showhtml == "no"}
				<h2>{#editmilestone#}</h2>
				{else}
				<h2>&nbsp;</h2>
				{/if}
					
					<form class="main" method="post" action="managemilestone.php?action=edit&amp;id={$milestone.project}" {literal}onsubmit="return validateCompleteForm(this,'input_error');"{/literal}>
					<fieldset>

					<div class="row">
						<label for="name">{#name#}:</label><input type="text" value="{$milestone.name}" name="name" id="name" required="1" realname="{#name#}" />
						<br/>
					</div>
					
					<div class = "row">
						<label for="desc">{#description#}:</label><textarea name="desc" id="desc" realname="{#description#}" rows="3" cols="1" >{$milestone.desc}</textarea>
					</div>

					<div class="row">
						<label for="external">{#external#}:</label>
						<input type="checkbox" class="checkbox" name="external"  id="external" realname="{#external#}"{if $milestone.external} checked="checked"{/if} />
					</div>
					
					<div class="row">
						<label for="end">{#end#}:</label><input type="text" value="{$milestone.endstring}" name="end"  id="end" required="1" realname="{#end#}" />
					</div>

					<div class="datepick">
						<div id = "datepicker_mile" class="picker" style = "display:none;"></div>
					</div>
				
					<script type="text/javascript">
					  	theCal{$lists[list].ID} = new calendar({$theM},{$theY});
						theCal{$lists[list].ID}.dayNames = ["{#monday#}","{#tuesday#}","{#wednesday#}","{#thursday#}","{#friday#}","{#saturday#}","{#sunday#}"];
						theCal{$lists[list].ID}.monthNames = ["{#january#}","{#february#}","{#march#}","{#april#}","{#may#}","{#june#}","{#july#}","{#august#}","{#september#}","{#october#}","{#november#}","{#december#}"];
						theCal{$lists[list].ID}.relateTo = "end";
						theCal{$lists[list].ID}.getDatepicker("datepicker_mile");
					</script>

					<input type="hidden" name="mid" value="{$milestone.ID}" />


					<div class="row-butn-bottom">
						<label>&nbsp;</label>
						<button type="submit" onfocus="this.blur();">{#send#}</button>
						{if $showhtml == "no"}
						<button onclick="blindtoggle('form_edit');toggleClass('edit_butn','edit-active','edit');toggleClass('sm_mile','smooth','nosmooth');return false;" onfocus="this.blur();" {if $showhead == "1"} style="display:none;"{/if}>{#cancel#}</button>
						{/if}
					</div>


					</fieldset>
					</form>
					</div> {*block_in_wrapper end*}


{if $showhtml != "no"}
<div class="content-spacer"></div>
</div> {*Miles END*}
</div> {*content-left-in END*}
</div> {*content-left END*}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}

{/if}