{**
 * MILEBIZ 米乐商城
 * ============================================================================
 * 版权所有 2011-20__ 米乐网。
 * 网站地址: http://www.milebiz.com
 * ============================================================================
 * $Author: zhourh $
 *}

<script type="text/javascript">
	i18n_tax_exc = '{l s='Tax Excl.:'} ';
	i18n_tax_inc = '{l s='Tax Incl.:'} ';

	var product_tax = '{$tax_rates}';
	function calcPrice(element, element_has_tax)
	{
			var element_price = element.val().replace(/,/g, '.');
			var other_element_price = 0;

			if (!isNaN(element_price) && element_price > 0)
			{
				if (element_has_tax)
					other_element_price = parseFloat(element_price / ((product_tax / 100) + 1)).toFixed(6);
				else
					other_element_price = ps_round(parseFloat(element_price * ((product_tax / 100) + 1)), 2).toFixed(2);
			}

			$('#related_to_'+element.attr('name')).val(other_element_price);
	}

	$(document).ready(function() { $('.price_impact').each(function() { calcPrice($(this), false); }); });
</script>

{include file="toolbar.tpl" toolbar_btn=$toolbar_btn toolbar_scroll=$toolbar_scroll title=$title}
<div class="leadin">{block name="leadin"}{/block}</div>

{if $generate}<div class="module_confirmation conf confirm">{l s='%d product(s) successfully created.' sprintf=$combinations_size}</div>{/if}
<script type="text/javascript" src="../js/attributesBack.js"></script>
<form enctype="multipart/form-data" method="post" id="generator" action="{$url_generator}">
	<fieldset style="margin-bottom: 35px;">
		<legend><img src="../img/admin/asterisk.gif" alt="" />{l s='Attributes generator'}</legend>
		<div style="float: left; margin-right: 50px;">
			<select multiple name="attributes[]" id="attribute_group" style="height: 500px; margin-bottom: 10px;">
				{foreach $attribute_groups as $k => $attribute_group}
					{if isset($attribute_js[$attribute_group['id_attribute_group']])}
						<optgroup name="{$attribute_group['id_attribute_group']}" id="{$attribute_group['id_attribute_group']}" label="{$attribute_group['name']|escape:'htmlall':'UTF-8'}">
							{foreach $attribute_js[$attribute_group['id_attribute_group']] as $k => $v}
								<option name="{$k}" id="attr_{$k}" value="{$v|escape:'htmlall':'UTF-8'}" title="{$v|escape:'htmlall':'UTF-8'}">{$v|escape:'htmlall':'UTF-8'}</option>
							{/foreach}
						</optgroup>
					{/if}
				{/foreach}
			</select>
			<div style="text-align: center; margin-bottom: 10px;">
				<p>
					<input class="button" type="button" style="margin-right: 15px;" value="{l s='Add'}" class="button" onclick="add_attr_multiple();" />
					<input class="button" type="button" value="{l s='Delete'}" class="button" onclick="del_attr_multiple();" />
				</p>
			</div>
		</div>
		<div style="float: left; width: 570px;">
			<div class="hint" style="width: 570px; padding-left: 45px; margin-bottom: 15px; display: block; position: inherit;">{l s='The Combinations Generator is a tool which allows you to easily create a series of combinations by selecting the related attributes. For example, if you are selling T-Shirts in 3 different sizes and 2 different colors, the Generator will create 6 combinations for you.'}</div>
			<p>{l s='You are currently generating combinations for the following product:'} <b>{$product_name|escape:'htmlall':'UTF-8'}</b></p>
			<h4>{l s='Step 1: On the left side, Select the attributes you want to use (Hold down the "CTRL" Key on your keyboard and validate by clicking on "Add")'}</h4>
			<div>
			{foreach $attribute_groups as $k => $attribute_group}
				{if isset($attribute_js[$attribute_group['id_attribute_group']])}
					<table class="table clear" cellpadding="0" cellspacing="0" style="margin-bottom: 10px; display: none;">
						<thead>
							<tr>
								<th id="tab_h1" style="width: 150px">{$attribute_group['name']|escape:'htmlall':'UTF-8'}</th>
								<th id="tab_h2" style="width: 350px" colspan="2">{l s='Impact on the Product Price'} ({$currency_sign})</th>
								<th style="width: 150px">{l s='Impact on the Product Weight'} ({$weight_unit})</th>
							</tr>
						</thead>
						<tbody id="table_{$attribute_group['id_attribute_group']}" name="result_table">
						</tbody>
					</table>
					{if isset($attributes[$attribute_group['id_attribute_group']])}
						{foreach $attributes[$attribute_group['id_attribute_group']] AS $k => $attribute}
							<script type="text/javascript">
								$('#table_{$attribute_group['id_attribute_group']}').append(create_attribute_row({$k}, {$attribute_group['id_attribute_group']}, '{$attribute['attribute_name']|addslashes}', {$attribute['price']}, {$attribute['weight']}));
								toggle(getE('table_' + {$attribute_group['id_attribute_group']}).parentNode, true);
							</script>
						{/foreach}						
					{/if}
				{/if}
			{/foreach}
            </div>
			<h4>{l s='Step 2 (optional): Select a default Quantity and Reference for all the combinations that the Generator will create for this product'}</h4>
			<table border="0" class="table" cellpadding="0" cellspacing="0">
				<tr>
					<td>{l s='Default Quantity:'}</td>
					<td><input type="text" size="20" name="quantity" value="0" style="width: 50px;" /></td>
				</tr>
				<tr>
					<td>{l s='Default Reference:'}</td>
					<td><input type="text" size="20" name="reference" value="{$product_reference|escape:'htmlall':'UTF-8'}" /></td>
				</tr>
			</table>
			<h4>{l s='Finally, click on "Generate these combinations"'}</h4>
			<p><input type="submit" class="button" style="margin-bottom:5px;" name="generate" value="{l s='Generate these combinations'}" /></p>
		</div>
	</fieldset>
</form>