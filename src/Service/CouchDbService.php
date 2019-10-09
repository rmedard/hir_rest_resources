<?php


namespace Drupal\hir_rest_resources\Service;


use Doctrine\CouchDB\Attachment;
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
        unset($normalized['field_advert_couch_id']); // No need to put couch id in the body
        unset($normalized['field_advert_couch_rev']); // No need to put couch rev in the body
        return $normalized;
    }

    /**
     * @param $entityArray
     * @return array couch db identifier|null
     */
    public function createEntity($entityArray) {
        try {
            $picture = Drupal\file\Entity\File::load(17444);
            $url = 'http://www.hir-dev.ml/sites/default/files/2019-02/1kagugu-plot-plut-properties-3.jpg';
            $attachment = Attachment::createFromBinaryData(fopen($url, 'r'), 'image/jpeg');
            $entityArray['_attachments'] = array('firstImage.jpg' => $attachment);
            return $this->client->postDocument($entityArray);
        } catch (HTTPException $e) {
            Drupal::logger('hir_rest_resources')->error("Create failed: " . $e->getMessage());
        }
        return array();
    }

    /**
     * @param $entityArray
     * @param $id
     * @param $rev
     * @return array
     */
    public function updateEntity($entityArray, $id, $rev) {
        try {
            return $this->client->putDocument($entityArray, $id, $rev);
        } catch (HTTPException $e) {
            if ($e->getCode() == 409) {
                //TODO Fetch document, merge with new changes and put again
//                $r = $this->client->findDocument($id);
//                $r->
                Drupal::logger('hir_rest_resources')->error("Update failed: " . $e->getMessage());
            }
            Drupal::logger('hir_rest_resources')->error("Update failed: " . $e->getMessage());
        }
        return array();
    }

    public function deleteEntity($id, $rev) {
        try {
            $this->client->deleteDocument($id, $rev);
        } catch (HTTPException $e) {
            Drupal::logger('hir_rest_resources')->error("Delete failed: " . $e->getMessage());
        }
    }
}