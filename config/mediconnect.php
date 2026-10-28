<?php

return [
    /*
     | Free trial given to every newly registered hospital, and the
     | subscription charged afterwards (demo billing for now).
     */
    'trial_days' => 7,
    'subscription_price' => 50000,
    'subscription_days' => 30,
    'currency' => 'TZS',

    // Days before trial end on which the admin is reminded.
    'reminder_days' => [3, 1],
];
