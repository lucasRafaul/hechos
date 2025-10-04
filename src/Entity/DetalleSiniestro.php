<?php

namespace App\Entity;

use App\Repository\DetalleSiniestroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DetalleSiniestroRepository::class)]
//#[UniqueEntity(fields: ['persona', 'siniestro', 'rol'], message: 'Esta persona ya tiene ese rol en este siniestro.')]
class DetalleSiniestro
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'detalleSiniestros')]
    private ?Siniestro $id_siniestro = null;

    #[ORM\ManyToOne(inversedBy: 'detalleSiniestros')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Persona $id_persona = null;

    #[ORM\Column(length: 255)]
    private ?string $rol = null;

    #[ORM\Column(length: 255)]
    private ?string $estado_alcoholico = null;

    #[ORM\Column(length: 255)]
    private ?string $porcentaje_alcohol = null;

    #[ORM\Column(length: 255)]
    private ?string $observaciones = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdSiniestro(): ?Siniestro
    {
        return $this->id_siniestro;
    }

    public function setIdSiniestro(?Siniestro $id_siniestro): static
    {
        $this->id_siniestro = $id_siniestro;

        return $this;
    }

    public function getIdPersona(): ?persona
    {
        return $this->id_persona;
    }

    public function setIdPersona(?Persona $id_persona): static
    {
        $this->id_persona = $id_persona;

        return $this;
    }

    public function getRol(): ?string
    {
        return $this->rol;
    }

    public function setRol(string $rol): static
    {
        $this->rol = $rol;

        return $this;
    }

    public function getEstadoAlcoholico(): ?string
    {
        return $this->estado_alcoholico;
    }

    public function setEstadoAlcoholico(string $estado_alcoholico): static
    {
        $this->estado_alcoholico = $estado_alcoholico;

        return $this;
    }

    public function getPorcentajeAlcohol(): ?string
    {
        return $this->porcentaje_alcohol;
    }

    public function setPorcentajeAlcohol(string $porcentaje_alcohol): static
    {
        $this->porcentaje_alcohol = $porcentaje_alcohol;

        return $this;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(string $observaciones): static
    {
        $this->observaciones = $observaciones;

        return $this;
    }
}
