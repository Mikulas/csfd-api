<?php
/**
 * Checks that all uses of NULL are uppercase.
 *
 * @category  PHP
 * @package   SIM_CodeSniffer
 */
class CodingStandard_Sniffs_PHP_UpperCaseNullConstantSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array(
                                   'PHP',
                                   'JS',
                                  );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
                T_NULL,
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
        $tokens = $phpcsFile->getTokens();

        // Is this a member var name?
        $prevPtr = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);
        if ($tokens[$prevPtr]['code'] === T_OBJECT_OPERATOR) {
            return;
        }

        // Is this a class name?
        if ($tokens[$prevPtr]['code'] === T_CLASS
            || $tokens[$prevPtr]['code'] === T_EXTENDS
            || $tokens[$prevPtr]['code'] === T_IMPLEMENTS
            || $tokens[$prevPtr]['code'] === T_NEW
        ) {
            return;
        }

        // Class or namespace?
        if ($tokens[($stackPtr - 1)]['code'] === T_NS_SEPARATOR) {
            return;
        }

        $keyword = $tokens[$stackPtr]['content'];
        if (strtoupper($keyword) !== $keyword) {
            $error = 'NULL must be uppercase; expected "%s" but found "%s"';
            $data  = array(
                      strtoupper($keyword),
                      $keyword,
                     );
            $phpcsFile->addError($error, $stackPtr, 'Found', $data);
        }

    }//end process()


}//end class

?>
