<?php
/*************************************************************************************
 * vb.php
 * ------
 * Author: Roberto Rossi (rsoftware@altervista.org)
 * Copyright: (c) 2004 Roberto Rossi (http://rsoftware.altervista.org),
 *                     Nigel McNie (http://qbnz.com/highlighter)
 * Release Version: 1.0.8.11
 * Date Started: 2004/08/30
 *
 * Visual Basic language file for GeSHi.
 *
 * CHANGES
 * -------
 * 2008/08/27 (1.0.8.1)
 *  -  changed keyword list for better Visual Studio compliance
 * 2008/08/26 (1.0.8.1)
 *  -  Fixed multiline comments
 * 2004/11/27 (1.0.1)
 *  -  Added support for multiple object splitters
 * 2004/08/30 (1.0.0)
 *  -  First Release
 *
 * TODO (updated 2004/11/27)
 * -------------------------
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
    'LANG_NAME' => 'Visual Basic',
    'COMMENT_SINGLE' => [],
    'COMMENT_MULTI' => [],
    'COMMENT_REGEXP' => [
        // Comments (either single or multiline with _
        1 => '/\'.*(?<! _)\n/sU',
        ],
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS' => ['"'],
    'ESCAPE_CHAR' => '',
    'KEYWORDS' => [
        1 => [
            'Binary', 'Boolean', 'Byte', 'Currency', 'Date', 'Decimal', 'Double',
            'String', 'Enum', 'Integer', 'Long', 'Object', 'Single', 'Variant'
            ],
        2 => [
            'CreateObject', 'GetObject', 'New', 'Option', 'Function',
            'Call', 'Private', 'Public', 'Sub', 'Explicit', 'Compare', 'Exit'
            ],
        3 => [
            'And', 'Case', 'Do', 'Each', 'Else', 'ElseIf', 'For',
            'Goto', 'If', 'Is', 'Loop', 'Next', 'Not', 'Or', 'Select', 'Step',
            'Then', 'To', 'Until', 'While', 'With', 'Xor', 'WithEvents',
            'DoEvents', 'Close', 'Like', 'In', 'End'
            ],
        4 => [
            'As', 'Dim', 'Get', 'Set', 'ReDim', 'Error',
            'Resume', 'Declare', 'Let', 'ByRef', 'ByVal',
            'Optional', 'Property', 'Control', 'UBound', 'Mod',
            'GoSub', 'Implements', 'Input', 'LBound', 'Static', 'Stop',
            'Type', 'TypeOf', 'On', 'Open', 'Output', 'ParamArray',
            'Preserve', 'Print', 'RaiseEvent', 'Random', 'Line'
            ],
        5 => [
            'Nothing', 'False', 'True', 'Null', 'Empty'
            ],
        6 => [
            'ErrorHandler', 'ExitProc', 'PublishReport'
            ],
        ],
    'SYMBOLS' => [
        ],
    'CASE_SENSITIVE' => [
        GESHI_COMMENTS => false,
        1 => false,
        2 => false,
        3 => false,
        4 => false,
        5 => false,
        6 => false
        ],
    'STYLES' => [
        'KEYWORDS' => [
            1 => 'color: #F660AB; font-weight: bold;',
            2 => 'color: #E56717; font-weight: bold;',
            3 => 'color: #8D38C9; font-weight: bold;',
            4 => 'color: #151B8D; font-weight: bold;',
            5 => 'color: #00C2FF; font-weight: bold;',
            6 => 'color: #3EA99F; font-weight: bold;'
            ],
        'COMMENTS' => [
            1 => 'color: #008000;'
            ],
        'BRACKETS' => [
            ],
        'STRINGS' => [
            0 => 'color: #800000;'
            ],
        'NUMBERS' => [
            ],
        'METHODS' => [
            ],
        'SYMBOLS' => [
            ],
        'ESCAPE_CHAR' => [
            0 => 'color: #800000; font-weight: bold;'
            ],
        'SCRIPT' => [
            ],
        'REGEXPS' => [
            ]
        ],
    'URLS' => [
        1 => '',
        2 => '',
        3 => '',
        4 => '',
        5 => '',
        6 => ''
        ],
    'OOLANG' => false,
    'OBJECT_SPLITTERS' => [
        ],
    'REGEXPS' => [
        ],
    'STRICT_MODE_APPLIES' => GESHI_NEVER,
    'SCRIPT_DELIMITERS' => [
        ],
    'HIGHLIGHT_STRICT_BLOCK' => [
        ],
    'PARSER_CONTROL' => [
        'ENABLE_FLAGS' => [
            'BRACKETS' => GESHI_NEVER,
            'SYMBOLS' => GESHI_NEVER,
            'NUMBERS' => GESHI_NEVER
            ]
        ]
];
