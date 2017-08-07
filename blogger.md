#####blog_personal(个人中心)

|	名字	|	类型	| 长度 | 索引	 | 默认值	 |	自增	|  注释 |
|---		|	---		| ---	| --- 	 | ---	 |---| --- |
| pers_id	| int(10)Unsigned|NO| PRI	 |null | auto_increment | 详情id |
| pers_name   | varchar | 255 |  |  |  | 管理员昵称 |
| user_id	  | int(20)Unsigned|NO|MUL	 |	0  |  | 用户表id|
| pers_phone  | varchar | 255 |  |  |  | 手机号 |
| pers_email  | varchar | 255 |  |  |  | 邮箱   |
| pers_sex    | varchar | 255 |  |  |  | 性别   |
| pers_shuo   | varchar | 255 |  |  |  | 心情   |
| pers_friends| varchar | 255 |  |  |  | 粉丝人数|
| pers_city   | varchar | 255 |  |  |  | 地址   |
| pers_age    | varchar | 255 |  |  |  | 年龄   |
| pers_avatar | varchar | 255 |  |  |  | 头像   |
| created_at  | datetime| 0   |  |  |  | 创建时间|
| updated_at  | datetime| 0   |  |  |  | 修改时间|

#####blog_user(用户)

|	名字	|	类型	| 长度 | 索引	 | 默认值	 |	自增	|  注释 |
|---|---| ---| --- | --- |---|---|
| user_id    | int(11)Unsigned |NO| PRI|null | auto_increment |用户id|
| user_name  | varchar | 50 | UNI | | |登录名   |
| user_pass  | varchar | 255|     | | |登录密码 |
| create_at  |timestamp| 0  |     | | |创建时间 |
| updated_at |timestamp| 0  |     | | |修改时间 |
| user_email | varchar | 255|	  | | |邮箱     |
| user_ctime |   int   | 10 |     | | |创建时间 |
| user_token | varchar | 255|     | | |token值  |
| user_status| varchar | 255|     | | |状态值   |

#####blog_config(网站配置)
|	名字	|	类型	| 长度 | 索引	 | 默认值	 |	自增	|  注释 |
|---|---| ---| --- | --- |---|---|
|conf_id|int Unsigned | 11 | PRI| |  auto_increment| 配置id |
|conf_title  |varchar| 50 | | | |网站标题|
|conf_name   |varchar| 50 | | | |配置名 |
|conf_content|text   | 0  | | | |内容   |
|conf_order  |int    | 11 | | | |排序   |
|conf_tips   |varchar| 255| | | |说明   |
|field_type  |varchar| 50 | | | |类型   |
|field_value |varchar| 255| | | |值     |
|created_at  |timestamp| 0| | | |创建时间|
| updated_at |timestamp| 0| | | |修改时间|
|delete_at   |timestamp| 0| | | |删除时间|

#####blog_roles(角色)
|	名字	|	类型	| 长度 | 索引	 | 默认值	 |	自增	|  注释 |
|---|---| ---| --- | --- |---|---|
|role_id|int Unsigned | 11 | PRI| |  auto_increment| 角色id |
|    name     | varchar | 50 | | | |角色名 |
| description | varchar | 255| | | |描述   |
| created_at  |timestamp| 0  | | | |创建时间|
| updated_at  |timestamp| 0  | | | |修改时间|

#####blog_role_user(用户角色)
|	名字	|	类型	| 长度 | 索引	 | 默认值	 |	自增	|  注释 |
|---|---| ---| --- | --- |---|---|
|user_id|int Unsigned | 11 | | | | 用户id |
|role_id|int Unsigned | 11 | | | | 角色id |

#####blog_permissions(权限)
|	名字	|	类型	| 长度 | 索引	 | 默认值	 |	自增	|  注释 |
|---|---| ---| --- | --- |---|---|
|id|int Unsigned | 11 | PRI| |  auto_increment| 权限id |
|    name     | varchar | 50 | | | |角色名 |
| description | varchar | 255| | | |描述   |
| created_at  |timestamp| 0  | | | |创建时间|
| updated_at  |timestamp| 0  | | | |修改时间|

#####blog_permission_role(角色权限)
|	名字	|	类型	| 长度 | 索引	 | 默认值	 |	自增	|  注释 |
|---|---| ---| --- | --- |---|---|
|permission_id|int Unsigned | 11 | | | | 用户id |
|role_id      |int Unsigned | 11 | | | | 角色id |

#####blog_sub(订阅)
|	名字	|	类型	| 长度 | 索引	 | 默认值	 |	自增	|  注释 |
|---|---| ---| --- | --- |---|---|
|sub_id    |int	   |11 |PRI| auto_increment | |订阅id |
|sub_email |varchar|255| not null| || 订阅邮箱|
|sub_token |varchar|255| | |		| 订阅token|
|created_at|timestamp|0| | | 		|创建时间|
|updated_at|timestamp|0| | | 		|修改时间|
|delete_at |timestamp|0| | | 		|删除时间|


#####blog_use_details(前台用户中心)
|	名字	|	类型	| 长度 | 索引	 | 默认值	 |	自增	|  注释 |
|---|---| ---| --- | --- |---|---|
|det_id   |int    |10 |PRI | auto_increment | |用户中心id|
|det_sex  |tinyint|2  |    |			    | |性别|
|det_name |varchar|255|	   |		   	    | |名字|
|det_birth|varchar|20 |	   |		  		| |生日|
|det_phone|char   |11 |	   |			    | |手机|


