<?php
  function _authorizations_user_authorizations(&$user, $op, $profile_id, $context) {
    $profile = \Drupal::entityManager()->getStorage('authorization_profile')->load($profile_id);

    $authorizations = array();
    $notifications = array();
    $watchdog_tokens = array('%username' => $user->getDisplayName());

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
      foreach ($consumers as $consumer_type => $consumer) {
        $authorizations[$consumer_type] = array();
      }
      return array($authorizations, $notifications);
    }

    $uid = $user->id();

    /**
     * Determine if user is authenticated using ldap authmap or external auth authmap
     * @TODO we should only load profiles that match the providers in the authmaps.
     * @TODO providers should be able to register as handlers for externalauth 'providers'.
     */
    // External Auth
    // $authmap = new Authmap();
    // $authmap->getAll($uid);
    // $authmap->get($provider_type, $uid);

    // LDAP Authentication (would be empty or not)
    if ( function_exists('ldap_user_get_identifier_from_map') ) {
      $identifier = ldap_user_get_identifier_from_map($uid);
      if ( $identifier ) {
        drupal_set_message('LDAP user');
      }
    }

    return array($authorizations, $notifications);

  }
