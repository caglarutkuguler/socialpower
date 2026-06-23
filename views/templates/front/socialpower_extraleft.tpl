{*
*	Module Name: Social Buttons Pro
*	Module URI: Please contact with info@megventure.com
*	Description: A professional social sharing module for Prestashop platforms
*	Version: 2.0.0
*	Author: MEG Venture
*
*	Copyright 2012, MEG Venture (info@megventure.com)
*
*	This program is not a free software: you can't redistribute it and/or modify
*	it. All rights reserved to MEG Venture.
*
*	This copyright notice  and licence should be retained in all modules based on this framework.
*	This does not affect your rights to assert copyright over your own original work.
*	
*}

<!-- Social power BEGIN -->
<div class="social-footer">
<ul class="social-buttons cf">

{if $socialpower->in_enable=="true"}
<li style="padding-right:5px;margin-top:5px;" >
<script type="IN/Share" data-counter="{$socialpower->in_data_count|escape:'htmlall':'UTF-8'}"></script></li>
{/if}  

{if $socialpower->gp_enable=="true"}
<li style="width:{$socialpower->gp_data_width|escape:'htmlall':'UTF-8'}px; padding-right:5px;" >
<div class="g-plusone" data-size="{$socialpower->gp_data_size|escape:'htmlall':'UTF-8'}" data-annotation="{$socialpower->gp_data_annotation|escape:'htmlall':'UTF-8'}" data-width="{$socialpower->in_data_count|escape:'htmlall':'UTF-8'}"></div>
</li>
{/if}  

{if $socialpower->tw_enable=="true"}
<li style="width:{$socialpower->tw_width|escape:'htmlall':'UTF-8'}px; padding-right:5px;">   
   <a href="https://twitter.com/share" class="twitter-share-button" data-count="{$socialpower->tw_data_count|escape:'htmlall':'UTF-8'}" data-via="{$socialpower->tw_data_via|escape:'htmlall':'UTF-8'}">{l s='Share on Twitter' mod='socialpower'}</a>
 </li>
{/if}  

{if $socialpower->fb_enable=="true"}

{if $socialpower->fb_data_action=="like"}
<li style="padding-right:5px;" >
<div class="fb-like" data-send="false" data-width="{$socialpower->fb_data_width|escape:'htmlall':'UTF-8'}" data-layout="{$socialpower->fb_data_layout|escape:'htmlall':'UTF-8'}" data-show-faces="{$socialpower->fb_data_show_faces|escape:'htmlall':'UTF-8'}" data-font="{$socialpower->fb_data_font|escape:'htmlall':'UTF-8'}" data-action="like"></div>
	</li>
{/if} 

{if $socialpower->fb_data_action=="recommend"}
<li style="padding-right:5px;" >
<div id="fb-root"></div>
<div class="fb-like" data-send="false" data-width="{$socialpower->fb_data_width|escape:'htmlall':'UTF-8'}" data-layout="{$socialpower->fb_data_layout|escape:'htmlall':'UTF-8'}" data-show-faces="{$socialpower->fb_data_show_faces|escape:'htmlall':'UTF-8'}" data-font="{$socialpower->fb_data_font|escape:'htmlall':'UTF-8'}" data-action="recommend"></div>
	</li>{/if} 

{if $socialpower->fb_data_action=="both"}
<div id="fb-root"></div>
<li style="padding-right:5px;" >
<div class="fb-like" data-send="false" data-width="{$socialpower->fb_data_width|escape:'htmlall':'UTF-8'}" data-layout="{$socialpower->fb_data_layout|escape:'htmlall':'UTF-8'}" data-show-faces="{$socialpower->fb_data_show_faces|escape:'htmlall':'UTF-8'}" data-font="{$socialpower->fb_data_font|escape:'htmlall':'UTF-8'}" data-action="recommend"></div>
</li>    
<li style="padding-right:5px;" >
<div class="fb-like" data-send="false" data-width="{$socialpower->fb_data_width|escape:'htmlall':'UTF-8'}" data-layout="{$socialpower->fb_data_layout|escape:'htmlall':'UTF-8'}" data-show-faces="{$socialpower->fb_data_show_faces|escape:'htmlall':'UTF-8'}" data-font="{$socialpower->fb_data_font|escape:'htmlall':'UTF-8'}" data-action="like"></div>
</li>  
{/if} 
{/if}  
    
</ul>
</div>

<!-- Social power END -->
