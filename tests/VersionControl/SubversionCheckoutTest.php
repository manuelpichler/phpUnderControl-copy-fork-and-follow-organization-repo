<?php
/**
 * This file is part of phpUnderControl.
 * 
 * PHP Version 5.2.4
 *
 * Copyright (c) 2007-2008, Manuel Pichler <mapi@manuel-pichler.de>.
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
 * @category  QualityAssurance
 * @package   VersionControl
 * @author    Manuel Pichler <mapi@manuel-pichler.de>
 * @copyright 2007-2008 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://www.phpundercontrol.org/
 */

require_once dirname( __FILE__ ) . '/../AbstractTest.php';

/**
 * Test case for the subversion checkout.
 *
 * @category  QualityAssurance
 * @package   VersionControl
 * @author    Manuel Pichler <mapi@manuel-pichler.de>
 * @copyright 2007-2008 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://www.phpundercontrol.org/
 */
class phpucSubversionCheckoutTest extends phpucAbstractTest
{
    /**
     * Resets the path and os settings in {@link phpucFileUtil}.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        
        phpucFileUtil::setOS();
        phpucFileUtil::setPaths();
    }
    
    /**
     * Tests a simple svn://example.com checkout without login.
     *
     * @return void
     */
    public function testSvnCheckoutNoLogin()
    {
        $destination = PHPUC_TEST_DIR . '/pdepend';
        $checkFile1  = $destination . '/pdepend.php';
        $checkFile2  = $destination . '/PHP/Depend.php';
        $checkFile3  = $destination . '/PHP/Depend/Code/Class.php';
        
        $this->assertFileNotExists( $checkFile1 );
        $this->assertFileNotExists( $checkFile2 );
        $this->assertFileNotExists( $checkFile3 );
        
        $checkout       = new phpucSubversionCheckout();
        $checkout->url  = 'svn://svn.xplib.de/PHP_Depend/trunk';
        $checkout->dest = $destination;
        
        $checkout->checkout();
        
        $this->assertFileExists( $checkFile1 );
        $this->assertFileExists( $checkFile2 );
        $this->assertFileExists( $checkFile3 );
    }
    
    /**
     * Tests a simple svn://example.com checkout with an invalid uri.
     *
     * @return void
     */
    public function testSvnCheckoutInvalidUrlFail()
    {
        $this->setExpectedException( 'phpucErrorException' );
        
        $checkout      = new phpucSubversionCheckout();
        $checkout->url = 'svn://svn.xplib.de/PHP_Depened/trunk';
        $checkout->checkout();
    }
    
    /**
     * Tests a simple svn://example.com checkout with login.
     *
     * @return void
     */
    public function testSvnCheckoutWithLogin()
    {
        $destination = PHPUC_TEST_DIR . '/phpundercontrol';
        $checkFile1  = $destination . '/Commands/AbstractCommand.php';
        $checkFile2  = $destination . '/Commands/InstallCommand.php';
        
        $this->assertFileNotExists( $checkFile1 );
        $this->assertFileNotExists( $checkFile2 );
        
        $checkout           = new phpucSubversionCheckout();
        $checkout->url      = 'svn://svn.xplib.de/phpuc-test';
        $checkout->dest     = $destination;
        $checkout->username = 'mapi17';
        $checkout->password = 'foobar42';

        $checkout->checkout();
        
        $this->assertFileExists( $checkFile1 );
        $this->assertFileExists( $checkFile2 );
    }
    
    /**
     * Tests a svn://example.com checkout with invalid username.
     *
     * @return void
     */
    public function testSvnCheckoutWithInvalidUsernameFail()
    {
        $this->setExpectedException( 'phpucErrorException' );
        
        $checkout           = new phpucSubversionCheckout();
        $checkout->url      = 'svn://svn.xplib.de/phpuc-test';
        $checkout->dest     = PHPUC_TEST_DIR;
        $checkout->username = 'mapi';
        $checkout->password = 'foobar42';
        $checkout->checkout();
    }
    
    /**
     * Tests a svn://example.com checkout with invalid password.
     *
     * @return void
     */
    public function testSvnCheckoutWithInvalidPasswordFail()
    {
        $this->setExpectedException( 'phpucErrorException' );
        
        $checkout           = new phpucSubversionCheckout();
        $checkout->url      = 'svn://svn.xplib.de/phpuc-test';
        $checkout->dest     = PHPUC_TEST_DIR;
        $checkout->username = 'mapi17';
        $checkout->password = 'foobar';
        $checkout->checkout();
    }
    
    /**
     * Tests a http://example.com checkout.
     *
     * @return void
     */
    public function testHttpCheckout()
    {
        $destination = PHPUC_TEST_DIR . '/pdepend';
        $checkFile1  = $destination . '/pdepend.php';
        $checkFile2  = $destination . '/PHP/Depend.php';
        $checkFile3  = $destination . '/PHP/Depend/Code/Class.php';
        
        $this->assertFileNotExists( $checkFile1 );
        $this->assertFileNotExists( $checkFile2 );
        $this->assertFileNotExists( $checkFile3 );
        
        $checkout       = new phpucSubversionCheckout();
        $checkout->url  = 'http://svn.xplib.de/PHP_Depend/trunk';
        $checkout->dest = $destination;
        
        $checkout->checkout();
        
        $this->assertFileExists( $checkFile1 );
        $this->assertFileExists( $checkFile2 );
        $this->assertFileExists( $checkFile3 );
    }
    
    /**
     * Tests a http://example.com checkout with an invalid domain name.
     *
     * @return void
     */
    public function testHttpCheckoutWithInvalidDomainNameFail()
    {
        $this->setExpectedException( 'phpucErrorException' );
        
        $checkout      = new phpucSubversionCheckout();
        $checkout->url = 'http://svn.xplib.de_/PHP_Depend/trunk';
        $checkout->checkout();
    }
    
    /**
     * Tests a http://example.com checkout with an invalid repository uri.
     *
     * @return void
     */
    public function testHttpCheckoutWithInvalidUrlFail()
    {
        $this->setExpectedException( 'phpucErrorException' );
        
        $checkout      = new phpucSubversionCheckout();
        $checkout->url = 'http://svn.xplib.de/PHP_Depened/trunk';
        $checkout->checkout();
    }
    
    /**
     * Tests a svn+ssh://example.com checkout.
     *
     * @return void
     */
    public function testSvnSshPrivateKeyCheckout()
    {
        if ( !in_array( exec( 'hostname' ), array( 'arwen', 'gandalf' ) ) )
        {
            $this->markTestSkipped( 'Invalid host for key checkout test.' );
            return;
        }

        $destination = PHPUC_TEST_DIR . '/pdepend';
        $checkFile1  = $destination . '/pdepend.php';
        $checkFile2  = $destination . '/PHP/Depend.php';
        $checkFile3  = $destination . '/PHP/Depend/Code/Class.php';
        
        $this->assertFileNotExists( $checkFile1 );
        $this->assertFileNotExists( $checkFile2 );
        $this->assertFileNotExists( $checkFile3 );
        
        $checkout       = new phpucSubversionCheckout();
        $checkout->url  = 'svn+ssh://mapi@xplib.de/srv/pdepend/trunk';
        $checkout->dest = $destination;
        
        $checkout->checkout();
        
        $this->assertFileExists( $checkFile1 );
        $this->assertFileExists( $checkFile2 );
        $this->assertFileExists( $checkFile3 );
    }
}