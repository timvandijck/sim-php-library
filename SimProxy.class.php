<?php

/**
 * @file
 * Provides a proxy for the Selligent Individual service.
 */

abstract class SimProxy {
  
  // Password to access the API
  private $password = '';

  // Login username to access the API
  private $login = '';

  private $emsecure_name = '';
  private $client = FALSE;

  protected $individualURL = '';
  protected $broadcastURL = '';
  

  // @todo document this function.
  public function __construct($config) {
    if (isset($config['individual_url'])) {
      $this->individualURL = $config['individual_url'];
    } else {
      throw new Exception('Credentials not set.');
    }

    if (isset($config['broadcast_url'])) {
      $this->broadcastURL = $config['broadcast_url'];
    } else {
      throw new Exception('Credentials not set.');
    }

    if (isset($config['login'])) {
      $this->login = $config['login'];
    } else {
      throw new Exception('Credentials not set.');
    }

    if (isset($config['password'])) {
      $this->password = $config['password'];
    } else {
      throw new Exception('Credentials not set.');
    }

    $this->connect();
  }

  // @todo document this function.
  public function connect() {
    try {
      $this->client = new SoapClient($this->getSoapUrl());
      $this->client->__setSoapHeaders($this->getHeader());
    } catch (Exception $e) {
      throw new Exception('Could not connect: ' . $e->getMessage());
    }
  }

  // @todo document this function.
  public function getClient() {
    return $this->client;
  }

  /**
   * Get the Soapheader
   */
  protected function getHeader() {
    return new SoapHeader('http://tempuri.org/', 'AutomationAuthHeader', array('Login' => $this->login, 'Password' => $this->password));
  }

  // @todo document this function.
  protected abstract function getSoapUrl();

  /**
   * Do the Soap call.
   */
  public function call($method, $param = '') {
    if ($this->client) {
      return $this->client->{$method}($param);
    } else {
      throw new Exception(t('SOAP Client unavailable'));
    }
  }
}