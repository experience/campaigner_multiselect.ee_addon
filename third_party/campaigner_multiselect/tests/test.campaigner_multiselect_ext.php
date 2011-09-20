<?php if ( ! defined('EXT')) exit('Invalid file request.');

/**
 * Campaigner Multi-Select extension tests.
 *
 * @author          Stephen Lewis (http://github.com/experience/)
 * @copyright       Experience Internet
 * @package         Campaigner_multiselect
 */

require_once PATH_THIRD .'campaigner_multiselect/ext.campaigner_multiselect.php';
require_once PATH_THIRD .'campaigner_multiselect/tests/mocks/mock.campaigner_multiselect_model.php';

class Test_campaigner_multiselect_ext extends Testee_unit_test_case {

  private $_model;
  private $_subject;


  /* --------------------------------------------------------------
   * PUBLIC METHODS
   * ------------------------------------------------------------ */

  /**
   * Set up.
   *
   * @access  public
   * @return  void
   */
  public function setUp()
  {
    parent::setUp();

    Mock::generate(
      'Mock_campaigner_multiselect_model',
      get_class($this) .'_mock_model'
    );

    $this->_model = $this->_get_mock('model');
    $this->_ee->campaigner_multiselect_model =& $this->_model;
    $this->_subject = new Campaigner_multiselect_ext();
  }


/* End of file      : test.campaigner_multiselect_ext.php */
/* File location    : third_party/campaigner_multiselect/tests/
 *                    test.campaigner_multiselect_ext.php */
