<?php

//\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
//\\\       \\\\\\\\|                                                           \\
//\\\ @@    @@\\\\\\| Mail_IMAPv2                                               \\
//\\ @@@@  @@@@\\\\\|___________________________________________________________\\
//\\\@@@@| @@@@\\\\\|                                                           \\
//\\\ @@ |\\@@\\\\\\|(c) Copyright 2004-2005 Richard York, All rights Reserved  \\
//\\\\  ||   \\\\\\\|___________________________________________________________\\
//\\\\  \\_   \\\\\\|Redistribution and use in source and binary forms, with or \\
//\\\\\        \\\\\|without modification, are permitted provided that the      \\
//\\\\\  ----  \@@@@|following conditions are met:                              \\
//@@@@@\       \@@@@|                                                           \\
//@@@@@@\     \@@@@@| o Redistributions of source code must retain the above    \\
//\\\\\\\\\\\\\\\\\\|   copyright notice, this list of conditions and the       \\
//                      following disclaimer.                                   \\
//  o Redistributions in binary form must reproduce the above copyright notice, \\
//    this list of conditions and the following disclaimer in the documentation \\
//    and/or other materials provided with the distribution.                    \\
//  o The names of the authors may not be used to endorse or promote products   \\
//    derived from this software without specific prior written permission.     \\
//                                                                              \\
//  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" \\
//  AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE   \\
//  IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE  \\
//  ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE    \\
//  BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR      \\
//  CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF        \\
//  SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS    \\
//  INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN     \\
//  CONTRACT, STRICT LIABILITY, OR TORT  (INCLUDING NEGLIGENCE OR OTHERWISE)    \\
//  ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE  \\
//  POSSIBILITY OF SUCH DAMAGE.                                                 \\
//\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

require_once 'PEAR/ErrorStack.php';

define('Mail_IMAPv2_BODY',                                0);
define('Mail_IMAPv2_LITERAL',                             1);
define('Mail_IMAPv2_LITERAL_DECODE',                      2);

define('Mail_IMAPv2_ERROR',                               1);
define('Mail_IMAPv2_ERROR_ARGUMENT_REQUIRES_ARRAY',       2);
define('Mail_IMAPv2_ERROR_INVALID_OPTION',                3);
define('Mail_IMAPv2_ERROR_INVALID_PID',                   4);
define('Mail_IMAPv2_ERROR_INVALID_ACTION',                5);

define('Mail_IMAPv2_NOTICE',                              100);
define('Mail_IMAPv2_NOTICE_FALLBACK_PID',                 102);

define('Mail_IMAPv2_FATAL',                               200);

/**
* Mail_IMAPv2 provides a flexible API for connecting to and retrieving
* mail from mailboxes using the IMAP, POP3 or NNTP mail protocols.
* Connection to a mailbox is acheived through the c-client extension
* to PHP (http://www.php.net/imap). Meaning installation of the
* c-client extension is required to use Mail_IMAPv2.
*
* Mail_IMAPv2 can be used to retrieve the contents of a mailbox,
* whereas it may serve as the backend for a webmail application or
* mailing list manager.
*
* Since Mail_IMAPv2 is an abstracted object, it allows for complete
* customization of the UI for any application.
*
* By default Mail_IMAPv2 parses and retrieves information about
* multipart message in a threaded fashion similar to MS Outlook, e.g.
* only top level attachments are retrieved initially, then when message
* part id and message id are passed to Mail_IMAPv2, it retrieves
* attachments and information relevant to that message part.
* {@link getParts} can be supplied an argument to turn off threading,
* whereas all parts are retrieved at once.
*
* Mail_IMAPv2 also, by default retrieves the alternative message parts
* of multipart messages. This is most useful for debugging
* applications that send out multipart mailers where both a text/html
* and alterntaive text/plain part are included. This can also be
* turned off by supplying an additional argument to {@link getParts}.
*
* Mail_IMAPv2 always searches for a text/html part to display as the primary
* part. This can be reversed so that it always looks for a text/plain part
* initially by supplying the necessary arguments to {@link getParts},
* and {@link getBody}.
*
* PLEASE REPORT BUGS FOLLOWING THE GUIDELINES AT:
*   http://www.smilingsouls.net/Mail_IMAP
*
* @author       Richard York <rich_y@php.net>
* @category     Mail
* @package      Mail_IMAPv2
* @license      BSD
* @version      0.2.0
* @copyright    (c) Copyright 2004, Richard York, All Rights Reserved.
* @since        PHP 4.2.0
* @since        C-Client 2001
* @tutorial     http://www.smilingsouls.net/Mail_IMAP
*
* @example      docs/examples/IMAP.inbox.php
*   Mail_IMAPv2 Inbox
*
* @example      docs/examples/IMAP.message_viewer.php
*   Mail_IMAPv2 Message
*
* @example      docs/examples/IMAP.part_viewer.php
*   Mail_IMAPv2 Message
*
* @example      docs/examples/IMAP.connection_wizard.php
*   Mail_IMAPv2 Connection Wizard
*
* @example      docs/examples/IMAP.connection_wizard_example.php
*   Mail_IMAPv2 Connection Wizard
*/
class Mail_IMAPv2 {

    /**
    * Contains an instance of the PEAR_ErrorStack object.
    * @var      object $error
    * @access   public
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/error
    */
    var $error;

    /**
    * Contains the imap resource stream.
    * @var     resource $mailbox
    * @access  public
    * @see     Mail_IMAPv2
    * @see     open
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/mailbox
    */
    var $mailbox;

    /**
     * Contains information about the current mailbox.
     * @var     array $mailboxInfo
     * @access  public
     * @see     connect
     * @see     getMailboxInfo
     * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/mailboxInfo
     */
    var $mailboxInfo = array();

    /**
     * Set flags for various imap_* functions.
     *
     * Use associative indices to indicate the imap_* function to set flags for,
     *  create the indice omitting the 'imap_' portion of the function name.
     *  see: {@link setOptions} for more information.
     *
     * @var     array $option
     * @access  public
     * @see     setOptions
     * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/setOptions
     */
    var $option = array();

    /**
     * Contains various information returned by {@link imap_fetchstructure}.
     * The object returned by imap_fetchstructure stored in $this->structure[$mid]['obj'].
     *
     * @var     array $_structure
     * @access  public
     * @see     _declareParts
     * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/structure
     */
    var $structure = array();

    /**
     * Contains various information about a message, separates inline parts from
     * attachments and contains the default part id for each message.
     *
     * @var     array $msg
     * @access  public
     * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/msg
     */
    var $msg = array();

    /**
     * (array)(mixed) Associative array containing information
     * gathered by {@link imap_headerinfo} or
     * {@link imap_rfc822_parse_headers}.
     *
     * @var    header array $header
     * @see     getHeaders
     * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/header
     */

    var $header = array();

    /**
     * (string) contains the various possible data types.
     * @var     array $_dataTypes
     * @access  private
     * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/_dataTypes
     */
    var $_dataTypes = array(
    	'text',
        'multipart',
        'message',
        'application',
        'audio',
        'image',
        'video',
        'other'
    );

    /**
     * (string) Contains the various possible encoding types.
     * @var     array $_encodingTypes
     * @access  private
     * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/_encodingTypes
     */
    var $_encodingTypes = array(
    	'7bit',
        '8bit',
        'binary',
        'base64',
        'quoted-printable',
        'other'
    );

    /**
    * (string) Contains the fields searched for and added to inline and attachment part
    * arrays. These are the 'in' and 'at' associative indices of the $msg member variable.
    * @var    array $fields
    * @access public
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/fields
    */
    var $fields = array(
    	'fname',
        'pid',
        'ftype',
        'fsize',
        'has_at',
        'charset',
        'cid'
    );

    /**
    * Constructor. Optionally set the IMAP resource stream.
    *
    * If IMAP connection arguments are not supplied, returns null.  Accepts a URI
    * abstraction of the standard imap_open connection argument (see {@link connect})
    * or the imap resource indicator.
    *
    * If the optional flags argument of imap_open needs to be set, then {@link connect}
    * should be called after either setting the {@link $option} member variable or
    * calling {@link setOptions}.
    *
    * Since Mail_IMAPv2 0.1.0 creates an instance of PEAR_ErrorStack.
    *  $options argument became $get_info argument see {@link connect}.
    *
    * @param     string         $connection  (optional) server URI | imap resource identifier
    * @param     int            $action
    *
    * @tutorial  http://www.smilingsouls.net/?content=Mail_IMAP/Mail_IMAP
    * @access    public
    * @return    BOOL|null|PEAR_Error
    * @see       connect
    * @see       imap_open
    */
    function Mail_IMAPv2($connection = null, $get_info = true)
    {
        $this->error = new PEAR_ErrorStack('Mail_IMAPv2');

        if (!empty($connection) && is_resource($connection)) {
            if (get_resource_type($connection) == 'imap') {
                $this->mailbox = $connection;
            } else {
                $this->error->push(
                	Mail_IMAPv2_ERROR,
                	'error',
                	null,
                	'Invalid imap resource passed to constructor.'
                );
            }
        } else if (!empty($connection)) {
            $this->connect($connection, $get_info);
        }
    }

