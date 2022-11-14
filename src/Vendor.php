<?php

namespace Mitie;

class Vendor
{
    public const VERSION = '0.7';

    public const PLATFORMS = [
        'x86_64-linux' => [
            'file' => 'libmitie.so',
            'checksum' => '07b241d857a4bcd7fd97b68a87ccb06fbab70bfc621ee25aa0ea6bd7f905c45c'
        ],
        'x86_64-darwin' => [
            'file' => 'libmitie.dylib',
            'checksum' => '8c4fdbe11ef137c401141242af8030628672d64589b5e63ba9c13b7162d29d6c'
        ],
        'arm64-darwin' => [
            'file' => 'libmitie.arm64.dylib',
            'checksum' => '616117825ac8a37ec1f016016868e1d72a21e5f3a90cc6b0347d4ff9dbf98088'
        ],
        'x64-windows' => [
            'file' => 'mitie.dll',
            'checksum' => 'dfeaaf72b12c7323d9447275af16afe5a1c64096ec2f00d04cb50f518ca19776'
        ]
    ];

    public static function check($event = null)
    {
        $dest = self::defaultLib();
        if (file_exists($dest)) {
            echo "✔ MITIE found\n";
            return;
        }

        $dir = self::libDir();
        if (!file_exists($dir)) {
            mkdir($dir);
        }

        echo "Downloading MITIE...\n";

        $file = self::libFile();
        $url = self::withVersion("https://github.com/ankane/ml-builds/releases/download/mitie-{{version}}/$file");
        $contents = file_get_contents($url);

        $checksum = hash('sha256', $contents);
        if ($checksum != self::platform('checksum')) {
            throw new Exception("Bad checksum: $checksum");
        }

        file_put_contents($dest, $contents);

        echo "✔ Success\n";
    }

    public static function defaultLib()
    {
        return self::libDir() . '/' . self::libFile();
    }

    private static function libDir()
    {
        return __DIR__ . '/../lib';
    }

    private static function libFile()
    {
        return self::platform('file');
    }

    private static function platform($key)
    {
        return self::PLATFORMS[self::platformKey()][$key];
    }

    private static function platformKey()
    {
        if (PHP_OS_FAMILY == 'Windows') {
            return 'x64-windows';
        } elseif (PHP_OS_FAMILY == 'Darwin') {
            if (php_uname('m') == 'x86_64') {
                return 'x86_64-darwin';
            } else {
                return 'arm64-darwin';
            }
        } else {
            if (php_uname('m') == 'x86_64') {
                return 'x86_64-linux';
            } else {
                return 'aarch64-linux';
            }
        }
    }

    private static function withVersion($str)
    {
        return str_replace('{{version}}', self::VERSION, $str);
    }
}
