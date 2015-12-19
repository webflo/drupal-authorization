<?php

/**
 * @file
 * Contains \Drupal\authorization\Provider\ProviderPluginBase.
 */

namespace Drupal\authorization\Provider;

use Drupal\authorization\Plugin\ConfigurablePluginBase;
use Drupal\authorization\Provider\ProviderInterface;

/**
 * Base class for Authorization provider plugins.
 */
abstract class ProviderPluginBase extends ConfigurablePluginBase implements ProviderInterface {

  // Add common methods and abstract methods for your plugin type here.

}
