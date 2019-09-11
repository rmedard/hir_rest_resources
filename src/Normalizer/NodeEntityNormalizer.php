<?php
/**
 * Created by PhpStorm.
 * User: medard
 * Date: 25/03/2017
 * Time: 17:06
 */

namespace Drupal\hir_rest_resources\Normalizer;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\serialization\Normalizer\ContentEntityNormalizer;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;

class NodeEntityNormalizer extends ContentEntityNormalizer
{

    /**
     * @var string
     */
    protected $supportedInterfaceOrClass = 'Drupal\node\NodeInterface';

    /**
     * Normalizes an object into a set of arrays/scalars.
     *
     * @param mixed $entity Object to normalize
     * @param string $format Format the normalization result will be encoded as
     * @param array $context Context options for the normalizer
     *
     * @return array|string|int|float|bool
     *
     * @throws InvalidArgumentException   Occurs when the object given is not an attempted type for the normalizer
     * @throws CircularReferenceException Occurs when the normalizer detects a circular reference when no circular
     *                                    reference handler can fix it
     * @throws LogicException             Occurs when the normalizer is not called in an expected context
     */
    public function normalize($entity, $format = NULL, array $context = [])
    {
        $attributes = parent::normalize($entity);
        $changed_timestamp = $entity->getChangedTime();
        $created_timestamp = $entity->getCreatedTime();

        $changed_date = DrupalDateTime::createFromTimestamp($changed_timestamp);
        $created_date = DrupalDateTime::createFromTimestamp($created_timestamp);

        /**
         * Add useful fields
         */
        $attributes['changed_iso8601'] = $changed_date->format('d-m-Y H:i:s');
        $attributes['created_iso8601'] = $created_date->format('d-m-Y H:i:s');
        $attributes['link'] = $entity->toUrl()->toString();

        /**
         * Remove unneeded fields eg: 'revision_*'
         */
        $attributes = array_filter($attributes, function ($key) {
            return 0 !== strpos($key, 'revision_');
        }, ARRAY_FILTER_USE_KEY);

        ksort($attributes);

        return $attributes;
    }

}