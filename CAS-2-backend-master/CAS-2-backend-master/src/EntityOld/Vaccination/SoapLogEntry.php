<?php

namespace App\EntityOld\Vaccination;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class SoapVaccination
 * @package MartInfo\VeterinaryBundle\EntityOld\Vaccination
 * @ORM\Table(schema="vaccination", name="soap_log_entries")
 * @ORM\Entity(repositoryClass="App\Repository\Vaccination\SoapLogEntryRepository")
 * @ORM\Entity()
 */
class SoapLogEntry
{
    /**
     * @ORM\Column(type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @var string
     */
    private $id;

    /**
     * @ORM\Column(name="input_data", type="jsonb")
     * @Assert\NotNull
     * @var array
     */
    private $inputData;

    /**
     * @ORM\Column(name="output_data", type="jsonb")
     * @var array
     */
    private $outputData;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set inputData
     *
     * @param array $inputData
     *
     * @return SoapLogEntry
     */
    public function setInputData(array $inputData)
    {
        $this->inputData = $inputData;

        return $this;
    }

    /**
     * Get inputData
     *
     * @return array
     */
    public function getInputData()
    {
        return $this->inputData;
    }

    /**
     * Set outputData
     *
     * @param array $outputData
     *
     * @return SoapLogEntry
     */
    public function setOutputData(array $outputData)
    {
        $this->outputData = $outputData;

        return $this;
    }

    /**
     * Get outputData
     *
     * @return array
     */
    public function getOutputData()
    {
        return $this->outputData;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return SoapLogEntry
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
