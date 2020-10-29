<?php
/*************************************************************************************
 * teraterm.php
 * --------
 * Author: Boris Maisuradze (boris at logmett.com)
 * Copyright: (c) 2008 Boris Maisuradze (http://logmett.com)
 * Release Version: 1.0.8.11
 * Date Started: 2008/09/26
 *
 * Tera Term Macro language file for GeSHi.
 *
 *
 * This version of teraterm.php was created for Tera Term 4.62 and LogMeTT 2.9.4.
 * Newer versions of these application can contain additional Macro commands
 * and/or keywords that are not listed here. The latest release of teraterm.php
 * can be downloaded from Download section of LogMeTT.com
 *
 * CHANGES
 * -------
 * 2008/09/26 (1.0.0)
 *   -  First Release for Tera Term 4.60 and below.
 * 2009/03/22 (1.1.0)
 *   -  First Release for Tera Term 4.62 and below.
 * 2009/04/25 (1.1.1)
 *   -  Second Release for Tera Term 4.62 and below.
 * 2010/09/12 (1.1.2)
 *   -  Second Release for Tera Term 4.67, LogMeTT 2.97, TTLEditor 1.2.1 and below.
 *
 * TODO (updated 2010/09/12)
 * -------------------------
 * *
 *
 *************************************************************************************
 *
 *     This file is part of GeSHi.
 *
 *   GeSHi is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   GeSHi is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with GeSHi; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 ************************************************************************************/

