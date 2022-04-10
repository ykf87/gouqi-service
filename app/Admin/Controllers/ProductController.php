<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Admin\Actions\Post\SiginPost;

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
        $grid->model()->orderByDesc('id');

        $grid->column('id', __('Id'))->filter('range');
        $grid->column('title', __('产品名称'))->editable()->filter('like');
        $grid->column('price', __('市场价'))->editable()->filter('range')->sortable();
        $grid->column('sale', __('售价'))->editable()->filter('range')->sortable();
        $grid->column('pricein', __('进货价'))->hide()->editable()->filter('range')->sortable();
        $grid->column('desc', __('简介'))->hide()->display(function($val){
            $val    = preg_replace('`<.+?>`', '', $val);
            $len    = mb_strlen($val, 'utf-8');
            if($len > 10){
                $val    = mb_substr(trim($val), 0, 10, 'utf-8') . '...';
            }
            return $val;
        });
        $grid->column('cover', __('首图'))->image(50,50);
        $grid->column('images', __('图集'))->hide();
        $grid->column('selled', __('已售'))->editable()->filter('range')->sortable();
        $grid->column('main_sendout', __('已送出'))->hide()->editable()->filter('range')->sortable();
        $grid->column('main_maxown', __('最多领取'))->editable()->filter('range')->sortable();
        $grid->column('main_collection', __('收藏人数'))->hide()->editable()->filter('range')->sortable();
        $grid->column('main_stock', __('总库存'))->editable()->filter('range')->sortable();
        $grid->column('main_status', __('状态'))->using(Product::$status)->label(Product::$statusLabel)->filter(Product::$status);
        $grid->column('url', __('Url'))->hide()->editable();

        $grid->actions(function ($actions) {
            $actions->add(new SiginPost);
        });
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
        // $show = new Show(Product::findOrFail($id));

        // $show->field('id', __('Id'));
        // $show->field('title', __('Title'));
        // $show->field('price', __('Price'));
        // $show->field('sale', __('Sale'));
        // $show->field('pricein', __('Pricein'));
        // $show->field('desc', __('Desc'));
        // $show->field('cover', __('Cover'));
        // $show->field('images', __('Images'));
        // $show->field('selled', __('Selled'));
        // $show->field('main_sendout', __('Main sendout'));
        // $show->field('main_maxown', __('Main maxown'));
        // $show->field('main_collection', __('Main collection'));
        // $show->field('main_stock', __('Main stock'));
        // $show->field('main_status', __('Main status'));
        // $show->field('url', __('Url'));

        // return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Product());

        $form->text('title', __('产品标题'));
        $form->decimal('price', __('市场价'))->default(0.00);
        $form->decimal('sale', __('售价'))->default(0.00);
        $form->decimal('pricein', __('进货价'))->default(0.00);
        $form->textarea('desc', __('简介'));
        $form->image('cover', __('封面'));
        $form->multipleImage('images', __('图集'));
        $form->number('selled', __('已售'));
        $form->number('main_sendout', __('已送出'));
        $form->number('main_maxown', __('最多领取'))->default(1);
        $form->number('main_collection', __('已收藏'));
        $form->number('main_stock', __('总库存'));
        $form->switch('main_status', __('状态'))->default(1);
        $form->url('url', __('Url'));

        return $form;
    }
}
