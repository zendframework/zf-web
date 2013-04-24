<?php

class VOKpiValue {
	
	public $error; # VOError
	public $comment; # VOComment (kpi comment) 
	public $value;
	public $limits; # VOLimit[] - foreach kpi
	public $tresholdValue;
}