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
use Drupal\file\Plugin\Field\FieldType\FileFieldItemList;
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
            if (is_array($values) and isset($values[0])) {
                if (isset($values[0]['value'])) {
                    $values = $values[0]['value'];
                }

                if ($object->getName() === 'field_advert_picture') {
                    $values[0]['file_url'] = file_create_url(File::load($values[0]['target_id'])->getFileUri());
                    $values[1]['file_url'] = file_create_url(File::load($values[1]['target_id'])->getFileUri());
                    $values[2]['file_url'] = file_create_url(File::load($values[2]['target_id'])->getFileUri());
                    $values[3]['file_url'] = file_create_url(File::load($values[3]['target_id'])->getFileUri());
                    $values[4]['file_url'] = file_create_url(File::load($values[4]['target_id'])->getFileUri());
                    kint($values);
                    die();
                }
            }
        }
        return $values;
    }
}