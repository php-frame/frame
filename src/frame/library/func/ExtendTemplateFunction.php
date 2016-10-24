<?php

namespace frame\library\func;

use frame\library\TemplateContext;

/**
 * Function to include another template resource which needs to be rendered at
 * runtime. For dynamic extend blocks, used internally.
 *
 * Syntax: _extends(<resource>, <append-template-code>)
 *
 * {_extends("my-template.tpl", "Display my {$variable})}
 */
class ExtendTemplateFunction implements TemplateFunction {

    /**
     * Calls the function with the provided context and arguments
     * @param \frame\library\TemplateContext $context Context for the function
     * @param array $arguments Arguments for the function
     * @return mixed Result of the function
     */
    public function call(TemplateContext $context, array $arguments) {
        $engine = $context->getEngine();
        $output = '';

        $resource = array_shift($arguments);
        $body = array_shift($arguments);
        $body = str_replace('\\$', '$', $body);

        if (!$resource) {
            throw new RuntimeTemplateException('Could not include template: no resource(s) provided');
        }

        $output .= $engine->render($resource, [], $context, $body);

        return $output;
    }

}