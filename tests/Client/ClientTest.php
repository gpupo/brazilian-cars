<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/brazilian-cars created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\BrazilianCars\Tests\Client;

use Gpupo\BrazilianCars\Client\Client;
use Gpupo\BrazilianCars\Tests\TestCaseAbstract;

/**
 * @coversNothing
 */
class ClientTest extends TestCaseAbstract
{
    public function dataProviderClient()
    {
        return [[new Client(getenv())]];
    }

    /**
     * @dataProvider dataProviderClient
     */
    public function testBaseUrl(Client $object)
    {
        $this->assertSame('https://foo.bar/api/veiculos', $object->getOptions()->get('base_url'));
    }
}