    /**
    * @todo Finish writing this method, and test it.
    */
    function errorTemplate()
    {
        return array(
            // Generic Error    
            Mail_IMAPv2_ERROR                           => '%message%',
            Mail_IMAPv2_ERROR_ARGUMENT_REQUIRES_ARRAY   => 'Argument \'%arg%\' must be an array.',
            Mail_IMAPv2_ERROR_INVALID_OPTION            => 'Indice \'%indice%\' for argument \'%arg%\' is not a valid option.',
            Mail_IMAPv2_ERROR_INVALID_PID               => 'Supplied part id \'%pid%\' is not valid.',
            Mail_IMAPv2_ERROR_INVALID_ACTION            => 'Action \'%action%\' is not a valid action for the \'%arg%\' argument.',
            Mail_IMAPv2_NOTICE_FALLBACK_PID             => 'Fallback PID used. A fallback PID is used in the event that Mail_IMAPv2 is not able to find a valid text/plain or text/html message part. The MIME type for the fallback pid is %ftype%'
        );
    }

    /**
    * Wrapper method for {@link imap_open}.  Accepts a URI abstraction in
    * the following format: imap://user:pass@mail.example.com:143/INBOX#notls
    * instead of the standard connection arguments used in imap_open.
    * Replace the protocol with one of pop3|pop3s imap|imaps nntp|nntps.
    * Place intial folder in the file path portion, and optionally append
    * tls|notls|novalidate-cert in the anchor portion of the URL.  A port
    * number is optional, however, leaving it off could lead to a serious
    * degradation in preformance.
    *
    * Since Mail_IMAPv2 0.1.0 the $options argument became the $get_info argument.
    * constants for action were removed and the argument is now a BOOL toggle.
    *
    * @param    string           $uri   server URI
    * @param    bool             $get_info
    *   (optional) true by default. If true, make a call to {@link getMailboxInfo}
    *   if false do not call {@link getMailboxInfo}
    * @return   BOOL
    * @tutorial http://www.smilingsouls.net/index.php?content=Mail_IMAP/connect
    * @access   public
    * @see      imap_open
    */
    function connect($uri, $get_info = true)
    {
        if (!class_exists('Net_URL') && !@include_once('Net/URL.php')) {
			$this->error->push(Mail_IMAPv2_ERROR, 'error', null, 'Inclusion of Net_URL not successful.');
            return false;
        }

        $opt = (isset($this->option['open']))? $this->option['open'] : null;

        $net_url =& new Net_URL($uri);

        $uri  = '{'.$net_url->host;

        if (!empty($net_url->port)) {
            $uri .= ':'.$net_url->port;
        }

        $secure   = ('tls' == substr($net_url->anchor, 0, 3))? '' : '/ssl';

        $uri .= ('s' == (substr($net_url->protocol, -1)))? '/'.substr($net_url->protocol, 0, 4).$secure : '/'.$net_url->protocol;

        if (!empty($net_url->anchor)) {
            $uri .= '/'.$net_url->anchor;
        }

        $uri .= '}';

        $this->mailboxInfo['Mail_IMAPv2']['version'] = 'Mail_IMAPv2 0.2.0 Beta';
        $this->mailboxInfo['host'] = $uri;

        // Trim off the leading slash '/'
        if (!empty($net_url->path)) {
            $this->mailboxInfo['folder'] = substr($net_url->path, 1, (strlen($net_url->path) - 1));
            $uri .= $this->mailboxInfo['folder'];
        }

        $this->mailboxInfo['user'] = urldecode($net_url->user);

        if (false === ($this->mailbox = @imap_open($uri, urldecode($net_url->user), $net_url->pass, $opt))) {
            $this->error->push(
            	Mail_IMAPv2_ERROR,
            	'error',
            	null,
            	'Unable to build a connection to the specified mail server.'
            );
            $ret = false;
        } else {
            $ret = true;
        }

        // get mailbox info
        if ($get_info) {
            $this->getMailboxInfo(false);
        }

        return $ret;
    }

    /*
    * Adds to the {@link $mailboxInfo} member variable information about the current
    * mailbox from {@link imap_mailboxmsginfo}.
    *
    * Note: This method is automatically called on by default by {@link connect}.
    *
    * @param    string           $connect   server URL
    * @param    bool             $get_info
    *   (optional) true by default. If true, make a call to {@link getMailboxInfo}
    *   if false do not call {@link getMailboxInfo}
    *
    * @return   VOID|Array
    * @access   public
    * @see      imap_open
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/getMailboxInfo
    */
    function getMailboxInfo($ret = true)
    {
        // It's possible that this function has already been called by $this->connect
        // If so, the 'Mailbox' indice will already exist and the user just wants
        // the contents of the mailboxInfo member variable.
        if (!isset($this->mailboxInfo['Mailbox'])) {
            $this->mailboxInfo = @array_merge(
            	$this->mailboxInfo,
            	get_object_vars(
            		imap_mailboxmsginfo($this->mailbox)
            	)
            );
        }

        return ($ret)? $this->mailboxInfo : true;
    }

    /**
    * Set the $option member variable, which is used to specify optional imap_* function
    * arguments (labeled in the manual as flags or options e.g. FT_UID, OP_READONLY, etc).
    *
    * <b>Example:</b>
    * <code>
    *    $msg->setOptions(array('body', 'fetchbody', 'fetchheader'), 'FT_UID');
    * </code>
    *
    * This results in imap_body, imap_fetchbody and imap_fetchheader being passed the FT_UID
    * option in the flags/options argument where ever these are called on by Mail_IMAPv2.
    *
    * Note: this method only sets optional imap_* arguments labeled as flags/options.
    *
    * @param    array          $options - function names to pass the arugument to
    * @param    string         $constant   - constant name to pass.
    * @return   PEAR_Error|true
    * @access   public
    * @see      $option
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/setOptions
    */
    function setOptions($options, $constant)
    {
        if (is_array($options) && !empty($options)) {
            foreach ($options as $value) {
                if (!$this->option[$value] = @constant($constant)) {
                    $this->error->push(
                    	Mail_IMAPv2_ERROR,
                    	'error',
                    	null,
                    	'The constant: '.$constant.' is not defined!'
                    );
                }
            }
        } else {
            $this->error->push(
            	Mail_IMAPv2_ERROR_ARGUMENT_REQUIRES_ARRAY,
            	'error',
            	array('arg' => '$options')
            );
            return false;
        }
        return true;
    }

    /**
    * Wrapper method for {@link imap_close}.  Close the IMAP resource stream.
    *
    * @return   BOOL
    * @access   public
    * @tutorial http://www.smilingsouls.net/index.php?content=Mail_IMAP/close
    * @see      imap_close
    */
    function close()
    {
        $opt = (isset($this->option['close']))? $this->option['close'] : null;
        return @imap_close($this->mailbox, $opt);
    }

    /**
    * Wrapper method for {@link imap_num_msg}.
    *
    * @return   int mailbox message count
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/messageCount
    * @access   public
    * @see      imap_num_msg
    */
    function messageCount()
    {
        return @imap_num_msg($this->mailbox);
    }

