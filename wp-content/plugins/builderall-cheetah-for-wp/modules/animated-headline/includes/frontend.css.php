<?php
$nodeId = '.ba-cheetah-node-'.$id;

BACheetahCSS::typography_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'typography',
	'selector'     => ".ba-cheetah-content $nodeId .ba-module__animated-headline *",
) );


?>

/**/
<?php
//
//$obj_reference = [
//        'slidingVertical'=>'topToBottom',
//        'slidingHorizontal'=>'leftToRight',
//        'fadeIn'=>'fadeEffect',
//        'verticalFlip'=>'vertical',
//        'horizontalFlip'=>'horizontal',
//        'antiClock'=>'anti',
//        'clockWise'=>'clock',
//        'popEffect'=>'pop',
//        'pushEffect'=>'push'
//];
//
//
// foreach ($settings->items as $key => $item) :
//
//
//            if ($item->type == 'words'):
//
//                echo $nodeId.' .hasEffect-'.$key.'.'.$item->effect.' span {
//                                    animation: '.$obj_reference[$item->effect].' '.count($item->text_items) * 2.5.'s linear infinite 0s;
//                                    -ms-animation: '.$obj_reference[$item->effect].' '.count($item->text_items) * 2.5.'s linear infinite 0s;
//                                    -webkit-animation: '.$obj_reference[$item->effect].' '.count($item->text_items) * 2.5.'s linear infinite 0s;
//                                    opacity: 0;
//                                    overflow: hidden;
//                                    position: absolute;
//                                }';
//
//                foreach ($item->text_items as $subKey => $subItem):
//
//                    echo $nodeId.' .hasEffect-'.$key.'.'.$item->effect.' span:nth-child('.($subKey+1) .') {
//                            animation-delay: '.($subKey * 2.5).'s;
//                            -ms-animation-delay: '.($subKey * 2.5).'s;
//                            -webkit-animation-delay: '.($subKey * 2.5).'s;
//                        }';
//
//                endforeach;
//
//            else:
//                if ($item->type !== 'disabled' && $item->effect !== 'none'):
//                   echo $nodeId.' .hasEffect-'.$key.'.'.$item->effect.' span {
//                                animation: '.$obj_reference[$item->effect].' 12.5s linear infinite 0s;
//                                -ms-animation: '.$obj_reference[$item->effect].' 12.5s linear infinite 0s;
//                                -webkit-animation: '.$obj_reference[$item->effect].' 12.5s linear infinite 0s;
//                                opacity: 0;
//                                overflow: hidden;
//                                position: absolute;
//                            }';
//
//                   echo $nodeId.' .hasEffect-'.$key.'.'.$item->effect.' span:nth-child(1) {
//                                animation-delay: 0s;
//                                -ms-animation-delay: 0s;
//                                -webkit-animation-delay: 0s;
//                            }';
//                   echo $nodeId.' .hasEffect-'.$key.'.'.$item->effect.' span:nth-child(2) {
//                                animation-delay: 2.5s;
//                                -ms-animation-delay: 2.5s;
//                                -webkit-animation-delay: 2.5s;
//                            }';
//                   echo $nodeId.' .hasEffect-'.$key.'.'.$item->effect.' span:nth-child(3) {
//                                animation-delay: 5s;
//                                -ms-animation-delay: 5s;
//                                -webkit-animation-delay: 5s;
//                            }';
//                   echo $nodeId.' .hasEffect-'.$key.'.'.$item->effect.' span:nth-child(4) {
//                                animation-delay: 7.5s;
//                                -ms-animation-delay: 7.5s;
//                                -webkit-animation-delay: 7.5s;
//                            }';
//                   echo $nodeId.' .hasEffect-'.$key.'.'.$item->effect.' span:nth-child(5) {
//                                animation-delay: 10s;
//                                -ms-animation-delay: 10s;
//                                -webkit-animation-delay: 10s;
//                            }';
//
//                endif;
//            endif;
//
//        endforeach; ?>
