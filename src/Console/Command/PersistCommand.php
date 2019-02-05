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
use Gpupo\Common\Entity\CollectionInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class PersistCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('vehicle:persist')
            ->setDescription('Persiste a coleção de Vehicle no banco de dados')
            ->addArgument('filename', InputArgument::REQUIRED, 'A serialized filename path')
            ;

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $collection = $this->reloadCollection($filename);
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
                $entityManager->merge($vehicle);
            } else {
                ++$result['updated'];
                $existent->setUpdatedAt(new DateTime());
                $entityManager->persist($existent);
            }

            ++$i;
            if ($max === $i) {
                $i = 0;
                $entityManager->flush();
            }
        }

        $entityManager->flush();
        $output->writeln(sprintf('Inserted <info>%d</> new vehicles and <info>%d</> updates', ...array_values($result)));
    }
}
