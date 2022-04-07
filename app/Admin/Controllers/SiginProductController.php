<?php

namespace App\Admin\Controllers;

use App\Models\SiginProduct;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SiginProductController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'SiginProduct';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SiginProduct());

        $grid->column('id', __('Id'));
        $grid->column('product_id', __('Product id'));
        $grid->column('start_time', __('Start time'));
        $grid->column('end_time', __('End time'));
        $grid->column('max_own', __('Max own'));
        $grid->column('sendout', __('Sendout'));
        $grid->column('days', __('Days'));
        $grid->column('stocks', __('Stocks'));
        $grid->column('sortby', __('Sortby'));
        $grid->column('collection', __('Collection'));
        $grid->column('timeout_days_allower', __('Timeout days allower'));
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
        $show = new Show(SiginProduct::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('product_id', __('Product id'));
        $show->field('start_time', __('Start time'));
        $show->field('end_time', __('End time'));
        $show->field('max_own', __('Max own'));
        $show->field('sendout', __('Sendout'));
        $show->field('days', __('Days'));
        $show->field('stocks', __('Stocks'));
        $show->field('sortby', __('Sortby'));
        $show->field('collection', __('Collection'));
        $show->field('timeout_days_allower', __('Timeout days allower'));
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
        $form = new Form(new SiginProduct());

        $form->number('product_id', __('Product id'));
        $form->number('start_time', __('Start time'));
        $form->number('end_time', __('End time'));
        $form->number('max_own', __('Max own'))->default(1);
        $form->number('sendout', __('Sendout'));
        $form->number('days', __('Days'))->default(7);
        $form->number('stocks', __('Stocks'));
        $form->number('sortby', __('Sortby'));
        $form->number('collection', __('Collection'));
        $form->number('timeout_days_allower', __('Timeout days allower'))->default(2);
        $form->switch('status', __('Status'))->default(1);

        return $form;
    }
}
