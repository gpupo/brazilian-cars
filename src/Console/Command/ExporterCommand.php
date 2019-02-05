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

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ExporterCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('vehicle:exporter')
            ->setDescription('Exporta a coleção de Vehicle para YAML')
            ->addArgument('filename', InputArgument::REQUIRED, 'A serialized filename path')
            ->addArgument('output-filename', InputArgument::REQUIRED, 'A yaml filename path to output')
            ;

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $collection = $this->reloadCollection($input->getArgument('filename'));
        $this->saveResourceToYamlFile($input->getArgument('output-filename'), $collection->toArray());
    }
}
