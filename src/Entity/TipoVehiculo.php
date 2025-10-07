<?php

namespace App\Entity;

use App\Repository\TipoVehiculoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TipoVehiculoRepository::class)]
class TipoVehiculo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $descripcion = null;

    /**
     * @var Collection<int, DetalleSiniestro>
     */
    #[ORM\OneToMany(targetEntity: DetalleSiniestro::class, mappedBy: 'tipoVehiculo')]
    private Collection $detalleSiniestros;

    public function __construct()
    {
        $this->detalleSiniestros = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $detalleSiniestro->setTipoVehiculo($this);
        }

        return $this;
    }

    public function removeDetalleSiniestro(DetalleSiniestro $detalleSiniestro): static
    {
        if ($this->detalleSiniestros->removeElement($detalleSiniestro)) {
            // set the owning side to null (unless already changed)
            if ($detalleSiniestro->getTipoVehiculo() === $this) {
                $detalleSiniestro->setTipoVehiculo(null);
            }
        }

        return $this;
    }
}
