<?php

namespace frame\library\block;

use frame\library\exception\CompileTemplateException;
use frame\library\tokenizer\symbol\SyntaxSymbol;
use frame\library\tokenizer\IncludeTokenizer;
use frame\library\TemplateCompiler;

/**
 * Include block element, used to include another template
 */
class IncludeTemplateBlock implements TemplateBlock {

    /**
     * Constructs a new macro template block
     * @return null
     */
    public function __construct() {
        $this->tokenizer = new IncludeTokenizer();
    }

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
        $resource = null;
        $arguments = null;
        $value = '';

        $tokens = $this->tokenizer->tokenize($signature);
        foreach ($tokens as $token) {
            if ($token == SyntaxSymbol::INCLUDE_WITH) {
                $resource .= trim($value);
                $value = '';
            } else {
                $value .= $token;
            }
        }

        if ($value) {
            if ($resource === null) {
                $resource = trim($value);
            } else {
                $arguments = trim($value);
            }
        }

        try {
            $resource = $compiler->compileScalarValue($resource);
            $isStaticTemplate = true;
        } catch (CompileTemplateException $exception) {
            $isStaticTemplate = false;
        }

        if ($arguments) {
            $arguments = $compiler->compileExpression($arguments);
            if (substr($arguments, 0, 1) != SyntaxSymbol::ARRAY_OPEN && substr($arguments, -1) != SyntaxSymbol::ARRAY_CLOSE) {
                throw new CompileTemplateException('Could not include ' . $resource . ': arguments should be an array');
            }
        }

        if ($isStaticTemplate) {
            $resource = substr($resource, 1, -1);
            $code = $compiler->getContext()->getResourceHandler()->getResource($resource);

            if ($arguments) {
                $compiler->getOutputBuffer()->appendCode('$context->setVariables(' . $arguments . ');');
            }
        } else {
            if ($arguments) {
                $arguments = ', ' . $arguments;
            }

            $code = '{_include(' . $signature  . $arguments . ')}';
        }

        try {
            $compiler->subcompile($code);
        } catch (CompileTemplateException $exception) {
            $e = new CompileTemplateException('Could not compile ' . $resource . ': syntax error on line ' . $exception->getLineNumber(), 0, $exception->getPrevious());
            $e->setResource($resource);
            $e->setLineNumber($exception->getLineNumber());

            throw $e;
        }
    }

}
