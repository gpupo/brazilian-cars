<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/brazilian-cars created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\BrazilianCars\Tests\Entity;

use Gpupo\BrazilianCars\Entity\VehicleManager;
use Gpupo\Common\Entity\Collection;
use PHPUnit\Framework\TestCase as CoreTestCase;

/**
 * @coversNothing
 */
class VehicleManagerTest extends CoreTestCase
{
    public function testExplodeData()
    {
        $vehicleManager = new vehicleManager();
        $brand = new Collection([
            'name' => 'Citroën',
        ]);
        $model = new Collection([
            'id' => '2841',
            'name' => 'Berlingo MultSpace GLX 1.8i 3p',
        ]);
        $version = [
            'id' => '2841998-1',
            'name' => '1998 Gasolina',
        ];

        $vehicle = $vehicleManager->createVehicle($brand, $model, $version);
        $this->assertSame('Berlingo', $vehicle->getFamily());
        $this->assertSame('Gasolina', $vehicle->getFuelType());
    }
}
