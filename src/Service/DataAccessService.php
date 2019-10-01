<?php


namespace Drupal\hir_rest_resources\Service;


use Drupal;
use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\node\Entity\Node;

class DataAccessService
{
    protected $entityTypeManager;

    /**
     * DataAccessService constructor.
     * @param $entityTypeManager
     */
    public function __construct(EntityTypeManager $entityTypeManager)
    {
        $this->entityTypeManager = $entityTypeManager;
    }

    public function loadPublishedNodes()
    {
        $adverts = array();
        try {
            $storage = $this->entityTypeManager->getStorage('node');
            $query = $storage->getQuery()
                ->condition('type', 'advert')
                ->condition('status', Node::PUBLISHED)
                ->notExists('field_advert_couch_id');
            $advertsIds = $query->execute();
            if ($advertsIds && !empty($advertsIds)) {
                $adverts = $storage->loadMultiple($advertsIds);
            }
        } catch (InvalidPluginDefinitionException $e) {
            Drupal::logger('hir_rest_resources')->error('Invalid plugin: ' . $e->getMessage());
        } catch (PluginNotFoundException $e) {
            Drupal::logger('hir_rest_resources')->error('Plugin not found: ' . $e->getMessage());
        } finally {
            return $adverts;
        }
    }
}