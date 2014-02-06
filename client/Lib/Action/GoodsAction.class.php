<?php

class GoodsAction extends Action
{

    public function inputPanel()
    {
    	$id = $this->_get("id");
        
    	$this->display();
    }
    
}

?>