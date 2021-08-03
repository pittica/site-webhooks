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
 * Server.
 * @package Pittica\WebHooks
 * @author Lucio Benini <info@pittica.com>
 * @since 1.0.0
 */
class Server
{
    /**
     * Gets the address of the request.
     * @return string The address of the request.
     * @author Lucio Benini <info@pittica.com>
     * @since 1.0.0
     */
    public function getRemoteAddress(): string
    {
        $request = ServerRequest::fromGlobals();
        $params = $request->getServerParams();

        return $params['REMOTE_ADDR'];
    }

    /**
     * Determines whether the request is POST.
     * @return bool A value indicating whether the request is POST.
     * @author Lucio Benini <info@pittica.com>
     * @since 1.0.0
     */
    public function isPost(): bool
    {
        $request = ServerRequest::fromGlobals();
        $params = $request->getServerParams();

        return $params['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Gets fields from request.
     * @return array The fields from the current request.
     * @author Lucio Benini <info@pittica.com>
     * @since 1.0.0
     */
    public function getFields(): array
    {
        $request = ServerRequest::fromGlobals();
        $body = $request->getParsedBody();

        if (is_array($body)) {
            return $body;
        }

        return [];
    }

    /**
     * Gets a field from request.
     * @param $key string The key of the field.
     * @return string A field from the current request.
     * @author Lucio Benini <info@pittica.com>
     * @since 1.0.0
     */
    public function getField(string $key): string
    {
        $body = $this->getFields();

        if (!empty($body[$key])) {
            return $body[$key];
        }

        return '';
    }
}
