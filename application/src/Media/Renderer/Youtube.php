<?php
namespace Omeka\Media\Renderer;

use Omeka\Api\Representation\MediaRepresentation;
use Zend\Uri\Http as HttpUri;
use Zend\View\Renderer\PhpRenderer;

class Youtube extends AbstractRenderer
{
    const WIDTH = 420;
    const HEIGHT = 315;
    const ALLOWFULLSCREEN = true;

    public function render(PhpRenderer $view, MediaRepresentation $media,
        array $options = array()
    ) {
        if (!isset($options['width'])) {
            $options['width'] = self::WIDTH;
        }
        if (!isset($options['height'])) {
            $options['height'] = self::HEIGHT;
        }
        if (!isset($options['allowfullscreen'])) {
            $options['allowfullscreen'] = self::ALLOWFULLSCREEN;
        }

        // Compose the YouTube embed URL and build the markup.
        $data = $media->mediaData();
        $url = new HttpUri(sprintf('https://www.youtube.com/embed/%s', $data['id']));
        $url->setQuery(array('start' => $data['start'], 'end' => $data['end']));
        $embed = sprintf(
            '<iframe width="%s" height="%s" src="%s" frameborder="0"%s></iframe>',
            $view->escapeHtml($options['width']),
            $view->escapeHtml($options['height']),
            $view->escapeHtml($url),
            $options['allowfullscreen'] ? ' allowfullscreen' : ''
        );
        return $embed;
    }
}

