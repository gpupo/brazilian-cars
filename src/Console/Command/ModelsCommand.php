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

namespace Gpupo\BrazilianCars\Console\Command;

use Gpupo\CommonSdk\Traits\ResourcesTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ModelsCommand extends AbstractCommand
{
    use ResourcesTrait;

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
    }
}
