<?php

use CRM_PeopleFinder_ExtensionUtil as E;

/**
 * People Finder logout page
 */
class CRM_PeopleFinder_Page_Logout extends CRM_Core_Page {

  public function run() {
    CRM_PeopleFinder_BAO_PeopleFinderUser::logout();
    CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/peoplefinder/login', NULL, TRUE));
  }

}

