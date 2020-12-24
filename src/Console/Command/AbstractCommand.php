<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/brazilian-cars created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\BrazilianCars\Console\Command;

use Gpupo\Common\Entity\CollectionInterface;
use Gpupo\Common\Traits\TableTrait;
use Gpupo\CommonSdk\Console\Command\AbstractCommand as Core;
use Gpupo\CommonSdk\Traits\ResourcesTrait;
use Symfony\Component\Console\Input\InputOption;

abstract class AbstractCommand extends Core
{
    use ResourcesTrait;
    use TableTrait;

    protected function configure()
    {
        $this
            ->addOption(
                'no-cache',
                null,
                InputOption::VALUE_OPTIONAL,
                'Should ignore cache?',
                false
            )
            ->addOption(
                'filter',
                null,
                InputOption::VALUE_OPTIONAL,
                'Name to filter',
                false
            );
    }

    protected function reloadCollection(string $filename): CollectionInterface
    {
        return $this->resourceDecodeSerializedFile($filename);
    }
}
