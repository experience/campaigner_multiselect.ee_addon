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


  public function test__constructor__success()
  {
    $lang = $this->_ee->lang;

    $lang->expectCallCount('line', 2);
    $lang->expectAt(0, 'line', array('ext_description'));
    $lang->expectAt(1, 'line', array('ext_name'));

    $this->_model->expectOnce('get_package_version');
  }


  public function test__activate_extension__success()
  {
    $this->_model->expectOnce('install_extension');
    $this->_subject->activate_extension();
  }


  public function test__disable_extension__success()
  {
    $this->_model->expectOnce('uninstall_extension');
    $this->_subject->disable_extension();
  }


  public function test__on_campaigner_should_subscribe_member__multi_value_subscribe()
  {
    $member_data = array(
      'email'         => 'steve@apple.com',
      'favorite_os'   => "MacOS\niOS",
      'screen_name'   => 'Steve Jobs'
    );

    $mailing_list = new Campaigner_mailing_list(array(
      'active'        => TRUE,
      'list_id'       => 'ABC123',
      'list_name'     => 'My Lovely List',
      'trigger_field' => 'favorite_os',
      'trigger_value' => 'iOS'
    ));
  
    $this->assertIdentical(
      TRUE,
      $this->_subject->on_campaigner_should_subscribe_member(
        $member_data,
        $mailing_list
      )
    );
  }


  public function test__on_campaigner_should_subscribe_member__single_value_subscribe()
  {
    $member_data = array(
      'email'         => 'steve@apple.com',
      'favorite_os'   => 'iOS',
      'screen_name'   => 'Steve Jobs'
    );

    $mailing_list = new Campaigner_mailing_list(array(
      'active'        => TRUE,
      'list_id'       => 'ABC123',
      'list_name'     => 'My Lovely List',
      'trigger_field' => 'favorite_os',
      'trigger_value' => 'iOS'
    ));
  
    $this->assertIdentical(
      TRUE,
      $this->_subject->on_campaigner_should_subscribe_member(
        $member_data,
        $mailing_list
      )
    );
  }


  public function test__on_campaigner_should_subscribe_member__multi_value_no_subscribe()
  {
    $member_data = array(
      'email'         => 'steve@apple.com',
      'favorite_os'   => "MacOS\niOS",
      'screen_name'   => 'Steve Jobs'
    );

    $mailing_list = new Campaigner_mailing_list(array(
      'active'        => TRUE,
      'list_id'       => 'ABC123',
      'list_name'     => 'My Lovely List',
      'trigger_field' => 'favorite_os',
      'trigger_value' => 'Windows'
    ));
  
    $this->assertIdentical(
      FALSE,
      $this->_subject->on_campaigner_should_subscribe_member(
        $member_data,
        $mailing_list
      )
    );
  }


  public function test__on_campaigner_should_subscribe_member__single_value_no_subscribe()
  {
    $member_data = array(
      'email'         => 'steve@apple.com',
      'favorite_os'   => 'iOS',
      'screen_name'   => 'Steve Jobs'
    );

    $mailing_list = new Campaigner_mailing_list(array(
      'active'        => TRUE,
      'list_id'       => 'ABC123',
      'list_name'     => 'My Lovely List',
      'trigger_field' => 'favorite_os',
      'trigger_value' => 'Windows'
    ));
  
    $this->assertIdentical(
      FALSE,
      $this->_subject->on_campaigner_should_subscribe_member(
        $member_data,
        $mailing_list
      )
    );
  }


  public function test__on_campaigner_should_subscribe_member__no_trigger_field()
  {
    $member_data = array(
      'email'         => 'steve@apple.com',
      'favorite_os'   => 'iOS',
      'screen_name'   => 'Steve Jobs'
    );

    $mailing_list = new Campaigner_mailing_list(array(
      'active'        => TRUE,
      'list_id'       => 'ABC123',
      'list_name'     => 'My Lovely List',
      'trigger_field' => '',
      'trigger_value' => 'Windows'
    ));
  
    $this->assertIdentical(
      TRUE,
      $this->_subject->on_campaigner_should_subscribe_member(
        $member_data,
        $mailing_list
      )
    );
  }


  public function test__on_campaigner_should_subscribe_member__subscribe_with_empty_trigger_value()
  {
    $member_data = array(
      'email'         => 'steve@apple.com',
      'favorite_os'   => '',
      'screen_name'   => 'Steve Jobs'
    );

    $mailing_list = new Campaigner_mailing_list(array(
      'active'        => TRUE,
      'list_id'       => 'ABC123',
      'list_name'     => 'My Lovely List',
      'trigger_field' => 'favorite_os',
      'trigger_value' => ''
    ));
  
    $this->assertIdentical(
      TRUE,
      $this->_subject->on_campaigner_should_subscribe_member(
        $member_data,
        $mailing_list
      )
    );
  }


  public function test__on_campaigner_should_subscribe_member__no_subscribe_with_empty_trigger_value()
  {
    $member_data = array(
      'email'         => 'steve@apple.com',
      'favorite_os'   => 'iOS',
      'screen_name'   => 'Steve Jobs'
    );

    $mailing_list = new Campaigner_mailing_list(array(
      'active'        => TRUE,
      'list_id'       => 'ABC123',
      'list_name'     => 'My Lovely List',
      'trigger_field' => 'favorite_os',
      'trigger_value' => ''
    ));
  
    $this->assertIdentical(
      FALSE,
      $this->_subject->on_campaigner_should_subscribe_member(
        $member_data,
        $mailing_list
      )
    );
  }


  public function test__on_campaigner_should_subscribe_member__no_subscribe_empty_member_field()
  {
    $member_data = array(
      'email'         => 'steve@apple.com',
      'favorite_os'   => '',
      'screen_name'   => 'Steve Jobs'
    );

    $mailing_list = new Campaigner_mailing_list(array(
      'active'        => TRUE,
      'list_id'       => 'ABC123',
      'list_name'     => 'My Lovely List',
      'trigger_field' => 'favorite_os',
      'trigger_value' => 'iOS'
    ));
  
    $this->assertIdentical(
      FALSE,
      $this->_subject->on_campaigner_should_subscribe_member(
        $member_data,
        $mailing_list
      )
    );
  }


  public function test__on_campaigner_should_subscribe_member__no_subscribe_missing_member_field()
  {
    $member_data = array(
      'email'         => 'steve@apple.com',
      'screen_name'   => 'Steve Jobs'
    );

    $mailing_list = new Campaigner_mailing_list(array(
      'active'        => TRUE,
      'list_id'       => 'ABC123',
      'list_name'     => 'My Lovely List',
      'trigger_field' => 'favorite_os',
      'trigger_value' => 'iOS'
    ));
  
    $this->assertIdentical(
      FALSE,
      $this->_subject->on_campaigner_should_subscribe_member(
        $member_data,
        $mailing_list
      )
    );
  }


  public function test__on_campaigner_should_subscribe_member__subscribe_with_empty_trigger_value_and_missing_member_field()
  {
    $member_data = array(
      'email'         => 'steve@apple.com',
      'screen_name'   => 'Steve Jobs'
    );

    $mailing_list = new Campaigner_mailing_list(array(
      'active'        => TRUE,
      'list_id'       => 'ABC123',
      'list_name'     => 'My Lovely List',
      'trigger_field' => 'favorite_os',
      'trigger_value' => ''
    ));
  
    $this->assertIdentical(
      TRUE,
      $this->_subject->on_campaigner_should_subscribe_member(
        $member_data,
        $mailing_list
      )
    );
  }


  public function test__update_extension__success()
  {
    $installed_version  = '1.1.1';
    $expected_result    = 'wibble';

    $this->_model->expectOnce('update_extension', array($installed_version));
    $this->_model->setReturnValue('update_extension', $expected_result);
  
    // Whatever the model returns should be passed along.
    $this->assertIdentical(
      $expected_result,
      $this->_subject->update_extension($installed_version)
    );
  }

}


/* End of file      : test.campaigner_multiselect_ext.php */
/* File location    : third_party/campaigner_multiselect/tests/
 *                    test.campaigner_multiselect_ext.php */
