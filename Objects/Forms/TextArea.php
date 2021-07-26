<?php


namespace App\Core\Objects\Forms;


use App\Core\Model;
use App\Core\Objects\Obj;

class TextArea extends Obj {

    private string $attr;
    private string $label;
    private ?string $value;
    private string $placeholder = "";

    //CONFIGS
    private bool $autofill = true;

    public function __construct(string $attr, Model $model = null, ?string $value = null) {
        parent::__construct($model);
        $this->attr = $attr;
        $this->label = ucwords($model->getLabel($attr));
        $this->value = $value;
        $this->addClass('form-control');
    }

    public function __toString(): string {
        if ($this->model->hasError($this->attr))
            $this->addClass('is-invalid');
        $field = sprintf('
        <div class="form-group">
            <label for="%s">%s</label>
            <textarea  name="%s" placeholder="%s" class="%s" id="%s">%s</textarea>
            <div class="invalid-feedback">
                %s
            </div>
        </div>', "textarea_$this->attr", $this->label, $this->attr, $this->placeholder, $this->getClassesString(),  "textarea_$this->attr", $this->getValue(), $this->model->getFirstError($this->attr) ?? '');
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