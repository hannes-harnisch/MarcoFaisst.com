<?php

abstract class Page
{
	abstract public function renderTitle() : string;
	abstract public function renderBody() : string;
}

?>