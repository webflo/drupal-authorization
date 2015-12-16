<?php

/**
 * @file
 * Contains \Drupal\authorization\Form\AuthorizationProfileForm.
 */

namespace Drupal\authorization\Form;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class AuthorizationProfileForm.
 *
 * @package Drupal\authorization\Form
 */
class AuthorizationProfileForm extends EntityForm {
  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $authorization_profile = $this->entity;
    $form['label'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $authorization_profile->label(),
      '#description' => $this->t("Label for the Authorization profile."),
      '#required' => TRUE,
    );

    $form['id'] = array(
      '#type' => 'machine_name',
      '#default_value' => $authorization_profile->id(),
      '#machine_name' => array(
        'exists' => '\Drupal\authorization\Entity\AuthorizationProfile::load',
      ),
      '#disabled' => !$authorization_profile->isNew(),
    );

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $authorization_profile = $this->entity;
    $status = $authorization_profile->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Authorization profile.', [
          '%label' => $authorization_profile->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Authorization profile.', [
          '%label' => $authorization_profile->label(),
        ]));
    }
    $form_state->setRedirectUrl($authorization_profile->urlInfo('collection'));
  }

}
