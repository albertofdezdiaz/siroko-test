<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Shared\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Bridge\Doctrine\Types\AbstractUidType;

use Symfony\Component\Uid\Uuid;

final class BinaryUuidType extends AbstractUidType
{
    public const NAME = 'uuid';

    public function getName(): string
    {
        return self::NAME;
    }

    protected function getUidClass(): string
    {
        return Uuid::class;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getBinaryTypeDeclarationSQL([
            'length' => 16,
            'fixed' => true,
        ]);
    }

    /**
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof AbstractUid || method_exists($value, 'toBinary')) {
            return $value->toBinary();
        }

        if (null === $value || '' === $value) {
            return null;
        }

        if (!\is_string($value)) {
            throw new ConversionException(sprintf(
                'Expected a string or an instance of "%s", got "%s".',
                $this->getUidClass(),
                \is_object($value) ? \get_class($value) : \gettype($value)
            ));
        }

        try {
            return $this->getUidClass()::fromString($value)->toBinary();
        } catch (\InvalidArgumentException $e) {
            throw new ConversionException(sprintf(
                'Could not convert database value "%s" to Doctrine Type %s. Expected format: %s',
                $value,
                $this->getName(),
                $this->getUidClass()
            ), 0, $e);
        }
    }
}