<?php
class TemplateEngine {

    const TYPE_HTML = 'HTML';

    const TYPE_XSLT = 'XSLT';

    const TYPE_FILE = 'FILE';

    public static function run ($template) {

        switch ((string) $template['type']) {
            case self::TYPE_XSLT:
                self::doXSLT($template);
                break;
            default:
                define('TEMPLATE_PATH', (string) $template['path']);
                include_once TEMPLATE_PATH;
        }
    }

    private static function doXSLT ($template) {
        if(isset($template->input) && self::TYPE_FILE == (string)$template->input['type']){
            echo XsltHandler::getInstance()->transform((string)$template->input['value'],(string)$template['path']);
        }
    }

}
?>