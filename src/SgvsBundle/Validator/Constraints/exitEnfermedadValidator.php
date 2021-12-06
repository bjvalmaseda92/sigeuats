<?php
/**
 * Created by PhpStorm.
 * User: Bárbaro
 * Date: 19/05/2016
 * Time: 15:07
 */

namespace SgvsBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;



class exitEnfermedadValidator extends ConstraintValidator
{

    private $doctrine;

    /**
     * exitPacienteValidator constructor.
     * @param $doctrine
     */
    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }


    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     *
     * @api
     */
    public function validate($value, Constraint $constraint)
    {
        $em=$this->doctrine->getManager();
        $enfermedad=$em->getRepository('SgvsBundle:Enfermedad')->findOneBy(array('nombre'=>$value));
        if ($enfermedad==null){
            $this->context->addViolation($constraint->message, array(), null);
        }

}
}
