<?php
namespace SimClient;

/**
 * @file
 * Implements the Selligent Individual API.
 */

require_once('SimProxy.class.php');

/**
 * This class is used to query the Selligent Individual API.
 * Set the properties for your query and execute the API function you need.
 */
class IndividualClient extends SimProxy{
  /**
   * The list you want to query.
   * @var int
   */
  private $lid = '';

  /**
   * The filters you like to apply.
   * @var array
   */
  private $filter = array();

  /**
   * The maximum amount of values you like to be returned.
   * Only applies to the 'ByFilter'-functions.
   * @var int
   */
  private $maxcount = 10;

  /**
   * A constraint you like to add (see Selligent documentation).
   * Only applies to the 'ByConstraint'-functions.
   * @var string
   */
  private $constraint = '';

  /**
   * The user ID you would like to retrieve.
   * Only applies to the 'ById'-function.
   * @var int
   */
  private $uid = '';

  /**
   * The properties you like to set.
   * @var array
   */
  private $properties = array();

  /**
   * The campaign gate you like to use (see Selligent documentation).
   * @var int
   */
  private $gate = '';

  /**
   * The XML you want to use.
   * @var string (xml)
   */
  private $xml = '';

  private $proxy;

  public function setList($lid) {
    $this->lid = $lid;
    return $this;
  }

  public function setMaxcount($maxcount) {
    $this->maxcount = $maxcount;
    return $this;
  }

  public function setConstraint($constraint) {
    $this->constraint = $constraint;
    return $this;
  }

  public function setUserId($uid) {
    $this->uid = $uid;
    return $this;
  }

  public function setGate($gate) {
    $this->gate = $gate;
    return $this;
  }

  public function setXml($xml) {
    $this->xml = $xml;
    return $this;
  }

  public function addProperty($key, $value) {
    $this->properties[] = array('Key' => $key, 'Value' => $value);
    return $this;
  }

  public function addFilter($key, $value) {
    $this->filter[] = array('Key' => $key, 'Value' => $value);
    return $this;
  }

  /**
   * @return ResultIDs Array containing the users retrieved
   * @return ErrorStr Error description (only when process fails)
   */
  public function getUsersByFilter() {
    if ($this->lid == '') throw new \Exception('Not all properties are set for this method.');

    $input = array();
    $input['List'] = $this->lid;
    $input['Filter'] = $this->filter;
    $input['MaxCount'] = $this->maxcount;

    $result = $this->call('GetUsersByFilter', $input);

    if (isset($result->ErrorStr) && $result->ErrorStr != 'No user found' && $result->ErrorStr != '') {
      throw new \Exception($result->ErrorStr);
    }

    if (isset($result->ResultIDs->int)) {
      $result = $result->ResultIDs->int;
    }
    else {
      $result = array();
    }

    return $result;
  }

  /**
   * @return ResultSet Array containing the user information
   * @return ErrorStr Error description (only when process fails)
   */
  public function getUserByFilter() {
    if ($this->lid == '') throw new \Exception('Not all properties are set for this method.');

    $input = array();
    $input['List'] = $this->lid;
    $input['Filter'] = $this->filter;

    $result = $this->call('GetUserByFilter', $input);

    if (isset($result->ErrorStr) && $result->ErrorStr != 'No user found' && $result->ErrorStr != '') {
      throw new \Exception($result->ErrorStr);
    }

    if (isset($result->ErrorStr) && $result->ErrorStr == 'No user found') {
      return array();
    } else {
      if ($result->ResultSet)
        $result = $this->fetchUserResultSet($result->ResultSet->Property);

      return $result;
    }
  }

  /**
   * @return ResultIDs Array containing the users retrieved
   * @return ErrorStr Error description (only when process fails)
   */
  public function getUsersByConstraint() {
    if ($this->lid == '') throw new \Exception('Not all properties are set for this method.');

    $input = array();
    $input['List'] = $this->lid;
    $input['Constraint'] = $this->constraint;
    $input['MaxCount'] = $this->maxcount;

    $result = $this->call('GetUsersByConstraint', $input);

    if (isset($result->ErrorStr) && $result->ErrorStr != 'No user found' && $result->ErrorStr != '') {
      throw new \Exception($result->ErrorStr);
    }

    if (isset($result->ResultIDs->int)) {
      $result = $result->ResultIDs->int;
    }
    else {
      $result = array();
    }

    return $result;
  }

