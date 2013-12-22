<?php
/**
 * Ensures all presenter classes are either final or abstract
 *
 */
class CodingStandard_Sniffs_MVC_AbstractOrFinalPresenterSniff implements PHP_CodeSniffer_Sniff {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return array(T_CLASS);
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
	 * @param integer $stackPtr The position of the current token in the stack passed in $tokens.
	 * @return void
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();

		$namePtr = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);
		$class = $tokens[$namePtr]['content'];
		$match = array();
		if (!preg_match('~^(?P<name>(?P<base>Base)?.*?)Presenter$~', $class, $match)) {
			// not a presenter class
			return;
		}

		$modifiers = array();
		$offset = 1;
		while (true) {
			$token = $tokens[$stackPtr - $offset];
			if (!in_array($token['code'], array(
				T_WHITESPACE, T_FINAL, T_ABSTRACT
			))) {
				break;
			}
			if ($token['code'] !== T_WHITESPACE) {
				$modifiers[] = $token['code'];
			}

			$offset++;
		}

		if (isset($match['base']) && !in_array(T_ABSTRACT, $modifiers)) {
			$error = 'Base presenter %s must be defined abstract';
			$phpcsFile->addError($error, $stackPtr, 'AbstractBasePresenter', array($class));

		} elseif (!count($modifiers)) {
			$error = 'Presenter %s must be either final or abstract';
			$phpcsFile->addError($error, $stackPtr, 'NoPresenterModifier', array($class));
		}
	}

}
