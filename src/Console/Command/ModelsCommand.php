<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/brazilian-cars created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\BrazilianCars\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ModelsCommand extends AbstractCommand
{
    private $manager;

    protected function configure()
    {
        $this
            ->setName('vehicle:models')
            ->setDescription('Atualiza o cache dos modelos comercializados no Brasil')
            ->addArgument('filename', InputArgument::REQUIRED, 'A serialized filename path')
            ;

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getArgument('filename');
        $this->manager = $this->getFactory()->factoryManager('vehicle');
        $collection = $this->manager->getModels($renew = (null === $input->getOption('no-cache')));
        $detailedModels = $this->manager->detailedModels($collection);
        $this->saveResourceToSerializedFile($filename, $detailedModels);
        $this->displayTableResults($output, $collection);
        $output->writeln(sprintf('Filename <info>%d</> saved', $filename));
        $output->writeln('<info>Done</>');

        return 0;
    }
}
