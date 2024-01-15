<?php
class DTO_Magazine
{
    public $Id;
    public $Title;

    /**
     * DTO_Magazine constructor.
     * @param $Id
     * @param $Title
     */
    public function __construct($Id, $Title)
    {
        $this->Id = $Id;
        $this->Title = $Title;
    }
}