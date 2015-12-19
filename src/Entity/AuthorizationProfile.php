<?php

/**
 * @file
 * Contains \Drupal\authorization\Entity\AuthorizationProfile.
 */

namespace Drupal\authorization\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\authorization\AuthorizationProfileInterface;

/**
 * Defines the Authorization profile entity.
 *
 * @ConfigEntityType(
 *   id = "authorization_profile",
 *   label = @Translation("Authorization profile"),
 *   handlers = {
 *     "list_builder" = "Drupal\authorization\AuthorizationProfileListBuilder",
 *     "form" = {
 *       "add" = "Drupal\authorization\Form\AuthorizationProfileForm",
 *       "edit" = "Drupal\authorization\Form\AuthorizationProfileForm",
 *       "delete" = "Drupal\authorization\Form\AuthorizationProfileDeleteForm"
 *     }
 *   },
 *   config_prefix = "authorization_profile",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/authorization_profile/{authorization_profile}",
 *     "edit-form" = "/admin/structure/authorization_profile/{authorization_profile}/edit",
 *     "delete-form" = "/admin/structure/authorization_profile/{authorization_profile}/delete",
 *     "collection" = "/admin/structure/visibility_group"
 *   }
 * )
 */
class AuthorizationProfile extends ConfigEntityBase implements AuthorizationProfileInterface {
  /**
   * The Authorization profile ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Authorization profile label.
   *
   * @var string
   */
  protected $label;

  /**
   * The id of the Authorization provider.
   *
   * @var string
   */
  protected $provider;

  /**
   * The id of the Authorization consumer.
   *
   * @var string
   */
  protected $consumer;

  /**
   * The provider plugin configuration.
   *
   * @var array
   */
  protected $provider_config = array();

  /**
   * The provider plugin instance.
   *
   * @var \Drupal\authorization\provider\ProviderInterface
   */
  protected $providerPlugin;

  /**
   * The consumer plugin configuration.
   *
   * @var array
   */
  protected $consumer_config = array();

  /**
   * The consumer plugin instance.
   *
   * @var \Drupal\authorization\consumer\ConsumerInterface
   */
  protected $consumerPlugin;

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * {@inheritdoc}
   */
  public function getProviderId() {
    return $this->provider;
  }

  /**
   * {@inheritdoc}
   */
  public function getConsumerId() {
    return $this->consumer;
  }

  /**
   * {@inheritdoc}
   */
  public function hasValidProvider() {
    $provider_plugin_definition = \Drupal::service('plugin.manager.authorization.provider')->getDefinition($this->getProviderId(), FALSE);
    return !empty($provider_plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public function getProvider() {
    error_log("Get Provider");
    if (!$this->providerPlugin) {
      error_log("No Provider");
      $provider_plugin_manager = \Drupal::service('plugin.manager.authorization.provider');
      $config = $this->provider_config;
      $config['profile'] = $this;
      if (!($this->providerPlugin = $provider_plugin_manager->createInstance($this->getProviderId(), $config))) {
        $args['@provider'] = $this->getProviderId();
        $args['%profile'] = $this->label();
        throw new SearchApiException(new FormattableMarkup('The provider with ID "@provider" could not be retrieved for profile %profile.', $args));
      }
    } else {
      error_log(print_r($this->provider, TRUE));
    }

    return $this->providerPlugin;
  }

  /**
   * {@inheritdoc}
   */
  public function getProviderConfig() {
    return $this->provider_config;
  }

  /**
   * {@inheritdoc}
   */
  public function setProviderConfig(array $provider_config) {
    $this->provider_config = $provider_config;
    $this->getProvider()->setConfiguration($provider_config);
    return $this;
  }


  /**
   * {@inheritdoc}
   */
  public function hasValidConsumer() {
    $consumer_plugin_definition = \Drupal::service('plugin.manager.authorization.consumer')->getDefinition($this->getconsumerId(), FALSE);
    return !empty($consumer_plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public function getConsumer() {
    if (!$this->consumerPlugin) {
      $consumer_plugin_manager = \Drupal::service('plugin.manager.authorization.consumer');
      $config = $this->consumer_config;
      $config['server'] = $this;
      if (!($this->consumerPlugin = $consumer_plugin_manager->createInstance($this->getConsumerId(), $config))) {
        $args['@consumer'] = $this->getConsumerId();
        $args['%server'] = $this->label();
        throw new SearchApiException(new FormattableMarkup('The consumer with ID "@consumer" could not be retrieved for server %server.', $args));
      }
    }
    return $this->consumerPlugin;
  }

  /**
   * {@inheritdoc}
   */
  public function getConsumerConfig() {
    return $this->consumer_config;
  }

  /**
   * {@inheritdoc}
   */
  public function setConsumerConfig(array $consumer_config) {
    $this->consumer_config = $consumer_config;
    $this->getConsumer()->setConfiguration($consumer_config);
    return $this;
  }

}
