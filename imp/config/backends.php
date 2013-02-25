<?php
/**
 * This file specifies which mail servers IMP can login to.
 *
 * IMPORTANT: DO NOT EDIT THIS FILE!
 * Local overrides MUST be placed in backends.local.php or backends.d/.
 * If the 'vhosts' setting has been enabled in Horde's configuration, you can
 * use backends-servername.php.
 *
 * Example configuration file that sets a different server name than localhost
 * for the IMAP server:
 *
 * <code>
 * <?php
 * $servers['imap']['hostspec'] = 'imap.example.com';
 * </code>
 *
 * Example configuration file that enables the advanced IMAP server in favor of
 * the simple server and enables 'hordeauth':
 *
 * <code>
 * <?php
 * $servers['imap']['disabled'] = true;
 * $servers['advanced']['disabled'] = false;
 * $servers['advanced']['hordeauth'] = true;
 * </code>
 *
 * Properties that can be set for each server:
 * ===========================================
 *
 * disabled: (boolean) If true, the config entry is disabled.
 *
 * name: (string) This is the name displayed in the server list on the login
 *   screen.
 *
 * hostspec: (string) The hostname/IP address of the mail server to connect to.
 *
 * hordeauth: (mixed) Use Horde authentication?  One of:
 *     - true: [DEFAULT] IMP will attempt to use the user's existing
 *             credentials (the username/password they used to log in to
 *             Horde with) to login to this server.
 *             Everything after and including the first @ in the username
 *             will be stripped off before attempting authentication.
 *     - 'full': The username will be used unmodified.
 *     - false: Don't use Horde authentication; always require separate login.
 *
 * protocol: (string) The server protocol.  One of:
 *     - 'imap': [DEFAULT] IMAP. Requires a IMAP4rev1 (RFC 3501) compliant
 *               server.
 *     - 'pop': POP3. Requires a POP3 (RFC 1939) compliant server. All
 *              mailbox options will be disabled (POP3 does not support
 *              mailboxes). Other advanced functions will also be disabled
 *              (e.g. caching, searching, sorting).
 *
 * secure: (mixed) Security method used to connect to the server. One of:
 *     - 'ssl': [DEPRECATED; see below] Use SSL to connect to the server.
 *     - 'tls': [DEFAULT on IMAP; RECOMMENDED] Use TLS to connect to the
 *              server.
 *     - false: [DEFAULT on POP3; NOT RECOMMENED] Do not use any encryption.
 *
 *   The 'ssl' and 'tls' options will only work if you've compiled PHP
 *   with SSL support and the mail server supports secure connections.
 *
 *   The use of 'ssl' is STRONGLY DISCOURAGED. If a secure connection
 *   is needed, 'tls' should be used and the connection should be made
 *   to the base protocol port (110 for POP3, 143 for IMAP).
 *   See: http://wiki2.dovecot.org/SSL
 *
 * port: (integer) The port that the mail service/protocol you selected runs
 *   on. Default values:
 *     - imap (unsecure or w/TLS):  143
 *     - imap (w/SSL):  993 (DEPRECATED - use TLS on port 143)
 *     - pop (unsecure or w/TLS):  110
 *     - pop (w/SSL):  995 (DEPRECATED - use TLS on port 110)
 *
 * maildomain: (string) What to put after the @ when sending mail. This
 *   setting is generally useful when the sending host is different from the
 *   mail receiving host. This setting will also be used to complete
 *   unqualified addresses when composing mail. E.g. If you want all sent
 *   messages to look like:
 *
 *       From: user@example.com
 *
 *   set 'maildomain' to 'example.com'.
 *
 * smtp: (array) If Horde is configured to use SMTP as the mailer, entries in
 *   this array will overwrite the default Horde SMTP parameters. The
 *   following configuration parameters are available:
 *     - auth: (integer|boolean) Authentication method to use. Set to boolean
 *             true to choose the best available authenticate method
 *             (RECOMMENDED).
 *     - debug: (string) If set, enables SMTP debugging. See the 'debug'
 *              parameter below for acceptable values.
 *     - host: (string) SMTP server host.
 *     - localhost: (string) The local hostname.
 *     - password: (string) Password to use for SMTP server authentication (if
 *                 empty, uses IMP authentication password).
 *     - port: (integer) SMTP server port.
 *     - username: (string) Username to use for SMTP server authentication (if
 *                 empty, uses IMP authentication username).
 *
 * admin: [IMAP only] (array) Use this if you want to enable mailbox
 *   management for administrators via Horde's user administration interface.
 *   The mailbox management gets enabled if you let IMP handle the Horde
 *   authentication with the 'application' authentication driver.  Your IMAP
 *   server needs to support mailbox management via IMAP commands.
 *
 *   Do not define this value if you do not want mailbox management [DEFAULT].
 *
 *   The following parameters are available:
 *     - 'password': (string) The admin user's password.
 *     - 'user': (string) The admin user.
 *     - 'userhierarchy': (string) The hierarchy where user mailboxes are
 *                        stored.
 *
 * acl: [IMAP only] (boolean) Access Control Lists (ACLs).  One of:
 *     - true:  Enable ACLs. (Not all IMAP servers support this feature).
 *     - false:  [DEFAULT] Disable ACLs.
 *
 * cache: (mixed) Enables caching for the server. This requires configuration
 *   of a Horde_Cache driver in Horde. Will be disabled on any empty value and
 *   enabled on any non-false value.
 *
 *   Caching is HIGHLY RECOMMENDED. There is no reason not to cache if the
 *   IMAP server supports the CONDSTORE and/or QRESYNC IMAP extensions. If the
 *   server does not support these extensions, and caching is enabled, any
 *   flags changed by another mail agent while the IMP session is active will
 *   not be updated. If IMP will be the exclusive method of accessing the IMAP
 *   server, or you do not care about this behavior, caching should also be
 *   enabled on these servers.
 *
 *   The following optional parameters are available:
 *     - 'lifetime': (integer) The lifetime, in seconds, of the cached data.
 *     - 'slicesize': (integer) The number of messages stored in each cache
 *                    slice.  (The default should be fine for most users.)
 *
 * debug: (string) If set, will output debug information from the IMAP
 *   library. The value can be any PHP supported wrapper that can be opened
 *   via PHP's fopen() command. This setting should not be enabled by default
 *   on production servers, since the log file will quickly grow very large.
 *
 *   Example: To output to a file, provide the full path to the file (a bare
 *   string is interpreted by PHP to be a filename). This file must be
 *   writable by the PHP process.
 *
 *   Example 2: To restrict logging to a certain user ('foo'), and to log this
 *   output to the file '/tmp/imaplog', the following can be used:
 *
 *     ($GLOBALS['registry']->getAuth() == 'foo') ? '/tmp/imaplog' : false
 *
 * debug_raw: (boolean) By default, IMAP debugging (see 'debug') will only
 *   output a short summary of the message text sent to and received from the
 *   server. If you want the debug stream to output the full, raw data of the
 *   client/server communication, set this option to true.
 *
 * quota: (array) Use this if you want to display a user's quota status. Set
 *   to an empty value to disable quota status (DEFAULT).
 *
 *   To enable, set the 'driver' key to the name of the driver. The 'params'
 *   key can contain optional configuration parameters.
 *
 *   These 'params' keys are available for ALL drivers:
 *     - 'hide_when_unlimited': (boolean) True if you want to hide quota
 *                              output when the server reports an unlimited
 *                              quota.
 *     - 'format': (array) Specifies the formats of the quota messages
 *                 disaplayed to the user. The array must contain the
 *                 following four keys:
 *                   - 'long'
 *                   - 'short'
 *                   - 'nolimit_long'
 *                   - 'nolimit_short'
 *                 The values for each of these keys are strings that will be
 *                 passed through PHP's sprintf() command.
 *
 *                 The default values for each key is as follows (these might
 *                 appear slightly different based on the current language;
 *                 [UNIT] will be replaced with the value of the 'unit'
 *                 parameter):
 *                   - 'long': Quota status: %.2f [UNIT] / %.2f [UNIT] (%.2f%%)
 *                   - 'nolimit_long: Quota status: %.2f [UNIT] / NO LIMIT
 *                   - 'short': %.0f%% of %.0f [UNIT]
 *                   - 'nolimit_short': %.0f [UNIT]
 *     - 'unit': (string) What storage unit the quota messages should be
 *               displayed in.  One of:
 *                 - 'GB'
 *                 - 'MB' [DEFAULT]
 *                 - 'KB'
 *
 *   These are the available drivers, along with their optional parameters:
 *     - 'command':  Use the UNIX quota command to handle quotas. Parameters:
 *         - 'quota_path': (string) [REQUIRED] Path to the quota binary.
 *                         binary. Command line parameters can be specified in
 *                         this value.
 *         - 'grep_path': (string) [REQUIRED] Path to the grep binary.
 *         - 'partition': (string) If all user mailboxes are on a single
 *                        partition, the partition label. By default, will
 *                        determine quota information using the user's home
 *                        directory value.
 *     - 'hook': Use the quota hook to handle quotas (see
 *               imp/config/hooks.php). All parameters defined for this driver
 *               will be passed to the quota hook function.
 *     - 'imap': Use the IMAP QUOTA extension to handle quotas. The IMAP
 *               server must support the QUOTAROOT command to use this driver.
 *               This is the RECOMMENDED way of handling quotas.
 *     - 'maildir': Use Maildir++ quota files to handle quotas. Parameters:
 *         - 'msg_count': (boolean) Display information on the message limit
 *                        rather than the storage limit? The storage limit
 *                        information is displayed by default.
 *         - 'path': (string) The path to the user's Maildir directory. You
 *                   may use the two-character sequence "~U" to represent the
 *                   user's account name, and the actual username will be
 *                   substituted in that location. Example:
 *                     '/home/~U/Maildir/' or '/var/mail/~U/Maildir/'.
 *     - 'mdaemon': Use Mdaemon server to handle quotas. Parameters:
 *         - 'app_location': (string) Location of the application.
 *     - 'mercury32': Use Mercury/32 server to handle quotas. Parameters:
 *         - 'mail_user_folder': (string) The path to folder mail mercury.
 *     - 'sql': Use arbitrary SQL queries to handle quotas. This driver
 *              accepts these SQL connection parameters:
 *                - 'database'
 *                - 'hostspec'
 *                - 'password'
 *                - 'phptype'
 *                - 'username'
 *              See horde/config/conf.php for further information on these
 *              parameters. If using the Horde DB, these parameters can be
 *              found in Horde's $GLOBALS['conf']['sql'] variable and may be
 *              merged into the parameter configuration like this:
 *                'params' => array_merge(
 *                    $GLOBALS['conf']['sql'],
 *                    array(
 *                        'query_quota' => [...],
 *                        'query_used' => [...],
 *                    )
 *                )
 *
 *             Additional SQL parameters:
 *               - 'query_quota': (string) SQL query which returns single
 *                                row/column with user quota (in bytes). %u is
 *                                replaced with current user name, %U with the
 *                                user name without the domain part, and %d
 *                                with the domain.
 *               - 'query_used': (string) SQL query which returns single
 *                               row/column with user used space (in bytes).
 *                               Placeholders are the same as in
 *                               'query_quota'.
 *
 * disable_features: (array) A list of features to disable for this backend.
 *   One or more of the following:
 *     - IMP_Imap::DISABLE_FOLDERS: Disable folder navigation (other than
 *       INBOX) for IMAP backends. This option has no effect on POP backends.
 *
 * autocreate_special: (boolean) If true, automatically create special
 *                     mailboxes (Drafts, Sent Mail, Spam, Trash) on login?
 *
 *
 * *** The following options should NOT be set unless you REALLY know what ***
 * *** you are doing! FOR MOST PEOPLE, AUTO-DETECTION OF THESE PARAMETERS  ***
 * *** (the default if the parameters are not set) SHOULD BE USED!         ***
 *
 * capability_ignore: [IMAP only] (array) A list of IMAP capabilites to
 *   ignore, even if they are supported on the server. The capability names
 *   should be in all capitals. This option may be useful, for example, if it
 *   is known that a certain capability is buggy on the given server.
 *   Otherwise, all available and supported IMAP capabilities will be (and
 *   should be) used.
 *
 * comparator: [IMAP only] (string) The search comparator to use instead of
 *   the default IMAP server comparator (e.g. for sorting text fields). See
 *   RFC 4790 [3.1] - "collation-id" - for the format. Your IMAP server must
 *   support the I18NLEVEL extension. By default, the server default
 *   comparator is used.
 *
 * id: [IMAP only] (array) Send ID information to the IMAP server. This must
 *   be an array with the keys being the fields to send and the values being
 *   the associated values. Your IMAP server must support the ID extension.
 *   See RFC 2971 [3.3] for a list of defined field values.
 *
 * lang: [IMAP only] (array) A list of languages (in priority order) to be
 *   used to display human readable messages returned by the IMAP server. Your
 *   IMAP server must support the LANGUAGE extension. By default, IMAP
 *   messages are output in the IMAP server default language.
 *
 * namespace: [IMAP only] (array) The list of namespaces that exist on the
 *   server. Example:
 *
 *     array('#shared/', '#news/', '#public/')
 *
 *   This parameter should only be used if you want to allow access to names
 *   namespaces that may not be publicly advertised by the IMAP server (see
 *   RFC 2342 [3]). These additional namespaces will be ADDED to the list of
 *   available namespaces returned by the server.
 *
 * preferred: (string | array) Useful if you want to use the same backends.php
 *   file for different machines. If the hostname of the IMP machine is
 *   identical to one of those in the preferred list, then that entry will be
 *   selected by default on the login screen. Otherwise the first entry in the
 *   list is selected.
 *
 * sort_force: (boolean) By default, IMP only allows sorting by criteria
 *   other than arrival time if using IMAP and the remote IMAP server supports
 *   the SORT extension (RFC 5256). If this setting is true, IMP will
 *   implement sorting on the web server. However, this requires that the
 *   selected sort criteria be downloaded from the remote server for EVERY
 *   message in a mailbox before the mailbox can be displayed. For mailboxes
 *   that contain more than a few hundred messages, this can be a tremendously
 *   expensive operation. Enable sorting on these installations at your peril.
 *
 * thread: [IMAP only] (string) Set the preferred thread sort algorithm. This
 *   algorithm must be supported by the remote server. By default, IMP
 *   attempts to use REFERENCES sorting and, if this is not available, will
 *   fallback to ORDEREDSUBJECT sorting performed by Horde on the local server.
 *
 * timeout: (integer) Set the server timeout (in seconds).
 */

