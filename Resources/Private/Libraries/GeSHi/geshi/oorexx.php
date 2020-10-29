<?php
/*************************************************************************************
 * oorexx.php
 * ---------------------------------
 * Author: Jon Wolfers (sahananda@windhorse.biz)
 * Contributors:
 *    - Walter Pachl (pachl@chello.at)
 * Copyright: (c) 2008 Jon Wolfers, (c) 2012 Walter Pachl
 * Release Version: 1.0.8.11
 * Date Started: 2008/01/07
 *
 * ooRexx language file for GeSHi.
 *
 * CHANGES
 * -------
 * 2012/07/29 (1.0.0)
 *    -  tried to get it syntactically right
 *
 * TODO (updated 2012/07/29)
 * -------------------------
 *   -  Get it working on rosettacode.org
 *
 *************************************************************************************
 *
 *      This file is part of GeSHi.
 *
 *    GeSHi is free software; you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation; either version 2 of the License, or
 *    (at your option) any later version.
 *
 *    GeSHi is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with GeSHi; if not, write to the Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 ************************************************************************************/

$language_data = [
    'LANG_NAME' => 'ooRexx',
    'COMMENT_SINGLE' => [1 => '--'],
    'COMMENT_MULTI' => ['/*' => '*/'],
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS' => ["'", '"'],
    'ESCAPE_CHAR' => '',
    'KEYWORDS' => [
        1 => [
            'address', 'arg', 'attribute', 'call', 'constant', 'do',
            'drop', 'exit', 'if',
            'interpret', 'iterate', 'leave', 'loop', 'nop', 'numeric',
            'parse', 'procedure', 'pull', 'push', 'queue',
            'raise', 'reply', 'return', 'say', 'select', 'signal',
            'use'
            ],
        2 => [
            'abstract', 'any', 'arguments', 'array', 'by',
            'continue', 'digits', 'engineering', 'error',
            'expose', 'external', 'failure', 'for', 'forever',
            'forward', 'get', 'guard', 'guarded', 'halt',
            'inherit', 'library', 'lostdigits', 'message',
            'metaclass', 'mixinclass', 'name', 'nomethod', 'nostring',
            'notready', 'novalue', 'off', 'on', 'options', 'over',
            'private', 'protected', 'public', 'scientific', 'set',
            'source', 'subclass', 'syntax', 'to', 'unguarded',
            'unprotected', 'until', 'user',
            'version', 'while', 'with'
            ],
        3 => [
            'else', 'end', 'otherwise', 'then', 'when'
            ],
        4 => [
            'rc', 'result', 'self', 'sigl', 'super'
            ],
        5 => [
            '::attribute', '::class', '::constant', '::method',
            '::optins', '::requires', '::routine'
            ],
        6 => [
            'abbrev', 'abs', 'beep', 'bitand', 'bitor',
            'bitxor', 'b2x', 'center', 'centre', 'changestr', 'charin',
            'charout', 'chars', 'compare', 'condition', 'copies',
            'countstr', 'c2d', 'c2x', 'datatype', 'date', 'delstr',
            'delword', 'directory', 'd2c', 'd2x', 'endlocal',
            'errortext', 'filespec', 'form', 'format', 'fuzz', 'insert',
            'lastpos', 'left', 'length', 'linein', 'lineout', 'lines',
            'lower', 'max', 'min', 'overlay', 'pos', 'qualify', 'queued',
            'random', 'reverse', 'right', 'rxfuncadd', 'rxfuncdrop',
            'rxfuncquery', 'rxqueue', 'setlocal', 'sign', 'sourceline',
            'space', 'stream', 'strip', 'substr', 'subword', 'symbol',
            'time', 'trace', 'translate', 'trunc', 'upper', 'userid',
            'value', 'var', 'verify', 'word', 'wordindex', 'wordlength',
            'wordpos', 'words', 'xrange', 'x2b', 'x2c', 'x2d'
            ]
        ],
    'SYMBOLS' => [
        '(', ')', '<', '>', '[', ']', '=', '+', '-', '*', '/', '!', '%', '^', '&', ':',
        '<', '>'
        ],
    'CASE_SENSITIVE' => [
        GESHI_COMMENTS => true,
        1 => false,
        2 => false,
        3 => false,
        4 => false,
        5 => false,
        6 => false
        ],
    'STYLES' => [
        'KEYWORDS' => [
            1 => 'color: #b1b100;',
            2 => 'color: #ff0000; font-weight: bold;',
            3 => 'color: #00ff00; font-weight: bold;',
            4 => 'color: #0000ff; font-weight: bold;',
            5 => 'color: #880088; font-weight: bold;',
            6 => 'color: #888800; font-weight: bold;'
            ],
        'COMMENTS' => [
            1 => 'color: #666666;',
            'MULTI' => 'color: #808080;'
            ],
        'ESCAPE_CHAR' => [
            0 => 'color: #000099; font-weight: bold;'
            ],
        'BRACKETS' => [
            0 => 'color: #66cc66;'
            ],
        'STRINGS' => [
            0 => 'color: #ff0000;'
            ],
        'NUMBERS' => [
            0 => 'color: #cc66cc;'
            ],
        'METHODS' => [
            1 => 'color: #202020;',
            2 => 'color: #202020;'
            ],
        'SYMBOLS' => [
            0 => 'color: #66cc66;'
            ],
        'REGEXPS' => [
            ],
        'SCRIPT' => [
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
    'OOLANG' => true,
    'OBJECT_SPLITTERS' => [
        1 => '.'
        ],
    'REGEXPS' => [
        ],
    'STRICT_MODE_APPLIES' => GESHI_NEVER,
    'SCRIPT_DELIMITERS' => [
        ],
    'HIGHLIGHT_STRICT_BLOCK' => [
        ],
    'TAB_WIDTH' => 4
];
