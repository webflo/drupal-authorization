<?php

/**
 * @file
 * Contains \Drupal\authorization_drupal_roles\Plugin\authorization\consumer\DrupalRolesConsumer.
 */

namespace Drupal\authorization_drupal_roles\Plugin\authorization\consumer;

use Drupal\Core\Form\FormStateInterface;

use Drupal\authorization\Consumer\ConsumerPluginBase;
/**
 * @AuthorizationConsumer(
 *   id = "authorization_drupal_roles",
 *   label = @Translation("Drupal Roles"),
 *   description = @Translation("Add users to roles.")
 * )
 */
class DrupalRolesConsumer extends ConsumerPluginBase {

  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['description'] = array(
      '#type' => 'markup',
      '#markup' => t('There are no settings for Drupal roles.'),
    );
    return $form;
  }

  public function buildRowForm(array $form, FormStateInterface $form_state, $index) {
    $row = array();
    $role_options = array();
    $roles = user_roles(TRUE);
    foreach ( $roles as $key => $role ) {
      if ( $key != 'authenticated' ) {
        $role_options[$key] = $role->label();
      }
    }
    $row['role'] = array(
      '#type' => 'select',
      '#title' => t('Role'),
      '#options' => $role_options,
      '#default_value' => $this->configuration['role'],
      '#description' => 'Choose the Drupal role to apply to the user.',
    );
    return $row;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::validateConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
  }
}
