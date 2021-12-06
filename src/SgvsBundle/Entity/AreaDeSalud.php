<?php

namespace SgvsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * AreaDeSalud
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class AreaDeSalud
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     * @Assert\NotBlank(message = "El campo no puede estar vacío")
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255)
     * @Assert\NotBlank(message = "El campo no puede estar vacío")
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="correo", type="string", length=255)
     * @Assert\NotBlank(message = "El campo no puede estar vacío")
     * @Assert\Email(message="Debe introducir una dirección de correo válida")
     */
    private $correo;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=8)
     * @Assert\NotBlank(message = "El campo no puede estar vacío")
     * @Assert\Regex(pattern="/\d{6}/", message="El número de teléfono debe poseer 6 digitos")
     */
    private $telefono;


    /**
     * @ORM\OneToMany(targetEntity="SgvsBundle\Entity\Caso", mappedBy="areadesalud", orphanRemoval=true)
     */
    private $casos;
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
     * Set nombre
     *
     * @param string $nombre
     * @return AreaDeSalud
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return AreaDeSalud
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set correo
     *
     * @param string $correo
     * @return AreaDeSalud
     */
    public function setCorreo($correo)
    {
        $this->correo = $correo;

        return $this;
    }

    /**
     * Get correo
     *
     * @return string 
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return AreaDeSalud
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    public function __toString(){
        return $this->nombre;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->casos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add casos
     *
     * @param \SgvsBundle\Entity\Caso $casos
     * @return AreaDeSalud
     */
    public function addCaso(\SgvsBundle\Entity\Caso $casos)
    {
        $this->casos[] = $casos;

        return $this;
    }

    /**
     * Remove casos
     *
     * @param \SgvsBundle\Entity\Caso $casos
     */
    public function removeCaso(\SgvsBundle\Entity\Caso $casos)
    {
        $this->casos->removeElement($casos);
    }

    /**
     * Get casos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCasos()
    {
        return $this->casos;
    }
}
