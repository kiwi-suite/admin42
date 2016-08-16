<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\Controller\Api;

use Admin42\Mvc\Controller\AbstractAdminController;
use Admin42\TableGateway\TagTableGateway;
use Core42\View\Model\JsonModel;
use Zend\Db\Sql\Predicate\IsNull;
use Zend\Db\Sql\Predicate\Like;

class ApiController extends AbstractAdminController
{
    /**
     * @return JsonModel
     */
    public function tagSuggestAction()
    {
        $searchTag = $this->request->getQuery('tag', '');

        $tags = [];
        if (!empty($searchTag)) {
            $tagResult = $this->getTableGateway(TagTableGateway::class)->select([
                new Like('tag', $searchTag . '%'),
                new IsNull('namespace')
            ]);
            foreach ($tagResult as $tag) {
                $tags[] = [
                    'id' => $tag->getId(),
                    'tag' => $tag->getTag(),
                ];
            }
        }

        return new JsonModel(['results' => $tags, 'success' => true]);
    }
}
