<?php

namespace Sisnanceiro\Transformes;

class BankCategoryTransform {

    /**
     * return tree (multidimensional array) of categories
     * @param  array   $elements array record categories
     * @param  integer $parentId id of parent parent
     * @return array
     */
    public function buildTree(array $elements = [], $parentId = 0) {
        $branch = [];
        foreach ($elements as $element) {
            if ((int) $element['parent_category_id'] === $parentId) {
                $children = $this->buildTree($elements, (int) $element['id']);
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
        $html = null;
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