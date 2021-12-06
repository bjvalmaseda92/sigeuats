<?php

namespace SgvsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Usuario
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SgvsBundle\Entity\UsuariosRepository")
 * @UniqueEntity("nombreUsuario", message="EL nombre de usuario ya está en uso ")
 * @UniqueEntity("correo", message="Exite un usuario registrado con este email ")
 */
class Usuario implements UserInterface
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
     * @Assert\Regex(pattern="/^[A-Z|Á|É|Í|Ó|Ú|Ñ]{1}[a-z|á|é|í|ó|ú|A-Z|Á|É|Í|Ó|Ú|ñ|Ñ|\s]{1,50}$/", message="El nombre debe  contener solo carateres alfabéticos y comenzar con mayúscula")
     * @Assert\NotBlank(message = "El campo no puede estar vacío")
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="Apellidos", type="string", length=255)
     * @Assert\Regex(pattern="/^[A-Z|Á|É|Í|Ó|Ú|Ñ]{1}[a-z|á|é|í|ó|ú|A-Z|Á|É|Í|Ó|Ú|ñ|Ñ|\s]{1,75}$/", message="Los apellidos deben  contener solo carateres alfabéticos y comenzar con mayúscula")
     * @Assert\NotBlank(message = "El campo no puede estar vacío")
     */
    private $apellidos;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre_usuario", type="string", length=100)
     * @Assert\Regex(pattern="/^[a-z|á|é|í|ó|ú|\d]{1,15}$/", message="Solo puede contener caracteres alfanúmericos y no de sobrepasar los 15 caracteres")
     * @Assert\NotBlank(message = "El campo no puede estar vacío")
     */
    private $nombreUsuario;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

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
     * @ORM\Column(name="rol", type="string", length=255)
     */
    private $rol;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;

    /**
     * @Assert\Type(type="SgvsBundle\Entity\Imagen")
     * @ORM\OneToOne(targetEntity="SgvsBundle\Entity\Imagen", cascade={"persist","remove"})
     */
    protected $picture;


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
     * @return Usuarios
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
     * Set apellidos
     *
     * @param string $apellidos
     * @return Usuarios
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string 
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set nombreUsuario
     *
     * @param string $nombreUsuario
     * @return Usuarios
     */
    public function setNombreUsuario($nombreUsuario)
    {
        $this->nombreUsuario = $nombreUsuario;

        return $this;
    }

    /**
     * Get nombreUsuario
     *
     * @return string 
     */
    public function getNombreUsuario()
    {
        return $this->nombreUsuario;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Usuarios
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set correo
     *
     * @param string $correo
     * @return Usuarios
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
     * Set rol
     *
     * @param string $rol
     * @return Usuarios
     */
    public function setRol($rol)
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * Get rol
     *
     * @return string 
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Usuarios
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set picture
     *
     * @param \SgvsBundle\Entity\Imagen $picture
     * @return Usuario
     */
    public function setPicture(\SgvsBundle\Entity\Imagen $picture = null)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return \SgvsBundle\Entity\Imagen 
     */
    public function getPicture()
    {

            return $this->picture;

    }
    /**
     * Get imagen
     *
     * @return string
     */
    public function getImagen()
    {
        if ($this->picture!=null){
            return $this->picture;
        }else{
            return 'no-image.jpg';
        }
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[] The user roles
     */
    public function getRoles()
    {
       return array($this->getRol());
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->getNombreUsuario();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function __toString(){
        return $this->getNombre().' '.$this->getApellidos();
    }
}
