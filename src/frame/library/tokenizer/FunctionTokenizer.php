<?php

namespace frame\library\tokenizer;

use frame\library\tokenizer\symbol\NestedSymbol;
use frame\library\tokenizer\symbol\SimpleSymbol;
use frame\library\tokenizer\symbol\StringSymbol;
use frame\library\tokenizer\symbol\SyntaxSymbol;

/**
 * Tokenizer for a function signature
 */
class FunctionTokenizer extends Tokenizer {

    /**
     * Constructs a new function tokenizer
     * @return null
     */
    public function __construct() {
        $this->addSymbol(new StringSymbol());
        $this->addSymbol(new SimpleSymbol(SyntaxSymbol::FUNCTION_ARGUMENT));
        $this->addSymbol(new NestedSymbol(SyntaxSymbol::NESTED_OPEN, SyntaxSymbol::NESTED_CLOSE, $this, true));

        parent::setWillTrimTokens(false);
    }

}