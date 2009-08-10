<?php //netteCache[01]000162a:2:{s:4:"time";s:21:"0.48705800 1249890548";s:2:"df";a:1:{s:78:"D:\01Data\htdocs\01Vyvoj\TabControl\app\components\TabControl/TabControl.phtml";i:1249851997;}}?><?php
// file D:\01Data\htdocs\01Vyvoj\TabControl\app\components\TabControl/TabControl.phtml
//

$_cb = CurlyBracketsMacros::initRuntime($template, NULL, '96e3dbda8c'); unset($_extends);

if (SnippetHelper::$outputAllowed) {
} if ($_cb->foo = SnippetHelper::create($control, "content")) { $_cb->snippets[] = $_cb->foo; 
       $control->tabContainer->addClass("ui-tabs ui-widget ui-widget-content ui-corner-all");
       $control->tabContainer->id = $control->DOMtabsID ?>
    <?php echo $control->tabContainer->startTag() ?>

                <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
<?php foreach ($iterator = $_cb->its[] = new SmartCachingIterator($control->components) as $name => $tab): ?>
                <?
                  $li = Html::el("li")->class("ui-tabs-panel ui-widget-content ui-state-default ui-corner-top");
                  if($activeTab->getName() == $name)
                      $li->addClass("ui-tabs-selected ui-state-active");
                  else
                      $li->addClass("ui-state-default") ?>
                <?php echo $li->startTag() ?>

                    <a id="<?php echo TemplateHelpers::escapeHtml($control->getSnippetId('a_'.$name)) ?>" href="<?php echo TemplateHelpers::escapeHtml($control->link("activateTab!", array("activeTab"=>$name))) ?>">
                       <span><?php echo $tab->header ?></span>
                    </a>
                <?php echo $li->endTag() ?>

<?php endforeach; array_pop($_cb->its); $iterator = end($_cb->its) ?>
        </ul>

                <?php } foreach ($iterator = $_cb->its[] = new SmartCachingIterator($control->components) as $name => $tab): if (SnippetHelper::$outputAllowed) { ?>
                        <?php } 
                $container = Html::el("div")->addClass("ui-tabs-panel ui-widget-content ui-corner-bottom");
                if($activeTab->getName() != $name)
                    $container->addClass("ui-tabs-hide"); if (SnippetHelper::$outputAllowed) { } if ($_cb->foo = SnippetHelper::create($control, $name, $container)) { $_cb->snippets[] = $_cb->foo ?>
                <?php } if (isSet($component->tabsForDraw[$name])): if (SnippetHelper::$outputAllowed) { ?>
                    <?php }  $tab->renderContent(); if (SnippetHelper::$outputAllowed) { ?>
                <?php } endif ;if (SnippetHelper::$outputAllowed) { array_pop($_cb->snippets)->finish(); } if (SnippetHelper::$outputAllowed) { ?>
        <?php } endforeach; array_pop($_cb->its); $iterator = end($_cb->its) ;if (SnippetHelper::$outputAllowed) { ?>
    <?php echo $control->tabContainer->endTag() ?>


    <?php } if ($control->mode !== TabControl::MODE_NO_AJAX): if (SnippetHelper::$outputAllowed) { ?>
        <script type="text/JavaScript">
            $(function(){
<?php if (is_string($control->loaderText)): foreach ($iterator = $_cb->its[] = new SmartCachingIterator($control->components) as $name => $tab): if ($activeTab->getName() != $name): ?>
                            $("#"+<?php echo TemplateHelpers::escapeJs($control->getSnippetId($name)) ?>).livequery(function(){
                                $(this).html(<?php echo TemplateHelpers::escapeJs($control->loaderText) ?>);
                            });
<?php endif ;endforeach; array_pop($_cb->its); $iterator = end($_cb->its) ;endif ;foreach ($iterator = $_cb->its[] = new SmartCachingIterator($control->components) as $name => $tab): ?>
                        $("#"+<?php echo TemplateHelpers::escapeJs($control->getSnippetId('a_'.$name)) ?>).each(function(){
                            var jThis = $(this);
                            jThis.attr("url",jThis.attr("href"));
                            jThis.attr("href","#"+<?php echo TemplateHelpers::escapeJs($control->getSnippetId($name)) ?>);
                            jThis.attr("loaded",false);
                            jThis.get(0).jTab = $("#"+<?php echo TemplateHelpers::escapeJs($control->getSnippetId($name)) ?>)
                            jThis.get(0).jTab.get(0).jAnchor = jThis;

<?php if ($control->mode == TabControl::MODE_PRELOAD): if ($activeTab->getName() != $name): ?>
                                    $.getJSON(<?php echo TemplateHelpers::escapeJs($control->link("preload", array('activeTab'=>$name))) ?>);
<?php endif ;endif ?>
                            
                            $("#"+<?php echo TemplateHelpers::escapeJs($control->DOMtabsID) ?>).bind("tabsselect",function(event,ui){
                                if(ui.panel.id === <?php echo TemplateHelpers::escapeJs($control->getSnippetId($name)) ?>){
                                    var tab = $("#"+<?php echo TemplateHelpers::escapeJs($control->getSnippetId("a_".$name)) ?>);
                                    var panel = $("#"+<?php echo TemplateHelpers::escapeJs($control->getSnippetId($name)) ?>);

<?php if ($control->mode == TabControl::MODE_LAZY): ?>
                                        if(panel.html() == <?php echo TemplateHelpers::escapeJs($control->loaderText) ?>){
                                            //alert("test");
                                            $.getJSON(jThis.attr("url"));
                                            jThis.attr("loaded",1);
                                        }
<?php endif ?>

<?php if ($control->mode == TabControl::MODE_RELOAD): if (is_string($control->loaderText)): ?>
                                            jThis.get(0).jTab.html(<?php echo TemplateHelpers::escapeJs($control->loaderText) ?>);
<?php endif ?>
                                        $.getJSON(jThis.attr("url"));
<?php endif ?>
                                }
                            })
                        });
<?php endforeach; array_pop($_cb->its); $iterator = end($_cb->its) ?>

                /* Tohle by bylo fajn, ale nevím jak změnit persistentní parametr v průběhu renderování
<?php if ($control->mode === TabControl::MODE_PRELOAD): ?>
                    $.getJSON(<?php echo TemplateHelpers::escapeJs($control->link("preload!")) ?>);
<?php endif ?>
                */
                $("#"+<?php echo TemplateHelpers::escapeJs($control->DOMtabsID) ?>).livequery(function(){
                    $(this).tabs(<?php echo $component->jQueryTabsOptions ?>)
                })
            })
        </script>
<?php } if ($_cb->foo = SnippetHelper::create($control, "JavaScript")) { $_cb->snippets[] = $_cb->foo ?>
            <script type="text/JavaScript">
                $(function(){
                    var tabs = $("#"+<?php echo TemplateHelpers::escapeJs($control->DOMtabsID) ?>)
<?php foreach ($iterator = $_cb->its[] = new SmartCachingIterator($control->javaScript) as $code): ?>
                        <?php echo $code ?>

<?php endforeach; array_pop($_cb->its); $iterator = end($_cb->its) ?>
                });
            </script>
<?php array_pop($_cb->snippets)->finish(); } if (SnippetHelper::$outputAllowed) { ?>
    <?php } endif ;if (SnippetHelper::$outputAllowed) { array_pop($_cb->snippets)->finish(); } if (SnippetHelper::$outputAllowed) { 
}
?>