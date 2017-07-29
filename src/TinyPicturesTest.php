<?php
namespace TinyPictures;
require_once __DIR__ . '/../vendor/autoload.php';

class TinyPicturesTest extends \PHPUnit\Framework\TestCase {
    protected $defaultOptions = [
        'user' => 'demo',
        'protocol' => 'https',
        'namedSources' => [
            'main' => 'https://tiny.pictures/'
        ]
    ];

    public function testConstruct() {
        $tinyPictures = new TinyPictures($this->defaultOptions);
        $this->assertSame('demo', $tinyPictures->getUser());
        $this->assertSame('https', $tinyPictures->getProtocol());
        $this->assertSame('https://demo.tiny.pictures/', $tinyPictures->getBaseUrl());
        $this->assertSame(1, count($tinyPictures->getNamedSources()));
        $this->assertSame('https://tiny.pictures/', $tinyPictures->getNamedSources()['main']);
    }

    public function testConstructWithNullUser() {
        $options = $this->defaultOptions;
        $options['user'] = null;
        $this->expectException(\Exception::class);
        $tinyPictures = new TinyPictures($options);
    }

    public function testConstructWithoutUser() {
        $options = $this->defaultOptions;
        unset($options['user']);
        $this->expectException(\Exception::class);
        $tinyPictures = new TinyPictures($options);
    }

    public function testConstructWithoutProtocol() {
        $options = $this->defaultOptions;
        unset($options['protocol']);
        $tinyPictures = new TinyPictures($options);
        $this->assertSame('https', $tinyPictures->getProtocol());
    }

    public function testConstructWithoutNamedSources() {
        $options = $this->defaultOptions;
        unset($options['namedSources']);
        $tinyPictures = new TinyPictures($options);
        $this->assertSame([], $tinyPictures->getNamedSources());
    }

    public function testUrlShouldThrowIfNoSourceIsSet() {
        $tinyPictures = new TinyPictures($this->defaultOptions);
        $this->expectException(\ArgumentCountError::class);
        $result = $tinyPictures->url();
    }

    public function testUrlShouldReturnTinyPicturesUrlWithNamedSource() {
        $tinyPictures = new TinyPictures($this->defaultOptions);
        $this->assertSame('https://demo.tiny.pictures/main/example1.jpg', $tinyPictures->url('https://tiny.pictures/example1.jpg'));
    }

    public function testUrlShouldReturnTinyPicturesUrlWithoutNamedSource() {
        $tinyPictures = new TinyPictures($this->defaultOptions);
        $this->assertSame('https://demo.tiny.pictures/?source=https%3A%2F%2Fother.domain%2Fexample1.jpg', $tinyPictures->url('https://other.domain/example1.jpg'));
    }

    public function testUrlShouldReturnTinyPicturesUrlWithWidth() {
        $tinyPictures = new TinyPictures($this->defaultOptions);
        $this->assertSame('https://demo.tiny.pictures/main/example1.jpg?width=200', $tinyPictures->url('https://tiny.pictures/example1.jpg', ['width' => 200]));
    }
}