    /**
    * Gather message information returned by {@link imap_fetchstructure} and recursively iterate
    * through each parts array.  Concatenate part numbers in the following format `1.1`
    * each part id is separated by a period, each referring to a part or subpart of a
    * multipart message.  Create part numbers as such that they are compatible with
    * {@link imap_fetchbody}.
    *
    * @param    int           &$mid         message id
    * @param    array         $sub_part     recursive
    * @param    string        $sub_pid      recursive parent part id
    * @param    int           $n            recursive counter
    * @param    bool          $is_sub_part  recursive
    * @param    bool          $skip_part    recursive
    * @return   mixed
    * @access   protected
    * @see      imap_fetchstructure
    * @see      imap_fetchbody
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/_declareParts
    */
    function _declareParts(&$mid, $sub_part = null, $sub_pid = null, $n = 0, $is_sub_part = false, $skip_part = false, $last_was_signed = false)
    {
        if (!is_array($sub_part)) {
            $opt = (isset($this->option['fetchstructure']))? $this->option['fetchstructure'] : null;
            $this->structure[$mid]['obj'] = @imap_fetchstructure($this->mailbox, $mid, $opt);
        }

        if (isset($this->structure[$mid]['obj']->parts) || is_array($sub_part)) {
            if (!$is_sub_part) {
                $parts = $this->structure[$mid]['obj']->parts;
            } else {
                $parts = $sub_part;
                $n++;
            }

            for ($p = 0, $i = 1; $p < count($parts); $n++, $p++, $i++) {
                // Skip the following...
                // multipart/mixed!
                // subsequent multipart/alternative if this part is message/rfc822
                // multipart/related
                //
                // Have noticed the existence of several other multipart/* types of messages
                // but have yet had the opportunity to test on those.
                $ftype = (empty($parts[$p]->type))?
                    $this->_dataTypes[0].'/'.strtolower($parts[$p]->subtype)
                :
                	$this->_dataTypes[$parts[$p]->type].'/'.strtolower($parts[$p]->subtype);

                $this_was_signed	= ($ftype == 'multipart/signed')? true : false;
                $skip_next			= ($ftype == 'message/rfc822')?   true : false;

                if (
                	$ftype == 'multipart/mixed' && ($last_was_signed || $skip_part) || 
                	$ftype == 'multipart/signed' || 
                	$skip_part && $ftype == 'multipart/alternative' || 
                	$ftype == 'multipart/related' && count($parts) == 1
               	) {
                    $n--;
                    $skipped = true;
                } else {
                    $skipped = false;

                    $this->structure[$mid]['pid'][$n]       = ($is_sub_part == false)? (string) "$i" : (string) "$sub_pid.$i";
                    $this->structure[$mid]['ftype'][$n]     = $ftype;
                    $this->structure[$mid]['encoding'][$n]  = (empty($parts[$p]->encoding))? $this->_encodingTypes[0] : $this->_encodingTypes[$parts[$p]->encoding];
                    $this->structure[$mid]['fsize'][$n]     = (!isset($parts[$p]->bytes) || empty($parts[$p]->bytes))? 0 : $parts[$p]->bytes;

                    // Get extra parameters.
                    if ($parts[$p]->ifparameters) {
                        foreach ($parts[$p]->parameters as $param) {
                            $this->structure[$mid][strtolower($param->attribute)][$n] = strtolower($param->value);
                        }
                    }

                    // Force inline disposition if none is present
                    if ($parts[$p]->ifdisposition) {
                        $this->structure[$mid]['disposition'][$n] = strtolower($parts[$p]->disposition);
                        if ($parts[$p]->ifdparameters) {
                            foreach ($parts[$p]->dparameters as $param) {
                                if (strtolower($param->attribute) == 'filename') {
                                    $this->structure[$mid]['fname'][$n] = $param->value;
                                    break;
                                }
                            }
                        }
                    } else {
                        $this->structure[$mid]['disposition'][$n] = 'inline';
                    }

                    if ($parts[$p]->ifid) {
                        $this->structure[$mid]['cid'][$n] = $parts[$p]->id;
                    }
                }

                if (isset($parts[$p]->parts) && is_array($parts[$p]->parts)) {
                    if (!$skipped) {
                        $this->structure[$mid]['has_at'][$n] = true;
                    }

                    $n = $this->_declareParts($mid, $parts[$p]->parts, $this->structure[$mid]['pid'][$n], $n, true, $skip_next, $this_was_signed);
                }
                else if (!$skipped) {
                	$this->structure[$mid]['has_at'][$n] = false;
                }
            }

            if ($is_sub_part) {
                return $n;
            }
         } else {
             // $parts is not an array... message is flat
            $this->structure[$mid]['pid'][0] = 1;

            if (empty($this->structure[$mid]['obj']->type)) {
                $this->structure[$mid]['obj']->type = (int) 0;
            }

            if (isset($this->structure[$mid]['obj']->subtype)) {
                $this->structure[$mid]['ftype'][0] = $this->_dataTypes[$this->structure[$mid]['obj']->type].'/'.strtolower($this->structure[$mid]['obj']->subtype);
            }

            if (empty($this->structure[$mid]['obj']->encoding)) {
                $this->structure[$mid]['obj']->encoding = (int) 0;
            }

            $this->structure[$mid]['encoding'][0] = $this->_encodingTypes[$this->structure[$mid]['obj']->encoding];

            if (isset($this->structure[$mid]['obj']->bytes)) {
                $this->structure[$mid]['fsize'][0] = strtolower($this->structure[$mid]['obj']->bytes);
            }

            $this->structure[$mid]['disposition'][0]    = 'inline';
            $this->structure[$mid]['has_at'][0] = false;

            // Go through the parameters, if any
            if (isset($this->structure[$mid]['obj']->ifparameters) && $this->structure[$mid]['obj']->ifparameters) {
                foreach ($this->structure[$mid]['obj']->parameters as $param) {
                    $this->structure[$mid][strtolower($param->attribute)][0] = $param->value;
                }
            }
        }

        return;
    }

    /**
    * Checks if the part has been parsed, if not calls on _declareParts to
    * parse the message.
    *
    * @param    int          &$mid         message id
    * @param    bool         $checkPid
    * @return   void
    * @access   protected
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/_checkIfParsed
    */
    function _checkIfParsed(&$mid, $checkPid = true, $get_mime = 'text/html')
    {
        if (!isset($this->structure[$mid]['pid'])) {
           $this->_declareParts($mid);
        }

        if ($checkPid == true && !isset($this->msg[$mid]['pid'])) {
           $this->_getDefaultPid($mid, $get_mime);
        }
        return;
    }

    /**
    * sets up member variables containing inline parts and attachments for a specific
    * part in member variable arrays beginning with 'in' and 'attach'. If inline parts
    * are present, sets {@link $inPid}, {@link $inFtype}, {@link $inFsize},
    * {@link $inHasAttach}, {@link $inInlineId} (if an inline CID is specified). If
    * attachments are present, sets, {@link $attachPid}, {@link $attachFsize},
    * {@link $attachHasAttach}, {@link $attachFname} (if a filename is present, empty
    * string otherwise).
    *
    * @param    int           &$mid         message id
    * @param    int           &$pid         part id
    * @param    bool          $ret
    *   false by default, if true returns the contents of the $in* and $attach* arrays.
    *   If false method returns BOOL.
    *
    * @param    string        $args         (optional)
    *   Associative array containing optional extra arguments. The following are the
    *   possible indices.
    *
    *       $args['get_mime'] STRING
    *           Values: text/plain|text/html, text/html by default. The MIME type for
    *           the part to be displayed by default for each level of nesting.
    *
    *       $agrs['get_alternative'] BOOL
    *           If true, includes the alternative part of a multipart/alternative
    *           message in the $in* array. If veiwing text/html part by default this
    *           places the text/plain part in the $in* (inline attachment array).
    *
    *       $args['retrieve_all'] BOOL
    *           If true, gets all the message parts at once, this option will index
    *           the entire message in the $in* and $attach* member variables regardless
    *           of nesting (method indexes parts relevant to the current level of
    *           nesting by default).
    *
    * @return   BOOL|Array
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/getParts
    * @access   public
    * @since    PHP 4.2.0
    */
    function getParts(&$mid, $pid = '0', $ret = false, $args = array())
    {
        if (!isset($args['get_mime'])) {
            $args['get_mime'] = 'text/html';
        }

        if (!isset($args['get_alternative'])) {
            $args['get_alternative'] = true;
        }

        $this->_checkIfParsed($mid, true, $args['get_mime']);

        if ($pid === '0') {
            $pid = $this->msg[$mid]['pid'];
        }

        if (count($this->structure[$mid]['pid']) == 1 && !isset($this->structure[$mid]['fallback'][0])) {
            return true;
        }

        // retrieve key for this part, so that the information may be accessed
        if (false !== ($i = array_search((string) $pid, $this->structure[$mid]['pid']))) {
            if (isset($args['retrieve_all']) && $args['retrieve_all'] == true) {
                $this->_scanMultipart($mid, $pid, $i, $args['get_mime'], 'add', 'none', 2, $args['get_alternative']);
            } else {
                if ($pid == $this->msg[$mid]['pid']) {
                    $this->_scanMultipart($mid, $pid, $i, $args['get_mime'], 'add', 'top', 2, $args['get_alternative']);
                } else if ($this->structure[$mid]['ftype'][$i] == 'message/rfc822') {
                    $this->_scanMultipart($mid, $pid, $i, $args['get_mime'], 'add', 'all', 1, $args['get_alternative']);
                }
            }
        } else {
            $this->error->push(Mail_IMAPv2_ERROR_INVALID_PID, 'error', array('pid' => $pid));
            return false;
        }

        return ($ret)? $this->msg[$mid] : true;
    }

