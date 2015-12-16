<?php

/**
 * @file
 * Contains \Drupal\authorization\Form\AuthorizationProfileForm.
 */

namespace Drupal\authorization\Form;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AuthorizationProfileForm.
 *
 * @package Drupal\authorization\Form
 */
class AuthorizationProfileForm extends EntityForm {

  /**
   * Constructs a AuthorizationProfileForm object.
   *
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   The entity manager.
   * @param \Drupal\authorization\Provider\ProviderPluginManager $provider_plugin_manager
   *   The Provider plugin manager.
   * @param \Drupal\authorization\Consumer\ConsumerPluginManager $consumer_plugin_manager
   *   The Consumer plugin manager.
   */
  public function __construct(EntityManagerInterface $entity_manager, ProviderPluginManager $provider_plugin_manager, ConsumerPluginManager $consumer_plugin_manager) {
    $this->storage = $entity_manager->getStorage('authorization');
    $this->ProviderPluginManager = $provider_plugin_manager;
    $this->ConsumerPluginManager = $consumer_plugin_manager;
  }


  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    /** @var \Drupal\Core\Entity\EntityManagerInterface $entity_manager */
    $entity_manager = $container->get('entity.manager');
    /** @var \Drupal\authorization\Backend\BackendPluginManager $backend_plugin_manager */
    $provider_plugin_manager = $container->get('plugin.manager.authorization_provider.processor');
    $consumer_plugin_manager = $container->get('plugin.manager.authorization.consumer');
    return new static($entity_manager, $provider_plugin_manager, $consumer_plugin_manager);
  }
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
    $provider_options = $this->getProviderOptions();
    if ($provider_options) {
      if (count($provider_options) == 1) {
        $profile->set('provider', key($provider_options));
      }
      $form['provider'] = array(
        '#type' => 'radios',
        '#title' => $this->t('provider'),
        '#description' => $this->t('Choose a Provider to use for this profile.'),
        '#options' => $provider_options,
        '#default_value' => $profile->getProviderId(),
        '#required' => TRUE,
        '#ajax' => array(
          'callback' => array(get_class($this), 'buildAjaxProviderConfigForm'),
          'wrapper' => 'authorization-profile-provider-config-form',
          'method' => 'replace',
          'effect' => 'fade',
        ),
      );
    }

    $consumer_options = $this->getConsumerOptions();
    if ($consumer_options) {
      if (count($consumer_options) == 1) {
        $profile->set('consumer', key($consumer_options));
      }
      $form['consumer'] = array(
        '#type' => 'radios',
        '#title' => $this->t('consumer'),
        '#description' => $this->t('Choose a Consumer to use for this profile.'),
        '#options' => $consumer_options,
        '#default_value' => $profile->getConsumerId(),
        '#required' => TRUE,
        '#ajax' => array(
          'callback' => array(get_class($this), 'buildAjaxconsumerConfigForm'),
          'wrapper' => 'authorization-profile-consumer-config-form',
          'method' => 'replace',
          'effect' => 'fade',
        ),
      );
    }
    return $form;
  }


  /**
   * Returns all available Provider plugins, as an options list.
   *
   * @return string[]
   *   An associative array mapping Provider plugin IDs to their (HTML-escaped)
   *   labels.
   */
  protected function getProviderOptions() {
    $options = array();
    foreach ($this->getProviderPluginManager()->getDefinitions() as $plugin_id => $plugin_definition) {
      $options[$plugin_id] = Html::escape($plugin_definition['label']);
    }
    return $options;
  }


  /**
   * Returns all available Consumer plugins, as an options list.
   *
   * @return string[]
   *   An associative array mapping Consumer plugin IDs to their (HTML-escaped)
   *   labels.
   */
  protected function getConsumerOptions() {
    $options = array();
    foreach ($this->getConsumerPluginManager()->getDefinitions() as $plugin_id => $plugin_definition) {
      $options[$plugin_id] = Html::escape($plugin_definition['label']);
    }
    return $options;
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
