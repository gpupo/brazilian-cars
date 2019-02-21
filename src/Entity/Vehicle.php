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

use Doctrine\ORM\Mapping as ORM;
use Gpupo\CommonSchema\ORM\Entity\AbstractEntity;
use Gpupo\CommonSchema\ORM\Entity\EntityInterface;

/**
 * Vehicle Entity.
 *
 * @see https://schema.org/Vehicle
 * @ORM\Table(name="bc_vehicle", options={"collate"="utf8mb4_unicode_ci"})
 * @ORM\Entity(repositoryClass="Gpupo\BrazilianCars\Entity\VehicleRepository")
 */
class Vehicle extends AbstractEntity implements EntityInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=false, unique=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", nullable=false, unique=true)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="family", type="string", nullable=false, unique=false)
     */
    protected $family;

    /**
     * @var null|string
     *
     * @ORM\Column(name="manufacturer", type="string", nullable=false, unique=false)
     */
    protected $manufacturer;

    /**
     * @var int
     *
     * @ORM\Column(name="manufacturer_id", type="integer", nullable=false, unique=false)
     */
    protected $manufacturer_id;

    /**
     * @var int
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
     * Get contactened name.
     *
     * @return null|string
     */
    public function getFullName()
    {
        return sprintf('%s %s %s %s', $this->getManufacturer(), $this->getName(), $this->getModelYear(), $this->getFuelType());
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setFamily(string $family): void
    {
        $this->family = $family;
    }

    public function getFamily(): ?string
    {
        return $this->family;
    }

    public function setManufacturer(?string $manufacturer): void
    {
        $this->manufacturer = $manufacturer;
    }

    public function getManufacturer(): ?string
    {
        return $this->manufacturer;
    }

    public function setManufacturerId(?int $manufacturer_id): void
    {
        $this->manufacturer_id = $manufacturer_id;
    }

    public function getManufacturerId(): ?int
    {
        return $this->manufacturer_id;
    }

    public function setModelYear(?int $model_year): void
    {
        $this->model_year = $model_year;
    }

    public function getModelYear(): ?int
    {
        return $this->model_year;
    }

    public function setModelIdentifier($model_identifier): void
    {
        $this->model_identifier = $model_identifier;
    }

    public function getModelIdentifier(): ?int
    {
        return $this->model_identifier;
    }

    public function setFuelType(?string $fuel_type): void
    {
        $this->fuel_type = $fuel_type;
    }

    public function getFuelType(): ?string
    {
        return $this->fuel_type;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