    /**
    * Finds message parts relevant to the message part currently being displayed or
    * looks through a message and determines which is the best body to display.
    *
    * @param    int           &$mid         message id
    * @param    int           &$pid         part id
    * @param    int           $i            offset indice correlating to the pid
    * @param    str           $MIME         one of text/plain or text/html the default MIME to retrieve.
    * @param    str           $action       one of add|get
    * @param    str           $look_for     one of all|multipart|top|none
    * @param    int           $pid_add      determines the level of nesting.
    * @param    bool          $get_alternative
    *   Determines whether the program retrieves the alternative part in a
    *   multipart/alternative message.
    *
    * @return   string|false
    * @access   private
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/_scanMultipart
    */
    function _scanMultipart(&$mid, &$pid, &$i, $MIME, $action = 'add', $look_for = 'all', $pid_add = 1, $get_alternative = true)
    {
        // Find subparts, create variables
        // Create inline parts first, and attachments second

        // Get all top level parts, with the exception of the part currently being viewed
        // If top level part contains multipart/alternative go into that subpart to
        // retrieve the other inline message part to display

        // If this part is message/rfc822 get subparts that begin with this part id
        // Skip multipart/alternative message part
        // Find the displayable message, get text/plain part if $getInline is true
        if ($action == 'add') {
           $excludeMIME = $MIME;
           $MIME        = ($excludeMIME == 'text/plain')? 'text/html' : 'text/plain';
           $in          = 0;
           $a           = 0;
        } else if ($action == 'get') {
           $excludeMIME = null;
        }

        $pid_len      = strlen($pid);
        $this_nesting = count(explode('.', $pid));

        foreach ($this->structure[$mid]['pid'] as $p => $id) {
            // To look at the next level of nesting one needs to determine at which level
            // of nesting the program currently resides, this needs to be independent of the
            // part id length, since part ids can get into double digits (let's hope they
            // don't get into triple digits!)

            // To accomplish this we'll explode the part id on the dot to get a count of the
            // nesting, then compare the string with the next level in.

            $nesting = count(explode('.', $this->structure[$mid]['pid'][$p]));

            switch ($look_for) {
                case 'all':
                {
                    $condition = (($nesting == ($this_nesting + 1)) && $pid == substr($this->structure[$mid]['pid'][$p], 0, $pid_len));
                    break;
                }
                case 'multipart':
                {
                    $condition = (($nesting == ($this_nesting + 1)) && ($pid == substr($this->structure[$mid]['pid'][$p], 0)));
                    break;
                }
                // Used if *all* parts are being retrieved
                case 'none':
                {
                    $condition = true;
                    break;
                }
                // To gaurantee a top-level part, detect whether a period appears in the pid string
                case 'top':
                default:
                {
                    if ($this->_isMultipart($mid, 'related') || $this->_isMultipart($mid, 'mixed')) {
                        $condition = (!stristr($this->structure[$mid]['pid'][$p], '.') || ($nesting == 2) && substr($this->msg[$mid]['pid'], 0, 1) == substr($this->structure[$mid]['pid'][$p], 0, 1));
                    } else {
                        $condition = (!stristr($this->structure[$mid]['pid'][$p], '.'));
                    }
                }
            }

            if ($condition == true) {
                if ($this->structure[$mid]['ftype'][$p] == 'multipart/alternative' || $this->structure[$mid]['ftype'][$p] == 'multipart/mixed') {
                    foreach ($this->structure[$mid]['pid'] as $mp => $mpid) {
                        // Part must begin with last matching part id and be two levels in
                        $sub_nesting = count(explode('.', $this->structure[$mid]['pid'][$p]));

                        if (
                        	$this->structure[$mid]['ftype'][$mp] == $MIME &&
                            $get_alternative == true &&
                            ($sub_nesting == ($this_nesting + $pid_add)) &&
                            ($pid == substr($this->structure[$mid]['pid'][$mp], 0, strlen($this->structure[$mid]['pid'][$p])))
                        ) {
                            if ($action == 'add') {
                                 $this->_addPart($in, $mid, $mp, 'in');
                                 break;
                            } else if ($action == 'get' && !isset($this->structure[$mid]['fname'][$mp]) && empty($this->structure[$mid]['fname'][$mp])) {
                                return $this->structure[$mid]['pid'][$mp];
                            }
                        } else if ($this->structure[$mid]['ftype'][$mp] == 'multipart/alternative' && $action == 'get') {
                            // Need to match this PID to next level in
                            $pid          = (string) $this->structure[$mid]['pid'][$mp];
                            $pid_len      = strlen($pid);
                            $this_nesting = count(explode('.', $pid));
                            $pid_add       = 2;
                            continue;
                        }
                    }
                } else if ($this->structure[$mid]['disposition'][$p] == 'inline' && $this->structure[$mid]['ftype'][$p] != 'multipart/related' && $this->structure[$mid]['ftype'][$p] != 'multipart/mixed') {
                    if ((
                    	  $action == 'add' &&
                          $this->structure[$mid]['ftype'][$p] != $excludeMIME &&
                          $pid != $this->structure[$mid]['pid'][$p]
                       	) || (
                          $action == 'add' &&
                          $this->structure[$mid]['ftype'][$p] == $excludeMIME &&
                          isset($this->structure[$mid]['fname'][$p]) &&
                          $pid != $this->structure[$mid]['pid'][$p]
                       	) || (
                          $action == 'add' && isset($this->structure[$mid]['fallback'][0])
                       )) {
                        $this->_addPart($in, $mid, $p, 'in');
                    } else if ($action == 'get' && $this->structure[$mid]['ftype'][$p] == $MIME && !isset($this->structure[$mid]['fname'][$p])) {
                        return $this->structure[$mid]['pid'][$p];
                    }
                } else if ($action == 'add' && $this->structure[$mid]['disposition'][$p] == 'attachment') {
                    $this->_addPart($a, $mid, $p, 'at');
                }
            }
        }

        return false;
    }

    /**
    * Determines whether a message contains a multipart/(insert subtype here) part.
    * Only called on by $this->_scanMultipart
    *
    * @return   BOOL
    * @access   private
    * @see      _scanMultipart
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/_isMultipart
    */
    function _isMultipart($mid, $subtype)
    {
        $ret = $this->extractMIME($mid, array('multipart/'.$subtype));
        return (!empty($ret) && is_array($ret) && count($ret) >= 1)? true : false;
    }

    /**
    * Looks to see if this part has any inline parts associated with it.
    * It looks up the message tree for parts with CID entries and
    * indexes those entries, whereas an algorithm may be ran to replace
    * inline CIDs with a part viewer.
    *
    * @param   int      &$mid          message id
    * @param   string   &$pid          part id
    * @param   array    $secureMIME    array of acceptable CID MIME types.
    *
    * The $secureMIME argument allows you to limit the types of files allowed
    * in a multipart/related message, for instance, to prevent a browser from
    * automatically initiating download of a part that could contain potentially
    * malicious code.
    *
    * Suggested MIME types:
    * text/plain, text/html, text/css, image/jpeg, image/pjpeg, image/gif
    * image/png,  image/x-png, application/xml, application/xhtml+xml,
    * text/xml
    *
    * MIME types are not limited by default.
    *
    * @return  array|false
    *    On success returns an array of parts associated with the current message,
    *    including the cid of the part, the part id and the MIME type.
    *
    * @access  public
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/getRelatedParts
    */
    function getRelatedParts(&$mid, &$pid, $secureMIME = array())
    {
        // Check to see if this part has already been parsed
        $this->_checkIfParsed($mid);

        // Message has a PID of 1.1.2
        // Cid parts are located at the prior level of nesting at 1.x
        // From the supplied PID, go back one level of nesting.
        // Compare the first number of the supplied PID against the current PID.
        // Look for a cid entry in the structure array.
        // Index the PID and CID of the part.
        //
        // Supplied pid must correspond to a text/html part.
        if (!empty($secureMIME) && is_array($secureMIME)) {
            $this->error->push(
            	Mail_IMAPv2_ERROR_ARGUMENT_REQUIRES_ARRAY,
            	'error',
            	array(
            		'arg' => '$secureMIME',
            		'actual_value' => $secureMIME
            	)
            );
            return false;
        }

        $related = array();

        if (isset($this->structure[$mid]['pid']) && is_array($this->structure[$mid]['pid'])) {
            if (strlen($pid) > 1) {
                $nesting = count(explode('.', $pid));
                $compare = substr($pid, 0, -4);
                foreach ($this->structure[$mid]['pid'] as $i => $rpid) {
                    // This level of nesting is one above the message part
                    // The beginning of the pid string of the related part matches that of the
                    // beginning of the pid supplied
                    if (count(explode('.', $rpid)) == ($nesting - 1) && substr($rpid, 0, -2) == $compare) {
                        $this->_getCIDs($mid, $i, $secureMIME, $related);
                    }
                }
            } else if (strlen($pid) == 1) {
                // If the pid is in the first level of nesting, odds are the related parts are in the
                // sub level of nesting.
                foreach ($this->structure[$mid]['pid'] as $i => $rpid) {
                    // The part is one level under and the first number matches that
                    // of its parent part.
                    if (count(explode('.', $rpid)) == 2 && substr($rpid, 0, 1) == $pid) {
                        $this->_getCIDs($mid, $i, $secureMIME, $related);
                    }
                }
            }
        } else {
            $this->error->push(
            	Mail_IMAPv2_ERROR,
            	'error',
            	null,
            	'Message structure does not exist.'
            );
        }
        return (count($related) >= 1)? $related : false;
    }

