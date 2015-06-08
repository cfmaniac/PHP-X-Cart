<br>

{* Previous *}
{if $prev.productid}
<div style="float:left;"><A HREF="product.php?productid={$prev.productid}"><img src="{$ImagesDir}/larrow.gif" width=9 height=9 border=0 alt="{$lng.lbl_prev_page}"> {$prev.product}</A></div>
{/if}

{* Next *}
{if $next.productid}
<div style="float:right;"><A HREF="product.php?productid={$next.productid}">{$next.product} <img src="{$ImagesDir}/rarrow.gif" width=9 height=9 border=0 alt="{$lng.lbl_next_page}"></A></div>
{/if}

<br><br>
