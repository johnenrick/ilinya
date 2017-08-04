<?php

namespace App\Ilinya\Facebook; 

class GenericTemplate{

  public static function toArray($text, $elements, $buttons, $defaultAction){
    $resultButtons = [];
    foreach ($buttons as $button) {
      $resultButtons[] = $button instanceof ButtonElement? $button->toArray(): $button;
    }

    $resultDefaultAction = [
      "type"  => $defaultAction['type'],
      "url"   => $defaultAction['url'],
      "messenger_extension" => true,
      "webview_height_ratio"  => $defaultAction['webview_height_ratio'],
      "fallback_url"  => $defaultAction['fallback_url']
    ];
 
    $resultElements = [
      "title"   => $elements['title'],
      "image_url" => $elements['imageUrl'],
      "subtitle"  => $elements['subtitle'],
      "default_action" => $resultDefaultAction,
      "buttons" => $resultButtons
    ];

    $response =  [
      "attachment"  => [
        "type"      => 'template',
        "payload"   =>  [
          "template_type" => "generic",
          "elements"       => $resultElements
        ]
      ]
    ];
    return $response;
  }

}
