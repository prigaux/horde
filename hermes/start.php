<?php
/**
 * Copyright 2005-2009 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (BSD). If you
 * did not receive this file, see http://www.horde.org/licenses/bsdl.php.
 *
 * @author Chuck Hagenbuch <chuck@horde.org>
 * @author Jan Schneider <jan@horde.org>
 */

require_once dirname(__FILE__) . '/lib/Application.php';
$hermes = Horde_Registry::appInit('hermes');

$vars = Horde_Variables::getDefaultVariables();

$form = new Horde_Form($vars, _("Stop Watch"));
$form->addVariable(_("Stop watch description"), 'description', 'text', true);

if ($form->validate($vars)) {
    $timers = $prefs->getValue('running_timers', false);
    if (empty($timers)) {
        $timers = array();
    } else {
        $timers = @unserialize($timers);
        if (!$timers) {
            $timers = array();
        }
    }
    $now = time();
    $timers[$now] = array('name' => Horde_String::convertCharset($vars->get('description'),
                                                       Horde_Nls::getCharset(),
                                                       $prefs->getCharset()),
                          'time' => $now);
    $prefs->setValue('running_timers', serialize($timers), false);

    Horde_Util::closeWindowJS('alert(\'' . addslashes(sprintf(_("The stop watch \"%s\" has been started and will appear in the sidebar at the next refresh."), $vars->get('description'))) . '\');');
    exit;
}

$title = _("Stop Watch");
require HERMES_TEMPLATES . '/common-header.inc';

$renderer = new Horde_Form_Renderer();
$form->renderActive($renderer, $vars, 'start.php', 'post');

require $registry->get('templates', 'horde') . '/common-footer.inc';
