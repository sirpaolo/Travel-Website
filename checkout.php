<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/requirelogin.php';

\Stripe\Stripe::setApiKey('Your api key');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    exit('Invalid request method.');
}

// READ & SANITIZE INPUTS

$place_name     = trim($_POST['name'] ?? 'Unknown Place');
$place_address  = trim($_POST['address'] ?? '');

$checkin  = trim($_POST['checkin'] ?? '');
$checkout = trim($_POST['checkout'] ?? '');

$guests              = max(1, (int)($_POST['guests'] ?? 1));
$per_person_amount   = (float)($_POST['per_person_amount'] ?? 0);
$service_fee_php     = (float)($_POST['service_fee_php'] ?? 0);
$tax_php             = (float)($_POST['tax_php'] ?? 0);
$subtotal_php        = (float)($_POST['subtotal_php'] ?? 0);
$total_php           = (float)($_POST['total_php'] ?? 0);

//VALIDATION

if ($per_person_amount <= 0 || $total_php <= 0) {
    http_response_code(400);
    exit('Invalid booking amounts.');
}

if (!$checkin || !$checkout) {
    http_response_code(400);
    exit('Check-in and check-out dates are required.');
}

// CALCULATE NIGHTS (BACKEND)

$checkin_date  = new DateTime($checkin);
$checkout_date = new DateTime($checkout);
$nights = (int)$checkin_date->diff($checkout_date)->days;

if ($nights <= 0) {
    http_response_code(400);
    exit('Checkout must be after check-in.');
}

//PRICING

$NIGHTLY_RATE = 1200;
$nightly_total = $nights * $NIGHTLY_RATE;

//STRIPE LINE ITEMS


$line_items = [];


//Accommodation (per night)

$line_items[] = [
    'quantity' => 1,
    'price_data' => [
        'currency' => 'php',
        'unit_amount' => (int) round($nightly_total * 100), // ₱1000 × nights
        'product_data' => [
            'name' => 'Accommodation — ' . $place_name,
            'description' =>
                ($place_address ? $place_address . "\n\n" : '') .
                "{$checkin} to {$checkout} (" .
                "{$nights} night" . ($nights !== 1 ? 's' : '') .
                " × ₱1,200)",
        ],
    ],
];

//Guests / per-person cost

$guest_total = $per_person_amount * $guests;
$line_items[] = [
    'quantity' => 1,
    'price_data' => [
        'currency' => 'php',
        'unit_amount' => (int) round($guest_total * 100),
        'product_data' => [
            'name' => 'Guests',
            'description' =>
                "{$guests} guest" . ($guests !== 1 ? 's' : '') .
                " × ₱" . number_format($per_person_amount, 2),
        ],
    ],
];



//Service Fee

if ($service_fee_php > 0) {
    $line_items[] = [
        'quantity' => 1,
        'price_data' => [
            'currency' => 'php',
            'unit_amount' => (int)round($service_fee_php * 100),
            'product_data' => [
                'name' => 'Service Fee',
            ],
        ],
    ];
}

//Tax

if ($tax_php > 0) {
    $line_items[] = [
        'quantity' => 1,
        'price_data' => [
            'currency' => 'php',
            'unit_amount' => (int)round($tax_php * 100),
            'product_data' => [
                'name' => 'Tax (12%)',
            ],
        ],
    ];
}

//STRIPE SESSION

try {
    $session = \Stripe\Checkout\Session::create([
        'mode' => 'payment',
        'payment_method_types' => ['card'],
        'success_url' => 'http://localhost/mytravel/success.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => 'http://localhost/mytravel/home.php',
        'locale' => 'auto',
        'line_items' => $line_items,
        'metadata' => [
            'checkin' => $checkin,
            'checkout' => $checkout,
            'nights' => (string)$nights,
            'guests' => (string)$guests,
            'nightly_rate_php' => number_format($NIGHTLY_RATE, 2, '.', ''),
            'per_person_php' => number_format($per_person_amount, 2, '.', ''),
            'service_fee_php' => number_format($service_fee_php, 2, '.', ''),
            'tax_php' => number_format($tax_php, 2, '.', ''),
            'total_php' => number_format($total_php, 2, '.', ''),
        ],
    ]);

    header('HTTP/1.1 303 See Other');
    header('Location: ' . $session->url);
    exit;

} catch (\Stripe\Exception\ApiErrorException $e) {
    http_response_code(500);
    echo 'Stripe error: ' . htmlspecialchars($e->getMessage());
    exit;
}
