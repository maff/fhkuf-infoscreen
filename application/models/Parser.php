<?php
class InfoScreen_Model_Parser
{
    protected $_date;
    protected $_data;

    /**
     * @var Zend_Http_CookieJar
     */
    protected $_cookieJar;

    public function __construct($date = null)
    {
        $this->_date = InfoScreen_Date::parse($date);
    }

    /**
     * URL to parse
     *
     * @return string
     */
    public function getUrl()
    {
        return InfoScreen_Config::getInstance()->datasource->endpoint;
    }

    /**
     * @return Zend_Http_Client
     */
    public function getHttpClient()
    {
        $client = new Zend_Http_Client();
        $client->setConfig(array(
            'adapter' => 'Zend_Http_Client_Adapter_Curl',
            'curloptions' => array(
                CURLOPT_SSL_VERIFYPEER => false
            )
        ));

        if ($this->_cookieJar === null) {
            // first request gets session cookie
            $cookieClient = clone $client;
            $cookieClient->setCookieJar();
            $cookieClient->setUri($this->getUrl());
            $cookieClient->request();

            $this->_cookieJar = $cookieClient->getCookieJar();
        }

        $client->setCookieJar($this->_cookieJar);
        return $client;
    }

    public function getData()
    {
        if ($this->_data === null) {
            $this->load();
        }

        return $this->_data;
    }

    protected function _getFormData()
    {
        $client = $this->getHttpClient();
        $client->setUri($this->getUrl());

        $dom = new Zend_Dom_Query($client->request()->getBody());
        $domelement = $dom->query('form.iceFrm')->current();

        $form = new simple_html_dom();
        $form->load($this->_getDOMElementInnerHtml($domelement));

        $params = array();

        $neededParams = array(
            'A6096:infoscreen',
            'A6096:infoscreen:date1',
            'A6096:infoscreen:date2',
            'A6096:infoscreen:gruppen',
            'A6096:infoscreen:lektor',
            'A6096:infoscreen:jahrgaenge',
            'A6096:infoscreen:lv_typ',
            'A6096:infoscreen:date1_cc',
            'A6096:infoscreen:date2_cc',
            'A6096:infoscreen:j_idA6096:infoscreen:date1sp',
            'A6096:infoscreen:j_idA6096:infoscreen:date2sp',
            'A6096:infoscreen:j_idcl',
            'A6096:infoscreen:senden',
            'ice.view',
            'ice.window',
            'icefacesCssUpdates',
            'javax.faces.ViewState',
            'javax.faces.encodedURL',
        );

        foreach ($form->find('input') as $e) {
            if (in_array($e->name, $neededParams)) {
                $value = $e->value;
                if (!$value) {
                    $value = '';
                }

                $params[$e->name] = $value;
            }
        }

        foreach ($form->find('select') as $e) {
            if (in_array($e->name, $neededParams)) {
                $value = '';
                foreach ($e->find('option') as $o) {
                    if (isset($o->selected) && $o->selected == true) {
                        $value = $o->value;
                    }
                }

                if ($value == '') {
                    $value = $e->find('option', 0)->value;
                }

                $params[$e->name] = $value;
            }
        }

        $data = array(
            'action' => $domelement->getAttribute('action'),
            'params' => $params,
        );

        return $data;
    }

    public function load()
    {
        $client = $this->getHttpClient();
        $client->setMethod(Zend_Http_Client::POST);

        $formData = $this->_getFormData();
        $formData['params']['A6096:infoscreen:date1'] = $this->_date;

        $client->setParameterPost($formData['params']);

        $client->setUri($formData['action']);
        $response = $client->request();

        $this->_data = array();
        $dom = new Zend_Dom_Query($response->getBody());

        /* @var $element DOMElement */
        foreach ($dom->query('div.kurstable table tbody tr') as $domelement) {
            $htmldata = array();
            $element = new simple_html_dom();
            $element->load($this->_getDOMElementInnerHtml($domelement));

            $htmldata['date'] = trim(@$element->find('td.coldatum', 0)->plaintext);
            $htmldata['startTime'] = trim(@$element->find('td.colvon', 0)->plaintext);
            $htmldata['endTime'] = trim(@$element->find('td.colbis', 0)->plaintext);
            $htmldata['course'] = trim(@$element->find('td.coljg', 0)->plaintext);
            $htmldata['group'] = trim(@$element->find('td.colgrp', 0)->plaintext);
            $htmldata['description'] = trim(@$element->find('td.colbez', 0)->plaintext);
            $htmldata['lector'] = trim(@$element->find('td.collek', 0)->plaintext);
            $htmldata['room'] = trim(@$element->find('td.colraum', 0)->plaintext);
            $htmldata['info'] = trim(@$element->find('td.colinfo', 0)->plaintext);

            $this->_data[] = $htmldata;
        }
    }

    protected function _getDOMElementInnerHtml(DOMElement $element)
    {
        $doc = new DOMDocument();
        foreach ($element->childNodes as $child) {
            $doc->appendChild($doc->importNode($child, true));
        }

        return $doc->saveHTML();
    }

}