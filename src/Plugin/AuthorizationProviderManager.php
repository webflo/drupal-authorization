<?php

/**
 * @file
 * Contains \Drupal\authorization\Plugin\AuthorizationProviderManager.
 */

namespace Drupal\authorization;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Provides the Authorization provider plugin manager.
 */
class AuthorizationProviderManager extends DefaultPluginManager {

  /**
   * Constructor for AuthorizationProviderManager objects.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/AuthorizationProvider', $namespaces, $module_handler, 'Drupal\authorization\Plugin\AuthorizationProviderInterface', 'Drupal\authorization\Annotation\AuthorizationProvider');

    $this->alterInfo('authorization_authorization_provider_info');
    $this->setCacheBackend($cache_backend, 'authorization_authorization_provider_plugins');
  }

}
