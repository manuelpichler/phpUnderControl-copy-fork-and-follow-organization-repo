<?php
/**
 * This file is part of phpUnderControl.
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
 * @package   Tasks
 * @author    Sebastian Marek <proofek@gmail.com>
 * @copyright 2007-2010 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://www.phpundercontrol.org/
 */

require_once dirname( __FILE__ ) . '/../AbstractTest.php';

/**
 * Test case for the exec ant task.
 *
 * @package   Tasks
 * @author    Sebastian Marek <proofek@gmail.com>
 * @copyright 2007-2010 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://www.phpundercontrol.org/
 */
class phpucExecAntTaskTest extends phpucAbstractTest
{
    /**
     * The test project name.
     *
     * @type string
     * @var string $projectName
     */
    protected $projectName = 'php-under-control';

    /**
     * The test build file name.
     *
     * @type string
     * @var string $fileName
     */
    protected $fileName = null;

    /**
     * The constructor sets the build file name.
     *
     * @param string $name An optional test case name.
     * @param array  $data An optional data array.
     */
    public function __construct( $name = null, array $data = array() )
    {
        parent::__construct( $name, $data );

        $this->fileName = PHPUC_TEST_DIR . '/build.xml';
    }

    /**
     * Tests the exec target is generated properly.
     *
     * @covers phpucExecAntTask::buildXml
     *
     * @return void
     */
    public function testBuildExecTarget()
    {
        $buildFile = new phpucBuildFile( $this->fileName, $this->projectName );
        $target    = $buildFile->createBuildTarget( 'phpuc' );

        $execTask = phpucAbstractAntTask::create( $buildFile, 'exec' );
        $execTask->executable = 'phpuc';
        $execTask->dir         = PHPUC_TEST_DIR;
        $execTask->argLine     = 'example /opt/cruisecontrol';
        $execTask->output      = PHPUC_TEST_DIR;
        $execTask->failonerror = true;
        $execTask->logerror    = true;

        $target->addTask($execTask);
        $buildFile->store();

        $sxml  = simplexml_load_file( $this->fileName );

        $build = $sxml->xpath( '/project/target[@name="build"]' );
        $phpuc = $sxml->xpath( '/project/target[@name="phpuc"]' );

        $this->assertEquals( 1, count( $build ) );
        $this->assertEquals( 1, count( $phpuc ) );

        $this->assertEquals( 'phpuc', (string) $build[0]['depends'] );
        $this->assertEquals( 'phpuc', (string) $phpuc[0]['name'] );

        $exec = $phpuc[0]->exec;

        $this->assertType( 'SimpleXMLElement', $exec );
        $this->assertEquals( 'phpuc', (string) $exec['executable'] );
        $this->assertEquals( 'on', (string) $exec['failonerror'] );
        $this->assertEquals( 'on', (string) $exec['logerror'] );
        $this->assertEquals( PHPUC_TEST_DIR, (string) $exec['dir'] );
        $this->assertEquals( PHPUC_TEST_DIR, (string) $exec['output'] );

        $this->assertType( 'SimpleXMLElement', $exec->arg );

        $this->assertEquals( 'example /opt/cruisecontrol', (string) $exec->arg['line'] );
    }
}