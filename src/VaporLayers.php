<?php
/**
 * @author Aaron Francis <aarondfrancis@gmail.com|https://twitter.com/aarondfrancis>
 */

namespace Hammerstone\Sidecar\PHP;

use Exception;
use Hammerstone\Sidecar\Region;
use Illuminate\Support\Arr;

class VaporLayers
{
    const PHP_74 = 'php-74al2';
    const PHP_80 = 'php-80al2';
    const PHP_81 = 'php-81al2';
    const PHP_82 = 'php-81al2';

    /**
     * @var string
     */
    public static $defaultPhpVersion;

    public static $layers = [
        Region::US_EAST_1 => [
            self::PHP_74 => 3, self::PHP_80 => 4, self::PHP_81 => 2, self::PHP_82,
        ],
        Region::US_EAST_2 => [
            self::PHP_74 => 3, self::PHP_80 => 4, self::PHP_81 => 2,self::PHP_82,
        ],
        Region::US_WEST_1 => [
            self::PHP_74 => 3, self::PHP_80 => 4, self::PHP_81 => 2,self::PHP_82,
        ],
        Region::US_WEST_2 => [
            self::PHP_74 => 3, self::PHP_80 => 4, self::PHP_81 => 2,self::PHP_82,
        ],
        Region::AP_SOUTH_1 => [
            self::PHP_74 => 3, self::PHP_80 => 4, self::PHP_81 => 2,self::PHP_82,
        ],
        Region::AP_NORTHEAST_3 => [
            self::PHP_74 => 2, self::PHP_80 => 2, self::PHP_81 => 2,self::PHP_82,
        ],
        Region::AP_NORTHEAST_2 => [
            self::PHP_74 => 4, self::PHP_80 => 5, self::PHP_81 => 2,self::PHP_82,
        ],
        Region::AP_SOUTHEAST_1 => [
            self::PHP_74 => 3, self::PHP_80 => 4, self::PHP_81 => 2,self::PHP_82,
        ],
        Region::AP_SOUTHEAST_2 => [
            self::PHP_74 => 3, self::PHP_80 => 4, self::PHP_81 => 2,self::PHP_82,
        ],
        Region::AP_NORTHEAST_1 => [
            self::PHP_74 => 4, self::PHP_80 => 5, self::PHP_81 => 2,self::PHP_82,
        ],
        Region::CA_CENTRAL_1 => [
            self::PHP_74 => 2, self::PHP_80 => 4, self::PHP_81 => 2,self::PHP_82,
        ],
        Region::EU_CENTRAL_1 => [
            self::PHP_74 => 3, self::PHP_80 => 4, self::PHP_81 => 2,self::PHP_82,
        ],
        Region::EU_WEST_1 => [
            self::PHP_74 => 3, self::PHP_80 => 4, self::PHP_81 => 2,self::PHP_82,
        ],
        Region::EU_WEST_2 => [
            self::PHP_74 => 3, self::PHP_80 => 4, self::PHP_81 => 2,self::PHP_82,
        ],
        Region::EU_WEST_3 => [
            self::PHP_74 => 4, self::PHP_80 => 5, self::PHP_81 => 1,self::PHP_82,
        ],
        Region::EU_NORTH_1 => [
            self::PHP_74 => 3, self::PHP_80 => 4, self::PHP_81 => 2,self::PHP_82,
        ],
        Region::SA_EAST_1 => [
            self::PHP_74 => 3, self::PHP_80 => 4, self::PHP_81 => 2,self::PHP_82,
        ],
    ];

    public static function phpVersions()
    {
        return [
            self::PHP_74,
            self::PHP_80,
            self::PHP_81,
            self::PHP_82
        ];
    }

    public static function find($version = null, $region = null)
    {
        $version = $version ?? static::guessPhpVersion();
        $region = $region ?? config('sidecar.aws_region');

        $revision = Arr::get(static::$layers, "$region.$version", false);

        if ($revision === false) {
            throw new Exception("Unable to find PHP layer for $version in $region.");
        }

        return "arn:aws:lambda:{$region}:959512994844:layer:vapor-$version:$revision";
    }

    protected static function guessPhpVersion()
    {
        if (static::$defaultPhpVersion) {
            return static::$defaultPhpVersion;
        }

        if (PHP_VERSION_ID >= 70400 && PHP_VERSION_ID < 70500) {
            return static::PHP_74;
        }

        if (PHP_VERSION_ID >= 80000 && PHP_VERSION_ID < 80100) {
            return static::PHP_80;
        }

        if (PHP_VERSION_ID >= 80100 && PHP_VERSION_ID < 80200) {
            return static::PHP_81;
        }
        
        if (PHP_VERSION_ID >= 80200 && PHP_VERSION_ID < 80300) {
            return static::PHP_82;
        }

        throw new Exception('Unable to guess the correct PHP layer to use.');
    }

}
