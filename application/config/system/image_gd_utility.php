<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$image_gd_utility['allow_formats'] = array ('gif', 'jpg', 'png');
$image_gd_utility['mime_formats'] = array ('image/gif' => 'gif',
                                            'image/jpeg' => 'jpg',
                                            'image/pjpeg' => 'jpg',
                                            'image/png' => 'png',
                                            'image/x-png' => 'png');

$image_gd_utility['d4_options'] = array ('interlace' => null,
                                          'resizeUp' => true,
                                          'jpegQuality' => 90,
                                          'preserveAlpha' => true,
                                          'preserveTransparency' => true,
                                          'alphaMaskColor' => array (255, 255, 255),
                                          'transparencyMaskColor' => array (0, 0, 0));