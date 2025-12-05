<?php

use CRM_PeopleFinder_ExtensionUtil as E;

/**
 * Admin page for managing People Finder users
 */
class CRM_PeopleFinder_Admin_UserManagement extends CRM_Core_Page {

  public function run() {
    // Check permissions
    if (!CRM_Core_Permission::check('administer CiviCRM')) {
      CRM_Core_Error::fatal(E::ts('You do not have permission to access this page.'));
    }

    // Get all users
    $users = [];
    $dao = CRM_Core_DAO::executeQuery("
      SELECT pf.*, c.display_name, c.email as contact_email
      FROM civicrm_peoplefinder_user pf
      INNER JOIN civicrm_contact c ON pf.contact_id = c.id
      ORDER BY pf.created_date DESC
    ");

    while ($dao->fetch()) {
      $users[] = [
        'id' => $dao->id,
        'contact_id' => $dao->contact_id,
        'display_name' => $dao->display_name,
        'email' => $dao->email,
        'contact_email' => $dao->contact_email,
        'is_verified' => $dao->is_verified,
        'created_date' => $dao->created_date,
        'last_login' => $dao->last_login,
      ];
    }

    $this->assign('users', $users);

    // Handle actions
    if (!empty($_GET['action'])) {
      $action = $_GET['action'];
      $userId = CRM_Utils_Array::value('user_id', $_GET);
      
      if ($action == 'send_invitation' && $userId) {
        $this->sendInvitation($userId);
      } elseif ($action == 'delete' && $userId) {
        $this->deleteUser($userId);
      }
    }

    CRM_Utils_System::setTitle(E::ts('People Finder - User Management'));
    parent::run();
  }

  private function sendInvitation($userId) {
    $user = new CRM_PeopleFinder_DAO_PeopleFinderUser();
    $user->id = $userId;
    $user->find(TRUE);
    
    if ($user->id) {
      if (empty($user->invitation_token)) {
        $user->invitation_token = CRM_PeopleFinder_BAO_PeopleFinderUser::generateToken();
        $user->save();
      }
      
      $inviteUrl = CRM_Utils_System::url('civicrm/peoplefinder/verify', "token={$user->invitation_token}", TRUE, NULL, FALSE, TRUE);
      
      $contact = civicrm_api3('Contact', 'get', [
        'id' => $user->contact_id,
        'return' => ['display_name', 'email'],
        'sequential' => 1,
      ]);

      if ($contact['count'] > 0) {
        $subject = E::ts('Invitation to People Finder');
        $message = E::ts('You have been invited to use People Finder. Click the link below to get started:') . "\n\n" . $inviteUrl;
        
        CRM_Utils_Mail::send([
          'toName' => $contact['values'][0]['display_name'],
          'toEmail' => $contact['values'][0]['email'],
          'subject' => $subject,
          'text' => $message,
        ]);
        
        CRM_Core_Session::setStatus(E::ts('Invitation sent successfully.'), E::ts('Success'), 'success');
      }
    }
    
    CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/peoplefinder/admin', NULL, TRUE));
  }

  private function deleteUser($userId) {
    $user = new CRM_PeopleFinder_DAO_PeopleFinderUser();
    $user->id = $userId;
    $user->delete();
    
    CRM_Core_Session::setStatus(E::ts('User deleted successfully.'), E::ts('Success'), 'success');
    CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/peoplefinder/admin', NULL, TRUE));
  }
}

