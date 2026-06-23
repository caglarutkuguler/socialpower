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

{if $socialpower->in_enable=="true"}<script src="http://platform.linkedin.com/in.js" type="text/javascript"></script>{/if}
{if $socialpower->gp_enable=="true"}
<script src="https://apis.google.com/js/platform.js" async defer></script>	
{/if}
{if $socialpower->tw_enable=="true"}
{literal}<script>
!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>{/literal}
{/if} 
 
{if $socialpower->fb_enable=="true"}
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "http://connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
{/if}  