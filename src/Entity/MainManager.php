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

use Gpupo\Common\Entity\CollectionInterface;
use Gpupo\CommonSdk\Entity\GenericManager;

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

    public function getBrands(bool $renew = false): CollectionInterface
    {
        $list = [];

        foreach ($this->types as $type_id => $type_name) {
            $collection = $this->getBrandsWithType($type_id, $renew);
            foreach ($collection as $item) {
                $item = $this->normalizeBrand($item);
                $key = $item['name'];

                if (!\array_key_exists($key, $list)) {
                    $list[$key] = $item;
                }

                $list[$key]['type'][] = ['id' => (int) $type_id, 'name' => $type_name];
            }
        }

        sort($list);

        return $this->factoryCollection($list);
    }

    protected function normalizeBrand(array $item): array
    {
        $item['id'] = (int) $item['Value'];
        $item['name'] = $item['Label'];
        unset($item['Value'], $item['Label']);

        $item['type'] = [];

        return $item;
    }

    protected function getBrandsWithType(int $type = 1, bool $renew = false): CollectionInterface
    {
        $body = [
            'codigoTabelaReferencia' => $this->getCurrentListId(),
            'codigoTipoVeiculo' => $type,
        ];

        return $this->requestWithCache([
            'POST',
            '/ConsultarMarcas',
        ], 'marcas'.$type, json_encode($body), $renew);
    }
}
