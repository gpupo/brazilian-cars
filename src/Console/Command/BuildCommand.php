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

use Gpupo\BrazilianCars\Entity\VehicleCollection;
use Gpupo\Common\Entity\CollectionInterface;
use Gpupo\CommonSdk\Traits\ResourcesTrait;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class BuildCommand extends AbstractCommand
{
    use ResourcesTrait;

    private $manager;

    private $collection;

    protected function configure()
    {
        $this
            ->setName('vehicle:build')
            ->setDescription('Processa os modelos, gerando uma coleção de Vehicle para persistência em banco de dados')
            ->addArgument('filename', InputArgument::REQUIRED, 'A serialized filename path')
            ->addArgument('output-filename', InputArgument::OPTIONAL, 'A serialized filename path to output vehicle collection', 'var/data/vehicleCollection.php-serialized.ser')
            ;

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->collection = new VehicleCollection();
        $filename = $input->getArgument('filename');
        $this->manager = $this->getFactory()->factoryManager('vehicle');
        $collection = $this->resourceDecodeSerializedFile($filename);

        foreach ($collection as $brand) {
            $filter = $input->getOption('filter');
            if (!empty($filter)) {
                if (strtolower($filter) !== strtolower($brand->get('name'))) {
                    continue;
                }
            }

            $this->unitBrand($output, $brand);
        }

        $output->writeln(sprintf('Filename <info>%s</> loaded, <info>%s</> Vehicles', $filename, $this->collection->count()), OutputInterface::VERBOSITY_VERBOSE);
        $this->saveResourceToSerializedFile($input->getArgument('output-filename'), $this->collection);
    }

    protected function unitBrand(OutputInterface $output, CollectionInterface $brand): void
    {
        $table = new Table($output);
        foreach ($brand->get('models') as $model) {
            foreach ($model->get('versions') as $version) {
                $vehicle = $this->manager->createVehicle($brand, $model, $version);

                if (1900 > $vehicle->getModelYear() || 2100 < $vehicle->getModelYear()) {
                    $output->writeln(sprintf('Parse failed:<error>%s</error>', $vehicle->getFullName()), OutputInterface::VERBOSITY_VERBOSE);

                    continue;
                }

                $this->collection->add($vehicle);

                //$this->manager->persist($vehicle);
                $rows[] = [
                    $vehicle->getId(),
                    $vehicle->getFamily(),
                    $vehicle->getName(),
                    $vehicle->getModelYear(),
                    $vehicle->getFuelType(),
                    $vehicle->getModelIdentifier(),
                ];
            }
        }

        $table
            ->setHeaderTitle($vehicle->getManufacturer())
            ->setFooterTitle($vehicle->getManufacturer())
            ->setHeaders(['Id', 'Family', 'Name', 'Year', 'Fuel', 'Model Id'])
            ->setColumnWidths([8, 5, 60, 5, 8, 5])
            ->setRows($rows)
        ;
        $table->render();
        $output->writeln([
            '',
            '',
            '',
        ]);
    }
}
