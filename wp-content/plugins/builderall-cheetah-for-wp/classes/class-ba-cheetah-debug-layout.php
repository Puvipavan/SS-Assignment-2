<?php
class BACheetahDebugLayout {

	private $data;

	private $status;

	private $postID;

	function __construct($postID, $status = 'draft') {
        $this->status = $status;
		$this->postID = $postID;
		$this->level = 0;
		$this->levels = [];
		$this->data = BACheetahModel::get_layout_data($this->status, $this->postID);

		$this->debug();
    }

	public function debug() {
		$root = $this->search_childs_by_parent(NULL); //root dont have parent
		echo $this->disply_nodes($root);	
	}
	
	public function disply_nodes($nodes) {
		$text = '';

		foreach ($nodes as $key => $node) {

			if (array_key_exists($node->parent, $this->levels)) {
				$this->level = $this->levels[$node->parent];
			}else  {
				$this->levels[$node->parent] = $this->level;
			}

			$text .= join([
				str_repeat('|---', $this->level),
				$node->type,
				($node->type == "module") ? ' - '.($node->settings->type) : '', ' - ',
				$node->node,
				"(parent $node->parent)",
				'<br>'
			]);

			$nodes = $this->search_childs_by_parent($node->node);

			if (!empty($nodes)) {
				$this->level ++;
				$text .= $this->disply_nodes($nodes);
			}
		}
		return ($text);
	} 
	
	public function search_childs_by_parent($find) {
		return array_filter($this->data, function($node) use ($find) {
			return $node->parent === $find;
		});
	}
}
?>

