<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/brazilian-cars created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
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
(new Dotenv())->load(dirname(__DIR__).'/.env');

if (!defined('ENDPOINT_DOMAIN')) {
    define('ENDPOINT_DOMAIN', $_ENV['ENDPOINT_DOMAIN']);
}

function app_mysql_credentials(): array
{
    return [
        'dbname' => getenv('MYSQL_DATABASE'),
        'user' => getenv('MYSQL_USER'),
        'password' => getenv('MYSQL_PASSWORD'),
        'host' => getenv('DBHOST'),
        'driver' => 'pdo_mysql',
        'charset' => 'utf8mb4',
    ];
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

    return EntityManager::create(app_mysql_credentials(), $config, $evm);
}
