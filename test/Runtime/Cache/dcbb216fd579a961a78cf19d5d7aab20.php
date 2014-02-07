<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>平滑性测试，上传文件</title>
</head>
<body>
上传图片，在SAE上会使用storage服务，请先建立一个名为public的domain
<form action="" method="post" enctype="multipart/form-data">
  <input type="file" name="files[]"/>
  <input name="" value="上传" type="submit" />
</form>
<?php if(!empty($filename)): ?><img src="__PUBLIC__/upload/<?php echo ($filename); ?>" /> <a href="__URL__/unlink/filename/<?php echo ($filename); ?>">删除图片</a><?php endif; ?>

</body>
</html>