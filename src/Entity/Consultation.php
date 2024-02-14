<?php

namespace App\Entity;

use App\Repository\ConsultationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConsultationRepository::class)]
class Consultation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $duree = null;

    #[ORM\Column(length: 255)]
    private ?string $note = null;

    #[ORM\Column(nullable: true)]
    private ?float $avisPatient = null;

    #[ORM\Column]
    private ?bool $RecommandationSuivi = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?RendezVous $rendezvous = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDuree(): ?\DateTimeInterface
    {
        return $this->duree;
    }

    public function setDuree(\DateTimeInterface $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(string $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getAvisPatient(): ?float
    {
        return $this->avisPatient;
    }

    public function setAvisPatient(?float $avisPatient): static
    {
        $this->avisPatient = $avisPatient;

        return $this;
    }

    public function isRecommandationSuivi(): ?bool
    {
        return $this->RecommandationSuivi;
    }

    public function setRecommandationSuivi(bool $RecommandationSuivi): static
    {
        $this->RecommandationSuivi = $RecommandationSuivi;

        return $this;
    }

    public function getRendezvous(): ?RendezVous
    {
        return $this->rendezvous;
    }

    public function setRendezvous(?RendezVous $rendezvous): static
    {
        $this->rendezvous = $rendezvous;

        return $this;
    }
}
