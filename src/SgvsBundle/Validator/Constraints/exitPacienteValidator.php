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



class exitPacienteValidator extends ConstraintValidator
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
        $data=explode(' - ', $value);

        $em=$this->doctrine->getManager();
        $paciente=$em->getRepository('SgvsBundle:Paciente')->findOneBy(array('ci'=>$data[1]));
        if ($paciente==null){
            $this->context->addViolation($constraint->message, array(), null);
        }

}
}
