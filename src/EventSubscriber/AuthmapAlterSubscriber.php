<?php /**
 * @file
 * Contains \Drupal\authorization\EventSubscriber\AuthmapAlterSubscriber.
 */

namespace Drupal\authorization\AuthmapAlterSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\externalauth\Event\ExternalAuthEvents;

class AuthmapAlterSubscriber implements EventSubscriberInterface {

  public function onAuthmapAlter() {

  }

  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents() {
    $events[ExternalAuthEvents::AUTHMAP_ALTER][] = array('onAuthmapAlter');
    return $events;
  }

}
