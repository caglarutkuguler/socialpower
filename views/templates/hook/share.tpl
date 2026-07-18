{**
 * Social Share Buttons - Grow Traffic with One-Click Sharing
 *
 * @author    MEG Venture <info@megventure.com>
 * @copyright 2019-2026 MEG Venture
 * @license   Academic Free License 3.0 (AFL-3.0)
 *}
<div class="sp-share sp-share--{$sp.mode|escape:'html':'UTF-8'} sp-style-{$sp.style|escape:'html':'UTF-8'} sp-shape-{$sp.shape|escape:'html':'UTF-8'} sp-size-{$sp.size|escape:'html':'UTF-8'} sp-color-{$sp.color|escape:'html':'UTF-8'}{if $sp.show_label} sp-has-label{/if}{if $sp.mode == 'floating'} sp-float-{$sp.float_pos|escape:'html':'UTF-8'}{/if}"
     data-url="{$sp.url|escape:'html':'UTF-8'}"
     data-title="{$sp.title|escape:'html':'UTF-8'}"
     data-image="{$sp.image|escape:'html':'UTF-8'}"
     data-via="{$sp.via|escape:'html':'UTF-8'}"
     data-copied="{$sp.copied_label|escape:'html':'UTF-8'}">
  {if $sp.show_heading && $sp.mode != 'floating'}
    <span class="sp-heading">{$sp.heading|escape:'html':'UTF-8'}</span>
  {/if}
  <ul class="sp-list">
    {foreach from=$sp.networks item=net}
      <li class="sp-item">
        <button type="button"
                class="sp-btn sp-btn--{$net.key|escape:'html':'UTF-8'}"
                data-network="{$net.key|escape:'html':'UTF-8'}"
                {if $sp.color != 'mono'}style="--sp-brand:{$net.color|escape:'html':'UTF-8'};"{/if}
                aria-label="{$net.label|escape:'html':'UTF-8'}"
                title="{$net.label|escape:'html':'UTF-8'}">
          <span class="sp-icon">{$net.svg nofilter}</span>
          {if $sp.show_label}<span class="sp-label">{$net.label|escape:'html':'UTF-8'}</span>{/if}
        </button>
      </li>
    {/foreach}
  </ul>
</div>
