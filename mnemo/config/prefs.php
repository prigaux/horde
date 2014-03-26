<?php
/**
 * See horde/config/prefs.php for documentation on the structure of this file.
 *
 * IMPORTANT: DO NOT EDIT THIS FILE!
 * Local overrides MUST be placed in prefs.local.php or prefs.d/.
 * If the 'vhosts' setting has been enabled in Horde's configuration, you can
 * use prefs-servername.php.
 */

$prefGroups['display'] = array(
    'column' => _("General Preferences"),
    'label' => _("Display Preferences"),
    'desc' => _("Change your note sorting and display preferences."),
    'members' => array('show_notepad', 'sortby', 'sortdir')
);

$prefGroups['share'] = array(
    'column' => _("Notepad and Share Preferences"),
    'label' => _("Default Notepad"),
    'desc' => _("Choose your default Notepad."),
    'members' => array('default_notepad')
);

$prefGroups['sync'] = array(
    'column' => _("Notepad and Share Preferences"),
    'label' => _("Synchronization Preferences"),
    'desc' => _("Choose the Notepads to use for synchronization with external devices."),
    'members' => array('sync_notepads', 'activesync_no_multiplex'),
);

$prefGroups['deletion'] = array(
    'column' => _("General Preferences"),
    'label' => _("Delete Confirmation"),
    'desc' => _("Delete button behaviour"),
    'members' => array('delete_opt')
);


// show a notepad column in the list view?
$_prefs['show_notepad'] = array(
    'value' => 0,
    'type' => 'checkbox',
    'desc' => _("Should the Notepad be shown in its own column in the List view?")
);

// user preferred sorting column
$_prefs['sortby'] = array(
    'value' => Mnemo::SORT_DESC,
    'type' => 'enum',
    'enum' => array(
        Mnemo::SORT_DESC => _("Note Text"),
        Mnemo::SORT_NOTEPAD => _("Notepad"),
        Mnemo::SORT_MOD_DATE => _("Modification Date"),
    ),
    'desc' => _("Default sorting criteria:")
);

// user preferred sorting direction
$_prefs['sortdir'] = array(
    'value' => 0,
    'type' => 'enum',
    'enum' => array(
        Mnemo::SORT_ASCEND => _("Ascending"),
        Mnemo::SORT_DESCEND => _("Descending")
    ),
    'desc' => _("Default sorting direction:")
);

// default notepad
// Set locked to true if you don't want users to have multiple notepads.
$_prefs['default_notepad'] = array(
    'value' => '',
    'type' => 'enum',
    'enum' => array(),
    'desc' => _("Your default notepad:"),
    'on_init' => function($ui) {
        $enum = array();
        foreach (Mnemo::listNotepads(false, Horde_Perms::EDIT) as $key => $val) {
            $enum[$key] = Mnemo::getLabel($val);
        }
        $ui->prefs['default_notepad']['enum'] = $enum;
    },
    'on_change' => function() {
        $GLOBALS['injector']->getInstance('Mnemo_Factory_Notepads')
            ->create()
            ->setDefaultShare($GLOBALS['prefs']->getValue('default_notepad'));
        $sync = @unserialize($GLOBALS['prefs']->getValue('sync_notepads'));
        $haveDefault = false;
        $default = Mnemo::getDefaultNotepad(Horde_Perms::EDIT);
        foreach ($sync as $cid) {
            if ($cid == $default) {
                $haveDefault = true;
                break;
            }
        }
        if (!$haveDefault) {
            $sync[] = $default;
            $GLOBALS['prefs']->setValue('sync_notepads', serialize($sync));
        }
    },
);

// Sync
$_prefs['sync_notepads'] = array(
    'value' => 'a:0:{}',
    'type' => 'multienum',
    'enum' => array(),
    'desc' => _("Select the notepads that, in addition to the default, should be used for synchronization with external devices:"),
    'on_init' => function($ui) {
        $enum = array();
        $sync = @unserialize($GLOBALS['prefs']->getValue('sync_notepads'));
        if (empty($sync)) {
            $GLOBALS['prefs']->setValue('sync_notepads', serialize(array(Mnemo::getDefaultNotepad())));
        }
        foreach (Mnemo::listNotepads(false, Horde_Perms::EDIT) as $key => $list) {
            if ($list->getName() != Mnemo::getDefaultNotepad(Horde_Perms::EDIT)) {
                $enum[$key] = Mnemo::getLabel($list);
            }
        }
        $ui->prefs['sync_notepads']['enum'] = $enum;
    },
    'on_change' => function() {
        $sync = @unserialize($GLOBALS['prefs']->getValue('sync_notepads'));
        $haveDefault = false;
        $default = Mnemo::getDefaultNotepad(Horde_Perms::EDIT);
        foreach ($sync as $cid) {
            if ($cid == $default) {
                $haveDefault = true;
                break;
            }
        }
        if (!$haveDefault) {
            $sync[] = $default;
            $GLOBALS['prefs']->setValue('sync_notepads', serialize($sync));
        }
        if ($GLOBALS['conf']['activesync']['enabled'] && !$GLOBALS['prefs']->getValue('activesync_no_multiplex')) {
            try {
                $sm = $GLOBALS['injector']->getInstance('Horde_ActiveSyncState');
                $sm->setLogger($GLOBALS['injector']->getInstance('Horde_Log_Logger'));
                $devices = $sm->listDevices($GLOBALS['registry']->getAuth());
                foreach ($devices as $device) {
                    $sm->removeState(array(
                        'devId' => $device['device_id'],
                        'id' => Horde_Core_ActiveSync_Driver::TASKS_FOLDER_UID,
                        'user' => $GLOBALS['registry']->getAuth()
                    ));
                }
                $GLOBALS['notification']->push(_("All state removed for your ActiveSync devices. They will resynchronize next time they connect to the server."));
            } catch (Horde_ActiveSync_Exception $e) {
                $GLOBALS['notification']->push(_("There was an error communicating with the ActiveSync server: %s"), $e->getMessage(), 'horde.error');
            }
        }
    }
);

// @todo We default to using multiplex since that is the current behavior
// For Mnemo 5 we should default to separate.
$_prefs['activesync_no_multiplex'] = array(
    'type' => 'checkbox',
    'desc' => _("Support separate notepads?"),
    'value' => 0
);

// store the notepads to diplay
$_prefs['display_notepads'] = array(
    'value' => 'a:0:{}'
);

// preference for delete confirmation dialog.
$_prefs['delete_opt'] = array(
    'value' => 1,
    'type' => 'checkbox',
    'desc' => _("Do you want to confirm deleting entries?")
);
