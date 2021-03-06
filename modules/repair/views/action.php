<?php
/**
 * @filesource modules/repair/views/action.php
 *
 * @see http://www.kotchasan.com/
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Repair\Action;

use Gcms\Login;
use Kotchasan\Html;

/**
 * บันทึกการรับซ่อม
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\View
{
    /**
     * แสดงฟอร์ม Modal สำหรับการปรับสถานะการทำรายการ.
     *
     * @param object $index
     * @param array  $login
     *
     * @return string
     */
    public function render($index, $login)
    {
        // ผู้ดูแลระบบ
        $isAdmin = Login::isAdmin();
        // register form
        $form = Html::create('form', array(
            'id' => 'setup_frm',
            'class' => 'setup_frm',
            'autocomplete' => 'off',
            'action' => 'index.php/repair/model/action/submit',
            'onsubmit' => 'doFormSubmit',
            'ajax' => true,
            'token' => true,
        ));
        $fieldset = $form->add('fieldset', array(
            'titleClass' => 'icon-tools',
            'title' => '{LNG_Update repair status} {LNG_Receipt No.} '.$index->job_id,
        ));
        // status
        $fieldset->add('select', array(
            'id' => 'status',
            'labelClass' => 'g-input icon-star0',
            'itemClass' => 'item',
            'label' => '{LNG_Repair status}',
            'options' => \Repair\Status\Model::create()->toSelect(),
            'value' => $index->status,
        ));
        // comment
        $fieldset->add('textarea', array(
            'id' => 'comment',
            'labelClass' => 'g-input icon-comments',
            'itemClass' => 'item',
            'label' => '{LNG_Comment}',
            'comment' => '{LNG_Note or additional notes}',
            'rows' => 5,
        ));
        if (Login::checkPermission($login, 'can_received_repair')) {
            // operator_id
            $fieldset->add('select', array(
                'id' => 'operator_id',
                'labelClass' => 'g-input icon-customer',
                'itemClass' => 'item',
                'label' => '{LNG_Operator}',
                'options' => \Repair\Operator\Model::create()->toSelect(),
                'value' => $index->operator_id,
            ));
        }
        // cost
        $fieldset->add('currency', array(
            'id' => 'cost',
            'labelClass' => 'g-input icon-money',
            'itemClass' => 'item',
            'label' => '{LNG_Cost}',
            'comment' => '{LNG_Fill in the repair costs you want to inform the customer}',
            'value' => $index->cost,
        ));
        $fieldset = $form->add('fieldset', array(
            'class' => 'submit',
        ));
        // submit
        $fieldset->add('submit', array(
            'id' => 'save',
            'class' => 'button save large icon-save',
            'value' => '{LNG_Save}',
        ));
        // repair_id
        $fieldset->add('hidden', array(
            'id' => 'repair_id',
            'value' => $index->id,
        ));

        return $form->render();
    }
}
