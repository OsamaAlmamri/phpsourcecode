{**
 * MILEBIZ 米乐商城
 * ============================================================================
 * 版权所有 2011-20__ 米乐网。
 * 网站地址: http://www.milebiz.com
 * ============================================================================
 * $Author: zhourh $
 *}

<script type="text/javascript">
// <![CDATA[
idSelectedCountry = {if isset($smarty.post.id_state)}{$smarty.post.id_state|intval}{else}{if isset($address->id_state)}{$address->id_state|intval}{else}false{/if}{/if};
countries = new Array();
countriesNeedIDNumber = new Array();
countriesNeedZipCode = new Array();
{foreach from=$countries item='country'}
	{if isset($country.states) && $country.contains_states}
		countries[{$country.id_country|intval}] = new Array();
		{foreach from=$country.states item='state' name='states'}
			countries[{$country.id_country|intval}].push({ldelim}'id' : '{$state.id_state}', 'name' : '{$state.name|addslashes}'{rdelim});
		{/foreach}
	{/if}
	{if $country.need_identification_number}
		countriesNeedIDNumber.push({$country.id_country|intval});
	{/if}
	{if isset($country.need_zip_code)}
		countriesNeedZipCode[{$country.id_country|intval}] = {$country.need_zip_code};
	{/if}
{/foreach}
$(function(){ldelim}
	$('.id_state option[value={if isset($smarty.post.id_state)}{$smarty.post.id_state|intval}{else}{if isset($address->id_state)}{$address->id_state|intval}{/if}{/if}]').attr('selected', true);
{rdelim});
{if $vat_management}
{literal}
	$(document).ready(function() {
		$('#company').blur(function(){
			vat_number();
		});
		vat_number();
		function vat_number()
		{
			if ($('#company').val() != '')
				$('#vat_number').show();
			else
				$('#vat_number').hide();
		}
	});
{/literal}
{/if}
//]]>
</script>

{capture name=path}{l s='Your addresses'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}

<h1>{l s='Your addresses'}</h1>

<h3>
{if isset($id_address) && (isset($smarty.post.alias) || isset($address->alias))}
	{l s='Modify address'} 
	{if isset($smarty.post.alias)}
		"{$smarty.post.alias}"
	{else}
		{if isset($address->alias)}"{$address->alias}"{/if}
	{/if}
{else}
	{l s='To add a new address, please fill out the form below.'}
{/if}
</h3>

{include file="$tpl_dir./errors.tpl"}

<p class="required"><sup>*</sup> {l s='Required field'}</p>

