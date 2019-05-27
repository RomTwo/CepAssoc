<?php

namespace App\Transformer;

use App\Entity\Job;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class JobToStringTransformer implements DataTransformerInterface
{
    /**
     * This function return the first element of the array (use in the Form/AccountAdminType.php for the cast the roles array in string)
     *
     * @param mixed $stringJob
     * @return string
     * @internal param mixed $job
     */
    public function transform($stringJob)
    {
        if (null === $stringJob) {
            return NULL;
        }

        if (!is_string($stringJob)) {
            throw new TransformationFailedException('Expected a string.');

        }

        $job = new Job();
        $job->setName($stringJob);
        return $job;
    }


    /**
     * This function permit to transform a string in array (store the string parameter in an array)
     *
     * @param mixed $job
     * @return string
     * @internal param mixed $stringJob
     */
    public function reverseTransform($job)
    {
        if (null === $job) {
            return '';
        }

        if (!is_object($job)) {
            throw new TransformationFailedException('Expected a datetime.');
        }

        return $job->getName();
    }

}