    /**
    * Helper function for getRelatedParts
    *
    * @return void
    * @access private
    * @see    getRelatedParts
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/_getCIDs
    */
    function _getCIDs(&$mid, &$i, &$secureMIME, &$related)
    {
        if ((isset($this->structure[$mid]['cid'][$i])) && (empty($secureMIME) || is_array($secureMIME) && in_array($this->structure[$mid]['ftype'][$i], $secureMIME))) {
            $related['cid'][] = $this->structure[$mid]['cid'][$i];
            $related['pid'][] = $this->structure[$mid]['pid'][$i];
            $related['ftype'][] = $this->structure[$mid]['ftype'][$i];
        }
    }

    /**
    * Destroys variables set by {@link getParts} and _declareParts.
    *
    * @param    integer  &$mid   message id
    * @return   void
    * @access   public
    * @see      getParts
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/unsetParts
    */
    function unsetParts(&$mid)
    {
        unset($this->msg[$mid]);
        unset($this->structure[$mid]);
        return;
    }

    /**
    * Adds information to the member variable inline part 'in' and attachment 'at' arrays.
    *
    * @param    int     &$n   offset part counter
    * @param    int     &$mid  message id
    * @param    int     &$i    offset structure reference counter
    * @return   void
    * @access   private
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/_addPart
    */
    function _addPart(&$n, &$mid, &$i, $part)
    {
        foreach ($this->fields as $field) {
            if (isset($this->structure[$mid][$field][$i]) && !empty($this->structure[$mid][$field][$i])) {
                $this->msg[$mid][$part][$field][$n] = $this->structure[$mid][$field][$i];
            }
        }
        $n++;
        return;
    }

    /**
    * Returns entire unparsed message body.  See {@link imap_body} for options.
    *
    * @param    int     &$mid      message id
    * @return   string|null
    * @tutorial http://www.smilingsouls.net/index.php?content=Mail_IMAPv2/getRawMessage
    * @access   public
    * @see      imap_body
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/getRawMessage
    */
    function getRawMessage(&$mid)
    {
        $opt = (isset($this->option['body']))? $this->option['body'] : null;
        return imap_body($this->mailbox, $mid, $opt);
    }

    /**
    * Searches parts array set in $this->_declareParts() for a displayable message.
    * If the part id passed is message/rfc822 looks in subparts for a displayable body.
    * Attempts to return a text/html inline message part by default. And will
    * automatically attempt to find a text/plain part if a text/html part could
    * not be found.
    *
    * Returns an array containing three associative indices; 'ftype', 'fname' and
    * 'message'.  'ftype' contains the MIME type of the message, 'fname', the original
    * file name, if any, empty string otherwise.  And 'message', which contains the
    * message body itself which is returned decoded from base64 or quoted-printable if
    * either of those encoding types are specified, returns untouched otherwise.
    * Returns false on failure.
    *
    * @param    int     &$mid                    message id
    * @param    string  $pid                     part id
    * @param    int     $action
    *      (optional) options for body return.  Set to one of the following:
    *      Mail_IMAPv2_BODY (default), if part is message/rfc822 searches subparts for a
    *      displayable body and returns the body decoded as part of an array.
    *      Mail_IMAPv2_LITERAL, return the message for the specified $pid without searching
    *      subparts or decoding the message (may return unparsed message) body is returned
    *      undecoded as a string.
    *      Mail_IMAPv2_LITERAL_DECODE, same as Mail_IMAPv2_LITERAL, except message decoding is
    *      attempted from base64 or quoted-printable encoding, returns undecoded string
    *      if decoding failed.
    *
    * @param    string  $getPart
    *      (optional) one of text/plain or text/html, allows the specification of the default
    *      part to return from multipart messages, text/html by default.
    *
    * @param    int     $attempt
    *      (optional) used internally by getBody to track attempts at finding the
    *      right part to display for the body of the message.
    *
    * @return   array|string|false
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/getBody
    * @access   public
    * @see      imap_fetchbody
    * @see      $this->getParts
    * @since    PHP 4.2.0
    */
    function getBody(&$mid, $pid = '1', $action = 0, $get_mime = 'text/html', $attempt = 1)
    {
        $options = (isset($this->option['fetchbody']))? $this->option['fetchbody'] : null;

        if ($action == Mail_IMAPv2_LITERAL) {
            return @imap_fetchbody($this->mailbox, $mid, $pid, $options);
        }

        $this->_checkIfParsed($mid, true, $get_mime);

        if (false !== ($i = array_search((string) $pid, $this->structure[$mid]['pid']))) {
            if ($action == Mail_IMAPv2_LITERAL_DECODE) {
                $msg_body = @imap_fetchbody($this->mailbox, $mid, $pid, $options);
                return $this->_decodeMessage($msg_body, $this->structure[$mid]['encoding'][$i]);
            }

            // If this is an attachment, and the part is message/rfc822 update the pid to the subpart
            // If this is an attachment, and the part is multipart/alternative update the pid to the subpart
            if (
            	$this->structure[$mid]['ftype'][$i] == 'message/rfc822' ||
            	$this->structure[$mid]['ftype'][$i] == 'multipart/related' ||
            	$this->structure[$mid]['ftype'][$i] == 'multipart/alternative'
           	) {
                $new_pid = 
                	($this->structure[$mid]['ftype'][$i] == 'message/rfc822' || $this->structure[$mid]['ftype'][$i] == 'multipart/related') ? 
                		$this->_scanMultipart($mid, $pid, $i, $get_mime, 'get', 'all', 1)
                	:
                		$this->_scanMultipart($mid, $pid, $i, $get_mime, 'get', 'multipart', 1);

                // if a new pid for text/html couldn't be found, try again, this time look for text/plain
                switch(true) {
                    case (!empty($new_pid)):
                    {
                    	$pid = $new_pid;
                    	break;
                    }
                    case (empty($new_pid) && $get_mime == 'text/html'):
                    {
                    	return ($attempt == 1)? $this->getBody($mid, $pid, $action, 'text/plain', 2) : false;
                    }
                    case (empty($new_pid) && $get_mime == 'text/plain'):
                    {
                    	return ($attempt == 1)? $this->getBody($mid, $pid, $action, 'text/html', 2) : false;
                    }
                }
            }

            // Update the key for the new pid
            if (!empty($new_pid)) {
                if (false === ($i = array_search((string) $pid, $this->structure[$mid]['pid']))) {
                    // Something's afoot!
                    $this->error->push(
                        Mail_IMAPv2_ERROR,
                        'error',
                        array(
                            'mid' => $mid,
                            'pid' => $pid
                        ),
                        'Unable to find a suitable replacement part ID. Message: may be poorly formed, corrupted, or not supported by the Mail_IMAPv2 parser.'
                    );
                    return false;
                }
            }

            $msg_body = imap_fetchbody($this->mailbox, $mid, $pid, $options);

            if ($msg_body == null) {
                $this->error->push(
                    Mail_IMAPv2_ERROR,
                    'error',
                    array(
                        'mid' => $mid,
                        'pid' => $pid
                    ),
                    'Message body is null.'
                );
                return false;
            }

            // Decode message.
            // Because the body returned may not correspond with the original PID, return
            // an array which also contains the MIME type and original file name, if any.
            $body['message'] = $this->_decodeMessage(
                $msg_body,
                $this->structure[$mid]['encoding'][$i],
                $this->structure[$mid]['charset'][$i]
            );
            $body['ftype']   = $this->structure[$mid]['ftype'][$i];
            $body['fname']   = (isset($this->structure[$mid]['fname'][$i]))? $this->structure[$mid]['fname'][$i] : '';
            $body['charset'] = $this->structure[$mid]['charset'][$i];

            return $body;
        }
        else
        {
            $this->error->push(
                Mail_IMAPv2_ERROR_INVALID_PID,
                'error',
                array(
                    'pid' => $pid
                )
            );
            return false;
        }

        return false;
    }

