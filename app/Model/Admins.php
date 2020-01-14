<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Admins extends Model
{
       protected $table = 'admins';
	 protected $primaryKey = 'admin_id';

	  /**
	 * 表明模型是否应该被打上时间戳
	 *
	 * @var bool
	 */
	 public $timestamps = false;

	 /**
	  * 白名单
	 * 可以被批量赋值的属性.
	 *
	 * @var array
	 */
	// protected $fillable = ['brand_name','brand_url','brand_logo','brand_desc'];

	 /**
	 * 不能被批量赋值的属性
	 *
	 * @var array
	 */
	 protected $guarded = [];
}
