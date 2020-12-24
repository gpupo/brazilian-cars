<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/brazilian-cars created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\BrazilianCars;

use Gpupo\BrazilianCars\Client\Client;
use Gpupo\CommonSdk\Entity\GenericManager;
use Gpupo\CommonSdk\FactoryAbstract;

class Factory extends FactoryAbstract
{
    protected $name = 'brazilian-cars';

    public function setClient(?array $clientOptions = [])
    {
        $this->client = new Client($clientOptions, $this->getLogger(), $this->getSimpleCache());
    }

    public function getNamespace()
    {
        return '\\'.__NAMESPACE__.'\Entity\\';
    }

    protected function getSchema(): array
    {
        return [
            'generic' => [
                'manager' => GenericManager::class,
            ],
            'main' => [
                'manager' => Entity\MainManager::class,
            ],
            'vehicle' => [
                'manager' => Entity\VehicleManager::class,
                'class' => Entity\Vehicle::class,
            ],
        ];
    }
}
