<?php

declare(strict_types=1);

namespace Sulu\Bundle\StylingBundle\Admin;

use Sulu\Bundle\StylingBundle\Entity\Styling;
use Sulu\Bundle\AdminBundle\Admin\Admin;
use Sulu\Bundle\AdminBundle\Admin\Navigation\NavigationItem;
use Sulu\Bundle\AdminBundle\Admin\Navigation\NavigationItemCollection;
use Sulu\Bundle\AdminBundle\Admin\View\TogglerToolbarAction;
use Sulu\Bundle\AdminBundle\Admin\View\ToolbarAction;
use Sulu\Bundle\AdminBundle\Admin\View\ViewBuilderFactoryInterface;
use Sulu\Bundle\AdminBundle\Admin\View\ViewCollection;
use Sulu\Component\Webspace\Manager\WebspaceManagerInterface;

class StylingAdmin extends Admin
{
    const STYLING_LIST_KEY = 'styling';

    const STYLING_FORM_KEY = 'styling_details';

    const STYLING_LIST_VIEW = 'sulu_styling.styling_list';

    const STYLING_ADD_FORM_VIEW = 'sulu_styling.styling_add_form';

    const STYLING_EDIT_FORM_VIEW = 'sulu_styling.styling_edit_form';

    /**
     * @var ViewBuilderFactoryInterface
     */
    private $viewBuilderFactory;

    /**
     * @var WebspaceManagerInterface
     */
    private $webspaceManager;

    public function __construct(
        ViewBuilderFactoryInterface $viewBuilderFactory,
        WebspaceManagerInterface $webspaceManager
    ) {
        $this->viewBuilderFactory = $viewBuilderFactory;
        $this->webspaceManager = $webspaceManager;
    }

    public function configureNavigationItems(NavigationItemCollection $navigationItemCollection): void
    {
        $module = new NavigationItem('sulu_styling.styling');
        $module->setPosition(40);
        $module->setIcon('fa-calendar');

        // Configure a NavigationItem with a View
        $styling = new NavigationItem('sulu_styling.styling');
        $styling->setPosition(10);
        $styling->setView(static::STYLING_LIST_VIEW);

        $module->addChild($styling);

        $navigationItemCollection->add($module);
    }

    public function configureViews(ViewCollection $viewCollection): void
    {
        $locales = $this->webspaceManager->getAllLocales();

        // Configure styling List View
        $listToolbarActions = [new ToolbarAction('sulu_admin.add'), new ToolbarAction('sulu_admin.delete')];
        $listView = $this->viewBuilderFactory->createListViewBuilder(self::STYLING_LIST_VIEW, '/styling/:locale')
            ->setResourceKey(Styling::RESOURCE_KEY)
            ->setListKey(self::STYLING_LIST_KEY)
            ->setTitle('sulu_styling.styling')
            ->addListAdapters(['table'])
            ->addLocales($locales)
            ->setDefaultLocale($locales[0])
            ->setAddView(static::STYLING_ADD_FORM_VIEW)
            ->setEditView(static::STYLING_EDIT_FORM_VIEW)
            ->addToolbarActions($listToolbarActions);
        $viewCollection->add($listView);

        // Configure styling Add View
        $addFormView = $this->viewBuilderFactory->createResourceTabViewBuilder(self::STYLING_ADD_FORM_VIEW, '/styling/:locale/add')
            ->setResourceKey(Styling::RESOURCE_KEY)
            ->setBackView(static::STYLING_LIST_VIEW)
            ->addLocales($locales);
        $viewCollection->add($addFormView);

        $addDetailsFormView = $this->viewBuilderFactory->createFormViewBuilder(self::STYLING_ADD_FORM_VIEW . '.details', '/details')
            ->setResourceKey(Styling::RESOURCE_KEY)
            ->setFormKey(self::STYLING_FORM_KEY)
            ->setTabTitle('sulu_admin.details')
            ->setEditView(static::STYLING_EDIT_FORM_VIEW)
            ->addToolbarActions([new ToolbarAction('sulu_admin.save')])
            ->setParent(static::STYLING_ADD_FORM_VIEW);
        $viewCollection->add($addDetailsFormView);

        // Configure styling Edit View
        $editFormView = $this->viewBuilderFactory->createResourceTabViewBuilder(static::STYLING_EDIT_FORM_VIEW, '/styling/:locale/:id')
            ->setResourceKey(Styling::RESOURCE_KEY)
            ->setBackView(static::STYLING_LIST_VIEW)
            ->setTitleProperty('title')
            ->addLocales($locales);
        $viewCollection->add($editFormView);

        $formToolbarActions = [
            new ToolbarAction('sulu_admin.save'),
            new ToolbarAction('sulu_admin.delete'),
            new TogglerToolbarAction(
                'sulu_styling.enable_styling',
                'enabled',
                'enable',
                'disable'
            ),
        ];
        $editDetailsFormView = $this->viewBuilderFactory->createFormViewBuilder(static::STYLING_EDIT_FORM_VIEW . '.details', '/details')
            ->setResourceKey(Styling::RESOURCE_KEY)
            ->setFormKey(self::STYLING_FORM_KEY)
            ->setTabTitle('sulu_admin.details')
            ->addToolbarActions($formToolbarActions)
            ->setParent(static::STYLING_EDIT_FORM_VIEW);
        $viewCollection->add($editDetailsFormView);
    }
}
