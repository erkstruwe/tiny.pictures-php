<?php
namespace TinyPictures;

class TinyPictures {
    protected $user;
    public function getUser() {
        return $this->user;
    }
    protected $protocol;
    public function getProtocol() {
        return $this->protocol;
    }
    protected $baseUrl;
    public function getBaseUrl() {
        return $this->baseUrl;
    }
    protected $namedSources;
    public function getNamedSources() {
        return $this->namedSources;
    }

    public function __construct(array $options) {
        if (!isset($options['user']) || !$options['user']) {
            throw new \Exception('No user set');
        }
        $this->user = $options['user'];
        $this->protocol = isset($options['protocol']) && $options['protocol'] ? $options['protocol'] : 'https';
        $this->namedSources = isset($options['namedSources']) && $options['namedSources'] ? $options['namedSources'] : [];

        $this->baseUrl = $this->protocol . '://' . $this->user . '.tiny.pictures/';
    }

    public function url(string $source, array $options = []) {
        $tinyPicturesUrl = $this->baseUrl;
        $query = [];
        $namedSource = $this->findNamedSource($source);
        if ($namedSource) {
            $path = substr($source, strlen($namedSource['url']));
            $tinyPicturesUrl .= $namedSource['name'] . '/' . $path;
        } else {
            $query['source'] = $source;
        }
        $query = $query + $options;
        return $tinyPicturesUrl . (count($query) ? '?' . http_build_query($query) : '');
    }

    protected function findNamedSource(string $source) {
        foreach ($this->namedSources as $namedSourceName => $namedSourceUrl) {
            if (substr($source, 0, strlen($namedSourceUrl)) === $namedSourceUrl) {
                return [
                    'name' => $namedSourceName,
                    'url' => $namedSourceUrl
                ];
            }
        }
        return null;
    }
}
