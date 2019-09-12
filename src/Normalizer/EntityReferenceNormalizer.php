<?php


namespace Drupal\hir_rest_resources\Normalizer;


use Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem;
use Drupal\serialization\Normalizer\EntityReferenceFieldItemNormalizer;

class EntityReferenceNormalizer extends EntityReferenceFieldItemNormalizer
{

    /**
     * @param $field_item
     * @param null $format
     * @param array $context
     * @return array|bool|float|int|string
     */
    function normalize($field_item, $format = NULL, array $context = [])
    {
        $values = parent::normalize($field_item, $format, $context);
        if (isset($field_item->entity)) {
            $values['image_path'] = $field_item->entity->getFileUri();
        }
        return $values;
    }
}