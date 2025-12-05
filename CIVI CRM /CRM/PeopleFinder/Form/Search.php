<?php

use CRM_PeopleFinder_ExtensionUtil as E;

/**
 * People Finder search form
 */
class CRM_PeopleFinder_Form_Search extends CRM_Core_Form {

  public function buildQuickForm() {
    $this->add('text', 'first_name', E::ts('First Name'), ['size' => 30]);
    $this->add('text', 'last_name', E::ts('Last Name'), ['size' => 30]);
    $this->add('text', 'state_province', E::ts('State/Province'), ['size' => 30]);
    $this->add('text', 'city', E::ts('City'), ['size' => 30]);
    
    $this->addButtons([
      [
        'type' => 'submit',
        'name' => E::ts('Search'),
        'isDefault' => TRUE,
      ],
    ]);

    parent::buildQuickForm();
  }

  public function setDefaultValues() {
    return [];
  }

}

