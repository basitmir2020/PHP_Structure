<?php
namespace App\Util;

class Validator {
    private $errors = [];

    public function __construct() {
        $this->errors = [];
    }

    /**
     * Validates data against a set of rules.
     *
     * @param array $data The data to validate (e.g., $_POST).
     * @param array $rulesSet Associative array of rules. 
     *                        Example: ['username' => ['required', 'minLength:5']]
     * @return bool True if validation passes, false otherwise.
     */
    public function validate(array $data, array $rulesSet) {
        $this->errors = []; // Reset errors for each validation call

        foreach ($rulesSet as $field => $rules) {
            $value = $data[$field] ?? null;

            foreach ($rules as $rule) {
                $ruleName = $rule;
                $ruleParam = null;

                if (strpos($rule, ':') !== false) {
                    list($ruleName, $ruleParam) = explode(':', $rule, 2);
                }

                // Construct method name, e.g., checkRequired, checkMinLength
                $methodName = 'check' . ucfirst($ruleName);

                if (method_exists($this, $methodName)) {
                    if ($ruleParam !== null) {
                        if (!$this->$methodName($field, $value, $ruleParam)) {
                            // Error added by the check method if it returns false
                            // To avoid duplicate errors for the same field by different rules,
                            // we can break after the first error for a field, or let all rules run.
                            // For now, let all rules for a field run.
                        }
                    } else {
                        if (!$this->$methodName($field, $value)) {
                            // Error added by the check method
                        }
                    }
                } else {
                    // Potentially log or throw an error for an unknown validation rule
                    $this->addError($field, "Unknown validation rule: {$ruleName}");
                }
            }
        }
        return $this->isValid();
    }

    /**
     * Adds an error message for a specific field.
     *
     * @param string $field The field name.
     * @param string $message The error message.
     */
    public function addError(string $field, string $message) {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }

    /**
     * Checks if validation passed (no errors).
     *
     * @return bool True if valid, false otherwise.
     */
    public function isValid() {
        return empty($this->errors);
    }

    /**
     * Gets all error messages.
     *
     * @return array Associative array of errors, field => [messages].
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Gets the first error message for a specific field.
     *
     * @param string $field The field name.
     * @return string|null The first error message, or null if no error.
     */
    public function getFirstError(string $field) {
        return isset($this->errors[$field][0]) ? $this->errors[$field][0] : null;
    }

    // --- Validation Rule Methods ---

    protected function checkRequired(string $field, $value) {
        if (empty($value) && $value !== '0' && $value !== 0) { // Allow '0' and 0 as valid inputs
            $this->addError($field, ucfirst($field) . " is required.");
            return false;
        }
        return true;
    }

    protected function checkEmail(string $field, $value) {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, "Invalid email format for " . ucfirst($field) . ".");
            return false;
        }
        return true;
    }

    protected function checkMinLength(string $field, $value, int $length) {
        if (mb_strlen(trim((string)$value)) < $length) {
            $this->addError($field, ucfirst($field) . " must be at least {$length} characters long.");
            return false;
        }
        return true;
    }

    protected function checkMaxLength(string $field, $value, int $length) {
        if (mb_strlen(trim((string)$value)) > $length) {
            $this->addError($field, ucfirst($field) . " must not exceed {$length} characters.");
            return false;
        }
        return true;
    }

    protected function checkAlphaNum(string $field, $value) {
        if (!ctype_alnum((string)$value)) {
            $this->addError($field, ucfirst($field) . " must contain only letters and numbers.");
            return false;
        }
        return true;
    }

    protected function checkNumeric(string $field, $value) {
        if (!is_numeric($value)) {
            $this->addError($field, ucfirst($field) . " must be a numeric value.");
            return false;
        }
        return true;
    }
    
    protected function checkInteger(string $field, $value) {
        if (filter_var($value, FILTER_VALIDATE_INT) === false) {
            $this->addError($field, ucfirst($field) . " must be a valid integer.");
            return false;
        }
        return true;
    }
}
?>
