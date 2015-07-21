{*
$Id: menu_manufacturers.tpl,v 1.9 2009/04/18 06:33:06 max Exp $
vim: set ts=2 sw=2 sts=2 et:
*}
{if $manufacturers_menu ne ''}

  {capture name=menu}
    <select name="manufacturers" id="manufacturers" onchange="if (this.selectedIndex > 0) location.href=this[this.selectedIndex].value;">
    <option>Manufacturers</option>
    {foreach from=$manufacturers_menu item=m}
    <option value="manufacturers.php?manufacturerid={$m.manufacturerid}">{$m.manufacturer}</option>
    {/foreach}
    </select>
  {/capture}
  {include file="customer/menu_dialog.tpl" title="Select your Make" content=$smarty.capture.menu additional_class="menu-manufacturers"}

{/if}
