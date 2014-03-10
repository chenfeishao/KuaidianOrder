<?php if (!defined('THINK_PATH')) exit(); if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
   		<td style="width: 50px;"><?php echo ($i); ?>
   		</td>
   		<td class="span2"><?php echo ($vo["customName"]); ?></td>
   		<td class="right span5"><?php echo ($vo["goodsName"]); ?></td>
   		<td class="right span1"><?php echo ($vo["createDate"]); ?></td>
   		<td class="right span1"><?php echo ($vo["printDate"]); ?></td>
   		<td class="right span1"><?php echo ($vo["printState"]); ?></td>
		<td style="width: 100px;">
			<a href="<?php echo U("Order/historyOver");?>?no=<?php echo ($vo["id"]); ?>" class="button success">查看</a>
			<div onclick="goDelete('<?php echo U("Order/deleteTmpOrder");?>?no=<?php echo ($vo["id"]); ?>&t=<?php echo ($vo["tkey"]); ?>');" class="button warning">删除</div>
		</td>
	</tr><?php endforeach; endif; else: echo "" ;endif; ?>
<?php echo ($page); ?>