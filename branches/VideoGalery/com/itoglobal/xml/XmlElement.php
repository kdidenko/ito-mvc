<?php

/**
 * @author kdidenko
 * XmlElement extends SimpleXMLElement 
 * providing additional methods missing for everydays needs.
 */
class XmlElement extends SimpleXMLElement {
	
	/**
	 * Copies all attributes from the source node into the current node. 
	 * @param SimpleXMLElement $srcNode source node
	 */
	public function copyAttributes($srcNode) {
		foreach ( $srcNode->attributes () as $key => $value ) {
			$this->addAttribute ( $key, ( string ) $value );
		}
	}
	
	public function copyChildren($srcNode) {
		if ($srcNode) {
			foreach ( $srcNode->children () as $child ) {
				$next = $this->addChild($child->getName(), (string) $child);
				$next->copyAttributes($child);
				$next->copyChildren($child);
			}
		}
	}

}

?>