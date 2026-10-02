# Changelog

All notable changes to `socialpower`.

## 3.1.1

### Fixed

- **"Could not perform action upgrade for module undefined" when upgrading
  from 3.0.x.** The review-request helper was declared as
  `MegVentureReviewNudge`, a class name that every MEG Venture module with the
  same feature also uses for its own copy. Another such module (Login as
  Customer loads its copy at the top of its main file) had already declared
  the name when the 3.1.0 upgrade script loaded this one, so PHP stopped with
  "Cannot declare class" and the module manager could not even name the
  module. The class is now `SocialpowerReviewNudge`; the file name and the
  translations are unchanged.

## 3.1.0

### Added

- A single review-request line on the module's own configuration page. It
  appears at the earliest 21 days after installing, asks once for a short
  review on megventure.com, and disappears forever after a click, a
  "No thanks", or three unanswered views. It makes no outbound request of any
  kind and stores nothing beyond three prefixed configuration values, which
  uninstalling removes.

## 3.0.1

### Fixed

- **Fatal error on newer PrestaShop cores.** `implements WidgetInterface` resolves against the global namespace, but newer cores ship the interface only as `PrestaShop\PrestaShop\Core\Module\WidgetInterface` with no global alias, so the module died with `ClassNotFoundError` on those shops. Whichever name the shop provides is now aliased to the global one before the class declaration.
