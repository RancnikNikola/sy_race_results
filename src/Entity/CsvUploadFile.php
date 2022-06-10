<?php

namespace App\Entity;

use App\Repository\CsvUploadFileRepository;
use Doctrine\ORM\Mapping as ORM;

// ------------------------------------------- // 

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @ORM\Entity(repositoryClass=CsvUploadFileRepository::class)
 */
class CsvUploadFile
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $csvFile;

    public function __toString()
    {
        return $this->csvFile;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCsvFile(): ?string
    {
        return $this->csvFile;
    }

    public function setCsvFile(string $csvFile): self
    {
        $this->csvFile = $csvFile;

        return $this;
    }
}
