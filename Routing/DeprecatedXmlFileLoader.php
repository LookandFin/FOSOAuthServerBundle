<?php

declare(strict_types=1);

/*
 * This file is part of the FOSOAuthServerBundle package.
 *
 * (c) klapaudius <klapaudius@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\OAuthServerBundle\Routing;

use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;

/**
 * Custom XML routing loader that triggers deprecation warnings for XML routing files.
 *
 * This loader detects when deprecated XML routing files (authorize.xml, token.xml)
 * are being loaded and triggers E_USER_DEPRECATED warnings to guide users toward
 * using the YAML equivalents.
 */
class DeprecatedXmlFileLoader extends YamlFileLoader
{
    /**
     * List of deprecated XML routing files that should trigger warnings.
     */
    private const DEPRECATED_FILES = [
        'authorize.xml',
        'token.xml',
    ];

    public function load(mixed $file, ?string $type = null): RouteCollection
    {
        $filename = basename($file);

        if (in_array($filename, self::DEPRECATED_FILES, true)) {
            $yamlFile = str_replace('.xml', '.yaml', $filename);

            trigger_deprecation(
                'klapaudius/oauth-server-bundle',
                '5.1',
                'Loading OAuth routing from XML file "%s" is deprecated. Use the YAML file "@FOSOAuthServerBundle/Resources/config/routing/%s" instead.',
                $filename,
                $yamlFile
            );
        }
        $yamlFile = str_replace('.xml', '.yaml', $file);

        return parent::load($yamlFile, $type);
    }

    public function supports(mixed $resource, ?string $type = null): bool
    {
        if (! is_string($resource)) {
            return false;
        }

        $filename = basename($resource);
        $yamlFile = str_replace('.xml', '.yaml', $resource);

        return parent::supports($yamlFile, 'yaml')
            && in_array($filename, self::DEPRECATED_FILES, true);
    }
}
