<?
    global $_widgetTypeFactoryInstance;
    global $_allTypes; // TODO: fix bug in php5->php4
    class WidgetTypeFactory
    {

        function getInstance()
        {
            global $_widgetFactoryInstance;
            if (!$_widgetFactoryInstance) {
                $_widgetFactoryInstance = new WidgetTypeFactory ();
            }

            return $_widgetFactoryInstance;
        }

        function registerWidgetType($type, &$widget)
        {
            global $_allTypes;
            $_allTypes[$type] = $widget;
        }

        function getWidgetType($id)
        {
            global $_allTypes;
            if (isset($_allTypes[$id]))
                return $_allTypes[$id];

            $widgetFilename = PATH_WG_WIDGETS.$id."/widget.php";
            include_once($widgetFilename);

            if (isset($_allTypes[$id]))
                return $_allTypes[$id];
            else
                return PEAR::raiseError("Cannot find type: $type");
        }

    }

?>