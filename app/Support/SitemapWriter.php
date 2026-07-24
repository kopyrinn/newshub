<?php

namespace App\Support;

use DateTimeInterface;
use RuntimeException;
use XMLWriter;

final class SitemapWriter
{
    private const SITEMAP_NAMESPACE = 'http://www.sitemaps.org/schemas/sitemap/0.9';

    private const XHTML_NAMESPACE = 'http://www.w3.org/1999/xhtml';

    private XMLWriter $writer;

    private string $temporaryPath;

    private bool $finished = false;

    public function __construct(private readonly string $path)
    {
        $directory = dirname($path);

        if (! is_dir($directory) && ! mkdir($directory, 0775, true) && ! is_dir($directory)) {
            throw new RuntimeException("Unable to create sitemap directory: {$directory}");
        }

        $this->temporaryPath = "{$path}.tmp";
        $this->writer = new XMLWriter;

        if (! $this->writer->openUri($this->temporaryPath)) {
            throw new RuntimeException("Unable to open sitemap file: {$this->temporaryPath}");
        }

        $this->writer->setIndent(true);
        $this->writer->startDocument('1.0', 'UTF-8');
        $this->writer->startElement('urlset');
        $this->writer->writeAttribute('xmlns', self::SITEMAP_NAMESPACE);
        $this->writer->writeAttribute('xmlns:xhtml', self::XHTML_NAMESPACE);
    }

    /**
     * @param  array<string, string>  $alternates
     */
    public function add(
        string $location,
        ?DateTimeInterface $lastModified,
        float $priority,
        array $alternates = [],
        ?string $changeFrequency = null
    ): void {
        $this->writer->startElement('url');
        $this->writer->writeElement('loc', $location);

        if ($lastModified !== null) {
            $this->writer->writeElement('lastmod', $lastModified->format(DateTimeInterface::ATOM));
        }

        if ($changeFrequency !== null) {
            $this->writer->writeElement('changefreq', $changeFrequency);
        }

        $this->writer->writeElement('priority', number_format($priority, 1, '.', ''));

        foreach ($alternates as $language => $url) {
            $this->writer->startElement('xhtml:link');
            $this->writer->writeAttribute('rel', 'alternate');
            $this->writer->writeAttribute('hreflang', $language);
            $this->writer->writeAttribute('href', $url);
            $this->writer->endElement();
        }

        $this->writer->endElement();
    }

    public function finish(): void
    {
        if ($this->finished) {
            return;
        }

        $this->writer->endElement();
        $this->writer->endDocument();
        $this->writer->flush();

        if (! rename($this->temporaryPath, $this->path)) {
            throw new RuntimeException("Unable to publish sitemap file: {$this->path}");
        }

        $this->finished = true;
    }
}
