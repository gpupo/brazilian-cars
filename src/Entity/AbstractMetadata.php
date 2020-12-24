<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/brazilian-cars created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\BrazilianCars\Entity;

use Gpupo\CommonSdk\Entity\Metadata\MetadataContainerAbstract;
use Gpupo\CommonSdk\Traits\FinderTrait;

abstract class AbstractMetadata extends MetadataContainerAbstract
{
    use FinderTrait;
}
