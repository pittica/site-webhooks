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

use GuzzleHttp\Psr7\ServerRequest;

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
        if (!$this->isPost()) {
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
        if ($_REQUEST['token'] === $this->_config['token'] && !empty($_REQUEST['hook'])) {
            header('Content-type:application/json;charset=utf-8');

            try {
                $hook = Hook::loadHook($_REQUEST['hook']);

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
     * Determines whether the request is POST.
     * 
     * @return bool A value indicating whether the request is POST.
     * @author Lucio Benini <info@pittica.com>
     * @since  1.0.0
     */
    protected function isPost(): bool
    {
        $request = ServerRequest::fromGlobals();
        $params = $request->getServerParams();

        return $params['REQUEST_METHOD'] === 'POST';
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
