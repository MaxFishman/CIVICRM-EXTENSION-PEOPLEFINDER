<?php

use CRM_PeopleFinder_ExtensionUtil as E;

/**
 * People Finder email verification page
 */
class CRM_PeopleFinder_Page_Verify extends CRM_Core_Page {

  public function run() {
    $token = CRM_Utils_Array::value('token', $_GET);
    
    if (empty($token)) {
      $this->assign('message', E::ts('Invalid verification link.'));
      $this->assign('message_type', 'error');
    } else {
      try {
        $result = civicrm_api3('PeopleFinder', 'verify', [
          'token' => $token,
        ]);
        
        $this->assign('message', E::ts('Email verified successfully! You can now use People Finder.'));
        $this->assign('message_type', 'success');
        $this->assign('redirect_url', CRM_Utils_System::url('civicrm/peoplefinder', NULL, TRUE));
      } catch (Exception $e) {
        $this->assign('message', $e->getMessage());
        $this->assign('message_type', 'error');
      }
    }

    CRM_Utils_System::setTitle(E::ts('Email Verification'));
    parent::run();
  }

}