/* Example configurations: */

$servers['imap'] = array(
    // ENABLED by default
    'disabled' => false,
    'name' => 'IMAP Server',
    'hostspec' => 'localhost',
    'hordeauth' => false,
    'protocol' => 'imap',
    'port' => 143,
    // Plaintext logins are disabled by default on IMAP servers (see RFC 3501
    // [6.2.3]), so TLS is the only guaranteed authentication available by
    // default.
    'secure' => 'tls',
    'maildomain' => '',
    'smtp' => array(
    //    'auth' => true,
    //    'debug' => false,
    //    'localhost' => 'localhost',
    //    'host' => 'smtp.example.com',
    //    'password' => null,
    //    'port' => 25,
    //    'username' => null
    ),
    'cache' => false,
);

$servers['advanced'] = array(
    // Disabled by default
    'disabled' => true,
    'name' => 'Advanced IMAP Server',
    'hostspec' => 'localhost',
    'hordeauth' => false,
    'protocol' => 'imap',
    'port' => 143,
    'secure' => 'tls',
    'maildomain' => '',
    'smtp' => array(
    //    'auth' => true,
    //    'debug' => false,
    //    'localhost' => 'localhost',
    //    'host' => 'smtp.example.com',
    //    'password' => null,
    //    'port' => 25,
    //    'username' => null
    ),
    'admin' => array(
    //     'user' => 'cyrus',
    //     'password' => 'cyrus_pass',
    //     'userhierarchy' => 'user.'
    ),
    'acl' => true,
    'cache' => false,
    // 'debug' => '/tmp/imp_imap.log',
    // 'debug_raw' => false,
    'quota' => array(
        'driver' => 'imap',
        'params' => array(
            'hide_when_unlimited' => true,
            'unit' => 'MB'
        )
    ),
    'disable_features' => array(
        // IMP_Imap::DISABLE_FOLDERS,
    ),
    'autocreate_special' => false,
);

$servers['pop'] = array(
    // Disabled by default
    'disabled' => true,
    'name' => 'POP3 Server',
    'hostspec' => 'localhost',
    'hordeauth' => false,
    'protocol' => 'pop3',
    'port' => 110,
    'secure' => false,
    'maildomain' => '',
    'smtp' => array(
    //    'auth' => true,
    //    'debug' => false,
    //    'localhost' => 'localhost',
    //    'host' => 'smtp.example.com',
    //    'password' => null,
    //    'port' => 25,
    //    'username' => null
    ),
    'cache' => false,
);

$servers['secure-imap'] = array(
    // Disabled by default
    'disabled' => true,
    'name' => 'Secure IMAP Server',
    'hostspec' => 'localhost',
    'hordeauth' => false,
    'protocol' => 'imap',
    'port' => 143,
    'secure' => 'tls',
    'maildomain' => '',
    'smtp' => array(
    //    'auth' => true,
    //    'debug' => false,
    //    'localhost' => 'localhost',
    //    'host' => 'smtp.example.com',
    //    'password' => null,
    //    'port' => 25,
    //    'username' => null
    ),
    'acl' => false,
    'cache' => false,
);
