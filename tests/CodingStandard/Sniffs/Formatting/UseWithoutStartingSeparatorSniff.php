<?php
/**
 * Processes this test, when one of its tokens is encountered.
 *
 * Disalows:
 * <code>
 * use \Foo\Bar;
 * </code>
 *
 * Expects:
 * <code>
 * use Foo\Bar;
 * </code>
 */
class CodingStandard_Sniffs_Formatting_UseWithoutStartingSeparatorSniff implements PHP_CodeSniffer_Sniff {

/**
 * Returns an array of tokens this test wants to listen for.
 *
 * @return array
 */
	public function register() {
		return array(T_USE);
	}

/**
 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
 * @param integer $stackPtr The position of the current token in the stack passed in $tokens.
 * @return void
 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();

		$isClosure = $phpcsFile->findPrevious(
			array(T_CLOSURE),
			($stackPtr - 1),
			null,
			false,
			null,
			true
		);
		if ($isClosure) {
			return;
		}

		if ($tokens[$stackPtr + 1]['code'] === T_WHITESPACE && $tokens[$stackPtr + 2]['code'] === T_NS_SEPARATOR)
		{
			$error = 'Usings must not start with opening ns separator';
			$phpcsFile->addError($error, $stackPtr, 'NotAllowed', array());
		}
	}

}
