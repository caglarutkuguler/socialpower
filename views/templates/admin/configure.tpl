{**
 * Social Share Buttons - Grow Traffic with One-Click Sharing
 *
 * @author    MEG Venture <info@megventure.com>
 * @copyright 2019-2026 MEG Venture & Consulting Ltd.
 * @license   https://opensource.org/licenses/MIT MIT License
 *}

<link rel="stylesheet" href="{$sp_css_url|escape:'html':'UTF-8'}">

<div class="panel">
  <div class="panel-heading">
    <i class="icon-share"></i> {l s='Social Share Buttons' mod='socialpower'}
    <span class="badge">v{$sp_module_version|escape:'html':'UTF-8'}</span>
  </div>

  <p>{l s='Turn your visitors into promoters. Every share button spreads your products across social media for free — bringing new visitors back to your store. These are lightweight share links: they load no third-party tracking scripts, so your pages stay fast and cookie-consent friendly.' mod='socialpower'}</p>

  <div class="row" style="margin-top:15px;">
    <div class="col-lg-4 col-md-6">
      <div class="sp-step">
        <span class="sp-step__num">1</span>
        <strong>{l s='Pick your networks' mod='socialpower'}</strong>
        <p class="text-muted">{l s='Choose the social networks your customers actually use in the settings below.' mod='socialpower'}</p>
      </div>
    </div>
    <div class="col-lg-4 col-md-6">
      <div class="sp-step">
        <span class="sp-step__num">2</span>
        <strong>{l s='Choose where they appear' mod='socialpower'}</strong>
        <p class="text-muted">{l s='Show a share row on product pages, a floating bar on every page, or both.' mod='socialpower'}</p>
      </div>
    </div>
    <div class="col-lg-4 col-md-6">
      <div class="sp-step">
        <span class="sp-step__num">3</span>
        <strong>{l s='Match your design' mod='socialpower'}</strong>
        <p class="text-muted">{l s='Set the style, shape, size and colors, then open your store to see them live.' mod='socialpower'}</p>
      </div>
    </div>
  </div>
</div>

<div class="panel">
  <div class="panel-heading">
    <i class="icon-eye"></i> {l s='Live preview' mod='socialpower'}
  </div>
  <p class="text-muted">{l s='This is how your buttons look with the current settings. Save changes to update the preview.' mod='socialpower'}</p>
  <div class="sp-preview">
    {$sp_preview_html nofilter}
  </div>
</div>

<div class="panel">
  <div class="panel-heading">
    <i class="icon-map-marker"></i> {l s='Where your buttons show' mod='socialpower'}
  </div>
  <ul class="list-unstyled">
    <li>
      {if $sp_show_product}
        <i class="icon-check text-success"></i> {l s='Product pages: a share row is shown under each product.' mod='socialpower'}
      {else}
        <i class="icon-remove text-muted"></i> {l s='Product pages: off.' mod='socialpower'}
      {/if}
    </li>
    <li>
      {if $sp_show_floating}
        <i class="icon-check text-success"></i> {l s='Floating bar: pinned to the side of the screen.' mod='socialpower'}
      {else}
        <i class="icon-remove text-muted"></i> {l s='Floating bar: off.' mod='socialpower'}
      {/if}
    </li>
    <li>
      <i class="icon-puzzle-piece text-info"></i>
      {l s='Anywhere else: place the buttons in your theme with the code' mod='socialpower'}
      <code>{literal}{widget name='socialpower'}{/literal}</code>
      {l s='or transplant the module onto another hook from the Positions page.' mod='socialpower'}
    </li>
  </ul>
  <a href="{$sp_positions_url|escape:'html':'UTF-8'}" class="btn btn-default">
    <i class="icon-move"></i> {l s='Open the Positions page' mod='socialpower'}
  </a>
</div>

<style>
  .sp-step { padding: 12px 0; }
  .sp-step__num {
    display: inline-flex; align-items: center; justify-content: center;
    width: 28px; height: 28px; margin-right: 6px;
    background: #6c5ce7; color: #fff; border-radius: 50%; font-weight: 700;
  }
  .sp-preview { padding: 10px 4px; }
  .sp-preview .sp-share--inline { position: static; }
</style>
