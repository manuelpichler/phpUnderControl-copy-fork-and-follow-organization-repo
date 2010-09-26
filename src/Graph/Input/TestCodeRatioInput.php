<?php
/**
 * This file is part of phpUnderControl.
 *
 * PHP Version 5.2.0
 *
 * Copyright (c) 2007-2010, Manuel Pichler <mapi@phpundercontrol.org>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Manuel Pichler nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   QualityAssurance
 * @package    Graph
 * @subpackage Input
 * @author     Manuel Pichler <mapi@phpundercontrol.org>
 * @copyright  2007-2010 Manuel Pichler. All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id$
 * @link       http://www.phpundercontrol.org/
 */

/**
 * This class implements the graph input for the tests to normal code ratio.
 *
 * @category   QualityAssurance
 * @package    Graph
 * @subpackage Input
 * @author     Manuel Pichler <mapi@phpundercontrol.org>
 * @copyright  2007-2010 Manuel Pichler. All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: @package_version@
 * @link       http://www.phpundercontrol.org/
 */
class phpucTestCodeRatioInput extends phpucAbstractInput
{
    /**
     * Constructs a new unit coverage input object.
     */
    public function __construct()
    {
        parent::__construct(
            'Test to Code Ratio',
            '12-test-to-code-ratio',
            phpucChartI::TYPE_LINE
        );

        $this->yAxisLabel = 'Classes / Methods';
        $this->xAxisLabel = 'Build ';

        $this->addRule(
            new phpucInputRule(
                'Classes',
                '/cruisecontrol/coverage/project/file/class',
                self::MODE_COUNT
            )
        );
        $this->addRule(
            new phpucInputRule(
                'Methods',
                '/cruisecontrol/coverage/project/file/line[@type="method"]',
                self::MODE_COUNT
            )
        );
        $this->addRule(
            new phpucInputRule(
                'Test Classes',
                '/cruisecontrol/testsuites//testsuite[testcase]',
                self::MODE_COUNT
            )
        );
        $this->addRule(
            new phpucInputRule(
                'Test Methods',
                '/cruisecontrol/testsuites//testsuite/testcase',
                self::MODE_COUNT
            )
        );
    }
}
