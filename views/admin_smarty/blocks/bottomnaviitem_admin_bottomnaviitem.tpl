[{if method_exists($oViewConf,'devCoreActive') }]
<li>
   <form name="vtdevcore" id="vtdevcore" action="[{$oViewConf->getSelfLink() }]" method="post" target="devframe">
      [{$oViewConf->getHiddenSid() }]
      <input type="hidden" name="cl" value="vt_dev_options">

      <button class="textButton" type="submit" name="fnc" value="cleartmp">clear php cache</button>&nbsp;
      <button class="textButton" type="submit" name="fnc" value="cleartpl">clear tpl cache</button>&nbsp;
      <button class="textButton" type="submit" name="fnc" value="updateviews" onclick="return confirm('[{oxmultilang ident="SHOP_MALL_UPDATEVIEWSCONFIRM"}]?')">update views</button>
      <iframe name="devframe" width="0" height="0" border="0" style="display:none;"></iframe>
   </form>
</li>
[{/if}]
[{$smarty.block.parent}]