<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 3/24/18
 * Time: 8:25 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class LocalUsers
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="local_users")
 */
class LocalUsers
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $id_pr;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $project_name;


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
     * Set idPr
     *
     * @param integer $idPr
     *
     * @return LocalUsers
     */
    public function setIdPr($idPr)
    {
        $this->id_pr = $idPr;

        return $this;
    }

    /**
     * Get idPr
     *
     * @return integer
     */
    public function getIdPr()
    {
        return $this->id_pr;
    }

    /**
     * Set projectName
     *
     * @param string $projectName
     *
     * @return LocalUsers
     */
    public function setProjectName($projectName)
    {
        $this->project_name = $projectName;

        return $this;
    }

    /**
     * Get projectName
     *
     * @return string
     */
    public function getProjectName()
    {
        return $this->project_name;
    }
}
