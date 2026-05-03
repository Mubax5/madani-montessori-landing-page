<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;

class HtmlSanitizer
{
    private const ALLOWED_TAGS = [
        'a',
        'blockquote',
        'br',
        'em',
        'h2',
        'h3',
        'h4',
        'li',
        'ol',
        'p',
        'strong',
        'ul',
    ];

    public static function clean(?string $html): string
    {
        if (blank($html)) {
            return '';
        }

        $document = new DOMDocument;
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML(
            '<?xml encoding="UTF-8"><!DOCTYPE html><html><body>' . $html . '</body></html>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD,
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $xpath = new DOMXPath($document);

        foreach (iterator_to_array($xpath->query('//*')) as $node) {
            if (! $node instanceof DOMElement) {
                continue;
            }

            if (! in_array($node->nodeName, self::ALLOWED_TAGS, true)) {
                self::unwrap($node);

                continue;
            }

            self::sanitizeAttributes($node);
        }

        $body = $document->getElementsByTagName('body')->item(0);
        $output = '';

        foreach ($body?->childNodes ?? [] as $child) {
            $output .= $document->saveHTML($child);
        }

        return $output;
    }

    private static function unwrap(DOMNode $node): void
    {
        $parent = $node->parentNode;

        if (! $parent) {
            return;
        }

        while ($node->firstChild) {
            $parent->insertBefore($node->firstChild, $node);
        }

        $parent->removeChild($node);
    }

    private static function sanitizeAttributes(DOMElement $node): void
    {
        $href = trim((string) $node->getAttribute('href'));

        foreach (iterator_to_array($node->attributes) as $attribute) {
            $node->removeAttribute($attribute->nodeName);
        }

        if ($node->nodeName !== 'a') {
            return;
        }

        if ($href === '' || ! self::isSafeUrl($href)) {
            return;
        }

        $node->setAttribute('href', $href);
        $node->setAttribute('rel', 'noopener noreferrer');

        if (str_starts_with($href, 'http://') || str_starts_with($href, 'https://')) {
            $node->setAttribute('target', '_blank');
        }
    }

    private static function isSafeUrl(string $url): bool
    {
        return str_starts_with($url, '/')
            || str_starts_with($url, '#')
            || str_starts_with($url, 'http://')
            || str_starts_with($url, 'https://')
            || str_starts_with($url, 'mailto:')
            || str_starts_with($url, 'tel:');
    }
}
