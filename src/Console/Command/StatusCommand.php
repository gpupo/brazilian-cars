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

final class StatusCommand extends AbstractCommand
{
    private $manager;

    private $collection;

    protected function configure()
    {
        $this
            ->setName('app:status')
            ->setDescription('Identifica status da App')
            ;

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach (app_mysql_credentials() as $k => $v) {
            $output->writeln(sprintf('>> %s: <info>%s</>', $k, $v));
        }

        $entityManager = app_doctrine_connection();

        try {
            $entityManager->getConnection()->connect();
            $output->writeln('<info>Connection OK</>');
        } catch (\Exception $e) {
            $output->writeln('<error>Failed to connect</>');
        }
    }
}
