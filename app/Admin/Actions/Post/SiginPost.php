<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\SiginProduct;

class SiginPost extends RowAction
{
    public $name = '签到';

    public function handle(Model $model, Request $request){
    	$add 					= false;
    	$row 	= SiginProduct::where('product_id', $model->id)->first();
    	if(!$row){
    		$row 	= new SiginProduct;
    		$row->product_id 	= $model->id;
    		$add 				= true;
    	}
    	$row->start_time 			= $request->input('start_time');
    	$row->end_time 				= $request->input('end_time');
    	$row->max_own 				= $request->input('max_own');
    	$row->days 					= $request->input('days');
    	$row->stocks 				= $request->input('stocks');
    	$row->collection 			= $request->input('collection');
    	$row->timeout_days_allower 	= $request->input('timeout_days_allower');
    	$row->sortby 				= $request->input('sortby');
    	$row->sendout 				= $request->input('sendout');
    	$row->status 				= 1;
    	// dd($row);
    	if($row->save()){
    		return $this->response()->success($add ? '添加成功':'更新成功!')->refresh();
    	}else{
    		return $this->response()->error($add ? '添加失败':'更新失败!');
    	}
    }
    public function form(Model $model){
    	$row 	= SiginProduct::where('product_id', $model->id)->first();
    	if($row){
    		$this->datetime('start_time', '开始时间')->default($row->start_time > 0 ? $row->start_time : '');
	    	$this->datetime('end_time', '结束时间')->default($row->end_time > 0 ? $row->end_time : '');
	    	$this->text('max_own', '最多领取')->default($row->max_own ?? '');
	    	$this->text('days', '签到天数')->default($row->days ?? '');
	    	$this->text('stocks', '库存')->default($row->stocks ?? '');
	    	$this->text('collection', '收藏人数')->default($row->collection ?? '');
	    	$this->text('timeout_days_allower', '补签天数')->default($row->timeout_days_allower ?? '');
	    	$this->text('sortby', '排序')->default($row->sortby ?? '');
	    	$this->text('sendout', '已赠送')->default($row->sendout ?? '');
    	}else{
    		$this->datetime('start_time', '开始时间');
	    	$this->datetime('end_time', '结束时间');
	    	$this->text('max_own', '最多领取');
	    	$this->text('days', '签到天数');
	    	$this->text('stocks', '库存')->default(1);
	    	$this->text('collection', '收藏人数')->default(1);
	    	$this->text('timeout_days_allower', '补签天数')->default(2);
	    	$this->text('sortby', '排序')->default(0);
	    	$this->text('sendout', '已赠送')->default(10);
    	}
	}

}