<?php

namespace Statamic\Sites;

use Statamic\Support\Str;


class Site
{
    protected $handle;
    protected $config;

    public function __construct($handle, $config)
    {
        $this->handle = $handle;
        $this->config = $config;
    }

    public function handle()
    {
        return $this->handle;
    }

    public function name()
    {
        return $this->config['name'];
    }

    public function locale()
    {
        return $this->config['locale'];
    }

    public function shortLocale()
    {
        return explode('-', str_replace('_', '-', $this->locale()))[0];
    }

    public function url()
    {
        $url = $this->config['url'];

        if ($url === '/') {
            return '/';
        }

        return Str::removeRight($url, '/');
    }

    public function absoluteUrl()
    {
        if (Str::startsWith($url = $this->url(), '/')) {
            $url = Str::ensureLeft($url, request()->getSchemeAndHttpHost());
        }

        return Str::removeRight($url, '/');
    }

    public function relativePath($url)
    {
        $url = Str::ensureRight($url, '/');

        $path = Str::removeLeft($url, $this->absoluteUrl());

        $path = Str::removeRight(Str::ensureLeft($path, '/'), '/');

        return $path === '' ? '/' : $path;
    }

    private function removePath($url)
    {
        $parsed = parse_url($url);

        return $parsed['scheme'] . '://' . $parsed['host'];
    }
}
