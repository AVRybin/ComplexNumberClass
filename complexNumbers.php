<?php

class ComplexNumber{

    private float $partReal;
    private float $partImaginary;

    function __construct(float $partReal, float $partImaginary = 0){
        $this->partReal = $partReal;
        $this->partImaginary = $partImaginary;
    }

    public function getStr(): string{
        $strImaginary = ($this->partImaginary >= 0) ? '+'. $this->partImaginary : $this->partImaginary;
        return $this->partReal. $strImaginary. 'i';
    }

    public function add(...$terms){
        $this->modify('getSum', ...$terms);
    }

    public function subtract(...$subtrahends){
        $this->modify('getDifference', ...$subtrahends);
    }

    public function multiply(...$factors){
        $this->modify('getProduct', ...$factors);
    }

    public function divide(...$dividers){
        $this->modify('getQuotient', ...$dividers);
    }

    private function getSum(ComplexNumber $term){
        $this->partReal += $term->partReal;
        $this->partImaginary += $term->partImaginary;
    }

    private function getDifference(ComplexNumber $subtrahend){
        $this->partReal -= $subtrahend->partReal;
        $this->partImaginary -= $subtrahend->partImaginary;
    }

    private function getProduct(ComplexNumber $factor){
        $partReal = $this->partReal * $factor->partReal - $this->partImaginary * $factor->partImaginary;
        $partImaginary = $this->partReal * $factor->partImaginary + $this->partImaginary * $factor->partReal;

        $this->partReal = $partReal;
        $this->partImaginary = $partImaginary;
    }

    private function getQuotient(ComplexNumber $divider){
        $sumPow = pow($divider->partReal, 2) + pow($divider->partImaginary, 2);

        $partReal = $this->partReal * $divider->partReal + $this->partImaginary * $divider->partImaginary;
        $partImaginary = $this->partImaginary * $divider->partReal - $this->partReal * $divider->partImaginary ;

        $partReal /= $sumPow;
        $partImaginary /= $sumPow;

        $this->partReal = $partReal;
        $this->partImaginary = $partImaginary;
    }

    private function modify($callbackModify, ...$args){
        $result = $this;

        foreach ($args as $arg) {
            $arg = self::getValidateType($arg);
            $result->$callbackModify($arg);
        }

        $this->partReal = $result->partReal;
        $this->partImaginary = $result->partImaginary;
    }

    private static function getValidateType($arg): ComplexNumber{

        if (!is_object($arg) || !is_a($arg, __CLASS__)){

            if (!is_int($arg) && !is_float($arg)) {
                throw new TypeError();
            }
            else{
                return new ComplexNumber($arg);
            }
        }

        return $arg;
    }
}
