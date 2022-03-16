<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ProductController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Product';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Product());

        $grid->column('id', __('Id'));
        $grid->column('type', __('商品类型'));
        $grid->column('title', __('Title'));
        $grid->column('price', __('Price'));
        $grid->column('sale', __('Sale'));
        $grid->column('pricein', __('Pricein'));
        $grid->column('desc', __('Desc'));
        $grid->column('cover', __('Cover'));
        $grid->column('images', __('Images'));
        $grid->column('days', __('Days'));
        $grid->column('sendout', __('Sendout'));
        $grid->column('maxown', __('Maxown'));
        $grid->column('collection', __('Collection'));
        $grid->column('probability', __('Probability'));
        $grid->column('guanggao', __('Guanggao'));
        $grid->column('stock', __('Stock'));
        $grid->column('sort', __('Sort'));
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
        $show = new Show(Product::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('type', __('Type'));
        $show->field('title', __('Title'));
        $show->field('price', __('Price'));
        $show->field('sale', __('Sale'));
        $show->field('pricein', __('Pricein'));
        $show->field('desc', __('Desc'));
        $show->field('cover', __('Cover'));
        $show->field('images', __('Images'));
        $show->field('days', __('Days'));
        $show->field('sendout', __('Sendout'));
        $show->field('maxown', __('Maxown'));
        $show->field('collection', __('Collection'));
        $show->field('probability', __('Probability'));
        $show->field('guanggao', __('Guanggao'));
        $show->field('stock', __('Stock'));
        $show->field('sort', __('Sort'));
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
        $form = new Form(new Product());

        $form->number('type', __('Type'));
        $form->text('title', __('Title'));
        $form->decimal('price', __('Price'))->default(0.00);
        $form->decimal('sale', __('Sale'))->default(0.00);
        $form->decimal('pricein', __('Pricein'))->default(0.00);
        $form->textarea('desc', __('Desc'));
        $form->image('cover', __('Cover'));
        $form->textarea('images', __('Images'));
        $form->number('days', __('Days'));
        $form->number('sendout', __('Sendout'));
        $form->number('maxown', __('Maxown'))->default(1);
        $form->number('collection', __('Collection'));
        $form->switch('probability', __('Probability'));
        $form->switch('guanggao', __('Guanggao'));
        $form->number('stock', __('Stock'));
        $form->number('sort', __('Sort'));
        $form->switch('status', __('Status'));

        return $form;
    }
}
