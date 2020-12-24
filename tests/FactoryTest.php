<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/brazilian-cars created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\BrazilianCars\Tests;

use Gpupo\BrazilianCars\Entity\Vehicle;
use Gpupo\BrazilianCars\Factory;
use Gpupo\CommonSdk\Client\ClientInterface;
use Gpupo\CommonSdk\Tests\FactoryTestAbstract;

/**
 * @coversNothing
 */
class FactoryTest extends FactoryTestAbstract
{
    public $namespace = '\Gpupo\BrazilianCars\\';

    public function getFactory()
    {
        return Factory::getInstance();
    }

    /**
     * Dá acesso ao ``Client``.
     */
    public function testGetClient()
    {
        $factory = new Factory();

        $this->assertInstanceOf(ClientInterface::class, $factory->getClient());
    }

    public function testSetApplicationApiClient()
    {
        $factory = new Factory();

        $factory->setClient([
        ]);

        $this->assertInstanceOf(ClientInterface::class, $factory->getClient());
    }

    public function dataProviderObjetos()
    {
        return [
           [Vehicle::class, 'vehicle'],
       ];
    }
}
