<?php

class nav {
	protected $arr = array();

	function __construct($array = array()) {
		if (!empty($array))
			$this->parse($array, true);
	}

	public function addLink($link, $text = false) {
		$this->arr[] = new link($link, $text);
	}

	public function parse($array, $keep = false) {
		$new_array = array();
		foreach ($array as $key => $value) {
			$new_array[] = new link($key, $value);
		}

		return $keep ? $this->arr = $new_array : $new_array;
	}

	public function output($links = false, $dropdowns = true) {
		$links = is_array($links) ? $this->parse($links) : $this->arr;
		$active = $this->getActive(true);

		foreach ($links as $link) {
			echo $link->getHTML($active, $dropdowns);
		}
	}

	public function getActive($recursive = false) {
		$p = empty($_GET['p']) ? trim(get_path(), '/') : str_replace('./', '/', $_GET['p']);
		if (!$p) $p = 'home';
		if (!empty($_GET['p']) && file_exists(TEMPLATEPATH . '/pages/' . $p . '.php'))
			return $p;
		
		foreach ($this->arr as $key => $link) {
			if ($a = $link->isActive($p))
				return $recursive ? $a : $p;
		}

		return 'home';
	}
}

class link {
	const	TYPE_LINK = 0,
			TYPE_DROPDOWN = 1;
	private $type = 0,
			$arr = array(),
			$link = "",
			$text = "";

	function __construct($link, $text = false) {
		if (is_array($text)) {
			$this->type = self::TYPE_DROPDOWN;
			foreach ($text as $key => $value) {
				$this->arr[] = new link($key, $value);
			}
			return;
		}

		if (!$link || is_int($link)) {
			$link = $text;
			$text = ucwords(trim($link, '/'));
		} elseif ($text === false) {
			$text = ucwords(trim($link, '/'));
		}

		$this->type = self::TYPE_LINK;
		$this->link = $link;
		$this->text = $text;
	}

	function getLink() {
		if ($this->type == self::TYPE_DROPDOWN)
			return $this->arr[0]->getLink();

		return $this->link;
	}

	function getText() {
		return $this->text;
	}

	function getHTML($link = false, $dropdowns = true) {
		switch ($this->type) {
			case self::TYPE_DROPDOWN:
				if (!$dropdowns)
					return $this->arr[0]->getHTML($link);

				$html = '<div>' .
							$this->arr[0]->getHTML($link) .
							'<div>';
				foreach ($this->arr as $key => $value) {
					if ($key == 0) continue;
					$html .= $this->arr[$key]->getHTML();
				}
				$html .= 	'</div>' .
						'</div>';
				return $html;
				break;

			case self::TYPE_LINK:
				$trimmed = trim($this->link, '/');
				$active = '';
				if ($trimmed == trim($link, '/') || ($trimmed == '.' && $link == 'home'))
					$active = ' class="active"';
				return '<a href="' . $this->link . '"' . $active . '>' . $this->text . '</a>';
				break;
		}
	}

	public function isActive($p) {
		if ($this->type == self::TYPE_LINK)
			return trim($this->link, '/') == $p ? $p : false;

		foreach ($this->arr as $key => $link) {
			if (trim($link->getLink(), '/') == $p)
				return trim($this->arr[0]->getLink(), '/');
		}
	}
}

?>