  /**
   * @return ResultSet Array containing the user information
   * @return ErrorStr Error description (only when process fails)
   */
  public function getUserByConstraint() {
    if ($this->lid == '') throw new \Exception('Not all properties are set for this method.');

    $input = array();
    $input['List'] = $this->lid;
    $input['Constraint'] = $this->constraint;

    $result = $this->call('GetUserByConstraint', $input);

    if (isset($result->ErrorStr) && $result->ErrorStr != 'No user found' && $result->ErrorStr != '') {
      throw new \Exception($result->ErrorStr);
    }

    if ($result) {
      $result = $this->fetchUserResultSet($result->ResultSet->Property);
    } else {
      $result = array();
    }

    return $result;
  }

  /**
   * @return ResultSet Array containing the user information
   * @return ErrorStr Error description (only when process fails)
   */
  public function getUserById() {
    if ($this->lid == '' || $this->uid == '') throw new \Exception('Not all properties are set for this method.');

    $input = array();
    $input['List'] = $this->lid;
    $input['UserID'] = $this->uid;

    $result = $this->call('GetUserById', $input);

    if (isset($result->ErrorStr) && $result->ErrorStr != 'No user found' && $result->ErrorStr != '') {
      throw new \Exception($result->ErrorStr);
    }

    if ($result) {
      $result = $this->fetchUserResultSet($result->ResultSet->Property);
    } else {
      $result = array();
    }

    return $result;
  }

  /**
   * @return ID ID of the newly created user ErrorStr
   * @return Error description (only when process fails)
   */
  public function createUser() {
    if ($this->lid == '' || sizeof($this->properties) == 0) throw new \Exception('Not all properties are set for this method.');

    $input['List'] = $this->lid;
    $input['Changes'] = $this->properties;

    $result = $this->call('CreateUser', $input);
    $result = $result->ID;

    return $result;
  }

  /**
   * @return ID ID of the newly created user ErrorStr
   * @return Error description (only when process fails)
   */
  public function updateUser() {
    if ($this->lid == '' || sizeof($this->properties) == 0 || $this->uid == '') throw new \Exception('Not all properties are set for this method.');

    $input['List'] = $this->lid;
    $input['Changes'] = $this->properties;
    $input['UserID'] = $this->uid;
    $result = $this->call('UpdateUser', $input);

    return $result;
  }

  /**
   * @return HashCode Hashcode used to access a module by doing a HTTP call on the SIM WebAgent
   * @return ErrorStr Error description (only when process fails)
   */
  public function retrieveHashForUser() {
    if ($this->lid == '' || $this->gate == '' || $this->uid == '') throw new \Exception('Not all properties are set for this method.');

    $input['GateName'] = $this->gate;
    $input['List'] = $this->lid;
    $input['UserID'] = $this->uid;

    $result = $this->call('RetrieveHashForUser', $input);
    $result = $result->HashCode;

    return $result;
  }

  /**
   * @return ErrorStr Error description (only when process fails)
   */
  public function triggerCampaign() {
    if ($this->gate == '' || sizeof($this->properties) == 0) throw new \Exception('Not all properties are set for this method.');

    $input['GateName'] = $this->gate;
    $input['InputData'] = $this->properties;

    $result = $this->call('TriggerCampaign', $input);
    return $result;
  }

  /**
   * @return ErrorStr Error description (only when process fails)
   */
  public function triggerCampaignByXML() {
    if ($this->lid == '' || $this->gate == '' || $this->uid == '' || sizeof($this->properties) == 0) \Exception('Not all properties are set for this method.');

    $input['GateName'] = $this->gate;
    $input['Xml'] = $this->data;

    $result = $this->call('TriggerCampaignByXml', $input);
    return $result;
  }

  public function triggerCampaignForUser() {
    if ($this->lid == '' || $this->gate == '' || $this->uid == '' || sizeof($this->properties) == 0) throw new \Exception('Not all properties are set for this method.');

    $input['GateName'] = $this->gate;
    $input['List'] = $this->lid;
    $input['InputData'] = $this->properties;
    $input['UserID'] = $this->uid;

    $result = $this->call('TriggerCampaignForUser', $input);
    return $result;
  }

  /**
   * @return string the current system status
   */
  public function getSystemStatus() {
    $result = $this->call('GetSystemStatus', NULL);

    if (isset($result->GetSystemStatusResult)) {
      return $result->GetSystemStatusResult;
    } else {
      return 'unable to retrieve status';
    }
  }

  /**
   * Overrides abstract function getSoapUrl
   */
  protected function getSoapUrl() {
    return $this->individualURL;
  }

  /**
   * Helper function to fetch the Result URL
   * @param array $resultSet
   */
  private function fetchUserResultSet($resultSet) {
    $return = array();

    foreach ($resultSet as $result) {
      $return[$result->Key] = $result->Value;
    }

    return $return;
  }
}
