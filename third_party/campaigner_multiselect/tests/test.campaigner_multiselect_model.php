<?php if ( ! defined('EXT')) exit('Invalid file request.');

/**
 * Campaigner Multi-Select model tests.
 *
 * @author          Stephen Lewis (http://github.com/experience/)
 * @copyright       Experience Internet
 * @package         Campaigner_multiselect
 */

require_once PATH_THIRD .'campaigner_multiselect/models/campaigner_multiselect_model' .EXT;

class Test_campaigner_multiselect_model extends Testee_unit_test_case {

  private $_package_name;
  private $_package_version;
  private $_site_id;
  private $_subject;


  /* --------------------------------------------------------------
   * PUBLIC METHODS
   * ------------------------------------------------------------ */

  /**
   * Constructor.
   *
   * @access  public
   * @return  void
   */
  public function setUp()
  {
    parent::setUp();

    $this->_package_name    = 'example_package';
    $this->_package_version = '1.0.0';

    $this->_site_id = 10;

    $this->_ee->config->setReturnValue(
      'item',
      $this->_site_id, array('site_id')
    );

    $this->_subject = new Campaigner_multiselect_model(
      $this->_package_name,
      $this->_package_version
    );
  }


  public function test__constructor__package_name_and_version()
  {
    $package_name       = 'Example_package';
    $package_version    = '1.0.0';

    $subject = new Campaigner_multiselect_model($package_name, $package_version);

    $this->assertIdentical(
      strtolower($package_name),
      $subject->get_package_name()
    );

    $this->assertIdentical($package_version, $subject->get_package_version());
  }


  public function test__get_site_id__success()
  {
    $this->_ee->config->expectOnce('item', array('site_id'));

    $this->assertIdentical(
      intval($this->_site_id),
      $this->_subject->get_site_id()
    );
  }


}


/* End of file      : test.campaigner_multiselect_model.php */
/* File location    : third_party/campaigner_multiselect/tests/test.campaigner_multiselect_model.php */
