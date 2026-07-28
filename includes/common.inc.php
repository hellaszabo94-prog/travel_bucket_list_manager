<?php

// This function displays the incoming data when TESTOPERATION is active.

function test(mixed $incomeing):void {

	if(TESTOPERATION) {

		echo('<pre class="test">');

		print_r($incomeing);

		echo('</pre>');

	}
}
?>