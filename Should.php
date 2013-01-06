<?php

namespace test;

// Examples, tentatively
$test = new Should("TestNameHere");
$test->item($response)->should()->have()->a()->property("error");
$test->item($response['error'])->should()->have()->a()->lengthOf(5);
$test->item($response)->should()->have()->a()->property("body");
$test->item($response['body'])->should()->have()->a()->lengthOf(3);

class Should {

	public $_testName;
	public $_data;
	public $_results;
	public $_string;
	public $_expectedFalse;

	public function __construct( $testName ) {
		$this->_testName = $testName;
		return $this;
	}

	protected function result( $result ) {
		( $result == false && $this->_expectedFalse == true ) ? $success = true : $success = $result;
		
		$this->_results[] = array('string' => $this->_string, 'success' => $success );
		$this->_string = null;
		$this->_data = null;
	}

	protected function method( $method ) {
		$this->_string .= " $method";
	}

	protected function negate() {
		$this->_expectedFalse = true;
	}

	public function should() {
		$this->method("should");
		return $this;
	}

	public function not() {
		$this->method("not");
		$this->negate();
		return $this;
	}

	public function the() {
		$this->method("the");
		return $this;
	}

	public function be() {
		$this->method("be");
		return $this;
	}

	public function with() {
		$this->method("with");
		return $this;
	}

	public function have() {
		$this->method("have");
		return $this;
	}

	public function item( $item ) {
		$this->method("item");
		$this->data = $item;
		$this->type = gettype( $item );
		return $this;
	}

	public function a( $item ) {
		$this->method("a");
		return $this->an( $item, true );
	}

	public function an( $item, $aliased = false ) {
		if( !$aliased )
			$this->method("an");
		switch( $item ) {

		}
		return $this;
	}

	public function lengthOf( $length ) {
		$this->method("lengthOf");
		switch( $this->type ) {
			case "object":
			case "array":
				$this->result( count( $this->_data ) === $length );
				return $this;
				break;
			case "string":
				$this->result( strlen( $this->_data ) === $length );
				return $this;
				break;
			default: 
				$this->result("Invalid data type passed to lengthOf");
				return $this;
		}
	}

	public function does() {
		$this->method("does");
		return $this;
	}

	public function doesnt() {
		$this->method("doesnt");
		$this->negate();
		return $this;
	}

	public function property( $property ) {
		$this->method("property");
		return isset( $this->data[$property] );
	}

}