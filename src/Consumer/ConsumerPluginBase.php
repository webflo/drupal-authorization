<?php

/**
 * @file
 * Contains \Drupal\authorization\Consumer\ConsumerPluginBase.
 */

namespace Drupal\authorization\Consumer;

use Drupal\authorization\Plugin\ConfigurablePluginBase;
use Drupal\authorization\Consumer\ConsumerInterface;
use Drupal\authorization\Form\SubFormState;

/**
 * Base class for Authorization consumer plugins.
 */
abstract class ConsumerPluginBase extends ConfigurablePluginBase implements ConsumerInterface {

  public $type = 'consumer';
  protected $allowConsumerObjectCreation = NULL;

  public function submitRowForm(array &$form, SubFormState $form_state) {
    $values = $form_state->getValues();
    $consumer_mappings = array();
    foreach ($values as $key => $value) {
      $consumer_mappings[] = $value['consumer_mappings'];
    }
    $form_state->setValue('consumer_mappings', $consumer_mappings);

    parent::submitRowForm($form, $form_state);
  }

  /**
   * @TODO fix the documentation
   * @param drupal user object $user to have $consumer_id revoked
   * @param string lower case $consumer_id $consumer_id such as drupal role name, og group name, etc.
   * @param mixed $consumer.  depends on type of consumer.  Drupal roles are strings, og groups are ??
   * @param array $user_auth_data array of $user data specific to this consumer type.
   *   stored in $user->data['ldap_authorizations'][<consumer_type>] array
   * @param boolean $reset signifying if caches associated with $consumer_id should be invalidated.
   *
   * @return boolean TRUE on success, FALSE on fail.  If user save is FALSE, the user object will
   *   not be saved and reloaded, so a returned TRUE may be misleading.
   *   $user_auth_data should have successfully revoked consumer id removed
   */
   public function revokeSingleAuthorization(&$user, $op, $incoming, $consumer_mapping, &$user_auth_data, $user_save=FALSE, $reset=FALSE) {
     // method must be overridden
  }

  /**
   * @TODO fix the documentation
   * @param stdClass $user as drupal user object to have $consumer_id granted
   * @param string lower case $consumer_id $consumer_id such as drupal role name, og group name, etc.
   * @param mixed $consumer.  depends on type of consumer.  Drupal roles are strings, og groups are ??
   * @param array $user_auth_data in form
   *   array('my drupal role' =>
   *     'date_granted' => 1351814718,
   *     'consumer_id_mixed_case' => 'My Drupal Role',
   *     )
   * @param boolean $reset signifying if caches associated with $consumer_id should be invalidated.
   *
   * @return boolean FALSE on failure or TRUE on success
   */
  public function grantSingleAuthorization(&$user, $op, $incoming, $consumer_mapping, &$user_auth_data, $user_save=FALSE, $reset=FALSE) {
     // method must be overridden
  }

  public function createConsumers() {
    return $this->allowConsumerObjectCreation;
  }

}
