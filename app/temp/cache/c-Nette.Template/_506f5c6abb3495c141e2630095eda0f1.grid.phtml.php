<?php //netteCache[01]000163a:2:{s:4:"time";s:21:"0.39689700 1249890604";s:2:"df";a:1:{s:79:"D:\01Data\htdocs\01Vyvoj\TabControl\app\components\DataGrid\DataGrid/grid.phtml";i:1249042693;}}?><?php
// file D:\01Data\htdocs\01Vyvoj\TabControl\app\components\DataGrid\DataGrid/grid.phtml
//

$_cb = CurlyBracketsMacros::initRuntime($template, NULL, '87f605d9eb'); unset($_extends);

if (SnippetHelper::$outputAllowed) {
} if ($_cb->foo = SnippetHelper::create($control, "grid")) { $_cb->snippets[] = $_cb->foo ?>

<?php foreach ($iterator = $_cb->its[] = new SmartCachingIterator($flashes) as $flash): ?>
<div class="flash <?php echo TemplateHelpers::escapeHtml($flash->type) ?>"><?php echo TemplateHelpers::escapeHtml($flash->message) ?></div>
<?php endforeach; array_pop($_cb->its); $iterator = end($_cb->its) ?>

<?php  $control->render('begin') ; $control->render('errors') ; $control->render('body') ; $control->render('end') ?>

<?php array_pop($_cb->snippets)->finish(); } if (SnippetHelper::$outputAllowed) { 
}
?>