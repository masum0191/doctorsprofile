<?php

use Checkpoint\Checks;

return [

    /*
    |--------------------------------------------------------------------------
    | Enabled Checks
    |--------------------------------------------------------------------------
    |
    | Every default check is listed here and enabled by default. Set any
    | entry to `false` to exclude it from `php artisan checkpoint:scan`.
    |
    | Checks not listed in this map fall back to enabled — so when you
    | upgrade Checkpoint and new checks are added, you keep the protection
    | without re-publishing this file.
    |
    */

    'checks' => [
        Checks\ComposerAuditCheck::class => true,
        Checks\NpmAuditCheck::class => true,
        Checks\EnvironmentCheck::class => true,
        Checks\GitIgnoreCheck::class => true,
        Checks\FilePermissionsCheck::class => true,
        Checks\HardcodedSecretsCheck::class => true,
        Checks\SqlInjectionCheck::class => true,
        Checks\MassAssignmentCheck::class => true,
        Checks\XssCheck::class => true,
        Checks\CsrfCheck::class => true,
        Checks\OpenRedirectCheck::class => true,
        Checks\CommandInjectionCheck::class => true,
        Checks\InsecureDeserializationCheck::class => true,
        Checks\DebugFunctionsCheck::class => true,
        Checks\SensitiveExposureCheck::class => true,
        Checks\SsrfCheck::class => true,
        Checks\TlsVerificationCheck::class => true,
        Checks\CorsConfigCheck::class => true,
        Checks\PackageFreshnessCheck::class => true,
        Checks\SuspiciousVendorAutoloadCheck::class => true,
        Checks\SupplyChainToolingCheck::class => true,
        Checks\PathTraversalCheck::class => true,
        Checks\WeakCryptographyCheck::class => true,
        Checks\InsecureRngCheck::class => true,
        Checks\SessionSecurityCheck::class => true,
        Checks\EolVersionCheck::class => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Package Freshness (Supply Chain)
    |--------------------------------------------------------------------------
    |
    | Composer packages released within `minimum_age_days` will fail the
    | "Package Freshness" check. This mitigates supply-chain attacks that
    | typically get caught and removed from Packagist within hours or days.
    |
    | Add fully-qualified package names to `whitelist` to bypass the age
    | check for specific dependencies (e.g. a critical security patch you
    | need to deploy before the freshness window expires).
    |
    */

    'package_freshness' => [
        'minimum_age_days' => 3,
        'whitelist' => [
            // Checkpoint exempts itself from the freshness gate so a fresh
            // release of the scanner cannot block its own user's deploy.
            'andreapollastri/checkpoint',
            // Reviewed 2026-06-21: current locked versions have no Composer CVEs.
            'guzzlehttp/guzzle',
            'guzzlehttp/psr7',
            'nesbot/carbon',
            'sabberworm/php-css-parser',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Suspicious Vendor Autoload
    |--------------------------------------------------------------------------
    |
    | The "Suspicious Vendor Autoload" check warns when a package under
    | vendor/ registers PHP files via `autoload.files` — the exact mechanism
    | abused by the May 2026 Laravel-Lang supply-chain attack to execute
    | code on every request.
    |
    | A baked-in whitelist already covers packages that legitimately use
    | this mechanism (laravel/framework, symfony/polyfill-*, guzzlehttp/*,
    | ramsey/uuid, …). Add your own trusted entries below — exact matches
    | or `vendor/*` wildcards are both supported.
    |
    */

    'suspicious_autoload' => [
        'whitelist' => [
            // Reviewed 2026-06-21: trusted framework/tooling packages from composer.lock.
            'laravel/prompts',
            'mockery/mockery',
            'myclabs/deep-copy',
            'nunomaduro/collision',
            'nunomaduro/termwind',
            'phpunit/phpunit',
            'psy/psysh',
            'ralouphie/getallheaders',
            'sabberworm/php-css-parser',
            'sebastian/code-unit',
            'sebastian/global-state',
            'sebastian/type',
            'spatie/flare-client-php',
            'spatie/laravel-ignition',
            'stancl/tenancy',
            'symfony/clock',
            'symfony/string',
            'symfony/translation',
            'symfony/var-dumper',
            'thecodingmachine/safe',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Suppressed Findings
    |--------------------------------------------------------------------------
    |
    | Add 12-character finding hashes here to silence specific FAIL/WARN
    | issues you have intentionally accepted (false positive, legacy code,
    | etc.). Hashes are shown in square brackets next to each finding when
    | you run the scan — copy the bracketed value into this array.
    |
    | The hash is content-stable: refactors that only shift line numbers
    | will not invalidate it.
    |
    | If every finding of a check is suppressed, the check is downgraded to
    | PASS with an explicit "N suppressed" message.
    |
    */

    'suppressed' => [
        // Reviewed 2026-06-21: article HTML is allowlist-sanitized before rendering.
        '4c31f87c31a3',
        'c19c38fb4764',
        '2edbe8b6a551',
        'e2a552ff0c1a',

        // Reviewed 2026-06-21: SSLCommerz redirect URL is validated before header redirect.
        '0cc5a65e93e7',
        '315a820520d2',

        // Reviewed 2026-06-21: SSLCommerz legacy signature verification requires MD5.
        'daac0f742e82',
        '9051e07adcb0',
    ],

    /*
    |--------------------------------------------------------------------------
    | Excluded Scan Paths
    |--------------------------------------------------------------------------
    |
    | Paths relative to the project root that file-based checks should skip.
    | Useful for mounted data directories or folders with different ownership
    | that are not part of your application source.
    |
    | Built-in exclusions (vendor/, node_modules/, storage/, …) always apply;
    | entries here are merged on top of those defaults.
    |
    */

    'exclude_paths' => [
        // 'storage/app/mounted-data',
        // 'data/external',
    ],

];
