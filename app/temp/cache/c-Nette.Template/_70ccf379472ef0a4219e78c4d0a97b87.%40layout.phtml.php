<?php //netteCache[01]000158a:2:{s:4:"time";s:21:"0.43977300 1249890548";s:2:"df";a:1:{s:74:"D:\01Data\htdocs\01Vyvoj\TabControl\app/templates/Default/../@layout.phtml";i:1249852670;}}?><?php
// file …/Default/../@layout.phtml
//

$_cb = CurlyBracketsMacros::initRuntime($template, NULL, '6ff7674db9'); unset($_extends);


//
// block #content
//
if (!function_exists($_cb->blocks['#content'][] = '_cbb0875a8b3e0__content')) { function _cbb0875a8b3e0__content() { extract(func_get_arg(0))
;if (SnippetHelper::$outputAllowed) { ?>
                            No content
                        <?php } 
}}

//
// end of blocks
//

if ($_cb->extends) { ob_start(); }

if (SnippetHelper::$outputAllowed) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="en" />

	<title>TabControl - Nette Framework example</title>

	<!-- custom CSS -->
	<link rel="stylesheet" media="screen" href="http://jqueryui.com/latest/themes/cupertino/ui.all.css" type="text/css" />
	<link rel="stylesheet" media="screen" href="<?php echo TemplateHelpers::escapeHtml($baseUri) ?>css/screen.css" type="text/css" />
	<link rel="stylesheet" media="screen" href="<?php echo TemplateHelpers::escapeHtml($baseUri) ?>css/datagrid.css" type="text/css" />
	<link rel="stylesheet" media="screen" href="<?php echo TemplateHelpers::escapeHtml($baseUri) ?>css/ajax.css" type="text/css" />
	<link rel="stylesheet" media="print" href="<?php echo TemplateHelpers::escapeHtml($baseUri) ?>css/print.css" type="text/css" />

	<!-- jQuery -->
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
	<script type="text/javascript" src="<?php echo TemplateHelpers::escapeHtml($baseUri) ?>js/jquery.livequery.js"></script>
	<script type="text/javascript" src="http://jqueryui.com/latest/ui/ui.core.js"></script>
	<script type="text/javascript" src="http://jqueryui.com/latest/ui/ui.datepicker.js"></script>
	<script type="text/javascript" src="http://jqueryui.com/latest/ui/ui.tabs.js"></script>
	<script type="text/javascript" src="http://jqueryui.com/latest/ui/i18n/ui.datepicker-cs.js"></script>

	<!-- custom JS -->
	<script src="<?php echo TemplateHelpers::escapeHtml($baseUri) ?>js/jquery.nette.js" type="text/javascript"></script>
	<script src="<?php echo TemplateHelpers::escapeHtml($baseUri) ?>js/datagrid.js" type="text/javascript"></script>
</head>

<body>
	<h1>TabControl - Nette Framework example</h1>

<?php } if ($_cb->foo = SnippetHelper::create($control, "flashes")) { $_cb->snippets[] = $_cb->foo ?>
	<div id="flashes">
<?php foreach ($iterator = $_cb->its[] = new SmartCachingIterator($flashes) as $flash): ?>
		<div class="flash <?php echo TemplateHelpers::escapeHtml($flash->type) ?>"><?php echo $flash->message ?></div>
<?php endforeach; array_pop($_cb->its); $iterator = end($_cb->its) ?>

		<!--
		<div class="flash info">Info message.</div>
		<div class="flash success">Success message.</div>
		<div class="flash warning">Warning message.</div>
		<div class="flash error">Error message.</div>
		<div class="flash validation">Validation message.</div>
		-->

	</div>
<?php array_pop($_cb->snippets)->finish(); } if (SnippetHelper::$outputAllowed) { ?>


	<div id="mainframe-cover">
		<div id="mainframe" class="rounded">
			<?php } if (!$_cb->extends) { call_user_func(reset($_cb->blocks['#content']), get_defined_vars()); }  if (SnippetHelper::$outputAllowed) { ?>
		</div>
	</div>
</body>
</html>
<?php
}

if ($_cb->extends) { ob_end_clean(); CurlyBracketsMacros::includeTemplate($_cb->extends, get_defined_vars(), $template)->render(); }
