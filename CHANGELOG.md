# Changelog

All notable changes to `socialpower`.

## 3.0.1

### Fixed

- **Fatal error on newer PrestaShop cores.** `implements WidgetInterface` resolves against the global namespace, but newer cores ship the interface only as `PrestaShop\PrestaShop\Core\Module\WidgetInterface` with no global alias, so the module died with `ClassNotFoundError` on those shops. Whichever name the shop provides is now aliased to the global one before the class declaration.
