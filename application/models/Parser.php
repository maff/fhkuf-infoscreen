<?php
class InfoScreen_Model_Parser
{
    protected $_date;
    protected $_data;

    public function  __construct($date)
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
        $url = InfoScreen_Config::getInstance()->infoscreen->url;
        $url .= '?STGID=' . InfoScreen_Config::getInstance()->infoscreen->stgid;
        $url .= '&DATUM=' . $this->_date;

        return $url;
    }

    public function getData()
    {
        if($this->_data === null) {
            $this->load();
        }

        return $this->_data;
    }

    public function load()
    {
        $dom = new simple_html_dom();
        $dom->load(file_get_contents($this->getUrl()));

        $this->_data = array();
        foreach($dom->find('div.appointment') as $element) {
            $htmldata = array();
            $htmldata['date'] = $this->_date;
            $htmldata['startTime'] = @$element->find('td.appointmentDate', 0)->plaintext;
            $htmldata['endTime'] = @$element->find('td.appointmentDate', 1)->plaintext;
            $htmldata['course'] = @$element->find('td.appointmentDate', 2)->plaintext;
            $htmldata['group'] = @$element->find('td.appointmentDate', 3)->plaintext;
            $htmldata['description'] = @$element->find('span.appointmentText', 0)->plaintext;
            $htmldata['lector'] = @$element->find('span.appointmentLektor', 0)->plaintext;
            $htmldata['room'] = @$element->find('td.appointmentRaum > div', 0)->plaintext;
            $htmldata['info'] = @$element->find('div.appointmentInfo span', 0)->plaintext;

            $this->_data[] = $htmldata;
        }
    }
}