<form action="{$link->getPageLink('address', true)}" method="post" class="std" id="add_adress">
	<fieldset>
		<h3>{if isset($id_address)}{l s='Your address'}{else}{l s='New address'}{/if}</h3>
		<p class="required text dni">
			<label for="dni">{l s='Identification number'}</label>
			<input type="text" class="text" name="dni" id="dni" value="{if isset($smarty.post.dni)}{$smarty.post.dni}{else}{if isset($address->dni)}{$address->dni}{/if}{/if}" />
			<span class="form_info">{l s='DNI / NIF / NIE'}</span>
			<sup>*</sup>
		</p>
	{assign var="stateExist" value="false"}
	{foreach from=$ordered_adr_fields item=field_name}
		{if $field_name eq 'company'}
			<p class="text">
			<input type="hidden" name="token" value="{$token}" />
			<label for="company">{l s='Company'}</label>
			<input type="text" id="company" name="company" value="{if isset($smarty.post.company)}{$smarty.post.company}{else}{if isset($address->company)}{$address->company}{/if}{/if}" />
		</p>
		{if $vat_display == 2}
			<div id="vat_area">
		{elseif $vat_display == 1}
			<div id="vat_area" style="display: none;">
		{else}
			<div style="display: none;">
		{/if}
			<div id="vat_number">
				<p class="text">
					<label for="vat_number">{l s='VAT number'}</label>
					<input type="text" class="text" name="vat_number" value="{if isset($smarty.post.vat_number)}{$smarty.post.vat_number}{else}{if isset($address->vat_number)}{$address->vat_number}{/if}{/if}" />
				</p>
			</div>
		</div>
		{/if}
		{if $field_name eq 'lastname'}
		<p class="required text">
			<label for="lastname">{l s='Last name'} <sup>*</sup></label>
			<input type="text" id="lastname" name="lastname" value="{if isset($smarty.post.lastname)}{$smarty.post.lastname}{else}{if isset($address->lastname)}{$address->lastname}{/if}{/if}" />
		</p>
		{/if}
		{if $field_name eq 'firstname'}
		<p class="required text">
			<label for="firstname">{l s='First name'} <sup>*</sup></label>
			<input type="text" name="firstname" id="firstname" value="{if isset($smarty.post.firstname)}{$smarty.post.firstname}{else}{if isset($address->firstname)}{$address->firstname}{/if}{/if}" />
		</p>
		{/if}
		{if $field_name eq 'address1'}
		<p class="required text">
			<label for="address1">{l s='Address'} <sup>*</sup></label>
			<input type="text" id="address1" name="address1" value="{if isset($smarty.post.address1)}{$smarty.post.address1}{else}{if isset($address->address1)}{$address->address1}{/if}{/if}" />
		</p>
		{/if}
		{if $field_name eq 'address2'}
		<p class="required text">
			<label for="address2">{l s='Address (Line 2)'}</label>
			<input type="text" id="address2" name="address2" value="{if isset($smarty.post.address2)}{$smarty.post.address2}{else}{if isset($address->address2)}{$address->address2}{/if}{/if}" />
		</p>
		{/if}
		{if $field_name eq 'postcode'}
		<p class="required postcode text">
			<label for="postcode">{l s='Zip / Postal Code'} <sup>*</sup></label>
			<input type="text" id="postcode" name="postcode" value="{if isset($smarty.post.postcode)}{$smarty.post.postcode}{else}{if isset($address->postcode)}{$address->postcode}{/if}{/if}" onkeyup="$('#postcode').val($('#postcode').val().toUpperCase());" />
		</p>
		{/if}
		{if $field_name eq 'City:name' || $field_name eq 'city'}
		<p class="required text">
			<label for="id_city">{l s='City'} <sup>*</sup></label>
			<select name="id_city" id="id_city">
				<option value="">-</option>
			</select>
		</p>
		{*
			if customer hasn't update his layout address, country has to be verified
			but it's deprecated
		*}
		{/if}
		{if $field_name eq 'Country:name' || $field_name eq 'country'}
		<p class="required select">
			<label for="id_country">{l s='Country'} <sup>*</sup></label>
			<select id="id_country" name="id_country">{$countries_list}</select>
		</p>
		{if $vatnumber_ajax_call}
		<script type="text/javascript">
		var ajaxurl = '{$ajaxurl}';
		{literal}
				$(document).ready(function(){
					$('#id_country').change(function() {
						$.ajax({
							type: "GET",
							url: ajaxurl+"vatnumber/ajax.php?id_country="+$('#id_country').val(),
							success: function(isApplicable){
								if(isApplicable == "1")
								{
									$('#vat_area').show();
									$('#vat_number').show();
								}
								else
								{
									$('#vat_area').hide();
								}
							}
						});
					});
				});
		{/literal}
		</script>
		{/if}
		{/if}
		{if $field_name eq 'State:name'}
		{assign var="stateExist" value="true"}
		<p class="required id_state select">
			<label for="id_state">{l s='State'} <sup>*</sup></label>
			<select name="id_state" id="id_state">
				<option value="">-</option>
			</select>
		</p>
		{/if}
		{/foreach}
		{if $stateExist eq "false"}
		<p class="required id_state select">
			<label for="id_state">{l s='State'} <sup>*</sup></label>
			<select name="id_state" id="id_state">
				<option value="">-</option>
			</select>
		</p>
		{/if}
		<p class="textarea">
			<label for="other">{l s='Additional information'}</label>
			<textarea id="other" name="other" cols="26" rows="3">{if isset($smarty.post.other)}{$smarty.post.other}{else}{if isset($address->other)}{$address->other}{/if}{/if}</textarea>
		</p>
		{if $onr_phone_at_least}
			<p class="inline-infos required">{l s='You must register at least one phone number'} <sup class="required">*</sup></p>
		{/if}
		<p class="text">
			<label for="phone">{l s='Home phone'}</label>
			<input type="text" id="phone" name="phone" value="{if isset($smarty.post.phone)}{$smarty.post.phone}{else}{if isset($address->phone)}{$address->phone}{/if}{/if}" />
		</p>
		<p class="text">
			<label for="phone_mobile">{l s='Mobile phone'}</label>
			<input type="text" id="phone_mobile" name="phone_mobile" value="{if isset($smarty.post.phone_mobile)}{$smarty.post.phone_mobile}{else}{if isset($address->phone_mobile)}{$address->phone_mobile}{/if}{/if}" />
		</p>
		<p class="required text" id="adress_alias">
			<label for="alias">{l s='Assign an address title for future reference'} <sup>*</sup></label>
			<input type="text" id="alias" name="alias" value="{if isset($smarty.post.alias)}{$smarty.post.alias}{else if isset($address->alias)}{$address->alias}{else if isset($select_address)}{l s='My address'}{/if}" />
		</p>
	</fieldset>
	<p class="submit2">
		{if isset($id_address)}<input type="hidden" name="id_address" value="{$id_address|intval}" />{/if}
		{if isset($back)}<input type="hidden" name="back" value="{$back}" />{/if}
		{if isset($mod)}<input type="hidden" name="mod" value="{$mod}" />{/if}
		{if isset($select_address)}<input type="hidden" name="select_address" value="{$select_address|intval}" />{/if}
		<input type="submit" name="submitAddress" id="submitAddress" value="{l s='Save'}" class="button" />
	</p>
</form>
