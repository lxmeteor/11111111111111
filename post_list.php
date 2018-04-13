<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
  <link rel="stylesheet" href="/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
  <script src="/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    <?php 
      include_once '../common/checkSession.php';
      include_once '../common/nav.php';
      include_once '../common/mysql.php';

      //获取分类名称
      $sql = "select * from ali_cate";
      $cate_res = mysql_query($sql);

      //根据条件查询
      $cateid = isset($_GET['cateid'])?$_GET['cateid']:0;
      $state = isset($_GET['state'])?$_GET['state']:0;

      $where  = '';
      if($cateid !=0 ){
        $where .="post_cateid = $cateid and ";
      }
      if($state !=0 ){
        $where .="post_state = $state and ";
      }
      $where .='1';

      //设置分页
      $pageno = isset($_GET['pageno'])?$_GET['pageno']:1;
      $pagesize = 3;
      $offset = ($pageno-1)* $pagesize;
      
      //构建sql语句
      $sql1 = "select post_id,post_title,user_nickname,cate_name,post_updtime,post_state 
      from ali_post as p
      join ali_user as u on p.post_author = u.user_id
      join ali_cate as c on p.post_cateid = c.cate_id
      where $where
      limit $offset,$pagesize";

      $res = mysql_query($sql1);

      //查询总条数
      $sql2 = "select count(*) as num 
      from ali_post as p
      join ali_user as u on p.post_author = u.user_id
      join ali_cate as c on p.post_cateid = c.cate_id
      where $where";

      $pages = ceil((mysql_fetch_assoc(mysql_query($sql2))['num'])/$pagesize);
      

     ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>所有文章</h1>
        <a href="post-add.html" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <div class="page-action">
        <!-- show when multiple checked -->
        <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
        <form class="form-inline" action="" method="get">
          <select name="cateid" class="form-control input-sm">
            <option value="">所有分类</option>
            <?php while($row = mysql_fetch_assoc($cate_res)): ?>
              
              <option value="<?=$row['cate_id'] ?>" 
                    
                  <?=$row['cate_id']==$cateid?'selected':'' ?>
              >

                <?=$row['cate_name'] ?>

              </option>
            <?php endwhile; ?>
          </select>
          <select name="state" class="form-control input-sm">
            <option value="0" <?=$state==0?'selected':'' ?>>所有状态</option>
            <option value="2" <?=$state==2?'selected':'' ?>>草稿</option>
            <option value="1" <?=$state==1?'selected':'' ?>>已发布</option>
          </select>
          <input type="submit" value="筛选" class="btn btn-default btn-sm">
        </form>
        <ul class="pagination pagination-sm pull-right">
          <li><a href="post_list.php?cateid=<?=$cateid ?>&state=<?=$state ?>&pageno=1">首页</a></li>
          <li><a href="#">上一页</a></li>
          <li><a href="post_list.php?cateid=<?=$cateid ?>&state=<?=$state ?>&pageno=<?=$pageno ?>"><?=$pageno ?></a></li>
          <li><a href="#">下一页</a></li>
          <li><a href="post_list.php?cateid=<?=$cateid ?>&state=<?=$state ?>&pageno=<?=$pages ?>">尾页</a></li>
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = mysql_fetch_assoc($res)): ?>
          <tr>
            <td class="text-center"><input type="checkbox"></td>
            <td><?=$row['post_title'] ?></td>
            <td><?=$row['user_nickname'] ?></td>
            <td><?=$row['cate_name'] ?></td>
            <td class="text-center"><?=date('Y/m/d',$row['post_updtime']) ?></td>
            <td class="text-center"><?=$row['post_state']==1?'已发布':'草稿' ?></td>
            <td class="text-center">
              <a href="post_edit.php?id=<?=$row['post_id'];?>" class="btn btn-default btn-xs">编辑</a>
              <a href="javascript:;" data-id="<?=$row['post_id'] ?>" class="del btn btn-danger btn-xs">删除</a>
            </td>
          </tr>
          <?php endwhile; ?>
         
        </tbody>
      </table>
    </div>
  </div>

  <div class="aside">
   <?php include_once '../common/aside.php' ?>
  </div>

  <script src="/assets/vendors/jquery/jquery.js"></script>
  <script src="/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
</body>
</html>
