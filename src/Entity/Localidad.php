<?php

namespace App\Entity;

use App\Repository\LocalidadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocalidadRepository::class)]
class Localidad
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    /**
     * @var Collection<int, Siniestro>
     */
    #[ORM\OneToMany(targetEntity: Siniestro::class, mappedBy: 'localidad')]
    private Collection $siniestros;

    public function __construct()
    {
        $this->siniestros = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return Collection<int, Siniestro>
     */
    public function getSiniestros(): Collection
    {
        return $this->siniestros;
    }

    public function addSiniestro(Siniestro $siniestro): static
    {
        if (!$this->siniestros->contains($siniestro)) {
            $this->siniestros->add($siniestro);
            $siniestro->setLocalidad($this);
        }

        return $this;
    }

    public function removeSiniestro(Siniestro $siniestro): static
    {
        if ($this->siniestros->removeElement($siniestro)) {
            // set the owning side to null (unless already changed)
            if ($siniestro->getLocalidad() === $this) {
                $siniestro->setLocalidad(null);
            }
        }

        return $this;
    }
}
