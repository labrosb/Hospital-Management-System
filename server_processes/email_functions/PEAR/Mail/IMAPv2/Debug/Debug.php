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

/**
* This class provides an extension to Mail_IMAPv2 that adds debugging 
* utilities for the base IMAP.php class. The debugging functionality 
* provided by this class is currently accessed by supplying various 
* $_GET method arguments.
*
* @author       Richard York <rich_y@php.net>
* @category     Mail
* @package      Mail_IMAPv2
* @license      BSD
* @version      0.1.0 Beta
* @copyright    (c) Copyright 2004-2005, Richard York, All Rights Reserved.
* @since        PHP 4.2.0
* @since        C-Client 2001
* @tutorial     http://www.smilingsouls.net/Mail_IMAP
*/
class Mail_IMAPv2_Debug extends Mail_IMAPv2 {

	function Mail_IMAPv2_Debug($connection = NULL, $get_info = TRUE)
	{
    	$this->Mail_IMAPv2($connection, $get_info);
 
		if (isset($_GET['dump_mid'])) {
	        $this->debug($_GET['dump_mid']);
		} else {
			$this->error->push(Mail_IMAPv2_ERROR, 'error', array('method' => 'Mail_IMAPv2_Debug', 'error_string' => 'No mid was specified for debugging.'));
		}
	}

    /**
    * Dumps various information about a message for debugging. Specify $_GET 
    * variables to view information.
    *
    * Calling on the debugger exits script execution after debugging operations
    * have been completed.
    *
    * @param    int  $mid         $mid to debug
    * @return   void
    * @access   public
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP_Debug/debug
    */
    function debug($mid = 0)
    {
        $this->_declareParts($mid);

        if (isset($_GET['dump_mb_info'])) {
            $this->dump($this->mailboxInfo);
        }
        if (isset($_GET['dump_cid'])) {
            $this->dump($this->msg[$mid]['in']['cid']);
        }
        if (isset($_GET['dump_related'])) {
            $this->dump($this->getRelatedParts($mid, $_GET['dump_related']));
        }
        if (isset($_GET['dump_msg']) && isset($_GET['dump_pid'])) {
			$this->getParts($mid, $_GET['dump_pid']);
	        $this->dump($this->msg);
        }
        if (isset($_GET['dump_pid'])) {
            $this->dump($this->structure[$mid]['pid']);
        }
        if (isset($_GET['dump_ftype'])) {
            $this->dump($this->structure[$mid]['ftype']);
        }
        if (isset($_GET['dump_structure'])) {
            $this->dump($this->structure[$mid]['obj']);
        }
        if (isset($_GET['test_pid'])) {
            echo imap_fetchbody($this->mailbox, $mid, $_GET['test_pid'], NULL);
        }
        if (isset($_GET['dump_mb_list'])) {
            $this->dump($this->getMailboxes());
        }
        if (isset($_GET['dump_headers'])) {
	     	$this->dump($this->getHeaders($mid, $_GET['dump_headers'], TRUE));
        }
        if ($this->error->hasErrors()) {
            $this->dump($this->error->getErrors(TRUE));   
        }
    }

    /**
    * Calls on var_dump and outputs with HTML <pre> tags.
    *
    * @param    mixed  $thing         $thing to dump.
    * @return   void
    * @access   public
    * @tutorial http://www.smilingsouls.net/Mail_IMAP?content=Mail_IMAP_Debug/dump
    */
    function dump(&$thing)
    {
        echo "<pre style='display: block; font-family: monospace; white-space: pre;'>\n";
        
        ob_end_flush();
        ob_start();
        var_dump($thing);
        $output = ob_get_contents();
        ob_end_clean();
        echo htmlspecialchars($output);
        echo "</pre>\n";
    }
}
	
?>