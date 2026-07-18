# Social Share Buttons — Grow Traffic with One-Click Sharing

Social sharing buttons for PrestaShop **1.7, 8 and 9**. Let your visitors share
products and pages on Facebook, X (Twitter), WhatsApp, Pinterest, LinkedIn,
Telegram, Reddit and email with a single click — turning every shopper into a
free word-of-mouth promoter.

- **Module technical name:** `socialpower`
- **Version:** 3.0.0
- **Author:** MEG Venture

---

## Why this module

Every share sends your product link to a new audience for free. More shares mean
more inbound visitors, more social proof and better organic reach — without
paying for ads.

Unlike old "social button" modules, this one **loads no third-party JavaScript**.
The buttons are plain share links (share intents), so:

- Your pages stay **fast** — no Facebook/Twitter SDKs slowing them down.
- You stay **GDPR / cookie-consent friendly** — no external tracking scripts and
  no cookies dropped before consent.
- The buttons **never break** when a social network changes its widget API.

---

## Supported networks

| Network      | What it does                                             |
|--------------|----------------------------------------------------------|
| Facebook     | Opens the Facebook share dialog for the page             |
| X (Twitter)  | Pre-fills a tweet with the page title, link and your handle |
| WhatsApp     | Shares the link in a chat (mobile & WhatsApp Web)        |
| Pinterest    | Pins the product image with a description                |
| LinkedIn     | Shares the page on LinkedIn                              |
| Telegram     | Shares the link on Telegram                              |
| Reddit       | Submits the page to Reddit                               |
| Email        | Opens the visitor's mail app with the link               |
| Copy link    | Copies the page URL to the clipboard (with confirmation) |
| Native share | Uses the phone's built-in share sheet (mobile only)      |

---

## Quick start

1. Install the module from **Modules → Module Manager**.
2. Open its configuration page.
3. Tick the networks you want, choose where the buttons appear (product pages,
   floating bar, or both) and pick a style.
4. Save and open your storefront — the buttons are live.

A **live preview** on the configuration page shows exactly how your buttons will
look with the current settings.

---

## Where the buttons can appear

- **Product pages** — a share row under each product (on by default).
- **Floating bar** — a vertical bar pinned to the left or right edge of every
  page (or product pages only). It automatically moves to the bottom on phones.
- **Anywhere in your theme** — drop this snippet where you want the buttons:

  ```smarty
  {widget name='socialpower'}
  ```

- **Any hook** — from **Design → Positions**, transplant the module onto another
  hook (for example the footer or a column).

---

## Settings

| Setting              | Description                                                        |
|----------------------|--------------------------------------------------------------------|
| Networks             | Which share buttons to show                                        |
| Show on product pages| Share row under each product                                       |
| Show floating bar    | Fixed share bar on the side of the screen                          |
| Floating bar side    | Left or right                                                      |
| Floating bar pages   | All pages, or product pages only                                   |
| Button style         | Solid, outline or minimal (icon only)                              |
| Button shape         | Rounded, circle or square                                          |
| Button size          | Small, medium or large                                             |
| Colors               | Official brand colors, or one neutral color                        |
| Show text labels     | Show the network name next to each icon                            |
| Show "Share" heading | Small heading above the buttons                                    |
| X (Twitter) handle   | Your handle (without `@`); tweets are tagged `via @handle`         |

The share URL, page title and (for Pinterest) the product image are detected
automatically: from the product on product pages, and from the page's canonical
URL / title everywhere else.

---

## Privacy & GDPR

This module does **not** load any external script or set any cookie. Sharing only
happens when a visitor clicks a button, which opens the chosen network in a new
window. Nothing is sent anywhere until the visitor acts.

---

## Troubleshooting

**The buttons do not appear on product pages.**
Make sure *Show on product pages* is enabled. Some heavily customised themes
override the product template — in that case use `{widget name='socialpower'}`
where you want the row, or transplant the module onto another hook from
**Design → Positions**.

**Pinterest does not pick up an image.**
Pinterest needs a product image. On non-product pages, Pinterest falls back to
letting the user choose an image from the page.

**The floating bar overlaps my content on mobile.**
It automatically switches to a centred bar at the bottom of the screen on small
screens. If your theme has its own sticky footer, choose *Product pages only* or
turn the floating bar off and use the product-page row instead.

**Nothing happens when I click a button.**
Share buttons need JavaScript. Check that your theme is not blocking the module's
`front.js`, and that a pop-up blocker is not silently closing the share window.

**I use several shops (multistore).**
Settings are stored per shop context. Select the shop (or "All shops") in the
top bar before saving.

---

## Upgrading from 2.x

Version 3.0.0 is a full modernisation. The old embedded platform widgets
(Facebook Like SDK, the Twitter/LinkedIn/Google+ scripts) are gone — Google+ and
the old LinkedIn/Twitter widgets no longer exist, and the Facebook SDK was slow
and privacy-invasive. They are replaced by lightweight share links.

The upgrade runs automatically and:

- removes the old `socialpower` / `socialpower_lang` database tables (all
  settings now live in PrestaShop configuration);
- unregisters the legacy hooks and registers the modern ones;
- deletes the obsolete 2.x files (the PS 1.4 backward-compatibility shim, the
  `SocialpowerClass` model, the seven near-identical templates and the widget
  screenshot images).

Your previous cosmetic choices are not migrated because the underlying widgets no
longer exist; review the settings once after upgrading.

## Fixed in 3.0.0

- **Dead widgets removed:** Google+ (shut down in 2019), the old LinkedIn
  `in.js` and the legacy Twitter count button no longer work; they were removed.
- **No more insecure `http://` scripts:** the old Facebook/LinkedIn includes
  loaded over `http://`, causing mixed-content warnings on HTTPS shops.
- **Multistore data loss fixed:** applying a template used to run
  `DELETE FROM socialpower WHERE id > 0`, wiping every shop's settings. There are
  no such templates or raw deletes anymore.
- **No PHP 8 fatals:** the configuration page no longer relies on the fragile
  `getContent()` flow that could call methods on a `false` object.
- **Lighter footprint:** ~260 KB of widget screenshots and seven duplicated
  templates were removed.

---

## Uninstall

Uninstalling removes all of the module's settings and hooks. It leaves no
database tables behind.

---

© 2019–2026 MEG Venture. All rights reserved.
