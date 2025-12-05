<?php
use CRM_PeopleFinder_ExtensionUtil as E;

/**
 * Collection of utility functions for this extension.
 */
class CRM_PeopleFinder_ExtensionUtil {
  const SHORT_NAME = "peoplefinder";
  const LONG_NAME = "com.example.peoplefinder";
  const PATH = "com.example.peoplefinder";
  const URL = "civicrm/peoplefinder";

  /**
   * Translate a string using the extension's domain.
   *
   * If the extension doesn't provide a translation the original string is returned.
   *
   * @param string $text
   *   Canonical message text (generally en_US).
   * @param array $params
   *
   * @return string
   *   Translated text.
   */
  public static function ts($text, $params = []) {
    if (!array_key_exists('domain', $params)) {
      $params['domain'] = [self::LONG_NAME, NULL];
    }
    return ts($text, $params);
  }

  /**
   * Get the URL of a resource file (in this extension).
   *
   * @param string|NULL $file
   *   Ex: NULL.
   *   Ex: 'css/foo.css'.
   *
   * @return string
   *   Ex: 'http://example.org/sites/default/ext/org.example.foo'.
   *   Ex: 'http://example.org/sites/default/ext/org.example.foo/css/foo.css'.
   */
  public static function url($file = NULL) {
    if ($file === NULL) {
      return rtrim(CRM_Core_Resources::singleton()->getUrl(self::LONG_NAME), '/');
    }
    return CRM_Core_Resources::singleton()->getUrl(self::LONG_NAME, $file);
  }

}

