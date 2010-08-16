<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GeoPortal Server
 *
 * Exception class
 *
 * PHP 5
 *
 * @package		classes
 * @subpackage	exception
 * @author		Steffen Vogel (info@steffenvogel.de)
 * @modifedby	$LastChangedBy: steffen $
 * @copyright	Copyright (c) 2009 Steffen Vogel (info@steffenvogel.de)
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://www.geoportal.griesm.de
 * @since		38
 * @version		$Revision: 45 $
 * @lastmodifed	$Date: 2009-04-28 20:06:10 +0200 (Di, 28 Apr 2009) $
 * @filesource	$HeadURL: http://svn.griesm.de/geo/server/trunk/include/classes/exception.php $
 */

class EBase extends Exception {

	function toXml($xml, $withTrace = false) {
		$xmlError = $xml->createElement('error');

		$xmlError->appendChild($xml->createElement('message', $this->message));
		$xmlError->appendChild($xml->createElement('code', $this->code));
		$xmlError->appendChild($xml->createElement('file', $this->file));
		$xmlError->appendChild($xml->createElement('line', $this->line));
		if ($withTrace == true) {
			$xmlTrace = $xml->createElement('trace');
			$i = 0;
			foreach ($this->getTrace() as $step) {
				$xmlTraceStep = $xml->createElement('step' . $i++);
				$xmlTraceStep->appendChild($xml->createElement('file', $step['file']));
				$xmlTraceStep->appendChild($xml->createElement('line', $step['line']));
				$xmlTraceStep->appendChild($xml->createElement('function', $step['function']));
				$p = 0;
				$xmlTraceStepArgs = $xml->createElement('args');
				foreach ($step['args'] as $arg) {
					$xmlTraceStepArgs->appendChild($xml->createElement('arg' . $p, is_scalar($arg) ? gettype($arg) . ': ' . $arg : gettype($arg)));
				}
				$xmlTraceStep->appendChild($xmlTraceStepArgs);
				$xmlTrace->appendChild($xmlTraceStep);
			}
			$xmlError->appendChild($xmlTrace);
		}

		return $xmlError;
	}
}

class EError extends EBase {
	private $context;

	public function __construct($message, $code, $file, $line, $context = null) {
		parent::__construct($message, $code);

		$this->file = $file;
		$this->line = $line;

		$this->context = $context;
	}
}


abstract class TErrorHandler {
	public static function initialize() {
		set_error_handler(array('TErrorHandler', 'handleError'), E_ALL ^ E_NOTICE);
	}

	public static function uninitialize() {
		restore_error_handler();
	}

	public static function handleError($errno, $errstr, $errfile, $errline, $errcontext) {
		throw new EError($errstr, $errno, $errfile, $errline, $errcontext);
	}
}

?>