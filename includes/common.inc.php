<?php

// This function displays the incoming data when TESTOPERATION is active.

function test(mixed $incoming):void {

	if(TESTOPERATION) {

		echo('<pre class="test">');

		print_r($incoming);

		echo('</pre>');

	}
}
?>