<?php

namespace App\Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TimeToStringTransformer implements DataTransformerInterface
{

    /**
     * This function return the first element of the array (use in the Form/AccountAdminType.php for the cast the roles array in string)
     *
     * @param mixed $time
     * @return string
     */
    public function transform($time)
    {
        if (null === $time) {
            return '';
        }

        if (!is_object($time)) {
            throw new TransformationFailedException('Expected a datetime.');
        }
        return $time->format('H:i');


    }

    /**
     * This function permit to transform a string in array (store the string parameter in an array)
     *
     * @param mixed $stringTime
     * @return \DateTime
     */
    public function reverseTransform($stringTime)
    {
        if (null === $stringTime) {
            return NULL;
        }

        if (!is_string($stringTime)) {
            throw new TransformationFailedException('Expected a string.');
        }

        return new \DateTime($stringTime);
    }
}