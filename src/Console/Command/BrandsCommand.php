<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/brazilian-cars created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\BrazilianCars\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class BrandsCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('vehicle:brands')
            ->setDescription('Lista de marcas comercializadas');

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = $this->getFactory()->factoryManager('main');
        $collection = $manager->getBrands($renew = (null === $input->getOption('no-cache')));
        $this->displayTableResults($output, $collection);

        return 0;
    }
}
