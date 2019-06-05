<?php

namespace App\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class ArrayToStringTransformer implements DataTransformerInterface
{

    /**
     * This function return the first element of the array (use in the Form/AccountAdminType.php for the cast the roles array in string)
     *
     * @param mixed $value
     * @return mixed
     */
    public function transform($value)
    {
        return $value[0];
    }

    /**
     * This function permit to transform a string in array (store the string parameter in an array)
     *
     * @param mixed $value
     * @return array
     */
    public function reverseTransform($value)
    {
        return array($value);
    }
}