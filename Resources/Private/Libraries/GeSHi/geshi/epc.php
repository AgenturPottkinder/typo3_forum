<?php
/*************************************************************************************
 * epc.php
 * --------
 * Author: Thorsten Muehlfelder (muehlfelder@enertex.de)
 * Copyright: (c) 2010 Enertex Bayern GmbH
 * Release Version: 1.0.8.11
 * Date Started: 2010/08/26
 *
 * Enerscript language file for GeSHi.
 *
 * CHANGES
 * -------
 * 2010/08/26 (1.0.8.10)
 *   -  First Release
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
    'LANG_NAME' => 'EPC',
    'COMMENT_SINGLE' => ['//'],
    'COMMENT_MULTI' => ['/*' => '*/'],
    'COMMENT_REGEXP' => [
        //[Sections]
        //1 => "/^\\[.*\\]/"
        ],
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS' => [
        0 => '"',
        1 => '$'
        ],
    'ESCAPE_CHAR' => '',
    'KEYWORDS' => [
        1 => [
            'if', 'then', 'else', 'endif',
            'and', 'or', 'xor', 'hysteresis'
            ],
        2 => [
            'read', 'write', 'event',
            'gettime', 'settime', 'getdate', 'setdate', 'gettimedate', 'settimedate',
            'hour', 'minute', 'second', 'changehour', 'changeminute', 'changesecond',
            'date', 'month', 'day', 'dayofweek', 'sun', 'azimuth', 'elevation',
            'sunrisehour', 'sunriseminute', 'sunsethour', 'sunsetminute',
            'wtime', 'htime', 'mtime', 'stime',
            'cwtime', 'chtime', 'cmtime', 'cstime',
            'delay', 'after', 'cycle',
            'readflash', 'writeflash',
            'abs', 'acos', 'asin', 'atan', 'cos', 'ceil', 'average', 'exp', 'floor',
            'log', 'max', 'min', 'mod', 'pow', 'sqrt', 'sin', 'tan', 'change', 'convert',
            'eval', 'systemstart', 'random', 'comobject', 'sleep', 'scene', 'storescene', 'callscene',
            'find', 'stringcast', 'stringset', 'stringformat', 'split', 'size',
            'readrs232' . 'sendrs232', 'address', 'readknx',
            'readudp', 'sendudp', 'connecttcp', 'closetcp', 'readtcp', 'sendtcp',
            'resolve', 'sendmail',
            'button', 'webbutton', 'chart', 'webchart', 'webdisplay', 'getslider', 'pshifter', 'mpshifter',
            'getpslider', 'mbutton', 'mbbutton', 'mchart', 'mpchart', 'mpbutton', 'pdisplay', 'pchart',
            'pbutton', 'setslider', 'setpslider', 'slider', 'pslider', 'page', 'line', 'header',
            'footer', 'none', 'plink', 'link', 'frame', 'dframe'
            ]
        ],
    'SYMBOLS' => [
        0 => [
            '%', 'b01',
            ],
        1 => [
            '+', '-', '==', '>=', '=<',
            ],
        ],
    'CASE_SENSITIVE' => [
        GESHI_COMMENTS => false,
        1 => true,
        2 => true
        ],
    'STYLES' => [
        'KEYWORDS' => [
            1 => 'color: #e63ec3;',
            2 => 'color: #e63ec3;'
            ],
        'COMMENTS' => [
            0 => 'color: #0000ff;'
            //1 => 'color: #ffa500;'
            ],
        'ESCAPE_CHAR' => [
            1 => 'color: #000099;'
            ],
        'BRACKETS' => [
            0 => 'color: #000000;'
            ],
        'STRINGS' => [
            0 => 'color: #8a0808;',
            1 => 'color: #6e6e6e;'
            ],
        'NUMBERS' => [
            0 => 'color: #0b610b;'
            ],
        'METHODS' => [
            ],
        'SYMBOLS' => [
            0 => 'color: #0b610b;',
            1 => 'color: #e63ec3;'
            ],
        'REGEXPS' => [
            1 => 'color: #0b610b;'
            ],
        'SCRIPT' => [
            ]
        ],
    'URLS' => [
        1 => '',
        2 => ''
        ],
    'OOLANG' => false,
    'OBJECT_SPLITTERS' => [
        ],
    'REGEXPS' => [
        // Numbers, e.g. 255u08
        1 => '[0-9]*[subf][0136][12468]'
        ],
    'STRICT_MODE_APPLIES' => GESHI_NEVER,
    'SCRIPT_DELIMITERS' => [
        ],
    'HIGHLIGHT_STRICT_BLOCK' => [
        ],
    'TAB_WIDTH' => 4,
    'PARSER_CONTROL' => [
        'COMMENTS' => [
            'DISALLOWED_BEFORE' => '$'
        ],
        'KEYWORDS' => [
            'DISALLOWED_BEFORE' => "(?<![\.\-a-zA-Z0-9_\$\#])",
            'DISALLOWED_AFTER' =>  "(?![\.\-a-zA-Z0-9_%=\\/])"
        ]
    ]
];
