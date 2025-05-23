<?php

namespace EncryptDecrypt\Database\Type;

use EncryptDecrypt\Traits\EncryptDecrypt;
use Cake\Database\DriverInterface;
use Cake\Database\Type\BaseType;
use InvalidArgumentException;
use PDO;

/**
 * Class EncryptType
 * @package App\Model\Types
 */
class EncryptType extends BaseType
{

    use EncryptDecrypt;

    /**
     * @param $value
     * @param Driver $driver
     * @return mixed|string|null
     */
    public function toDatabase($value, \Cake\Database\Driver $driver): mixed
    {

        if ($value === null) {
            return null;
        };

        if (is_string($value)) {
            return $this->encrypt($value);
        }

        if (is_object($value) && method_exists($value, '__toString')) {
            return $this->encrypt($value->__toString());
        }

        if (is_scalar($value)) {
            return $this->encrypt((string)$value);
        }

        throw new InvalidArgumentException('Failed to encrypt data');
    }

    /**
     * @param mixed $value
     * @param Driver $driver
     * @return mixed|string|null
     */
    public function toPHP($value,  \Cake\Database\Driver $driver): mixed
    {
        if ($value === null) {
            return null;
        }
        return $this->decrypt($value);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function marshal($value): mixed
    {
        if ($value === null) {
            return null;
        }

        if (is_array($value)) {
            return '';
        }

        return $value;
    }

    /**
     * @param $value
     * @param Driver $driver
     * @return int|mixed
     */
    public function toStatement($value,  \Cake\Database\Driver $driver): int
    {
        if ($value === null) {
            return PDO::PARAM_NULL;
        }

        return PDO::PARAM_STR;
    }
}
