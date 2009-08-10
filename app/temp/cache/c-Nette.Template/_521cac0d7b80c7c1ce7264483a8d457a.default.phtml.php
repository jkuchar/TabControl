<?php //netteCache[01]000155a:2:{s:4:"time";s:21:"0.41039900 1249890548";s:2:"df";a:1:{s:71:"D:\01Data\htdocs\01Vyvoj\TabControl\app/templates/Default/default.phtml";i:1249889618;}}?><?php
// file …/Default/default.phtml
//

$_cb = CurlyBracketsMacros::initRuntime($template, true, '2603d340db'); unset($_extends);


//
// block #title
//
if (!function_exists($_cb->blocks['#title'][] = '_cbb4509391148__title')) { function _cbb4509391148__title() { extract(func_get_arg(0))
?>Tab Control<?php
}}


//
// block #content
//
if (!function_exists($_cb->blocks['#content'][] = '_cbb14da3d5c24__content')) { function _cbb14da3d5c24__content() { extract(func_get_arg(0))
;if (SnippetHelper::$outputAllowed) { ?>
    <h2>Externí ovládání TabControlu</h2>
    <table border="2">
        <tr>
            <td width="33%">
                <h3>Neajaxové chování</h3>
                <a href="<?php echo TemplateHelpers::escapeHtml($control->link("jdiNaTab!", array('form1'))) ?>">Přejdi na Formulář 1</a><br>
                <a href="<?php echo TemplateHelpers::escapeHtml($control->link("jdiNaTab!", array('datagrid'))) ?>">Přejdi na DataGrid</a>
            </td>
            <td width="33%">
                <h3>Ajaxové chování</h3>
                <a href="<?php echo TemplateHelpers::escapeHtml($control->link("jdiNaTab!", array('form1'))) ?>" class="ajax">Přejdi na Formulář 1</a><br>
                <a href="<?php echo TemplateHelpers::escapeHtml($control->link("prekresliTab!", array('form1'))) ?>" class="ajax">Překresli Formulář 1</a><br>
                <a href="<?php echo TemplateHelpers::escapeHtml($control->link("jdiNaTab!", array('datagrid'))) ?>" class="ajax">Přejdi na DataGrid</a>
            </td>
            <td width="33%">
                <h3>Kombinované chování (doporučeno)</h3>
                <p>Pokud je požadavek ajaxový, pouze se překreslí cílový tab.</p><br>
                <p>Pokud je požadavek neajaxový, přejde se na nový tab a přesměruje se pomocí redirect("this") na url bez handleru na změnu tabu. (leší kvůli vyhledávačům)</p><br>
                <a href="<?php echo TemplateHelpers::escapeHtml($control->link("jdiNaTabCanonicky!", array('form1'))) ?>" class="ajax">ajax: Přejdi na Formulář 1</a><br>
                <a href="<?php echo TemplateHelpers::escapeHtml($control->link("jdiNaTabCanonicky!", array('datagrid'))) ?>" class="ajax">ajax: Přejdi na DataGrid</a><br>
                <a href="<?php echo TemplateHelpers::escapeHtml($control->link("jdiNaTabCanonicky!", array('form1'))) ?>">normal: Přejdi na Formulář 1</a><br>
                <a href="<?php echo TemplateHelpers::escapeHtml($control->link("jdiNaTabCanonicky!", array('datagrid'))) ?>">normal: Přejdi na DataGrid</a><br>
            </td>
        </tr>
    </table>
    <h2 style="margin: 30px 0px 10px 0px;">Samotný TabControl</h2>
    <?php } $control->getWidget("tabs")->render() ;if (SnippetHelper::$outputAllowed) { } 
}}

//
// end of blocks
//

if ($_cb->extends) { ob_start(); }

if (SnippetHelper::$outputAllowed) {
} $_cb->extends = "../@layout.phtml" ;if (SnippetHelper::$outputAllowed) { ?>



<?php }  if (SnippetHelper::$outputAllowed) { 
}

if ($_cb->extends) { ob_end_clean(); CurlyBracketsMacros::includeTemplate($_cb->extends, get_defined_vars(), $template)->render(); }
