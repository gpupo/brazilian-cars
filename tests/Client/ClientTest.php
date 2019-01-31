<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/brazilian-cars
 * Created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <https://opensource.gpupo.com/>.
 *
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
