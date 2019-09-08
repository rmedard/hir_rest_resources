<?php
/**
 * Created by PhpStorm.
 * User: medard
 * Date: 26/03/2017
 * Time: 15:32
 */

namespace Drupal\hir_rest_resources\Normalizer;


use Drupal\serialization\Normalizer\NormalizerBase;

class TypedDataNormalizer extends NormalizerBase
{

    /**
     * The interface or class that this Normalizer supports.
     * @var string
     */
    protected $supportedInterfaceOrClass = 'Drupal\Core\TypedData\TypedDataInterface';

    public function normalize($object, $format = NULL, array $context = array())
    {
        $value = $object->getValue();
        if (isset($value[0]) && isset($value[0]['value'])) {
            \Drupal::logger('rest')->info($object);
            $value = $value[0]['value'];
        }
        return $value;
    }
}