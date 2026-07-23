<?php
function test(mixed $incoming):void {
	if(TESTOPERATION) {
		echo('<pre class="ta">');
		print_r($incoming);
		echo('</pre>');
	}
}
?>