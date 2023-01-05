<?php

namespace App\EntityOld\Contractors;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     schema="contractors",
 *     name="supervisory_authority_district_relations",
 * )
 * @UniqueEntity(
 *     fields={"supervisoryAuthority", "district"}
 * )
 */
class SupervisoryAuthorityDistrictRelation
{
    use TimestampableEntity;

    /**
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @var string
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="SupervisoryAuthority", inversedBy="supervisoryAuthorityDistrictRelations")
     * @ORM\JoinColumn(name="supervisory_authority_id", nullable=false)
     */
    private $supervisoryAuthority;

//    /**
//     * @ORM\ManyToOne(targetEntity="MartInfo\VeterinaryBundle\EntityOld\Dictionary\District")
//     * @ORM\JoinColumn(name="district_id", nullable=false)
//     */
//    private $district;

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set supervisoryAuthority
     *
     * @param \MartInfo\VeterinaryBundle\EntityOld\Contractors\SupervisoryAuthority $supervisoryAuthority
     *
     * @return SupervisoryAuthorityDistrictRelation
     */
    public function setSupervisoryAuthority(SupervisoryAuthority $supervisoryAuthority)
    {
        $this->supervisoryAuthority = $supervisoryAuthority;

        return $this;
    }

    /**
     * Get supervisoryAuthority
     *
     * @return \MartInfo\VeterinaryBundle\EntityOld\Contractors\SupervisoryAuthority
     */
    public function getSupervisoryAuthority()
    {
        return $this->supervisoryAuthority;
    }

   
}