$language_data = [
    'LANG_NAME' => 'Tera Term Macro',
    'COMMENT_SINGLE' => [1 => ';'],
    'COMMENT_MULTI' => [],
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS' => ["'", '"'],
    'ESCAPE_CHAR' => '',
    'KEYWORDS' => [
        /* Commands */
        1 => [
            'Beep',
            'BplusRecv',
            'BplusSend',
            'Break',
            'Call',
            'CallMenu',
            'ChangeDir',
            'ClearScreen',
            'Clipb2Var',
            'ClosesBox',
            'CloseTT',
            'Code2Str',
            'Connect',
            'CRC32',
            'CRC32File',
            'CygConnect',
            'DelPassword',
            'Disconnect',
            'DispStr',
            'Do',
            'Else',
            'ElseIf',
            'EnableKeyb',
            'End',
            'EndIf',
            'EndUntil',
            'EndWhile',
            'Exec',
            'ExecCmnd',
            'Exit',
            'FileClose',
            'FileConcat',
            'FileCopy',
            'FileCreate',
            'FileDelete',
            'FileMarkPtr',
            'FileNameBox',
            'FileOpen',
            'FileRead',
            'FileReadln',
            'FileRename',
            'FileSearch',
            'FileSeek',
            'FileSeekBack',
            'FileStat',
            'FileStrSeek',
            'FileStrSeek2',
            'FileTruncate',
            'FileWrite',
            'FileWriteLn',
            'FindClose',
            'FindFirst',
            'FindNext',
            'FlushRecv',
            'For',
            'GetDate',
            'GetDir',
            'GetEnv',
            'GetHostname',
            'GetPassword',
            'GetTime',
            'GetTitle',
            'GetTTDir',
            'Getver',
            'GoTo',
            'If',
            'IfDefined',
            'Include',
            'InputBox',
            'Int2Str',
            'KmtFinish',
            'KmtGet',
            'KmtRecv',
            'KmtSend',
            'LoadKeymap',
            'LogClose',
            'LogOpen',
            'LogPause',
            'LogStart',
            'LogWrite',
            'Loop',
            'MakePath',
            'MessageBox',
            'MPause',
            'Next',
            'PasswordBox',
            'Pause',
            'QuickVANRecv',
            'QuickVANSend',
            'Random',
            'RecvLn',
            'RestoreSetup',
            'Return',
            'RotateLeft',
            'RotateRight',
            'ScpRecv',
            'ScpSend',
            'Send',
            'SendBreak',
            'SendBroadcast',
            'SendFile',
            'SendKCode',
            'SendLn',
            'SendLnBroadcast',
            'SendMulticast',
            'SetBaud',
            'SetDate',
            'SetDebug',
            'SetDir',
            'SetDlgPos',
            'SetDTR',
            'SetEcho',
            'SetEnv',
            'SetExitCode',
            'SetMulticastName',
            'SetRTS',
            'SetSync',
            'SetTime',
            'SetTitle',
            'Show',
            'ShowTT',
            'SPrintF',
            'SPrintF2',
            'StatusBox',
            'Str2Code',
            'Str2Int',
            'StrCompare',
            'StrConcat',
            'StrCopy',
            'StrInsert',
            'StrJoin',
            'StrLen',
            'StrMatch',
            'StrRemove',
            'StrReplace',
            'StrScan',
            'StrSpecial',
            'StrSplit',
            'StrTrim',
            'TestLink',
            'Then',
            'ToLower',
            'ToUpper',
            'UnLink',
            'Until',
            'Var2Clipb',
            'Wait',
            'Wait4All',
            'WaitEvent',
            'WaitLn',
            'WaitN',
            'WaitRecv',
            'WaitRegEx',
            'While',
            'XmodemRecv',
            'XmodemSend',
            'YesNoBox',
            'YmodemRecv',
            'YmodemSend',
            'ZmodemRecv',
            'ZmodemSend'
            ],
        /* System Variables */
        2 => [
            'groupmatchstr1',
            'groupmatchstr2',
            'groupmatchstr3',
            'groupmatchstr4',
            'groupmatchstr5',
            'groupmatchstr6',
            'groupmatchstr7',
            'groupmatchstr8',
            'groupmatchstr9',
            'inputstr',
            'matchstr',
            'mtimeout',
            'param2',
            'param3',
            'param4',
            'param5',
            'param6',
            'param7',
            'param8',
            'param9',
            'result',
            'timeout'
            ],
        /* LogMeTT Key Words */
        3 => [
            '$[1]',
            '$[2]',
            '$[3]',
            '$[4]',
            '$[5]',
            '$[6]',
            '$[7]',
            '$[8]',
            '$[9]',
            '$branch$',
            '$computername$',
            '$connection$',
            '$email$',
            '$logdir$',
            '$logfilename$',
            '$lttfilename$',
            '$mobile$',
            '$name$',
            '$pager$',
            '$parent$',
            '$phone$',
            '$snippet$',
            '$ttdir$',
            '$user$',
            '$windir$',
        ],
        /* Keyword Symbols */
        4 => [
            'and',
            'not',
            'or',
            'xor'
            ]
        ],
    'SYMBOLS' => [
        '(', ')', '[', ']', '{', '}',
        '+', '-', '*', '/', '%',
        '!', '&', '|', '^',
        '<', '>', '=',
        '?', ':', ';',
        ],
    'CASE_SENSITIVE' => [
        GESHI_COMMENTS => false,
        1 => false,
        2 => false,
        3 => false,
        4 => false
        ],
    'STYLES' => [
        'KEYWORDS' => [
            1 => 'color: #000080; font-weight: bold!important;',
            2 => 'color: #808000; font-weight: bold;',  // System Variables
            3 => 'color: #ff0000; font-weight: bold;',  // LogMeTT Key Words
            4 => 'color: #ff00ff; font-weight: bold;'   // Keyword Symbols
            ],
        'COMMENTS' => [
            1 => 'color: #008000; font-style: italic;',
            ],
        'ESCAPE_CHAR' => [],
        'BRACKETS' => [
            0 => 'color: #ff00ff; font-weight: bold;'
        ],
        'STRINGS' => [
            0 => 'color: #800080;'
            ],
        'NUMBERS' => [
            0 => 'color: #008080;'
            ],
        'SCRIPT' => [
            ],
        'METHODS' => [
            ],
        'SYMBOLS' => [
            0 => 'color: #ff00ff; font-weight: bold;'
            ],
        'REGEXPS' => [
            0 => 'color: #0000ff; font-weight: bold;'
            ]
        ],
    'URLS' => [
        1 => '',
        2 => '',
        3 => '',
        4 => ''
        ],
    'OOLANG' => false,
    'OBJECT_SPLITTERS' => [],
    'REGEXPS' => [
        0 => [
            GESHI_SEARCH => '(\:[_a-zA-Z][_a-zA-Z0-9]+)',
            GESHI_REPLACE => '\\1',
            GESHI_MODIFIERS => '',
            GESHI_BEFORE => '',
            GESHI_AFTER => ''
            ]
        ],
    'STRICT_MODE_APPLIES' => GESHI_NEVER,
    'SCRIPT_DELIMITERS' => [],
    'HIGHLIGHT_STRICT_BLOCK' => [],
    'TAB_WIDTH' => 4
];
