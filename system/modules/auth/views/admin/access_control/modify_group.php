<h2><?=$header?></h2>

<?=form_open('auth/admin/acl_groups/modify',array('class'=>'horizontal'))?>
    <fieldset>
        <ol>
            <li>
                <?=form_label($this->lang->line('access_name'),'name')?>
                <?=form_input('name',$this->validation->name,'class="text" readonly')?>
            </li>
            <li>
                <?=form_label($this->lang->line('access_disabled'),'disabled')?>
                Yes <?=form_radio('disabled','1',$this->validation->set_radio('disabled','1'))?>
                No <?=form_radio('disabled','0',$this->validation->set_radio('disabled','0'))?>
            </li>
            <li>
                <?=form_label($this->lang->line('access_parent_name'),'parent')?>
                <?php 
                    // Output nested resource view
                    $resourceObj = & $this->access_control_model->group;  
                    $tree = $resourceObj->getTreePreorder($resourceObj->getRoot());
                    $resources = array();
                    while($resourceObj->getTreeNext($tree)):
                        $lvl = $resourceObj->getTreeLevel($tree);
                        $offset = '';
                        
                        // Nest the tree
                        if($lvl > 1){
                            $ancestor = $resourceObj->getAncestor($tree['row']);
                            while( ! $resourceObj->checkNodeIsRoot($ancestor))
                            {
                                if($resourceObj->checkNodeHasNextSibling($ancestor)):
                                    // Ancestor has sibling so put a | in offset
                                    $offset = "|&nbsp;&nbsp; " . $offset;
                                else:
                                    // No next sibling just put space
                                    $offset = "&nbsp;&nbsp;&nbsp; " . $offset;   
                                endif; 
                                $ancestor = $resourceObj->getAncestor($ancestor);                            
                            }
                        }                 
                        
                        // If this is the last node add branch terminator
                        if($resourceObj->checkNodeHasNextSibling($tree['row']))
                            $offset .= "|- ";
                        elseif($lvl != 0)
                            $offset .= "'- ";                        
                        
                        $resources[$tree['row']['name']] = $offset.$tree['row']['name'];
                    endwhile;
                    ?>
                    <?=form_dropdown('parent',$resources,$this->validation->parent,'size=10 style="width:20.3em;"')?>
            </li>
            <li class="submit">
                <?=form_hidden('id',$this->validation->id)?>
                <?=form_submit('submit',$this->lang->line('access_save'))?>
            </li>
        </ol>
    </fielset>
<?=form_close()?>
