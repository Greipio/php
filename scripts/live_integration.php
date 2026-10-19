<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Greip\API\Config;
use Greip\API\GeoIP;
use Greip\API\Fraud;
use Greip\API\Enums\Mode;

$token = getenv('GREIP_TOKEN') ?: '';
if ($token === '') {
    fwrite(STDERR, "GREIP_TOKEN required\n");
    exit(1);
}

(new Config())->setToken($token);
$geo = new GeoIP();
$fraud = new Fraud();

$checks = [
    'lookup' => fn () => $geo->lookup('1.1.1.1', [], 'EN', Mode::LIVE),
    'country' => fn () => $geo->country('PS', [], 'EN', Mode::LIVE),
    'asn' => fn () => $geo->asn('AS13335', Mode::LIVE),
    'threats' => fn () => $geo->threats('1.1.1.1', Mode::LIVE),
    'email' => fn () => $fraud->email('name@domain.com', Mode::LIVE),
    'phone' => fn () => $fraud->phone('1234567890', 'TR', Mode::LIVE),
    'iban' => fn () => $fraud->iban('DE89370400440532013000', null, Mode::LIVE),
    'profanity' => fn () => $fraud->profanity('This is a test message', Mode::LIVE),
    'payment' => fn () => $fraud->payment([
        'action' => 'purchase',
        'website_domain' => 'greip.io',
        'transaction_amount' => 1000,
        'transaction_currency' => 'GBP',
        'customer_email' => 'name@domain.com',
        'customer_ip' => '1.1.1.1',
    ], Mode::LIVE),
];

foreach ($checks as $name => $fn) {
    try {
        $result = $fn();
        if (!is_array($result) || empty($result)) {
            throw new RuntimeException('empty result');
        }
        echo "OK $name\n";
    } catch (Throwable $e) {
        fwrite(STDERR, "FAIL $name: {$e->getMessage()}\n");
        exit(1);
    }
}

echo "All php live checks passed.\n";
