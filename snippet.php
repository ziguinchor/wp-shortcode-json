<?php

function generate_shortcode_for_path($nestedArray, $shortcode, $arrayPath, $template = null)
{
	$keys = explode('.', $arrayPath);
	$value = $nestedArray;

	// Traverse the array to get the corresponding value
	foreach ($keys as $key) {
		if (isset($value[$key])) {
			$value = $value[$key];
		} else {
			// Handle case where the array path is invalid
			$value = "<p class='hidden'></p>";
		}
	}

	// If the value is an array, convert it to a string with <p> tags for each element
	if (is_array($value)) {
		$value = array_map(function ($element, $key) use ($template) {
			if (!is_null($template)) {
				return str_replace("{}", $element, $template);
			}
			// Return empty string if the element is empty or null
			if (empty($element) || $element === null) {
				return "";
			}
			if (is_numeric($key)) {
				return "<p>" . $element . "</p>";
			};
			return "<dt>$key</dt><dd>$element</dd>";

		}, $value, array_keys($value));

		$value = implode('', $value);
	}

	// Check if the final value is empty or undefined
	if (empty($value) || $value === null || $value === false) {
		return "<p class='hidden'></p>";
	}

	// Register the shortcode using the provided name
	add_shortcode($shortcode, function () use ($shortcode, $value) {
		// You can customize the output based on the shortcode and value
		if (!empty($template)) {
			return str_replace("{}", $value, $template);
		}
		return $value;
	});

	return "Shortcode $shortcode registered for array path $arrayPath";
}
