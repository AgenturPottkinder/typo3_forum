<?php
/*************************************************************************************
 * javascript.php
 * --------------
 * Author: Ben Keen (ben.keen@gmail.com)
 * Copyright: (c) 2004 Ben Keen (ben.keen@gmail.com), Nigel McNie (http://qbnz.com/highlighter)
 * Release Version: 1.0.8.11
 * Date Started: 2004/06/20
 *
 * JavaScript language file for GeSHi.
 *
 * CHANGES
 * -------
 * 2012/06/27 (1.0.8.11)
 *  -  Reordered Keyword Groups to reflect syntactical meaning of keywords
 * 2008/05/23 (1.0.7.22)
 *  -  Added description of extra language features (SF#1970248)
 * 2004/11/27 (1.0.1)
 *  -  Added support for multiple object splitters
 * 2004/10/27 (1.0.0)
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
    'LANG_NAME' => 'Javascript',
    'COMMENT_SINGLE' => [1 => '//'],
    'COMMENT_MULTI' => ['/*' => '*/'],
    'COMMENT_REGEXP' => [
        //Regular Expressions
        2 => "/(?<=[\\s^])(s|tr|y)\\/(?!\*)(?!\s)(?:\\\\.|(?!\n)[^\\/\\\\])+(?<!\s)\\/(?!\s)(?:\\\\.|(?!\n)[^\\/\\\\])*(?<!\s)\\/[msixpogcde]*(?=[\\s$\\.\\;])|(?<=[\\s^(=])(m|q[qrwx]?)?\\/(?!\*)(?!\s)(?:\\\\.|(?!\n)[^\\/\\\\])+(?<!\s)\\/[msixpogc]*(?=[\\s$\\.\\,\\;\\)])/iU"
        ],
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS' => ["'", '"'],
    'ESCAPE_CHAR' => '\\',
    'KEYWORDS' => [
        1 => [
            //reserved/keywords; also some non-reserved keywords
            'break', 'case', 'catch', 'const', 'continue',
            'default', 'delete', 'do',
            'else',
            'finally', 'for', 'function',
            'get', 'goto',
            'if', 'in', 'instanceof',
            'new',
            'prototype',
            'return',
            'set', 'static', 'switch',
            'this', 'throw', 'try', 'typeof',
            'var', 'void'
            ],
        2 => [
            //reserved/non-keywords; metaconstants
            'false', 'null', 'true', 'undefined', 'NaN', 'Infinity'
            ],
        3 => [
            //magic properties/functions
            '__proto__', '__defineGetter__', '__defineSetter__', 'hasOwnProperty', 'hasProperty'
            ],
        4 => [
            //type constructors
            'Object', 'Function', 'Date', 'Math', 'String', 'Number', 'Boolean', 'Array'
            ],
        5 => [
            //reserved, but invalid in language
            'abstract', 'boolean', 'byte', 'char', 'class', 'debugger', 'double', 'enum', 'export', 'extends',
            'final', 'float', 'implements', 'import', 'int', 'interface', 'long', 'native',
            'short', 'super', 'synchronized', 'throws', 'transient', 'volatile'
            ],
        ],
    'SYMBOLS' => [
        '(', ')', '[', ']', '{', '}',
        '+', '-', '*', '/', '%',
        '!', '@', '&', '|', '^',
        '<', '>', '=',
        ',', ';', '?', ':'
        ],
    'CASE_SENSITIVE' => [
        GESHI_COMMENTS => false,
        1 => true,
        2 => true,
        3 => true,
        4 => true,
        5 => true
        ],
    'STYLES' => [
        'KEYWORDS' => [
            1 => 'color: #000066; font-weight: bold;',
            2 => 'color: #003366; font-weight: bold;',
            3 => 'color: #000066;',
            5 => 'color: #FF0000;'
            ],
        'COMMENTS' => [
            1 => 'color: #006600; font-style: italic;',
            2 => 'color: #009966; font-style: italic;',
            'MULTI' => 'color: #006600; font-style: italic;'
            ],
        'ESCAPE_CHAR' => [
            0 => 'color: #000099; font-weight: bold;'
            ],
        'BRACKETS' => [
            0 => 'color: #009900;'
            ],
        'STRINGS' => [
            0 => 'color: #3366CC;'
            ],
        'NUMBERS' => [
            0 => 'color: #CC0000;'
            ],
        'METHODS' => [
            1 => 'color: #660066;'
            ],
        'SYMBOLS' => [
            0 => 'color: #339933;'
            ],
        'REGEXPS' => [
            ],
        'SCRIPT' => [
            0 => '',
            1 => '',
            2 => '',
            3 => ''
            ]
        ],
    'URLS' => [
        1 => '',
        2 => '',
        3 => '',
        4 => '',
        5 => ''
    ],
    'OOLANG' => true,
    'OBJECT_SPLITTERS' => [
        1 => '.'
        ],
    'REGEXPS' => [
        ],
    'STRICT_MODE_APPLIES' => GESHI_MAYBE,
    'SCRIPT_DELIMITERS' => [
        0 => [
            '<script type="text/javascript">' => '</script>'
            ],
        1 => [
            '<script language="javascript">' => '</script>'
            ]
        ],
    'HIGHLIGHT_STRICT_BLOCK' => [
        0 => true,
        1 => true
        ]
];
