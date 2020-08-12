<?php

namespace Helper;

use AbstractTestCase;
use App\Helper\UrlValidator;

class UrlValidatorTest extends AbstractTestCase
{
    /**
     * @test
     * @dataProvider ValidUrlDataProvider
     * @param string $url
     */
    public function testIsValidUrl(string $url)
    {
        $actual = UrlValidator::isValid($url);

        $this->assertEquals($url, $actual);
    }

    /**
     * @test
     * @dataProvider InvalidUrlDataProvider
     * @param string $url
     */
    public function testIsInValidUrl(string $url)
    {
        $actual = UrlValidator::isValid($url);

        $this->assertFalse($actual);
    }

    /**
     * @test
     */
    public function testEmptyUrl()
    {
        // Schema is not set, so it will return false in this case
        $actual = UrlValidator::isValid('');

        $this->assertFalse($actual);
    }

    /**
     * @test
     */
    public function testEmptyUrlWithFilledServerVars()
    {
        $_SERVER['REQUEST_SCHEME'] = 'http';
        $_SERVER['SERVER_NAME'] = 'foo.com';
        $_SERVER['REQUEST_URI'] = '/blah_blah/';
        // Schema is set, so it will return true in this case
        $actual = UrlValidator::isValid('');

        $this->assertEquals('http://foo.com/blah_blah/', $actual);
    }

    /**
     * @return array
     */
    public function InvalidUrlDataProvider(): array
    {
        return [
            // The following are valid url
            'http://✪df.ws/123' => ['Url' => 'http://✪df.ws/123'],
            'http://➡.ws/䨹' => ['Url' => 'http://➡.ws/䨹'],
            'http://⌘.ws' => ['Url' => 'http://⌘.ws'],
            'http://⌘.ws/' => ['Url' => 'http://⌘.ws/'],
            'http://foo.com/unicode_(✪)_in_parens' => ['Url' => 'http://foo.com/unicode_(✪)_in_parens'],
            'http://☺.damowmow.com/' => ['Url' => 'http://☺.damowmow.com/'],
            'http://مثال.إختبار' => ['Url' => 'http://مثال.إختبار'],
            'http://例子.测试' => ['Url' => 'http://例子.测试'],
            'http://उदाहरण.परीक्षा' => ['Url' => 'http://उदाहरण.परीक्षा'],
            'https://foo_bar.example.com/' => ['Url' => 'https://foo_bar.example.com/'],

            // The following are invalid url
            'http://' => ['Url' => 'http://'],
            'http://.' => ['Url' => 'http://.'],
            'http://..' => ['Url' => 'http://..'],
            'http://../' => ['Url' => 'http://../'],
            'http://?' => ['Url' => 'http://?'],
            'http://foo.bar?q=Spaces should be encoded' => ['Url' => 'http://foo.bar?q=Spaces should be encoded'],
        ];
    }

    /**
     * Url specs defined by https://url.spec.whatwg.org/
     * @return array
     */
    public function ValidUrlDataProvider(): array
    {
        return [
            'http://foo.com/blah_blah' => ['Url' => 'http://foo.com/blah_blah'],
            'http://foo.com/blah_blah/' => ['Url' => 'http://foo.com/blah_blah/'],
            'http://foo.com/blah_blah_(wikipedia)' => ['Url' => 'http://foo.com/blah_blah_(wikipedia)'],
            'http://foo.com/blah_blah_(wikipedia)_(again)' => ['Url' => 'http://foo.com/blah_blah_(wikipedia)_(again)'],
            'http://www.example.com/wpstyle/?p=364' => ['Url' => 'http://www.example.com/wpstyle/?p=364'],
            'https://www.example.com/foo/?bar=baz&inga=42&quux' => ['Url' => 'https://www.example.com/foo/?bar=baz&inga=42&quux'],
            'http://userid:password@example.com:8080' => ['Url' => 'http://userid:password@example.com:8080'],
            'http://userid:password@example.com:8080/' => ['Url' => 'http://userid:password@example.com:8080/'],
            'http://userid@example.com' => ['Url' => 'http://userid@example.com'],
            'http://userid@example.com/' => ['Url' => 'http://userid@example.com/'],
            'http://userid@example.com:8080' => ['Url' => 'http://userid@example.com:8080'],
            'http://userid:password@example.com' => ['Url' => 'http://userid:password@example.com'],
            'http://userid:password@example.com/' => ['Url' => 'http://userid:password@example.com/'],
            'http://142.42.1.1/' => ['Url' => 'http://142.42.1.1/'],
            'http://142.42.1.1:8080/' => ['Url' => 'http://142.42.1.1:8080/'],
            'http://foo.com/blah_(wikipedia)#cite-1' => ['Url' => 'http://foo.com/blah_(wikipedia)#cite-1'],
            'http://foo.com/blah_(wikipedia)_blah#cite-1' => ['Url' => 'http://foo.com/blah_(wikipedia)_blah#cite-1'],
            'http://foo.com/(something)?after=parens' => ['Url' => 'http://foo.com/(something)?after=parens'],
            'http://code.google.com/events/#&product=browser' => ['Url' => 'http://code.google.com/events/#&product=browser'],
            'http://j.mp' => ['Url' => 'http://j.mp'],
            'ftp://foo.bar/baz' => ['Url' => 'ftp://foo.bar/baz'],
            'http://foo.bar/?q=Test%20URL-encoded%20stuff' => ['Url' => 'http://foo.bar/?q=Test%20URL-encoded%20stuff'],
            'http://1337.net' => ['Url' => 'http://1337.net'],
            'http://a.b-c.de' => ['Url' => 'http://a.b-c.de'],
            'http://223.255.255.254' => ['Url' => 'http://223.255.255.254'],
            'http://-.~_!$&\'()*+,;=:%40:80%2f::::::@example.com' => ['Url' => 'http://-.~_!$&\'()*+,;=:%40:80%2f::::::@example.com'],
        ];
    }
}
