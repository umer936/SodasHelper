<?php
declare(strict_types=1);

namespace SodasHelper\View\Helper;

use Cake\View\Helper;

/**
 * WebP helper
 */
class WebPHelper extends Helper
{
    public $helpers = ['Html', 'Url'];
    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected $_defaultConfig = [];

// Use instead of Html->image
// can pass an alt_ext that has a . in it otherwise .png!
// Path can be with/without .webp

    public function image($path, array $options): string
    {
        $ret_val = '';
        if (array_key_exists('alt_ext', $options)) {
            $alt_extension = $options ['alt_ext'];
        } else {
            $alt_extension = '.png';
        }
        unset ($options ['alt_ext']);
        $path_no_ext = preg_replace('/.webp$/', '', $path);
        $ret_val .= "<picture><source type='image/webp' srcset='" . $this->Url->build('/img/' . $path . '.webp', $options) . "'>";
        $ret_val .= $this->Html->image($path_no_ext . $alt_extension, $options);
        $ret_val .= "</picture>";

        return $ret_val;
    }

// Use instead of Url->build
// Must pass an alt_ext that has a . in it!
    public function build($url, array $options): string
    {
        $ret_val = '';
        $alt_extension = $options ['alt_ext'];
        unset ($options ['alt_ext']);
        $path_no_ext = preg_replace('/.webp$/', '', $path);
        $ret_val .= $this->Url->build($path_no_ext . $alt_extension, $options);

        return $ret_val;
    }

// produce a CSS string which has a fallback
// Send in image with no extension and the other extension with a . in it
    public function backgroundImage($image, $alt_ext = '.png')
    {
        $url_webp = $this->Url->build('/img/' . $image . '.webp');
        $url_alt = $this->Url->build('/img/' . $image . $alt_ext);

        return "background-image: image-set('$url_webp') type('image/webp'); background-image: -webkit-image-set ('$url_webp' 1x); background-image: url('$url_alt');";
    }
}