    /**
    * Decode a string from quoted-printable or base64 encoding.  If
    * neither of those encoding types are specified, returns string
    * untouched.
    *
    * @param    string  &$body           string to decode
    * @param    string  &$encoding       encoding to decode from.
    * @return   string
    * @access   private
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/_decodeMessage
    */
    function _decodeMessage(&$body, &$encoding, &$charset)
    {
        switch ($encoding) {
            case 'quoted-printable':
                return ($charset == 'utf-8')? utf8_decode(imap_utf8(imap_qprint($body))) : imap_qprint($body);
            case 'base64':            return imap_base64($body);
            default:                  return $body;
        }
    }

    /**
    * Searches structure defined in $this->_declareParts for the top-level default message.
    * Attempts to find a text/html default part, if no text/html part is found,
    * automatically attempts to find a text/plain part. Returns the part id for the default
    * top level message part on success. Returns false on failure.
    *
    * @param    int     &$mid           message id
    * @param    string  $getPart
    *     (optional) default MIME type to look for, one of text/html or text/plain
    *     text/html by default.
    * @param    int     $attempt
    *     (optional) Used internally by _getDefaultPid to track the method's attempt
    *     at retrieving the correct default part to display.
    *
    * @return   string
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/_getDefaultPid
    * @access   private
    */
    function _getDefaultPid(&$mid, $get_mime = 'text/html', $attempt = 1)
    {
        // Check to see if this part has already been parsed
        $this->_checkIfParsed($mid, false);

        // Look for a text/html message part
        // If no text/html message part was found look for a text/plain message part
        $part = 
        	($get_mime == 'text/html') ?
        		array('text/html', 'text/plain')
        	:
        		array('text/plain', 'text/html');

        foreach ($part as $mime) {
            if (0 !== count($msg_part = @array_keys($this->structure[$mid]['ftype'], $mime))) {
                foreach ($msg_part as $i) {
                    if ($this->structure[$mid]['disposition'][$i] == 'inline' && !stristr($this->structure[$mid]['pid'][$i], '.')) {
                        $this->msg[$mid]['pid'] = $this->structure[$mid]['pid'][$i];
                        return $this->structure[$mid]['pid'][$i];
                    }
                }
            }
        }

        // If no text/plain or text/html part was found
        // Look for a multipart/alternative part
        $mp_nesting = 1;
        $pid_len    = 1;

        if (is_array($this->structure[$mid]['pid'])) {
	        foreach ($this->structure[$mid]['pid'] as $p => $id) {
	            $nesting = count(explode('.', $this->structure[$mid]['pid'][$p]));
	
	            if (!isset($mpid)) {
	                if ($nesting == 1 && isset($this->structure[$mid]['ftype'][$p]) && ($this->structure[$mid]['ftype'][$p] == 'multipart/related')) {
	                    $mp_nesting = 2;
	                    $pid_len    = 3;
	                    continue;
	                }
	                if (
	                	$nesting == $mp_nesting && 
	                	isset($this->structure[$mid]['ftype'][$p]) && 
	                	($this->structure[$mid]['ftype'][$p] == 'multipart/alternative'  || $this->structure[$mid]['ftype'][$p]  == 'multipart/mixed')
	               	) {
	                    $mpid = $this->structure[$mid]['pid'][$p];
	                    continue;
	                }
	            }
	
	            if (
	            	isset($mpid) && $nesting == ($mp_nesting + 1) && 
	            	$this->structure[$mid]['ftype'][$p] == $get_mime && 
	            	$mpid == substr($this->structure[$mid]['pid'][$p], 0, $pid_len)
	            ) {
	                $this->msg[$mid]['pid'] = $this->structure[$mid]['pid'][$p];
	                return $this->structure[$mid]['pid'][$p];
	            }
	        }
        } else {
        	$this->error->push(Mail_IMAPv2_ERROR, 'error', null, 'Message structure does not exist.');
        }

        // if a text/html part was not found, call on the function again
        // and look for text/plain
        // if the application was unable to find a text/plain part
        switch ($get_mime) {
            case 'text/html':
            {
                $rtn = ($attempt == 1)?
                    $this->_getDefaultPid($mid, 'text/plain', 2)
                :
                    false;

                break;
            }
            case 'text/plain':
            {
                $rtn = ($attempt == 1)?
                    $this->_getDefaultPid($mid, 'text/html', 2)
                :
                    false;

                break;
            }
            default:
            {
                $rtn = false;
            }
        }

        if ($rtn == false && $attempt == 2) {
            if (isset($this->structure[$mid]['ftype'][0])) {
                $this->structure[$mid]['fallback'][0] = true;
            } else {
                $this->error->push(Mail_IMAPv2_ERROR, 'error', null, 'Message contains no MIME types.');
            }
        }

        $this->msg[$mid]['pid'] = ($rtn == false)? 1 : $rtn;

        return $this->msg[$mid]['pid'];
    }

    /**
    * Searches all message parts for the specified MIME type.  Use {@link getBody}
    * with $action option Mail_IMAPv2_LITERAL_DECODE to view MIME type parts retrieved.
    * If you need to access the MIME type with filename use normal {@link getBody}
    * with no action specified.
    *
    * Returns an array of part ids on success.
    * Returns false if MIME couldn't be found, or on failure.
    *
    * @param    int           &$mid           message id
    * @param    string|array  $MIMEs          mime type to extract
    * @return   array|false
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/extractMIME
    * @access   public
    */
    function extractMIME(&$mid, $MIMEs)
    {
        $this->_checkIfParsed($mid);

        if (is_array($this->structure[$mid]['ftype'])) {
            if (is_array($MIMEs)) {
                foreach ($MIMEs as $MIME) {
                    if (0 !== count($keys = array_keys($this->structure[$mid]['ftype'], $MIME))) {
                        foreach ($keys as $key) {
                            $rtn[] = $this->structure[$mid]['pid'][$key];
                        }
                    }
                }
            } else {
                $this->error->push(
                    Mail_IMAPv2_ERROR_ARGUMENT_REQUIRES_ARRAY,
                    'error',
                    array(
                        'arg' => '$MIMEs',
                        'actual_value' => $MIMEs
                    )
                );
            }
        } else {
            $this->error->push(
                Mail_IMAPv2_ERROR,
                'error',
                null,
                'Member variable $this->structure[\'ftype\'] is not an array'
            );
        }

        return (isset($rtn))? $rtn : false;
    }

    /**
    * Set member variable {@link $rawHeaders} to contain Raw Header information
    * for a part.  Returns default header part id on success, returns false on failure.
    *
    * @param    int     &$mid          message_id
    * @param    string  $pid           (optional) part id to retrieve headers for
    * @param    bool    $rtn
    *   Decides what to return. One of true|false|return_pid
    *   If true return the raw headers (returns the headers by default)
    *
    * @return   string|false
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/getRawHeaders
    * @access   public
    * @see      imap_fetchbody
    * @see      getHeaders
    */
    function getRawHeaders(&$mid, $pid = '0', $rtn = true, $pid_check = false)
    {
        $this->_checkIfParsed($mid);

        if ($pid == $this->msg[$mid]['pid']) {
            $pid = (string) '0';
        }

        if ($pid !== '0') {
            if (false === ($pid = $this->_defaultHeaderPid($mid, $pid))) {
                $this->error->push(Mail_IMAPv2_ERROR_INVALID_PID, 'error', array('pid' => $pid));
                return false;
            }
        }

        if ($pid === '0' && $pid_check) {
            return true;
        } else if ($pid_check) {
            $rtn = true;
        }

        if ($pid === '0') {
            $opt = (isset($this->option['fetchheader']))? $this->option['fetchheader'] : null;
            $raw_headers = @imap_fetchheader($this->mailbox, $mid, $opt);
        } else {
            $opt = (isset($this->option['fetchbody']))? $this->option['fetchbody'] : null;
            $raw_headers = @imap_fetchbody($this->mailbox, $mid, $pid, $opt);
        }

        if ($rtn) {
            return $raw_headers;
        } else {
            $this->header[$mid]['raw'] = $raw_headers;
            return true;
        }
    }

