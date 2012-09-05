<?php

namespace DownloadsTest\Model;

use Downloads\Model\ReleaseModel;
use PHPUnit_Framework_TestCase as TestCase;

class ReleaseModelTest extends TestCase
{
    public function setUp()
    {
        $config = include __DIR__ . '/TestAsset/module.config.php';
        $this->config = $config = $config['downloads'];
        $this->model  = new ReleaseModel(
            $config['release_base_path'], 
            $config['versions'],
            $config['manual_languages'],
            $config['products']
        );
    }

    public function testCanRetrieveMostCurrentStableVersion()
    {
        $this->assertEquals('1.12.0', $this->model->getCurrentStableVersion());
    }


    public function testCanRetrieveMostCurrentStableVersionByMajorVersion()
    {
        $this->assertEquals('1.12.0', $this->model->getCurrentStableVersion('1'));
    }

    public function testCanRetrieveMostCurrentStableVersionByMinorVersion()
    {
        $this->assertEquals('1.11.14', $this->model->getCurrentStableVersion('1.11'));
    }

    public function testCanRetrieveReleaseDateByVersion()
    {
        $this->assertEquals($this->config['versions']['1.11.13'], $this->model->getReleaseDate('1.11.13'));
    }

    public function testCanRetrieveListOfAllVersionNumbers()
    {
        $versions = array_keys($this->config['versions']);
        $test     = $this->model->getVersions();
        $this->assertEquals(count($versions), count($test));
        foreach ($versions as $version) {
            $this->assertContains($version, $test);
        }
    }

    public function testCanRetrieveReleaseDates()
    {
        $versions = $this->config['versions'];
        $this->assertEquals($versions, $this->model->getReleaseDates());
    }

    public function testCanRetrieveFullReleaseTarballByVersion()
    {
        $path = $this->config['release_base_path'];
        $expected = $path . 'ZendFramework-1.11.14/ZendFramework-1.11.14.tar.gz';
        $this->assertEquals($expected, $this->model->getArchive('1.11.14', ReleaseModel::ARCHIVE_TAR));
    }

    public function testCanRetrieveFullReleaseZipballByVersion()
    {
        $path = $this->config['release_base_path'];
        $expected = $path . 'ZendFramework-1.11.14/ZendFramework-1.11.14.zip';
        $this->assertEquals($expected, $this->model->getArchive('1.11.14', ReleaseModel::ARCHIVE_ZIP));
    }

    public function getPre1Dot0Packages()
    {
        $config = include __DIR__ . '/TestAsset/module.config.php';
        $config = $config['downloads'];
        $path   = $config['release_base_path'];
        return array(
            array('0.1.3',      $path . 'ZendFramework-0.1.3.zip'),
            array('0.2.0',      $path . 'ZendFramework-0.2.0.zip'),
            array('0.6.0',      $path . 'ZendFramework-0.6.0.zip'),
            array('0.7.0',      $path . 'ZendFramework-0.7.0.zip'),
            array('0.8.0',      $path . 'ZendFramework-0.8.0.zip'),
            array('0.9.0-Beta', $path . 'ZendFramework-0.9.0-Beta.zip'),
            array('0.9.3-Beta', $path . 'ZendFramework-0.9.3-Beta.zip'),
            array('1.0.0-RC1',  $path . 'ZendFramework-1.0.0-RC1.zip'),
            array('1.0.0-RC2',  $path . 'ZendFramework-1.0.0-RC2/ZendFramework-1.0.0-RC2.zip'),
        );
    }

    /**
     * @dataProvider getPre1Dot0Packages
     */
    public function testPre1Dot0PackagesShouldNotIncludeExtraPathInformation($version, $expected)
    {
        $this->assertEquals($expected, $this->model->getArchive($version, ReleaseModel::ARCHIVE_ZIP));
    }

    public function testCanRetrieveMinimalReleaseTarballByVersion()
    {
        $path = $this->config['release_base_path'];
        $expected = $path . 'ZendFramework-1.11.14/ZendFramework-1.11.14-minimal.tar.gz';
        $this->assertEquals($expected, $this->model->getMinimalArchive('1.11.14', ReleaseModel::ARCHIVE_TAR));
    }

    public function testCanRetrieveMinimalReleaseZipballByVersion()
    {
        $path = $this->config['release_base_path'];
        $expected = $path . 'ZendFramework-1.11.14/ZendFramework-1.11.14-minimal.zip';
        $this->assertEquals($expected, $this->model->getMinimalArchive('1.11.14', ReleaseModel::ARCHIVE_ZIP));
    }

    public function testMinimalArchivePathsForZf2VersionsAreFormattedCorrectly()
    {
        $path = $this->config['release_base_path'];
        $expected = $path . 'ZendFramework-2.0.0rc6/ZendFramework-minimal-2.0.0rc6.zip';
        $this->assertEquals($expected, $this->model->getMinimalArchive('2.0.0rc6', ReleaseModel::ARCHIVE_ZIP));
    }

