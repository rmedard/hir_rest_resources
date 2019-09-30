<?php


namespace Drupal\hir_rest_resources\Normalizer;


use Drupal;
use Drupal\serialization\Normalizer\EntityReferenceFieldItemNormalizer;

class EntityReferenceNormalizer extends EntityReferenceFieldItemNormalizer
{

    public function normalize($field_item, $format = NULL, array $context = [])
    {
        $attributes = parent::normalize($field_item);
//        if (is_array($attributes) and $attributes[0]) {
//            Drupal::logger('hir_rest_resources')->info($attributes[0]->target_id);
//        }
//        if ($attributes['target_id'] and $attributes['width'] and $attributes['height']) {
            Drupal::logger('hir_rest_resources')
                ->info('<pre><code>' . print_r($attributes, TRUE) . '</code></pre>');
//        }
//        if ($field_item instanceof FileEn)
        return $attributes;
    }
}