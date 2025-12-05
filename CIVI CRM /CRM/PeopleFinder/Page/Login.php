<?php

use CRM_PeopleFinder_ExtensionUtil as E;

/**
 * People Finder login/register page
 */
class CRM_PeopleFinder_Page_Login extends CRM_Core_Page {

  public function run() {
    // Check if already logged in
    $user = CRM_PeopleFinder_BAO_PeopleFinderUser::getCurrentUser();
    if ($user && $user->is_verified) {
      CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/peoplefinder', NULL, TRUE));
    }

    // Handle form submission
    if (!empty($_POST['email'])) {
      $email = CRM_Utils_Array::value('email', $_POST);
      
      try {
        $result = civicrm_api3('PeopleFinder', 'register', [
          'email' => $email,
        ]);
        
        $this->assign('message', E::ts('Verification email sent! Please check your inbox.'));
        $this->assign('message_type', 'success');
      } catch (Exception $e) {
        $this->assign('message', $e->getMessage());
        $this->assign('message_type', 'error');
      }
    }

    CRM_Utils_System::setTitle(E::ts('People Finder - Login'));
    parent::run();
  }

}

