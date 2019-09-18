<?php
function creatingInput($class, $id, $name, $type, $label='', $errors=array(), $options = array()) {
	global $inputLabelProto;
	global $inputHelpProto;
	global $inputProto;
	global $inputLongProto;
		
	$val = '';
	if (isset($_POST[$name])) {
		$val = htmlspecialchars($_POST[$name]);
	} else if (isset($_GET[$name])) {
		$val = htmlspecialchars($_GET[$name]);
	}
	
	$labelPack = '';
	if (!empty($label)) {
		$replaces = array($name, $label);
		$labelPack = replacingHoldersInProto($inputLabelProto, $replaces);
	}
	
	$optionsPack = '';
	if (!empty($options)) {
		foreach($options as $k => $v) {
			$optionsPack .= "$k = '$v'";
		}
	}
	
	$errorAlert = '';
	$errorHelp = '';
	if (array_key_exists($name, $errors)) {
		$errorAlert = 'block-error';
		$replaces = array();
		$replaces = array($errors[$name]);
		$errorHelp = replacingHoldersInProto($inputHelpProto, $replaces);
	}
		
	if ($type === 'text' || $type === 'email' || $type === 'password' || $type === 'checkbox' || $type === 'hidden') {
		$replaces = array();
		$replaces = array($errorAlert, $labelPack, $class, $id, $name, $type, $optionsPack, $val, $errorHelp);
		$input = replacingHoldersInProto($inputProto, $replaces);
	} else if ($type === 'textarea') {
		$replaces = array();
		$replaces = array($errorAlert, $labelPack, $errorHelp, $class, $id, $name, $optionsPack, $val);
		$input = replacingHoldersInProto($inputLongProto, $replaces);
	}
	
	// final things is to combine all parts into a prototype
	return $input;
}

function creatingSelect($class, $id, $name, $label, $artOptions=array(), $errors=array(), $options = array()) {
	global $artOptionProto;
	global $artSelectProto;
	global $inputLabelProto;
	
	$labelPack = '';
	if (!empty($label)) {
		$replaces = array($name, $label);
		$labelPack = replacingHoldersInProto($inputLabelProto, $replaces);
	}

	
	$artOptionsHanlder = '';
	if (!empty($artOptions)) {
		foreach($artOptions as $value => $htmlText) {
			$replaces = array();
			$replaces = array($value, $htmlText);
			$artOptionsHanlder .= replacingHoldersInProto($artOptionProto, $replaces);
		}
	}

	$errorAlert = '';
	$errorHelp = '';
	if (array_key_exists($name, $errors)) {
		$errorAlert = 'block-error';
		$replaces = array();
		$replaces = array($errors[$name]);
		$errorHelp = replacingHoldersInProto($inputHelpProto, $replaces);
	}

	$optionsPack = '';
	if (!empty($options)) {
		foreach($options as $k => $v) {
			$optionsPack .= "$k = '$v'";
		}
	}

	$replaces = array($labelPack, $class, $errorAlert, $id, $name, $optionsPack, $artOptionsHanlder, $errorHelp);
	$select = replacingHoldersInProto($artSelectProto, $replaces);
	
	return $select;
}

function creatingButton($class, $id, $name, $label='', $val) {
	global $inputLabelProto;
	global $buttonProto;
			
	$labelPack = '';
	if (!empty($label)) {
		$replaces = array($name, $label);
		$labelPack = replacingHoldersInProto($inputLabelProto, $replaces);
	}
	
	$replaces = array();
	$replaces = array($labelPack, $class, $id, $name, $val);
	$button = replacingHoldersInProto($buttonProto, $replaces);
	return $button;
}

function creatingSubmit($class, $id, $name, $value) {
	global $submitProto;
		
	$replaces = array($class, $id, $name, $value);
	$btn = replacingHoldersInProto($submitProto, $replaces);
	return $btn;
}

function creatingFormP($class, $file, $inputSeries) {
	global $formPProto;
		
	$replaces = array($class, $file, $inputSeries);
	$form = replacingHoldersInProto($formPProto, $replaces);
	return $form;
}

function creatingFormG($class, $file, $inputSeries) {
	global $formGProto;
		
	$replaces = array($class, $file, $inputSeries);
	$form = replacingHoldersInProto($formGProto, $replaces);
	return $form;
}

?>