    /**
    * Set member variable containing header information.  Creates an array containing
    * associative indices referring to various header information.  Use {@link var_dump}
    * or {@link print_r} on the {@link $header} member variable to view information
    * gathered by this function.
    *
    * If $ret is true, returns array containing header information on success and false
    * on failure.
    *
    * If $ret is false, adds the header information to the $header member variable
    * and returns BOOL.
    *
    * @param    int     &$mid           message id
    * @param    string  &$pid           (optional) part id to retrieve headers for.
    * @param    bool    $rtn
    *   (optional) If true return the headers, if false, assign to $header member variable.
    *
    * @param    array   $args
    *   (optional) Associative array containing extra arguments.
    *
    *       $args['from_length'] int
    *           From field length for imap_headerinfo.
    *
    *       $args['subject_length'] int
    *           Subject field length for imap_headerinfo
    *
    *       $args['default_host'] string
    *           Default host for imap_headerinfo & imap_rfc822_parse_headers
    *
    * @return   Array|BOOL
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/getHeaders
    * @access   public
    * @see      getParts
    * @see      imap_fetchheader
    * @see      imap_fetchbody
    * @see      imap_headerinfo
    * @see      imap_rfc822_parse_headers
    */
    function getHeaders(&$mid, $pid = '0', $rtn = false, $args = array())
    {
        $this->_checkIfParsed($mid);

        if ($pid == $this->msg[$mid]['pid']) {
            $pid = '0';
        }

        if ($pid !== '0') {
            if (false === ($raw_headers = $this->getRawHeaders($mid, $pid, true, true))) {
                return false;
            }

            if ($raw_headers === true) {
                $pid = '0';
            }
        }

        if (!isset($args['from_length'])) {
            $args['from_length'] = 1024;
        }

        if (!isset($args['subject_length'])) {
            $args['subject_length'] = 1024;
        }

        if (!isset($args['default_host'])) {
            $args['default_host'] = null;
        }

        // Parse the headers
        $header_info = 
        	($pid === '0')?
                imap_headerinfo($this->mailbox, $mid, $args['from_length'], $args['subject_length'], $args['default_host'])
            :
                imap_rfc822_parse_headers($raw_headers, $args['default_host']);

        // Since individual member variable creation might create extra overhead,
        // and having individual variables referencing this data and the original
        // object would be too much as well, we'll just copy the object into an
        // associative array, preform clean-up on those elements that require it,
        // and destroy the original object after copying.

        if (!is_object($header_info)) {
            $this->error->push(
                Mail_IMAPv2_ERROR_INVALID_PID,
                'error',
                array(
                    'pid' => $pid
                )
            );
            return false;
        }

        $headers = get_object_vars($header_info);

        foreach ($headers as $key => $value) {
            if (!is_object($value) && !is_array($value)) {
                // Decode all the headers using utf8_decode(imap_utf8())
                $this->header[$mid][$key] = utf8_decode(imap_utf8($value));
            }
        }

        // copy udate or create it from date string.
        $this->header[$mid]['udate'] = (isset($header_info->udate) && !empty($header_info->udate))? 
            $header_info->udate
        :
            strtotime($header_info->Date);

        // clean up addresses
        $line = array(
        	'from',
        	'reply_to',
       		'sender',
        	'return_path',
        	'to',
        	'cc',
        	'bcc'
        );

        for ($i = 0; $i < count($line); $i++) {
            if (isset($header_info->$line[$i])) {
                $this->_parseHeaderLine($mid, $header_info->$line[$i], $line[$i]);
            }
        }

        // All possible information has been copied, destroy original object
        unset($header_info);

        return ($rtn)? $this->header[$mid] : false;
    }

    /**
    * Parse header information from the given line and add it to the {@link $header}
    * array.  This function is only used by {@link getRawHeaders}.
    *
    * @param     string   &$line
    * @param     string   $name
    * @return    array
    * @access    private
    * @tutorial  http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/_parseHeaderLine
    */
    function _parseHeaderLine(&$mid, &$line, $name) 
    {
        if (isset($line) && count($line) >= 1) {
            $i = 0;
            foreach ($line as $object) {
                if (isset($object->adl)) {
                    $this->header[$mid][$name.'_adl'][$i] = $object->adl;
                }
                if (isset($object->mailbox)) {
                    $this->header[$mid][$name.'_mailbox'][$i] = $object->mailbox;
                }
                if (isset($object->personal)) {
                    $this->header[$mid][$name.'_personal'][$i] = $object->personal;
                }
                if (isset($object->host)) {
                    $this->header[$mid][$name.'_host'][$i] = $object->host;
                }
                if (isset($object->mailbox) && isset($object->host)) {
                    $this->header[$mid][$name][$i] = $object->mailbox.'@'.$object->host;
                }
                $i++;
            }
            // Return the full lines "toaddress", "fromaddress", "ccaddress"... etc
            if (isset(${$name.'address'})) {
                $this->header[$mid][$name.'address'][$i] = ${$name.'address'};
            }
        }
    }

    /**
    * Finds and returns a default part id for headers and matches any sub message part to
    * the appropriate headers.  Returns false on failure and may return a value that
    * evaluates to false, use the '===' operator for testing this function's return value.
    *
    * @param    int     &$mid            message id
    * @param    string  $pid             part id
    * @return   string|false
    * @access   private
    * @see      getHeaders
    * @see      getRawHeaders
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/_defaultHeaderPid
    */
    function _defaultHeaderPid(&$mid, $pid)
    {
        // pid is modified in this function, so don't pass by reference (will create a logic error)
        $this->_checkIfParsed($mid);

        // retrieve key for this part, so that the information may be accessed
        if (false !== ($i = array_search((string) $pid, $this->structure[$mid]['pid']))) {
            // If this part is message/rfc822 display headers for this part
            if ($this->structure[$mid]['ftype'][$i] == 'message/rfc822') {
                $rtn = (string) $pid.'.0';
            } else if ($pid == $this->msg[$mid]['pid']) {
                $rtn = (string) '0';
            } else {
                $pid_len = strlen($pid);
                $this_nesting = count(explode('.', $pid));

                // Deeper searching may be required, go back to this part's parent.
                if (!stristr($pid, '.') || ($this_nesting - 1) == 1) {
                    $rtn = (string) '0';
                } else if ($this_nesting > 2) {
                    // Look at previous parts until a message/rfc822 part is found.
                    for ($pos = $this_nesting - 1; $pos > 0; $pos -= 1) {
                        foreach ($this->structure[$mid]['pid'] as $p => $aid) {
                            $nesting = count(explode('.', $this->structure[$mid]['pid'][$p]));

                            if (
                            	$nesting == $pos && 
                            	($this->structure[$mid]['ftype'][$p] == 'message/rfc822' || $this->structure[$mid]['ftype'][$p] == 'multipart/related')
                           	) {
                                // Break iteration and return!
                                return (string) $this->structure[$mid]['pid'][$p].'.0';
                            }
                        }
                    }

                    $rtn = ($pid_len == 3)? (string) '0' : false;
                } else {
                    $rtn = false;
                }
            }
            return $rtn;
        } else {
            // Something's afoot!
            $this->error->push(
                Mail_IMAPv2_ERROR_INVALID_PID,
                'error',
                array(
                    'pid' => $pid
                )
            );
            return false;
        }
    }

    /**
    * Destroys variables set by {@link getHeaders}.
    *
    * @param    int     &$mid            message id
    * @return   void
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/unsetHeaders
    * @access   public
    * @see      getHeaders
    */
    function unsetHeaders(&$mid)
    {
        unset($this->header[$mid]);
        return;
    }

    /**
    * Converts an integer containing the number of bytes in a file to one of Bytes, Kilobytes,
    * Megabytes, or Gigabytes, appending the unit of measurement.
    *
    * This method may be called statically.
    *
    * @param    int     $bytes
    * @return   string
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/convertBytes
    * @access   public
    * @static
    */
    function convertBytes($bytes)
    {
        switch (true) {
            case ($bytes < pow(2,10)):
            {
                return $bytes.' Bytes';
            }
            case ($bytes >= pow(2,10) && $bytes < pow(2,20)):
            {
                return round($bytes / pow(2,10), 0).' KB';
            }
            case ($bytes >= pow(2,20) && $bytes < pow(2,30)):
            {
                return round($bytes / pow(2,20), 1).' MB';
            }
            case ($bytes > pow(2,30)):
            {
                return round($bytes / pow(2,30), 2).' GB';
            }
        }
    }

