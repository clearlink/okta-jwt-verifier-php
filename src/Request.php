<?php
/******************************************************************************
 * Copyright 2017 Okta, Inc.                                                  *
 *                                                                            *
 * Licensed under the Apache License, Version 2.0 (the "License");            *
 * you may not use this file except in compliance with the License.           *
 * You may obtain a copy of the License at                                    *
 *                                                                            *
 *      http://www.apache.org/licenses/LICENSE-2.0                            *
 *                                                                            *
 * Unless required by applicable law or agreed to in writing, software        *
 * distributed under the License is distributed on an "AS IS" BASIS,          *
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.   *
 * See the License for the specific language governing permissions and        *
 * limitations under the License.                                             *
 ******************************************************************************/

namespace Okta\JwtVerifier;

use GuzzleHttp\Client;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class Request
{
    protected $client;

    /**
     * The UriInterface of the request to be made.
     *
     * @var UriInterface
     */
    protected $url;

    /**
     * The set of query parameters to send with request.
     *
     * @var array
     */
    protected $query = [];

    public function __construct(ClientInterface $httpClient = null)
    {
        $this->client = $httpClient ?: new Client();
    }

    public function setUrl($url): Request
    {
        $this->url = $url;
        return $this;
    }

    public function withQuery($key, $value = null): Request
    {
        $this->query[$key] = $value;

        return $this;
    }

    public function get(): ResponseInterface
    {
        return $this->request('GET');
    }

    protected function request($method): ResponseInterface
    {
        return $this->client->request($method, $this->url, [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'query'   => $this->query,
        ]);
    }
}
