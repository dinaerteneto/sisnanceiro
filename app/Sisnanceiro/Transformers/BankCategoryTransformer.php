<?php

namespace Sisnanceiro\Transformers;

use League\Fractal\TransformerAbstract;
use Sisnanceiro\Models\BankCategory;

class BankCategoryTransformer extends TransformerAbstract
{

    /**
     * Transform the bank categories for controller return
     * @param BankCategory $bankCategory
     * @return array
     */
    public function transform(BankCategory $bankCategory)
    {
        return [
            'id'                      => $bankCategory->id,
            'main_parent_category_id' => $bankCategory->main_parent_category_id,
            'parent_category_id'      => $bankCategory->parent_category_id,
            'name'                    => $bankCategory->name,
        ];
    }

    /**
     * return tree (multidimensional array) of categories
     * @param  array   $elements array record categories
     * @param  integer $parentId id of parent parent
     * @return array
     */
    public function buildTree(array $elements = [], $parentId = 0)
    {
        $branch = [];
        foreach ($elements as $element) {
            if ($element['parent_category_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

    /**
     * return tree (multidimensional array) of categories
     * warning: function only two tree nivel category
     * @param  array   $elements array record categories
     * @param  integer $parentId id of parent parent
     * return array
     */
    public function buildHtmlDiv(array $elements = [], $parentId = 0)
    {
        $return = [];
        $html   = '';
        $data   = $this->buildTree($elements, $parentId);

        if ($data) {
            foreach ($data as $node) {
                $html .= "<div>{$node['text']}</div>";

                $return[] = [
                    'id'        => (int) $node['id'],
                    'html'      => "<div>{$node['text']}</div>",
                    'selection' => $node['text'],
                    'text'      => $node['text']
                ];

                if (isset($node['children'])) {
                    foreach ($node['children'] as $child) {
                        $html .= "<div style=\"padding-left: 10px\"><i class=\"fa fa-arrow-right\"></i> {$child['text']}</div>";
                        $return[] = [
                            'id'        => (int) $child['id'],
                            'html'      => "<div style=\"padding-left: 10px\"><i class=\"fa fa-arrow-right\"></i> {$child['text']}</div>",
                            'selection' => $child['text'],
                            'text'      => $child['text']
                        ];
                    }
                }
            }
        }
        return $return;
    }

    /**
     * return option values format html
     * warning: function only two tree nivel category
     * @param  array   $elements array record categories
     * @param  integer $parentId id of parent parent
     * return string
     */
    public function buildDropDownOptions(array $elements = [], $parentId = 0)
    {
        $html = '';
        $data = $this->buildTree($elements, $parentId);
        if ($data) {
            foreach ($data as $node) {
                $html .= "<optgroup label=\"{$node['text']}\" value=\"{$node['id']}\">";
                if (isset($node['children'])) {
                    foreach ($node['children'] as $child) {
                        $html.="<option value=\"{$child['id']}\">{$child['text']}</option>";
                    }
                }
                $html.="</optgroup>";
            }
        }
        return $html;
    }

}
