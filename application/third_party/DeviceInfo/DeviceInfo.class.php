<?php
/**
 * Project:     DeviceInfo: Device Info Detecter
 * File:        DeviceInfo.class.php
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @link http://www.sevenmedia.jp/
 * @copyright 2011 Sevenmedia Inc.
 * @author Takeo Noda
 * @package DeviceInfo
 * @version 0.0.1
 */

/**
 * define shorthand directory separator constant
 */
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

/**
 * This is the main DeviceInfo class
 * @package DeviceInfo
 */
class DeviceInfo {

    /**
     * DeviceInfo version
     */
    const VERSION = 'DeviceInfo-0.0.1';

    /**
     * Initialize new Smarty object
     *
     */
    public function __construct()
    {
    }

	/**
	 * デバイスマッチパターン
	 * type(種別) => suffix(ファイル末尾子)
	 */
	protected $device_type = array();

	/**
	 * デバイス規約
	 * type(デバイス種別) => array(pattern(正規表現パターン) => array(name(端末種別), コード名マッチ位置))
	 */
	protected $rules = array();

	/**
	 * ロボット規約
	 * type(デバイス種別) => array(pattern(正規表現パターン) => array(name(端末種別), コード名マッチ位置))
	 */
	protected $robots = array();

	/**
	 * User Agent
	 */
	private $user_agent = "";

	/**
	 * Type
	 */
	private $category = "";

	/**
	 * Code
	 */
	private $code = "";

	/**
	 * Code
	 */
	private $robot_code = "";

	/**
	 * Type
	 */
	private $is_matches = array();

	/**
	 * Type
	 */
	private $is_robots = false;

	/**
	 * Set user agent
	 */
	public function set_user_agent($user_agent) {
		$this->user_agent = $user_agent;
	}

	/**
	 * Set user agent
	 */
	public function set_device_type($device_type) {
		$this->device_type = $device_type;
	}

	/**
	 * Set user agent
	 */
	public function set_rules($rules) {
		$this->rules = $rules;
	}

	/**
	 * Set user agent
	 */
	public function set_robots($robots) {
		$this->robots = $robots;
	}

	/**
	 *
	 */
	public function initialize() {
		foreach ($this->device_type as $type => $suffix) {
			if (!array_key_exists($type, $this->rules)) {
				continue;
			}
			$patterns = $this->rules[$type];
			foreach ($patterns as $pattern => $rule) {
				if (!preg_match($pattern, $this->user_agent, $matches)) {
					continue;
				}
				list($category, $order) = $rule;
				if (isset($matches[$order])) {
					$this->code = $matches[$order];
				}
				$this->category = $category;
				$this->is_matches[$type] = 1;
				break;
			}
		}

		// ロボットについてマッチ
		if (!is_array($this->robots)) {
			return;
		}
		foreach ($this->robots as $pattern => $rule) {
			if (preg_match($pattern, $this->user_agent, $matches)) {
				list($category, $order) = $rule;
				if (isset($matches[$order])) {
					$this->robots_code = $matches[$order];
				}
				$this->is_robots = true;
			}
		}
	}

    /**
    * 
    */
    public function get_code() {
		return $this->code;
    }

    /**
    * 
    */
    public function get_category() {
		return $this->category;
    }

    /**
    */
    public function is_robot() {
		return $this->is_robots;
    }

    /**
    */
    public function is_mobile() {
		return $this->is_matches('mobile');
    }

    /**
    */
    public function is_smartphone() {
		return $this->is_matches('smartphone');
    }

    /**
    */
    public function is_tablet() {
		return $this->is_matches('tablet');
    }

    /**
    */
	public function is_matches($key) {
		return isset($this->is_matches[$key]) ? $this->is_matches[$key] : 0;
	}

	/**
	 *
	 */
	public function get_user_agent() {
		return $this->user_agent;
	}
}

/**
 * DeviceInfo exception class
 * @package Smarty
 */
class DeviceInfoException extends Exception {
}

?>