    public function getMinimalVersions()
    {
        return array(
            array('0.2.0', false),
            array('1.0.0rc3', false),
            array('1.5.3', false),
            array('1.6.0rc1', false),
            array('1.6.0rc2', false),
            array('1.6.0rc3', false),
            array('1.6.0', true),
            array('1.7.1', true),
            array('1.8.2', true),
            array('1.9.3', true),
            array('1.10.4', true),
            array('1.11.5', true),
            array('1.12.0', true),
            array('2.0.0rc6', true),
        );
    }

    /**
     * @dataProvider getMinimalVersions
     */
    public function testVersionsPriorTo1Dot6Dot0DidNotHaveMinimalVersions($version, $expected)
    {
        $this->assertSame($expected, $this->model->hasMinimalVersion($version));
    }

    public function getManualLanguages()
    {
        return array(
            array('0.2.0', array()),
            array('1.0.0rc2', array()),
            array('1.0.0rc3', array('en', 'de', 'fr', 'ja', 'ru', 'zh')),
            array('1.12.0', array('en', 'de', 'fr', 'ja', 'ru', 'zh')),
            array('2.0.0rc1', array('en')),
            array('2.0.0rc6', array('en')),
        );
    }

    /**
     * @dataProvider getManualLanguages
     */
    public function testCanGetManualLanguagesByVersion($version, $expected)
    {
        $this->assertEquals($expected, $this->model->getManualLanguages($version));
    }

    public function getManualLanguageArchives()
    {
        return array(
            array('0.2.0',    'en', false),
            array('1.0.0rc2', 'fr', false),
            array('1.0.0rc3', 'en', true),
            array('1.0.0rc3', 'fr', true),
            array('1.0.0rc3', 'zh', true),
            array('1.12.0',   'ja', true),
            array('1.12.0',   'de', true),
            array('1.12.0',   'ru', true),
            array('2.0.0rc1', 'en', true),
            array('2.0.0rc1', 'fr', false),
            array('2.0.0rc6', 'en', true),
            array('2.0.0rc6', 'zh', false),
        );
    }

    /**
     * @dataProvider getManualLanguageArchives
     */
    public function testCanRetrieveManualReleaseTarballByVersionAndLanguage($version, $language, $expected)
    {
        $path = $this->config['release_base_path'] . 'ZendFramework-%s/ZendFramework-%s-manual-%s.%s';
        if (!$expected) {
            $this->setExpectedException('InvalidArgumentException', 'exist');
            $this->model->getManualArchive($version, $language, ReleaseModel::ARCHIVE_TAR);
        } else {
            $expected = sprintf($path, $version, $version, $language, 'tar.gz');
            $this->assertEquals($expected, $this->model->getManualArchive($version, $language, ReleaseModel::ARCHIVE_TAR));
        }
    }

    /**
     * @dataProvider getManualLanguageArchives
     */
    public function testCanRetrieveManualReleaseZipballByVersionAndLanguage($version, $language, $expected)
    {
        $path = $this->config['release_base_path'] . 'ZendFramework-%s/ZendFramework-%s-manual-%s.%s';
        if (!$expected) {
            $this->setExpectedException('InvalidArgumentException', 'exist');
            $this->model->getManualArchive($version, $language, ReleaseModel::ARCHIVE_ZIP);
        } else {
            $expected = sprintf($path, $version, $version, $language, 'zip');
            $this->assertEquals($expected, $this->model->getManualArchive($version, $language, ReleaseModel::ARCHIVE_ZIP));
        }
    }

    public function testCanRetrieveApidocReleasePackagesByVersionAndType()
    {
        $path = $this->config['release_base_path'] . 'ZendFramework-%s/ZendFramework-%s-apidoc.%s';
        foreach (array(ReleaseModel::ARCHIVE_TAR => 'tar.gz', ReleaseModel::ARCHIVE_ZIP => 'zip') as $type => $suffix) {
            foreach (array_keys($this->config['versions']) as $version) {
                $expected = sprintf($path, $version, $version, $suffix);
                $this->assertEquals($expected, $this->model->getApidocArchive($version, $type));
            }
        }
    }

    public function getVersionsForStabilityComparison()
    {
        return array(
            array('2.0.0rc3', false),
            array('1.12.0', true),
            array('1.12.0rc3', false),
            array('1.10.0alpha1', false),
            array('1.10.3', true),
            array('1.8.4pl1', true),
        );
    }

    /**
     * @dataProvider getVersionsForStabilityComparison
     */
    public function testCanTestIfAGivenVersionIsStable($version, $expected)
    {
        $this->assertSame($expected, $this->model->isStable($version));
    }

