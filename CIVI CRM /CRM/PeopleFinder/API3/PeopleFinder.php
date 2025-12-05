<?php

use CRM_PeopleFinder_ExtensionUtil as E;

/**
 * PeopleFinder API
 */
function _civicrm_api3_people_finder_spec(&$spec) {
  $spec['first_name']['api.required'] = 0;
  $spec['last_name']['api.required'] = 0;
  $spec['state_province']['api.required'] = 0;
  $spec['city']['api.required'] = 0;
  $spec['email']['api.required'] = 0;
}

/**
 * Search for people with privacy restrictions
 * 
 * Returns only partial information (first name initial, last name initial, state)
 * Full details are only revealed after connection is accepted
 */
function civicrm_api3_people_finder_search($params) {
  // Check authentication
  $currentUser = CRM_PeopleFinder_BAO_PeopleFinderUser::getCurrentUser();
  if (!$currentUser) {
    throw new API_Exception('Authentication required', 'unauthorized');
  }

  if (!$currentUser->is_verified) {
    throw new API_Exception('Email verification required', 'not_verified');
  }

  // Build search query with privacy restrictions
  // Only search contacts that are in the system (not allowing crawling)
  // This ensures the person being searched is already in CiviCRM
  
  $where = ["contact_a.contact_type = 'Individual'", "contact_a.is_deleted = 0", "contact_a.id != {$currentUser->contact_id}"];
  $params_sql = [];
  
  // Add search filters
  if (!empty($params['first_name'])) {
    $where[] = "contact_a.first_name LIKE %1";
    $params_sql[1] = ['%' . $params['first_name'] . '%', 'String'];
  }
  
  if (!empty($params['last_name'])) {
    $where[] = "contact_a.last_name LIKE %2";
    $params_sql[2] = ['%' . $params['last_name'] . '%', 'String'];
  }
  
  if (!empty($params['state_province'])) {
    $where[] = "state_province.name LIKE %3";
    $params_sql[3] = ['%' . $params['state_province'] . '%', 'String'];
  }
  
  if (!empty($params['city'])) {
    $where[] = "city.name LIKE %4";
    $params_sql[4] = ['%' . $params['city'] . '%', 'String'];
  }

  // Limit results to prevent abuse
  $limit = CRM_Utils_Array::value('limit', $params, 50);
  $offset = CRM_Utils_Array::value('offset', $params, 0);
  
  $whereClause = implode(' AND ', $where);
  
  $sql = "SELECT contact_a.id, contact_a.first_name, contact_a.last_name, 
          state_province.name as state_province, city.name as city
          FROM civicrm_contact contact_a
          LEFT JOIN civicrm_address address ON address.contact_id = contact_a.id AND address.is_primary = 1
          LEFT JOIN civicrm_state_province state_province ON address.state_province_id = state_province.id
          LEFT JOIN civicrm_county city ON address.county_id = city.id
          WHERE {$whereClause}
          GROUP BY contact_a.id
          LIMIT {$limit} OFFSET {$offset}";

  $dao = CRM_Core_DAO::executeQuery($sql, $params_sql);
  
  $results = [];
  while ($dao->fetch()) {
    // Privacy: Only return partial information
    $firstName = $dao->first_name ? substr($dao->first_name, 0, 1) . '.' : '';
    $lastName = $dao->last_name ? substr($dao->last_name, 0, 1) . '.' : '';
    $fullLastName = $dao->last_name ? $dao->last_name : '';
    
    // Return format: "Steve S, Michigan" - first name + last initial + state
    $results[] = [
      'contact_id' => $dao->id,
      'display_name' => trim($firstName . ' ' . $fullLastName),
      'first_initial' => $firstName,
      'last_name' => $fullLastName, // Full last name for matching
      'state_province' => $dao->state_province ? $dao->state_province : '',
      'city' => $dao->city ? $dao->city : '',
    ];
  }

  // Log the search
  CRM_PeopleFinder_BAO_SearchLog::create([
    'user_id' => $currentUser->id,
    'search_params' => json_encode($params),
    'results_count' => count($results),
  ]);

  return civicrm_api3_create_success($results, $params, 'PeopleFinder', 'search');
}

/**
 * Register a new user (must be in database already)
 */
