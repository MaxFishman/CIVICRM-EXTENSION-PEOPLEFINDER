<?php

use CRM_PeopleFinder_ExtensionUtil as E;

/**
 * BAO for PeopleFinder User
 */
class CRM_PeopleFinder_BAO_PeopleFinderUser extends CRM_PeopleFinder_DAO_PeopleFinderUser {

  /**
   * Create or update a user
   */
  public static function create($params) {
    $entityName = 'PeopleFinderUser';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new static();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  }

  /**
   * Find user by email
   */
  public static function findByEmail($email) {
    $dao = new static();
    $dao->email = $email;
    $dao->find(TRUE);
    return $dao->id ? $dao : NULL;
  }

  /**
   * Find user by verification token
   */
  public static function findByVerificationToken($token) {
    $dao = new static();
    $dao->verification_token = $token;
    $dao->find(TRUE);
    return $dao->id ? $dao : NULL;
  }

  /**
   * Find user by invitation token
   */
  public static function findByInvitationToken($token) {
    $dao = new static();
    $dao->invitation_token = $token;
    $dao->find(TRUE);
    return $dao->id ? $dao : NULL;
  }

  /**
   * Generate verification token
   */
  public static function generateToken() {
    return bin2hex(random_bytes(32));
  }

  /**
   * Verify user email
   */
  public static function verifyEmail($token) {
    $user = self::findByVerificationToken($token);
    if ($user) {
      $user->is_verified = 1;
      $user->verification_token = NULL;
      $user->save();
      return $user;
    }
    return FALSE;
  }

  /**
   * Check if user is authenticated
   */
  public static function isAuthenticated() {
    return isset($_SESSION['peoplefinder_user_id']);
  }

  /**
   * Get current user
   */
  public static function getCurrentUser() {
    if (self::isAuthenticated()) {
      $dao = new static();
      $dao->id = $_SESSION['peoplefinder_user_id'];
      $dao->find(TRUE);
      return $dao->id ? $dao : NULL;
    }
    return NULL;
  }

  /**
   * Login user
   */
  public static function login($userId) {
    $_SESSION['peoplefinder_user_id'] = $userId;
    $user = new static();
    $user->id = $userId;
    $user->last_login = date('Y-m-d H:i:s');
    $user->save();
  }

  /**
   * Logout user
   */
  public static function logout() {
    unset($_SESSION['peoplefinder_user_id']);
  }

  /**
   * Find user by contact ID
   */
  public static function findByContactId($contactId) {
    $dao = new static();
    $dao->contact_id = $contactId;
    $dao->find(TRUE);
    return $dao->id ? $dao : NULL;
  }
}

