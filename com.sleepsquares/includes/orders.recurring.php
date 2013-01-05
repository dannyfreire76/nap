<?php
?>
<script type="text/javascript" src="../includes/jquery.js"></script>
<script type="text/javascript" src="../includes/_.jquery.js"></script>
<span>Want this order repeated <i>periodically</i>?
    <form method="post" action="#" id="recurring" name="recurring">
	<ul style="list-style:none;">
	    <li><input type="submit" name="recurring" value="Yes!" /> Please ship it again, every <select name="every" id="every">
	    <option value="30" selected="selected">
		30 days
	    </option>
	    <option value="60">
		60 days
	    </option>
	</select>.</li>
	    <li><input type="submit" name="recurring" value="No" />, thanks.  Maybe later.</li>
	</ul>
	<input type="hidden" name="after" id="after" value="2*7">
    </form></span>
</body>
</html>
