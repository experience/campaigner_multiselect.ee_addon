<?php if ( ! defined('EXT')) exit('Invalid file request.');

/**
 * Campaigner Multi-Select model.
 *
 * @author          Stephen Lewis (http://github.com/experience/)
 * @copyright       Experience Internet
 * @package         Campaigner_multiselect
 * @version         0.1.0
 */

class Campaigner_multiselect_model extends CI_Model {

  private $_ee;
  private $_extension_class;
  private $_namespace;
  private $_package_name;
  private $_package_version;
  private $_site_id;


  /* --------------------------------------------------------------
   * PRIVATE METHODS
   * ------------------------------------------------------------ */

  /**
   * Returns a references to the package cache. Should be called
   * as follows: $cache =& $this->_get_package_cache();
   *
   * @access  private
   * @return  array
   */
  private function &_get_package_cache()
  {
    return $this->_ee->session->cache[$this->_namespace][$this->_package_name];
  }


  /* --------------------------------------------------------------
  * PUBLIC METHODS
  * ------------------------------------------------------------ */

  /**
   * Constructor.
   *
   * @access  public
   * @param   string    $package_name       Package name. Used for testing.
   * @param   string    $package_version    Package version. Used for testing.
   * @param   string    $namespace          Session namespace. Used for testing.
   * @return  void
   */
  public function __construct(
    $package_name = '',
    $package_version = '',
    $namespace = ''
  )
  {
    parent::__construct();

    // Load the OmniLogger class.
    if (file_exists(PATH_THIRD .'omnilog/classes/omnilogger.php'))
    {
      include_once PATH_THIRD .'omnilog/classes/omnilogger.php';
    }

    $this->_ee =& get_instance();

    $this->_namespace = $namespace
      ? strtolower($namespace)
      : 'experience';

    $this->_package_name = $package_name
      ? strtolower($package_name)
      : 'campaigner_multiselect';

    $this->_package_version = $package_version
      ? $package_version
      : '0.1.0';

    $this->_extension_class = ucfirst($this->_package_name) .'_ext';

    // Initialise the add-on cache.
    if ( ! array_key_exists($this->_namespace, $this->_ee->session->cache))
    {
      $this->_ee->session->cache[$this->_namespace] = array();
    }

    if ( ! array_key_exists(
      $this->_package_name,
      $this->_ee->session->cache[$this->_namespace])
    )
    {
      $this->_ee->session->cache[$this->_namespace]
        [$this->_package_name] = array();
    }
  }


  /**
   * Returns the package name.
   *
   * @access  public
   * @return  string
   */
  public function get_package_name()
  {
    return $this->_package_name;
  }


  /**
   * Returns the package theme folder URL, appending a forward slash if required.
   *
   * @access    public
   * @return    string
   */
  public function get_package_theme_url()
  {
    $theme_url = $this->_ee->config->item('theme_folder_url');
    $theme_url .= substr($theme_url, -1) == '/'
      ? 'third_party/'
      : '/third_party/';

    return $theme_url .$this->get_package_name() .'/';
  }


  /**
   * Returns the package version.
   *
   * @access  public
   * @return  string
   */
  public function get_package_version()
  {
    return $this->_package_version;
  }


  /**
   * Returns the site ID.
   *
   * @access  public
   * @return  int
   */
  public function get_site_id()
  {
    if ( ! $this->_site_id)
    {
      $this->_site_id = intval($this->_ee->config->item('site_id'));
    }

    return $this->_site_id;
  }


  /**
   * Installs the extension.
   *
   * @access  public
   * @param   array        $hooks        The extension hooks.
   * @return  bool
   */
  public function install_extension(Array $hooks = array())
  {
    if ( ! $hooks)
    {
      return;
    }

    foreach ($hooks AS $hook)
    {
      if ( ! is_string($hook))
      {
        return;
      }
    }

    $default_hook_data = array(
      'class'     => $this->_extension_class,
      'enabled'   => 'y',
      'hook'      => '',
      'method'    => '',
      'priority'  => '5',
      'settings'  => '',
      'version'   => $this->get_package_version()
    );

    foreach ($hooks AS $hook)
    {
      $this->_ee->db->insert('extensions', array_merge(
        $default_hook_data,
        array('hook' => $hook, 'method' => 'on_' .$hook)
      ));
    }
  }


  /**
   * Logs a message to OmniLog.
   *
   * @access  public
   * @param   string      $message        The log entry message.
   * @param   int         $severity       The log entry 'level'.
   * @return  void
   */
  public function log_message($message, $severity = 1)
  {
    if (class_exists('Omnilog_entry') && class_exists('Omnilogger'))
    {
      switch ($severity)
      {
        case 3:
          $notify = TRUE;
          $type   = Omnilog_entry::ERROR;
          break;

        case 2:
          $notify = FALSE;
          $type   = Omnilog_entry::WARNING;
          break;

        case 1:
        default:
          $notify = FALSE;
          $type   = Omnilog_entry::NOTICE;
          break;
      }

      $omnilog_entry = new Omnilog_entry(array(
        'addon_name'    => 'Campaigner_multiselect',
        'date'          => time(),
        'message'       => $message,
        'notify_admin'  => $notify,
        'type'          => $type
      ));

      Omnilogger::log($omnilog_entry);
    }
  }


  /**
   * Uninstalls the extension.
   *
   * @access    public
   * @return    void
   */
  public function uninstall_extension()
  {
    $this->_ee->db->delete('extensions',
      array('class' => $this->_extension_class));
  }


  /**
   * Updates the extension.
   *
   * @access  public
   * @param   string        $installed_version        The installed version.
   * @return  bool
   */
  public function update_extension($installed_version = '')
  {
    if ( ! $installed_version
      OR version_compare($installed_version, $this->get_package_version(), '>=')
    )
    {
      return FALSE;
    }

    $this->_ee->db->update(
      'extensions',
      array('version' => $this->get_package_version()),
      array('class'   => $this->_extension_class)
    );
  }


}


/* End of file      : campaigner_multiselect_model.php */
/* File location    : third_party/campaigner_multiselect/models/
 *                    campaigner_multiselect_model.php */
