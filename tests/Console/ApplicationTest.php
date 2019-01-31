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

namespace Gpupo\BrazilianCars\Tests\Console;

use Gpupo\BrazilianCars\Console\Application;
use Gpupo\BrazilianCars\Tests\TestCaseAbstract;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @coversNothing
 */
class ApplicationTest extends TestCaseAbstract
{
    public function testAppendCommand()
    {
        $output = new NullOutput();
        $input = new StringInput('help');
        $app = new Application('brazilian-cars');
        $app->init('Gpupo\BrazilianCars\Console', \dirname(\dirname(__DIR__)), $output, $input);

        $command = $app->find('setup:checklist');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $string = trim($commandTester->getDisplay());
        $data = explode("\n", $string);
        $this->assertSame('Current config:', current($data));
    }
}
