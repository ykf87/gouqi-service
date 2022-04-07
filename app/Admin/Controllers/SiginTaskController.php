<?php

namespace App\Admin\Controllers;

use App\Models\SiginTask;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SiginTaskController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'SiginTask';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SiginTask());

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('User id'));
        $grid->column('product_id', __('Product id'));
        $grid->column('need_day', __('Need day'));
        $grid->column('sigin_day', __('Sigin day'));
        $grid->column('get_time', __('Get time'));
        $grid->column('status', __('Status'));

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
        $show = new Show(SiginTask::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('product_id', __('Product id'));
        $show->field('need_day', __('Need day'));
        $show->field('sigin_day', __('Sigin day'));
        $show->field('get_time', __('Get time'));
        $show->field('status', __('Status'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new SiginTask());

        $form->number('user_id', __('User id'));
        $form->number('product_id', __('Product id'));
        $form->number('need_day', __('Need day'))->default(7);
        $form->number('sigin_day', __('Sigin day'));
        $form->number('get_time', __('Get time'));
        $form->switch('status', __('Status'));

        return $form;
    }
}
