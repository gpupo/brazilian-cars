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

use Gpupo\CommonSdk\Entity\GenericManager;
use Gpupo\Common\Entity\CollectionInterface;
use Gpupo\Common\Entity\Collection;

class MainManager extends GenericManager
{
    protected $currentListId;

    protected $types = [
        '1' => 'Car',
        // '2' => 'Motorcycle',
        // '3' => 'Truck',
        //'4' => 'MotorizedBicycle',
    ];

    public function getLists(bool $renew = false)
    {
        return $this->requestWithCache([
            'POST',
            '/ConsultarTabelaDeReferencia',
        ], 'lists', null, $renew);
    }

    public function getCurrentListId(): int
    {
        if (empty($this->currentListId)) {
            $this->currentListId = (int) current($this->getLists()->first());
        }

        return $this->currentListId;
    }

    protected function normalizeBrand(array $item): array
    {
        $item['id'] = (int) $item['Value'];
        $item['name'] = $item['Label'];
        unset($item['Value']);
        unset($item['Label']);
        $item['type'] = [];

        return $item;
    }

    public function getBrands(bool $renew = false): CollectionInterface
    {
        $list = [];

        foreach($this->types as $type_id => $type_name) {
            $collection = $this->getBrandsWithType($type_id, $renew);
            foreach($collection as $item) {
                $item = $this->normalizeBrand($item);
                $key = $item['name'];

                if (!array_key_exists($key, $list)) {
                    $list[$key] = $item;
                }

                $list[$key]['type'][] = ['id' => (int) $type_id, 'name'=> $type_name];
            }
        }

        sort($list);

        return $this->factoryCollection($list);
    }

    protected function getBrandsWithType(int $type = 1, bool $renew = false): CollectionInterface
    {
        $body = [
        	'codigoTabelaReferencia'=> $this->getCurrentListId(),
        	'codigoTipoVeiculo'=> $type,
        ];

        return $this->requestWithCache([
            'POST',
            '/ConsultarMarcas',
        ], 'marcas'.$type, json_encode($body), $renew);
    }


    public function getModels(bool $renew = false): CollectionInterface
    {
        $list = [];
        foreach($this->getBrands() as $brand) {
            $brand['models'] = $this->getModelsWithBrand($brand, $renew);
            $list[] = $this->factoryCollection($brand);
        }

        return $this->factoryCollection($list);
    }

    protected function getModelsWithBrand(array $brand, bool $renew = false): CollectionInterface
    {
        $list = [];
        foreach($brand['type'] as $type) {
            $models = $this->getModelsWithBrandAndType($brand['id'], $type['id'], $renew);

            $list = array_merge($list, $models->toArray());

        }

        return $this->factoryCollection($list);
    }

    protected function getModelsWithBrandAndType(int $brand, int $type = 1, bool $renew = false): CollectionInterface
    {
        $body = [
            'codigoTabelaReferencia'=> $this->getCurrentListId(),
            'codigoTipoVeiculo'=> $type,
            'codigoMarca'=> $brand,
        ];

        $lambda = function($collection) use ($type) {
            $array = [];
            foreach($collection->get('Modelos') as $model) {
                $array[] = [
                    'id' => $model['Value'],
                    'name' => $model['Label'],
                    'type_id' => $type,
                ];
            }

            return new Collection($array);
        };

        $data =  $this->requestWithCache([
            'POST',
            '/ConsultarModelos',
        ], sprintf('models-brand%s-type%s', $brand,$type), json_encode($body), $renew, $lambda);

        return $data;
    }

    public function detailedModels(CollectionInterface $brands)
    {
        $list = new Collection();
        foreach($brands as $brand) {
            $modelCollection = new Collection();
            foreach($brand->get('models') as $model) {
                $model['versions'] = $this->getVersionsWithBrandAndModel($brand, $model);
                $modelCollection->add($this->factoryCollection($model));
            }

            $brand->set('models', $modelCollection);
            $list->add($brand);
        }

        return $list;
    }

    protected function getVersionsWithBrandAndModel(CollectionInterface $brand, array $model, bool $renew = false): CollectionInterface
    {
        $parameters = [
            'codigoTabelaReferencia'=> $this->getCurrentListId(),
            'codigoMarca'=> $brand['id'],
            'codigoTipoVeiculo'=> $model['type_id'],
            'codigoModelo'=> $model['id'],
        ];

        $lambda = function($collection) {
            $array = [];
            foreach($collection as $model) {
                if (is_array($model) && array_key_exists('Value', $model)) {
                    $array[] = [
                        'id' => $model['Value'],
                        'name' => $model['Label'],
                    ];
                }
            }

            return new Collection($array);
        };

        $body=json_encode($parameters);
        $data =  $this->requestWithCache([
            'POST',
            '/ConsultarAnoModelo',
        ], $body, $body, $renew, $lambda);

        return $data;
    }
}
