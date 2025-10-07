<?php

namespace App\Entity;

use App\Repository\SexoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SexoRepository::class)]
class Sexo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $descripcion = null;

    /**
     * @var Collection<int, Persona>
     */
    #[ORM\OneToMany(targetEntity: Persona::class, mappedBy: 'genero')]
    private Collection $personas;

    public function __construct()
    {
        $this->personas = new ArrayCollection();
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
     * @return Collection<int, Persona>
     */
    public function getPersonas(): Collection
    {
        return $this->personas;
    }

    public function addPersona(Persona $persona): static
    {
        if (!$this->personas->contains($persona)) {
            $this->personas->add($persona);
            $persona->setGenero($this);
        }

        return $this;
    }

    public function removePersona(Persona $persona): static
    {
        if ($this->personas->removeElement($persona)) {
            // set the owning side to null (unless already changed)
            if ($persona->getGenero() === $this) {
                $persona->setGenero(null);
            }
        }

        return $this;
    }
}
