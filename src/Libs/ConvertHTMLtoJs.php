<?php

namespace Souravmsh\HtmlToJs\Libs;

use DOMDocument;

class ConvertHTMLtoJs
{

    public static function convert($html)
    {
        return self::response(self::process($html));
    }

    private static function process($html)
    {
        if(is_string($html)) {
            $type = "string";
        } else {
            $type = get_class($html);
        }

        $result = [
            "code" => "",
            "id"   => ""
        ];

        switch($type){
            case "DOMDocument":
            case "DOMElement":
                $id = $html->nodeName."_".md5(uniqid())."_element";
                if($html->nodeName != "#document"){
                    $code = "var ".$id." = document.createElement('".$html->nodeName."');\n";
                }
                else{
                    $code = "";
                }
                if(!!$html->attributes){ 
                    foreach($html->attributes as $attr){
                        $code .= $id.".setAttribute('".$attr->name."', '".$attr->value."');\n";
                    }
                }
                if(!!$html->childNodes){
                    foreach($html->childNodes as $child){
                        if($child->nodeType == XML_TEXT_NODE){
                            $code .= $id.".appendChild(document.createTextNode('".htmlentities($child->nodeValue)."'));\n";
                        }
                        else{
                            $element = self::process($child);
                            $code .= $element["code"];
                            if($html->nodeName != "#document"){
                                $code .= $id.".appendChild(".$element["id"].");\n";
                            }
                            else{
                                $id = $element["id"];
                            }
                        }
                    }
                }
                $result = [
                    "code" => $code,
                    "id"   => $id
                ];
                break;
            case "DOMDocumentType":
                break;
            default:
            case "string":
                $dom = new DOMDocument();
                $dom->strictErrorChecking = FALSE;
                $dom->loadHTML($html);
                $result = self::process($dom);
                break;
        } 

        return $result;
    }

    private static function response($result): object
    {
        $data = [];
        if (is_array($result)) {
            $data = [
                "code" => !empty($result["code"]) ? $result["code"] : $result,
                "id"   => !empty($result["id"]) ? $result["id"] : null,
            ];
        } else {
            $data = [
                "id"   => null, 
                "code" => $result
            ];
        }

        return (object)$data;
    }
}
