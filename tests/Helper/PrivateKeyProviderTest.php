<?php

namespace Helper;

use AbstractTestCase;
use App\Infrastructure\PrivateKeyProvider\PrivateKeyProvider;

class PrivateKeyProviderTest extends AbstractTestCase
{
    /**
     * Test case to show case the implementation of the private key provider
     */
    public function testPrivateKeyProvider()
    {
        $privateKeyProvider = new PrivateKeyProvider(getenv(PrivateKeyProvider::PRIVATE_KEY));
        $actual = $privateKeyProvider->getPrivateKey();
        $this->assertEquals('d74f07ce2f504f7f7d71a74deae2dd0adc9322777b2e6250daef3db17b6982ce', $actual);
    }

    /**
     * @dataProvider privateKeyDataProvider
     * @param string $key
     */
    public function testPrivateKeyWithDataProvider(string $key)
    {
        $privateKeyProvider = new PrivateKeyProvider($key);
        $actual = $privateKeyProvider->getPrivateKey();
        $this->assertEquals(12345, $actual);
    }

    /**
     * @return array
     */
    public function privateKeyDataProvider(): array
    {
        return [
            'privateKey' => ['key' => '12345']
        ];
    }
}
