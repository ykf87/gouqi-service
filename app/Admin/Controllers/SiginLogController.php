<?php

namespace App\Admin\Controllers;

use App\Models\SiginLog;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SiginLogController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'SiginLog';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SiginLog());

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('User id'));
        $grid->column('sigin_task_id', __('Sigin task id'));
        $grid->column('product_id', __('Product id'));
        $grid->column('index', __('Index'));
        $grid->column('sigin_time', __('Sigin time'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(SiginLog::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('sigin_task_id', __('Sigin task id'));
        $show->field('product_id', __('Product id'));
        $show->field('index', __('Index'));
        $show->field('sigin_time', __('Sigin time'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new SiginLog());

        $form->number('user_id', __('User id'));
        $form->number('sigin_task_id', __('Sigin task id'));
        $form->number('product_id', __('Product id'));
        $form->number('index', __('Index'));
        $form->number('sigin_time', __('Sigin time'));

        return $form;
    }
}
