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

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gpupo\Common\Entity\AbstractORMEntity;

/**
 * Vehicle Entity.
 *
 * @see https://schema.org/Vehicle
 * @ORM\Table(name="bc_vehicle")
 * @ORM\Entity(repositoryClass="Gpupo\BrazilianCars\Entity\VehicleRepository")
 */
class Vehicle extends AbstractORMEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var DateTime (Record update timestamp)
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updated_at;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Versioned
     */
    protected $deleted_at;

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
     * Sets deleted_at.
     *
     * @param DateTime $deleted_at
     */
    public function setDeleatedAt(?DateTime $deleted_at = null): void
    {
        $this->dele = $deleted_at;
    }

    /**
     * Sets updated_at.
     *
     * @param DateTime $updated_at
     */
    public function setUpdatedAt(?DateTime $updated_at = null): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * Is deleted?
     *
     * @return bool
     */
    public function isDeleted(): bool
    {
        return null !== $this->deleted_at;
    }

    public function setId($id)
    {
        return $this->id = $id;
    }

    public function getSchema(): array
    {
        return [
        ];
    }

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
     * Get contactened name.
     *
     * @return null|string
     */
    public function getFullName()
    {
        return sprintf('%s %s %s %s', $this->getManufacturer(), $this->getName(), $this->getModelYear(), $this->getFuelType());
    }
}
