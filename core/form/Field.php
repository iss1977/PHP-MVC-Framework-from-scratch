<?php


namespace app\core\form;


use app\core\Model;

class Field
{
    // needed for field types
    public const TYPE_TEXT ='text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_NUMBER = 'number';
    public string $type;

    public Model $model;
    public string $attribute;

    /**
     * Field constructor.
     * @param Model $model
     * @param string $attribute
     */
    public function __construct(Model $model, string $attribute)
    {
        $this->type = self::TYPE_TEXT;
        $this->model = $model;
        $this->attribute = $attribute;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return sprintf('
                <div class=form-group">
                    <label for="%s" class="form-label">%s</label>
                    <input type="%s" value = "%s" class="form-control %s" name ="%s" id="%s" >
                    <div class="invalid-feedback">
                        %s
                    </div>
                </div>
        ',
        $this->attribute, //for
        $this->attribute, // label
        $this->type, // type of the field : password, text, number ...
        $this->model->{$this->attribute}, //value
        $this->model->hasErrors($this->attribute) ? 'is-invalid': '', // adding class if there are errors
        $this->attribute, // name
        $this->attribute, // id
        $this->model->getFirstError($this->attribute) // the error message
        );
    }

    public function passwordField(): Field
    {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }

}