<?php
/**
 * Created by PhpStorm.
 * User: medard
 * Date: 26/03/2017
 * Time: 15:32
 */

namespace Drupal\hir_rest_resources\Normalizer;


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
                    kint($object->getDataDefinition()->getClass() === FileFieldItemList::class);
                    die();
                    for ($i = 0; $i < count($values); $i++) {
                        $values[$i]['file_url'] = file_create_url(File::load($values[$i]['target_id'])->getFileUri());
                    }
                }
            }
        }
        return $values;
    }
}