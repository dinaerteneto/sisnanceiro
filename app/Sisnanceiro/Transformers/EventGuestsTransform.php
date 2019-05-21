<?php
namespace Sisnanceiro\Transformers;

use League\Fractal\TransformerAbstract;
use Sisnanceiro\Models\Event;

class EventGuestsTransform extends TransformerAbstract
{

    public function transform(Event $event)
    {
        $return = [];
        if ($guests = $event->guests()->orderBy('person_name')) {
            foreach ($guests->get() as $guest) {
                $return[] = [
                    'id'     => $guest->id,
                    'name'   => $guest->person_name,
                    'email'  => $guest->email,
                    'status' => $guest->getStatus(),
                ];
            }
            // $return['total_guest'] = count($guests);
        }
        return $return;
    }

    /**
     * return tree (multidimensional array) of guests
     * @param  array   $elements array record guests
     * @param  integer $parentId id of parent parent
     * @return array
     */
    public function buildTree(array $elements = [], $parentId = 0)
    {
        $branch = [];
        foreach ($elements as $element) {
            if ($element['invited_by_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }
}
