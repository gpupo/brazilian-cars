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

use Gpupo\CommonSchema\ORM\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Vehicle Entity.
 * @see https://schema.org/Vehicle
 * @ORM\Table(name="bc_vehicle")
 * @ORM\Entity(repositoryClass="Gpupo\BrazilianCars\Entity\VehicleRepository")
 */
class Vehicle extends AbstractEntity
{
    /**
     * @var null|string
     *
     * @ORM\Column(name="name", type="string", nullable=true, unique=false)
     */
    protected $name;

    /**
    * @var null|string
    *
    * @ORM\Column(name="code", type="string", nullable=false, unique=true)
    */
    protected $code;

    /**
    * @var null|string
    *
    * @ORM\Column(name="manufacturer", type="string", nullable=false, unique=false)
    */
    protected $manufacturer;

    /**
    * @var null|int
    *
    * @ORM\Column(name="manufacturer_id", type="int", nullable=false, unique=false)
    */
    protected $manufacturer_id;


    /**
    * @var null|int
    *
    * @ORM\Column(name="model_year", type="integer", nullable=false, unique=false)
    */
    protected $model_year;

    /**
     * @var null|int
     *
     * @ORM\Column(name="model_identifier", type="integer", nullable=true, unique=false)
     */
    protected $model_identifier;

    /**
     * @var null|string
     *
     * @ORM\Column(name="fuel_type", type="string", nullable=true, unique=false)
     */
    protected $fuel_type;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param null|string $name
     *
     * @return Provider
     */
    public function setName($name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Get contactened nama.
     *
     * @return null|string
     */
    public function getFullName()
    {
        return sprintf('%s %s', $this->getManufacturer(), $this->getName());
    }

}
