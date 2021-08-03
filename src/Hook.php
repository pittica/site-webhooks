<?php

/**
 * SiteWebhooks (https://github.com/pittica/site-webhooks)
 * Copyright (c) Pittica S.r.l.s. (https://pittica.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @category  Utilities
 * @package   SiteWebhooks
 * @author    Lucio Benini <info@pittica.com>
 * @copyright 2021 Pittica S.r.l.s. (https://pittica.com)
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 * @link      https://github.com/pittica/site-webhooks
 * @since     1.0.0
 */

namespace Pittica\SiteWebhooks;

use Psr\Http\Message\ResponseInterface;

/**
 * Hook abstract class.
 * 
 * @package Pittica\WebHooks
 * @author  Lucio Benini <info@pittica.com>
 * @since   1.0.0
 */
abstract class Hook
{
    /**
     * The configuration.
     * 
     * @var    array
     * @author Lucio Benini <info@pittica.com>
     * @since  1.0.0
     */
    private $_config = [];

    /**
     * Application constructor.
     * 
     * @author Lucio Benini <info@pittica.com>
     * @since  1.0.0
     */
    public function __construct()
    {
        $config = new Configuration;
        $keys = $config->get('keys');

        if (!empty($keys[$this->getName()])) {
            $this->_config = $keys[$this->getName()];
        }
    }

    /**
     * Loads an hook.
     * 
     * @param $name string The name of the hook.
     * 
     * @return Hook The loaded hook or null.
     * 
     * @author Lucio Benini <info@pittica.com>
     * @since  1.0.0
     */
    public static function loadHook(string $name): Hook
    {
        $class = 'Pittica\\SiteWebhooks\\Hooks\\' . $name;

        if (class_exists($class)) {
            return new $class();
        } else {
            return null;
        }
    }

    /**
     * Sends the hook.
     * 
     * @return int The response status code.
     * 
     * @author Lucio Benini <info@pittica.com>
     * @since  1.0.0
     */
    public abstract function send(): int;

    /**
     * Gets a key from configuration data.
     * 
     * @param $key string The name of the key.
     * 
     * @return array|string|null Key from configuration data.
     * 
     * @author Lucio Benini <info@pittica.com>
     * @since  1.0.0
     */
    protected function getKey(string $key): string
    {
        return !empty($this->_config[$key]) ? $this->_config[$key] : null;
    }

    /**
     * Gets the name of the hook.
     * 
     * @return string The name of the hook.
     * 
     * @author Lucio Benini <info@pittica.com>
     * @since  1.0.0
     */
    protected abstract function getName(): string;
}
