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

use Gpupo\BrazilianCars\Entity\Vehicle;
use Gpupo\Common\Entity\CollectionInterface;
use Gpupo\CommonSdk\Traits\ResourcesTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class PersistCommand extends AbstractCommand
{
    use ResourcesTrait;

    protected function configure()
    {
        $this
            ->setName('vehicle:persist')
            ->setDescription('Persiste os modelos no banco de dados')
            ->addArgument('filename', InputArgument::REQUIRED, 'A serialized filename path')
            ;

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getArgument('filename');
        $collection = $this->reloadCollection($input);
        $entityManager = app_doctrine_connection();
        $repository = $entityManager->getRepository(Vehicle::class);

        foreach ($collection as $vehicle) {
            $existent = $repository->findById($vehicle->getId());

            if ($existent) {
                $entityManager->merge($vehicle);
            } else {
                $entityManager->persist($vehicle);
            }
        }

        $entityManager->flush();

        // $output->writeln(sprintf('Filename <info>%s</> loaded, <info>%s</> Vehicles', $filename, $this->collection->count()), OutputInterface::VERBOSITY_VERBOSE);
    }

    private function reloadCollection(InputInterface $input): CollectionInterface
    {
        $filename = $input->getArgument('filename');

        return $this->resourceDecodeSerializedFile($filename);
    }
}
