<?php
/**
 * Created by PhpStorm.
 * User: medard
 * Date: 26/03/2017
 * Time: 15:32
 */

namespace Drupal\hir_rest_resources\Normalizer;


use Drupal;
use Drupal\Core\TypedData\TypedDataInterface;
use Drupal\file\Entity\File;
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
        $values = array();
        if ($object instanceof TypedDataInterface) {
            $values = $object->getValue();
            if ($object->getName() === 'field_advert_picture') {
                Drupal::logger('hir_rest_resources')
                    ->info("Type: " . $object->getDataDefinition()->getClass());
                $data = $object->getValue();
//                if ($data instanceof Drupal\file\Plugin\Field\FieldType\FileFieldItemList) {
//                    $data->getValue()
//
//                }
                kint($object);
                die();
            }
            if (is_array($values) and isset($values[0])) {
                if (isset($values[0]['value'])) {
                    $values = $values[0]['value'];
                }
                if (isset($values[0]['target_id']) and isset($values[0]['width']) and isset($values[0]['height'])) {
                    for ($i = 0; $i < $values->length; $i++) {
                        $values[$i]['file_url'] = file_create_url(File::load($values[$i]['target_id'])->getFileUri());
                    }
                }
            }
        }
        return $values;
    }
}