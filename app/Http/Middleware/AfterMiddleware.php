<?php

namespace App\Http\Middleware;

use Closure;

class AfterMiddleware
{
    public function handle($oRequest, Closure $oNext)
    {
        $oResponse = $oNext($oRequest);
        $sContent = $oResponse->original;

        //$sContent = preg_replace("/>[\s\n\t]+</", "><", $sContent);
        //$sContent = preg_replace("/(?<=<\w)[\s\n\t]+(?=>)/", ' ', $sContent);

        $sExpression = '%# Collapse whitespace everywhere but in blacklisted elements.
(?>             # Match all whitespans other than single space.
[^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
| \s{2,}        # or two or more consecutive-any-whitespace.
) # Note: The remaining regex consumes no text at all...
(?=             # Ensure we are not in a blacklist tag.
[^<]*+        # Either zero or more non-"<" {normal*}
(?:           # Begin {(special normal*)*} construct
<           # or a < starting a non-blacklist tag.
(?!/?(?:textarea|pre|script)\b)
[^<]*+      # more non-"<" {normal*}
)*+           # Finish "unrolling-the-loop"
(?:           # Begin alternation group.
<           # Either a blacklist start tag.
(?>textarea|pre|script)\b
		| \z          # or end of file.
)             # End alternation group.
)  # If we made it here, we are not in a blacklist tag.
%Six';
		$sContent = preg_replace($sExpression, " ", $sContent);
		$sContent = preg_replace("/>[\s\n\t]+</", "><", $sContent);

        $oResponse->setContent($sContent);

        return $oResponse;
    }
}