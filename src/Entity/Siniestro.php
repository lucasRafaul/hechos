<?php

namespace App\Entity;

use App\Repository\SiniestroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SiniestroRepository::class)]
class Siniestro
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $fecha = null;

    #[ORM\Column(length: 255)]
    private ?string $ubicacion = null;

    #[ORM\Column(length: 255)]
    private ?string $descripcion = null;

    /**
     * @var Collection<int, DetalleSiniestro>
     */
    #[ORM\OneToMany(targetEntity: DetalleSiniestro::class, mappedBy: 'id_siniestro',cascade: ['persist', 'remove'])]
    private Collection $detalleSiniestros;

    #[ORM\ManyToOne(inversedBy: 'siniestros')]
    private ?Localidad $localidad = null;

    #[ORM\ManyToOne(inversedBy: 'siniestros')]
    private ?Clima $clima = null;

    #[ORM\Column(length: 150)]
    private ?string $calle = null;

    #[ORM\Column(length: 50)]
    private ?string $altura = null;

    public function __construct()
    {
        $this->detalleSiniestros = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTime
    {
        return $this->fecha;
    }

    public function setFecha(\DateTime $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getUbicacion(): ?string
    {
        return $this->ubicacion;
    }

    public function setUbicacion(string $ubicacion): static
    {
        $this->ubicacion = $ubicacion;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * @return Collection<int, DetalleSiniestro>
     */
    public function getDetalleSiniestros(): Collection
    {
        return $this->detalleSiniestros;
    }

    public function addDetalleSiniestro(DetalleSiniestro $detalleSiniestro): static
    {
        if (!$this->detalleSiniestros->contains($detalleSiniestro)) {
            $this->detalleSiniestros->add($detalleSiniestro);
            $detalleSiniestro->setIdSiniestro($this);
        }

        return $this;
    }

    public function removeDetalleSiniestro(DetalleSiniestro $detalleSiniestro): static
    {
        if ($this->detalleSiniestros->removeElement($detalleSiniestro)) {
            // set the owning side to null (unless already changed)
            if ($detalleSiniestro->getIdSiniestro() === $this) {
                $detalleSiniestro->setIdSiniestro(null);
            }
        }

        return $this;
    }

    public function getLocalidad(): ?Localidad
    {
        return $this->localidad;
    }

    public function setLocalidad(?Localidad $localidad): static
    {
        $this->localidad = $localidad;

        return $this;
    }

    public function getClima(): ?Clima
    {
        return $this->clima;
    }

    public function setClima(?Clima $clima): static
    {
        $this->clima = $clima;

        return $this;
    }

    public function getCalle(): ?string
    {
        return $this->calle;
    }

    public function setCalle(string $calle): static
    {
        $this->calle = $calle;

        return $this;
    }

    public function getAltura(): ?string
    {
        return $this->altura;
    }

    public function setAltura(string $altura): static
    {
        $this->altura = $altura;

        return $this;
    }
}
