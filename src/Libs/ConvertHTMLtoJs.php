<?php

namespace Souravmsh\HtmlToJs\Libs;

use DOMDocument;

class ConvertHTMLtoJs
{
    public static function convert($html)
    {
        if (empty($html)) {
            return self::response();
        }

        $type = is_string($html) ? "string" : get_class($html);

        switch ($type) {
            case "DOMDocument":
            case "DOMElement":
                $id = $html->nodeName . "_" . md5(uniqid()) . "_element";
                $code = ($html->nodeName != "#document") ? "var $id = document.createElement('{$html->nodeName}');\n" : "";

                if (!!$html->attributes) {
                    foreach ($html->attributes as $attr) {
                        $code .= "$id.setAttribute('{$attr->name}', '{$attr->value}');\n";
                    }
                }

                if (!!$html->childNodes) {
                    foreach ($html->childNodes as $child) {
                        $code .= ($child->nodeType == XML_TEXT_NODE)
                            ? "$id.appendChild(document.createTextNode('" . htmlentities($child->nodeValue) . "'));\n"
                            : self::convertChild($child, $id);
                    }
                }

                return self::response($code, $id);

            case "DOMDocumentType":
                return self::response();

            default:
            case "string":
                $dom = new DOMDocument();
                $dom->strictErrorChecking = false;
                $dom->loadHTML($html);
                $result = self::convert($dom);
                return self::response($result->data, $result->id);
        }
    }

    private static function convertChild($child, $parentId)
    {
        $element = self::convert($child);
        $code = $element->data;

        if ($parentId != "") {
            $code .= "$parentId.appendChild($element->id);\n";
        } else {
            $parentId = $element->id;
        }

        return $code;
    }

    private static function response($data = "", $id = ""): object
    {
        return (object)[
            "id"   => $id,
            "data" => $data,
        ];
    }
}
