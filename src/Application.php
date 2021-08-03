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

/**
 * Application main class.
 * 
 * @package Pittica\WebHooks
 * @author  Lucio Benini <info@pittica.com>
 * @since   1.0.0
 */
class Application
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
     * @throws \Exception Method is not POST.
     * @author Lucio Benini <info@pittica.com>
     * @since  1.0.0
     */
    public function __construct()
    {
        $server = new Server;

        if (!$server->isPost()) {
            http_response_code(200);

            header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
            header('Access-Control-Allow-Headers: Authorization, Content-Type, Accept, Origin');

            exit(0);
        }

        $config = new Configuration;
        $this->_config = $config->get();
    }

    /**
     * Runs the application.
     * 
     * @return void
     * @author Lucio Benini <info@pittica.com>
     * @since  1.0.0
     */
    public function run(): void
    {
        if ($_GET['token'] === $this->_config['token']) {
            header('Content-type:application/json;charset=utf-8');

            try {
                $hook = Hook::loadHook($_GET['hook']);

                if ($hook) {
                    $this->respond($hook->send());
                } else {
                    $this->respond(400);
                }
            } catch (\Exception $e) {
                $this->respond(500);
            }
        } else {
            $this->respond(403);
        }
    }

    /**
     * Prepares and output the JSON response.
     * 
     * @param int $code HTTP code.
     * 
     * @return void
     * @author Lucio Benini <info@pittica.com>
     * @since  1.0.0
     */
    protected function respond(int $code): void
    {
        http_response_code($code);

        die(json_encode([
            'error' => $code < 200 || $code >= 300,
            'code' => $code
        ]));
    }
}
