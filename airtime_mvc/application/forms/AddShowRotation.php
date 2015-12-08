<?php

class Application_Form_AddShowRotation extends Zend_Form_SubForm  {

    public function init() {
        $this->setDecorators(
            array(
                array('ViewScript', array('viewScript' => 'form/add-show-rotation.phtml'))
            ));

        $schedulingType = new Zend_Form_Element_Radio('add_show_rotation_scheduling');
        $schedulingType->setRequired(false)
            ->setLabel(_('Scheduling:'))
            ->addMultiOptions(array(
                                  0 => 'Manual',
                                  1 => 'Rotation:'
                              ))
            ->setValue(0);
        $this->addElement($schedulingType);

        $rotations = new Zend_Form_Element_Select('add_show_rotations');
        $rotations->setRequired(false)
            ->setLabel(_("Rotation:"))
            ->setMultiOptions($this->getRotationList())
            ->setAttrib('class', 'input_select add_show_input_select');
        $this->addElement($rotations);

        $generation = new Zend_Form_Element_Radio('add_show_rotation_generate');
        $generation->setRequired(false)
            ->setLabel(_('Schedule tracks:'))
            ->addMultiOptions(array(
                                  0 => 'Now',
                                  1 => 'JIT'
                              ))
            ->setValue(0);
        $this->addElement($generation);
    }

    public function getRotationList() {
        $rotations = array(null => "Select a rotation",);
        $query = RotationQuery::create();
        foreach ($query->find() as $rotation) {
            $rotations[$rotation->getDbId()] = $rotation->getDbName();
        }

        return $rotations;
    }

    public function isValid($data) {
        $isValid = parent::isValid($data);

        if ($data["add_show_rotation_scheduling"] && empty($data["add_show_rotations"])) {
            return false;
        }
        return $isValid;
    }

    public function disable() {}
}
