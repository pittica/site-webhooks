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

namespace Pittica\SiteWebhooks\Hooks;

use Pittica\SiteWebhooks\Hook;
use GuzzleHttp\Client;

/**
 * Hook abstract class.
 * 
 * @package Pittica\WebHooks
 * @author  Lucio Benini <info@pittica.com>
 * @since   1.0.0
 */
class GitHubAction extends Hook
{
    /**
     * Sends the hook.
     * 
     * @return int The response status code.
     * 
     * @author Lucio Benini <info@pittica.com>
     * @since  1.0.0
     */
    public function send(): int
    {
        $client = new Client(['base_uri' => 'https://api.github.com']);

        if (!empty($_REQUEST['owner']) && !empty($_REQUEST['repo']) && !empty($_REQUEST['action'])) {
            try {
                $response = $client->request('POST', '/repos/' . $_REQUEST['owner'] . '/' . $_REQUEST['repo'] . '/actions/workflows/' . $_REQUEST['action'] . '/dispatches', [
                    'json' => ['ref' => !empty($_REQUEST['branch']) ? $_REQUEST['branch'] : 'main'],
                    'verify' => false,
                    'auth' => [$this->getKey('username'), $this->getKey('token')]
                ]);
            } catch (\Exception $ex) {
                die(print_r($ex));
            }

            return $response->getStatusCode();
        }

        return 403;
    }

    /**
     * Gets the name of the hook.
     * 
     * @return string The name of the hook.
     * 
     * @author Lucio Benini <info@pittica.com>
     * @since  1.0.0
     */
    protected function getName(): string
    {
        return 'GitHub';
    }
}
