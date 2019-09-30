<?php
/**
 * Created by PhpStorm.
 * User: medard
 * Date: 26/03/2017
 * Time: 15:32
 */

namespace Drupal\hir_rest_resources\Normalizer;


use Drupal;
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
            $value = $value[0]['value'];
        }

        if (isset($value[0]) and isset($value[0]['target_id']) and isset($value[0]['width'])){
            Drupal::logger('some_channel_name')->warning('<pre><code>' . print_r($value, TRUE) . '</code></pre>');
        }
        return $value;
    }
}