    public function getProductReleases()
    {
        return array(
            array('InfoCard', '1.0.3', false),
            array('InfoCard', '1.5.0', true),
            array('InfoCard', '1.11.14', true),
            array('InfoCard', '1.12.0', true),
            array('InfoCard', '2.0.0rc1', false),
            array('Gdata', '0.2.0', true),
            array('Gdata', '1.5.3', true),
            array('Gdata', '1.11.14', true),
            array('Gdata', '1.12.0', true),
            array('Gdata', '2.0.0rc1', false),
            array('AMF', '1.0.3', false),
            array('AMF', '1.5.0', false),
            array('AMF', '1.6.1', false),
            array('AMF', '1.7.0', true),
            array('AMF', '1.11.14', true),
            array('AMF', '1.12.0', true),
            array('AMF', '2.0.0rc1', false),
        );
    }

    /**
     * @dataProvider getProductReleases
     */
    public function testCanRetrieveProductReleaseTarballsByVersion($product, $version, $expected)
    {
        $path = $this->config['release_base_path'] . 'Zend%s-%s/Zend%s-%s.tar.gz';
        if ($expected) {
            $path = sprintf($path, $product, $version, $product, $version);
            $this->assertEquals($path, $this->model->getProductArchive($product, $version, ReleaseModel::ARCHIVE_TAR));
        } else {
            $this->setExpectedException('DomainException', 'product');
            $this->model->getProductArchive($product, $version, ReleaseModel::ARCHIVE_TAR);
        }
    }

    /**
     * @dataProvider getProductReleases
     */
    public function testCanRetrieveProductReleaseZipballsByVersion($product, $version, $expected)
    {
        $path = $this->config['release_base_path'] . 'Zend%s-%s/Zend%s-%s.zip';
        if ($expected) {
            $path = sprintf($path, $product, $version, $product, $version);
            $this->assertEquals($path, $this->model->getProductArchive($product, $version, ReleaseModel::ARCHIVE_ZIP));
        } else {
            $this->setExpectedException('DomainException', 'product');
            $this->model->getProductArchive($product, $version, ReleaseModel::ARCHIVE_ZIP);
        }
    }

    public function testCanRetrieveMostRecentStableProductVersion()
    {
        $this->assertEquals('1.12.0', $this->model->getCurrentStableProductVersion('InfoCard'));
    }

    public function testCanRetrieveMostRecentStableProductVersionByMajorVersion()
    {
        $this->assertEquals('1.12.0', $this->model->getCurrentStableProductVersion('InfoCard', '1'));
    }

    public function testCanRetrieveMostRecentStableProductVersionByMinorVersion()
    {
        $this->assertEquals('1.11.14', $this->model->getCurrentStableProductVersion('InfoCard', '1.11'));
    }

    public function getProductVersions()
    {
        $config      = include __DIR__ . '/TestAsset/module.config.php';
        $config      = $config['downloads'];
        $allReleases = array_keys($config['versions']);
        $products    = $config['products'];
        $model       = new ReleaseModel(
            $config['release_base_path'], 
            $config['versions'],
            $config['manual_languages'],
            $products
        );

        $return      = array();
        foreach ($products as $product => $spec) {
            $min  = $spec['first'];
            $max  = $spec['latest'];
            $max  = $model->getCurrentStableVersion($max);
            $test = array();
            foreach ($allReleases as $version) {
                if (version_compare($version, $min, 'lt')) {
                    continue;
                }
                if (version_compare($version, $max, 'gt')) {
                    continue;
                }
                $test[] = $version;
            }
            usort($test, 'version_compare');
            $test = array_reverse($test);
            $return[] = array($product, $test);
        }
        return $return;
    }

    /**
     * @dataProvider getProductVersions
     */
    public function testCanRetrieveAllReleasesByProductType($product, $versions)
    {
        $this->assertEquals($versions, $this->model->getProductVersions($product));
    }

    public function testCanRetrieveListOfProducts()
    {
        $this->assertEquals(array_keys($this->config['products']), $this->model->getProducts());
    }

    public function getVersionStrings()
    {
        return array(
            array('1.0.0rc3', '1', '1.0'),
            array('1.5.7', '1', '1.5'),
            array('1.11.15', '1', '1.11'),
            array('2.0.0beta5', '2', '2.0'),
            array('2.0.0rc3', '2', '2.0'),
            array('2.0.0', '2', '2.0'),
        );
    }

    /**
     * @dataProvider getVersionStrings
     */
    public function testCanRetrieveMajorAndMinorVersionFromFullVersionString($version, $major, $minor)
    {
        $this->assertEquals($major, $this->model->getMajorVersion($version));
        $this->assertEquals($minor, $this->model->getMinorVersion($version));
    }
}
