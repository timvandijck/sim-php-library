<?php

/**
 * @file
 * Broadcast client implementation for Selligent.
 */
require_once('SimProxy.class.php');

class BroadcastClient extends SimProxy {

  private $xml = '';
  private $campaignId = '';
  private $state = '';

  public function setXml($xml) {
    $this->xml = $xml;
  }

  public function setCampaignId($campaignId) {
    $this->campaignId = $campaignId;
  }

  public function setState($state) {
    $this->state = $state;
  }

  /**
   * Overrides abstract function getSoapUrl
   */
  protected function getSoapUrl() {
    return $this->broadcastURL;
  }

  /**
   * @param xml $xml defines the campaign
   */
  public function createCampaign() {
    if ($this->xml == '') {
      throw new Exception('Not all properties are set for this method.');
    }

    $input = array();
    $input['Xml'] = $this->xml;

    $result = $this->call('CreateCampaign', $xml);
    return $result;
  }

  /**
   * @param int $campaignID the ID of the targeted campaign
   * @param string $newState the new state of the campaign
   */
  public function setCampaignState() {
    if ($this->state == '' || $this->campaignId == '') {
      throw new Exception('Not all properties are set for this method.');
    }

    $input = array();
    $input['CampaignID'] = $this->campaignID;
    $input['NewState'] = $this->newState;

    $result = $this->call('SetCampaignState', $input);
    return $result;
  }

  /**
   * @param xml $xml contains the userdata to process
   */
  public function processUserData() {
    if ($this->xml == '') {
      throw new Exception('Not all properties are set for this method.');
    }

    $input = array();
    $input['xml'] = $this->xml;

    $result = $this->call('ProcessUserData', $xml);
    return $result;
  }
}