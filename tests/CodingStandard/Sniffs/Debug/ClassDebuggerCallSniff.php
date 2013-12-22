<?php
/**
 * Warns about the use of debug code.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer_MySource
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006-2012 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Warns about the use of debug code.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer_MySource
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006-2012 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class CodingStandard_Sniffs_Debug_ClassDebuggerCallSniff implements PHP_CodeSniffer_Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_DOUBLE_COLON);

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

        $className = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);
        $method = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);
        $methodName = $tokens[$method]['content'];

        $classes = array('debug', 'debugger');
        $methods = array('dump', 'bardump', 'firelog', 'timer');

        if (in_array(strtolower($tokens[$className]['content']), $classes)
            && in_array(strToLower($methodName), $methods)) {
            $error  = 'Call to debug function %s::%s() should be removed';
            $data   = array($tokens[$className]['content'], $methodName);
            $phpcsFile->addError($error, $stackPtr, 'Found', $data);
        }

    }//end process()


}//end class

?>
