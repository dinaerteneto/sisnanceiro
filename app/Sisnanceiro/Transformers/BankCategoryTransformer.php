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
     * return html structured <div> based on buildTree function
     * warning: function only two tree nivel category
     * @param array $elements
     * return string
     */
    public function buildHtmlDiv($elements = [])
    {
        $style = "";
        $html  = null;
        foreach ($elements as $element) {
            if (!in_array($element['parent_category_id'], [2, 3])) {
                $style = "padding-left: 20px";
            }
            if (isset($element['text'])) {
                $html .= "<div style=\"$style\" data-id=\"{$element['id']}\" data-parent-id=\"{$element['parent_category_id']}\" data-text=\"{$element['text']}\">";
                $html .= "{$element['text']}";
                $html .= "</div>\n";
            }
            if (isset($element['children'])) {
                $html .= $this->buildHtmlDiv($element['children']);
            }
        }
        return $html;
    }

}
