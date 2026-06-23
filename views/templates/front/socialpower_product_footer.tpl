{*
*	Module Name: Social Buttons Pro
*	Module URI: Please contact with info@megventure.com
*	Description: A professional social sharing module for Prestashop platforms
*	Version: 2.0.0
*	Author: MEG Venture
*
*	Copyright 2011, MEG Venture (info@megventure.com)
*
*	This program is not a free software: you can't redistribute it and/or modify
*	it. All rights reserved to MEG Venture.
*
*	This copyright notice  and licence should be retained in all modules based on this framework.
*	This does not affect your rights to assert copyright over your own original work.
*	
*}

<!-- Social power BEGIN -->
<article class="text">
<ul class="social-buttons cf">
{if $socialpower->in_enable=="true"}
<li><script type="IN/Share" data-counter="{$socialpower->in_data_count|escape:'htmlall':'UTF-8'}"></script></li>
{/if}  

{if $socialpower->gp_enable=="true"}
	<li style="width:{$socialpower->gp_data_width|escape:'htmlall':'UTF-8'}px;" >
<div class="g-plusone" data-size="{$socialpower->gp_data_size|escape:'htmlall':'UTF-8'}" data-annotation="{$socialpower->gp_data_annotation|escape:'htmlall':'UTF-8'}" data-width="{$socialpower->in_data_count|escape:'htmlall':'UTF-8'}"></div>
	</li>
{/if}  

{if $socialpower->tw_enable=="true"}
<li style="width:{$socialpower->tw_width|escape:'htmlall':'UTF-8'}px;"> 
   <a href="https://twitter.com/share" class="twitter-share-button" data-count="{$socialpower->tw_data_count|escape:'htmlall':'UTF-8'}" data-via="{$socialpower->tw_data_via|escape:'htmlall':'UTF-8'}">{l s='Share on Twitter' mod='socialpower'}</a>
   </li>
{/if}  


{if $socialpower->fb_enable=="true"}  

        <div id="fb-root"></div>
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=497372956951309&version=v2.0";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>  
        
    {if $socialpower->fb_data_action=="like"}
    <li>
        <div class="fb-like" data-layout="{$socialpower->fb_data_layout|escape:'htmlall':'UTF-8'}" data-action="like" data-show-faces="{$socialpower->fb_data_show_faces|escape:'htmlall':'UTF-8'}" data-share="false" data-width="{$socialpower->fb_data_width|escape:'htmlall':'UTF-8'}" data-font="{$socialpower->fb_data_font|escape:'htmlall':'UTF-8'}"></div>        
    </li>
    {/if} 
    
    {if $socialpower->fb_data_action=="recommend"}
        <li>
<div class="fb-like" data-layout="{$socialpower->fb_data_layout|escape:'htmlall':'UTF-8'}" data-action="recommend" data-show-faces="{$socialpower->fb_data_show_faces|escape:'htmlall':'UTF-8'}" data-share="false" data-width="{$socialpower->fb_data_width|escape:'htmlall':'UTF-8'}" data-font="{$socialpower->fb_data_font|escape:'htmlall':'UTF-8'}"></div>
        </li>
    {/if} 
    
    {if $socialpower->fb_data_action=="both"}
        <li>
		<div class="fb-like" data-layout="{$socialpower->fb_data_layout|escape:'htmlall':'UTF-8'}" data-action="recommend" data-show-faces="{$socialpower->fb_data_show_faces|escape:'htmlall':'UTF-8'}" data-share="false" data-width="{$socialpower->fb_data_width|escape:'htmlall':'UTF-8'}" data-font="{$socialpower->fb_data_font|escape:'htmlall':'UTF-8'}"></div>
        </li>
    	<li>
        <div class="fb-like" data-layout="{$socialpower->fb_data_layout|escape:'htmlall':'UTF-8'}" data-action="like" data-show-faces="{$socialpower->fb_data_show_faces|escape:'htmlall':'UTF-8'}" data-share="false" data-width="{$socialpower->fb_data_width|escape:'htmlall':'UTF-8'}" data-font="{$socialpower->fb_data_font|escape:'htmlall':'UTF-8'}"></div>        
    	</li>          
    {/if} 
{/if}  
    
</ul>
</article>

<!-- Social power END -->
