<?php

const FILTERS = [
    'string' => FILTER_SANITIZE_STRING,
];

/**
 * Recoge un array y lo devuelve en string 
 * y sin espacios
 * @param array $items
 * @return array
 */
function array_trim(array $items): array
{
    return array_map(function ($item) {
        if (is_string($item)) {
            return trim($item);
        } elseif (is_array($item)) {
            return array_trim($item);
        } else
            return $item;
    }, $items);
}

/**
 * Se encarga aplicar filtros de saneamiento a los datos de entrada, 
 * eliminando caracteres no deseados o aplicando ciertos formatos a 
 * los valores proporcionados.
 * @param array $inputs
 * @param array $fields
 * @param int $default_filter 
 * @param array $filters 
 * @param bool $trim
 * @return array
 */
function sanitize(array $inputs, array $fields = [], int $default_filter = FILTER_SANITIZE_STRING, array $filters = FILTERS, bool $trim = true): array
{
    if ($fields) {
        $options = array_map(fn ($field) => $filters[$field], $fields);
        $data = filter_var_array($inputs, $options);
    } else {
        $data = filter_var_array($inputs, $default_filter);
    }

    return $trim ? array_trim($data) : $data;
}

const DEFAULT_VALIDATION_ERRORS = [
    'required' => 'El %s es requerido',
    'alphanumeric' => 'El %s debe tener solo letras y números',
];

/**
 * Valida los datos con reglas específicas, proporcionadas como parámetros, 
 * y devuelve un array que contiene los errores de validación, si los hay.
 * @param array $data
 * @param array $fields
 * @param array $messages
 * @return array
 */
function validate(array $data, array $fields, array $messages = []): array
{

    $split = fn ($str, $separator) => array_map('trim', explode($separator, $str));


    $rule_messages = array_filter($messages, fn ($message) => is_string($message));

    $validation_errors = array_merge(DEFAULT_VALIDATION_ERRORS, $rule_messages);

    $errors = [];

    foreach ($fields as $field => $option) {

        $rules = $split($option, '|');

        foreach ($rules as $rule) {

            $params = [];

            if (strpos($rule, ':')) {
                [$rule_name, $param_str] = $split($rule, ':');
                $params = $split($param_str, ',');
            } else {
                $rule_name = trim($rule);
            }

            $fn = 'is_' . $rule_name;

            if (is_callable($fn)) {
                $pass = $fn($data, $field, ...$params);
                if (!$pass) {

                    $errors[$field] = sprintf(
                        $messages[$field][$rule_name] ?? $validation_errors[$rule_name],
                        $field,
                        ...$params
                    );
                }
            }
        }
    }

    return $errors;
}

/**
 * Verifica si un campo específico dentro de un array de 
 * datos es obligatorio y no está vacío. 
 * @param array $data
 * @param string $field
 * @return bool
 */
function is_required(array $data, string $field): bool
{
    return isset($data[$field]) && trim($data[$field]) !== '';
}

/**
 * Comprueba si el valor de un campo específico dentro de un array de datos es alfanumérico, 
 * @param array $data
 * @param string $field
 * @return bool
 */
function is_alphanumeric(array $data, string $field): bool
{
    if (!isset($data[$field])) {
        return true;
    }

    return ctype_alnum($data[$field]);
}

/**
 * Coordina la sanitizacion y validacion de los datos de acuerdo con las 
 * reglas definidas en un conjunto de campos y reglas específicas. 
 * @param array $data
 * @param array $fields
 * @param array $messages
 * @return array
 */
function filter(array $data, array $fields, array $messages = []): array
{
    $sanitization = [];
    $validation = [];

    foreach ($fields as $field => $rules) {
        if (strpos($rules, '|')) {
            [$sanitization[$field], $validation[$field]] = explode('|', $rules, 2);
        } else {
            $sanitization[$field] = $rules;
        }
    }

    $inputs = sanitize($data, $sanitization);
    $errors = validate($inputs, $validation, $messages);

    return [$inputs, $errors];
}
