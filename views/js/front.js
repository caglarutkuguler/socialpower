/**
 * Social Share Buttons - Grow Traffic with One-Click Sharing
 *
 * @author    MEG Venture <info@megventure.com>
 * @copyright 2019-2026 MEG Venture
 * @license   Academic Free License 3.0 (AFL-3.0)
 *
 * Zero dependencies. Builds native share-intent URLs on the fly from the page's
 * canonical URL (or a per-product hint) and opens them in a small popup. Handles
 * "copy link" and the Web Share API too. No third-party scripts are loaded.
 */
(function () {
    'use strict';

    var POPUP_NETWORKS = ['facebook', 'x', 'whatsapp', 'pinterest', 'linkedin', 'telegram', 'reddit'];

    function enc(value) {
        return encodeURIComponent(value || '');
    }

    // Prefer the page's declared canonical URL, fall back to the address bar.
    function canonicalUrl() {
        var link = document.querySelector('link[rel="canonical"]');
        if (link && link.href) {
            return link.href;
        }
        return window.location.href;
    }

    function buildUrl(network, data) {
        var u = data.url;
        var t = data.title;
        var img = data.image;
        var via = data.via;

        switch (network) {
            case 'facebook':
                return 'https://www.facebook.com/sharer/sharer.php?u=' + enc(u);
            case 'x':
                return 'https://twitter.com/intent/tweet?url=' + enc(u) + '&text=' + enc(t) + (via ? '&via=' + enc(via) : '');
            case 'whatsapp':
                return 'https://wa.me/?text=' + enc((t ? t + ' ' : '') + u);
            case 'pinterest':
                return 'https://www.pinterest.com/pin/create/button/?url=' + enc(u) + '&media=' + enc(img) + '&description=' + enc(t);
            case 'linkedin':
                return 'https://www.linkedin.com/sharing/share-offsite/?url=' + enc(u);
            case 'telegram':
                return 'https://t.me/share/url?url=' + enc(u) + '&text=' + enc(t);
            case 'reddit':
                return 'https://www.reddit.com/submit?url=' + enc(u) + '&title=' + enc(t);
            case 'email':
                return 'mailto:?subject=' + enc(t) + '&body=' + enc((t ? t + '\n\n' : '') + u);
            default:
                return '';
        }
    }

    function openPopup(shareUrl) {
        var w = 600;
        var h = 520;
        var left = 0;
        var top = 0;
        try {
            left = window.top.outerWidth / 2 + window.top.screenX - w / 2;
            top = window.top.outerHeight / 2 + window.top.screenY - h / 2;
        } catch (e) {
            left = (window.screen.width - w) / 2;
            top = (window.screen.height - h) / 2;
        }
        var win = window.open(
            shareUrl,
            'sp_share',
            'noopener,noreferrer,scrollbars=yes,width=' + w + ',height=' + h + ',top=' + top + ',left=' + left
        );
        if (!win) {
            window.open(shareUrl, '_blank', 'noopener');
        }
    }

    function legacyCopy(text) {
        var area = document.createElement('textarea');
        area.value = text;
        area.setAttribute('readonly', '');
        area.style.position = 'absolute';
        area.style.left = '-9999px';
        document.body.appendChild(area);
        area.select();
        try {
            document.execCommand('copy');
        } catch (e) { /* ignore */ }
        document.body.removeChild(area);
    }

    function flash(btn, label) {
        btn.setAttribute('data-copied', label || 'Copied!');
        btn.classList.add('sp-copied');
        window.setTimeout(function () {
            btn.classList.remove('sp-copied');
        }, 1500);
    }

    function copyLink(text, btn, label) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(
                function () { flash(btn, label); },
                function () { legacyCopy(text); flash(btn, label); }
            );
        } else {
            legacyCopy(text);
            flash(btn, label);
        }
    }

    function readData(wrapper) {
        return {
            url: wrapper.getAttribute('data-url') || canonicalUrl(),
            title: wrapper.getAttribute('data-title') || document.title,
            image: wrapper.getAttribute('data-image') || '',
            via: wrapper.getAttribute('data-via') || '',
            copied: wrapper.getAttribute('data-copied') || 'Copied!'
        };
    }

    function handleClick(wrapper, btn) {
        var network = btn.getAttribute('data-network');
        var data = readData(wrapper);

        if (network === 'copy') {
            copyLink(data.url, btn, data.copied);
            return;
        }

        if (network === 'native') {
            if (navigator.share) {
                navigator.share({ title: data.title, url: data.url }).catch(function () { /* dismissed */ });
            }
            return;
        }

        if (network === 'email') {
            window.location.href = buildUrl('email', data);
            return;
        }

        if (POPUP_NETWORKS.indexOf(network) !== -1) {
            openPopup(buildUrl(network, data));
        }
    }

    function init() {
        var wrappers = document.querySelectorAll('.sp-share');
        if (!wrappers.length) {
            return;
        }

        if (navigator.share) {
            document.documentElement.classList.add('sp-native-ready');
        }

        Array.prototype.forEach.call(wrappers, function (wrapper) {
            wrapper.addEventListener('click', function (event) {
                var btn = event.target.closest ? event.target.closest('.sp-btn') : null;
                if (btn && wrapper.contains(btn)) {
                    event.preventDefault();
                    handleClick(wrapper, btn);
                }
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