####blog_yonghu(留言)
|	名字	|	类型	| 长度 | 索引	 | 默认值	 |	自增	|  注释 |
|---|---| ---| --- | --- |---|---|
|y_id	|int unsigned|10|PRI|NULL|auto_increment|留言id|
|user_id|int		 |10|   |NULL|      		|用户id|
|y_dis  |varchar     |255|  |NULL|  			|留言内容|
|y_time |int		 |10 |  |  	 |				|留言时间|
|y_re	|varchar	 |255|  |    | 			    |回复内容|
|re_time|int		 |10 |  |    | 			    |回复时间|
|y_status|int		 |1  |  |NULL|  			|状态|


####blog_zan(点赞)
|	名字	|	类型	| 长度 | 索引	 | 默认值	 |	自增	|  注释 |
|---|---| ---| --- | --- |---|---|
|zan_id|int unsigned|11 |PRI|NULL|auto_increment|点赞id|
|art_id|int			|11 |	|	 |				|文章id|
|user_id|int		|11 |   |	 |				|用户id|


####blog_adv(广告)
|	名字	|	类型	| 长度 | 索引	 | 默认值	 |	自增	|  注释 |
|---|---| ---| --- | --- |---|---|
|adv_id|int unsigned|10|PRI|NULL|auto_increment|广告id|
|adv_name  |  varchar|20 ||||广告名字|
|adv_title |  varchar|255||||广告标题|
|adv_img   |  varchar|255||||广告图片|
|adv_link  |  varchar|255||||广告链接|
|adv_time  |  int	 |10 ||||广告时间|
|created_at|timestamp|255||||创建时间|
|updated_at|timestamp| 0 ||||修改时间|
|adv_order |  int    |3  ||||排序   |

####blog_article(文章)
|	名字	|	类型	| 长度 | 索引	 | 默认值	 |	自增	|  注释 |
|---|---| ---| --- | --- |---|---|
|art_id    |int unsigned|11 |PRI|NULL|auto_increment|文章id|
|art_title |varchar     |100||||文章标题|
|art_tag   |varchar     |100||||关键字  |
|art_description|varchar|255||||描述    |
|art_thumb |varchar     |255||||图片    |
|art_content|text       |0  ||||内容    |
|art_time  |varchar     |255||||发表时间|
|art_editor|varchar     |50 ||||作者    |
|art_view  |int         |11 ||||点击量  |
|cate_id   |int 		|11 ||||分类id  |
|created_at|timestamp   |0  ||||创建时间|
|updated_at|timestamp   |0  ||||修改时间|
|delete_at |timestamp   |0  ||||删除时间|

####blog_discuss(评论)
|	名字	|	类型	| 长度 | 索引	 | 默认值	 |	自增	|  注释 |
|---|---| ---| --- | --- |---|---|
|dis_id     |int unsigned|11 |PRI|NULL|auto_increment|留言id|
|art_id     |int         |10 ||||文章id   |
|dis_content|varchar     |255||||留言内容|
|dis_time   |varchar     |50 ||||留言时间|
|dis_reply  |varchar     |255||||留言回复|
|re_time    |timestamp   |0  ||||回复时间|
|dis_status |int   		 |1  ||||留言状态|
|re_id		|int		 |11 ||||回复id |
|user_id	|int 		 |11 ||||用户id |
|created_at |timestamp   |0  ||||创建时间|
|updated_at |timestamp   |0  ||||修改时间|
|delete_at  |timestamp   |0  ||||删除时间|

####blog_category(分类)
|	名字	|	类型	| 长度 | 索引	 | 默认值	 |	自增	|  注释 |
|---|---| ---| --- | --- |---|---|
|cate_id    |int unsigned|11 |PRI|NULL|auto_increment|分类id|
|cate_name  |varchar     |50 ||||分类名字|
|cate_title |varchar     |255||||分类标题|
|cate_keywords|varchar   |255||||关键字  |
|cate_description|varchar|255||||描述    |
|cate_view  |int         |10 ||||点击量  |
|cate_order |tinyint	 |4  ||||排序    |
|cate_pid   |int 		 |11 ||||分类父id|
|created_at |timestamp   |0  ||||创建时间|
|updated_at |timestamp   |0  ||||修改时间|
|delete_at  |timestamp   |0  ||||删除时间|

####blog_links(友情链接)
|	名字	|	类型	| 长度 | 索引	 | 默认值	 |	自增	|  注释 |
|---|---| ---| --- | --- |---|---|
|link_id    |int unsigned|11 |PRI|NULL|auto_increment|友情链接id|
|link_name  |varchar     |255||||友情链接名字|
|link_title |varchar     |255||||友情链接标题|
|link_url   |varchar     |255||||链接url    |
|link_order |int		 |11 ||||排序 	    |
|created_at |timestamp   |0  ||||创建时间    |
|updated_at |timestamp   |0  ||||修改时间    |
|delete_at  |timestamp   |0  ||||删除时间    |


####blog_navs(导航)
|	名字	|	类型	| 长度 | 索引	 | 默认值	 |	自增	|  注释 |
|---|---| ---| --- | --- |---|---|
|nav _id    |int unsigned|11 |PRI|NULL|auto_increment|导航id|
|nav_name   |varchar     |50 ||||导航名字|
|nav_alias  |varchar     |50 ||||导航描述|
|nav_url    |varchar     |255||||导航路由|
|nav_pid	|int		 |11 ||||父id   |
|nav_order  |int		 |11 ||||排序    |
|created_at |timestamp   |0  ||||创建时间|
|updated_at |timestamp   |0  ||||修改时间|
|delete_at  |timestamp   |0  ||||删除时间|



