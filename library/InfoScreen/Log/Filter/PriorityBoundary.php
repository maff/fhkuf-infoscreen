<?php
/** Zend_Log_Filter_Abstract */
require_once 'Zend/Log/Filter/Abstract.php';

class InfoScreen_Log_Filter_PriorityBoundary extends Zend_Log_Filter_Abstract
{
    /**
     * @var integer
     */
    protected $_lower;

    /**
     * @var integer
     */
    protected $_upper;

    /**
     * Filter logging by $priority. Will accept any log
     * event whose priority value is between $lower and $upper.
     *
     * @param  integer  $lower
     * @param  integer  $upper
     * @throws Zend_Log_Exception
     */
    public function __construct($lower = 0, $upper = 7)
    {
        if (!is_integer($lower) || !is_integer($upper)) {
            require_once 'Zend/Log/Exception.php';
            throw new Zend_Log_Exception('Priority boundaries must be integers');
        }

        $this->_lower = $lower;
        $this->_upper = $upper;
    }

    /**
     * Create a new instance of InfoScreen_Log_Filter_PriorityBoundary
     *
     * @param  array|Zend_Config $config
     * @return InfoScreen_Log_Filter_PriorityBoundary
     * @throws Zend_Log_Exception
     */
    static public function factory($config)
    {
        $config = self::_parseConfig($config);
        $config = array_merge(array(
            'lower' => 0,
            'upper' => 7,
        ), $config);

        // Add support for constants
        if (!is_numeric($config['lower']) && isset($config['lower']) && defined($config['lower'])) {
            $config['lower'] = constant($config['lower']);
        }

        if (!is_numeric($config['upper']) && isset($config['upper']) && defined($config['upper'])) {
            $config['upper'] = constant($config['upper']);
        }

        return new self(
            (int) $config['lower'],
            (int) $config['upper']
        );
    }

    /**
     * Returns TRUE to accept the message, FALSE to block it.
     *
     * @param  array    $event    event data
     * @return boolean            accepted?
     */
    public function accept($event)
    {
        return ($event['priority'] >= $this->_lower && $event['priority'] <= $this->_upper);
    }
}