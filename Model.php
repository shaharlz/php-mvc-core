<?php


namespace App\Core;


abstract class Model {

//    RULES
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';

    public array $errors = [];
    protected Application $app;

    public function __construct(Application $app) {
        $this->app = $app;
    }

    public function loadData($data) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key))
                $this->{$key} = $value;
        }
    }

    public function validate() {
        foreach ($this->rules() as $attr => $rules) {
            $value = $this->{$attr};
            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (is_array($ruleName)) {
                    $ruleName = $rule[0];
                }
                if ($ruleName === self::RULE_REQUIRED && empty($value))
                    $this->addErrorForRule($attr, self::RULE_REQUIRED);
                elseif ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL))
                    $this->addErrorForRule($attr, self::RULE_EMAIL);
                elseif ($ruleName === self::RULE_MIN && strlen($value) < $rule['min'])
                    $this->addErrorForRule($attr, self::RULE_MIN, $rule);
                elseif ($ruleName === self::RULE_MAX && strlen($value) > $rule['max'])
                    $this->addErrorForRule($attr, self::RULE_MAX, $rule);
                elseif ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']})
                    $this->addErrorForRule($attr, self::RULE_MATCH, ['match' => $this->getLabel($rule['match'])]);
                elseif ($ruleName === self::RULE_UNIQUE) {
                    $className = $rule['class'];
                    $uniqueAttr = $rule['attribute'] ?? $attr;
                    $tableName = $className::getTableName();
                    $this->app->db->query("SELECT * FROM $tableName WHERE $uniqueAttr = :$uniqueAttr");
                    $this->app->db->bind(":$uniqueAttr", $value);
                    $this->app->db->execute();
                    if ($this->app->db->rowCount() > 0) {
                        $this->addErrorForRule($attr, self::RULE_UNIQUE, ['field' => $this->getLabel($attr)]);
                    }
                }
            }
        }
        return empty($this->errors);
    }

    private function addErrorForRule(string $attr, string $rule, array $params = []) {
        $message = $this->errorMessages()[$rule] ?? '';
        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message);
        }
        $this->errors[$attr][] = $message;
    }

    public function addError(string $attr, string $message) {
        $this->errors[$attr][] = $message;
    }

    public function errorMessages() {
        return [
          self::RULE_REQUIRED => "This field is required",
            self::RULE_EMAIL => "This field must be a valid email address",
            self::RULE_MIN => "Min length of this field must be {min}",
            self::RULE_MAX => "Max length of this field must be {max}",
            self::RULE_MATCH => "This field must be the same as {match}",
            self::RULE_UNIQUE => "This {field} already exists"
        ];
    }

    public function hasError(string $attr): bool {
        return !empty($this->errors[$attr]);
    }

    public function getFirstError(string $attr): ?string {
        return $this->errors[$attr][0] ?? null;
    }

    public function labels(): array {
        return [];
    }

    public function getLabel(string $attr) {
        return $this->labels()[$attr] ?? $attr;
    }

    public abstract function rules() : array;

}