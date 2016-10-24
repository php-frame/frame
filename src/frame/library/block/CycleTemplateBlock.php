<?php

namespace frame\library\block;

use frame\library\TemplateCompiler;

/**
 * Cycle element values in a loop everytime you call this block
 * @see ForeachBlockElement
 */
class CycleTemplateBlock implements TemplateBlock {

    /**
     * Gets whether this block has a signature
     * @return boolean
     */
    public function hasSignature() {
        return true;
    }

    /**
     * Gets whether this block needs to be closed
     * @return boolean
     */
    public function needsClose() {
        return false;
    }

    /**
     * Compiles this block into the output buffer of the compiler
     * @param \frame\library\TemplateCompiler $compiler Instance of the compiler
     * @param string $signature Signature as provided in the template
     * @param string $body Contents of the block body
     * @return null
     */
    public function compile(TemplateCompiler $compiler, $signature, $body) {
        $buffer = $compiler->getOutputBuffer();

        $name = '_cycle' . md5($signature);
        $arguments = '[' . $compiler->compileExpression($signature) . ']';

        $buffer->appendCode('if (!$context->hasFunction(\'' . $name . '\')) {');
        $buffer->appendCode('$' . $name . ' = new \frame\library\func\CycleTemplateFunction();');
        $buffer->appendCode('$context->setFunction(\'' . $name . '\', $' . $name . ');');
        $buffer->appendCode(' }');

        $buffer->appendCode('echo $context->call(\'' . $name . '\', ' . $arguments . ');');
    }

}