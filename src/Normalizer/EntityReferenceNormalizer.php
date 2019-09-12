<?php


namespace Drupal\hir_rest_resources\Normalizer;


use Drupal;
use Drupal\serialization\Normalizer\EntityReferenceFieldItemNormalizer;

class EntityReferenceNormalizer extends EntityReferenceFieldItemNormalizer
{

    public function normalize($field_item, $format = NULL, array $context = [])
    {
        Drupal::logger('hir_rest_resources')->info(json_encode($field_item));
        return parent::normalize($field_item, $format, $context);
    }
}