<?php


namespace App\Core\Objects\Forms;


use App\Core\Model;
use App\Core\Objects\Obj;

class Button extends Obj {

//    TYPES
    public const TYPE_SUBMIT = 'submit';
    public const TYPE_RESET = 'reset';
    public const TYPE_BUTTON = 'button';

    private string $attr;
    private string $type;
    private ?string $label;

    public function __construct(string $attr, string $type = self::TYPE_BUTTON, Model $model = null, ?string $value = null) {
        parent::__construct($model);
        $this->attr = $attr;
        $this->type = $type;
        $this->label = ucwords($model->getLabel($attr));
        $this->addClass('btn');
    }

    public function __toString(): string {
        $field = sprintf('
        <button type="%s" name="%s" class="%s">%s</button>', $this->type, $this->attr, $this->getClassesString(), $this->label);
        return $field;
    }

}