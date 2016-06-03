<?php
  function _authorizations_user_authorizations(&$user, $op, $profile_id, $context) {
    $profile = \Drupal::entityManager()->getStorage('authorization_profile')->load($profile_id);

    $authorizations = array();
    $notifications = array();
    $watchdog_tokens = array('%username' => $user->getDisplayName());

    $authentication_providers = array();

    // @TODO Move this into a setting as per ldap_authorization.
    $detailed_watchdog_log = TRUE;

    /**
     * User 1 not used in ldap authorization. This is a design decision.
     * @TODO have this configurable per provider or per profile.
     */
    if ( $user->id() == 1 ) {
      if ($detailed_watchdog_log) {
      \Drupal::logger('authorization')->debug('%username : authorization not applied to user 1', $watchdog_tokens);
      }
      $notifications['all'] = AUTHORIZATION_NOT_APPLY_USER_1;
      return array($authorizations, $notifications);
    }

    $uid = $user->id();
    $provider = $profile->getProvider();
    $consumer = $profile->getConsumer();

    if ( $profile->checkConditions($user, $op) ) {
      $user_save = TRUE;
      // Apply the profile map to the user
      list($authorizations, $notifications) = $profile->grantsAndRevokes($op, $user, $user_auth_data, $identifier, $user_save);
    }

    return array($authorizations, $notifications);

  }
