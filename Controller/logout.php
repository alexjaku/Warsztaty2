<?php

	session_start();

	session_unset();

	header("Location: __dir__/../../Views/log.php");

?>
