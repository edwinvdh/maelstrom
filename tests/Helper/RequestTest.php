<?php

namespace Helper;

use AbstractTestCase;
use App\Helper\Request;

class RequestTest extends AbstractTestCase
{
    /**
     * Basic test to check if we can load Request Class
     */
    public function testIsInstanceOf()
    {
        $actual = new Request();
        $this->assertInstanceOf(Request::class, $actual);
    }

    /**
     * @dataProvider RequestSetDataProvider
     * @param $key
     * @param $value
     */
    public function testCanRequestSet(string $key, string $value)
    {
        $request = new Request();
        $request->set($key, $value);
        $actual = $request->get($key);
        $this->assertEquals($value, $actual);
    }

    /**
     * @dataProvider RequestSetDataProvider
     * @param $key
     * @param $value
     */
    public function testCanRequestUseGlobalGet(string $key, string $value)
    {
        $_GET[$key] = $value;
        $request = new Request();
        $actual = $request->get($key);
        $this->assertEquals($value, $actual);
    }

    /**
     * @dataProvider RequestSetDataProvider
     * @param $key
     * @param $value
     */
    public function testCanRequestUseGlobalPost(string $key, string $value)
    {
        $_POST[$key] = $value;
        $request = new Request();
        $actual = $request->get($key);
        $this->assertEquals($value, $actual);
    }

    /**
     * @dataProvider RequestSetDeleteDataProvider
     * @param string $value
     * @param string $delete
     */
    public function testCanRequestUseDelete(string $value, string $delete)
    {
        $_POST['key'] = $value;
        $_POST['delete'] = $delete;
        $request = new Request();
        $actual = $request->isDelete();
        $this->assertEquals(true, $actual);
    }

    /**
     * @return array
     */
    public function RequestSetDataProvider(): array
    {
        return [
            'RequestSet' => ['key', 'value'],
        ];
    }

    /**
     * @return array
     */
    public function RequestSetDeleteDataProvider(): array
    {
        return [
            'RequestSet' => [ 'key' => 'value', 'delete' => 1],
        ];
    }
}
