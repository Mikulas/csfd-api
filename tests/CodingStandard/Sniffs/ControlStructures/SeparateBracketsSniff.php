<?php

/**
 * Ensures control structures' blocks start on new line:
 *
 * Not allowed:
 * <code>
 * if (...) {
 *  return TRUE;
 * }
 * </code>
 *
 * Allowed:
 * <code>
 * if (...)
 * {
 *   return TRUE;
 * }
 * </code>
 */
class CodingStandard_Sniffs_ControlStructures_SeparateBracketsSniff implements PHP_CodeSniffer_Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_IF, T_ELSE, T_ELSEIF, T_FOREACH, T_FOR);

    }//end register()


    /**
     * Processes this sniff, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $next = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);
        $isInline = TRUE;
        $offset = 0;
        $showCondition = FALSE;
        // skip condition of IF and ELSE IF
        if ($tokens[$next]['code'] === T_OPEN_PARENTHESIS)
        {
            $showCondition = TRUE;
            $depth = 1;
            $offset = 1;
            while ($depth > 0) {
                if ($tokens[$next + $offset]['code'] === T_OPEN_PARENTHESIS) {
                    $depth++;
                } else if ($tokens[$next + $offset]['code'] === T_CLOSE_PARENTHESIS) {
                    $depth--;
                }
                $offset++;
            }
        }
        $blockOrStatement = $phpcsFile->findNext(T_WHITESPACE, ($next + $offset + 1), null, true);
        if ($tokens[$blockOrStatement]['code'] === T_OPEN_CURLY_BRACKET)
        {
            $isInline = FALSE;
        }

        if ($isInline) {
            // inline block such as
            // if (...) throw
            return;
        }

        $hasNewline = FALSE;
        $return = 1;
        $spacing = '';
        while (true) {
            $whitespace = $tokens[$blockOrStatement - $return];
            $return++;

            /**
             * <code>
             * }
             * else // comment
             * {
             * </code>
             */
            if ($whitespace['code'] === T_COMMENT) {
                if ($whitespace['content'] !== '/*') {
                    // comment until end of the line, this sniff must be fulfilled
                    return;
                } else {
                    throw new NotImplementedException();
                }
            }
            if ($whitespace['code'] !== T_WHITESPACE) {
                break;
            }
            $spacing .= $whitespace['content'];
        };

        if ($hasNewline = strpos($spacing, "\n") === FALSE) {
            $error = 'Structure missing newline before bracket; found "%s';
            if ($showCondition) {
                $error .= ' (...)';
            }
            $error .= ' {"';
            $data = array($tokens[$stackPtr]['content']);
            $phpcsFile->addError($error, $blockOrStatement - $return, 'MissingNewline', $data);
        }
    }//end process()


}//end class

?>
