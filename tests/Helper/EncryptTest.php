<?php

namespace Helper;

use AbstractTestCase;
use App\Helper\Encrypt;
use App\Infrastructure\PrivateKeyProvider\PrivateKeyProvider;

class EncryptTest extends AbstractTestCase
{
    const LENGTH_OF_ENCRYPTED_HASH = 100;
    /**
     * @dataProvider EncryptionDataProvider
     * @param string $privateKey
     * @param string $value
     * @param string $expected
     */
    public function testEncryption(string $privateKey, string $value, string $expected)
    {
        $privateKey = new PrivateKeyProvider($privateKey);
        $actual = Encrypt::encrypt($privateKey, $value);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider EncryptionDataProvider
     * @param string $privateKey
     * @param string $value
     * @param string $expected
     */
    public function testLengthIsExpected(string $privateKey, string $value, string $expected)
    {
        $privateKey = new PrivateKeyProvider($privateKey);
        $actual = Encrypt::encrypt($privateKey, $value);

        $this->assertEquals(self::LENGTH_OF_ENCRYPTED_HASH, strlen($actual));
    }

    /**
     * @dataProvider EncryptionDataProvider
     * @param string $privateKey
     * @param string $expected
     * @param string $encryptedString
     */
    public function testDecryption(string $privateKey, string $expected, string $encryptedString)
    {
        $privateKey = new PrivateKeyProvider($privateKey);
        $actual = Encrypt::decrypt($privateKey, $encryptedString);
        $this->assertEquals($expected, $actual);
    }

    public function EncryptionDataProvider()
    {
        return [
            'somevalue' => ['d74f07ce2f504f7f7d71a74deae2dd0adc9322777b2e6250daef3db17b6982ce', 'somevalue', 'ZDc0ZjA3Y2UyZjUwNGY3ZjdkNzFhNzRkZWFlMmRkMGFkYzkzMjI3NzdiMmU2MjUwZGFlZjNkYjE3YjY5ODJjZSBzb21ldmFsdWU='],
            'hash' => ['d74f07ce2f504f7f7d71a74deae2dd0adc9322777b2e6250daef3db17b6982ce', 'othervalue', 'ZDc0ZjA3Y2UyZjUwNGY3ZjdkNzFhNzRkZWFlMmRkMGFkYzkzMjI3NzdiMmU2MjUwZGFlZjNkYjE3YjY5ODJjZSBvdGhlcnZhbHVl'],
        ];
    }
}
