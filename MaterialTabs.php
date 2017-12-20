<?php

namespace faboslav\materialtabs;

use yii\base\InvalidConfigException;
use yii\bootstrap\Widget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class MaterialTabs extends Widget
{
    /**
     * @var string auto id prefix for material tabs for all material tabs widgets.
     */
    public static $autoIdPrefix = 'material-tabs-';

    /**
     * @var array of tav items.
     */
    public $items = [];

    /**
     * @var boolean whether to enable ripple effect on the tabs header items.
     */
    public $ripple = true;

    /**
     * @var boolean whether to enable moving border under the menu items.
     */
    public $border = true;

    /**
     * @var boolean whether to enable arrows for scrolling between header items.
     */
    public $arrows = true;

    public $navOptions;

    public $navItemOptions;

    public $contentOptions;

    public $contentItemOptions;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->initWidget();
        echo $this->renderTabs();
    }

    /**
     * Initializes the widget settings.
     */
    public function initWidget()
    {
        Html::addCssClass($this->options, ['widget' => 'material-tabs']);

        $this->registerAssets();
    }

    /**
     * Registers the assets.
     */
    public function registerAssets()
    {
        $view = $this->getView();

        MaterialTabsAsset::register($view);

        $id = '#' . $this->options['id'];

        if ($this->border) {
            $view->registerJs(<<<JS
                $('{$id} nav .viewport ul').append('<div class="material-tabs-border"></div>');
JS
            );
        }

        $view->registerJs(<<<JS
            jQuery('{$id}').materialTabs().init();
JS
        );

        if ($this->arrows) {
            $view->registerJs(<<<JS
                jQuery('{$id}').materialTabs().checkViewport();

                $(".viewport").scroll(function () {
                    jQuery('{$id}').materialTabs().checkViewport();
                });
        
                $(window).resize(function () {
                    jQuery('{$id}').materialTabs().checkViewport();
                });
                
                $(document).on('click', '{$id} .left-arrow', function (e) {
                    $('{$id} nav .viewport').animate({scrollLeft: $("{$id} nav .viewport").scrollLeft() - 150}, 150, 'swing');
                });
        
                $(document).on('click', '{$id} .right-arrow', function (e) {
                    $('{$id} nav .viewport').animate({scrollLeft: $("{$id} nav .viewport").scrollLeft() + 150}, 150, 'swing');
                });
JS
            );
        }

        $view->registerJs(<<<JS
            $(document).on('click', '{$id} nav li', function () {
                jQuery('{$id}').materialTabs().setActiveItem($(this));
            });

            $(window).resize(function () {
                activeItem = $('{$id} nav li.active');
    
                if (activeItem.length) {
                    jQuery('{$id}').materialTabs().setBorder(activeItem);
                }
            });
JS
        );

        if ($this->ripple) {
            $selector = "jQuery('{$id} nav .viewport ul li')";
            $view->registerJs("{$selector}.materialRipple();");
        }
    }

    /**
     * Renders tabs
     *
     * @return string the rendering result.
     */
    public function renderTabs()
    {
        return Html::tag('div', $this->renderNavItems() . $this->renderContentItems(), $this->options);
    }

    /**
     * Renders tabs nav items
     *
     * @return string the rendering result.
     */
    public function renderNavItems()
    {
        $items = [];
        $navClass = null;

        foreach ($this->items as $i => $item) {
            $items[] = $this->renderNavItem($i, $item);

            if (is_null($navClass) && array_key_exists('icon', $item)) {
                $navClass = 'with-icons';
            }
        }

        $navOptions = ArrayHelper::getValue($this, 'navOptions', []);

        $list = Html::tag('ul', implode("\n", $items));

        $leftFade = Html::tag('div', Html::tag('div', '', ['class' => 'left-arrow']), ['class' => 'left-fade']);
        $viewport = Html::tag('div', $list, ['class' => 'viewport']);
        $rightFade = Html::tag('div', Html::tag('div', '', ['class' => 'right-arrow']), ['class' => 'right-fade']);

        return Html::tag('nav', $leftFade . $viewport . $rightFade, merge($navOptions, [
            'class' => $navClass
        ]));
    }

    /**
     * Renders tab nav item
     *
     * @return string the rendering result.
     */
    public function renderNavItem($i, $item)
    {
        if (is_string($item)) {
            return $item;
        }

        $navItemOptions = ArrayHelper::getValue($item, 'options', []);

        if (isset($item['active']) && $item['active']) {
            Html::addCssClass($navItemOptions, 'active');
        }

        if (!isset($item['label'])) {
            throw new InvalidConfigException("The 'label' option is required.");
        }

        $icon = '';

        if (isset($item['icon']) && $item['icon'] !== '') {
            $icon = $item['icon'];
        }

        $navItemContent = Html::tag('div', $icon . Html::tag('span', $item['label']), ['class' => 'nav-tab-content']);

        return Html::tag('li', $navItemContent, merge($navItemOptions, [
            'data' => [
                'tab' => ($i + 1)
            ]
        ]));
    }

    /**
     * Renders tabs content items
     *
     * @return string the rendering result.
     */
    public function renderContentItems()
    {
        $items = [];

        foreach ($this->items as $i => $item) {
            $items[] = $this->renderContentItem($i, $item);
        }

        $contentOptions = ArrayHelper::getValue($this, 'contentOptions', []);

        return Html::tag('div', implode("\n", $items), merge($contentOptions, [
            'class' => 'tabs'
        ]));
    }

    /**
     * Renders tab content item
     *
     * @return string the rendering result.
     */
    public function renderContentItem($i, $item)
    {
        if (!isset($item['content'])) {
            throw new InvalidConfigException("The 'content' option is required.");
        }

        $contentItemOptions = ArrayHelper::getValue($item, 'contentItemOptions', []);

        Html::addCssClass($contentItemOptions, 'tab-content');

        if (isset($item['active']) && $item['active']) {
            Html::addCssClass($contentItemOptions, 'active');
        }

        return Html::tag('div', $item['content'], merge($contentItemOptions, [
            'data' => [
                'tab' => ($i + 1)
            ]
        ]));
    }
}