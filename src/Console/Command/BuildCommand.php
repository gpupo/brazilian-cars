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

use DateTime;
use Gpupo\BrazilianCars\Entity\Vehicle;
use Gpupo\BrazilianCars\Entity\VehicleCollection;
use Gpupo\Common\Entity\CollectionInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class BuildCommand extends AbstractCommand
{
    private $manager;

    private $collection;

    protected function configure()
    {
        $this
            ->setName('vehicle:build')
            ->setDescription('Processa os modelos, gerando uma coleção de Vehicle para persistência em banco de dados')
            ->addArgument('filename', InputArgument::REQUIRED, 'A serialized filename path')
            ->addArgument('output-filename', InputArgument::OPTIONAL, 'A serialized filename path to output vehicle collection', 'var/data/vehicleCollection.php-serialized.ser')
            ->addOption(
                'skip-updates',
                null,
                InputOption::VALUE_OPTIONAL,
                'Should ignore updates?',
                false
            )
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
            $brand->set('name', mb_strtoupper($brand->get('name'))); //Uppercase
            $filter = $input->getOption('filter');
            if (!empty($filter)) {
                if (false === strpos($brand->get('name'), mb_strtoupper($filter))) {
                    continue;
                }
            }

            $this->unitBrand($output, $brand);
        }

        $this->persist($input, $output, $this->collection);
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
                $rows[] = [
                    $vehicle->getId(),
                    $vehicle->getManufacturer(),
                    $vehicle->getFamily(),
                    $vehicle->getName(),
                    $vehicle->getModelYear(),
                    $vehicle->getFuelType(),
                    $vehicle->getModelIdentifier(),
                ];
            }
        }
        if (!$output->isQuiet()) {
            $table
                ->setHeaderTitle($vehicle->getManufacturer())
                ->setFooterTitle($vehicle->getManufacturer())
                ->setHeaders(['Id', 'Manufacturer', 'Family', 'Name', 'Year', 'Fuel', 'Model Id'])
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

    protected function persist(InputInterface $input, OutputInterface $output, CollectionInterface $collection)
    {
        $entityManager = app_doctrine_connection();
        $repository = $entityManager->getRepository(Vehicle::class);

        $i = 0;
        $max = 1000;
        $result = [
            'inserted' => 0,
            'updated' => 0,
        ];
        foreach ($collection as $vehicle) {
            $existent = $repository->findOneByObject($vehicle);
            if (empty($existent)) {
                ++$result['inserted'];
                $output->writeln(sprintf('Inserted <bg=blue>%s</> <info>%s</>', $vehicle->getId(), $vehicle->getManufacturer()), OutputInterface::VERBOSITY_VERBOSE);
                $entityManager->merge($vehicle);
                ++$i;
            } else {
                if (false === $input->getOption('skip-updates')) {
                    ++$result['updated'];
                    $existent->setUpdatedAt(new DateTime());
                    $entityManager->persist($existent);
                    ++$i;
                } else {
                    $output->writeln(sprintf('Skipped <info>%s</>', $vehicle->getId()), OutputInterface::VERBOSITY_VERY_VERBOSE);
                }
            }

            if ($max === $i) {
                $i = 0;
                $entityManager->flush();
            }
        }

        $entityManager->flush();
        $output->writeln(sprintf('Inserted <info>%d</> new vehicles and <info>%d</> updates', ...array_values($result)), OutputInterface::VERBOSITY_NORMAL);
    }
}
