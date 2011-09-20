<?php if ( ! defined('EXT')) exit('Invalid file request.');

/**
 * Campaigner Multi-Select extension.
 *
 * @author          Stephen Lewis (http://github.com/experience/)
 * @copyright       Experience Internet
 * @package         Campaigner_multiselect
 */

class Campaigner_multiselect_ext {

  private $_ee;
  private $_model;

  public $description;
  public $docs_url;
  public $name;
  public $settings;
  public $settings_exist;
  public $version;


  /* --------------------------------------------------------------
   * PUBLIC METHODS
   * ------------------------------------------------------------ */
  
  /**
   * Constructor.
   *
   * @access  public
   * @param   mixed    $settings    Settings array or empty string if non exist.
   * @return  void
   */
  public function __construct($settings = '')
  {
    $this->_ee =& get_instance();
    $this->_ee->load->model('campaigner_multiselect_model');

    $this->_model         = $this->_ee->campaigner_multiselect_model;

    $this->description    = $this->_ee->lang('ext_description');
    $this->docs_url       = 'http://experienceinternet.co.uk/software/campaigner';
    $this->name           = $this->_ee->lang('ext_name');
    $this->settings       = $settings;
    $this->settings_exist = 'n';
    $this->version        = $this->_model->get_package_version();
  }


  /**
   * Activates the extension.
   *
   * @access  public
   * @return  void
   */
  public function activate_extension()
  {
    $this->_model->install_extension(array(
      'campaigner_should_subscribe_member'
    ));
  }


  /**
   * Disables the extension.
   *
   * @access  public
   * @return  void
   */
  public function disable_extension()
  {
    $this->_model->uninstall_extension();
  }


  /**
   * Handles the `campaigner_should_subscribe_member` extension hook.
   *
   * @access  public
   * @param   Array                     $member_data    Associative array of
   *                                                    member data.
   * @param   Campaigner_mailing_list   $mailing_list   The mailing list.
   * @return  bool
   */
  public function on_campaigner_should_subscribe_member(
    Array $member_data,
    Campaigner_mailing_list $mailing_list
  )
  {
    
  }


  /**
   * Updates the extension.
   *
   * @access  public
   * @param   string    $installed_version    The currently-installed version.
   * @return  void|FALSE
   */
  public function update_extension($installed_version = '')
  {
    return $this->_model->update_extension($installed_version);
  }


}


/* End of file      : ext.campaigner_multiselect.php */
/* File location    : third_party/campaigner_multiselect/
 *                    ext.campaigner_mutliselect.php */
