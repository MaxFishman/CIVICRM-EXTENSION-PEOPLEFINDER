<?php

use CRM_PeopleFinder_ExtensionUtil as E;

/**
 * BAO for PeopleFinder Connection
 */
class CRM_PeopleFinder_BAO_Connection extends CRM_PeopleFinder_DAO_Connection {

  /**
   * Create or update a connection
   */
  public static function create($params) {
    $entityName = 'Connection';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new static();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  }

  /**
   * Find connection by searcher and target
   */
  public static function findBySearcherAndTarget($searcherId, $targetContactId) {
    $dao = new static();
    $dao->searcher_id = $searcherId;
    $dao->target_contact_id = $targetContactId;
    $dao->find(TRUE);
    return $dao->id ? $dao : NULL;
  }

  /**
   * Get connections for a user
   */
  public static function getByUser($userId, $params = []) {
    $dao = new static();
    $dao->searcher_id = $userId;
    
    if (!empty($params['status'])) {
      $dao->status = $params['status'];
    }
    
    $dao->orderBy('created_date DESC');
    $dao->find();
    
    $connections = [];
    while ($dao->fetch()) {
      // Get contact information (with privacy restrictions)
      $contact = civicrm_api3('Contact', 'get', [
        'id' => $dao->target_contact_id,
        'return' => ['display_name', 'first_name', 'last_name', 'email'],
        'sequential' => 1,
      ]);

      $connectionData = [
        'id' => $dao->id,
        'contact_id' => $dao->target_contact_id,
        'status' => $dao->status,
        'message' => $dao->message,
        'created_date' => $dao->created_date,
        'response_date' => $dao->response_date,
      ];

      // Only show full contact info if connection is accepted
      if ($dao->status === 'accepted' && $contact['count'] > 0) {
        $connectionData['contact'] = $contact['values'][0];
      } else {
        // Privacy: Only show partial info
        $connectionData['contact'] = [
          'id' => $dao->target_contact_id,
          'display_name' => $contact['values'][0]['first_name'] . ' ' . 
                           substr($contact['values'][0]['last_name'], 0, 1) . '.',
        ];
      }

      $connections[] = $connectionData;
    }

    return $connections;
  }

  /**
   * Accept a connection request
   */
  public static function accept($connectionId) {
    $dao = new static();
    $dao->id = $connectionId;
    $dao->find(TRUE);
    
    if ($dao->id) {
      $dao->status = 'accepted';
      $dao->response_date = date('Y-m-d H:i:s');
      $dao->save();
      return $dao;
    }
    
    return FALSE;
  }

  /**
   * Decline a connection request
   */
  public static function decline($connectionId) {
    $dao = new static();
    $dao->id = $connectionId;
    $dao->find(TRUE);
    
    if ($dao->id) {
      $dao->status = 'declined';
      $dao->response_date = date('Y-m-d H:i:s');
      $dao->save();
      return $dao;
    }
    
    return FALSE;
  }
}

