<?php


namespace App\Core\Objects\Forms;
use App\Core\Model;
use App\Core\Objects\Obj;

class Form extends Obj {

    public const METHOD_POST = 'post';
    public const METHOD_GET = 'get';

    private string $action;
    private string $method;
    private array $objs = [];

    public function __construct(string $action = '', string $method = '', ?Model $model = null) {
        parent::__construct($model);
        $this->action = $action;
        $this->method = strtolower($method);
    }

    /**
     * @return string
     */
    public function getAction(): string {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction(string $action): void {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getMethod(): string {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method): void {
        $this->method = $method;
    }

    public function addObjs(Obj ... $objs): Form {
        foreach ($objs as $obj) {
            array_push($this->objs, $obj);
        }
        return $this;
    }

    public function appendModelToObjs(?Model $model) {
        foreach ($this->objs as $obj) {
            $obj->setModel($model);
        }
        return $this;
    }

    public function __toString(): string {
        $form = sprintf('<form action="%s" method="%s">', $this->action, $this->method);
        foreach ($this->objs as $field) {
            $form .= $field;
        }
        $form .= '</form>';
        return $form;
    }

}