    /**
    * Wrapper function for {@link imap_delete}.  Sets the marked for deletion flag.  Note: POP3
    * mailboxes do not remember flag settings between connections, for POP3 mailboxes
    * this function should be used in addtion to {@link expunge}.
    *
    * @param    int     &$mid   message id
    * @return   BOOL
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/delete
    * @access   public
    * @see      imap_delete
    * @see      expunge
    */
    function delete(&$mid, $separator = "<br />\n")
    {
        if (!is_array($mid)) {
            if (!@imap_delete($this->mailbox, $mid)) {
                $this->error->push(
                    Mail_IMAPv2_ERROR,
                    'error',
                    array(
                        'mid' => $mid
                    ),
                    'Unable to mark message for deletion.'
                );
                $rtn = false;
            } else {
                $rtn = true;
            }
        } else {
            foreach ($mid as $id) {
                if (!@imap_delete($this->mailbox, $id)) {
                    $this->error->push(
                        Mail_IMAPv2_ERROR,
                        'error',
                        array(
                            'mid' => $id
                        ),
                        'Unable to mark message for deletion.'
                    );
                    $rtn = false;
                }
            }
            $rtn = true;
        }

        return $rtn;
    }

    /**
    * Wrapper function for {@link imap_expunge}.  Expunges messages marked for deletion.
    *
    * @return   BOOL
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/expunge
    * @access   public
    * @see      imap_expunge
    * @see      delete
    */
    function expunge()
    {
        if (imap_expunge($this->mailbox)) {
            return true;
        } else {
            $this->error->push(Mail_IMAPv2_ERROR, 'error', null, 'Unable to expunge mailbox.');
            return false;
        }
    }

    /**
    * Wrapper function for {@link imap_errors}.  Implodes the array returned by imap_errors,
    * (if any) and returns the error text.
    *
    * @param    bool      $handler
    *   How to handle the imap error stack, true by default. If true adds the errors
    *   to the PEAR_ErrorStack object. If false, returns the imap error stack.
    *
    * @param    string    $seperator
    *   (optional) Characters to seperate each error message. "<br />\n" by default.
    *
    * @return   bool|string
    * @access   public
    * @see      imap_errors
    * @see      alerts
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/errors
    */
    function errors($handler = true, $seperator = "<br />\n")
    {
        $errors = imap_errors();

        if (empty($errors)) {
            return false;
        }

        if ($handler) {
            foreach ($errors as $error) {
                $this->error->push(
                    Mail_IMAPv2_ERROR,
                    'error',
                    null,
                    $error
                );
            }
            return true;
        }
        return implode($seperator, $errors);
    }

    /**
    * Wrapper function for {@link imap_alerts}.  Implodes the array returned by imap_alerts,
    * (if any) and returns the text.
    *
    * @param    bool      $handler
    *   How to handle the imap error stack, true by default. If true adds the alerts
    *   to the PEAR_ErrorStack object. If false, returns the imap alert stack.
    *
    * @param    string    $seperator     Characters to seperate each alert message. '<br />\n' by default.
    * @return   bool|string
    * @access   public
    * @see      imap_alerts
    * @see      errors
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/alerts
    */
    function alerts($handler = true, $seperator = "<br />\n")
    {
        $alerts = imap_alerts();

        if (empty($alerts)) {
            return false;
        }

        if ($handler) {
            foreach ($alerts as $alert) {
                $this->error->push(
                    Mail_IMAPv2_ERROR,
                    'notice',
                    null,
                    $alert
                );
            }
            return true;
        }
        return implode($seperator, $alerts);
    }

    /**
    * Retreives information about the current mailbox's quota.  Rounds up quota sizes and
    * appends the unit of measurment.  Returns information in a multi-dimensional associative
    * array.
    *
    * @param    string   $folder    Folder to retrieve quota for.
    * @param    BOOL     $rtn
    *   (optional) true by default, if true return the quota if false merge quota
    *   information into the $mailboxInfo member variable.
    * @return   array|false
    * @access   public
    * @see      imap_get_quotaroot
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/getQuota
    */
    function getQuota($folder = null, $rtn = true)
    {
        if (empty($folder) && !isset($this->mailboxInfo['folder'])) {
            $folder = 'INBOX';
        } else if (empty($folder) && isset($this->mailboxInfo['folder'])) {
            $folder = $this->mailboxInfo['folder'];
        }

        $q = @imap_get_quotaroot($this->mailbox, $folder);

        // STORAGE Values are returned in KB
        // Convert back to bytes first
        // Then round these to the simpliest unit of measurement
        if (isset($q['STORAGE']['usage']) && isset($q['STORAGE']['limit'])) {
            $q['STORAGE']['usage'] = $this->convertBytes($q['STORAGE']['usage'] * 1024);
            $q['STORAGE']['limit'] = $this->convertBytes($q['STORAGE']['limit'] * 1024);
        }

        if (isset($q['MESSAGE']['usage']) && isset($q['MESSAGE']['limit'])) {
            $q['MESSAGE']['usage'] = $this->convertBytes($q['MESSAGE']['usage']);
            $q['MESSAGE']['limit'] = $this->convertBytes($q['MESSAGE']['limit']);
        }

        if (empty($q['STORAGE']['usage']) && empty($q['STORAGE']['limit'])) {
            $this->error->push(
                Mail_IMAPv2_ERROR,
                'error',
                null,
                'Quota not available for this server.'
            );
            return false;
        } else if ($rtn) {
            return $q;
        } else {
            $this->mailboxInfo = array_merge($this->mailboxInfo, $q);
            return true;
        }
    }

    /**
    * Wrapper function for {@link imap_setflag_full}.  Sets various message flags.
    * Accepts an array of message ids and an array of flags to be set.
    *
    * The flags which you can set are "\\Seen", "\\Answered", "\\Flagged",
    * "\\Deleted", and "\\Draft" (as defined by RFC2060).
    *
    * Warning: POP3 mailboxes do not remember flag settings from connection to connection.
    *
    * @param    array  $mids        Array of message ids to set flags on.
    * @param    array  $flags       Array of flags to set on messages.
    * @param    int    $action      Flag operation toggle one of set|clear
    * @param    int    $options
    *   (optional) sets the forth argument of {@link imap_setflag_full} or {@imap_clearflag_full}.
    *
    * @return   BOOL
    * @throws   Message IDs and Flags are to be supplied as arrays.  Remedy: place message ids
    *           and flags in arrays.
    * @access   public
    * @see      imap_setflag_full
    * @see      imap_clearflag_full
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/setFlags
    */
    function setFlags($mids, $flags, $action = 'set')
    {
        if (!is_array($mids)) {
            $this->error->push(
                Mail_IMAPv2_ERROR_ARGUMENT_REQUIRES_ARRAY,
                'error',
                array(
                    'arg' => '$mids'
                )
            );
            return false;
        }

        if (!is_array($flags)) {
            $this->error->push(
                Mail_IMAPv2_ERROR_ARGUMENT_REQUIRES_ARRAY,
                'error',
                array(
                    'arg' => '$flags'
                )
            );
            return false;
        }

        switch ($action) {
            case 'set':
            {
                $func = 'imap_setflag_full';
                break;
            }
            case 'clear':
            {
                $func = 'imap_clearflag_full';
                break;
            }
            default:
            {
                $this->error->push(
                    Mail_IMAPv2_ERROR_INVALID_ACTION,
                    'error',
                    array(
                        'action' => $action,
                        'arg' => '$action'
                    )
                );
                return false;
            }
        }
        
        $opt = 
        	(isset($this->option[$action.'flag_full']))?
            	$this->option[$action.'flag_full']
        	:
            	null;

        return @$func($this->mailbox, implode(',', $mids), implode(' ', $flags), $opt);
    }

    /**
    * Wrapper method for imap_list.  Calling on this function will return a list of mailboxes.
    * This method receives the host argument automatically via $this->connect in the
    * $this->mailboxInfo['host'] variable if a connection URI is used.
    *
    * @param    string  (optional) host name.
    * @return   array|false   list of mailboxes on the current server.
    * @access   public
    * @see      imap_list
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP/getMailboxes
    */
    function getMailboxes($host = null, $pattern = '*', $rtn = true)
    {
        if (empty($host) && !isset($this->mailboxInfo['host'])) {
            $this->error->push(
                Mail_IMAPv2_ERROR,
                'error',
                null,
                'Supplied host is not valid!'
            );
            return false;
        } else if (empty($host) && isset($this->mailboxInfo['host'])) {
            $host = $this->mailboxInfo['host'];
        }

        if ($list = @imap_list($this->mailbox, $host, $pattern)) {
            if (is_array($list)) {
                foreach ($list as $key => $val) {
                   $mb[$key] = str_replace($host, '', imap_utf7_decode($val));
                }
            }
        } else {
            $this->error->push(Mail_IMAPv2_ERROR, 'error', null, 'Cannot fetch mailbox names.');
            return false;
        }

        if ($rtn) {
           return $mb;
        } else {
            $this->mailboxInfo = array_merge($this->mailboxInfo, $mb);
        }
    }
}
?>