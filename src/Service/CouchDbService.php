<?php


namespace Drupal\hir_rest_resources\Service;


use Doctrine\CouchDB\CouchDBClient;
use Doctrine\CouchDB\HTTP\HTTPException;
use Drupal;
use Drupal\Core\TypedData\Exception\MissingDataException;
use Drupal\Core\TypedData\TypedDataInterface;
use Drupal\hir_rest_resources\Normalizer\TypedDataNormalizer;
use Drupal\node\NodeInterface;

class CouchDbService
{

    protected $client;

    public function __construct()
    {
        $this->client = CouchDBClient::create(array('dbname' => 'hir'));
    }

    public function normalizeEntity($entity) {
        $normalized = array();
        if ($entity instanceof NodeInterface) {
            $normalizer = new TypedDataNormalizer();
            try {
                $entity->getTypedData()->getProperties(true);
                foreach ($entity->getTypedData()->getProperties(true) as $property) {
                    if ($property instanceof TypedDataInterface) {
                        $normalized[$property->getName()] = $normalizer->normalize($property);
                    }
                }
            } catch (MissingDataException $e) {
                Drupal::logger('hir_rest_resources')->error("Missing data: " . $e->getMessage());
            }
        }
        return $normalized;
    }

    /**
     * @param $entityArray
     * @return string couch db identifier|null
     */
    public function createEntity($entityArray) {
        try {
            // Because couch db id is return at position 0
            return $this->client->postDocument($entityArray)[0];
        } catch (HTTPException $e) {
            Drupal::logger('hir_rest_resources')->error("Create failed: " . $e->getMessage());
        }
        return null;
    }

    /**
     * @param $entityArray
     * @param $id
     */
    public function updateEntity($entityArray, $id) {
        try {
            $this->client->putDocument($entityArray, $id);
        } catch (HTTPException $e) {
            Drupal::logger('hir_rest_resources')->error("Update failed: " . $e->getMessage());
        }
    }
}