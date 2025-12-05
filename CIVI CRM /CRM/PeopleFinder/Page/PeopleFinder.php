<?php

use CRM_PeopleFinder_ExtensionUtil as E;

/**
 * People Finder main page
 */
class CRM_PeopleFinder_Page_PeopleFinder extends CRM_Core_Page {

  public function run() {
    // Check if user is authenticated
    $user = CRM_PeopleFinder_BAO_PeopleFinderUser::getCurrentUser();
    
    if (!$user || !$user->is_verified) {
      // Redirect to login/register
      CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/peoplefinder/login', NULL, TRUE));
    }

    // Get user's contact info
    $contact = civicrm_api3('Contact', 'get', [
      'id' => $user->contact_id,
      'return' => ['display_name', 'first_name', 'last_name'],
      'sequential' => 1,
    ]);

    $this->assign('user', [
      'id' => $user->id,
      'email' => $user->email,
      'contact' => $contact['count'] > 0 ? $contact['values'][0] : NULL,
    ]);

    // Get connection requests
    try {
      $connections = civicrm_api3('PeopleFinder', 'getconnections', [
        'status' => 'pending',
      ]);
      $this->assign('pending_connections', $connections['values']);
    } catch (Exception $e) {
      $this->assign('pending_connections', []);
    }

    CRM_Utils_System::setTitle(E::ts('People Finder'));
    parent::run();
  }

}

