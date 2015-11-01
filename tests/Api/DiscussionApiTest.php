<?php

namespace Swader\Diffbot\Test\Api;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class DiscussionApiTest extends \PHPUnit_Framework_TestCase
{
    use setterUpper;

    protected $validMock;

    /**
     * @var \Swader\Diffbot\Api\Discussion
     */
    protected $apiWithMock;

    protected function setUp()
    {
        $diffbot = $this->preSetUp();

        $this->apiWithMock = $diffbot->createDiscussionAPI('https://discussion-mock.com');
    }

    protected function getValidMock()
    {
        if (!$this->validMock) {
            $this->validMock = new MockHandler([
                new Response(200, [],
                    file_get_contents(__DIR__ . '/../Mocks/Discussions/15-05-01/sp_discourse_php7_recap.json'))
            ]);
        }

        return $this->validMock;
    }

    public function testCall()
    {
        $this->apiWithMock->call();
    }

    public function testBuildUrlNoCustomFields()
    {
        $url = $this
            ->apiWithMock
            ->buildUrl();
        $expectedUrl = 'https://api.diffbot.com/v3/discussion?token=demo&url=https%3A%2F%2Fdiscussion-mock.com';
        $this->assertEquals($expectedUrl, $url);
    }

    public function testBuildUrlOneCustomField()
    {
        $url = $this
            ->apiWithMock
            ->setMeta(true)
            ->buildUrl();
        $expectedUrl = 'https://api.diffbot.com/v3/discussion?token=demo&url=https%3A%2F%2Fdiscussion-mock.com&fields=meta';
        $this->assertEquals($expectedUrl, $url);
    }

    public function testBuildUrlTwoCustomFields()
    {
        $url = $this
            ->apiWithMock
            ->setMeta(true)
            ->setLinks(true)
            ->buildUrl();
        $expectedUrl = 'https://api.diffbot.com/v3/discussion?token=demo&url=https%3A%2F%2Fdiscussion-mock.com&fields=meta,links';
        $this->assertEquals($expectedUrl, $url);
    }

    public function testBuildUrlFourCustomFields()
    {
        $url = $this
            ->apiWithMock
            ->setMeta(true)
            ->setLinks(true)
            ->setBreadcrumb(true)
            ->setQuerystring(true)
            ->setSentiment(true)
            ->buildUrl();
        $expectedUrl = 'https://api.diffbot.com/v3/discussion?token=demo&url=https%3A%2F%2Fdiscussion-mock.com&fields=meta,links,breadcrumb,querystring,sentiment';
        $this->assertEquals($expectedUrl, $url);
    }

    public function testBuildUrlOtherOptionsOnly()
    {
        $url = $this->apiWithMock
            ->setMaxPages(10)
            ->buildUrl();

        $expectedUrl = 'https://api.diffbot.com/v3/discussion?token=demo&url=https%3A%2F%2Fdiscussion-mock.com&maxPages=10';
        $this->assertEquals($expectedUrl, $url);
    }

    public function testBuildUrlOtherOptionsAndCustomFields()
    {
        $url = $this
            ->apiWithMock
            ->setMeta(true)
            ->setLinks(true)
            ->setBreadcrumb(true)
            ->setQuerystring(true)
            ->setMaxPages('all')
            ->buildUrl();
        $expectedUrl = 'https://api.diffbot.com/v3/discussion?token=demo&url=https%3A%2F%2Fdiscussion-mock.com&fields=meta,links,breadcrumb,querystring&maxPages=all';
        $this->assertEquals($expectedUrl, $url);
    }

}
