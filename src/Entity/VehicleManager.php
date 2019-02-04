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

use Gpupo\Common\Entity\Collection;
use Gpupo\Common\Entity\CollectionInterface;

class VehicleManager extends MainManager
{
    public function getModels(bool $renew = false): CollectionInterface
    {
        $list = [];
        foreach ($this->getBrands() as $brand) {
            $brand['models'] = $this->getModelsWithBrand($brand, $renew);
            $list[] = $this->factoryCollection($brand);
        }

        return $this->factoryCollection($list);
    }

    public function detailedModels(CollectionInterface $brands)
    {
        $list = new Collection();
        foreach ($brands as $brand) {
            $modelCollection = new Collection();
            foreach ($brand->get('models') as $model) {
                $model['versions'] = $this->getVersionsWithBrandAndModel($brand, $model);
                $modelCollection->add($this->factoryCollection($model));
            }

            $brand->set('models', $modelCollection);
            $list->add($brand);
        }

        return $list;
    }

    public function createVehicle(CollectionInterface $brand, CollectionInterface $model, $version): Vehicle
    {
        $vehicle = new Vehicle();
        $name = $model['name'];
        $family = current(explode(' ', $name));
        $vehicle->setName($name);
        $vehicle->setFamily($family);
        $explodedId = explode('-', $version['id']);
        $explodedName = explode(' ', $version['name']);
        $vehicle->setId((int) sprintf('%s%s%s', $model['id'], current($explodedId), end($explodedId)));
        $vehicle->setModelYear((int) current($explodedName));
        $vehicle->setFuelType((string) end($explodedName));
        $vehicle->setCode(sprintf('%s/%s/%s', $brand['id'], $model['id'], $version['id']));
        $vehicle->setModelIdentifier($model['id']);
        $vehicle->setManufacturer(strtoupper($brand['name']));
        $vehicle->setManufacturerId($brand['id']);

        return $vehicle;
    }

    protected function getModelsWithBrand(array $brand, bool $renew = false): CollectionInterface
    {
        $list = [];
        foreach ($brand['type'] as $type) {
            $models = $this->getModelsWithBrandAndType($brand['id'], $type['id'], $renew);

            $list = array_merge($list, $models->toArray());
        }

        return $this->factoryCollection($list);
    }

    protected function getModelsWithBrandAndType(int $brand, int $type = 1, bool $renew = false): CollectionInterface
    {
        $body = [
            'codigoTabelaReferencia' => $this->getCurrentListId(),
            'codigoTipoVeiculo' => $type,
            'codigoMarca' => $brand,
        ];

        $lambda = function ($collection) use ($type) {
            $array = [];
            foreach ($collection->get('Modelos') as $model) {
                $array[] = [
                    'id' => $model['Value'],
                    'name' => $model['Label'],
                    'type_id' => $type,
                ];
            }

            return new Collection($array);
        };

        $data = $this->requestWithCache([
            'POST',
            '/ConsultarModelos',
        ], sprintf('models-brand%s-type%s', $brand, $type), json_encode($body), $renew, $lambda);

        return $data;
    }

    protected function getVersionsWithBrandAndModel(CollectionInterface $brand, array $model, bool $renew = false): CollectionInterface
    {
        $parameters = [
            'codigoTabelaReferencia' => $this->getCurrentListId(),
            'codigoMarca' => $brand['id'],
            'codigoTipoVeiculo' => $model['type_id'],
            'codigoModelo' => $model['id'],
        ];

        $lambda = function ($collection) {
            $array = [];
            foreach ($collection as $model) {
                if (\is_array($model) && array_key_exists('Value', $model)) {
                    $array[] = [
                        'id' => $model['Value'],
                        'name' => $model['Label'],
                    ];
                }
            }

            return new Collection($array);
        };

        $body = json_encode($parameters);
        $data = $this->requestWithCache([
            'POST',
            '/ConsultarAnoModelo',
        ], $body, $body, $renew, $lambda);

        return $data;
    }
}
