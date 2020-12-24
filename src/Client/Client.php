<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/brazilian-cars created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\BrazilianCars\Client;

use Gpupo\CommonSdk\Client\ClientAbstract;
use Gpupo\CommonSdk\Client\ClientInterface;

class Client extends ClientAbstract implements ClientInterface
{
    const ENDPOINT = ENDPOINT_DOMAIN.'/api/veiculos';

    const CACHE_TTL = 86400 * 30;

    protected function renderAuthorization(): array
    {
        return [
            'Host' => ENDPOINT_DOMAIN,
            'Referer' => 'http://'.ENDPOINT_DOMAIN,
        ];
    }
}
