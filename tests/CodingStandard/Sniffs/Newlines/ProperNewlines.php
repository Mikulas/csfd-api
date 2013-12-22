<?php

/**
 * @category PHP
 * @package Clevis\Sim
 */
class CodingStandard_Sniffs_Newlines_ProperNewlines implements PHP_CodeSniffer_Sniff
{

	/**
	 * Returns the token types that this sniff is interested in.
	 *
	 * @return array(int)
	 */
	public function register()
	{
		return array(T_WHITESPACE);
	}

	/**
	 * Processes the tokens that this sniff is interested in.
	 *
	 * @param PHP_CodeSniffer_File $phpcsFile The file where the token was found.
	 * @param int                  $stackPtr  The position in the stack where
	 *                                        the token was found.
	 *
	 * @return void
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		var_dump(func_get_args());die;
		$tokens = $phpcsFile->getTokens();
		if ($tokens[$stackPtr]['content']{0} === '#') {
			$error = 'Hash comments are prohibited; found %s';
			$data  = array(trim($tokens[$stackPtr]['content']));
			$phpcsFile->addError($error, $stackPtr, 'Found', $data);
		}

	}

}
