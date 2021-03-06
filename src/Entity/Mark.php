<?php

namespace App\Entity;

use App\Repository\MarkRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MarkRepository::class)
 * @ApiResource(
 *  collectionOperations={
 *      "GET", 
 *      "POST",
 *      "average"={
 *          "method"="get", 
 *          "path"="/marks/average",
 *          "controller"="App\Controller\AverageMarksController",
 *          "deserialize"=false,
 *          "openapi_context"={
 *              "summary"="Calculates classroom average"
 *          }
 *      }
 *  },
 *  itemOperations={"GET"},
 *  
 *  normalizationContext={
 *      "groups"={"marks_read"}
 *  },
 *  denormalizationContext={"disable_type_enforcement"=true}
 * )
 * 
 */
class Mark
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"marks_read", "students_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * @Groups({"marks_read", "students_read"})
     * @Assert\NotBlank(message="La note est obligatoire !")
     * @Assert\Range(min=0, max=20, invalidMessage="La note doit obligatoirement être un nombre !", notInRangeMessage="La note doit être comprise entre 0 et 20 !")
     */
    private $value;

    /**
     * @ORM\Column(type="string", length=32)
     * @Groups({"marks_read", "students_read"})
     * @Assert\NotBlank(message="La matière est obligatoire !")
     * @Assert\Length(min=5, minMessage="Le matière doit faire au moins cinq caractères !")
     */
    private $subject;

    /**
     * @ORM\ManyToOne(targetEntity=Student::class, inversedBy="marks")
     * @ORM\JoinColumn(
     *      name="student_id",
     *      referencedColumnName="id",
     *      onDelete="CASCADE",
     *      nullable=false)
     * @Groups({"marks_read"})
     * @Assert\NotBlank(message="L'élève doit obligatoirement être renseigné !")
     */
    private $student;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;

        return $this;
    }
}
