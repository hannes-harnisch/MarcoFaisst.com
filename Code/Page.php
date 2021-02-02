<?php

abstract class Page
{
	abstract public function getTitle() : string;
	abstract public function getBody() : string;
}

?>