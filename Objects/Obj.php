<?php

namespace App\Core\Objects;
use App\Core\Model;

abstract class Obj {

    protected ?Model $model;
    protected array $classes = [];

    public function __construct(?Model $model = null) {
        $this->model = $model;
    }

    public function addClass(string $class): Obj {
        array_push($this->classes, trim($class));
        return $this;
    }

    protected function getClassesString(): string {
        $classes = "";
        foreach ($this->classes as $class) {
            $classes .= "$class ";
        }
        return trim($classes);
    }

    /**
     * @return Model|null
     */
    public function getModel(): ?Model {
        return $this->model;
    }

    /**
     * @param Model|null $model
     */
    public function setModel(?Model $model): void {
        $this->model = $model;
    }



    public abstract function __toString(): string;

}