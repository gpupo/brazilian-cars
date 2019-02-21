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

namespace Gpupo\BrazilianCars\Entity;

use Gpupo\CommonSchema\ORM\EntityRepository\AbstractEntityRepository;

class VehicleRepository extends AbstractEntityRepository
{
    public function findDistinctManufacturers(): array
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $queryBuilder->select('a.manufacturer, a.manufacturer_id');
        $queryBuilder->orderBy('a.manufacturer', 'ASC');

        $res = [];
        foreach ($queryBuilder->distinct()->getQuery()->execute() as $i) {
            $res[] = $i['manufacturer'];
        }

        return $res;
    }

    public function findDistinctFamilies($manufacturer = false): array
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $queryBuilder->select('a.family');
        $queryBuilder->orderBy('a.family', 'ASC');

        if ($manufacturer) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('a.manufacturer', ':manufacturer'))
                ->setParameter('manufacturer', $manufacturer);
        }

        $res = [];
        foreach ($queryBuilder->distinct()->getQuery()->execute() as $i) {
            $res[] = $i['family'];
        }

        return $res;
    }

    public function findDistinctModels($family = false): array
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $queryBuilder->select('a.name');
        $queryBuilder->orderBy('a.name', 'ASC');

        if ($family) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('a.family', ':family'))
                ->setParameter('family', $family);
        }

        $res = [];
        foreach ($queryBuilder->distinct()->getQuery()->execute() as $i) {
            $res[] = $i['name'];
        }

        return $res;
    }
}
