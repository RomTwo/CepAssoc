<?php

namespace App\Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class DateToStringTransformer implements DataTransformerInterface
{

    /**
     * This function return the first element of the array (use in the Form/AccountAdminType.php for the cast the roles array in string)
     *
     * @param mixed $date
     * @return string
     */
    public function transform($date)
    {
        if (null === $date) {
            return '';
        }

        if (!is_object($date)) {
            throw new TransformationFailedException('Expected a datetime.');
        }

        return $date->format('d-m-Y');


    }

    /**
     * This function permit to transform a string in array (store the string parameter in an array)
     *
     * @param mixed $stringDate
     * @return \DateTime
     */
    public function reverseTransform($stringDate)
    {
        if (null === $stringDate) {
            return NULL;
        }

        if (!is_string($stringDate)) {
            throw new TransformationFailedException('Expected a string.');
        }

        return new \DateTime($stringDate);
    }
}