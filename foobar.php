<?php

// Output numbers from 1 to 100
for ($i = 1; $i <= 100; $i++) {
    $output = '';

    // Check divisibility by 3 and 5 first
    if ($i % 3 === 0) {
        $output .= 'foo';
    }
    if ($i % 5 === 0) {
        $output .= 'bar';
    }

    // Output result or number if not divisible by 3 or 5
    echo $output ? $output : $i;
    echo "\n";
}