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

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Gpupo\CommonSchema\Normalizers\DoctrineTypesNormalizer;
use Symfony\Component\Dotenv\Dotenv;

if (!class_exists('\Gpupo\Common\Console\Application')) {
    require __DIR__.'/../vendor/autoload.php';
}

if (!class_exists(Dotenv::class)) {
    throw new RuntimeException('Please run "composer require symfony/dotenv" to load the ".env" files configuring the application.');
}

// load all the .env files
(new Dotenv())->loadEnv(dirname(__DIR__).'/.env');

if (!defined('ENDPOINT_DOMAIN')) {
    define('ENDPOINT_DOMAIN', getenv('ENDPOINT_DOMAIN'));
}

function app_doctrine_connection(): EntityManager
{
    DoctrineTypesNormalizer::overrideTypes();
    $evm = new \Doctrine\Common\EventManager();
    $cache = new \Doctrine\Common\Cache\ArrayCache();
    $annotationReader = new \Doctrine\Common\Annotations\AnnotationReader();
    $cachedAnnotationReader = new \Doctrine\Common\Annotations\CachedReader($annotationReader, $cache);
    $driverChain = new \Doctrine\ORM\Mapping\Driver\DriverChain();
    \Gedmo\DoctrineExtensions::registerAbstractMappingIntoDriverChainORM($driverChain, $cachedAnnotationReader);
    $annotationDriver = new \Doctrine\ORM\Mapping\Driver\AnnotationDriver($cachedAnnotationReader, [__DIR__.'/../src']);
    $driverChain->addDriver($annotationDriver, 'Entity');

    // general ORM configuration
    $config = new \Doctrine\ORM\Configuration();
    $config->setProxyDir(sys_get_temp_dir());
    $config->setProxyNamespace('Proxy');
    $config->setAutoGenerateProxyClasses(false); // this can be based on production config.
    // register metadata driver
    $config->setMetadataDriverImpl($driverChain);
    // use our already initialized cache driver
    $config->setMetadataCacheImpl($cache);
    $config->setQueryCacheImpl($cache);

    // timestampable
    $timestampableListener = new \Gedmo\Timestampable\TimestampableListener();
    $timestampableListener->setAnnotationReader($cachedAnnotationReader);
    $evm->addEventSubscriber($timestampableListener);
    $config = Setup::createAnnotationMetadataConfiguration([__DIR__.'/../src/Entity/'], true, null, null, false);

    $connectionParams = [
        'dbname' => 'app',
        'user' => 'app_db_user',
        'password' => 'app8as3',
        'host' => getenv('dbhost'),
        'driver' => 'pdo_mysql',
        'charset' => 'utf8mb4',
    ];

    return EntityManager::create($connectionParams, $config, $evm);
}
