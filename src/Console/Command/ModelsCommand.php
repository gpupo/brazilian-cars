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

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Gpupo\CommonSdk\Traits\ResourcesTrait;
use Gpupo\Common\Entity\CollectionInterface;

final class ModelsCommand extends AbstractCommand
{
    use ResourcesTrait;

    protected function configure()
    {
        $this
            ->setName('models')
            ->setDescription('Modelos comercializados');

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = $this->getFactory()->factoryManager('main');
        $collection = $manager->getModels($renew = (null === $input->getOption('no-cache')));
        $this->saveResourceToYamlFile('var/data/models.yaml', $collection->toArray());
        $detailedModels = $manager->detailedModels($collection);
        $this->saveResourceToYamlFile('var/data/detailedModels.yaml', $detailedModels->toArray());

        foreach($detailedModels as $brand) {
            $filter = $input->getOption('filter');
            if(!empty($filter)) {
                if (strtolower($filter) !== strtolower($brand->get('name'))) {
                    continue;
                }
            }

            $this->unitBrand($output, $brand);
        }


        $output->writeln('<info>Done</>');
    }


    protected function unitBrand(OutputInterface $output, CollectionInterface $brand): void
    {
        $output->writeln(sprintf('* %s <fg=blue>%s</>', ...array_values($brand->toArray())));
        foreach($brand->get('models') as $model) {
            $output->writeln("\r\t".sprintf('%s) <info>%s</>', ...array_values($model->toArray())));

            foreach($model->get('versions') as $version) {
                $output->writeln("\r\t\t".sprintf('%s <fg=yellow>%s</>', ...array_values($version)));
            }
        }
    }
}
