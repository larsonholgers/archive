<ul class="pagination">
	<li class="disabled"><a href="#"><span class="badge">{$page.total_items}</span> Items Found</a></li>
	{foreach $page.pages as $p => $l}
		<li {if $l == $args.1 || ($args.1 == '' && $p == 1)}class="active"{/if}><a href="{$link_root}page/{$l}/">Page {$p}</a></li>
	{/foreach}
</ul>