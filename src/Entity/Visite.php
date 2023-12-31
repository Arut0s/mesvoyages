<?php

namespace App\Entity;

use App\Repository\VisiteRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: VisiteRepository::class)]
#[Vich\Uploadable]
class Visite {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $ville = null;

    #[ORM\Column(length: 50)]
    private ?string $pays = null;

    #[Assert\LessThanOrEqual('now')]
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?DateTimeInterface $datecreation = null;

     #[Assert\Range(
        min: 0,
        max: 20,
    )]
    #[ORM\Column(nullable: true)]
    private ?int $note = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $avis = null;

    #[ORM\Column(nullable: true)]
    private ?int $tempmin = null;

    #[Assert\GreaterThan(propertyPath:"tempmin")]
    #[ORM\Column(nullable: true)]
    private ?int $tempmax = null;

    #[ORM\ManyToMany(targetEntity: Environnement::class)]
    private Collection $environnements;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'visites', fileNameProperty: 'imageName')]

    private ?File $imageFile = null;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updated_at = null;

    public function setImageFile(?File $imageFile = null): self {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->updated_at = new \DateTime('now');
        }
        return $this;
    }

    public function getImageFile(): ?File {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string {
        return $this->imageName;
    }

    public function __construct() {
        $this->environnements = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getVille(): ?string {
        return $this->ville;
    }

    public function setVille(string $ville): static {
        $this->ville = $ville;

        return $this;
    }

    public function getPays(): ?string {
        return $this->pays;
    }

    public function setPays(string $pays): static {
        $this->pays = $pays;

        return $this;
    }

    public function getDatecreation(): ?DateTimeInterface {
        return $this->datecreation;
    }

    public function getDatecreationString(): string {
        if ($this->datecreation == null) {
            return "";
        } else {
            return $this->datecreation->format('d/m/Y');
        }
    }

    public function setDatecreation(?DateTimeInterface $datecreation): static {
        $this->datecreation = $datecreation;

        return $this;
    }

    public function getNote(): ?int {
        return $this->note;
    }

    public function setNote(?int $note): static {
        $this->note = $note;

        return $this;
    }

    public function getAvis(): ?string {
        return $this->avis;
    }

    public function setAvis(?string $avis): static {
        $this->avis = $avis;

        return $this;
    }

    public function getTempmin(): ?int {
        return $this->tempmin;
    }

    public function setTempmin(?int $tempmin): static {
        $this->tempmin = $tempmin;

        return $this;
    }

    public function getTempmax(): ?int {
        return $this->tempmax;
    }

    public function setTempmax(?int $tempmax): static {
        $this->tempmax = $tempmax;

        return $this;
    }

    /**
     * @return Collection<int, Environnement>
     */
    public function getEnvironnements(): Collection {
        return $this->environnements;
    }

    public function addEnvironnement(Environnement $environnement): static {
        if (!$this->environnements->contains($environnement)) {
            $this->environnements->add($environnement);
        }

        return $this;
    }

    public function removeEnvironnement(Environnement $environnement): static {
        $this->environnements->removeElement($environnement);

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): static {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @Assert\Callback
     * @param ExecutionContextInterface $context
     */
    public function validate(ExecutionContextInterface $context) {
        $image = $this->getImageFile();
        if ($image != null && $image != "") {
            $tailleImage = @getimagesize($image);
            if (!($tailleImage == false)) {
                if ($tailleImage[0] > 1300 || $tailleImage[1] > 1300) {
                    $context->buildViolation("Cette image est trop grande (1300x1300 max)")
                            ->atPath('imageFile')
                            ->addViolation();
                }
            }else{
                $context->buildViolation("Ce n'est pas une image")
                        ->atPath('imageFile')
                        ->addViolation();
            }
        }
    }
}
