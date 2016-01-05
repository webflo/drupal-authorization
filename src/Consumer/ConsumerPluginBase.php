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

  public function submitRowForm(array &$form, SubFormState $form_state) {
    $values = $form_state->getValues();
    $consumer_mappings = array();
    foreach ($values as $key => $value) {
      $consumer_mappings[] = $value['consumer_mappings'];
    }
    $form_state->setValue('consumer_mappings', $consumer_mappings);

    parent::submitRowForm($form, $form_state);
  }

}
