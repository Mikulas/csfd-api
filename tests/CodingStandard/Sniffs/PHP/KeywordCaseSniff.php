<?php
/**
 * Checks that all constructs are lowercase but logical operators.
 *
 * @category  PHP
 * @package   SIM_CodeSniffer
 */
class CodingStandard_Sniffs_PHP_KeywordCaseSniff implements PHP_CodeSniffer_Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
                T_HALT_COMPILER,
                T_ABSTRACT,
                T_ARRAY,
                T_AS,
                T_BREAK,
                T_CALLABLE,
                T_CASE,
                T_CATCH,
                T_CLASS,
                T_CLONE,
                T_CONST,
                T_CONTINUE,
                T_DECLARE,
                T_DEFAULT,
                T_DO,
                T_ECHO,
                T_ELSE,
                T_ELSEIF,
                T_EMPTY,
                T_ENDDECLARE,
                T_ENDFOR,
                T_ENDFOREACH,
                T_ENDIF,
                T_ENDSWITCH,
                T_ENDWHILE,
                T_EVAL,
                T_EXIT,
                T_EXTENDS,
                T_FINAL,
                T_FINALLY,
                T_FOR,
                T_FOREACH,
                T_FUNCTION,
                T_GLOBAL,
                T_GOTO,
                T_IF,
                T_IMPLEMENTS,
                T_INCLUDE,
                T_INCLUDE_ONCE,
                T_INSTANCEOF,
                T_INSTEADOF,
                T_INTERFACE,
                T_ISSET,
                T_LIST,
                T_LOGICAL_AND,
                T_LOGICAL_OR,
                T_LOGICAL_XOR,
                T_NAMESPACE,
                T_NEW,
                T_PRINT,
                T_PRIVATE,
                T_PROTECTED,
                T_PUBLIC,
                T_REQUIRE,
                T_REQUIRE_ONCE,
                T_RETURN,
                T_STATIC,
                T_SWITCH,
                T_THROW,
                T_TRAIT,
                T_TRY,
                T_UNSET,
                T_USE,
                T_VAR,
                T_WHILE,
               );

    }//end register()


    /**
     * Processes this sniff, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens  = $phpcsFile->getTokens();
        $keyword = $tokens[$stackPtr]['content'];

        $logical = in_array($tokens[$stackPtr]['code'], array(T_LOGICAL_AND, T_LOGICAL_OR, T_LOGICAL_XOR));

        if ($logical) {
            if (strtoupper($keyword) !== $keyword) {
                $error = 'Logical PHP keywords must be uppercase; expected "%s" but found "%s"';
                $data  = array(
                          strtoupper($keyword),
                          $keyword,
                         );
                $phpcsFile->addError($error, $stackPtr, 'Found', $data);
            }
        } else {
            if (strtolower($keyword) !== $keyword) {
                $error = 'Non-logical PHP keywords must be lowercase; expected "%s" but found "%s"';
                $data  = array(
                          strtolower($keyword),
                          $keyword,
                         );
                $phpcsFile->addError($error, $stackPtr, 'Found', $data);
            }
        }

    }//end process()


}//end class

?>