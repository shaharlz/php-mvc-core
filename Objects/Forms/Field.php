<?php


namespace App\Core\Objects\Forms;


use App\Core\Model;
use App\Core\Objects\Obj;

class Field extends Obj {

//    TYPES
    public const TYPE_TEXT = 'text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_NUMBER = 'number';
    public const TYPE_EMAIL = 'email';
    public const TYPE_CHECKBOX = 'checkbox';
    public const TYPE_RADIO = 'radio';
    public const TYPE_RANGE = 'range';
    public const TYPE_IMAGE = 'image';
    public const TYPE_DATE = 'date';
    public const TYPE_TEL = 'tel';
    public const TYPE_COLOR = 'color';
    public const TYPE_BUTTON = 'button';
    public const TYPE_DATETIME_LOCAL = 'datetime-local';
    public const TYPE_FILE = 'file';
    public const TYPE_MONTH = 'month';
    public const TYPE_HIDDEN = 'hidden';
    public const TYPE_RESET = 'reset';
    public const TYPE_SEARCH = 'search';
    public const TYPE_SUBMIT = 'submit';
    public const TYPE_URL = 'url';
    public const TYPE_WEEK = 'week';

    private string $attr;
    private string $type;
    private string $label;
    private ?string $value;
    private string $placeholder = "";

    //CONFIGS
    private bool $autofill = true;

    public function __construct(string $attr, string $type = 'text', Model $model = null, ?string $value = null) {
        parent::__construct($model);
        $this->attr = $attr;
        $this->label = ucwords($model->getLabel($attr));
        $this->type = $type;
        $this->value = $value;
        $this->addClass('form-control');
    }

    public function __toString(): string {
        if ($this->model->hasError($this->attr))
            $this->addClass('is-invalid');
        $field = sprintf('
        <div class="form-group">
            <label for="%s">%s</label>
            <input type="%s" name="%s" value="%s" placeholder="%s" class="%s" id="%s">
            <div class="invalid-feedback">%s</div>
        </div>', "input_$this->attr", $this->label, $this->type, $this->attr, $this->getValue(), $this->placeholder, $this->getClassesString(), "input_$this->attr", $this->model->getFirstError($this->attr) ?? '');
        return $field;
    }

    /**
     * @return string
     */
    public function getAttr(): string {
        return $this->attr;
    }

    /**
     * @param string $attr
     */
    public function setAttr(string $attr) {
        $this->attr = $attr;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type) {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label) {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string {
        return $this->value ? $this->value : ($this->autofill ? ($this->model->{$this->attr} ?? '') : '');
    }

    /**
     * @param string $value
     */
    public function setValue(string $value) {
        $this->value = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function getAutofill(): bool {
        return $this->autofill;
    }

    /**
     * @param bool $autofill
     */
    public function setAutofill(bool $autofill) {
        $this->autofill = $autofill;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlaceholder(): string {
        return $this->placeholder;
    }

    /**
     * @param string $placeholder
     */
    public function setPlaceholder(string $placeholder) {
        $this->placeholder = $placeholder;
        return $this;
    }

}