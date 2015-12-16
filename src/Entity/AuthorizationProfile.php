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
   * The Authorization provider.
   *
   * @var string
   */
  protected $provider;

  /**
   * The Authorization consumer.
   *
   * @var string
   */
  protected $consumer;


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

}
