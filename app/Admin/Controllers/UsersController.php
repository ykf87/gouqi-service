<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UsersController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'User';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        $grid->column('id', __('Id'));
        $grid->column('openid', __('Openid'));
        $grid->column('parent', __('Parent'));
        $grid->column('parent_chian', __('Parent chian'));
        $grid->column('username', __('Username'));
        $grid->column('phone', __('Phone'));
        $grid->column('avatar', __('Avatar'));
        $grid->column('level', __('Level'));
        $grid->column('sex', __('Sex'));
        $grid->column('sigin', __('Sigin'));
        $grid->column('status', __('Status'));
        $grid->column('view_ratio', __('View ratio'));
        $grid->column('percentage_ratio', __('Percentage ratio'));
        $grid->column('recommended_ratio', __('Recommended ratio'));
        $grid->column('pwd', __('Pwd'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('name', __('Name'));
        $grid->column('email', __('Email'));
        $grid->column('email_verified_at', __('Email verified at'));
        $grid->column('password', __('Password'));
        $grid->column('remember_token', __('Remember token'));

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
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('openid', __('Openid'));
        $show->field('parent', __('Parent'));
        $show->field('parent_chian', __('Parent chian'));
        $show->field('username', __('Username'));
        $show->field('phone', __('Phone'));
        $show->field('avatar', __('Avatar'));
        $show->field('level', __('Level'));
        $show->field('sex', __('Sex'));
        $show->field('sigin', __('Sigin'));
        $show->field('status', __('Status'));
        $show->field('view_ratio', __('View ratio'));
        $show->field('percentage_ratio', __('Percentage ratio'));
        $show->field('recommended_ratio', __('Recommended ratio'));
        $show->field('pwd', __('Pwd'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->field('email_verified_at', __('Email verified at'));
        $show->field('password', __('Password'));
        $show->field('remember_token', __('Remember token'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User());

        $form->text('openid', __('Openid'));
        $form->number('parent', __('Parent'));
        $form->textarea('parent_chian', __('Parent chian'));
        $form->text('username', __('Username'));
        $form->number('phone', __('Phone'));
        $form->image('avatar', __('Avatar'));
        $form->text('level', __('Level'))->default('1');
        $form->switch('sex', __('Sex'));
        $form->number('sigin', __('Sigin'));
        $form->switch('status', __('Status'))->default(1);
        $form->decimal('view_ratio', __('View ratio'))->default(1.000);
        $form->decimal('percentage_ratio', __('Percentage ratio'))->default(1.000);
        $form->decimal('recommended_ratio', __('Recommended ratio'))->default(1.000);
        $form->password('pwd', __('Pwd'));
        $form->text('name', __('Name'));
        $form->email('email', __('Email'));
        $form->datetime('email_verified_at', __('Email verified at'))->default(date('Y-m-d H:i:s'));
        $form->password('password', __('Password'));
        $form->text('remember_token', __('Remember token'));

        return $form;
    }
}
