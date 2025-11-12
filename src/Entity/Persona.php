<?php

namespace App\Entity;

use App\Repository\PersonaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonaRepository::class)]
class Persona
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $apellido = null;

    #[ORM\Column(length: 255)]
    private ?string $dni = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $fecha_nacimiento = null;

    ##[ORM\Column(length: 255)]
    #private ?string $genero = null;

    #[ORM\Column(length: 255)]
    private ?string $estado_civil = null;

    /**
     * @var Collection<int, DetalleSiniestro>
     */
    #[ORM\OneToMany(mappedBy: 'id_persona', targetEntity: DetalleSiniestro::class, cascade: ['persist', 'remove'])]
    private Collection $detalleSiniestros;

    #[ORM\ManyToOne(inversedBy: 'personas')]
    private ?Sexo $genero = null;


    public function __construct()
    {
        $this->detalleSiniestros = new ArrayCollection();
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

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): static
    {
        $this->apellido = $apellido;

        return $this;
    }

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(string $dni): static
    {
        $this->dni = $dni;

        return $this;
    }

    public function getFechaNacimiento(): ?\DateTime
    {
        return $this->fecha_nacimiento;
    }

    public function setFechaNacimiento(\DateTime $fecha_nacimiento): static
    {
        $this->fecha_nacimiento = $fecha_nacimiento;

        return $this;
    }

    #public function getGenero(): ?string
    #{
        #return $this->genero;
    #}

    #public function setGenero(string $genero): static
    #{
        #$this->genero = $genero;

        #return $this;
    #}

    public function getEstadoCivil(): ?string
    {
        return $this->estado_civil;
    }

    public function setEstadoCivil(string $estado_civil): static
    {
        $this->estado_civil = $estado_civil;

        return $this;
    }

    /**
     * @return Collection<int, DetalleSiniestro>
     */
    public function getDetalleSiniestros(): Collection
    {
        return $this->detalleSiniestros;
    }

    public function addDetalleSiniestro(DetalleSiniestro $detalle): static
    {
        if (!$this->detalleSiniestros->contains($detalle)) {
            $this->detalleSiniestros->add($detalle);
            $detalle->getIdPersona($this);
        }

        return $this;
    }

    public function removeDetalleSiniestro(DetalleSiniestro $detalle): static
    {
        if ($this->detalleSiniestros->removeElement($detalle)) {
            if ($detalle->getIdPersona() === $this) {
                $detalle->setIdPersona(null);
            }
        }

        return $this;
    }

    public function getGenero(): ?Sexo
    {
        return $this->genero;
    }

    public function setGenero(?Sexo $genero): static
    {
        $this->genero = $genero;

        return $this;
    }

}
