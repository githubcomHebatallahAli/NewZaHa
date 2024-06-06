<?php

// return [
//     // frontend URL
//     'url' => env('APP_URL'),
//     // path to my frontend page with query param queryURL(temporarySignedRoute URL)
//     'email_verify_url' => env('FRONTEND_EMAIL_VERIFY_URL', '/email/verify?queryURL='),

// ];

return [
    'url' => env('FRONTEND_URL', 'https://zaha-script.vercel.app/'),
    'email_verify_url' => env('FRONTEND_EMAIL_VERIFY_URL', '/email/verify/')
];
