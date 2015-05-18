<?php
/**
 * A Horde_Injector:: based Horde_Kolab_Storage:: factory.
 *
 * PHP version 5
 *
 * @category Horde
 * @package  Core
 * @author   Gunnar Wrobel <wrobel@pardus.de>
 * @license  http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @link     http://pear.horde.org/index.php?package=Core
 */

/**
 * A Horde_Injector:: based Horde_Kolab_Storage:: factory.
 *
 * Copyright 2009-2015 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 *
 * @category Horde
 * @package  Core
 * @author   Gunnar Wrobel <wrobel@pardus.de>
 * @license  http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @link     http://pear.horde.org/index.php?package=Core
 */
class Horde_Core_Factory_KolabStorage extends Horde_Core_Factory_Base
{
    /**
     */
    public function __construct(Horde_Injector $injector)
    {
        parent::__construct($injector);
        $this->_setup();
    }

    /**
     * Setup the machinery to create Horde_Kolab_Session objects.
     *
     * @return NULL
     */
    private function _setup()
    {
        $this->_setupConfiguration();
    }

    /**
     * Provide configuration settings for Horde_Kolab_Session.
     *
     * @return NULL
     */
    private function _setupConfiguration()
    {
        $configuration = array();

        //@todo: Update configuration parameters
        if (!empty($GLOBALS['conf']['imap'])) {
            $configuration = $GLOBALS['conf']['imap'];
        }
        if (!empty($GLOBALS['conf']['kolab']['imap'])) {
            $configuration = $GLOBALS['conf']['kolab']['imap'];
        }
        if (!empty($GLOBALS['conf']['kolab']['storage'])) {
            $configuration = $GLOBALS['conf']['kolab']['storage'];
        }

        $this->_injector->setInstance(
            'Horde_Kolab_Storage_Configuration', $configuration
        );
    }

    /**
     * Return the Horde_Kolab_Storage:: instance.
     *
     * @return Horde_Kolab_Storage The storage handler.
     */
    public function create()
    {
        $configuration = $this->_injector->getInstance('Horde_Kolab_Storage_Configuration');

        $params = array(
            'driver' => 'horde',
            'params' => array(
                'host' => $configuration['server'],
                'username' => $GLOBALS['registry']->getAuth(),
                'password' => $GLOBALS['registry']->getAuthCredential('password'),
                'port'     => $configuration['port'],
                'secure'   => $configuration['secure'],
                'debug'    => isset($configuration['debug']) ? $configuration['debug'] : null,
            ),
            'queries' => array(
                'list' => array(
                    Horde_Kolab_Storage_List_Tools::QUERY_BASE => array(
                        'cache' => true
                    ),
                    Horde_Kolab_Storage_List_Tools::QUERY_ACL => array(
                        'cache' => true
                    ),
                    Horde_Kolab_Storage_List_Tools::QUERY_SHARE => array(
                        'cache' => true
                    ),
                )
            ),
            'queryset' => array(
                'data' => array('queryset' => 'horde'),
            ),
            'logger' => $this->_injector->getInstance('Horde_Log_Logger'),
            'log' => array('debug'),
            'cache' => $this->_injector->getInstance('Horde_Cache'),
        );

        // Check if the history system is enabled
        try {
            $history = $this->_injector->getInstance('Horde_History');
            $params['history'] = $history;
            $params['history_prefix_generator'] = new Horde_Core_Kolab_Storage_HistoryPrefix();
        } catch(Horde_Exception $e) {
        }

        $factory = new Horde_Kolab_Storage_Factory($params);
        return $factory->create();
    }
}