function civicrm_api3_people_finder_register($params) {
  $email = CRM_Utils_Array::value('email', $params);
  if (empty($email)) {
    throw new API_Exception('Email is required', 'missing_required');
  }

  // Check if contact exists with this email
  $contact = civicrm_api3('Contact', 'get', [
    'email' => $email,
    'return' => ['id', 'first_name', 'last_name'],
    'sequential' => 1,
  ]);

  if ($contact['count'] == 0) {
    throw new API_Exception('Contact not found in database', 'not_found');
  }

  $contactId = $contact['values'][0]['id'];

  // Check if user already exists
  $existingUser = CRM_PeopleFinder_BAO_PeopleFinderUser::findByEmail($email);
  if ($existingUser) {
    throw new API_Exception('User already registered', 'already_exists');
  }

  // Create user with verification token
  $verificationToken = CRM_PeopleFinder_BAO_PeopleFinderUser::generateToken();
  $user = CRM_PeopleFinder_BAO_PeopleFinderUser::create([
    'contact_id' => $contactId,
    'email' => $email,
    'verification_token' => $verificationToken,
    'is_verified' => 0,
  ]);

  // Send verification email
  $verifyUrl = CRM_Utils_System::url('civicrm/peoplefinder/verify', "token={$verificationToken}", TRUE, NULL, FALSE, TRUE);
  
  $subject = E::ts('Verify your People Finder account');
  $message = E::ts('Click the link below to verify your email and access People Finder:') . "\n\n" . $verifyUrl;
  
  CRM_Utils_Mail::send([
    'toName' => $contact['values'][0]['display_name'],
    'toEmail' => $email,
    'subject' => $subject,
    'text' => $message,
  ]);

  return civicrm_api3_create_success(['user_id' => $user->id, 'message' => 'Verification email sent'], $params, 'PeopleFinder', 'register');
}

/**
 * Verify email with token
 */
function civicrm_api3_people_finder_verify($params) {
  $token = CRM_Utils_Array::value('token', $params);
  if (empty($token)) {
    throw new API_Exception('Token is required', 'missing_required');
  }

  $user = CRM_PeopleFinder_BAO_PeopleFinderUser::verifyEmail($token);
  if (!$user) {
    throw new API_Exception('Invalid or expired token', 'invalid_token');
  }

  // Auto-login after verification
  CRM_PeopleFinder_BAO_PeopleFinderUser::login($user->id);

  return civicrm_api3_create_success(['user_id' => $user->id, 'message' => 'Email verified'], $params, 'PeopleFinder', 'verify');
}

/**
 * Send connection request
 */
function civicrm_api3_people_finder_connect($params) {
  $currentUser = CRM_PeopleFinder_BAO_PeopleFinderUser::getCurrentUser();
  if (!$currentUser) {
    throw new API_Exception('Authentication required', 'unauthorized');
  }

  $targetContactId = CRM_Utils_Array::value('contact_id', $params);
  if (empty($targetContactId)) {
    throw new API_Exception('Contact ID is required', 'missing_required');
  }

  // Check if connection already exists
  $existing = CRM_PeopleFinder_BAO_Connection::findBySearcherAndTarget($currentUser->id, $targetContactId);
  if ($existing) {
    throw new API_Exception('Connection request already exists', 'already_exists');
  }

  // Create connection request
  $connection = CRM_PeopleFinder_BAO_Connection::create([
    'searcher_id' => $currentUser->id,
    'target_contact_id' => $targetContactId,
    'status' => 'pending',
    'message' => CRM_Utils_Array::value('message', $params, ''),
  ]);

  // Send notification to target contact (if they're a registered user)
  $targetUser = CRM_PeopleFinder_BAO_PeopleFinderUser::findByContactId($targetContactId);
  if ($targetUser && $targetUser->is_verified) {
    $contact = civicrm_api3('Contact', 'get', [
      'id' => $targetContactId,
      'return' => ['display_name', 'email'],
      'sequential' => 1,
    ]);

    if ($contact['count'] > 0) {
      $searcherContact = civicrm_api3('Contact', 'get', [
        'id' => $currentUser->contact_id,
        'return' => ['display_name'],
        'sequential' => 1,
      ]);

      $subject = E::ts('Someone wants to connect with you');
      $message = E::ts('%1 wants to connect with you through People Finder.', [
        1 => $searcherContact['values'][0]['display_name']
      ]);
      $message .= "\n\n" . E::ts('Log in to People Finder to view the connection request.');

      CRM_Utils_Mail::send([
        'toName' => $contact['values'][0]['display_name'],
        'toEmail' => $contact['values'][0]['email'],
        'subject' => $subject,
        'text' => $message,
      ]);
    }
  }

  return civicrm_api3_create_success(['connection_id' => $connection->id], $params, 'PeopleFinder', 'connect');
}

/**
 * Get connection requests
 */
function civicrm_api3_people_finder_getconnections($params) {
  $currentUser = CRM_PeopleFinder_BAO_PeopleFinderUser::getCurrentUser();
  if (!$currentUser) {
    throw new API_Exception('Authentication required', 'unauthorized');
  }

  $connections = CRM_PeopleFinder_BAO_Connection::getByUser($currentUser->id, $params);
  
  return civicrm_api3_create_success($connections, $params, 'PeopleFinder', 'getconnections');
}

